<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (!defined('IS_VERSION_17')) define('IS_VERSION_17', _PS_VERSION_ >= '1.7');

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/omise.php';
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_charge_class.php';
    require_once _PS_MODULE_DIR_ . 'omise/classes/payment_order.php';
}

/**
 * Specify the version of Omise API that the module, Omise PrestaShop, is compatible.
 *
 * This constant is used to append to request header. The request is send by library, Omise PHP.
 *
 * @see OmiseApiResource::genOptions() The function in library, Omise PHP, that uses this constant.
 */
if (! defined('OMISE_API_VERSION')) {
    define('OMISE_API_VERSION', '2017-11-02');
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
    public 
        $charge,
        $display_column_left = false,
        $error_message,
        $order_reference
    ;
    protected
        $omise_transaction_model,
        $setting
    ;

    public function __construct()
    {
        parent::__construct();
        $this->omise_transaction_model = new OmiseTransactionModel();
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
    public function addOmiseTransaction($id_charge, $id_order)
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

        $this->setTemplate(IS_VERSION_17 ? 'module:omise/views/templates/front/1.7/payment-error.tpl' : '1.6/payment-error.tpl');
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
    public function validateCart()
    {
        if (Validate::isLoadedObject($this->context->cart) == false
            || $this->context->cart->OrderExists() == true
        ) {
            Tools::redirect('index.php?controller=order&step=1');
        }
    }

}
