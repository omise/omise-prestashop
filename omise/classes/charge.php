<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

require_once _PS_MODULE_DIR_ . '/omise/libraries/omise-php/lib/Omise.php';
require_once _PS_MODULE_DIR_ . '/omise/libraries/omise-plugin/Omise.php';
require_once _PS_MODULE_DIR_ . '/omise/setting.php';

class Charge extends ModuleFrontController
{
    public function getAmount()
    {
        $currency_code = $this->getCurrencyCode();
        $order_total = (float) $this->context->cart->getOrderTotal(true, Cart::BOTH);

        return OmisePluginHelperCharge::amount($currency_code, $order_total);
    }

    public function getCardToken()
    {
        return Tools::getValue('omise_card_token');
    }

    public function getCapture()
    {
        return 'true';
    }

    public function getChargeDescription()
    {
        return 'Charge a card using a token from PrestaShop (' . _PS_VERSION_ . ')';
    }

    public function getCurrencyCode()
    {
        $currency_id = (int) $this->context->cart->id_currency;
        $currency_instance = Currency::getCurrencyInstance($currency_id);

        return $currency_instance->iso_code;
    }

    public function getSecretKey()
    {
        $setting = new Setting();

        return $setting->getSecretKey();
    }
}
