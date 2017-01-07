<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . '/omise/classes/charge.php';
}

class OmisePaymentModuleFrontController extends ModuleFrontController
{
    public $context;
    public $display_column_left = false;

    public function initContent()
    {
        parent::initContent();

        $omiseCharge = new Charge();

        try {
            $omiseCharge->create();
        } catch (Exception $e) {
            $this->context->smarty->assign('error_message', $e->getMessage());
        }

        $this->setTemplate('payment_result.tpl');
    }
}
