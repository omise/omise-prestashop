<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

require_once _PS_MODULE_DIR_ . '/omise/libraries/omise-php/lib/Omise.php';
require_once _PS_MODULE_DIR_ . '/omise/setting.php';

class OmisePaymentModuleFrontController extends ModuleFrontController
{
    public $context;
    public $display_column_left = false;

    public function initContent()
    {
        parent::initContent();

        $data = array(
            'amount' => $this->getAmount(),
            'card' => $this->getCardToken(),
            'capture' => $this->getCapture(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
        );

        $secret_key = $this->getSecretKey();

        try {
            $charge = OmiseCharge::create($data, '', $secret_key);
            $payment_success = true;
        } catch (Exception $e) {
            $payment_success = false;
            $this->context->smarty->assign(
                array(
                    'error_message' => $e->getMessage(),
                )
            );
        }

        $this->context->smarty->assign(
            array(
                'payment_success' => $payment_success,
            )
        );

        $this->setTemplate('payment_result.tpl');
    }

    public function getAmount()
    {
        $total = (float) $this->context->cart->getOrderTotal(true, Cart::BOTH);

        return 100 * $total;
    }

    public function getCardToken()
    {
        return Tools::getValue('omise_card_token');
    }

    public function getCapture()
    {
        return 'true';
    }

    public function getCurrency()
    {
        $currency_id = (int) $this->context->cart->id_currency;
        $currency_instance = Currency::getCurrencyInstance($currency_id);

        return $currency_instance->iso_code;
    }

    public function getDescription()
    {
        return 'PrestaShop';
    }

    public function getSecretKey()
    {
        $setting = new Setting();

        return $setting->getSecretKey();
    }
}
