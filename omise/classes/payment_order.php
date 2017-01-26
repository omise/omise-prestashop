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
     * The array of extra variables that will be used to attach to the order email.
     *
     * @return array
     */
    protected function getExtraVariables()
    {
        return array();
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
     * @return mixed
     */
    protected function getOrderStateAcceptedPayment()
    {
        return Configuration::get('PS_OS_PAYMENT');
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

    public function save()
    {
        $this->module->validateOrder(
            $this->getCartId(),
            $this->getOrderStateAcceptedPayment(),
            $this->getCartOrderTotal(),
            $this->getModuleDisplayName(),
            $this->getOptionalMessage(),
            $this->getExtraVariables(),
            $this->getCurrencyId(),
            $this->isNotNeededRoundingCardOrderTotal(),
            $this->getCustomerSecureKey()
        );
    }
}
