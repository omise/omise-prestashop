<?php
use \Mockery as m;

class ChargeTest extends PHPUnit_Framework_TestCase
{
    private $charge;
    private $omise_plugin_helper_charge;
    private $secret_key = 'secretKey';
    private $setting;

    public function setup()
    {
        $context = $this->getMockBuilder(get_class(new stdClass()));
        $context->cart = new Cart();

        $currency = $this->getMockBuilder(get_class(new stdClass()));
        $currency->iso_code = 'THB';
        $context->currency = $currency;

        $link = $this->getMockBuilder(get_class(new stdClass))
            ->setMethods(
                array(
                    'getModuleLink',
                )
            )
            ->getMock();
        $link->method('getModuleLink')->willReturn('returnUri');
        $context->link = $link;

        $customer = $this->getMockBuilder(get_class(new stdClass));
        $customer->secure_key = 'customerSecureKey';
        $context->customer = $customer;

        m::mock('alias:\Context')
            ->shouldReceive('getContext')
            ->andReturn($context);

        $this->omise_plugin_helper_charge = m::mock('alias:\OmisePluginHelperCharge')
            ->shouldReceive('amount')
            ->andReturn(10025);

        $module = $this->getMockBuilder(get_class(new stdClass()));
        $module->id = 'omise';

        m::mock('alias:\Module')
            ->shouldReceive('getInstanceByName')
            ->andReturn($module);

        m::mock('alias:\Order')
            ->shouldReceive('getOrderByCartId')
            ->andReturn(1234);

        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('PaymentModule')
            ->getMock();

        $this->setting = $this->getMockBuilder(get_class(new Setting()))
            ->setMethods(
                array(
                    'getSecretKey',
                    'isThreeDomainSecureEnabled',
                )
            )
            ->getMock();
        $this->setting
            ->method('getSecretKey')
            ->willReturn($this->secret_key);

        m::mock('alias:\Tools')
            ->shouldReceive('getValue')
            ->with('omise_card_token')
            ->andReturn('cardToken');

        $this->charge = new Charge();
        $this->charge->setSetting($this->setting);
    }

    public function testCreate_createOmiseCharge_createOnlyOneOmiseChargeWithCompleteRequestParameters()
    {
        m::mock('alias:\OmiseCharge')
            ->shouldReceive('create')
            ->with($this->createChargeRequest(), '', $this->secret_key)
            ->once();

        $this->charge->create();
    }

    public function testCreate_createThreeDomainSecureOmiseCharge_returnUriMustBeAddedToRequest()
    {
        $this->setting
            ->method('isThreeDomainSecureEnabled')
            ->willReturn(true);

        m::mock('alias:\OmiseCharge')
            ->shouldReceive('create')
            ->with($this->createThreeDomainSecureChargeRequest(), '', $this->secret_key)
            ->once();

        $this->charge->create();
    }

    public function testGetAuthorizeUri_afterReceivedThreeDomainSecureResponseFromOmiseApi_authorizeUri()
    {
        m::mock('alias:\OmiseCharge')
            ->shouldReceive('create')
            ->andReturn($this->createThreeDomainSecureChargeResponse());

        $this->charge->create();

        $authorize_uri = $this->charge->getAuthorizeUri();

        $this->assertEquals('authorizeUri', $authorize_uri);
    }

    public function testGetErrorMessage_createOmiseChargeIsFail_failureCodeAndMessage()
    {
        $this->omise_plugin_helper_charge
            ->shouldReceive('getErrorMessage')
            ->andReturn($this->createChargeErrorMessage());

        $error_message = $this->charge->getErrorMessage();

        $this->assertEquals('(failureCode) failureMessage', $error_message);
    }

    public function testIsFailed_createOmiseChargeIsFail_true()
    {
        $this->omise_plugin_helper_charge
            ->shouldReceive('isFailed')
            ->andReturn(true);

        $is_charge_failed = $this->charge->isFailed();

        $this->assertTrue($is_charge_failed);
    }

    private function createChargeRequest()
    {
        $charge_request = array(
            'amount' => 10025,
            'card' => 'cardToken',
            'capture' => 'true',
            'currency' => 'THB',
            'description' => 'Charge a card using a token from PrestaShop (' . _PS_VERSION_ . ')',
        );

        return $charge_request;
    }

    private function createThreeDomainSecureChargeRequest()
    {
        $charge_request = $this->createChargeRequest();
        $charge_request['return_uri'] = 'returnUri?id_cart=1&id_module=omise&id_order=1234&key=customerSecureKey';

        return $charge_request;
    }

    private function createThreeDomainSecureChargeResponse()
    {
        $response = array(
            'authorize_uri' => 'authorizeUri',
        );

        return $response;
    }

    private function createChargeErrorMessage()
    {
        return '(failureCode) failureMessage';
    }

    public function tearDown()
    {
        m::close();
    }
}

class Cart
{
    const BOTH = 'both';

    public $id = 1;
    public $id_currency = 1234;

    public function getOrderTotal($with_taxes, $type)
    {
        return '100.25';
    }
}
