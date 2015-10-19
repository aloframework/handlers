<?php

    namespace AloFramework\Handlers\Output\Styles;

    use AloFramework\Handlers\Config\AbstractConfig;
    use Symfony\Component\Console\Formatter\OutputFormatterStyle;
    use Symfony\Component\Console\Formatter\OutputFormatterStyleInterface;

    /**
     * Abstract version of the CLI output formatter. See Symfony\Component\Console\Formatter\OutputFormatterStyle for
     * descriptions of the setters.
     * @author Art <a.molcanovas@gmail.com>
     */
    abstract class AbstractStyle implements OutputFormatterStyleInterface {

        /**
         * Config instance
         * @var AbstractConfig
         */
        protected $cfg;

        /**
         * Symfony's OutputFormatterStyle - can't extend because of their use of static:: as opposed to self::
         * @var OutputFormatterStyle
         */
        private $symfony;

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string|array $setOption Optionally set a formatter style option or array of options
         */
        function __construct($setOption = null) {
            $this->symfony = new OutputFormatterStyle();
            $this->cfg     = new AbstractConfig();
            $this->setBackground($this->cfg->bgCli);

            if ($setOption) {
                // nothing to test
                // @codeCoverageIgnoreStart
                if (is_array($setOption)) {
                    $this->setOptions($setOption);
                } else {
                    $this->setOption($setOption);
                }
                // @codeCoverageIgnoreEnd
            }
        }

        /**
         * Sets the background colour
         * @author             Art <a.molcanovas@gmail.com>
         *
         * @param string $bg The background colour
         *
         * @codeCoverageIgnore - nothing to test.
         */
        function setBackground($bg = null) {
            $this->symfony->setBackground($bg);
        }

        /**
         * Sets multiple options
         * @author             Art <a.molcanovas@gmail.com>
         *
         * @param array $opt The options
         *
         * @codeCoverageIgnore - nothing to test.
         */
        function setOptions(array $opt) {
            $this->symfony->setoptions($opt);
        }

        /**
         * Sets an option
         * @author             Art <a.molcanovas@gmail.com>
         *
         * @param string $opt The option
         *
         * @codeCoverageIgnore - nothing to test.
         */
        function setOption($opt) {
            $this->symfony->setOption($opt);
        }

        /**
         * Sets the foreground colour
         * @author             Art <a.molcanovas@gmail.com>
         *
         * @param string $fg The foreground colour
         *
         * @codeCoverageIgnore - nothing to test.
         */
        function setForeground($fg = null) {
            $this->symfony->setForeground($fg);
        }

        /**
         * Unsets some specific style option.
         * @author             Art <a.molcanovas@gmail.com>
         *
         * @param string $option The option name
         *
         * @codeCoverageIgnore - nothing to test.
         */
        function unsetOption($option) {
            $this->symfony->unsetOption($option);
        }

        /**
         * Applies the style to a given text.
         *
         * @param string $text The text to style
         *
         * @return string
         *
         * @codeCoverageIgnore - nothing to test.
         */
        function apply($text) {
            return $this->symfony->apply($text);
        }

    }
