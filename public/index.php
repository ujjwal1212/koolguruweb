<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';
//Setup error handler
require_once 'e_errorhandler.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();

function asd($data,$istrue=true){
    echo '<pre>';
    print_r($data);
    if($istrue){
        die();
    }
}
