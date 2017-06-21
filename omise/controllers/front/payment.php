<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/controllers/front/base.php';
}

class OmisePaymentModuleFrontController extends OmiseBasePaymentModuleFrontController
{
    public function postProcess()
    {
        $this->validateCart();

        parent::postProcess();

        if (! empty($this->error_message)) {
            return;
        }

        $this->payment_order->save(null, $this->charge->getId());

        $this->setRedirectAfter('index.php?controller=order-confirmation' .
            '&id_cart=' . $this->context->cart->id .
            '&id_module=' . $this->module->id .
            '&id_order=' . $this->module->currentOrder .
            '&key=' . $this->context->customer->secure_key);
    }
}
