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
        SWITCH_DESCRIPTION = 'Enables payments by Citi Points (currently only available in Thailand).',
        SOURCE = 'points_citi'
    ;

    public static
        $restrictedToCurrencies = array('thb')
    ;

}