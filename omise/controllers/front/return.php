<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/payment_order.php';
}

class OmiseReturnModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;

    public function postProcess()
    {
        $id_cart = Tools::getValue('id_cart');
        $id_module = Tools::getValue('id_module');
        $id_order = Tools::getValue('id_order');
        $key = Tools::getValue('key');

        $payment_order = new PaymentOrder();
        $payment_order->updateStateToBeSuccess($id_order);

        $this->setRedirectAfter('index.php?controller=order-confirmation' .
            '&id_cart=' . $id_cart .
            '&id_module=' . $id_module .
            '&id_order=' . $id_order .
            '&key=' . $key
        );
    }
}
