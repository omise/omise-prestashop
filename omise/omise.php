<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

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
    const MODULE_VERSION = '1.2';

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

        parent::__construct();

        $this->displayName            = self::MODULE_DISPLAY_NAME;
        $this->confirmUninstall       = $this->l('Are you sure you want to uninstall ' . self::MODULE_DISPLAY_NAME . ' module?');

        $this->setCheckoutForm(new CheckoutForm());
        $this->setOmiseTransactionModel(new OmiseTransactionModel());
        $this->setSetting(new Setting());
    }

    /**
     * Display the message about the inapplicable checkout condition.
     *
     * @return string Return the rendered template output. (@see Smarty_Internal_TemplateBase::display())
     */
    protected function displayInapplicablePayment()
    {
        $this->smarty->assign('omise_title', $this->setting->getTitle());

        return $this->display(__FILE__, 'inapplicable_payment.tpl');
    }

    /**
     * Display the internet banking checkout form.
     *
     * @return string Return the rendered template output. (@see Smarty_Internal_TemplateBase::display())
     */
    protected function displayInternetBankingPayment()
    {
        return $this->display(__FILE__, 'internet_banking_payment.tpl');
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

        return $this->display(__FILE__, 'card_payment.tpl');
    }

    /**
     * @return PrestaShop\PrestaShop\Core\Payment\PaymentOption
     */
    protected function generateCardPaymentOption()
    {
        $payment_option = new PaymentOption();

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
        $payment_option = new PaymentOption();

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
        ));

        return $this->display(__FILE__, 'views/templates/admin/setting.tpl');
    }

    public function hookDisplayOrderConfirmation($params)
    {
        if ($this->active == false) {
            return;
        }

        if ($params['order']->module != $this->name) {
            return;
        }

        $this->smarty->assign('order_reference', $params['order']->reference);

        return $this->display(__FILE__, 'confirmation.tpl');
    }

    public function hookHeader()
    {
        if ($this->setting->isInternetBankingEnabled()) {
            $this->context->controller->addCSS($this->_path . 'css/omise_internet_banking.css', 'all');
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

    public function install()
    {
        if (parent::install() == false
            || $this->registerHook('displayOrderConfirmation') == false
            || $this->registerHook('header') == false
            || $this->registerHook('paymentOptions') == false
            || $this->omise_transaction_model->createTable() == false
            || $this->setting->saveTitle(self::DEFAULT_CARD_PAYMENT_TITLE) == false
        ) {
            $this->uninstall();

            return false;
        }

        return true;
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
            && $this->unregisterHook('displayOrderConfirmation')
            && $this->unregisterHook('header')
            && $this->unregisterHook('paymentOptions');
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
