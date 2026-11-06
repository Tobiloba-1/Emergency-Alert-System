<?php
/**
 * Bootstraps the backend: config, autoloading, and session start.
 * Every file in /public requires this first.
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/helpers.php';

// Simple autoloader: looks for {ClassName}.php in core/, models/, controllers/.
spl_autoload_register(function (string $class): void {
    foreach (['core', 'models', 'controllers'] as $dir) {
        $path = __DIR__ . "/$dir/$class.php";
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

Session::start();
