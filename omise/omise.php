<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

require_once 'setting.php';

class Omise extends PaymentModule
{
    /**
     * The name that will be display to the user at the back-end.
     *
     * @var string
     */
    const MODULE_DISPLAY_NAME = 'Omise';

    /**
     * The name that used to referece in the program.
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

    protected $setting;

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

        $this->setSetting(new Setting());
    }

    public function getContent()
    {
        $content = '';

        if ($this->setting->isSubmit()) {
            $this->setting->save();
            $content .= $this->displayConfirmation($this->l('Settings updated'));
        }

        $content .= $this->setting->generateForm();

        return $content;
    }

    public function hookPayment($params)
    {
        if ($this->active == false || $this->setting->isModuleEnabled() == false) {
            return;
        }

        $this->smarty->assign('omise_title', $this->setting->getTitle());

        return $this->display(__FILE__, 'payment.tpl');
    }

    public function getSetting()
    {
        return $this->setting;
    }

    public function setSetting($setting)
    {
        $this->setting = $setting;
    }

    public function setSmarty($smarty)
    {
        $this->smarty = $smarty;
    }
}
