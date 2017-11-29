<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class Setting
{
    protected $submit_action = 'omise_save_setting';

    /**
     * Delete all setting values.
     */
    public function delete()
    {
        Configuration::deleteByName('omise_module_status');
        Configuration::deleteByName('omise_sandbox_status');
        Configuration::deleteByName('omise_test_public_key');
        Configuration::deleteByName('omise_test_secret_key');
        Configuration::deleteByName('omise_live_public_key');
        Configuration::deleteByName('omise_live_secret_key');
        Configuration::deleteByName('omise_title');
        Configuration::deleteByName('omise_three_domain_secure_status');
        Configuration::deleteByName('omise_internet_banking_status');
    }

    /**
     * @return string
     */
    public function getLivePublicKey()
    {
        return Configuration::get('omise_live_public_key');
    }

    /**
     * @return string
     */
    public function getLiveSecretKey()
    {
        return Configuration::get('omise_live_secret_key');
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
        return Configuration::get('omise_test_public_key');
    }

    /**
     * @return string
     */
    public function getTestSecretKey()
    {
        return Configuration::get('omise_test_secret_key');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return Configuration::get('omise_title');
    }

    /**
     * @return bool
     */
    public function isInternetBankingEnabled()
    {
        if (Configuration::get('omise_internet_banking_status')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        if (Configuration::get('omise_module_status')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSandboxEnabled()
    {
        if (Configuration::get('omise_sandbox_status')) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isThreeDomainSecureEnabled()
    {
        if (Configuration::get('omise_three_domain_secure_status')) {
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
        Configuration::updateValue('omise_module_status', strval(Tools::getValue('module_status')));
        Configuration::updateValue('omise_sandbox_status', strval(Tools::getValue('sandbox_status')));
        Configuration::updateValue('omise_test_public_key', strval(Tools::getValue('test_public_key')));
        Configuration::updateValue('omise_test_secret_key', strval(Tools::getValue('test_secret_key')));
        Configuration::updateValue('omise_live_public_key', strval(Tools::getValue('live_public_key')));
        Configuration::updateValue('omise_live_secret_key', strval(Tools::getValue('live_secret_key')));
        Configuration::updateValue('omise_title', strval(Tools::getValue('title')));
        Configuration::updateValue('omise_three_domain_secure_status', strval(Tools::getValue('three_domain_secure_status')));
        Configuration::updateValue('omise_internet_banking_status', strval(Tools::getValue('internet_banking_status')));
    }

    /**
     * @param string $title
     *
     * @return bool
     */
    public function saveTitle($title)
    {
        return Configuration::updateValue('omise_title', strval($title));
    }
}
