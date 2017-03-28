<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/libraries/omise-php/lib/Omise.php';
    require_once _PS_MODULE_DIR_ . 'omise/libraries/omise-plugin/Omise.php';
    require_once _PS_MODULE_DIR_ . 'omise/setting.php';
}

class OmiseChargeClass
{
    protected $context;
    protected $charge_response;
    protected $setting;

    public function __construct()
    {
        $this->context = Context::getContext();
        $this->setSetting(new Setting());
    }

    /**
     * @param string $card_token The Omise card token.
     *
     * @return $this
     */
    public function create($card_token)
    {
        $charge_request = array(
            'amount' => $this->getAmount(),
            'card' => $card_token,
            'capture' => 'true',
            'currency' => $this->getCurrencyCode(),
            'description' => $this->getChargeDescription(),
        );

        if ($this->setting->isThreeDomainSecureEnabled()) {
            $charge_request['return_uri'] = $this->getReturnUri();
        }

        $this->charge_response = OmiseCharge::create($charge_request, '', $this->getSecretKey());

        return $this;
    }

    /**
     * @param string $offsite The parameter used to specify a bank to create Omise internet banking charge.
     *
     * @return $this
     */
    public function createInternetBanking($offsite)
    {
        $charge_request = array(
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrencyCode(),
            'description' => $this->getChargeDescription(),
            'offsite' => $offsite,
            'return_uri' => $this->getReturnUri(),
        );

        $this->charge_response = OmiseCharge::create($charge_request, '', $this->getSecretKey());

        return $this;
    }

    protected function getAmount()
    {
        $currency_code = $this->getCurrencyCode();
        $order_total = (float) $this->context->cart->getOrderTotal(true, Cart::BOTH);

        return OmisePluginHelperCharge::amount($currency_code, $order_total);
    }

    /**
     * Return the URL, authorize_uri, from the Omise charge response.
     * This URL used to redirect in the process of create Omise charge.
     *
     * @return string
     */
    public function getAuthorizeUri()
    {
        return $this->charge_response['authorize_uri'];
    }

    protected function getChargeDescription()
    {
        return 'Charge a card using a token from PrestaShop (' . _PS_VERSION_ . ')';
    }

    /**
     * @return string
     */
    protected function getCurrencyCode()
    {
        return $this->context->currency->iso_code;
    }

    /**
     * Return the formatted error message from Omise API.
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return OmisePluginHelperCharge::getErrorMessage($this->charge_response);
    }

    /**
     * Return Omise charge ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->charge_response['id'];
    }

    /**
     * Generate a PrestaShop site URL that is used to receive the redirect back from Omise API.
     *
     * @return string
     */
    protected function getReturnUri()
    {
        $id_order = Order::getOrderByCartId($this->context->cart->id);
        $module = Module::getInstanceByName(Omise::MODULE_NAME);

        return $this->context->link->getModuleLink(Omise::MODULE_NAME, 'return', [], true) .
            '?id_cart=' . $this->context->cart->id .
            '&id_module=' . $module->id .
            '&id_order=' . $id_order .
            '&key=' . $this->context->customer->secure_key;
    }

    /**
     * @return string
     */
    protected function getSecretKey()
    {
        return $this->setting->getSecretKey();
    }

    public function isFailed()
    {
        return OmisePluginHelperCharge::isFailed($this->charge_response);
    }

    /**
     * @param $id string The Omise charge ID.
     *
     * @return $this
     */
    public function retrieve($id)
    {
        $this->charge_response = OmiseCharge::retrieve($id, '', $this->getSecretKey());

        return $this;
    }

    /**
     * @param \Setting $setting The instance of class, Setting.
     */
    public function setSetting($setting)
    {
        $this->setting = $setting;
    }
}
