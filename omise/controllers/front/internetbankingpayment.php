<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_transaction_model.php';
    require_once _PS_MODULE_DIR_ . 'omise/controllers/front/base.php';
}

class OmiseInternetBankingPaymentModuleFrontController extends OmiseBasePaymentModuleFrontController
{
    public function postProcess()
    {
        $this->validateCart();

        $this->payment_order->saveAsProcessing();

        try {
            $this->charge = $this->omise_charge->createInternetBanking(Tools::getValue('offsite'));
        } catch (Exception $e) {
            $this->error_message = $e->getMessage();
            return;
        }

        $id_order = Order::getOrderByCartId($this->context->cart->id);

        $this->payment_order->updatePaymentTransactionId($id_order, $this->charge->getId());

        if ($this->charge->isFailed()) {
            $this->error_message = $this->charge->getErrorMessage();
            return;
        }

        if (! empty($this->error_message)) {
            return;
        }

        $this->addOmiseTransaction($this->charge->getId(), $id_order);

        $this->setRedirectAfter($this->charge->getAuthorizeUri());
    }
}
