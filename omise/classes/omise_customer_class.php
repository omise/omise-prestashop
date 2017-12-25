<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/libraries/omise-php/lib/Omise.php';
    require_once _PS_MODULE_DIR_ . 'omise/setting.php';
}

class OmiseCustomerClass
{
    protected $customer_response;
    protected $setting;

    public function __construct()
    {
        $this->setSetting(new Setting());
    }

    /**
     * @param string $card_token The Omise card token.
     *
     * @return $this
     */
    public function create($card_token)
    {
        $customer_request = array(
            'card' => $card_token,
        );

        $this->customer_response = OmiseCustomer::create($customer_request, '', $this->setting->getSecretKey());

        return $this;
    }

    /**
     * Return Omise customer ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->customer_response['id'];
    }

    /**
     * @param \Setting $setting The instance of class, Setting.
     */
    public function setSetting($setting)
    {
        $this->setting = $setting;
    }
}
