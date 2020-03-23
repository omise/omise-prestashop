<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class Setting
{
    
    const
        PREFIX = 'omise_',
        SUBMIT_ACTION = 'omise_save_setting'
    ;

    public
        $all_settings = array(
            'module_status',
            'sandbox_status',
            'test_public_key',
            'test_secret_key',
            'live_public_key',
            'live_secret_key',
            'title'
        )
    ;

    protected
        $callToCFG = array(
            // retrieve normal xxx_yyy_zzz config values by calling getXxxYyyZzz
            array( 'match'=>'%^get[A-Z].*$%', 'find'=>array('%([A-Z])([a-z])%', '%get_%'), 'repl'=>array('_\1\2', '') ),
            // retrieve status xxx_yyy_zzz config values by calling isXxxYyyZzzEnabled
            array( 'match'=>'%^is[A-Z].*Enabled$%', 'find'=>array('%([A-Z])([a-z])%', '%^is_(.*)%', '%_Enabled$%'), 'repl'=>array('_\1\2', '\1', '_status')),
            // temp to return setting values just using their internal config name
            array( 'match'=>'%^.*$%', 'find'=>array(), 'repl'=>array() )
        )
    ;

    public function __construct()
    {
        foreach(OmisePaymentMethods::$list as $method) {
            $class = OmisePaymentMethods::className($method);
            $this->addUsedSettings($class::allSettingKeys());
        }
    }

    /**
     * Get a setting based on the name of the method called if there is no explicitly declared method
     */
    public function __call($method, $args)
    {
        foreach ($this->callToCFG as $d) {
            if (preg_match($d['match'], $method))  {
                $settingName = strToLower(preg_replace($d['find'], $d['repl'], $method));
                if (in_array($settingName, $this->all_settings)) return $this->getConfig($settingName);
            }
        }
        trigger_error('Call to undefined method '.__CLASS__.'::'.$method.'()', E_USER_ERROR);
    }

    /**
     * Get an Omise setting value from config
     */
    protected function getConfig($settingName)
    {
        return Configuration::get(Setting::PREFIX.$settingName);
    }

    /**
     * Add setting names to list to be saved/used
     */
    public function addUsedSettings($settings)
    {
        $this->all_settings = array_unique(array_merge($this->all_settings, $settings));
    }

    /**
     * Delete all setting values.
     */
    public function delete()
    {
        foreach($this->all_settings as $setting) Configuration::deleteByName(Setting::PREFIX.$setting);
    }

    /**
     * Return appropriate test/live public key based on sandbox setting
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->{$this->isSandboxEnabled() ? 'getTestPublicKey' : 'getLivePublicKey'}();
    }

    /**
     * Return appropriate test/live secret key based on sandbox setting
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
        return Setting::SUBMIT_ACTION;
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
