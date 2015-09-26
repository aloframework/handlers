<?php

    namespace AloFramework\Handlers;

    use ArrayObject;

    /**
     * Object representation of an error
     * @author Art <a.molcanovas@gmail.com>
     * @since  1.2
     */
    class Error extends ArrayObject {

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
                    $message    = self::get($typeOrData['message']);
                    $file       = self::get($typeOrData['file']);
                    $line       = self::get($typeOrData['line']);
                    $typeOrData = self::get($typeOrData['type']);
                }

                parent::__construct(['type'    => $typeOrData,
                                     'message' => $message,
                                     'file'    => $file,
                                     'line'    => $line]);
            }
        }

        /**
         * Returns the var if it's set, null if not
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param mixed $var Referemce to the var
         *
         * @return mixed|null
         */
        private static function get(&$var) {
            return isset($var) ? $var : null;
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
