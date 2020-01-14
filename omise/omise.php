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
    require_once _PS_MODULE_DIR_ . 'omise/checkout_form.php';
    require_once _PS_MODULE_DIR_ . 'omise/setting.php';
    require_once _PS_MODULE_DIR_ . 'omise/payment_methods/_all.php';
}

class Omise extends PaymentModule
{
    /**
     * The name that will be display to the user at the back-end.
     *
     * @var string
     */
    const MODULE_DISPLAY_NAME = 'Omise';

    /**
     * The name that used to reference in the program.
     *
     * @var string
     */
    const MODULE_NAME = 'omise';

    /**
     * The version of the module.
     *
     * @var string
     */
    const MODULE_VERSION = '1.7.7';

    /**
     * The instance of class, CheckoutForm.
     *
     * @var \CheckoutForm
     */
    public $checkout_form;

    /**
     * The instance of class, OmiseTransactionModel.
     *
     * @var \OmiseTransactionModel
     */
    protected $omise_transaction_model;

    /**
     * The instance of class, Setting.
     *
     * The Setting class is used to manipulate the configuration of module.
     *
     * @var \Setting
     */
    public $setting;

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

        $this->checkout_form = new CheckoutForm();
        $this->omise_transaction_model = new OmiseTransactionModel();
        $this->setting = new Setting();

        OmisePaymentMethod::$payModule = $this;
        OmisePaymentMethod::$smarty = $this->smarty;
    }

    /**
     * Display the message about the inapplicable checkout condition.
     *
     * @return string Return the rendered template output. (@see Smarty_Internal_TemplateBase::display())
     */
    protected function displayInapplicablePayment($title = null)
    {
        if ($title) $this->smarty->assign('title', $title);
        return $this->display(__FILE__, 'inapplicable_payment.tpl');
    }

    /**
     * @return PrestaShop\PrestaShop\Core\Payment\PaymentOption
     */
    protected function generatePaymentOption($method)
    {
        $payment_option_class = PRESTASHOP_PAYMENT_OPTION_CLASS;
        $payment_option = new $payment_option_class();
        $class = OmisePaymentMethods::className($method);

        $payment_option->setCallToActionText($class::getCallToActionText());
        $payment_option->setModuleName($class::PAYMENT_OPTION_NAME);

        if ($this->isCurrentCurrencyApplicable()) {
            $payment_option->setForm($class::display());
        } else {
            $payment_option->setAdditionalInformation($this->displayInapplicablePayment());
        }

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
            'webhooks_endpoint' => $this->getWebhooksEndpoint()
        );

        foreach ($this->setting->all_settings as $settingName) $smartyVars[$settingName] = $this->setting->{$settingName}();

        $this->smarty->assign($smartyVars);

        return $this->display(__FILE__, 'views/templates/admin/setting.tpl');
    }

    /**
     * Generate the URL to receive the requests from Omise server when events are triggered.
     *
     * The examples of events such as charge has been created, updated or charge is completed.
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
        if ($this->setting->isInternetBankingEnabled()) {
            $this->context->controller->addCSS($this->_path . 'css/omise_internet_banking.css', 'all');
            $this->context->controller->addJqueryPlugin('fancybox');
        }

        // Test mode warning
        if ($this->context->controller->php_self == 'order' && $this->setting->isModuleEnabled() && $this->setting->isSandboxEnabled()) {
            $this->context->controller->addJS($this->_path . 'js/test_warn.js', true);
            $this->context->controller->addCSS($this->_path . 'css/omise_test_mode.css', 'all');
            return $this->display(__FILE__, 'omise_warning_message.tpl');
        }
    }

    public function hookPaymentOptions()
    {
        $payment_options = array();

        foreach(OmisePaymentMethods::$list as $method) {
            $class = OmisePaymentMethods::className($method);
            if ($class::isEnabled()) $payment_options[] = $this->generatePaymentOption($method);
        }

        return count($payment_options) ? $payment_options : null;
    }

    // For PrestaShop 1.6
    public function hookPayment()
    {
        if (!$this->active) return;

        $payment = '';

        foreach(OmisePaymentMethods::$list as $method) {
            $class = OmisePaymentMethods::className($method);
            if ($class::isEnabled()) {
                $payment .= $this->isCurrentCurrencyApplicable() ?
                    $class::display() :
                    $this->displayInapplicablePayment($this->l($class::getCallToActionText()));
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
     * Check whether the current currency is supported by the Omise API.
     *
     * @return bool
     */
    protected function isCurrentCurrencyApplicable()
    {
        return OmisePluginHelperCharge::isCurrentCurrencyApplicable($this->context->currency->iso_code);
    }

    /**
     * Generate the URL that used for receive the payment information that submit from checkout form.
     *
     * The URL will be used at the attribute, action, of HTML, form.
     *
     * @return string Return the URL that link to module controller.
     *
     * @see LinkCore::getModuleLink()
     */
    public function getAction()
    {
        $controller = $this->setting->isThreeDomainSecureEnabled() ? 'threedomainsecurepayment' :'payment';
        return $this->context->link->getModuleLink(self::MODULE_NAME, $controller, [], true);
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
