<?php
use \Mockery as m;

class OmisePaymentModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $omiseCharge;
    private $omisePaymentModuleFrontController;
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

        $this->omiseCharge = m::mock('overload:\Charge');

        $this->payment_order = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'redirectToResultPage',
                    'save',
                )
            )
            ->getMock();

        $this->omisePaymentModuleFrontController = new OmisePaymentModuleFrontController();
        $this->omisePaymentModuleFrontController->context = $context;
        $this->omisePaymentModuleFrontController->setPaymentOrder($this->payment_order);
    }

    public function testDisplayColumnLeft_displayTheResultPage_theLeftColumnWillNotAppear()
    {
        $this->assertFalse($this->omisePaymentModuleFrontController->display_column_left);
    }

    public function testInitContent_createOmiseCharge_createOnlyOneOmiseCharge()
    {
        $this->omiseCharge->shouldReceive('create')
            ->once()
            ->andReturn($this->createSuccessOmiseChargeResult());

        $this->omisePaymentModuleFrontController->initContent();
    }

    public function testInitContent_createOmiseCharge_redirectTheSystemToTheResultPage()
    {
        $this->payment_order->expects($this->once())
            ->method('redirectToResultPage');

        $this->omisePaymentModuleFrontController->initContent();
    }

    public function testInitContent_successfullyCreateOmiseCharge_saveOnlyOneOrder()
    {
        $this->omiseCharge->shouldReceive('create')
            ->once()
            ->andReturn($this->createSuccessOmiseChargeResult());

        $this->payment_order->expects($this->once())
            ->method('save');

        $this->omisePaymentModuleFrontController->initContent();
    }

    public function testInitContent_exceptionOccurWhenCreateOmiseCharge_displayExceptionMessage()
    {
        $this->omiseCharge->shouldReceive('create')
            ->andThrow(new Exception("exceptionMessage"));

        $this->smarty->expects($this->once())
            ->method('assign')
            ->with('error_message', 'exceptionMessage');

        $this->omisePaymentModuleFrontController->initContent();
    }

    private function createSuccessOmiseChargeResult()
    {
        $result = $this->getMockBuilder(get_class(new stdClass()));
        $result->object = 'charge';

        return $result;
    }

    public function tearDown()
    {
        m::close();
    }
}
