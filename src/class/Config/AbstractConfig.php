<?php

    namespace AloFramework\Handlers\Config;

    use AloFramework\Common\Alo;
    use AloFramework\Config\AbstractConfig as ACFG;

    /**
     * AbstractHandler configuration
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.4
     */
    class AbstractConfig extends ACFG {

        /**
         * [STR] The CSS path configuration key
         * @var string
         */
        const CFG_CSS_PATH = 'a.css';

        /**
         * [INT] Maximum debug backtrace depth configuration key
         * @var string
         */
        const CFG_TRACE_MAX_DEPTH = 'a.trace.dpt';

        /**
         * [STR] CLI background colour configuration key
         * @var string
         */
        const CFG_BACKGROUND = 'a.cli.bg';

        /**
         * [STR] CLI notice level foreground colour cofiguration key
         * @var string
         */
        const CFG_FOREGROUND_NOTICE = 'a.fg.info';

        /**
         * [STR] CLI warning level foreground colour cofiguration key
         * @var string
         */
        const CFG_FOREGROUND_WARNING = 'a.fg.warn';

        /**
         * [STR] CLI error level foreground colour cofiguration key
         * @var string
         */
        const CFG_FOREGROUND_ERROR = 'a.fg.err';

        /**
         * [BOOL] Whether to force HTML output regardless of whether the error is raised in CLI mode (configuration key)
         * @var string
         */
        const CFG_FORCE_HTML = 'a.html';

        /**
         * [BOOL] Whether to register the shutdown handler via AbstractHandler's register() method (configuration key)
         * @var string
         */
        const CFG_REGISTER_SHUTDOWN_HANDLER = 'a.shutdown';

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
        function __construct(array $defaults = [], array $cfg = []) {
            self::setDefaults();
            parent::__construct(array_merge(self::$defaults, $defaults), $cfg);
        }

        /**
         * Sets default config
         * @author Art <a.molcanovas@gmail.com>
         */
        private static function setDefaults() {
            if (!self::$defaults) {
                self::$defaults = [self::CFG_CSS_PATH                  => Alo::ifundefined('ALO_HANDLERS_CSS_PATH',
                                                                                           __DIR__ .
                                                                                           DIRECTORY_SEPARATOR . '..' .
                                                                                           DIRECTORY_SEPARATOR . '..' .
                                                                                           DIRECTORY_SEPARATOR . 'error.min
                                                                                .css'),
                                   self::CFG_TRACE_MAX_DEPTH           => Alo::ifundefined('ALO_HANDLERS_TRACE_MAX_DEPTH',
                                                                                           50),
                                   self::CFG_BACKGROUND                => Alo::ifundefined('ALO_HANDLERS_BACKGROUND',
                                                                                           'default'),
                                   self::CFG_FOREGROUND_NOTICE         => Alo::ifundefined('ALO_HANDLERS_FOREGROUND_NOTICE',
                                                                                           'cyan'),
                                   self::CFG_FOREGROUND_WARNING        => Alo::ifundefined('ALO_HANDLERS_FOREGROUND_WARNING',
                                                                                           'yellow'),
                                   self::CFG_FOREGROUND_ERROR          => Alo::ifundefined('ALO_HANDLERS_FOREGROUND_ERROR',
                                                                                           'red'),
                                   self::CFG_FORCE_HTML                => Alo::ifundefined('ALO_HANDLERS_FORCE_HTML',
                                                                                           false),
                                   self::CFG_REGISTER_SHUTDOWN_HANDLER => Alo::ifundefined('ALO_HANDLERS_REGISTER_SHUTDOWN',
                                                                                           false)];
            }
        }
    }
