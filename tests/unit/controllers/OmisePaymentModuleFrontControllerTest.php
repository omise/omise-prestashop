<?php
use \Mockery as m;

class OmisePaymentModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $omiseCharge;
    private $omisePaymentModuleFrontController;
    private $smarty;

    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('ModuleFrontController')
            ->setMethods(
                array(
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

        $context = $this->createMock(get_class(new stdClass()));
        $context->smarty = $this->smarty;

        $this->omiseCharge = m::mock('overload:\Charge');

        $this->omisePaymentModuleFrontController = new OmisePaymentModuleFrontController();
        $this->omisePaymentModuleFrontController->context = $context;
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
        $result = $this->createMock(get_class(new stdClass()));
        $result->object = 'charge';

        return $result;
    }

    public function tearDown()
    {
        m::close();
    }
}
