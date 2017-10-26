<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_charge_class.php';
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_transaction_model.php';
    require_once _PS_MODULE_DIR_ . 'omise/classes/payment_order.php';
    require_once _PS_MODULE_DIR_ . 'omise/setting.php';
}

/**
 * Specify the software version when sending the request to Omise API. The specified versions will be displayed at
 * Omise dashboard.
 *
 * This constant is used to append to request header, User-Agent. The request is send by library, Omise PHP.
 *
 * @see OmiseApiResource::genOptions() The function in library, Omise PHP, that uses this constant.
 */
if (! defined('OMISE_USER_AGENT_SUFFIX')) {
    define('OMISE_USER_AGENT_SUFFIX', sprintf('OmisePrestaShop/%s PrestaShop/%s', Omise::MODULE_VERSION, _PS_VERSION_));
}

abstract class OmiseBasePaymentModuleFrontController extends ModuleFrontController
{
    protected $charge;
    public $display_column_left = false;
    protected $error_message;
    protected $omise_charge;
    protected $omise_transaction_model;
    protected $order_reference;
    protected $payment_order;
    protected $setting;

    public function __construct()
    {
        parent::__construct();

        $this->omise_charge = new OmiseChargeClass();
        $this->omise_transaction_model = new OmiseTransactionModel();
        $this->payment_order = new PaymentOrder();
        $this->setting = new Setting();
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

    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign('error_message', $this->error_message);
        $this->context->smarty->assign('order_reference', $this->order_reference);

        $this->setTemplate('module:omise/views/templates/front/payment-error.tpl');
    }

    /**
     * Override parent method.
     *
     * @see FrontControllerCore::postProcess()
     */
    public function postProcess()
    {
        try {
            $this->charge = $this->omise_charge->create(Tools::getValue('omise_card_token'));
        } catch (Exception $e) {
            $this->error_message = $e->getMessage();
            return;
        }

        if ($this->charge->isFailed()) {
            $this->error_message = $this->charge->getErrorMessage();
            return;
        }
    }

    /**
     * Override parent method.
     *
     * @see FrontControllerCore::redirect()
     */
    public function redirect()
    {
        Tools::redirect($this->redirect_after);
    }

    /**
     * The function used to check cart information to prevent directly access the payment URL or payment controller
     * without the cart information or the order of cart has been processed.
     */
    protected function validateCart()
    {
        if (Validate::isLoadedObject($this->context->cart) == false
            || $this->context->cart->OrderExists() == true
        ) {
            Tools::redirect('index.php?controller=order&step=1');
        }
    }

    /**
     * @param \OmiseTransactionModel $omise_transaction_model The instance of class, OmiseTransactionModel.
     */
    public function setOmiseTransactionModel($omise_transaction_model)
    {
        $this->omise_transaction_model = $omise_transaction_model;
    }
}
