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
        $jqueryPlugins = array('fancybox'),
        $restrictedToCurrencies = array('thb')
    ;

    public static function processPayment($controller, $context)
    {
        
        $c = $controller;

        $c->validateCart();

        $c->payment_order->save(
            $c->payment_order->getOrderStateProcessingInProgress(),
            self::getTitle()
        );

        try {
            $c->charge = $c->omise_charge->createOffsite(Tools::getValue('offsite'));
        } catch (Exception $e) {
            $c->error_message = $e->getMessage();
            return;
        }

        $id_order = Order::{PRESTASHOP_GET_ORDER_ID_METHOD}($context->cart->id);

        $c->payment_order->updatePaymentTransactionId($id_order, $c->charge->getId());

        if ($c->charge->isFailed()) {
            $c->error_message = $c->charge->getErrorMessage();
            return;
        }

        if (!empty($c->error_message)) {
            return;
        }

        $c->addOmiseTransaction($c->charge->getId(), $id_order);

        $c->setRedirectAfter($c->charge->getAuthorizeUri());
    }

}