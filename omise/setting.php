<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class Setting
{
    protected $submit_action = 'omise_save_setting';

    /**
     * @return string
     */
    public function getLivePublicKey()
    {
        return Configuration::get('live_public_key');
    }

    /**
     * @return string
     */
    public function getLiveSecretKey()
    {
        return Configuration::get('live_secret_key');
    }

    /**
     * Return the public key by checking whether
     * the current setting for sandbox status is enabled or disabled.
     *
     * Return the TEST public key, if the sandbox status is enabled (testing mode).
     * Return the LIVE public key, if the sandbox status is disabled (live mode).
     *
     * @return string
     */
    public function getPublicKey()
    {
        if ($this->isSandboxEnabled()) {
            return $this->getTestPublicKey();
        }

        return $this->getLivePublicKey();
    }

    /**
     * Return the secret key by checking whether
     * the current setting for sandbox status is enabled or disabled.
     *
     * Return the TEST secret key, if the sandbox status is enabled (testing mode).
     * Return the LIVE secret key, if the sandbox status is disabled (live mode).
     *
     * @return string
     */
    public function getSecretKey()
    {
        if ($this->isSandboxEnabled()) {
            return $this->getTestSecretKey();
        }

        return $this->getLiveSecretKey();
    }

    /**
     * @return string
     */
    public function getSubmitAction()
    {
        return $this->submit_action;
    }

    /**
     * @return string
     */
    public function getTestPublicKey()
    {
        return Configuration::get('test_public_key');
    }

    /**
     * @return string
     */
    public function getTestSecretKey()
    {
        return Configuration::get('test_secret_key');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return Configuration::get('title');
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        if (Configuration::get('module_status')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSandboxEnabled()
    {
        if (Configuration::get('sandbox_status')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
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
