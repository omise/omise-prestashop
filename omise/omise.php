<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

define('IS_VERSION_17', _PS_VERSION_ >= '1.7');

if (IS_VERSION_17) {
    define('PRESTASHOP_PAYMENTMODULE_HOOKS', "displayOrderConfirmation,header,paymentOptions");
    define('PRESTASHOP_GET_ORDER_ID_METHOD', "getIdByCartId");
    define('PRESTASHOP_PAYMENT_OPTION_CLASS', "PrestaShop\PrestaShop\Core\Payment\PaymentOption");
    define('PRESTASHOP_HOOK_DISPLAYORDERCONFIRM_ORDER_PARAM', "order");
    define('PRESTASHOP_VERSION_VIEW_PATH', '1.7/');
} else {
    define('PRESTASHOP_PAYMENTMODULE_HOOKS', "displayOrderConfirmation,header,payment");
    define('PRESTASHOP_GET_ORDER_ID_METHOD', "getOrderByCartId");
    define('PRESTASHOP_PAYMENT_OPTION_CLASS', "PaymentOption");
    define('PRESTASHOP_HOOK_DISPLAYORDERCONFIRM_ORDER_PARAM', "objOrder");
    define('PRESTASHOP_VERSION_VIEW_PATH', '1.6/');
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_transaction_model.php';
    require_once _PS_MODULE_DIR_ . 'omise/libraries/omise-plugin/Omise.php';
    require_once _PS_MODULE_DIR_ . 'omise/setting.php';
    require_once _PS_MODULE_DIR_ . 'omise/payment_methods/_all.php';
}

class Omise extends PaymentModule
{
    const
        MODULE_DISPLAY_NAME = 'Omise', // The name that will be display to the user at the back-end
        MODULE_NAME = 'omise', // The name that used to reference in the program
        MODULE_VERSION = '1.7.10' // The version of the module
    ;

    public
        $setting // Setting instance
    ;

    protected
        $omise_transaction_model, // OmiseTransactionModel instance
        $paymentMethodClassList = array()
    ;

    public function __construct()
    {
        $this->name                   = self::MODULE_NAME;
        $this->tab                    = 'payments_gateways';
        $this->version                = self::MODULE_VERSION;
        $this->author                 = 'Omise';
        $this->need_instance          = 1;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.7');
        $this->bootstrap              = true;

        $this->currencies_mode        = 'checkbox';

        parent::__construct();

        $this->displayName            = self::MODULE_DISPLAY_NAME;
        $this->confirmUninstall       = $this->l('Are you sure you want to uninstall the ' . self::MODULE_DISPLAY_NAME . ' module?');

        $this->omise_transaction_model = new OmiseTransactionModel();
        $this->setting = new Setting();
        $this->buildPaymentMethodList();

        OmisePaymentMethod::$payModule = $this;
        OmisePaymentMethod::$context = $this->context;
        OmisePaymentMethod::$smarty = $this->smarty;

    }


    protected function buildPaymentMethodList()
    {
        foreach(OmisePaymentMethods::$list as $method) $this->paymentMethodClassList[] = OmisePaymentMethods::className($method);
    }

    /**
     * @return PrestaShop\PrestaShop\Core\Payment\PaymentOption
     */
    protected function generatePaymentOption($method)
    {
        $payment_option_class = PRESTASHOP_PAYMENT_OPTION_CLASS;
        $payment_option = new $payment_option_class();
        $class = OmisePaymentMethods::className($method);

        $payment_option->setCallToActionText($class::getTitle());
        $payment_option->setModuleName($class::PAYMENT_OPTION_NAME);

        $payment_option->setForm($class::display());

        return $payment_option;
    }

    public function getContent()
    {
        if ($this->setting->isSubmit()) {
            $this->setting->save();
            $this->smarty->assign('confirmation', $this->displayConfirmation($this->l('Settings updated')));
        }

        $smartyVars = array(
            'submit_action' => $this->setting->getSubmitAction(),
            'webhooks_endpoint' => $this->getWebhooksEndpoint(),
            'cfg' => array(),
            'methodObjects' => array()
        );

        foreach ($this->setting->all_settings as $settingName) $smartyVars['cfg'][$settingName] = $this->setting->{$settingName}();
        foreach(OmisePaymentMethods::$list as $method) {
            $className = OmisePaymentMethods::className($method);
            $smartyVars['methodObjects'][$method] = new $className();
        }

        $smartyVars['method_admintemplate_path'] = dirname(__FILE__).'/payment_methods/admin_templates/';

        $this->smarty->assign($smartyVars);

        return $this->display(__FILE__, 'views/templates/admin/setting.tpl');
    }

