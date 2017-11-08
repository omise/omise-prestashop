<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'omise/omise.php';
}

class PaymentOrder
{
    protected $context;
    protected $module;

    public function __construct()
    {
        $this->context = Context::getContext();
        $this->module = Module::getInstanceByName(Omise::MODULE_NAME);
    }

    protected function getCartId()
    {
        return (int) $this->context->cart->id;
    }

    protected function getCartOrderTotal()
    {
        return (float) $this->context->cart->getOrderTotal();
    }

    protected function getCustomerSecureKey()
    {
        $customer = new Customer($this->context->cart->id_customer);

        return $customer->secure_key;
    }

    protected function getCurrencyId()
    {
        return (int) $this->context->currency->id;
    }

    /**
     * Return the array of extra variables.
     *
     * The extra variables will be used to save to database and attach to the order email.
     *
     * @param string $id_charge The Omise charge ID.
     *
     * @return array
     */
    protected function getExtraVariables($id_charge)
    {
        $extra_variables = array();

        if (! empty($id_charge)) {
            $extra_variables['transaction_id'] = $id_charge;
        }

        return $extra_variables;
    }

    protected function getModuleDisplayName()
    {
        return Omise::MODULE_DISPLAY_NAME;
    }

    /**
     * The optional message that will be used to attach to the order.
     *
     * @return string
     */
    protected function getOptionalMessage()
    {
        return null;
    }

    /**
     * The successful order status.
     *
     * @return int
     */
    public function getOrderStateAcceptedPayment()
    {
        return Configuration::get('PS_OS_PAYMENT');
    }

    /**
     * The order status that indicate the order is canceled.
     *
     * @return int
     */
    public function getOrderStateCanceled()
    {
        return Configuration::get('PS_OS_CANCELED');
    }

    /**
     * The order status that indicate the order is in progress.
     *
     * @return int
     */
    public function getOrderStateProcessingInProgress()
    {
        return Configuration::get('PS_OS_PREPARATION');
    }

    /**
     * The flag that used to indicate that the PrestaShop need to
     * round the card order total amount.
     *
     * If the flag is false, the PrestaShop will perform rounding.
     * If the flag is true, the PrestaShop WILL NOT preform rounding.
     *
     * @return bool
     */
    protected function isNotNeededRoundingCardOrderTotal()
    {
        return false;
    }

    /**
     * Save an order to database by using PrestaShop core function.
     *
     * @param int $id_order_state
     * @param string $payment_method The name of payment method.
     * @param string $id_charge The Omise charge ID.
     */
    public function save($id_order_state, $payment_method, $id_charge = null)
    {
        $this->module->validateOrder(
            $this->getCartId(),
            $id_order_state,
            $this->getCartOrderTotal(),
            $payment_method,
            $this->getOptionalMessage(),
            $this->getExtraVariables($id_charge),
            $this->getCurrencyId(),
            $this->isNotNeededRoundingCardOrderTotal(),
            $this->getCustomerSecureKey()
        );
    }

    /**
     * Update an order payment transaction ID to database.
     *
     * Note:
     * - The PrestaShop order payment transaction ID has been mapped with Omise charge ID.
     * - PrestaShop has order and order payment separate from each other. So, to update an order payment transaction ID,
     * it need to retrieve the order payment from the order.
     *
     * @param int $id_order
     * @param string $transaction_id The order payment transaction ID. It is the reference ID between PrestaShop order
     * payment and Omise payment gateway.
     */
    public function updatePaymentTransactionId($id_order, $transaction_id)
    {
        $order = new Order($id_order);
        $order_payment_collection = $order->getOrderPaymentCollection();

        $order_payment = $order_payment_collection[0];

        $order_payment->transaction_id = $transaction_id;
        $order_payment->update();
    }

    /**
     * @param \Order $order The instance of class, Order.
     */
    public function updateStateToBeCanceled($order)
    {
        $order_state = $this->getOrderStateCanceled();

        if ($order->current_state == $order_state) {
            return;
        }

        $order->setCurrentState($order_state);
    }

    /**
     * @param \Order $order The instance of class, Order.
     */
    public function updateStateToBeSuccess($order)
    {
        $order_state = $this->getOrderStateAcceptedPayment();

        if ($order->current_state == $order_state) {
            return;
        }

        $order->setCurrentState($order_state);
    }
}
