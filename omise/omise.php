<?php
if (! defined('_PS_VERSION_')) {
    exit();
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
    const MODULE_VERSION = '1.6.0.0';

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

    protected $checkout_form;

    public function __construct()
    {
        $this->name                   = self::MODULE_NAME;
        $this->tab                    = 'payments_gateways';
        $this->version                = self::MODULE_VERSION;
        $this->author                 = 'Omise';
        $this->need_instance          = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6');
        $this->bootstrap              = true;

        parent::__construct();

        $this->displayName            = self::MODULE_DISPLAY_NAME;
        $this->confirmUninstall       = $this->l('Are you sure you want to uninstall ' . self::MODULE_DISPLAY_NAME . ' module?');

        $this->setCheckoutForm(new CheckoutForm());
        $this->setSetting(new Setting());
    }

    /**
     * Display the message about the inapplicable checkout condition.
     *
     * @return string rendered template output (@see Smarty_Internal_TemplateBase::display())
     */
    protected function displayInapplicablePayment()
    {
        $this->smarty->assign('omise_title', $this->setting->getTitle());

        return $this->display(__FILE__, 'inapplicable_payment.tpl');
    }

    /**
     * Display the checkout form.
     *
     * @return string rendered template output (@see Smarty_Internal_TemplateBase::display())
     */
    protected function displayPayment()
    {
        $this->smarty->assign('action', $this->getAction());
        $this->smarty->assign('list_of_expiration_year', $this->checkout_form->getListOfExpirationYear());
        $this->smarty->assign('omise_public_key', $this->setting->getPublicKey());
        $this->smarty->assign('omise_title', $this->setting->getTitle());

        return $this->display(__FILE__, 'payment.tpl');
    }

    public function getContent()
    {
        if ($this->setting->isSubmit()) {
            $this->setting->save();
            $this->smarty->assign('confirmation', $this->displayConfirmation($this->l('Settings updated')));
        }

        $this->smarty->assign(array(
            'live_public_key' => $this->setting->getLivePublicKey(),
            'live_secret_key' => $this->setting->getLiveSecretKey(),
            'module_status' => $this->setting->isModuleEnabled(),
            'sandbox_status' => $this->setting->isSandboxEnabled(),
            'submit_action' => $this->setting->getSubmitAction(),
            'test_public_key' => $this->setting->getTestPublicKey(),
            'test_secret_key' => $this->setting->getTestSecretKey(),
            'title' => $this->setting->getTitle(),
            'three_domain_secure_status' => $this->setting->isThreeDomainSecureEnabled(),
        ));

        return $this->display(__FILE__, 'views/templates/admin/setting.tpl');
    }

    public function hookDisplayOrderConfirmation($params)
    {
        if ($this->active == false) {
            return;
        }

        if ($params['objOrder']->module != $this->name) {
            return;
        }

        $this->smarty->assign('order_reference', $params['objOrder']->reference);

        return $this->display(__FILE__, 'confirmation.tpl');
    }

    public function hookPayment()
    {
        if ($this->active == false || $this->setting->isModuleEnabled() == false) {
            return;
        }

        if ($this->isCurrentCurrencyApplicable()) {
            return $this->displayPayment();
        }

        return $this->displayInapplicablePayment();
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('payment')
            && $this->registerHook('displayOrderConfirmation');
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

    protected function getAction()
    {
        $controller = 'payment';

        if ($this->setting->isThreeDomainSecureEnabled()) {
            $controller = 'threedomainsecurepayment';
        }

        return $this->context->link->getModuleLink(self::MODULE_NAME, $controller, [], true);
    }

    /**
     * @return \Setting
     */
    public function getSetting()
    {
        return $this->setting;
    }

    public function setCheckoutForm($checkout_form)
    {
        $this->checkout_form = $checkout_form;
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
