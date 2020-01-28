<?php

class OmisePaymentMethod_Card extends OmisePaymentMethod
{

    const
        NAME = 'Card',
        PAYMENT_OPTION_NAME = 'omise-card-payment',
        DEFAULT_TITLE = 'Pay by Credit / Debit Card',
        TEMPLATE = "card_payment",
        SWITCH_DESCRIPTION = "Enable payments by credit and debit cards."
    ;

    public static
        $usedSettings = array(
            'module_status',
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

    public static function processPayment($controller, $context)
    {
        $c = $controller;
        $c->validateCart();

        $c->payment_order->save(
            $c->payment_order->getOrderStateProcessingInProgress(),
            self::getTitle()
        );

        $c->parentPostProcess();

        $id_order = Order::{PRESTASHOP_GET_ORDER_ID_METHOD}($context->cart->id);

        if (!empty($c->charge)) {
            $c->payment_order->updatePaymentTransactionId($id_order, $c->charge->getId());
        }

        if (!empty($c->error_message)) {
            $c->payment_order->updateStateToBeCanceled(new Order($id_order));
            return;
        }

        if (Tools::getValue('threedomainsecure') == '0') {
            $c->payment_order->updateStateToBeSuccess(new Order($id_order));
            $uri = 'index.php?controller=order-confirmation' .
                '&id_cart=' . $context->cart->id .
                '&id_module=' . $c->module->id .
                '&id_order=' . $c->module->currentOrder .
                '&key=' . $context->customer->secure_key;
        } else {
            $c->addOmiseTransaction($c->charge->getId(), $id_order);
            $uri = $c->charge->getAuthorizeUri();
        }

        $c->setRedirectAfter($uri);

    }

}
