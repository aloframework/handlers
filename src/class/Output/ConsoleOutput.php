<?php

    namespace AloFramework\Handlers\Output;

    use AloFramework\Handlers\OutputFormatters\Error;
    use AloFramework\Handlers\OutputFormatters\Info;
    use AloFramework\Handlers\OutputFormatters\Warning;
    use Symfony\Component\Console\Formatter\OutputFormatterInterface;

    /**
     * A small extension to Symfony's ConsoleOutput so write methods return $this
     * @author Art <a.molcanovas@gmail.com>
     * @codeCoverageIgnore
     */
    class ConsoleOutput extends \Symfony\Component\Console\Output\ConsoleOutput {

        /**
         * Constructor.
         *
         * @param int                           $verbosity The verbosity level (one of the VERBOSITY constants in
         *                                                 OutputInterface)
         * @param bool|null                     $decorated Whether to decorate messages (null for auto-guessing)
         * @param OutputFormatterInterface|null $formatter Output formatter instance (null to use default
         *                                                 OutputFormatter)
         */
        function __construct($verbosity = self::VERBOSITY_NORMAL, $decorated = null,
                             OutputFormatterInterface $formatter = null) {

            parent::__construct($verbosity, $decorated, $formatter);

            $this->setStyles();
        }

        /**
         * Sets the output styles
         * @author Art <a.molcanovas@gmail.com>
         */
        protected function setStyles() {
            $formatter = $this->getFormatter();

            $formatter->setStyle('e', Error::construct()->getFormatter());
            $formatter->setStyle('i', Info::construct()->getFormatter());
            $formatter->setStyle('w', Warning::construct()->getFormatter());

            $formatter->setStyle('eb', Error::construct()->setOption('bold')->getFormatter());
            $formatter->setStyle('ib', Info::construct()->setOption('bold')->getFormatter());
            $formatter->setStyle('wb', Warning::construct()->setOption('bold')->getFormatter());

            $formatter->setStyle('eu', Error::construct()->setOption('underscore')->getFormatter());
            $formatter->setStyle('iu', Info::construct()->setOption('underscore')->getFormatter());
            $formatter->setStyle('wu', Warning::construct()->setOption('underscore')->getFormatter());
        }

        /**
         * Writes a console message
         * @author Art <a.molcanovas@gmail.com>
         *
         * @param array|string $messages The message or array of messages
         * @param bool         $newline  Whether to insert a newline after the message(s)
         * @param int          $type     Output type
         *
         * @return ConsoleOutput
         */
        function write($messages, $newline = false, $type = self::OUTPUT_NORMAL) {
            parent::write($messages, $newline, $type);

            return $this;
        }
    }
