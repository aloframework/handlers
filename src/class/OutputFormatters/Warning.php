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
         */
        function __construct() {
            parent::__construct();
            $this->formatter->setForeground(ALO_HANDLERS_FOREGROUND_WARNING);
        }
    }
