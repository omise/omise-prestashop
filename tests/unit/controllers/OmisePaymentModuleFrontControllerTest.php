<?php
class OmisePaymentModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $omisePaymentModuleFrontController;
    private $payment_order;

    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('OmiseBasePaymentModuleFrontController')
            ->setMethods(
                array(
                    'postProcess',
                    'setRedirectAfter',
                )
            )
            ->getMock();

        $this->omisePaymentModuleFrontController = new OmisePaymentModuleFrontController();
        $this->omisePaymentModuleFrontController->payment_order = $this->getMockedPaymentOrder();
        $this->omisePaymentModuleFrontController->context = $this->getMockedContext();
        $this->omisePaymentModuleFrontController->module = $this->getMockedModule();
    }

    public function testPostProcess_errorOccurred_noAnyOrderHasBeenSaved()
    {
        $this->omisePaymentModuleFrontController->error_message = 'errorMessage';

        $this->payment_order->expects($this->never())
            ->method('save');

        $this->omisePaymentModuleFrontController->postProcess();
    }

    public function testPostProcess_noErrorOccurred_saveTheOrder()
    {
        $this->payment_order->expects($this->once())
            ->method('save');

        $this->omisePaymentModuleFrontController->postProcess();
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
