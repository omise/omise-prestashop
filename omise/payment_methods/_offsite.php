<?php

class OmiseOffsitePaymentMethod extends OmisePaymentMethod
{

    const TEMPLATE = 'simple_offsite_payment';

    public static function processOffsitePayment($offsiteType, $controller, $context)
    {
        
        $c = $controller;

        $c->validateCart();

        $c->payment_order->save(
            $c->payment_order->getOrderStateProcessingInProgress(),
            self::getTitle()
        );

        try {
            $c->charge = $c->omise_charge->createOffsite($offsiteType);
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