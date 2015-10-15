<?php

    namespace AloFramework\Handlers\Output;

    /**
     * The handlers' output formatter
     * @author Art <a.molcanovas@gmail.com>
     */
    class OutputFormatter extends \Symfony\Component\Console\Formatter\OutputFormatter {

        /**
         * Styles to set
         * @var array
         */
        private static $styles = [];

        /**
         * Colours to initialise
         * @var array
         */
        private static $styleColours = ['e' => '\AloFramework\Handlers\Output\Styles\Error',
                                        'i' => '\AloFramework\Handlers\Output\Styles\Info',
                                        'w' => '\AloFramework\Handlers\Output\Styles\Warning'];

        /**
         * Variants to initialise
         * @var array
         */
        private static $styleVariants = ['b' => 'bold',
                                         'u' => 'underscore'];

        /**
         * Constructor
         * @author Art <a.molcanovas@gmail.com>
         */
        function __construct() {
            parent::__construct();
            self::initStyles();

            foreach (self::$styles as $code => $class) {
                $this->setStyle($code, $class);
            }
        }

        /**
         * Initialises the style objects
         * @author Art <a.molcanovas@gmail.com>
         */
        private static function initStyles() {
            if (empty(self::$styles)) {
                foreach (self::$styleColours as $colourCode => $class) {
                    self::$styles[$colourCode] = new $class();

                    foreach (self::$styleVariants as $varCode => $var) {
                        self::$styles[$colourCode . $varCode] = new $class($var);
                    }
                }
            }
        }
    }
