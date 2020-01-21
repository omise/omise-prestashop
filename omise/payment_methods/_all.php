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
        $context,
        $smarty,
        $usedSettings = array(),
        $jsFiles = array(),
        $cssFiles = array(),
        $jqueryPlugins = array()
    ;

    public static function display()
    {
        self::setSmartyVars();
        return self::$payModule->versionSpecificDisplay(static::TEMPLATE . '.tpl');
    }

    public static function setSmartyVars()
    {
        self::$smarty->assign(static::getSmartyVars());
    }

    public static function getSmartyVars()
    {
        return array(
            'omise_title' => static::getTitle(),
            'action' => static::getAction(),
            'omise_public_key' => self::$payModule->setting->getPublicKey()
        );
    }

    public static function getTitle()
    {
        return static::DEFAULT_TITLE;
    }

    public static function getLink($controller, $params = []) 
    {
        return self::$context->link->getModuleLink(Omise::MODULE_NAME, $controller, $params, true);
    }

    public static function getAction() {
        // TODO - make this work properly
        return self::getLink(static::CONTROLLER);
    }

    public static function isEnabled()
    {
        $enabledMethod = 'is'.static::NAME.'Enabled';
        return self::$payModule->setting->$enabledMethod();
    }

}


foreach (OmisePaymentMethods::$list as $method) require_once _PS_MODULE_DIR_ . 'omise/payment_methods/' . strToLower(preg_replace('%([a-z])([A-Z])%', '\1_\2', $method)) . '.php';

