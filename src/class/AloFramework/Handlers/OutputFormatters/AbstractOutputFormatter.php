<?php

    namespace AloFramework\Handlers\OutputFormatters;

    use Symfony\Component\Console\Formatter\OutputFormatterStyle;

    abstract class AbstractOutputFormatter {

        /**
         * Symfony's OutputFormatterStyle object
         * @var OutputFormatterStyle
         */
        protected $formatter;

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
    }
