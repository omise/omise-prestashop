<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/controllers/front/base.php';
}

class OmiseThreeDomainSecurePaymentModuleFrontController extends OmiseBasePaymentModuleFrontController
{
    public function postProcess()
    {
        $this->validateCart();

        $this->payment_order->saveAsProcessing();

        parent::postProcess();

        $id_order = Order::getOrderByCartId($this->context->cart->id);

        if (! empty($this->charge)) {
            $this->payment_order->updatePaymentTransactionId($id_order, $this->charge->getId());
        }

        if (! empty($this->error_message)) {
            $this->payment_order->updateStateToBeCanceled(new Order($id_order));
            return;
        }

        $this->addOmiseTransaction($this->charge->getId(), $id_order);

        $this->setRedirectAfter($this->charge->getAuthorizeUri());
    }
}