    /**
     * Generate URL to receive requests from Omise server when events are triggered.
     *
     * @return string Return the URL that link to front module controller.
     *
     * @see LinkCore::getModuleLink() The PrestaShop function used to generate link.
     * @see OmiseWebhooksModuleFrontController The Omise controller used to handle requests from Omise server.
     */
    protected function getWebhooksEndpoint()
    {
        return $this->context->link->getModuleLink(Omise::MODULE_NAME, 'webhooks', [], true);
    }

    public function hookDisplayOrderConfirmation($params)
    {
        if (!$this->active) return;

        if ($params[PRESTASHOP_HOOK_DISPLAYORDERCONFIRM_ORDER_PARAM]->module != $this->name) return;

        $this->smarty->assign('order_reference', $params[PRESTASHOP_HOOK_DISPLAYORDERCONFIRM_ORDER_PARAM]->reference);

        return $this->versionSpecificDisplay('confirmation.tpl');
    }

    public function hookHeader()
    {
        $controller = $this->context->controller;

        // add required resources into page
        $resources = $this->getResourceLists();
        if (count($resources['cssFiles'])) $controller->addCSS(preg_replace('%(.+)%', $this->_path.'css/\1', $resources['cssFiles']));
        if (count($resources['jsFiles'])) $controller->addJS(preg_replace('%(.+)%', $this->_path.'js/\1', $resources['jsFiles']));
        if (count($resources['jqueryPlugins'])) $controller->addJqueryPlugin($resources['jqueryPlugins']);

        // Test mode warning
        if ($controller->php_self == 'order' && $this->setting->isSandboxEnabled()) {
            $controller->addJS($this->_path . 'js/test_warn.js', true);
            $controller->addCSS($this->_path . 'css/omise_test_mode.css', 'all');
            return $this->display(__FILE__, 'omise_warning_message.tpl');
        }
    }

    protected function getResourceLists()
    {
        $resTypes = array('jsFiles', 'cssFiles', 'jqueryPlugins');
        $res = array_combine($resTypes, array_fill(0, count($resTypes), array()));
        foreach($this->paymentMethodClassList as $class) {
            if ($class::isEnabled()) {
                foreach ($res as $type=>$list) $res[$type] = array_merge($list, $class::$$type);
            }
        }
        return array_combine($resTypes, array_map('array_unique', array_values($res)));
    }

    public function hookPaymentOptions()
    {
        $payment_options = array();

        foreach($this->paymentMethodClassList as $class) {
            if ($class::isEnabled() && $this->isCurrentCurrencyApplicable($class)) $payment_options[] = $this->generatePaymentOption($class::NAME);
        }

        return count($payment_options) ? $payment_options : null;
    }

    // For PrestaShop 1.6
    public function hookPayment()
    {
        if (!$this->active) return;

        $payment = '';

        foreach($this->paymentMethodClassList as $class) {
            if ($class::isEnabled()) {
                $payment .= $this->isCurrentCurrencyApplicable($class) ?
                    $class::display() :
                    '';
            }
        }

        return $payment;
    }


    public function install()
    {
        if (parent::install() == false
            || $this->applyToHooks(array($this, 'registerHook')) == false
            || $this->omise_transaction_model->createTable() == false
            || $this->setting->saveTitle(OmisePaymentMethod_Card::DEFAULT_TITLE) == false
        ) {
            $this->uninstall();
            return false;
        }

        return true;
    }

    /**
     * A PrestaShop version specific version of 'display'
     *
     * @return see parent 'display' method
     */
    public function versionSpecificDisplay($template)
    {
        return $this->display(__FILE__, PRESTASHOP_VERSION_VIEW_PATH . $template);
    }    

    /**
     * Apply passed callable function to all hooks for the module, bailing if any function fails
     *
     * @return bool
     */
    protected function applyToHooks($callable)
    {
        foreach (explode(',', PRESTASHOP_PAYMENTMODULE_HOOKS) as $hook) {
            if (!$res = call_user_func($callable, $hook)) break;
        }
        return $res;
    }    

    /**
     * Check whether the current currency is supported by the Omise API and the given payment method
     *
     * @return bool
     */
    protected function isCurrentCurrencyApplicable($paymentMethodClass)
    {
        return OmisePluginHelperCharge::isCurrentCurrencyApplicable($code=$this->context->currency->iso_code) && $paymentMethodClass::availableForCurrency($code);
    }


    /**
     * @return bool
     */
    public function uninstall()
    {
        $this->setting->delete();

        return parent::uninstall()
            && $this->applyToHooks(array($this, 'unregisterHook'));
    }

}
