<?php

class OmisePaymentMethods
{
    static $list = array(
        'Card',
        'InternetBanking',
        'Alipay',
        'CitiPoints'
    );

    public static function className($method) { return 'OmisePaymentMethod_'.$method; } 
}

class OmisePaymentMethod
{

    const
        ADMIN_TEMPLATE = ''
    ;

    public static
        $payModule,
        $context,
        $smarty,
        $usedSettings = array(),
        $jsFiles = array(),
        $cssFiles = array(),
        $jqueryPlugins = array(),
        $restrictedToCurrencies = array()
    ;

    // necessary for dealing with a deficiency in older Smarty version in PrestaShop 1.6
    public static function getAdminDetails() {
        return array(
            'name' => static::NAME,
            'title' => static::DEFAULT_TITLE,
            'usedSettings' => static::$usedSettings,
            'switchDescription' => static::SWITCH_DESCRIPTION,
            'adminTemplate' => static::ADMIN_TEMPLATE,
            'currencies' => static::$restrictedToCurrencies
        );
    }

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

    public static function availableForCurrency($code)
    {
        $curr = strToLower($code);
        return count(static::$restrictedToCurrencies) ? in_array($curr, static::$restrictedToCurrencies) : true;
    }

    public static function getLink($method, $params = []) 
    {
        return self::$context->link->getModuleLink(Omise::MODULE_NAME, 'paymentmethod', array_merge($params, array('type' => $method)));
    }

    public static function getAction() {
        return self::getLink(static::NAME);
    }

    public static function isEnabled()
    {
        $enabledMethod = 'is'.static::NAME.'Enabled';
        return self::$payModule->setting->$enabledMethod();
    }

}


foreach (OmisePaymentMethods::$list as $method) require_once _PS_MODULE_DIR_ . 'omise/payment_methods/' . strToLower(preg_replace('%([a-z])([A-Z])%', '\1_\2', $method)) . '.php';

