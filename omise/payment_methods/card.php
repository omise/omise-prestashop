<?php

class OmisePaymentMethod_Card extends OmisePaymentMethod
{

    const
        NAME = 'Card',
        PAYMENT_OPTION_NAME = 'omise-card-payment',
        DEFAULT_TITLE = 'Pay by Credit / Debit Card',
        TEMPLATE = "card_payment"
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
        $controller = self::$payModule->setting->isThreeDomainSecureEnabled() ? 'threedomainsecurepayment' :'payment';
        return self::getLink($controller);
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

}
