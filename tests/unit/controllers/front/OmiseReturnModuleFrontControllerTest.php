<?php
use \Mockery as m;

class OmiseReturnModuleFrontControllerTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    private $omise_return_module_front_controller;

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

        m::mock('overload:\Order');

        m::mock('alias:\Tools')->shouldIgnoreMissing();

        m::mock('alias:\Validate')->shouldIgnoreMissing();

        $this->omise_return_module_front_controller = new OmiseReturnModuleFrontController();
        $this->omise_return_module_front_controller->payment_order = $this->getMockedPaymentOrder();
    }

    public function testPostProcess_orderIsNotFound_errorMessageMustBeDefined()
    {
        m::mock('alias:\Validate')
            ->shouldReceive('isLoadedObject')
            ->andReturn(false);

        $this->omise_return_module_front_controller
            ->expects($this->once())
            ->method('l')
            ->with('Order not found.');

        $this->omise_return_module_front_controller->postProcess();
    }

    public function testPostProcess_returnFromOmiseApi_getValueThatPassedBackFromOmiseApi()
    {
        m::mock('alias:\Tools')
            ->shouldReceive('getValue')->with('id_cart')->andReturn('idCart')
            ->shouldReceive('getValue')->with('id_module')->andReturn('idModule')
            ->shouldReceive('getValue')->with('id_order')->andReturn('idOrder')
            ->shouldReceive('getValue')->with('key')->andReturn('key');

        $this->omise_return_module_front_controller->postProcess();
    }

    private function getMockedPaymentOrder()
    {
        $payment_order = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'saveAsProcessing',
                    'updatePaymentTransactionId',
                    'updateStateToBeCanceled',
                    'updateStateToBeSuccess',
                )
            )
            ->getMock();

        return $payment_order;
    }
}
