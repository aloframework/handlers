# AloFramework | Handlers #

A powerful error and exception handler of [AloFramework](https://github.com/aloframework/aloframework). It can automatically log errors and exceptions if an object implementing the [\Psr\Log\LoggerInterface](https://packagist.org/packages/psr/log) is passed on and will echo output based on your server's error reporting settings, altering its format depending on whether an error is raised during a HTTP or CLI call.

Latest release API documentation: [https://aloframework.github.io/handlers/](https://aloframework.github.io/handlers/)

[![License](https://poser.pugx.org/aloframework/handlers/license?format=plastic)](https://www.gnu.org/licenses/gpl-3.0.en.html)
[![Latest Stable Version](https://poser.pugx.org/aloframework/handlers/v/stable?format=plastic)](https://packagist.org/packages/aloframework/handlers)
[![Total Downloads](https://poser.pugx.org/aloframework/handlers/downloads?format=plastic)](https://packagist.org/packages/aloframework/handlers)

|                                                                                          dev-develop                                                                                          |                                                             Latest release                                                            |
|:--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|:-------------------------------------------------------------------------------------------------------------------------------------:|
| [![Dev Build Status](https://travis-ci.org/aloframework/handlers.svg?branch=develop)](https://travis-ci.org/aloframework/handlers)                                                            | [![Release Build Status](https://travis-ci.org/aloframework/handlers.svg?branch=1.2)](https://travis-ci.org/aloframework/handlers)  |
| [![SensioLabsInsight](https://insight.sensiolabs.com/projects/36b22482-e36a-44e3-a7de-ccf6e27999d1/mini.png)](https://insight.sensiolabs.com/projects/36b22482-e36a-44e3-a7de-ccf6e27999d1) | [![SensioLabsInsight](https://i.imgur.com/KygqLtf.png)](https://insight.sensiolabs.com/projects/36b22482-e36a-44e3-a7de-ccf6e27999d1) |

## Installation ##
Installation is available via Composer:

    composer require aloframework/handlers

## Usage ##

 - To enable only the exception handler call `\AloFramework\Handlers\ExceptionHandler::register()`
 - To enable only the error handler call `\AloFramework\Handlers\ErrorHandler::register()`
 - To enable both handlers call `\AloFramework\Handlers\AbstractHandler::register()`
	 - A ShutdownHandler is also available and requires the ErrorHandler to be registered - it should be able to handle and log fatal errors. If you define `define('ALO_HANDLERS_REGISTER_SHUTDOWN', true)` it will be registered via the abstract handler.

### Enabling logging ###

You can enable automatic logging by passing an instance of [\Psr\Log\LoggerInterface](https://packagist.org/packages/psr/log) to the `register()` method. The [AloFramework logger](https://packagist.org/packages/aloframework/log) implements this.

## Extension ##
If you extend the `ErrorHandler` or `ExceptionHandler` classes you will no longer be able to instantiate both handlers simultaneously via `\AloFramework\Handlers\AbstractHandler::register()` and will need to call each handler's `register()` method.

## Configuration ##

Any configuration must be made before calling composer's autoloader.

### Error levels ###

You can control which errors will be handled by defining **ALO_HANDLERS_ERROR_LEVEL**. This defaults to `ini_get('error_reporting')`.

### CSS ###

If you wish to change the CSS used for error output you can check **src/error.css**. Edit the values there to suit your needs, save the file in one of your project's directories and set the **ALO_HANDLERS_CSS_PATH** constant to point to that file's location **on your file system**, as its contents are read by PHP instead of pointing the browser to its location.

### CLI output colours ###

The output colours of the CLI handlers accept the following colour options: **black**, **red**, **green**, **yellow**, **blue**, **magenta**, **cyan** and **white** (as per [Symfony's Console component's specifications](http://symfony.com/doc/current/components/console/introduction.html#creating-a-basic-command)). For the background colour, the **default** option is available, which uses the console client's background colour.

The configurable colour constants are as follows:

 - **ALO_HANDLERS_BACKGROUND** changes the output background colour
 - **ALO_HANDLERS_FOREGROUND_NOTICE** changes the text colour for PHP notices
 - **ALO_HANDLERS_FOREGROUND_WARNING** changes the text colour for PHP warnings
 - **ALO_HANDLERS_FOREGROUND_ERROR** changes the text colour for PHP errors and exceptions

### Leak prevention ###

As a failsafe against infinite looping, you can define the **ALO_HANDLERS_EXCEPTION_DEPTH** constant to limit the maximum amount of previous exceptions reported (defaults to **10**) and **ALO_HANDLERS_TRACE_MAX_DEPTH** to limit the maximum number of debug backtrace items (defaults to **50**).

### Logging ###
You can prevent error/exception locations from showing up in the logger by defining the following constants before calling composer's autoloader:

    define('ALO_HANDLERS_LOG_ERROR_LOCATION', false);
    define('ALO_HANDLERS_LOG_EXCEPTION_LOCATION', false);

### Shutdown Handler ###
You can make the shutdown handler register by default by defining `define('ALO_HANDLERS_REGISTER_SHUTDOWN', true)`.
