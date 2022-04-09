<?php

// http://php.net/manual/en/function.error-reporting.php
error_reporting(E_ALL | E_STRICT);

// Error handler function.
function my_error_handler($errno, $errstr, $errfile, $errline)
{
    $filename = CFG_ERRORS_LOG_DIR.date("Y.m.d").".log";
    $time = date("H:i:s");
    $err_str = $time." || ".$errno." || ".
        $errstr." || ".$errfile." || ".
        $errline." || ".$_SERVER['REQUEST_URI']."\r\n";

    error_log($err_str, 3, $filename);
}

// Sets a user-defined error handler function.
set_error_handler("my_error_handler");

