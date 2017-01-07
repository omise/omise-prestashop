<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (! defined('_PS_VERSION_')) {
    define('_PS_VERSION_', 'TEST_VERSION');
}

function autoload($class)
{
    static $classes = null;

    if ($classes === null) {
        $classes = array(
            'Charge' => 'classes/charge.php',
            'CheckoutForm' => 'checkout_form.php',
            'Omise' => 'omise.php',
            'OmisePaymentModuleFrontController' => 'controllers/front/payment.php',
            'Setting' => 'setting.php',
        );
    }

    if (isset($classes[$class])) {
        require __DIR__ . '/../omise/' . $classes[$class];
    }
}

spl_autoload_register('autoload');
