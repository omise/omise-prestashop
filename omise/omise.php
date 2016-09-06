<?php
if (! defined('_PS_VERSION_'))
    exit();

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
    }

    public function getContent()
    {
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings')
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable/Disable'),
                    'name' => 'module',
                    'is_bool' => true,
                    'desc' => $this->l('Enable Omise Payment Module.'),
                    'values' => array(
                        array(
                            'id' => 'module_enabled',
                            'value' => 1,
                            'label' => 'Enabled'
                        ),
                        array(
                            'id' => 'module_disabled',
                            'value' => 0,
                            'label' => 'Disabled'
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Sandbox'),
                    'name' => 'sandbox',
                    'is_bool' => true,
                    'desc' => $this->l('Enabling sandbox means that all your transactions will be in TEST mode.'),
                    'values' => array(
                        array(
                            'id' => 'sandbox_on',
                            'value' => 1,
                            'label' => 'Enabled'
                        ),
                        array(
                            'id' => 'sandbox_off',
                            'value' => 0,
                            'label' => 'Disabled'
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Public key for test'),
                    'name' => 'publicKeyForTest',
                    'required' => false,
                    'desc' => 'The "Test" mode public key can be found in Omise Dashboard.'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Secret key for test'),
                    'name' => 'secretKeyForTest',
                    'required' => false,
                    'desc' => 'The "Test" mode secret key can be found in Omise Dashboard.'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Public key for live'),
                    'name' => 'publicKeyForLive',
                    'required' => false,
                    'desc' => 'The "Live" mode public key can be found in Omise Dashboard.'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Secret key for live'),
                    'name' => 'secretKeyForLive',
                    'required' => false,
                    'desc' => 'The "Live" mode secret key can be found in Omise Dashboard.'
                ),
                array(
                    'label' => '<b>Advance Settings</b>'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'required' => false,
                    'desc' => 'This controls the title which the user sees during checkout.'
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();
        $helper->submit_action = 'submit' . $this->name;

        return $helper->generateForm($fields_form);
    }
}
