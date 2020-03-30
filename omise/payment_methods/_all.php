<?php

class OmisePaymentMethods
{
    static $list = array(
        'Card',
        'InternetBanking',
        'Alipay',
        'CitiPoints',
        'TrueMoney'
    );

    public static function className($method) { return 'OmisePaymentMethod_'.$method; } 
}

$path = _PS_MODULE_DIR_ . 'omise/payment_methods/';
require_once $path . '_payment_method.php';

foreach (OmisePaymentMethods::$list as $method) require_once $path . strToLower(preg_replace('%([a-z])([A-Z])%', '\1_\2', $method)) . '.php';

