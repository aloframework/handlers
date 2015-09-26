<?php

    namespace AloFramework\Handlers\OutputFormatters;

    /**
     * Output formatter for notices
     * @author Art <a.molcanovas@gmail.com>
     */
    class Info extends AbstractOutputFormatter {

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         */
        function __construct() {
            parent::__construct();
            $this->formatter->setForeground(ALO_HANDLERS_FOREGROUND_NOTICE);
        }
    }
