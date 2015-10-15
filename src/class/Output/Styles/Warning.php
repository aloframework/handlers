<?php

    namespace AloFramework\Handlers\Output\Styles;

    /**
     * Output formatter for warnings
     * @author Art <a.molcanovas@gmail.com>
     */
    class Warning extends AbstractStyle {

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string|array $setOption Optionally set a formatter style option or array of options
         */
        function __construct($setOption = null) {
            parent::__construct($setOption);
            $this->setForeground($this->cfg->fgWarn);
        }
    }
