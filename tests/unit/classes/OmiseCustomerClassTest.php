<?php
use \Mockery as m;

class OmiseCustomerClassTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    private $omise_customer_class;

    public function setup()
    {
        $this->omise_customer_class = new OmiseCustomerClass();
        $this->omise_customer_class->setSetting($this->getMockSetting());
    }

    public function testCreate_createOmiseCustomer_createOnlyOneOmiseCustomerWithCompleteRequestParameters()
    {
        m::mock('alias:\OmiseCustomer')
            ->shouldReceive('create')
            ->with($this->createCustomerRequest(), '', 'secretKey')
            ->once();

        $this->omise_customer_class->create('cardToken');
    }

    public function testCreate_successfullyCreateOmiseCustomer_instanceOfOmiseCustomerClassMustBeReturned()
    {
        m::mock('alias:\OmiseCustomer')->shouldIgnoreMissing();

        $this->assertInstanceOf(OmiseCustomerClass::class, $this->omise_customer_class->create('cardToken'));
    }

    public function testGetId_afterSuccessfullyCreateOmiseCustomer_omiseCustomerIdMustBeAvailable()
    {
        m::mock('alias:\OmiseCustomer')
            ->shouldReceive('create')
            ->andReturn($this->createCustomerResponse());

        $this->omise_customer_class->create('cardToken');

        $this->assertEquals('omiseCustomerId', $this->omise_customer_class->getId());
    }

    private function createCustomerRequest()
    {
        $customer_request = array(
            'card' => 'cardToken',
        );

        return $customer_request;
    }

    private function createCustomerResponse()
    {
        $customer_response = array(
            'id' => 'omiseCustomerId',
        );

        return $customer_response;
    }

    private function getMockSetting()
    {
        $setting = $this->getMockBuilder(get_class(new Setting()))
            ->setMethods(
                array(
                    'getSecretKey',
                )
            )
            ->getMock();

        $setting->method('getSecretKey')->willReturn('secretKey');

        return $setting;
    }
}
