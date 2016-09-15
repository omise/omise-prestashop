<?php
define('_PS_VERSION_', 'TEST_VERSION');

class OmiseTest extends PHPUnit_Framework_TestCase
{
    private $omise;

    public function setup()
    {
        $paymentModule = $this->getMockBuilder(stdClass::class)
            ->setMockClassName('PaymentModule')
            ->setMethods(array('__construct', 'l'))
            ->getMock();

        $this->omise = new Omise();
    }

    public function testName_omise()
    {
        $this->assertEquals('omise', $this->omise->name);
    }

    public function testDisplayName_Omise()
    {
        $this->assertEquals('Omise', $this->omise->displayName);
    }

    public function testNeedInstance_0()
    {
        $this->assertEquals(0, $this->omise->need_instance);
    }

    public function testBootstrap_true()
    {
        $this->assertEquals(true, $this->omise->bootstrap);
    }
}
