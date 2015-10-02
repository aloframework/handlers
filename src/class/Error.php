<?php

    namespace AloFramework\Handlers;

    use AloFramework\Common\Alo;
    use ArrayObject;

    /**
     * Object representation of an error
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.2
     */
    class Error extends ArrayObject {

        /**
         * Error code/label map
         * @var array
         */
        static $map = [E_NOTICE            => 'NOTICE',
                       E_USER_NOTICE       => 'USER NOTICE',
                       E_CORE_ERROR        => 'CORE ERROR',
                       E_ERROR             => 'ERROR',
                       E_USER_ERROR        => 'USER ERROR',
                       E_COMPILE_ERROR     => 'COMPILE ERROR',
                       E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
                       E_DEPRECATED        => 'DEPRECATED',
                       E_USER_DEPRECATED   => 'USER DEPRECATED',
                       E_WARNING           => 'WARNING',
                       E_USER_WARNING      => 'USER WARNING',
                       E_STRICT            => 'STRICT',
                       E_CORE_WARNING      => 'CORE WARNING'];

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param int|array $typeOrData The error code or array of error_get_last()
         * @param string    $message    The error message
         * @param string    $file       The error file
         * @param int       $line       The error line
         */
        function __construct($typeOrData, $message = null, $file = null, $line = null) {
            if (empty($typeOrData)) {
                parent::__construct(['type'    => null,
                                     'message' => null,
                                     'file'    => null,
                                     'line'    => null]);
            } else {
                if (is_array($typeOrData) && !empty($typeOrData)) {
                    $message    = Alo::get($typeOrData['message']);
                    $file       = Alo::get($typeOrData['file']);
                    $line       = Alo::get($typeOrData['line']);
                    $typeOrData = Alo::get($typeOrData['type']);
                }

                parent::__construct(['type'    => $typeOrData,
                                     'message' => $message,
                                     'file'    => $file,
                                     'line'    => $line]);
            }
        }

        /**
         * Returns the error code
         * @author Art <a.molcanovas@gmail.com>
         * @return int
         */
        function getType() {
            return $this->offsetGet('type');
        }

        /**
         * Returns the error line
         * @author Art <a.molcanovas@gmail.com>
         * @return int
         */
        function getLine() {
            return $this->offsetGet('line');
        }

        /**
         * Returns the error message
         * @author Art <a.molcanovas@gmail.com>
         * @return string
         */
        function getMessage() {
            return $this->offsetGet('message');
        }

        /**
         * Returns the error file
         * @author Art <a.molcanovas@gmail.com>
         * @return string
         */
        function getFile() {
            return $this->offsetGet('file');
        }

        /**
         * Checks if the error is actually empty
         * @author Art <a.molcanovas@gmail.com>
         * @return bool
         */
        function isEmpty() {
            return !($this->getType() && $this->getMessage() && $this->getFile() && $this->getLine());
        }

        /**
         * Returns a string representation of $this
         * @author Art <a.molcanovas@gmail.com>
         * @return string
         */
        function __toString() {
            return '[' . $this->getType() . '] ' . $this->getMessage() . ' @ ' . $this->getFile() . ' @ line ' .
                   $this->getLine();
        }

        /**
         * Checks if an error should be reported
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param int $errcode           The error code
         * @param int $reportingSettings You can provide your own reporting settings. If omitted,
         *                               ALO_HANDLERS_ERROR_LEVEL will be used.
         *
         * @return bool
         */
        static function shouldBeReported($errcode, $reportingSettings = null) {
            if (!$reportingSettings || !is_numeric($reportingSettings)) {
                $reportingSettings = (int)ALO_HANDLERS_ERROR_LEVEL;
            }

            $reportingSettings = (int)$reportingSettings;

            return $errcode && $reportingSettings & ((int)$errcode) ? true : false;
        }

        /**
         * Checks if the two errors are equal
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param Error $e The other error
         *
         * @return bool
         */
        function equals(Error $e) {
            return $e->__toString() === $this->__toString();
        }
    }
