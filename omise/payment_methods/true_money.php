<?php

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/payment_methods/_offsite.php';
}

class OmisePaymentMethod_TrueMoney extends OmiseOffsitePaymentMethod
{

    const
        NAME = 'TrueMoney',
        PAYMENT_OPTION_NAME = 'omise-truemoney-payment',
        DEFAULT_TITLE = 'TrueMoney',
        TEMPLATE = 'truemoney_payment',
        SWITCH_DESCRIPTION = 'Enables payments by TrueMoney (currently only available in Thailand).'
    ;

    public static
        $restrictedToCurrencies = array('thb')
    ;

    public static function getSmartyVars()
    {
        $address = new Address(self::$context->cart->id_address_invoice);
        $phone = $address->phone_mobile ?: $address->phone ?: '';
        return array_merge(parent::getSmartyVars(), array(
            'initialPhone' => $phone
        ));
    }

    public static function getSource() {
        return array('type'=>'truemoney', 'phone_number'=>Tools::getValue('truemoney_number'));
    }

}
