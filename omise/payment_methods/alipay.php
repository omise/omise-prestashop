<?php

class OmisePaymentMethod_Alipay extends OmisePaymentMethod
{

    const
        NAME = 'Alipay',
        PAYMENT_OPTION_NAME = 'omise-alipay-payment',
        DEFAULT_TITLE = 'Alipay',
        TEMPLATE = 'alipay_payment',
        CONTROLLER = 'alipaypayment'
    ;

    public static
        $usedSettings = array('alipay_status'),
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
            $c->charge = $c->omise_charge->createOffsite('alipay');
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