<?php

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/payment_methods/_offsite.php';
}

class OmisePaymentMethod_Installments extends OmiseOffsitePaymentMethod
{

    const
        NAME = 'Installments',
        PAYMENT_OPTION_NAME = 'omise-installments-payment',
        DEFAULT_TITLE = 'Installments',
        TEMPLATE = 'installments_payment',
        SWITCH_DESCRIPTION = 'Enables customers to pay in installments (only available in Thailand).'
    ;

    public static
        $cssFiles = array('omise_internet_banking.css'),
        $jsFiles = array('message.js'),
        $jqueryPlugins = array('fancybox'),
        $restrictedToCurrencies = array('thb')
    ;

    public static function getSource() {
        return Tools::getValue('offsite');
    }
    
}
