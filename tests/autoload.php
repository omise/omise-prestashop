<?php
function autoload($class) {
    static $classes = null;

    if ($classes === null) {
        $classes = array('Omise' => 'omise.php');
    }

    if (isset($classes[$class])) {
        require __DIR__ . '/../omise/' . $classes[$class];
    }
}

spl_autoload_register('autoload');
