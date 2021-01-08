<?php

class OmisePaymentMethod_Card extends OmisePaymentMethod
{

    const
        NAME = 'Card',
        PAYMENT_OPTION_NAME = 'omise-card-payment',
        DEFAULT_TITLE = 'Pay by Credit / Debit Card',
        TEMPLATE = "card_payment",
        ADMIN_TEMPLATE = 'card',
        SWITCH_DESCRIPTION = "Enable payments by credit and debit cards.",
        STATUS_SETTING_KEY = 'module_status'
    ;

    public static
        $usedSettings = array(
            'title',
            'three_domain_secure_status'
        )
    ;

    public static function getSmartyVars()
    {
        return array_merge(parent::getSmartyVars(), array(
            'list_of_expiration_year' => range($d=date('Y'), $d+10)
        ));
    }

    public static function getAction()
    {
        $is3DS = self::$payModule->setting->isThreeDomainSecureEnabled() ? '1' : '0';
        return self::getLink(static::NAME, array('threedomainsecure' => $is3DS));
    }

    public static function getTitle()
    {
        return self::$payModule->setting->getTitle();
    }

    public static function isEnabled()
    {
        $enabledMethod = 'isModuleEnabled';
        return self::$payModule->setting->$enabledMethod();
    }

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
            $c->charge = $omiseCharge->create(Tools::getValue('omise_card_token'), $returnUri);
        } catch (Exception $e) {
            $c->error_message = $e->getMessage();
            return;
        }

        if ($c->charge->isFailed()) {
            $c->error_message = $c->charge->getErrorMessage();
            return;
        }

        $id_order = Order::{PRESTASHOP_GET_ORDER_ID_METHOD}(self::$context->cart->id);

        if (!empty($c->charge)) {
            $paymentOrder->updatePaymentTransactionId($id_order, $c->charge->getId());
        }

        if (!empty($c->error_message)) {
            $paymentOrder->updateStateToBeCanceled(new Order($id_order)); // TODO - check if this the right thing to be doing - can we not return to checkout?
            return;
        }

        if (Tools::getValue('threedomainsecure') == '0') {
            $paymentOrder->updateStateToBeSuccess(new Order($id_order));
            $uri = self::getOrderConfirmationUri(self::$context->cart->id, $c->module->id, $c->module->currentOrder, self::$context->customer->secure_key);
        } else {
            $c->addOmiseTransaction($c->charge->getId(), $id_order);
            $uri = $c->charge->getAuthorizeUri();
        }

        $c->setRedirectAfter($uri);

    }

}
