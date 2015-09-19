<?php

    namespace AloFramework\Handlers;

    use Exception;

    /**
     * Exception indicating that a path is invalid
     * @author Art <a.molcanovas@gmail.com>
     */
    class InvalidPathException extends Exception {

        /**
         * Error code when the path to the CSS file is invalid
         * @author Art <a.molcanovas@gmail.com>
         */
        const E_CSS_PATH_INVALID = 1;
    }
