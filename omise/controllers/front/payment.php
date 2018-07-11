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

        $this->payment_order->save(
            $this->payment_order->getOrderStateProcessingInProgress(),
            $this->setting->getTitle()
        );

        parent::postProcess();

        $ORDER_ID_METHOD = IS_VERSION_17 ? "getIdByCartId" : "getOrderByCartId";
        $id_order = Order::{$ORDER_ID_METHOD}($this->context->cart->id);

        if (! empty($this->charge)) {
            $this->payment_order->updatePaymentTransactionId($id_order, $this->charge->getId());
        }

        if (! empty($this->error_message)) {
            $this->payment_order->updateStateToBeCanceled(new Order($id_order));
            return;
        }

        $this->payment_order->updateStateToBeSuccess(new Order($id_order));

        $this->setRedirectAfter('index.php?controller=order-confirmation' .
            '&id_cart=' . $this->context->cart->id .
            '&id_module=' . $this->module->id .
            '&id_order=' . $this->module->currentOrder .
            '&key=' . $this->context->customer->secure_key);
    }
}
