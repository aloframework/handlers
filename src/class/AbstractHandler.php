<?php

    namespace AloFramework\Handlers;

    use AloFramework\Handlers\Output\ConsoleOutput;
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
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *                                AloFramework\Log\Log extends this interface.
         *
         * @uses   AloFramework\Handlers\Output\ConsoleOutput::__construct()
         */
        function __construct(LoggerInterface $logger = null) {
            $this->logger       = $logger;
            $this->isCLI        = php_sapi_name() == 'cli' || defined('STDIN');
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
         * @uses   AbstractHandler::traceCLI()
         * @uses   AbstractHandler::traceHTML()
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
         *
         * @uses   AbstractHandler::formatTraceLine()
         * @uses   VarDumper::dump()
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
         *
         * @uses   AbstractHandler::formatTraceLine()
         * @uses   AloFramework\Handlers\Output\ConsoleOutput::write()
         * @uses   AloFramework\Handlers\Output\ConsoleOutput::writeln()
         * @uses   VarDumper::dump()
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
         *                                AloFramework\Log\Log extends this interface.
         *
         * @return array An array containing [ErrorHandler::register(), ExceptionHandler::register()]
         * @uses   ErrorHandler::register()
         * @uses   ExceptionHandler::register()
         */
        static function register(LoggerInterface $logger = null) {
            return [ErrorHandler::register($logger), ExceptionHandler::register($logger)];
        }

        /**
         * Returns a string representation of the object
         * @return string
         * @uses VarDumper::dump()
         */
        function __toString() {
            ob_start();
            VarDumper::dump($this);

            return ob_get_clean();
        }
    }
