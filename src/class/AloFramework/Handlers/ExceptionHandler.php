<?php

    namespace AloFramework\Handlers;

    use Exception;
    use Psr\Log\LoggerInterface;

    /**
     * Exception handling class
     * @author Art <a.molcanovas@gmail.com>
     */
    class ExceptionHandler extends AbstractHandler {

        /**
         * Whether this handler has been enabled
         * @var bool
         */
        private static $registered = false;

        /**
         * Checks whether the handler has been registered
         * @author Art <a.molcanovas@gmail.com>
         * @return bool
         */
        static function isRegistered() {
            return self::$registered;
        }

        /**
         * Echoes previous exceptions if applicable
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param null|Exception $e The previous exception
         */
        protected static function echoPreviousExceptions($e) {
            if ($e instanceof Exception) {
                echo '<div>' .
                     '<span class="alo-bold">Preceded by </span>' .
                     '<span>[' .
                     $e->getCode() .
                     ']: ' .
                     $e->getMessage() .
                     ' @ <span class="alo-uline">' .
                     $e->getFile() .
                     '</span>\'s line ' .
                     $e->getLine() .
                     '.</span></div>';

                self::echoPreviousExceptions($e->getPrevious());
            }
        }

        /**
         * Exception handler
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param Exception $e The exception
         */
        function handle(Exception $e) {
            self::injectCss();
            $msg  = $e->getMessage();
            $code = $e->getCode();

            echo '<div class="text-center">'
                 //BEGIN outer container
                 .
                 '<div class="alo-err alert alert-danger">'
                 //BEGIN inner container
                 .
                 '<div>'
                 //BEGIN header
                 .
                 '<span class="alo-bold">Uncaught exception: </span><span>' .
                 $msg .
                 '</span></div>'
                 //END header
                 //BEGIN raised
                 .
                 '<div><span class="alo-bold">Raised in </span><span class="alo-uline">' .
                 $e->getFile() .
                 '</span> @ line ' .
                 $e->getLine() .
                 '</div>' .
                 '<div><span class="alo-bold">Code: </span><span>' .
                 $code .
                 '</span></div>';

            self::echoPreviousExceptions($e->getPrevious());

            echo '<span class="alo-bold">Backtrace:</span>';

            self::echoTrace($e->getTrace());

            echo '</div></div>'; //END inner/outer

            $this->log($code, $msg);
        }

        /**
         * Registers the exception handler
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param LoggerInterface $logger If provided, this will be used to log errors and exceptions.
         *                                AloFramework\Log\Log extends this interface.
         *
         * @return callable The return value of set_exception_handler()
         */
        static function register(LoggerInterface $logger = null) {
            self::$registered = true;

            return set_exception_handler([new ExceptionHandler($logger), 'handle']);
        }
    }
