<?php

    namespace AloFramework\Handlers\Output;

    use Kint;

    /**
     * Arg dumper
     * @author Art <a.molcanovas@gmail.com>
     */
    class Dump extends Kint {

        /**
         * Dumps in HTML mode
         * @author Art <a.molcanovas@gmail.com>
         *
         * @return string The output
         */
        static function html() {
            $fargs = func_get_args();
            $mode  = self::enabled();
            self::enabled(self::MODE_RICH);
            $ret = @self::dump(empty($fargs) ? null : $fargs[0]);
            self::enabled($mode);

            return $ret;
        }
    }
