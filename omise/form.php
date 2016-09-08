<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class Form extends PaymentModule
{
    protected $submit_action_name = 'submitOmiseForm';

    public function generate()
    {
        $helper = new HelperForm();

        $helper->submit_action = $this->submit_action_name;
        $helper->fields_value['module'] = Configuration::get('module');
        $helper->fields_value['sandbox'] = Configuration::get('sandbox');
        $helper->fields_value['publicKeyForTest'] = Configuration::get('publicKeyForTest');
        $helper->fields_value['secretKeyForTest'] = Configuration::get('secretKeyForTest');
        $helper->fields_value['publicKeyForLive'] = Configuration::get('publicKeyForLive');
        $helper->fields_value['secretKeyForLive'] = Configuration::get('secretKeyForLive');
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
                    'desc' => $this->l('The "Test" mode public key can be found in Omise Dashboard.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Secret key for test'),
                    'name' => 'secretKeyForTest',
                    'required' => false,
                    'desc' => $this->l('The "Test" mode secret key can be found in Omise Dashboard.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Public key for live'),
                    'name' => 'publicKeyForLive',
                    'required' => false,
                    'desc' => $this->l('The "Live" mode public key can be found in Omise Dashboard.')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Secret key for live'),
                    'name' => 'secretKeyForLive',
                    'required' => false,
                    'desc' => $this->l('The "Live" mode secret key can be found in Omise Dashboard.')
                ),
                array(
                    'label' => '<b>' . $this->l('Advance Settings') . '</b>'
                ),
                array(
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

    public function isSubmit()
    {
        if (Tools::isSubmit($this->submit_action_name)) {
            return true;
        } else {
            return false;
        }
    }

    public function save()
    {
        Configuration::updateValue('module', strval(Tools::getValue('module')));
        Configuration::updateValue('sandbox', strval(Tools::getValue('sandbox')));
        Configuration::updateValue('publicKeyForTest', strval(Tools::getValue('publicKeyForTest')));
        Configuration::updateValue('secretKeyForTest', strval(Tools::getValue('secretKeyForTest')));
        Configuration::updateValue('publicKeyForLive', strval(Tools::getValue('publicKeyForLive')));
        Configuration::updateValue('secretKeyForLive', strval(Tools::getValue('secretKeyForLive')));
        Configuration::updateValue('title', strval(Tools::getValue('title')));
    }

    public function getSubmitActionName()
    {
        return $this->submit_action_name;
    }
}
