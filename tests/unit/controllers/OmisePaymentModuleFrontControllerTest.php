<?php
use \Mockery as m;

class OmisePaymentModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $omise_charge;
    private $omise_payment_module_front_controller;
    private $payment_order;
    private $smarty;

    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('ModuleFrontController')
            ->setMethods(
                array(
                    '__construct',
                    'initContent',
                    'setTemplate',
                )
            )
            ->getMock();

        $this->smarty = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('Smarty')
            ->setMethods(
                array(
                    'assign',
                )
            )
            ->getMock();

        $context = $this->getMockBuilder(get_class(new stdClass()));
        $context->smarty = $this->smarty;

        $this->omise_charge = m::mock('overload:\Charge');
        $this->payment_order = m::mock('overload:\PaymentOrder');

        $this->omise_payment_module_front_controller = new OmisePaymentModuleFrontController();
        $this->omise_payment_module_front_controller->context = $context;
    }

    public function testDisplayColumnLeft_displayTheResultPage_theLeftColumnWillNotAppear()
    {
        $this->assertFalse($this->omise_payment_module_front_controller->display_column_left);
    }

    public function testInitContent_createOmiseCharge_createOnlyOneOmiseCharge()
    {
        $this->payment_order
            ->shouldReceive('redirectToResultPage')
            ->shouldReceive('save');

        $this->omise_charge->shouldReceive('create')
            ->once()
            ->andReturn($this->createSuccessOmiseChargeResult());

        $this->omise_payment_module_front_controller->initContent();
    }

    public function testInitContent_successfullyCreateOmiseCharge_redirectTheSystemToTheResultPage()
    {
        $this->omise_charge
            ->shouldReceive('create')
            ->andReturn($this->createSuccessOmiseChargeResult());

        $this->payment_order
            ->shouldReceive('redirectToResultPage')
            ->once()
            ->shouldReceive('save');

        $this->omise_payment_module_front_controller->initContent();
    }

    public function testInitContent_successfullyCreateOmiseCharge_saveOnlyOneOrder()
    {
        $this->omise_charge
            ->shouldReceive('create')
            ->andReturn($this->createSuccessOmiseChargeResult());

        $this->payment_order
            ->shouldReceive('redirectToResultPage')
            ->shouldReceive('save')
            ->once();

        $this->omise_payment_module_front_controller->initContent();
    }

    public function testInitContent_createOmiseChargeIsFail_displayErrorMessage()
    {
        $this->omise_charge
            ->shouldReceive('create')
            ->andReturn($this->createFailOmiseChargeResult());

        $this->smarty->expects($this->once())
            ->method('assign')
            ->with('error_message', 'errorMessage');

        $this->omise_payment_module_front_controller->initContent();
    }

    public function testInitContent_exceptionOccurWhenCreateOmiseCharge_displayExceptionMessage()
    {
        $this->omise_charge
            ->shouldReceive('create')
            ->andThrow(new Exception('exceptionMessage'));

        $this->smarty->expects($this->once())
            ->method('assign')
            ->with('error_message', 'exceptionMessage');

        $this->omise_payment_module_front_controller->initContent();
    }

    private function createFailOmiseChargeResult()
    {
        $result = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'getErrorMessage',
                    'isFailed',
                )
            )
            ->getMock();

        $result->method('getErrorMessage')->willReturn('errorMessage');
        $result->method('isFailed')->willReturn(true);

        return $result;
    }

    private function createSuccessOmiseChargeResult()
    {
        $result = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'isFailed',
                )
            )
            ->getMock();

        $result->method('isFailed')->willReturn(false);

        return $result;
    }

    public function tearDown()
    {
        m::close();
    }
}
