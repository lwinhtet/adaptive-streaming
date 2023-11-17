<?php

// https://phpdelusions.net/articles/error_reporting

/*
PHP offers two functions, set_error_handler() and set_exception_handler(). They both register a function 
that will be called in case an error or an uncaught exception appears.
1.customErrorHandler: This is a custom error handler function that takes four parameters (error number, 
error message, filename, and line number). In this function, we throw an ErrorException to convert the 
error into an exception.
2.set_error_handler: This function sets the custom error handler. It takes the name of the custom error 
handler function as an argument.
*/

/*
let's create a universal function that could satisfy both developer and user. to do so we need some parameter 
to tell a dev environment from a production one. There are many possible solutions, in my case I would just use 
the display_errors php.ini configuration parameter. As it must be set to 0 on the production server we could use 
it to tell that we are in the production environment.
*/

error_reporting(E_ALL);
// this is customErrorHandler
function myExceptionHandler($e)
{
  error_log($e);
  http_response_code(500);
  if (filter_var(ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN)) {
    echo $e;
  } else {
    echo "<h1>500 Internal Server Error</h1>
              An internal server error has been occurred.<br>
              Please try again later.";
  }
  exit;
}

set_exception_handler('myExceptionHandler');

set_error_handler(function ($level, $message, $file = '', $line = 0) {
  throw new ErrorException($message, 0, $level, $file, $line);
});

/*
Fatal errors are not caught by the standard error handler. To handle them the same way as other errors, 
we will need another function, register_shutdown_function which will tell PHP to call a function every 
time the PHP execution is terminated, both of natural causes or in case of error. So we will need to tell 
the latter from the former, for which the error_get_last() will be used, also providing the error information.
Unfortunately, this function is called at such a stage when no intelligent handling is possible, for example, 
an exception thrown inside this function wont be caught using try-catch operator. All we can do is some basic 
error handling.
*/

register_shutdown_function(function () {
  $error = error_get_last();
  if ($error !== null) {
    $e = new ErrorException(
      $error['message'],
      0,
      $error['type'],
      $error['file'],
      $error['line']
    );
    myExceptionHandler($e);
  }
});

function handleDatabaseError(PDOException $e)
{
  $errorCode = $e->getCode();
  $errorMessage = $e->getMessage();
  // Log minimal information
  error_log("Database Error: Code $errorCode, Message: $errorMessage");
  if (getDevEnv() === 'development') {
    // In development, throw the original exception for detailed debugging
    throw $e;
  } else if (getDevEnv() === 'production') {
    return false; // or throw a custom exception if needed
  }
}

/*
The set_exception_handler and set_error_handler functions in your error_handler.php script are used 
to define custom error and exception handlers in PHP. Here's how they relate to the getExecutedMigrations method:

Exception Handling:
In your getExecutedMigrations method, you have a try-catch block that catches exceptions, specifically 
\PDOException and \Throwable. If an exception of either type is thrown within the try block, it will be 
caught by the respective catch block.

Custom Exception Handler:
The set_exception_handler('myExceptionHandler') line sets a custom exception handler function (myExceptionHandler) 
to be called when an uncaught exception occurs. This function logs the exception, sets the HTTP response code to 500
(Internal Server Error), and provides an error message to the client.

Error Handling:
The set_error_handler function sets an anonymous function as the error handler. This function converts PHP errors 
to exceptions by throwing a new ErrorException when an error occurs. The register_shutdown_function function registers 
another function to be called when the script finishes execution. It checks for any fatal errors that might have 
occurred during script execution.
*/
