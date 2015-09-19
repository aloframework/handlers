# AloFramework | Handlers #

The logging component, implementing the PSR-3 logging interface.

[![License](https://poser.pugx.org/aloframework/handlers/license?format=plastic)](LICENSE)
[![Latest Stable Version](https://poser.pugx.org/aloframework/handlers/v/stable?format=plastic)](https://packagist.org/packages/aloframework/handlers)
[![Total Downloads](https://poser.pugx.org/aloframework/handlers/downloads?format=plastic)](https://packagist.org/packages/aloframework/handlers)

Development code quality:
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/36b22482-e36a-44e3-a7de-ccf6e27999d1/small.png)](https://insight.sensiolabs.com/projects/36b22482-e36a-44e3-a7de-ccf6e27999d1)

Dev: [![Dev Build Status](https://travis-ci.org/aloframework/handlers.svg?branch=master)](https://travis-ci.org/aloframework/handlers)
Release: [![Release Build Status](https://travis-ci.org/aloframework/handlers.svg?branch=0.1)](https://travis-ci.org/aloframework/handlers)

## Installation ##
Installation is available via Composer:

    composer require aloframework/handlers

## Usage ##

 - To enable only the exception handler call `\AloFramework\Handlers\ExceptionHandler::register()`
 - To enable only the error handler call `\AloFramework\Handlers\ErrorHandler::register()`
 - To enable both handlers call `\AloFramework\Handlers\AbstractHandler::register()`

### Enabling logging ###

You can enable automatic logging by passing an instance of [\Psr\Log\LoggerInterface](https://packagist.org/packages/psr/log) to the `register()` method. The [AloFramework logger](https://packagist.org/packages/aloframework/log) implements this.

## Configuration ##

Any configuration must be made before calling composer's autoloader.

### Error levels ###

You can control which errors will be handled by defining **ALO_HANDLERS_ERROR_LEVEL**. This defaults to `ini_get('error_reporting')`.

### CSS ###

If you wish to change the CSS used for error output you can check **src/error.css**. Edit the values there to suit your needs, save the file in one of your project's directories and set the **ALO_HANDLERS_CSS_PATH** constant to point to that file's location **on your file system**, as its contents are read by PHP instead of pointing the browser to its location.
