<?php
spl_autoload_register(function ($class_name) {
    // Mencari file class di dalam folder src/
    $file = __DIR__ . '/src/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
?>