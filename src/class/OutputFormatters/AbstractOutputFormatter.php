<?php

    namespace AloFramework\Handlers\OutputFormatters;

    use InvalidArgumentException;
    use Symfony\Component\Console\Formatter\OutputFormatterStyle;

    /**
     * Abstract version of the CLI output formatter
     * @author Art <a.molcanovas@gmail.com>
     */
    abstract class AbstractOutputFormatter {

        /**
         * Symfony's OutputFormatterStyle object
         * @var OutputFormatterStyle
         */
        protected $formatter;

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         * @uses   OutputFormatterStyle::__construct()
         * @uses   OutputFormatterStyle::setBackground()
         */
        function __construct() {
            $this->formatter = new OutputFormatterStyle();
            $this->formatter->setBackground(ALO_HANDLERS_BACKGROUND);
        }

        /**
         * Returns Symfony's OutputFormatterStyle object
         * @author Art <a.molcanovas@gmail.com>
         * @return OutputFormatterStyle
         */
        function getFormatter() {
            return $this->formatter;
        }

        /**
         * Static constructor
         * @author Art <a.molcanovas@gmail.com>
         * @return Error|Info|Warning
         * @uses   AbstractOutputFormatter::__construct()
         */
        static function construct() {
            $class = get_called_class();

            return new $class();
        }

        /**
         * Sets some specific style option.
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string $option The option name
         *
         * @throws InvalidArgumentException When the option name isn't defined
         *
         * @return AbstractOutputFormatter
         * @uses   OutputFormatterStyle::setOption()
         */
        function setOption($option) {
            $this->formatter->setOption($option);

            return $this;
        }
    }
