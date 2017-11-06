<?php
class OmisePaymentModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $omise_payment_module_front_controller;
    private $payment_order;

    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('OmiseBasePaymentModuleFrontController')
            ->setMethods(
                array(
                    '__construct',
                    'addOmiseTransaction',
                    'l',
                    'postProcess',
                    'setRedirectAfter',
                    'validateCart',
                )
            )
            ->getMock();

        $this->omise_payment_module_front_controller = new OmisePaymentModuleFrontController();
        $this->omise_payment_module_front_controller->charge = $this->getMockedCharge();
        $this->omise_payment_module_front_controller->context = $this->getMockedContext();
        $this->omise_payment_module_front_controller->module = $this->getMockedModule();
        $this->omise_payment_module_front_controller->payment_order = $this->getMockedPaymentOrder();
    }

    public function testPostProcess_errorOccurred_noAnyOrderHasBeenSaved()
    {
        $this->omise_payment_module_front_controller->error_message = 'errorMessage';

        $this->payment_order->expects($this->never())
            ->method('save');

        $this->omise_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_noErrorOccurred_saveTheOrder()
    {
        $this->payment_order->expects($this->once())
            ->method('save')
            ->with(null, $this->omise_payment_module_front_controller->charge->getId());

        $this->omise_payment_module_front_controller->postProcess();
    }

    private function getMockedCharge()
    {
        $charge = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'getAuthorizeUri',
                    'getId',
                )
            )
            ->getMock();

        return $charge;
    }

    private function getMockedContext()
    {
        $cart = $this->getMockBuilder(get_class(new stdClass()))->getMock();
        $cart->id = 1;

        $customer = $this->getMockBuilder(get_class(new stdClass()))->getMock();
        $customer->secure_key = 'customerSecureKey';

        $context = $this->getMockBuilder(get_class(new stdClass()))->getMock();
        $context->cart = $cart;
        $context->customer = $customer;

        return $context;
    }

    private function getMockedModule()
    {
        $module = $this->getMockBuilder(get_class(new stdClass()))->getMock();
        $module->id = '2';
        $module->currentOrder = '3';

        return $module;
    }

    private function getMockedPaymentOrder()
    {
        $this->payment_order = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'save',
                )
            )
            ->getMock();

        return $this->payment_order;
    }
}
