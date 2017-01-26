<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/libraries/omise-php/lib/Omise.php';
    require_once _PS_MODULE_DIR_ . 'omise/libraries/omise-plugin/Omise.php';
    require_once _PS_MODULE_DIR_ . 'omise/setting.php';
}

class Charge
{
    protected $context;
    protected $charge_response;
    protected $setting;

    public function __construct()
    {
        $this->context = Context::getContext();
        $this->setting = new Setting();
    }

    public function create()
    {
        $charge_request = array(
            'amount' => $this->getAmount(),
            'card' => $this->getCardToken(),
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

    protected function getAmount()
    {
        $currency_code = $this->getCurrencyCode();
        $order_total = (float) $this->context->cart->getOrderTotal(true, Cart::BOTH);

        return OmisePluginHelperCharge::amount($currency_code, $order_total);
    }

    public function getAuthorizeUri()
    {
        return $this->charge_response['authorize_uri'];
    }

    protected function getCardToken()
    {
        return Tools::getValue('omise_card_token');
    }

    protected function getChargeDescription()
    {
        return 'Charge a card using a token from PrestaShop (' . _PS_VERSION_ . ')';
    }

    protected function getCurrencyCode()
    {
        return $this->context->currency->iso_code;
    }

    public function getErrorMessage()
    {
        return OmisePluginHelperCharge::getErrorMessage($this->charge_response);
    }

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

    protected function getSecretKey()
    {
        return $this->setting->getSecretKey();
    }

    public function isFailed()
    {
        return OmisePluginHelperCharge::isFailed($this->charge_response);
    }
}
