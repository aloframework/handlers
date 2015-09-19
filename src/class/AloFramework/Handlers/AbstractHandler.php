<?php

    namespace AloFramework\Handlers;

    use Psr\Log\LoggerInterface;
    use Kint;

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
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *                                AloFramework\Log\Log extends this interface.
         */
        function __construct(LoggerInterface $logger = null) {
            $this->logger = $logger;
        }

        /**
         * Injects the error handler CSS if it hasn't been injected yet
         * @author Art <a.molcanovas@gmail.com>
         */
        protected static function injectCSS() {
            if (!self::$cssInjected) {
                if (file_exists(ALO_HANDLERS_CSS_PATH)) {
                    self::$cssInjected = true;
                    echo '<style type="text/css">';
                    include ALO_HANDLERS_CSS_PATH;
                    echo '</style>';
                } else {
                    echo 'The AloFramework handlers\' CSS file could not be found: ' . ALO_HANDLERS_CSS_PATH . PHP_EOL;
                }
            }
        }

        /**
         * Echoes a debug output
         * @author Art <a.molcanovas@gmail.com>
         * @return string
         */
        protected function debugLite() {
            ob_start();
            $argv = func_get_args();
            if (!Kint::enabled()) {
                echo 'The Kint module is not enabled! Debugging output will be more difficult to read' . PHP_EOL;
                echo '<pre>';
                foreach ($argv as $k => $v) {
                    echo $k . '=>' . PHP_EOL;
                    var_dump($v);
                    echo PHP_EOL . PHP_EOL;
                }
            } else {
                echo '<pre>';
                foreach ($argv as $k => $v) {
                    $k && print(PHP_EOL . PHP_EOL);
                    echo s($v);
                }

            }

            echo '</pre>' . PHP_EOL;

            return ob_get_clean();
        }

        /**
         * Echoes the debug backtrace
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $trace The debug backtrace
         */
        protected static function echoTrace($trace) {
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
                     (isset($v['args']) && !empty($v['args']) ? self::debugLite($v['args']) :
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
    }
