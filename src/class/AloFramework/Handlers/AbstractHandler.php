<?php

    namespace AloFramework\Handlers;

    use AloFramework\Handlers\OutputFormatters\Bold;
    use AloFramework\Handlers\OutputFormatters\AbstractOutputFormatter;
    use Psr\Log\LoggerInterface;
    use Symfony\Component\VarDumper\VarDumper;
    use AloFramework\Handlers\Output\ConsoleOutput;

    /**
     * Abstract error/exception handling things
     * @author Art <a.molcanovas@gmail.com>
     */
    abstract class AbstractHandler {

        /**
         * Whether CSS has been injected yet
         *
         * @var bool
         */
        private static $cssInjected = false;

        /**
         * Logger instance
         * @var LoggerInterface
         */
        protected $logger;

        /**
         * Whether we're dealing with a command-line request
         * @var bool
         */
        protected $isCLI;

        /**
         * ConsoleOutput object
         * @var ConsoleOutput
         */
        protected $console;

        /**
         * Defines error output as "warning"
         * @var string
         */
        const LABEL_WARNING = 'warning';

        /**
         * Defines error output as "info"
         * @var string
         */
        const LABEL_INFO = 'info';

        /**
         * Defines error output as "danger"
         * @var string
         */
        const LABEL_DANGER = 'danger';

        /**
         * How to colour output depending on error severity
         * @var array
         */
        protected static $labelColours = [self::LABEL_WARNING => 'yellow',
                                          self::LABEL_INFO    => 'cyan',
                                          self::LABEL_DANGER  => 'red'];

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *                                AloFramework\Log\Log extends this interface.
         */
        function __construct(LoggerInterface $logger = null) {
            $this->logger = $logger;
            $this->isCLI  = php_sapi_name() == 'cli' || defined('STDIN');

            if ($this->isCLI) {
                $this->initConsole();
            }
        }

        /**
         * Initialises the console and sets its styles
         * @author Art <a.molcanovas@gmail.com>
         */
        private function initConsole() {
            $this->console = new ConsoleOutput();
            $formatter     = $this->console->getFormatter();

            $styles = ['bold' => new Bold()];

            /** @var AbstractOutputFormatter $object */
            foreach ($styles as $label => $object) {
                $formatter->setStyle($label, $object->getFormatter());
            }
        }

        /**
         * Injects the error handler CSS if it hasn't been injected yet
         * @author Art <a.molcanovas@gmail.com>
         */
        protected function injectCSS() {
            if (!$this->isCLI && !self::$cssInjected) {
                self::$cssInjected = true;
                if (file_exists(ALO_HANDLERS_CSS_PATH)) {
                    echo '<style type="text/css">';
                    include ALO_HANDLERS_CSS_PATH;
                    echo '</style>';
                } else {
                    echo 'The AloFramework handlers\' CSS file could not be found: ' . ALO_HANDLERS_CSS_PATH . PHP_EOL;
                }
            }
        }

        /**
         * Echoes the debug backtrace
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $trace The debug backtrace
         */
        protected function echoTrace($trace) {
            if ($this->isCLI) {
                $this->traceCLI($trace);
            } else {
                $this->traceHTML($trace);
            }
        }

        /**
         * Echoes a HTML debug backtrace
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $trace The debug backtrace
         */
        private function traceHTML($trace) {
            echo '<table class="table" border="1">'//BEGIN table
                 . '<thead>'//BEGIN head
                 . '<tr>'//BEGIN head row
                 . '<th>#</th>'//Trace number
                 . '<th>Method</th>'//Method used
                 . '<th>Args</th>'//Method args
                 . '<th>Location</th>'//File
                 . '<th>Line</th>'//Line of code
                 . '</tr>'//END head row
                 . '</thead>'//END head
                 . '<tbody>'; //BEGIN table

            foreach ($trace as $k => $v) {
                $func = $loc = $line = '';

                if (isset($v['class'])) {
                    $func = $v['class'];
                }
                if (isset($v['type'])) {
                    $func .= $v['type'];
                }
                if (isset($v['function'])) {
                    $func .= $v['function'] . '()';
                }
                if (!$func) {
                    $func = '[unknown]';
                }

                if (isset($v['file'])) {
                    $loc = implode(DIRECTORY_SEPARATOR, array_slice(explode(DIRECTORY_SEPARATOR, $v['file']), -2));
                }
                if (isset($v['line'])) {
                    $line .= $v['line'];
                }

                echo '<tr>'
                     //BEGIN row
                     .
                     '<td>' .
                     $k .
                     '</td>'
                     //Trace #
                     .
                     '<td>' .
                     $func .
                     '</td>'
                     //Method used
                     .
                     '<td>' .
                     //BEGIN args
                     (isset($v['args']) && !empty($v['args']) ? VarDumper::dump($v['args']) :
                         '<span class="label label-default">NONE</span>') .
                     '</td>'
                     //END args
                     .
                     '<td>'
                     //BEGIN location
                     .
                     ($loc ? $loc : '<span class="label label-default">???</span>') .
                     '</td>'
                     //END location
                     .
                     '<td>'
                     //BEGIN line
                     .
                     ($line || $line == '0' ? $line : '<span class="label label-default">???</span>') .
                     '</td>'
                     //END line
                     .
                     '</tr>';
            }

            echo '</tbody>' . '</table>';
        }

        private function traceCLI($trace) {
            foreach ($trace as $t) {
                $this->console->write('<fg=red>' . $t['file'] . '</>');
            }
        }

        /**
         * Logs a message if the logger is enabled
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param int|string $errcode The error/exception code
         * @param string     $msg     The error or exception message
         */
        protected function log($errcode, $msg) {
            if ($this->logger) {
                $this->logger->error('[' . $errcode . '] ' . $msg);
            }
        }

        /**
         * Registers the error and exception handlers
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *                                AloFramework\Log\Log extends this interface.
         *
         * @return array An array containing [ErrorHandler::register(), ExceptionHandler::register()]
         */
        static function register(LoggerInterface $logger = null) {
            return [ErrorHandler::register($logger), ExceptionHandler::register($logger)];
        }

        /**
         * Returns a string representation of the object
         * @return string
         */
        function __toString() {
            ob_start();
            VarDumper::dump($this);

            return ob_get_clean();
        }
    }
