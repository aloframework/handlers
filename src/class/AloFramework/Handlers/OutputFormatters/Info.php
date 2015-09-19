<?php

    namespace AloFramework\Handlers\OutputFormatters;

    class Info extends AbstractOutputFormatter {

        function __construct() {
            parent::__construct();
            $this->formatter->setForeground();
        }
    }
