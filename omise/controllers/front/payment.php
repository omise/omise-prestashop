<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/charge.php';
    require_once _PS_MODULE_DIR_ . 'omise/classes/payment_order.php';
}

class OmisePaymentModuleFrontController extends ModuleFrontController
{
    protected $charge;
    public $display_column_left = false;
    protected $error_message;

    public function __construct()
    {
        parent::__construct();

        $this->omise_charge = new Charge();
        $this->payment_order = new PaymentOrder();
        $this->setting = new Setting();
    }

    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign('error_message', $this->error_message);
        $this->setTemplate('payment-error.tpl');
    }

    public function postProcess()
    {
        try {
            $this->charge = $this->omise_charge->create();
        } catch (Exception $e) {
            $this->error_message = $e->getMessage();
            return;
        }

        if ($this->charge->isFailed()) {
            $this->error_message = $this->charge->getErrorMessage();
            return;
        }

        $this->payment_order->save();

        $this->setRedirectAfter('index.php?controller=order-confirmation' .
            '&id_cart=' . $this->context->cart->id .
            '&id_module=' . $this->module->id .
            '&id_order=' . $this->module->currentOrder .
            '&key=' . $this->context->customer->secure_key
        );
    }

    protected function redirect()
    {
        Tools::redirect($this->redirect_after);
    }
}
