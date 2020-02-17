<?php

class OmisePaymentMethods
{
    static $list = array(
        'Card',
        'InternetBanking',
        'Alipay',
        'CitiPoints',
        'TrueMoney'
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
            'payment_method_name' => static::NAME,
            'payment_option_name' => static::PAYMENT_OPTION_NAME,
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

    public static function getLink($method, $params = [], $controller = 'paymentmethod', $ssl = false) 
    {
        return self::$context->link->getModuleLink(Omise::MODULE_NAME, $controller, array_merge($params, array('type' => $method)), $ssl);
    }

    public static function getAction() {
        return self::getLink(static::NAME);
    }

    public static function getReturnUri($cartId, $key) {
        $id_order = Order::{PRESTASHOP_GET_ORDER_ID_METHOD}($cartId);
        $module = Module::getInstanceByName(Omise::MODULE_NAME);
        return self::getLink(static::NAME, array(
            'id_cart' => $cartId,
            'id_module' => $module->id,
            'id_order' => $id_order,
            'key' => $key
        ), 'paymentreturn', true);

    }

    public static function isEnabled()
    {
        $enabledMethod = 'is'.static::NAME.'Enabled';
        return self::$payModule->setting->$enabledMethod();
    }

    public static function getOrderConfirmationUri($cartId, $moduleId, $orderId, $key)
    {
        return "index.php?controller=order-confirmation&id_cart=$cartId&id_module=$moduleId&id_order=$orderId&key=$key";
    }

    public static function handleReturn($controller, $context)
    {

    }

}


foreach (OmisePaymentMethods::$list as $method) require_once _PS_MODULE_DIR_ . 'omise/payment_methods/' . strToLower(preg_replace('%([a-z])([A-Z])%', '\1_\2', $method)) . '.php';

