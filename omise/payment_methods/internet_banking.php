<?php

class OmisePaymentMethod_InternetBanking extends OmisePaymentMethod
{

    const
        NAME = 'InternetBanking',
        PAYMENT_OPTION_NAME = 'omise-internet-banking-payment',
        DEFAULT_TITLE = 'Internet Banking',
        TEMPLATE = 'internet_banking_payment',
        CONTROLLER = 'internetbankingpayment'
    ;

    public static
        $usedSettings = array('internet_banking_status'),
        $cssFiles = array('omise_internet_banking.css'),
        $jqueryPlugins = array('fancybox')
    ;

}