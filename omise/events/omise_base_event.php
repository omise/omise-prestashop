<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/classes/omise_logger.php';
    require_once _PS_MODULE_DIR_ . 'omise/classes/payment_order.php';
}

abstract class OmiseBaseEvent
{
    protected $omise_logger;
    protected $omise_transaction_model;
    protected $payment_order;

    public function __construct()
    {
        $this->omise_logger = new OmiseLogger();
        $this->omise_transaction_model = new OmiseTransactionModel();
        $this->payment_order = new PaymentOrder();
    }

    public abstract function handle($event);
}
