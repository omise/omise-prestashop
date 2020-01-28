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
        TEMPLATE = 'alipay_payment',
        CONTROLLER = 'alipaypayment',
        SWITCH_DESCRIPTION = 'Enables payments by Alipay (currently only available in Thailand).'
    ;

    public static
        $usedSettings = array('alipay_status'),
        $restrictedToCurrencies = array('thb')
    ;

    public static function processPayment($controller, $context)
    {
        parent::processOffsitePayment('alipay', $controller, $context);
    }

}