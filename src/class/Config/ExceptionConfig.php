<?php

    namespace AloFramework\Handlers\Config;

    use AloFramework\Common\Alo;

    /**
     * Error config class
     * @author Art <a.molcanovas@gmail.com>
     */
    class ExceptionConfig extends AbstractConfig {

        /**
         * [INT] How many previous exceptions to echo configuration key
         * @var string
         */
        const CFG_EXCEPTION_DEPTH = 'ex.prev-dpt';

        /**
         * [BOOL] Whether to log where the exception occurred configuration key
         * @var string
         */
        const CFG_LOG_EXCEPTION_LOCATION = 'ex.log';

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
                self::$defaults = [self::CFG_EXCEPTION_DEPTH        => Alo::ifundefined('ALO_HANDLERS_EXCEPTION_DEPTH',
                                                                                        10),
                                   self::CFG_LOG_EXCEPTION_LOCATION => Alo::ifundefined('ALO_HANDLERS_LOG_EXCEPTION_LOCATION',
                                                                                        true)];
            }
        }
    }
