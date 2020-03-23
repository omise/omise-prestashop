<?php

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/payment_methods/_offsite.php';
}

class OmisePaymentMethod_CitiPoints extends OmiseOffsitePaymentMethod
{

    const
        NAME = 'CitiPoints',
        PAYMENT_OPTION_NAME = 'omise-citipoints-payment',
        DEFAULT_TITLE = 'Citi Pay with Points',
        TEMPLATE = 'citipoints_payment',
        SWITCH_DESCRIPTION = 'Enables payments by Citi Points (currently only available in Thailand).'
    ;

    public static
        $usedSettings = array('citi_points_status'),
        $restrictedToCurrencies = array('thb')
    ;

    public static function processPayment($controller, $context)
    {
        parent::processOffsitePayment('points_citi', $controller, $context);
    }

}