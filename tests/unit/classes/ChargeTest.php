<?php
use \Mockery as m;

class ChargeTest extends PHPUnit_Framework_TestCase
{
    private $charge;
    private $omise_plugin_helper_charge;
    private $secret_key = 'secretKey';

    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('ModuleFrontController')
            ->setMethods(
                array(
                    'initContent',
                )
            )
            ->getMock();

        $context = $this->getMockBuilder(get_class(new stdClass()));
        $context->cart = new Cart();

        $currency_instance = $this->getMockBuilder(get_class(new stdClass()));
        $currency_instance->iso_code = 'THB';

        m::mock('alias:\Currency')
            ->shouldReceive('getCurrencyInstance')
            ->with(1234)
            ->andReturn($currency_instance);

        $this->omise_plugin_helper_charge = m::mock('alias:\OmisePluginHelperCharge')
            ->shouldReceive('amount')
            ->andReturn(10025);

        m::mock('overload:\Setting')
            ->shouldReceive('getSecretKey')
            ->andReturn($this->secret_key);

        m::mock('alias:\Tools')
            ->shouldReceive('getValue')
            ->with('omise_card_token')
            ->andReturn('cardToken');

        $this->charge = new Charge();
        $this->charge->context = $context;
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testCreate_createOmiseCharge_createOnlyOneOmiseChargeWithCompleteRequestParameters()
    {
        m::mock('alias:\OmiseCharge')
            ->shouldReceive('create')
            ->with($this->createChargeRequest(), '', $this->secret_key)
            ->once();

        $this->charge->create();
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
            'amount' => '10025',
            'card' => 'cardToken',
            'capture' => 'true',
            'currency' => 'THB',
            'description' => 'Charge a card using a token from PrestaShop (' . _PS_VERSION_ . ')',
        );

        return $charge_request;
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

    public $id_currency = 1234;

    public function getOrderTotal($with_taxes, $type)
    {
        return '100.25';
    }
}
