<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . '/omise/libraries/omise-php/lib/Omise.php';
    require_once _PS_MODULE_DIR_ . '/omise/libraries/omise-plugin/Omise.php';
    require_once _PS_MODULE_DIR_ . '/omise/setting.php';
}

class Charge extends ModuleFrontController
{
    public function create()
    {
        $charge_request = array(
            'amount' => $this->getAmount(),
            'card' => $this->getCardToken(),
            'capture' => 'true',
            'currency' => $this->getCurrencyCode(),
            'description' => $this->getChargeDescription(),
        );

        return OmiseCharge::create($charge_request, '', $this->getSecretKey());
    }

    protected function getAmount()
    {
        $currency_code = $this->getCurrencyCode();
        $order_total = (float) $this->context->cart->getOrderTotal(true, Cart::BOTH);

        return OmisePluginHelperCharge::amount($currency_code, $order_total);
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
        $currency_id = (int) $this->context->cart->id_currency;
        $currency_instance = Currency::getCurrencyInstance($currency_id);

        return $currency_instance->iso_code;
    }

    protected function getSecretKey()
    {
        $setting = new Setting();

        return $setting->getSecretKey();
    }
}
