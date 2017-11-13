<?php
use \Mockery as m;

class OmisePaymentModuleFrontControllerTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    private $omise_payment_module_front_controller;
    private $payment_order;

    public function setup()
    {
        $unit_test_helper = new UnitTestHelper();

        $unit_test_helper->getMockedOmiseBasePaymentModuleFrontController();

        m::mock('alias:\Order')
            ->shouldReceive('getIdByCartId')
            ->andReturn('id_order');

        $this->omise_payment_module_front_controller = new OmisePaymentModuleFrontController();
        $this->omise_payment_module_front_controller->charge = $unit_test_helper->getMockedCharge();
        $this->omise_payment_module_front_controller->context = $this->getMockedContext();
        $this->omise_payment_module_front_controller->module = $this->getMockedModule();
        $this->omise_payment_module_front_controller->payment_order = $unit_test_helper->getMockedPaymentOrder();
        $this->omise_payment_module_front_controller->setting = $unit_test_helper->getMockedSetting();
    }

    public function testPostProcess_createCharge_saveAnOrderWithTheOrderStatusIsProcessing()
    {
        $this->omise_payment_module_front_controller->payment_order
            ->expects($this->once())
            ->method('save')
            ->with(
                'orderStatusProcessingInProgress',
                'title'
            );

        $this->omise_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_chargeResultIsNotEmpty_saveOmiseChargeIdToPrestaShopOrder()
    {
        $this->omise_payment_module_front_controller->payment_order
            ->expects($this->once())
            ->method('updatePaymentTransactionId')
            ->with(
                'id_order',
                'omiseChargeId'
            );

        $this->omise_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_createChargeIsError_updateOrderStatusToBeCanceled()
    {
        $this->omise_payment_module_front_controller->error_message = 'errorMessage';

        $this->omise_payment_module_front_controller->payment_order
            ->expects($this->once())
            ->method('updateStateToBeCanceled');

        $this->omise_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_createChargeIsSuccess_updateOrderStatusToBeSuccess()
    {
        $this->omise_payment_module_front_controller->error_message = '';

        $this->omise_payment_module_front_controller->payment_order
            ->expects($this->once())
            ->method('updateStateToBeSuccess');

        $this->omise_payment_module_front_controller->postProcess();
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
}
