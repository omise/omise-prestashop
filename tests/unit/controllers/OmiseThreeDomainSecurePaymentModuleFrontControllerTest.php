<?php
use \Mockery as m;

class OmiseThreeDomainSecurePaymentModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $omise_base_payment_module_front_controller;
    private $omise_three_domain_secure_payment_module_front_controller;
    private $omise_transaction_model;

    public function setup()
    {
        $this->omise_base_payment_module_front_controller = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('OmiseBasePaymentModuleFrontController')
            ->setMethods(
                array(
                    '__construct',
                    'postProcess',
                    'setRedirectAfter',
                )
            )
            ->getMock();

        $this->omise_transaction_model = $this->getMockedOmiseTransactionModel();

        m::mock('alias:\Order')
            ->shouldReceive('getOrderByCartId')
            ->andReturn('order');

        $this->omise_three_domain_secure_payment_module_front_controller = new OmiseThreeDomainSecurePaymentModuleFrontController();
        $this->omise_three_domain_secure_payment_module_front_controller->charge = $this->getMockedCharge();
        $this->omise_three_domain_secure_payment_module_front_controller->context = $this->getMockedContext();
        $this->omise_three_domain_secure_payment_module_front_controller->payment_order = $this->getMockedPaymentOrder();
        $this->omise_three_domain_secure_payment_module_front_controller->setOmiseTransactionModel($this->omise_transaction_model);
    }

    public function testPostProcess_createThreeDomainSecureCharge_saveAnOrderWithProcessingInProgressStatus()
    {
        $this->omise_three_domain_secure_payment_module_front_controller->payment_order
            ->expects($this->once())
            ->method('saveAsProcessing');

        $this->omise_three_domain_secure_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_createThreeDomainSecureChargeAndNoErrorOccurred_addOneRecordToOmiseTransactionTable()
    {
        $this->omise_three_domain_secure_payment_module_front_controller->error_message = '';

        $this->omise_transaction_model
            ->expects($this->once())
            ->method('add');

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

    public function testPostProcess_errorOccurredAfterProcess_omiseTransactionMustNotBeAdded()
    {
        $this->omise_three_domain_secure_payment_module_front_controller->error_message = 'errorMessage';

        $this->omise_transaction_model
            ->expects($this->never())
            ->method('add');

        $this->omise_three_domain_secure_payment_module_front_controller->postProcess();
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
        $context = $this->getMockBuilder(get_class(new stdClass()))->getMock();
        $cart = $this->getMockBuilder(get_class(new stdClass()))->getMock();

        $cart->id = 'cartId';
        $context->cart = $cart;

        return $context;
    }

    private function getMockedOmiseTransactionModel()
    {
        $omise_transaction_model = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('OmiseTransactionModel')
            ->setMethods(
                array(
                    'add',
                    'createTable',
                )
            )
            ->getMock();

        return $omise_transaction_model;
    }

    private function getMockedPaymentOrder()
    {
        $payment_order = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'saveAsProcessing',
                )
            )
            ->getMock();

        return $payment_order;
    }

    public function tearDown()
    {
        m::close();
    }
}
