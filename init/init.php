<?php

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../vendor/autoload.php";

spl_autoload_register(function($class_name){
    $file = __DIR__ . "/../Engine/" . $class_name . ".php";
    if (file_exists($file)){
        include_once $file;
    }
});


# Start Session
Session::startSession();