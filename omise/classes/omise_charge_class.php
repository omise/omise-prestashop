<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/vendor/omise/omise-php/lib/Omise.php';
    require_once _PS_MODULE_DIR_ . 'omise/libraries/omise-plugin/Omise.php';
    require_once _PS_MODULE_DIR_ . 'omise/setting.php';
}

class OmiseChargeClass
{
    const STATUS_FAILED = 'failed';
    const STATUS_SUCCESSFUL = 'successful';

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
    public function create($card_token, $returnUri = '')
    {
        $charge_request = array(
            'amount' => $this->getAmount(),
            'card' => $card_token,
            'capture' => 'true',
            'currency' => $this->getCurrencyCode(),
            'description' => $this->getChargeDescription(),
            'metadata' => $this->getMetadata(),
        );

        if ($this->setting->isThreeDomainSecureEnabled()) {
            $charge_request['return_uri'] = $returnUri;
        }

        $this->charge_response = OmiseCharge::create($charge_request, '', $this->getSecretKey());

        return $this;
    }

    /**
     * @param string|array $offsite The parameter used to specify simple offsite type, or array of type and additional params
     *
     * @return $this
     */
    public function createOffsite($offsiteType, $returnUri)
    {
        $charge_request = array(
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrencyCode(),
            'description' => $this->getChargeDescription(),
            'metadata' => $this->getMetadata(),
            'source' => is_array($offsiteType) ? $offsiteType : array('type' => $offsiteType),
            'return_uri' => $returnUri
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
     * @return array
     */
    protected function getMetadata()
    {
        return array('order_id' => Order::{PRESTASHOP_GET_ORDER_ID_METHOD}($this->context->cart->id));
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

    public function isPaid()
    {
        return OmisePluginHelperCharge::isPaid($this->charge_response);
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
