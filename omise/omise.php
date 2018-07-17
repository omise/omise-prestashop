<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


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
}

class Omise extends PaymentModule
{
    /**
     * The name that used as the identifier of card payment option.
     *
     * A payment module can has more than one payment option. At the front office, each payment options can be
     * identified by using module name (@see PaymentOption::setModuleName()).
     *
     * The module name is displayed at front office as an attribute of the payment option.
     *
     * @var string
     */
    const CARD_PAYMENT_OPTION_NAME = 'omise-card-payment';

    /**
     * The default title of card payment.
     *
     * This constant will be saved to the database at the module installation step. (@see Omise::install())
     *
     * @var string
     */
    const DEFAULT_CARD_PAYMENT_TITLE = 'Pay by Credit / Debit Card';

    /**
     * The default title of internet banking payment.
     *
     * @var string
     */
    const DEFAULT_INTERNET_BANKING_PAYMENT_TITLE = 'Internet Banking';

    /**
     * The name that used as the identifier of internet banking payment option.
     *
     * A payment module can has more than one payment option. At the front office, each payment options can be
     * identified by using module name (@see PaymentOption::setModuleName()).
     *
     * The module name is displayed at front office as an attribute of the payment option.
     *
     * @var string
     */
    const INTERNET_BANKING_PAYMENT_OPTION_NAME = 'omise-internet-banking-payment';

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
    const MODULE_VERSION = '1.6';

    /**
     * The instance of class, CheckoutForm.
     *
     * @var \CheckoutForm
     */
    protected $checkout_form;

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
    protected $setting;

