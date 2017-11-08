<?php
use \Mockery as m;

class OmiseThreeDomainSecurePaymentModuleFrontControllerTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    private $omise_three_domain_secure_payment_module_front_controller;

    public function setup()
    {
        $unit_test_helper = new UnitTestHelper();

        $unit_test_helper->getMockedOmiseBasePaymentModuleFrontController();

        m::mock('alias:\Order')
            ->shouldReceive('getIdByCartId')
            ->andReturn('order');

        $this->omise_three_domain_secure_payment_module_front_controller = new OmiseThreeDomainSecurePaymentModuleFrontController();
        $this->omise_three_domain_secure_payment_module_front_controller->charge = $unit_test_helper->getMockedCharge();
        $this->omise_three_domain_secure_payment_module_front_controller->context = $this->getMockedContext();
        $this->omise_three_domain_secure_payment_module_front_controller->payment_order = $unit_test_helper->getMockedPaymentOrder();
        $this->omise_three_domain_secure_payment_module_front_controller->setting = $unit_test_helper->getMockedSetting();
    }

    public function testPostProcess_createThreeDomainSecureCharge_saveAnOrderWithProcessingInProgressStatus()
    {
        $this->omise_three_domain_secure_payment_module_front_controller->payment_order
            ->expects($this->once())
            ->method('save')
            ->with('orderStatusProcessingInProgress', 'title');

        $this->omise_three_domain_secure_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_createThreeDomainSecureChargeAndNoErrorOccurred_updatePaymentTransactionId()
    {
        $this->omise_three_domain_secure_payment_module_front_controller->error_message = '';

        $this->omise_three_domain_secure_payment_module_front_controller->payment_order
            ->expects($this->once())
            ->method('updatePaymentTransactionId');

        $this->omise_three_domain_secure_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_createThreeDomainSecureChargeAndNoErrorOccurred_redirectToAuthorizeUri()
    {
        $this->omise_three_domain_secure_payment_module_front_controller->error_message = '';

        $this->omise_three_domain_secure_payment_module_front_controller->charge
            ->expects($this->once())
            ->method('getAuthorizeUri');

        $this->omise_three_domain_secure_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_errorOccurredAfterProcess_noRedirectToAuthorizeUri()
    {
        $this->omise_three_domain_secure_payment_module_front_controller->error_message = 'errorMessage';

        $this->omise_three_domain_secure_payment_module_front_controller->charge
            ->expects($this->never())
            ->method('getAuthorizeUri');

        $this->omise_three_domain_secure_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_errorOccurredAfterProcess_updateOrderStateToBeCanceled()
    {
        $this->omise_three_domain_secure_payment_module_front_controller->error_message = 'errorMessage';

        $this->omise_three_domain_secure_payment_module_front_controller->payment_order
            ->expects($this->once())
            ->method('updateStateToBeCanceled');

        $this->omise_three_domain_secure_payment_module_front_controller->postProcess();
    }

    private function getMockedContext()
    {
        $context = $this->getMockBuilder(get_class(new stdClass()))->getMock();
        $cart = $this->getMockBuilder(get_class(new stdClass()))->getMock();

        $cart->id = 'cartId';
        $context->cart = $cart;

        return $context;
    }
}
