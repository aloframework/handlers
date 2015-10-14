<?php

    namespace AloFramework\Handlers\Config;

    /**
     * Error config class
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.4.1 property PHPdocs added
     * @property bool $logErrorLocation Whether to include the error location in the log file
     * @property int  $errorLevel       Which error levels to log
     */
    class ErrorConfig extends AbstractConfig {

        /**
         * [INT] Which errors to log configuration key
         * @var string
         */
        const CFG_ERROR_LEVEL = 'errorLevel';

        /**
         * [BOOL] Whether to log where the error occurred configuration key
         * @var string
         */
        const CFG_LOG_ERROR_LOCATION = 'logErrorLocation';

        /**
         * Default config array
         * @var array
         */
        private static $defaults;

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $cfg Default configuration overrides
         */
        function __construct(array $cfg = []) {
            self::setDefaultConfig();
            parent::__construct(self::$defaults, $cfg);
        }

        /**
         * Sets the default configuration
         * @author Art <a.molcanovas@gmail.com>
         */
        private static function setDefaultConfig() {
            if (!self::$defaults) {
                self::$defaults = [self::CFG_ERROR_LEVEL        => error_reporting(),
                                   self::CFG_LOG_ERROR_LOCATION => true];
            }
        }
    }
