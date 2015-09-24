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