    public function __construct()
    {
        $this->name                   = self::MODULE_NAME;
        $this->tab                    = 'payments_gateways';
        $this->version                = self::MODULE_VERSION;
        $this->author                 = 'Omise';
        $this->need_instance          = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.7');
        $this->bootstrap              = true;

        $this->currencies_mode        = 'checkbox';

        parent::__construct();

        $this->displayName            = self::MODULE_DISPLAY_NAME;
        $this->confirmUninstall       = $this->l('Are you sure you want to uninstall the ' . self::MODULE_DISPLAY_NAME . ' module?');

        $this->setCheckoutForm(new CheckoutForm());
        $this->setOmiseTransactionModel(new OmiseTransactionModel());
        $this->setSetting(new Setting());
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
     * Display the internet banking checkout form.
     *
     * @return string Return the rendered template output. (@see Smarty_Internal_TemplateBase::display())
     */
    protected function displayInternetBankingPayment()
    {
        return $this->versionSpecificDisplay(__FILE__, 'internet_banking_payment.tpl');
    }

    /**
     * Display the checkout form.
     *
     * @return string Return the rendered template output. (@see Smarty_Internal_TemplateBase::display())
     */
    protected function displayCardPayment()
    {
        $this->smarty->assign('action', $this->getAction());
        $this->smarty->assign('list_of_expiration_year', $this->checkout_form->getListOfExpirationYear());
        $this->smarty->assign('omise_public_key', $this->setting->getPublicKey());
        $this->smarty->assign('omise_title', $this->setting->getTitle());

        return $this->versionSpecificDisplay(__FILE__, 'card_payment.tpl');
    }

    /**
     * @return PrestaShop\PrestaShop\Core\Payment\PaymentOption
     */
    protected function generateCardPaymentOption()
    {
        $payment_option_class = PRESTASHOP_PAYMENT_OPTION_CLASS;
        $payment_option = new $payment_option_class();

        $payment_option->setCallToActionText($this->setting->getTitle());
        $payment_option->setModuleName(self::CARD_PAYMENT_OPTION_NAME);

        if ($this->isCurrentCurrencyApplicable()) {
            $payment_option->setForm($this->displayCardPayment());
        } else {
            $payment_option->setAdditionalInformation($this->displayInapplicablePayment());
        }

        return $payment_option;
    }

    /**
     * @return PrestaShop\PrestaShop\Core\Payment\PaymentOption
     */
    protected function generateInternetBankingPaymentOption()
    {
        $payment_option_class = PRESTASHOP_PAYMENT_OPTION_CLASS;
        $payment_option = new $payment_option_class();

        $payment_option->setCallToActionText(self::DEFAULT_INTERNET_BANKING_PAYMENT_TITLE);
        $payment_option->setModuleName(self::INTERNET_BANKING_PAYMENT_OPTION_NAME);

        if ($this->isCurrentCurrencyApplicable()) {
            $payment_option->setForm($this->displayInternetBankingPayment());
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

        $this->smarty->assign(array(
            'internet_banking_status' => $this->setting->isInternetBankingEnabled(),
            'live_public_key' => $this->setting->getLivePublicKey(),
            'live_secret_key' => $this->setting->getLiveSecretKey(),
            'module_status' => $this->setting->isModuleEnabled(),
            'sandbox_status' => $this->setting->isSandboxEnabled(),
            'submit_action' => $this->setting->getSubmitAction(),
            'test_public_key' => $this->setting->getTestPublicKey(),
            'test_secret_key' => $this->setting->getTestSecretKey(),
            'title' => $this->setting->getTitle(),
            'three_domain_secure_status' => $this->setting->isThreeDomainSecureEnabled(),
            'webhooks_endpoint' => $this->getWebhooksEndpoint(),
        ));

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
        if ($this->active == false) {
            return;
        }

        if ($params[PRESTASHOP_HOOK_DISPLAYORDERCONFIRM_ORDER_PARAM]->module != $this->name) {
            return;
        }

        $this->smarty->assign('order_reference', $params[PRESTASHOP_HOOK_DISPLAYORDERCONFIRM_ORDER_PARAM]->reference);

        return $this->versionSpecificDisplay(__FILE__, 'confirmation.tpl');
    }

    public function hookHeader()
    {
        if ($this->setting->isInternetBankingEnabled()) {
            $this->context->controller->addCSS($this->_path . 'css/omise_internet_banking.css', 'all');
            $this->context->controller->addJqueryPlugin('fancybox');
        }
    }

    public function hookPaymentOptions()
    {
        $payment_options = array();

        if ($this->setting->isModuleEnabled()) {
            $payment_options[] = $this->generateCardPaymentOption();
        }

        if ($this->setting->isInternetBankingEnabled()) {
            $payment_options[] = $this->generateInternetBankingPaymentOption();
        }

        if (count($payment_options) == 0) {
            return null;
        }

        return $payment_options;
    }

    // For PrestaShop 1.6
    public function hookPayment()
    {
        if (!$this->active) return;

        $payment = '';

        if ($this->setting->isModuleEnabled()) {
            $payment .= $this->isCurrentCurrencyApplicable() ? 
                $this->displayCardPayment() :
                $this->displayInapplicablePayment($this->setting->getTitle());
        }

        if ($this->setting->isInternetBankingEnabled()) {
            $payment .= $this->isCurrentCurrencyApplicable() ? 
                $this->displayInternetBankingPayment() :
                $this->displayInapplicablePayment($this->l("Internet Banking"));
        }

        return $payment;
    }


    public function install()
    {
        if (parent::install() == false
            || $this->applyToHooks(array($this, 'registerHook')) == false
            || $this->omise_transaction_model->createTable() == false
            || $this->setting->saveTitle(self::DEFAULT_CARD_PAYMENT_TITLE) == false
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
    protected function versionSpecificDisplay($file, $template)
    {
        return $this->display($file, PRESTASHOP_VERSION_VIEW_PATH . $template);
    }    

    /**
     * Register/Unregister all hooks for the module
     *
     * @return bool
     */
    protected function applyToHooks($callable)
    {
        $res = true;
        foreach (explode(',', PRESTASHOP_PAYMENTMODULE_HOOKS) as $hook) {
            if (!$res &= call_user_func($callable, $hook)) break;
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
    protected function getAction()
    {
        $controller = 'payment';

        if ($this->setting->isThreeDomainSecureEnabled()) {
            $controller = 'threedomainsecurepayment';
        }

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

    /**
     * @return \Setting
     */
    public function getSetting()
    {
        return $this->setting;
    }

    /**
     * @param \CheckoutForm $checkout_form The instance of class, CheckoutForm.
     */
    public function setCheckoutForm($checkout_form)
    {
        $this->checkout_form = $checkout_form;
    }

    /**
     * @param \OmiseTransactionModel $omise_transaction_model The instance of class, OmiseTransactionModel.
     */
    public function setOmiseTransactionModel($omise_transaction_model)
    {
        $this->omise_transaction_model = $omise_transaction_model;
    }

    /**
     * @param \Setting $setting The instance of class, Setting.
     */
    public function setSetting($setting)
    {
        $this->setting = $setting;
    }

    /**
     * @param \Smarty_Data $smarty The instance of class, Smarty_Data.
     */
    public function setSmarty($smarty)
    {
        $this->smarty = $smarty;
    }
}
