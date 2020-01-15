<?php

class OmisePaymentMethods
{
    static $list = array(
        'Card',
        'InternetBanking',
        'Alipay'
    );

    public static function className($method) { return 'OmisePaymentMethod_'.$method; } 
}

class OmisePaymentMethod
{

    public static
        $payModule,
        $smarty,
        $usedSettings = array(),
        $cssFiles = array(),
        $jqueryPlugins = array()
    ;

    public static function display()
    {
        return self::$payModule->versionSpecificDisplay(static::TEMPLATE . '.tpl');
    }

    public static function getCallToActionText()
    {
        return static::DEFAULT_TITLE;
    }

    public static function isEnabled()
    {
        $enabledMethod = 'is'.static::NAME.'Enabled';
        return self::$payModule->setting->$enabledMethod();
    }

}


foreach (OmisePaymentMethods::$list as $method) require_once _PS_MODULE_DIR_ . 'omise/payment_methods/' . strToLower(preg_replace('%([a-z])([A-Z])%', '\1_\2', $method)) . '.php';

