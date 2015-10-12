# AloFramework | Handlers #

A powerful error and exception handler of [AloFramework](https://github.com/aloframework/aloframework). It can automatically log errors and exceptions if an object implementing the [\Psr\Log\LoggerInterface](https://packagist.org/packages/psr/log) is passed on and will echo output based on your server's error reporting settings, altering its format depending on whether an error is raised during a HTTP or CLI call.

Latest release API documentation: [https://aloframework.github.io/handlers/](https://aloframework.github.io/handlers/)

[![License](https://poser.pugx.org/aloframework/handlers/license?format=plastic)](https://www.gnu.org/licenses/gpl-3.0.en.html)
[![Latest Stable Version](https://poser.pugx.org/aloframework/handlers/v/stable?format=plastic)](https://packagist.org/packages/aloframework/handlers)
[![Total Downloads](https://poser.pugx.org/aloframework/handlers/downloads?format=plastic)](https://packagist.org/packages/aloframework/handlers)

|                                                                                          dev-develop                                                                                          |                                                             Latest release                                                            |
|:--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|:-------------------------------------------------------------------------------------------------------------------------------------:|
| [![Dev Build Status](https://travis-ci.org/aloframework/handlers.svg?branch=develop)](https://travis-ci.org/aloframework/handlers)                                                            | [![Release Build Status](https://travis-ci.org/aloframework/handlers.svg?branch=1.4)](https://travis-ci.org/aloframework/handlers)  |
| [![SensioLabsInsight](https://insight.sensiolabs.com/projects/36b22482-e36a-44e3-a7de-ccf6e27999d1/mini.png)](https://insight.sensiolabs.com/projects/36b22482-e36a-44e3-a7de-ccf6e27999d1) | [![SensioLabsInsight](https://i.imgur.com/KygqLtf.png)](https://insight.sensiolabs.com/projects/36b22482-e36a-44e3-a7de-ccf6e27999d1) |

## Installation ##
Installation is available via Composer:

    composer require aloframework/handlers

## Usage ##

 - To enable the exception handler call `\AloFramework\Handlers\ExceptionHandler::register()`
 - To enable the error handler call `\AloFramework\Handlers\ErrorHandler::register()`
 - To enable the shutdown handler call `\AloFramework\Handlers\ShutdownHandler::register()`

### Logging ###
Every error and exception has to be logged in this package. You can supply your own logger to have more control; if you don't, [aloframework/log](https://github.com/aloframework/log) will be used with its default settings. 

## Configuration ##
Configuration is done via the classes in the `AloFramework\Handlers\Config` namespace. 

### Common ###

 - `CFG_CSS_PATH` - path to the CSS file which will style the HTML output. Defaults to **error.min.css** in the src directory.
 - `CFG_TRACE_MAX_DEPTH` - maximum number of debug backtrace items to display [**50**]
 - `CFG_BACKGROUND` - CLI output background colour [**default**]
 - `CFG_FOREGROUND_NOTICE` - CLI output notice level foreground colour [**cyan**]
 - `CFG_FOREGROUND_WARNING` - CLI output warning level foreground colour [**yellow**]
 - `CFG_FOREGROUND_ERROR` - CLI output error/exception level foreground colour [**red**]
 - `CFG_FORCE_HTML` - Whether to force HTML output even in CLI mode [**false**]
 - `CFG_REGISTER_SHUTDOWN_HANDLER` - **deprecated**, whether to register the shutdown handler when calling the deprecated `AbstractHandler::register()` method [**false**]

### Error Handlers' Config ###

 - `CFG_ERROR_LEVEL` - Which error levels to handle. Defaults to the value of `error_reporting()`.
 - `CFG_LOG_ERROR_LOCATION` - Whether to include the error location in the log [**true**]

### Exception Handlers' Config ###

 - `CFG_EXCEPTION_DEPTH` - Maximum number previous exceptions to output in the exception handler [**10**]
 - `CFG_LOG_EXCEPTION_LOCATION` Whether to include the exception location in the log [**true**]

## Sample Output ###

| **HTML**                                                                                              | **CLI**                                                                                              |
|-------------------------------------------------------------------------------------------------------|------------------------------------------------------------------------------------------------------|
| [Error](https://github.com/aloframework/handlers/blob/develop/output-examples/error-html.png)         | [Error](https://github.com/aloframework/handlers/blob/develop/output-examples/error-cli.png)         |
| [Exception](https://github.com/aloframework/handlers/blob/develop/output-examples/exception-html.png) | [Exception](https://github.com/aloframework/handlers/blob/develop/output-examples/exception-cli.png) |
| [Warning](https://github.com/aloframework/handlers/blob/develop/output-examples/warning-html.png)     | [Warning](https://github.com/aloframework/handlers/blob/develop/output-examples/warning-cli.png)     |
| [Notice](https://github.com/aloframework/handlers/blob/develop/output-examples/notice-html.png)       | [Notice](https://github.com/aloframework/handlers/blob/develop/output-examples/notice-cli.png)       |
