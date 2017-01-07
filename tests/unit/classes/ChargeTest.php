<?php
use \Mockery as m;

class ChargeTest extends PHPUnit_Framework_TestCase
{
    private $charge;
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

        m::mock('alias:\OmisePluginHelperCharge')
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

    public function tearDown() {
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
