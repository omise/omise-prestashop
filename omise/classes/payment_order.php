<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class PaymentOrder extends ModuleFrontController
{
    protected function getCartId()
    {
        $cart = $this->context->cart;
        return (int) $cart->id;
    }

    protected function getCartOrderTotal()
    {
        $cart = $this->context->cart;
        return (float) $cart->getOrderTotal();
    }

    protected function getCustomerSecureKey()
    {
        $cart = $this->context->cart;
        $customer = new Customer($cart->id_customer);

        return $customer->secure_key;
    }

    protected function getCurrencyId()
    {
        $currency = $this->context->currency;
        return (int) $currency->id;
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
        return $this->module->displayName;
    }

    protected function getModuleId()
    {
        return (int) $this->module->id;
    }

    protected function getModuleCurrentOrder()
    {
        return $this->module->currentOrder;
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

    public function redirectToResultPage()
    {
        Tools::redirect('index.php?controller=order-confirmation' .
            '&id_cart=' . $this->getCartId() .
            '&id_module=' . $this->getModuleId() .
            '&id_order=' . $this->getModuleCurrentOrder() .
            '&key=' . $this->getCustomerSecureKey()
        );
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
