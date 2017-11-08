<?php
use \Mockery as m;

class OmiseReturnModuleFrontControllerTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    private $omise_return_module_front_controller;

    public function setup()
    {
        $unit_test_helper = new UnitTestHelper();

        $unit_test_helper->getMockedOmiseBasePaymentModuleFrontController();

        m::mock('alias:\Order');

        m::mock('alias:\Tools')
            ->shouldReceive('getValue')->with('id_cart')->andReturn('idCart')
            ->shouldReceive('getValue')->with('id_module')->andReturn('idModule')
            ->shouldReceive('getValue')->with('id_order')->andReturn('idOrder')
            ->shouldReceive('getValue')->with('key')->andReturn('key');

        m::mock('alias:\Validate')->shouldIgnoreMissing();

        $this->omise_return_module_front_controller = new OmiseReturnModuleFrontController();
        $this->omise_return_module_front_controller->payment_order = $unit_test_helper->getMockedPaymentOrder();
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
}
