<?php

class OmisePaymentMethod
{

    const
        ADMIN_TEMPLATE = '',
        STATUS_SETTING_KEY = ''
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
            'usedSettings' => self::allSettingKeys(),
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

    public static function statusSettingKey() {
        return static::STATUS_SETTING_KEY ?: substr(strToLower(preg_replace('%([A-Z])([a-z])%', '_\1\2', static::NAME)),1) . '_status';
    }

    public static function allSettingKeys() {
        return array_merge(array(static::statusSettingKey()), static::$usedSettings);
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

    public static function getLink($method, $params = [], $controller = 'paymentmethod', $ssl = true) 
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

    public static function getValidOrder($orderId, &$error, &$orderRef)
    {
        $order = new Order($orderId);

        if (!Validate::isLoadedObject($order)) {
            $error = 'Order not found.';
            return false;
        }

        $orderRef = $order->reference;

        if ($order->module != Omise::MODULE_NAME) {
            $error = 'Payment method is invalid.';
            return false;
        }

        return $order;

    }

    public static function getValidCharge($orderId, &$error)
    {

        $omiseTransaction = new OmiseTransactionModel();
        $omiseCharge = new OmiseChargeClass();

        $idCharge = $omiseTransaction->getIdCharge($orderId);

        try {
            $charge = $omiseCharge->retrieve($idCharge);
        } catch (Exception $e) {
            $error = $e->getMessage();
            return false;
        }

        return $charge;

    }

    public static function handleReturn($controller)
    {
        $cartId = Tools::getValue('id_cart');
        $moduleId = Tools::getValue('id_module');
        $orderId = Tools::getValue('id_order');
        $key = Tools::getValue('key');  
        $error = '';
        $orderRef = '';

        $paymentOrder = new PaymentOrder();

        // check we have a valid order
        $order = static::getValidOrder($orderId, $error, $orderRef);
        $controller->order_reference = $orderRef;
        if ( !$order ) {
            $controller->error_message = $controller->l($error);
            return;
        }

        // check we have a valid charge
        if ( !($charge = static::getValidCharge($orderId, $error)) ) {
            $controller->error_message = $error;
            return;
        }

        // check if charge failed
        if ($charge->isFailed()) {
            $paymentOrder->updateStateToBeCanceled($order); // TODO - check if this the right thing to be doing - can we not return to checkout?
            $controller->error_message = $charge->getErrorMessage();
            return;
        }

        // check if charge paid
        if ($charge->isPaid()) {
            $paymentOrder->updateStateToBeSuccess($order);
        }

        // redirect to confirmation
        $controller->setRedirectAfter(self::getOrderConfirmationUri($cartId, $moduleId, $orderId, $key));

    }

}
