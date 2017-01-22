<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . '/omise/classes/charge.php';
    require_once _PS_MODULE_DIR_ . '/omise/classes/payment_order.php';
}

class OmisePaymentModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;

    public function initContent()
    {
        parent::initContent();

        $omiseCharge = new Charge();

        try {
            $charge = $omiseCharge->create();
        } catch (Exception $e) {
            $this->context->smarty->assign('error_message', $e->getMessage());
            $this->setTemplate('payment-error.tpl');
            return;
        }

        if ($charge->isFailed()) {
            $this->context->smarty->assign('error_message', $charge->getErrorMessage());
            $this->setTemplate('payment-error.tpl');
            return;
        }

        $payment_order = new PaymentOrder();
        $payment_order->save();
        $payment_order->redirectToResultPage();
    }
}
