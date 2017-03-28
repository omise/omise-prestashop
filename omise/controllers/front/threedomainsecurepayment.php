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

        if (! empty($this->error_message)) {
            return;
        }

        $this->addOmiseTransaction($this->charge->getId(), Order::getOrderByCartId($this->context->cart->id));

        $this->setRedirectAfter($this->charge->getAuthorizeUri());
    }
}
