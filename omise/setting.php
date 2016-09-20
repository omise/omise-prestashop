<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class Setting extends PaymentModule
{
    protected $submit_action = 'omise_save_setting';

    public function getLivePublicKey()
    {
        return Configuration::get('live_public_key');
    }

    public function getLiveSecretKey()
    {
        return Configuration::get('live_secret_key');
    }

    public function getPublicKey()
    {
        if ($this->isSandboxEnabled()) {
            return Configuration::get('test_public_key');
        }

        return Configuration::get('live_public_key');
    }

    public function getSubmitAction()
    {
        return $this->submit_action;
    }

    public function getTestPublicKey()
    {
        return Configuration::get('test_public_key');
    }

    public function getTestSecretKey()
    {
        return Configuration::get('test_secret_key');
    }

    public function getTitle()
    {
        return Configuration::get('title');
    }

    public function isModuleEnabled()
    {
        if (Configuration::get('module_status')) {
            return true;
        }

        return false;
    }

    public function isSandboxEnabled()
    {
        if (Configuration::get('sandbox_status')) {
            return true;
        }

        return false;
    }

    public function isSubmit()
    {
        if (Tools::isSubmit($this->getSubmitAction())) {
            return true;
        }

        return false;
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
}
