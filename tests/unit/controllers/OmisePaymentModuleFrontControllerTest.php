<?php
if (! defined('_PS_MODULE_DIR_')) {
    define('_PS_MODULE_DIR_', __DIR__ . '/../../..');
}

class OmisePaymentModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $omisePaymentModuleFrontController;

    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('ModuleFrontController')
            ->getMock();

        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('PaymentModule')
            ->getMock();

        $this->omisePaymentModuleFrontController = new OmisePaymentModuleFrontController();
    }

    public function testDisplayColumnLeft_displayTheResultPage_theLeftColumnWillNotAppear()
    {
        $this->assertFalse($this->omisePaymentModuleFrontController->display_column_left);
    }

    public function testGetCardToken_createOmiseCharge_getOmiseCardTokenFromClientSide()
    {
        \Mockery::mock('alias:\Tools')
            ->shouldReceive('getValue')
            ->with('omise_card_token')
            ->andReturn('cardToken');

        $card_token = $this->omisePaymentModuleFrontController->getCardToken();

        $this->assertEquals('cardToken', $card_token);
    }

    public function testGetCapture_createOmiseCharge_captureIsTrue()
    {
        $this->assertEquals('true', $this->omisePaymentModuleFrontController->getCapture());
    }

    public function testGetChargeDescription_createOmiseCharge_validChargeDescription()
    {
        $validChargeDescription = 'Charge a card using a token from PrestaShop (' . _PS_VERSION_ . ')';

        $chargeDescription = $this->omisePaymentModuleFrontController->getChargeDescription();

        $this->assertEquals($validChargeDescription, $chargeDescription);
    }

    public function testGetCurrencyCode_createOmiseCharge_getCurrencyCodeFromCart()
    {
        $cart = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('Cart')
            ->setMethods(
                array(
                    'getOrderTotal',
                )
            )
            ->getMock();
        $cart->id_currency = 1234;
        $cart->method('getOrderTotal')->willReturn('123.45');

        $context = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('Context')
            ->getMock();
        $context->cart = $cart;

        $currency_instance = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('CurrencyInstance')
            ->getMock();
        $currency_instance->iso_code = 'THB';

        \Mockery::mock('alias:\Currency')
            ->shouldReceive('getCurrencyInstance')
            ->with(1234)
            ->andReturn($currency_instance);

        $this->omisePaymentModuleFrontController->context = $context;

        $currency_code = $this->omisePaymentModuleFrontController->getCurrencyCode();

        $this->assertEquals('THB', $currency_code);
    }

    public function testGetSecretKey_createOmiseCharge_getSecretKeyFromSetting()
    {
        \Mockery::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('sandbox_status')
            ->andReturn(true)
            ->shouldReceive('get')
            ->with('test_secret_key')
            ->andReturn('secretKey');

        $secretKey = $this->omisePaymentModuleFrontController->getSecretKey();

        $this->assertEquals('secretKey', $secretKey);
    }
}
