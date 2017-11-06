<?php
use \Mockery as m;

class OmiseInternetBankingPaymentModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $charge;
    private $omise_charge;
    private $omise_internet_banking_payment_module_front_controller;
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

        m::mock('alias:\Order')
            ->shouldReceive('getIdByCartId')
            ->andReturn('id_order');

        m::mock('alias:\Tools')
            ->shouldReceive('getValue')
            ->with('offsite')
            ->andReturn('mocked_offsite');

        $this->charge = $this->getMockedCharge();
        $this->omise_charge = $this->getMockedOmiseCharge();
        $this->payment_order = $this->getMockedPaymentOrder();

        $this->omise_internet_banking_payment_module_front_controller = new OmiseInternetBankingPaymentModuleFrontController();
        $this->omise_internet_banking_payment_module_front_controller->charge = $this->charge;
        $this->omise_internet_banking_payment_module_front_controller->context = $this->getMockedContext();
        $this->omise_internet_banking_payment_module_front_controller->omise_charge = $this->omise_charge;
        $this->omise_internet_banking_payment_module_front_controller->payment_order = $this->payment_order;
    }

    public function testPostProcess_createInternetBankingCharge_getValueFromClientSideToCreateInternetBankingCharge()
    {
        $this->omise_charge
            ->expects($this->once())
            ->method('createInternetBanking')
            ->with('mocked_offsite');

        $this->omise_internet_banking_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_createInternetBankingCharge_saveAnOrderWithProcessingInProgressStatus()
    {
        $this->payment_order
            ->expects($this->once())
            ->method('saveAsProcessing');

        $this->omise_internet_banking_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_exceptionOccurredDuringCreateInternetBankingCharge_errorMessageHasBeenDefinedFromExceptionMessage()
    {
        $this->omise_charge
            ->method('createInternetBanking')
            ->will($this->throwException(new Exception('exception_message')));

        $this->omise_internet_banking_payment_module_front_controller->postProcess();

        $this->assertEquals('exception_message', $this->omise_internet_banking_payment_module_front_controller->error_message);
    }

    public function testPostProcess_internetBankingChargeIsError_redirectUrlMustNotBeSet()
    {
        $this->charge->method('isFailed')->willReturn(false);
        $this->omise_internet_banking_payment_module_front_controller->error_message = 'error_message';

        $this->omise_internet_banking_payment_module_front_controller
            ->expects($this->never())
            ->method('setRedirectAfter');

        $this->omise_internet_banking_payment_module_front_controller->postProcess();
    }

    public function testPostProcess_internetBankingChargeIsFailed_errorMessageHasBeenDefinedFromChargeErrorMessage()
    {
        $this->charge->method('isFailed')->willReturn(true);
        $this->charge->method('getErrorMessage')->willReturn('charge_error_message');

        $this->omise_internet_banking_payment_module_front_controller->postProcess();

        $this->assertEquals('charge_error_message', $this->omise_internet_banking_payment_module_front_controller->error_message);
    }

    public function testPostProcess_internetBankingChargeIsSuccess_setOmiseAuthorizeUriToBeTheRedirectUrl()
    {
        $this->charge->method('isFailed')->willReturn(false);
        $this->charge->method('getAuthorizeUri')->willReturn('omise_authorize_uri');

        $this->omise_internet_banking_payment_module_front_controller
            ->expects($this->once())
            ->method('setRedirectAfter')
            ->with('omise_authorize_uri');

        $this->omise_internet_banking_payment_module_front_controller->postProcess();
    }

    private function getMockedCharge()
    {
        $charge = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'getAuthorizeUri',
                    'getErrorMessage',
                    'getId',
                    'isFailed',
                )
            )
            ->getMock();

        return $charge;
    }

    private function getMockedContext()
    {
        $cart = $this->getMockBuilder(get_class(new stdClass()))->getMock();
        $cart->id = 1;

        $context = $this->getMockBuilder(get_class(new stdClass()))->getMock();
        $context->cart = $cart;

        return $context;
    }

    private function getMockedOmiseCharge()
    {
        $omise_charge = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'createInternetBanking',
                )
            )
            ->getMock();

        $omise_charge->method('createInternetBanking')->willReturn($this->charge);

        return $omise_charge;
    }

    private function getMockedPaymentOrder()
    {
        $payment_order = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'saveAsProcessing',
                    'updatePaymentTransactionId',
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
