<?php

    namespace AloFramework\Handlers\Config;

    use AloFramework\Common\Alo;

    /**
     * Error config class
     * @author Art <a.molcanovas@gmail.com>
     */
    class ErrorConfig extends AbstractConfig {

        /**
         * [INT] Which errors to log configuration key
         * @var int
         */
        const CFG_ERROR_LEVEL = 201;

        /**
         * [BOOL] Whether to log where the error occurred configuration key
         * @var int
         */
        const CFG_LOG_ERROR_LOCATION = 202;

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
                self::$defaults =
                    [self::CFG_ERROR_LEVEL        => Alo::ifundefined('ALO_HANDLERS_ERROR_LEVEL', error_reporting()),
                     self::CFG_LOG_ERROR_LOCATION => Alo::ifundefined('ALO_HANDLERS_LOG_ERROR_LOCATION', true)];
            }
        }
    }
