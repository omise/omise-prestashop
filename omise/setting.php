<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class Setting extends PaymentModule
{
    protected $submit_action = 'omise_save_setting';

    public function generateForm()
    {
        $helper = new HelperForm();

        $helper->submit_action = $this->getSubmitAction();
        $helper->fields_value['module_status'] = Configuration::get('module_status');
        $helper->fields_value['sandbox_status'] = Configuration::get('sandbox_status');
        $helper->fields_value['test_public_key'] = Configuration::get('test_public_key');
        $helper->fields_value['test_secret_key'] = Configuration::get('test_secret_key');
        $helper->fields_value['live_public_key'] = Configuration::get('live_public_key');
        $helper->fields_value['live_secret_key'] = Configuration::get('live_secret_key');
        $helper->fields_value['title'] = Configuration::get('title');

        $fields = $this->getFields();

        return $helper->generateForm($fields);
    }

    public function getFields()
    {
        $fields[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings')
            ),
            'input' => array(
                'module_status' => array(
                    'type' => 'switch',
                    'label' => $this->l('Enable/Disable'),
                    'name' => 'module_status',
                    'is_bool' => true,
                    'desc' => $this->l('Enable Omise Payment Module.'),
                    'values' => array(
                        array(
                            'id' => 'module_status_enabled',
                            'value' => 1,
                            'label' => 'Enabled'
                        ),
                        array(
                            'id' => 'module_status_disabled',
                            'value' => 0,
                            'label' => 'Disabled'
                        )
                    )
                ),
                'sandbox_status' => array(
                    'type' => 'switch',
                    'label' => $this->l('Sandbox'),
                    'name' => 'sandbox_status',
                    'is_bool' => true,
                    'desc' => $this->l('Enabling sandbox means that all your transactions will be in TEST mode.'),
                    'values' => array(
                        array(
                            'id' => 'sandbox_status_enabled',
                            'value' => 1,
                            'label' => 'Enabled'
                        ),
                        array(
                            'id' => 'sandbox_status_disabled',
                            'value' => 0,
                            'label' => 'Disabled'
                        )
                    )
                ),
                'test_public_key' => array(
                    'type' => 'text',
                    'label' => $this->l('Public key for test'),
                    'name' => 'test_public_key',
                    'required' => false,
                    'desc' => $this->l('The "Test" mode public key can be found in Omise Dashboard.')
                ),
                'test_secret_key' => array(
                    'type' => 'text',
                    'label' => $this->l('Secret key for test'),
                    'name' => 'test_secret_key',
                    'required' => false,
                    'desc' => $this->l('The "Test" mode secret key can be found in Omise Dashboard.')
                ),
                'live_public_key' => array(
                    'type' => 'text',
                    'label' => $this->l('Public key for live'),
                    'name' => 'live_public_key',
                    'required' => false,
                    'desc' => $this->l('The "Live" mode public key can be found in Omise Dashboard.')
                ),
                'live_secret_key' => array(
                    'type' => 'text',
                    'label' => $this->l('Secret key for live'),
                    'name' => 'live_secret_key',
                    'required' => false,
                    'desc' => $this->l('The "Live" mode secret key can be found in Omise Dashboard.')
                ),
                'advance_settings' => array(
                    'label' => '<b>' . $this->l('Advance Settings') . '</b>'
                ),
                'title' => array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'title',
                    'required' => false,
                    'desc' => $this->l('This controls the title which the user sees during checkout.')
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        return $fields;
    }

    public function getTitle()
    {
        return Configuration::get('title');
    }

    public function isModuleEnabled()
    {
        return Configuration::get('module_status');
    }

    public function isSubmit()
    {
        if (Tools::isSubmit($this->getSubmitAction())) {
            return true;
        } else {
            return false;
        }
    }

    public function save()
    {
        Configuration::updateValue('module_status', strval(Tools::getValue('module_status')));
        Configuration::updateValue('sandbox_status', strval(Tools::getValue('sandbox_status')));
        Configuration::updateValue('test_public_key', strval(Tools::getValue('test_public_key')));
        Configuration::updateValue('test_secret_key', strval(Tools::getValue('test_secret_key')));
        Configuration::updateValue('live_public_key', strval(Tools::getValue('live_public_key')));
        Configuration::updateValue('live_secret_key', strval(Tools::getValue('live_secret_key')));
        Configuration::updateValue('title', strval(Tools::getValue('title')));
    }

    public function getSubmitAction()
    {
        return $this->submit_action;
    }
}
