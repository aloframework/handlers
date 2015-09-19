<?php

    namespace AloFramework\Handlers\OutputFormatters;

    class Bold extends AbstractOutputFormatter {

        function __construct() {
            parent::__construct();
            $this->formatter->setOption('bold');
        }
    }
