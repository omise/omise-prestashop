<?php

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/payment_methods/_offsite.php';
}

class OmisePaymentMethod_InternetBanking extends OmiseOffsitePaymentMethod
{

    const
        NAME = 'InternetBanking',
        PAYMENT_OPTION_NAME = 'omise-internet-banking-payment',
        DEFAULT_TITLE = 'Internet Banking',
        TEMPLATE = 'internet_banking_payment',
        SWITCH_DESCRIPTION = 'Enables customers of a bank to easily conduct financial transactions through a bank-operated website (only available in Thailand).'
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