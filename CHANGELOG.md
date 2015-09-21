# 1.0.4 #
Updated how the handlers' `register()` methods work. They now determine the class via `get_called_class()`, allowing you to extend the handlers without having to overwrite the `register()` method.
