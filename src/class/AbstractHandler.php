<?php

    namespace AloFramework\Handlers;

    use AloFramework\Common\Alo;
    use AloFramework\Config\Configurable;
    use AloFramework\Config\ConfigurableTrait;
    use AloFramework\Handlers\Config\AbstractConfig;
    use AloFramework\Handlers\Output\ConsoleOutput;
    use AloFramework\Log\Log;
    use Exception;
    use Psr\Log\LoggerInterface;
    use Symfony\Component\VarDumper\Cloner\VarCloner;
    use Symfony\Component\VarDumper\Dumper\CliDumper;
    use Symfony\Component\VarDumper\Dumper\HtmlDumper;

    /**
     * Abstract error/exception handling things
     * @author Art <a.molcanovas@gmail.com>
     * @codeCoverageIgnore
     * @since  1.4 Implements Configurable
     * @property AbstractConfig $config Abstract handler configuration
     */
    abstract class AbstractHandler implements Configurable {

        use ConfigurableTrait;

        /**
         * The line ender for __toString()
         * @var string
         */
        const EOL = " \n";
        /**
         * Whether CSS has been injected yet
         *
         * @var bool
         */
        private static $cssInjected = false;
        /**
         * Symfony's CLI dumper
         * @var CliDumper
         */
        private static $dumperCLI;
        /**
         * Symfony's HTML dumper
         * @var HtmlDumper
         */
        private static $dumperHTML;
        /**
         * Symfony's var cloner
         * @var VarCloner
         */
        private static $cloner;
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
         * @param AbstractConfig  $cfg    The configuration class. Required.
         *
         * @since  1.4 $cfg added. This will become the first parameter in the constructor in 2.0
         */
        function __construct(LoggerInterface $logger = null, AbstractConfig $cfg = null) {
            if (!$logger) {
                $logger = new Log();
            }

            $this->config = Alo::ifnull($cfg, new AbstractConfig());
            $this->logger = $logger;
            $this->isCLI  = !$this->config[AbstractConfig::CFG_FORCE_HTML] && Alo::isCliRequest();

            $this->initSymfony();
        }

        /**
         * Initialises Symfony's components
         * @author Art <a.molcanovas@gmail.com>
         * @return self
         */
        private function initSymfony() {
            if ($this->isCLI) {
                $this->console = new ConsoleOutput();
            }

            if (!self::$cloner || !self::$dumperCLI || !self::$dumperHTML) {
                self::$cloner     = new VarCloner();
                self::$dumperCLI  = new CliDumper();
                self::$dumperHTML = new HtmlDumper();
            }

            return $this;
        }

        /**
         * Registers the error and exception handlers. IMPORTANT: If you've extended the ErrorHandler or Exception
         * handler classes you must call their register() methods as this one would not register the correct handlers.
         * Due to method signature standards, this method only instantiates the error and exception handlers USING
         * THEIR DEFAULT SETTINGS
         * @author     Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         * @param AbstractConfig  $cfg    Your custom error configuration settings
         *
         * @return array An array containing [ErrorHandler::register(), ExceptionHandler::register()]. If the
         * ALO_HANDLERS_REGISTER_SHUTDOWN constant is set to true, it will also return it as the third [2] key.
         * @since      1.4 $cfg added, deprecated
         * @deprecated since 1.4. Use each handler's register() method separately instead.
         * @todo       Remove in 2.0
         */
        static function register(LoggerInterface $logger = null, $cfg = null) {
            if (!$logger) {
                $logger = new Log();
            }
            if (!$cfg) {
                $cfg = new AbstractConfig();
            }
            $r = [ErrorHandler::register($logger, null), ExceptionHandler::register($logger, null)];

            if ($cfg[AbstractConfig::CFG_REGISTER_SHUTDOWN_HANDLER]) {
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
                   ($this->maxTraceSize * -1);
        }

        /**
         * Injects the error handler CSS if it hasn't been injected yet
         * @author Art <a.molcanovas@gmail.com>
         */
        protected function injectCSS() {
            if (!$this->isCLI && !self::$cssInjected) {
                self::$cssInjected = true;
                if (file_exists($this->config[AbstractConfig::CFG_CSS_PATH])) {
                    echo '<style type="text/css">';
                    include $this->config[AbstractConfig::CFG_CSS_PATH];
                    echo '</style>';
                } else {
                    echo 'The AloFramework handlers\' CSS file could not be found: ' .
                         $this->config[AbstractConfig::CFG_CSS_PATH] . PHP_EOL;
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
                    $this->dump($v['args']);
                }

                $this->console->writeln('');
            }
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
         * Dumps a variable
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param mixed $var The variable
         */
        private function dump($var) {
            if ($this->isCLI) {
                try {
                    self::$dumperCLI->dump(self::$cloner->cloneVar($var));
                } catch (Exception $e) {

                }
            }
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
                            $this->dump($v['args']);
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
    }
