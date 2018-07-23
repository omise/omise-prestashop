<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class Setting
{
    
    const PREFIX = 'omise_';

    protected
        $submit_action = 'omise_save_setting',
        $all_settings = array(
            'module_status',
            'sandbox_status',
            'test_public_key',
            'test_secret_key',
            'live_public_key',
            'live_secret_key',
            'title',
            'three_domain_secure_status',
            'internet_banking_status',
            'alipay_status'
        )
    ;

    /**
     * Get an Omise setting value from config
     */
    protected function getConfig($settingName)
    {
        return Configuration::get(Setting::PREFIX.$settingName);
    }

    /**
     * Delete all setting values.
     */
    public function delete()
    {
        foreach($this->all_settings as $setting) Configuration::deleteByName(Setting::PREFIX.$setting);
    }

    /**
     * @return string
     */
    public function getLivePublicKey()
    {
        return $this->getConfig('live_public_key');
    }

    /**
     * @return string
     */
    public function getLiveSecretKey()
    {
        return $this->getConfig('live_secret_key');
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
        return $this->{$this->isSandboxEnabled() ? 'getTestPublicKey' : 'getLivePublicKey'}();
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
        return $this->{$this->isSandboxEnabled() ? 'getTestSecretKey' : 'getLiveSecretKey'}();
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
        return $this->getConfig('test_public_key');
    }

    /**
     * @return string
     */
    public function getTestSecretKey()
    {
        return $this->getConfig('test_secret_key');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfig('title');
    }

    /**
     * @return bool
     */
    public function isInternetBankingEnabled()
    {
        return !!$this->getConfig('internet_banking_status');
    }

    /**
     * @return bool
     */
    public function isAlipayEnabled()
    {
        return !!$this->getConfig('alipay_status');
    }

    /**
     * @return bool
     */
    public function isModuleEnabled()
    {
        return !!$this->getConfig('module_status');
    }

    /**
     * @return bool
     */
    public function isSandboxEnabled()
    {
        return !!$this->getConfig('sandbox_status');
    }

    /**
     * @return bool
     */
    public function isThreeDomainSecureEnabled()
    {
        return !!$this->getConfig('three_domain_secure_status');
    }

    /**
     * @return bool
     */
    public function isSubmit()
    {
        return !!Tools::isSubmit($this->getSubmitAction());
    }

    public function save()
    {
        foreach($this->all_settings as $setting) Configuration::updateValue(Setting::PREFIX.$setting, strval(Tools::getValue($setting)));
    }

    /**
     * @param string $title
     *
     * @return bool
     */
    public function saveTitle($title)
    {
        return Configuration::updateValue(Setting::PREFIX.'title', strval($title));
    }
}
