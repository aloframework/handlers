# 1.4.1 #

@property PHPDocs added to the config classes, config keys' string identifiers relabeled

# 1.4 #

 - Configuration is now expected to be done via the [Configurable interface](https://github.com/aloframework/config),
  the old, constant-based configuration is now deprecated and will be removed in 2.0
 - Better tests' code coverage
 - Output examples added

# 1.3 #

Bugfixes:

 - ExceptionHandler now correctly assigns the `$lastRegisteredHandler` and `$lastReported` values

Error class:

 - Now has a static `$map` property, allowing easy text representation of error codes
 - Now has a shouldBeReported() static method
 - It's now possible to force HTML error output when in CLI mode by defining `define('ALO_HANDLERS_FORCE_HTML', true);`

ShutdownHandler:

 - Doesn't force the `E_CORE_ERROR` error code now
 - Has the `getLastRegisteredHandler` static method now

ErrorHandler:

 - Error labels are more detailed now (e.g. `CORE ERROR` instead of just `ERROR`)

Misc:

 - .gitattributes added
 - More extensive testing

# 1.2.1 #

Fixed the if statement in the shutdown handler, which should now allow it to handle fatal errors if no errors were reported beforehand.

# 1.2 #

 - The last reported error is tracked in ErrorHandler and can be retrived via the error handler's static 
 `getLastReportedError` method.
 - The last registered handler can now be retrieved via the static `getLastRegisteredHandler` method.
	 - Equivalent operations are available to the ExceptionHandler via `getLastReportedException` and `getLastRegisteredHandler`.
 - `E_CORE_ERROR` is now labelled as **FATAL ERROR**.
 - `E_CORE_ERROR` and `E_CORE_WARNING` are now tracked.
 - The shutdown handler is now available in the `ShutdownHandler` class and can be registered via `\AloFramework\Handlers\ShutdownHandler::register()` static method.
	 - By default it is disabled and won't be registered via AbstractHandler. You can enable this by defining `define('ALO_HANDLERS_REGISTER_SHUTDOWN', true)` before calling composer's autoloader.
 - `aloframework/log` is now a dependency - all errors should be tracked.
 

# 1.1 #

 - The `log` method in `ErrorHandler` now accepts two more optional arguments: `$file` & `$line`. If they are provided, the file and line where the error occurred will be appended to the log message.
 - The `log` method in `ExceptionHandler` now accepts an additional boolean parameter, `$includeLocation`, which defaults to `true`. Unless set to false, the exception file and line will be appended to the log message.
	 - The above two functionalities can be switched off by including the following two lines in your code before initialising composer: 
		 - `define('ALO_HANDLERS_LOG_ERROR_LOCATION', false);`
		 - `define('ALO_HANDLERS_LOG_EXCEPTION_LOCATION', false);`
	 - The above dual configuration is in place to allow easy conditional location logging, i.e. you can simply extend the handler class and overwrite the logging methods to only log under specific conditions instead of turning the functionality off entirely.
 - The default configuration file is only loaded when the class file gets included
 - General cleanup of unnecessary files
 - Code documentation is now a lot more detailed

# 1.0.5 #
Removed private props & methods from docs. Removed some unused class constants.

# 1.0.4 #
Updated how the handlers' `register()` methods work. They now determine the class via `get_called_class()`, allowing you to extend the handlers without having to overwrite the `register()` method.
