<?php
/**
 * Init file
 *
 * @author Cyanne Justin Vega
 * @copyright  Ilocos Sur Polytechnic State College 2023
 */
include_once __DIR__ . "/config/Configuration.php";

spl_autoload_register(function ($class) {

    $file = __DIR__ . "/Engine/" . $class . ".php";

    if (file_exists($file)) {
        require $file;
    }

});