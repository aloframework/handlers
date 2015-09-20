<?php

    namespace AloFramework\Handlers;

    use AloFramework\Handlers\Output\ConsoleOutput;
    use Psr\Log\LoggerInterface;
    use Symfony\Component\VarDumper\VarDumper;

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
         * Maximum debug backtrace size
         * @var int
         */
        private $maxTraceSize;

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *                                AloFramework\Log\Log extends this interface.
         */
        function __construct(LoggerInterface $logger = null) {
            $this->logger       = $logger;
            $this->isCLI        = php_sapi_name() == 'cli' || defined('STDIN');
            $this->maxTraceSize = (int)ALO_HANDLERS_TRACE_MAX_DEPTH;

            if ($this->isCLI) {
                $this->console = new ConsoleOutput();
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
         * Returns the formatted debug backtrace
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array  $trace The debug backtrace
         * @param string $label Trace label style
         *
         * @return string
         */
        protected function getTrace($trace, $label) {
            ob_start();
            array_pop($trace);

            if ($this->isCLI) {
                $this->traceCLI($trace, $label);
            } else {
                $this->traceHTML($trace);
            }

            return ob_get_clean();
        }

        /**
         * Echoes a HTML debug backtrace
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $trace The debug backtrace
         */
        private function traceHTML(array $trace) {
            ?>
            <table class="table" border="1">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Method</th>
                    <th>Args</th>
                    <th>File</th>
                    <th>Line</th>
                </tr>
                </thead>
                <tbody><?php
                    foreach ($trace as $k => $v) {
                        $func = $loc = $line = '';
                        self::formatTraceLine($v, $func, $loc, $line);

                        if (isset($v['args']) && !empty($v['args'])) {
                            ob_start();
                            VarDumper::dump($v['args']);
                            $args = ob_get_clean();
                        } else {
                            $args = '[none]';
                        }

                        ?>
                        <tr>
                            <td><?= $k ?></td>
                            <td><?= $func ?></td>
                            <td><?= $args ?></td>
                            <td><?= $loc ? $loc : '<span class="label label-default">???</label>' ?></td>
                            <td><?= ($line == 0 || trim($line)) ? $line :
                                    '<span class="label label-default">???</span>' ?></td>
                        </tr>

                        <?php
                    }
                ?>
                </tbody>
            </table>
            <?php
        }

        /**
         * Formats the debug backtrace row
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $traceLine The row
         * @param mixed $method    Reference to the variable which will contain the formatted method
         * @param mixed $file      Reference to the variable which will contain the formatted file location
         * @param mixed $line      Reference to the variable which will contain the formatted line
         */
        private static function formatTraceLine(array $traceLine, &$method, &$file, &$line) {
            $method = $file = $line = '';

            if (isset($traceLine['class'])) {
                $method = $traceLine['class'];
            }
            if (isset($traceLine['type'])) {
                $method .= $traceLine['type'];
            }
            if (isset($traceLine['function'])) {
                $method .= $traceLine['function'] . '()';
            }
            if (!$method) {
                $method = '[unknown]';
            }

            if (isset($traceLine['file'])) {
                $file = '[...]' . implode(DIRECTORY_SEPARATOR,
                                          array_slice(explode(DIRECTORY_SEPARATOR,
                                                              $traceLine['file']),
                                                      -4));
            }

            if (array_key_exists('line', $traceLine)) {
                $line = $traceLine['line'];
            }
        }

        /**
         * CLI output of the debug backtrace
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array  $trace The debug backtrace
         * @param string $label Colour id
         */
        private function traceCLI(array $trace, $label) {
            foreach ($trace as $k => $v) {
                $func        = $loc = $line = '';
                $argsPresent = isset($v['args']) && !empty($v['args']);

                self::formatTraceLine($v, $func, $loc, $line);

                $this->console->write('<' . $label . 'b>#' . $k . ': </>')
                    ->write('<' . $label . '>' . ($loc ? $loc : '<<unknown file>>') . '</> ')
                    ->write('<' . $label . '>(' . ($line ? 'line ' . $line : 'unknown line') . ')</>')
                    ->write('<' . $label . '> | </>')
                    ->write('<' . $label . '>' . $func . '</>', true);

                if ($argsPresent) {
                    $this->console->write('<' . $label . 'b>Arguments:</>', true);
                    VarDumper::dump($v['args']);
                }

                $this->console->writeln('');
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
