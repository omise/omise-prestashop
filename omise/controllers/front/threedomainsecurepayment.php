<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_transaction_model.php';
    require_once _PS_MODULE_DIR_ . 'omise/controllers/front/base.php';
}

class OmiseThreeDomainSecurePaymentModuleFrontController extends OmiseBasePaymentModuleFrontController
{
    protected $omise_transaction_model;

    public function __construct()
    {
        parent::__construct();

        $this->setOmiseTransactionModel(new OmiseTransactionModel());
    }

    /**
     * Add a reference between PrestaShop order ID and Omise charge ID
     * to a database table.
     *
     * This reference will be used to check the status of Omise charge
     * at the step of redirect back from Omise API.
     * (@see OmiseReturnModuleFrontController::isChargeValid())
     *
     * @param string $id_charge The Omise charge ID.
     * @param int $id_order The PrestaShop order ID.
     *
     * @return bool
     */
    protected function addOmiseTransaction($id_charge, $id_order)
    {
        $this->omise_transaction_model->id_charge = $id_charge;
        $this->omise_transaction_model->id_order = $id_order;

        return $this->omise_transaction_model->add();
    }

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

    public function setOmiseTransactionModel($omise_transaction_model)
    {
        $this->omise_transaction_model = $omise_transaction_model;
    }
}
