<?php

class OmiseOffsitePaymentMethod extends OmisePaymentMethod
{

    const TEMPLATE = 'simple_offsite_payment';

    public static function processPayment($controller)
    {

        $c = $controller;
        $omiseCharge = new OmiseChargeClass();
        $paymentOrder = new PaymentOrder();

        $c->validateCart();

        $paymentOrder->save(
            $paymentOrder->getOrderStateProcessingInProgress(),
            self::getTitle()
        );

        try {
            $returnUri = self::getReturnUri(self::$context->cart->id, self::$context->customer->secure_key);
            $c->charge = $omiseCharge->createOffsite(self::getOffsiteSourceDetail(), $returnUri);
        } catch (Exception $e) {
            $c->error_message = $e->getMessage();
            return;
        }

        $id_order = Order::{PRESTASHOP_GET_ORDER_ID_METHOD}(self::$context->cart->id);

        $paymentOrder->updatePaymentTransactionId($id_order, $c->charge->getId());

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

    public static function getOffsiteSourceDetail() {
        return method_exists(get_called_class(), 'getSource') ? static::getSource() : static::SOURCE;
    }

}