<?php

    namespace AloFramework\Handlers\OutputFormatters;

    /**
     * Output formatter for warnings
     * @author Art <a.molcanovas@gmail.com>
     */
    class Warning extends AbstractOutputFormatter {

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         * @uses   AbstractOutputFormatter::__construct()
         * @uses   \Symfony\Component\Console\Formatter\OutputFormatterStyle::setForeground()
         */
        function __construct() {
            parent::__construct();
            $this->formatter->setForeground(ALO_HANDLERS_FOREGROUND_WARNING);
        }
    }
