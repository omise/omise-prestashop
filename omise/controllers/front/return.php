<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_transaction_model.php';
    require_once _PS_MODULE_DIR_ . 'omise/controllers/front/base.php';
    require_once _PS_MODULE_DIR_ . 'omise/omise.php';
}

class OmiseReturnModuleFrontController extends OmiseBasePaymentModuleFrontController
{
    protected $order;

    protected function isChargeValid($id_order)
    {
        $omise_transaction = new OmiseTransactionModel();

        $id_charge = $omise_transaction->getIdCharge($id_order);

        try {
            $this->charge = $this->omise_charge->retrieve($id_charge);
        } catch (Exception $e) {
            $this->error_message = $e->getMessage();
            return false;
        }

        if ($this->charge->isFailed()) {
            $this->payment_order->updateStateToBeCanceled($this->order);
            $this->error_message = $this->omise_charge->getErrorMessage();
            return false;
        }

        return true;
    }

    protected function isPaymentValid($id_order)
    {
        $this->order = new Order($id_order);

        if (Validate::isLoadedObject($this->order) == false) {
            $this->error_message = $this->l('Order not found.');
            return false;
        }

        $this->order_reference = $this->order->reference;

        if ($this->order->payment != Omise::MODULE_DISPLAY_NAME) {
            $this->error_message = $this->l('Payment method is invalid.');
            return false;
        }

        if ($this->order->current_state != $this->payment_order->getOrderStateProcessingInProgress()
            && $this->order->current_state != $this->payment_order->getOrderStateAcceptedPayment()) {
            $this->error_message = $this->l('Order status is invalid.');

            return false;
        }

        if ($this->isChargeValid($id_order) == false) {
            return false;
        }

        return true;
    }

    public function postProcess()
    {
        $id_cart = Tools::getValue('id_cart');
        $id_module = Tools::getValue('id_module');
        $id_order = Tools::getValue('id_order');
        $key = Tools::getValue('key');

        if ($this->isPaymentValid($id_order) == false) {
            return;
        }

        $this->payment_order->updateStateToBeSuccess($this->order);

        $this->setRedirectAfter('index.php?controller=order-confirmation' .
            '&id_cart=' . $id_cart .
            '&id_module=' . $id_module .
            '&id_order=' . $id_order .
            '&key=' . $key);
    }
}
