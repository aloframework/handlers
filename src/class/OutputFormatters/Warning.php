<?php

    namespace AloFramework\Handlers\OutputFormatters;

    use AloFramework\Handlers\Config\AbstractConfig;

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
            $this->formatter->setForeground((new AbstractConfig())->get(AbstractConfig::CFG_FOREGROUND_WARNING));
        }
    }
