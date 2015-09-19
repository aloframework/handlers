# AloFramework | Handlers #

The logging component, implementing the PSR-3 logging interface.

[![License](https://poser.pugx.org/aloframework/handlers/license?format=plastic)](LICENSE)
[![Latest Stable Version](https://poser.pugx.org/aloframework/handlers/v/stable?format=plastic)](https://packagist.org/packages/aloframework/handlers)
[![Total Downloads](https://poser.pugx.org/aloframework/handlers/downloads?format=plastic)](https://packagist.org/packages/aloframework/handlers)

Development code quality: [![SensioLabsInsight](https://insight.sensiolabs.com/projects/c3500bba-d9af-4734-9dc7-31fddc7f8abe/small.png)](https://insight.sensiolabs.com/projects/c3500bba-d9af-4734-9dc7-31fddc7f8abe)

Dev: [![Dev Build Status](https://travis-ci.org/aloframework/handlers.svg?branch=master)](https://travis-ci.org/aloframework/handlers)
Release: [![Release Build Status](https://travis-ci.org/aloframework/handlers.svg?branch=0.1)](https://travis-ci.org/aloframework/handlers)

## Installation ##
Installation is available via Composer:

    composer require aloframework/handlers

## Usage ##

 - To enable only the exception handler call `\AloFramework\Handlers\ExceptionHandler::register()`
 - To enable only the error handler call `\AloFramework\Handlers\ErrorHandler::register()`
 - To enable both handlers call `\AloFramework\Handlers\AbstractHandler::register()`

