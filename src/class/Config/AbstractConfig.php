<?php

    namespace AloFramework\Handlers\Config;

    use AloFramework\Common\Alo;
    use AloFramework\Config\AbstractConfig as ACFG;

    /**
     * AbstractHandler configuration
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.4
     */
    abstract class AbstractConfig extends ACFG {

        /**
         * [STR] The CSS path configuration key
         * @var int
         */
        const CFG_CSS_PATH = 101;

        /**
         * [INT] Maximum debug backtrace depth configuration key
         * @var int
         */
        const CFG_TRACE_MAX_DEPTH = 102;

        /**
         * [STR] CLI background colour configuration key
         * @var int
         */
        const CFG_BACKGROUND = 103;

        /**
         * [STR] CLI notice level foreground colour cofiguration key
         * @var int
         */
        const CFG_FOREGROUND_NOTICE = 104;

        /**
         * [STR] CLI warning level foreground colour cofiguration key
         * @var int
         */
        const CFG_FOREGROUND_WARNING = 105;

        /**
         * [STR] CLI error level foreground colour cofiguration key
         * @var int
         */
        const CFG_FOREGROUND_ERROR = 106;

        /**
         * [BOOL] Whether to force HTML output regardless of whether the error is raised in CLI mode (configuration key)
         * @var int
         */
        const CFG_FORCE_HTML = 107;

        /**
         * [BOOL] Whether to register the shutdown handler via AbstractHandler's register() method (configuration key)
         * @var int
         */
        const CFG_REGISTER_SHUTDOWN_HANDLER = 108;

        /**
         * Default configuration array
         * @var array
         */
        private static $defaults;

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array $defaults The default config of extending classes
         * @param array $cfg      Default configuration overrides
         */
        function __construct(array $defaults, array $cfg = []) {
            if (!self::$defaults) {
                self::$defaults = [self::CFG_CSS_PATH           => Alo::ifundefined('ALO_HANDLERS_CSS_PATH',
                                                                                    __DIR__ . DIRECTORY_SEPARATOR .
                                                                                    '..' . DIRECTORY_SEPARATOR . '..' .
                                                                                    DIRECTORY_SEPARATOR . 'error.min
                                                                                .css'),
                                   self::CFG_TRACE_MAX_DEPTH    => Alo::ifundefined('ALO_HANDLERS_TRACE_MAX_DEPTH', 50),
                                   self::CFG_BACKGROUND         => Alo::ifundefined('ALO_HANDLERS_BACKGROUND',
                                                                                    'default'),
                                   self::CFG_FOREGROUND_NOTICE  => Alo::ifundefined('ALO_HANDLERS_FOREGROUND_NOTICE',
                                                                                    'cyan'),
                                   self::CFG_FOREGROUND_WARNING => Alo::ifundefined('ALO_HANDLERS_FOREGROUND_WARNING',
                                                                                    'yellow'),
                                   self::CFG_FOREGROUND_ERROR   => Alo::ifundefined('ALO_HANDLERS_FOREGROUND_ERROR',
                                                                                    'red'),
                                   self::CFG_FORCE_HTML         => Alo::ifundefined('ALO_HANDLERS_FORCE_HTML', false)];
            }

            parent::__construct(array_merge(self::$defaults, $defaults), $cfg);
        }
    }
