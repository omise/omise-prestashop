<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class OmisePaymentModuleFrontController extends ModuleFrontController
{
    public $context;
    public $display_column_left = false;

    public function initContent()
    {
        parent::initContent();

        $charge_request = array(
            'amount' => $this->getAmount(),
            'card' => $this->getCardToken(),
            'capture' => $this->getCapture(),
            'currency' => $this->getCurrencyCode(),
            'description' => $this->getChargeDescription(),
        );

        $secret_key = $this->getSecretKey();

        try {
            $charge = OmiseCharge::create($charge_request, '', $secret_key);
        } catch (Exception $e) {
            $this->context->smarty->assign('error_message', $e->getMessage());
        }

        $this->setTemplate('payment_result.tpl');
    }
}
