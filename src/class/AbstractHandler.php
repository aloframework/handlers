<?php

    namespace AloFramework\Handlers;

    use AloFramework\Handlers\Output\ConsoleOutput;
    use AloFramework\Log\Log;
    use Psr\Log\LoggerInterface;
    use Symfony\Component\VarDumper\VarDumper;

    require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.default.php';

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
         * Maximum debug backtrace size
         * @var int
         */
        private $maxTraceSize;

        /**
         * The line ender for __toString()
         * @var string
         */
        const EOL = " \n";

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         */
        function __construct(LoggerInterface $logger = null) {
            if (!$logger) {
                $logger = new Log();
            }
            $this->logger       = $logger;
            $this->isCLI        = PHP_SAPI == 'cli' || defined('STDIN');
            $this->maxTraceSize = ((int)ALO_HANDLERS_TRACE_MAX_DEPTH) * -1;

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
            $trace = array_slice($trace, $this->maxTraceSize);

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
         * Registers the error and exception handlers. IMPORTANT: If you've extended the ErrorHandler or Exception
         * handler classes you must call their register() methods as this one would not register the correct handlers.
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *
         * @return array An array containing [ErrorHandler::register(), ExceptionHandler::register()]. If the
         * ALO_HANDLERS_REGISTER_SHUTDOWN constant is set to true, it will also return it as the third [2] key.
         */
        static function register(LoggerInterface $logger = null) {
            if (!$logger) {
                $logger = new Log();
            }
            $r = [ErrorHandler::register($logger), ExceptionHandler::register($logger)];

            if (ALO_HANDLERS_REGISTER_SHUTDOWN) {
                $r[] = ShutdownHandler::register($logger);
            }

            return $r;
        }

        /**
         * Returns a string representation of the object
         * @author Art <a.molcanovas@gmail.com>
         * @return string
         */
        function __toString() {
            return 'CSS injected: ' . (self::$cssInjected ? 'Yes' : 'No') . self::EOL . 'Logger: ' .
                   ($this->logger ? get_class($this->logger) : 'Not set') . self::EOL . 'Max stack trace size: ' .
                   $this->maxTraceSize;
        }
    }
