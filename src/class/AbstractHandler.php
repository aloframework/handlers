<?php

    namespace AloFramework\Handlers;

    use AloFramework\Common\Alo;
    use AloFramework\Config\Configurable;
    use AloFramework\Config\ConfigurableTrait;
    use AloFramework\Handlers\Config\AbstractConfig;
    use AloFramework\Handlers\Output\ConsoleOutput;
    use AloFramework\Log\Log;
    use AloFramework\Handlers\Output\Dump;
    use Psr\Log\LoggerInterface;
    use Symfony\Component\VarDumper\VarDumper;

    /**
     * Abstract error/exception handling things
     * @author Art <a.molcanovas@gmail.com>
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
            $this->isCLI  = !$this->config->forceHTML && Alo::isCliRequest();

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

            return $this;
        }

        /**
         * Returns a string representation of the object
         * @author Art <a.molcanovas@gmail.com>
         * @return string
         */
        function __toString() {
            return 'CSS injected: ' . (self::$cssInjected ? 'Yes' : 'No') . self::EOL . 'Logger: ' .
                   ($this->logger ? get_class($this->logger) : 'Not set') . self::EOL . 'Max stack trace size: ' .
                   ($this->config->traceDepth);
        }

        /**
         * Injects the error handler CSS if it hasn't been injected yet
         * @author Art <a.molcanovas@gmail.com>
         */
        protected function injectCSS() {
            if (!$this->isCLI && !self::$cssInjected) {
                self::$cssInjected = true;
                if (file_exists($this->config->cssPath)) {
                    echo '<style type="text/css">';
                    include $this->config->cssPath;
                    echo '</style>';
                } else {
                    echo 'The AloFramework handlers\' CSS file could not be found: ' . $this->config->cssPath . PHP_EOL;
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
            $trace = array_slice($trace, $this->config->traceDepth * -1);

            // @codeCoverageIgnoreStart
            if ($this->isCLI) {
                $this->traceCLI($trace, $label);
            } else {
                // @codeCoverageIgnoreEnd
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
         *
         * @codeCoverageIgnore
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
         * Formats the debug backtrace row
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $traceLine The row
         * @param mixed $method    Reference to the variable which will contain the formatted method
         * @param mixed $file      Reference to the variable which will contain the formatted file location
         * @param mixed $line      Reference to the variable which will contain the formatted line
         *
         * @codeCoverageIgnore
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
         * Echoes a HTML debug backtrace
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $trace The debug backtrace
         *
         * @codeCoverageIgnore
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
                            $args = Dump::html($v['args']);
                        } else {
                            $args = '<span class="label label-default">[none]</span>';
                        }

                        ?>
                        <tr>
                            <td><?= $k ?></td>
                            <td><?= $func ?></td>
                            <td class="text-center"><?= $args ?></td>
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
