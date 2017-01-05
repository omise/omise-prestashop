<?php
function autoload($class)
{
    static $classes = null;

    if ($classes === null) {
        $classes = array(
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
