<?php

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/payment_methods/_offsite.php';
}

class OmisePaymentMethod_Alipay extends OmiseOffsitePaymentMethod
{

    const
        NAME = 'Alipay',
        PAYMENT_OPTION_NAME = 'omise-alipay-payment',
        DEFAULT_TITLE = 'Alipay',
        SWITCH_DESCRIPTION = 'Enables payments by Alipay (currently only available in Thailand).',
        SOURCE = 'alipay'
    ;

    public static
        $restrictedToCurrencies = array('thb')
    ;

}