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
    protected $payment_order;

    public function __construct()
    {
        parent::__construct();

        $this->setPaymentOrder(new PaymentOrder());
    }

    public function initContent()
    {
        parent::initContent();

        $omiseCharge = new Charge();

        try {
            $omiseCharge->create();
            $this->payment_order->save();
        } catch (Exception $e) {
            $this->context->smarty->assign('error_message', $e->getMessage());
        }

        $this->payment_order->redirectToResultPage();
    }

    public function setPaymentOrder($payment_order)
    {
        $this->payment_order = $payment_order;
    }
}
