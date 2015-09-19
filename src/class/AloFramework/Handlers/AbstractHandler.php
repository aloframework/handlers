<?php

    namespace AloFramework\Handlers;

    /**
     * Abstract error/exception handling things
     * @author Art <a.molcanovas@gmail.com>
     */
    abstract class AbstractHandler {

        /**
         * Whether CSS has been injected yet
         *
         * @var bool
         */
        protected static $cssInjected = false;

        /**
         * Injects the error handler CSS if it hasn't been injected yet
         * @author Art <a.molcanovas@gmail.com>
         * @throws InvalidPathException If the CSS file could not be found
         */
        protected static function injectCSS() {
            if (!self::$cssInjected) {
                if (file_exists(ALO_HANDLERS_CSS_PATH)) {
                    self::$cssInjected = true;
                    echo '<style type="text/css">';
                    include ALO_HANDLERS_CSS_PATH;
                    echo '</style>';
                } else {
                    throw new InvalidPathException('The exception CSS file could not be found: ' .
                                                   ALO_HANDLERS_CSS_PATH);
                }
            }
        }
    }
