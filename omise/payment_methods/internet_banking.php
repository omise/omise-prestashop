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
        CONTROLLER = 'internetbankingpayment',
        SWITCH_DESCRIPTION = 'Enables customers of a bank to easily conduct financial transactions through a bank-operated website (only available in Thailand).'
    ;

    public static
        $usedSettings = array('internet_banking_status'),
        $cssFiles = array('omise_internet_banking.css'),
        $jqueryPlugins = array('fancybox'),
        $restrictedToCurrencies = array('thb')
    ;

    public static function processPayment($controller, $context)
    {
        parent::processOffsitePayment(Tools::getValue('offsite'), $controller, $context);
    }

}