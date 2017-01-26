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
    protected $charge_response;

    public function create()
    {
        $charge_request = array(
            'amount' => $this->getAmount(),
            'card' => $this->getCardToken(),
            'capture' => 'true',
            'currency' => $this->getCurrencyCode(),
            'description' => $this->getChargeDescription(),
        );

        $setting = new Setting();
        if ($setting->isThreeDomainSecureEnabled()) {
            $charge_request['return_uri'] = $this->getReturnUri();
        }

        $this->charge_response = OmiseCharge::create($charge_request, '', $this->getSecretKey());

        return $this;
    }

    protected function getAmount()
    {
        $currency_code = $this->getCurrencyCode();
        $order_total = (float) Context::getContext()->cart->getOrderTotal(true, Cart::BOTH);

        return OmisePluginHelperCharge::amount($currency_code, $order_total);
    }

    public function getAuthorizeUrl()
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
        return Context::getContext()->currency->iso_code;
    }

    public function getErrorMessage()
    {
        return OmisePluginHelperCharge::getErrorMessage($this->charge_response);
    }

    protected function getReturnUri()
    {
        $cart = Context::getContext()->cart;
        $customer = new Customer($cart->id_customer);
        $id_order = Order::getOrderByCartId($cart->id);
        $link = Context::getContext()->link;
        $module = Module::getInstanceByName(Omise::MODULE_NAME);

        return $link->getModuleLink(Omise::MODULE_NAME, 'return', [], true) .
            '?id_cart=' . $cart->id .
            '&id_module=' . $module->id .
            '&id_order=' . $id_order .
            '&key=' . $customer->secure_key;
    }

    protected function getSecretKey()
    {
        $setting = new Setting();

        return $setting->getSecretKey();
    }

    public function isFailed()
    {
        return OmisePluginHelperCharge::isFailed($this->charge_response);
    }
}
