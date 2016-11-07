<?php
    /**
 *    Copyright (c) Arturas Molcanovas <a.molcanovas@gmail.com> 2016.
 *    https://github.com/aloframework/handlers
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

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
