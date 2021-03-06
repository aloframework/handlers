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

    namespace AloFramework\Handlers\Output\Styles;

    /**
     * Output formatter for warnings
     *
     * @author Art <a.molcanovas@gmail.com>
     */
    class Warning extends AbstractStyle {

        /**
         * Constructor
         *
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param string|array $setOption Optionally set a formatter style option or array of options
         */
        public function __construct($setOption = null) {
            parent::__construct($setOption);
            $this->setForeground($this->cfg->fgWarn);
        }
    }
