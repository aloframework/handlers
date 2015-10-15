<?php

    namespace AloFramework\Handlers\Output\Styles;

    use AloFramework\Handlers\Config\AbstractConfig;
    use Symfony\Component\Console\Formatter\OutputFormatterStyle;

    /**
     * Abstract version of the CLI output formatter
     * @author Art <a.molcanovas@gmail.com>
     */
    abstract class AbstractStyle extends OutputFormatterStyle {

        /**
         * Config instance
         * @var AbstractConfig
         */
        protected $cfg;

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string|array $setOption Optionally set a formatter style option or array of options
         */
        function __construct($setOption = null) {
            parent::__construct();
            $this->cfg = new AbstractConfig();
            $this->setBackground($this->cfg->bgCli);

            if ($setOption) {
                if (is_array($setOption)) {
                    $this->setOptions($setOption);
                } else {
                    $this->setOption($setOption);
                }
            }
        }
    }
