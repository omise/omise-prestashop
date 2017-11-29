<?php
use \Mockery as m;

class OmiseBasePaymentModuleFrontControllerTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    public function setup()
    {
        $unit_test_helper = new UnitTestHelper();

        $unit_test_helper->getMockedPaymentModule();

        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('ModuleFrontController')
            ->setMethods(
                array(
                    '__construct',
                )
            )
            ->getMock();

        m::mock('alias:\OmiseChargeClass');
        m::mock('alias:\OmiseTransactionModel');
        m::mock('alias:\PaymentOrder');

        $this->getMockBuilder('OmiseBasePaymentModuleFrontController')
            ->getMockForAbstractClass();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testOmiseApiVersion_beforeSendTheRequestToOmiseApi_omiseApiVersionIs20151117()
    {
        $this->assertEquals('2015-11-17', OMISE_API_VERSION);
    }

    /**
      * @runInSeparateProcess
      * @preserveGlobalState disabled
      */
    public function testOmiseUserAgentSuffix_beforeCreateOmiseCharge_omisePrestaShopVersionForOmiseUserAgentSuffixMustBeDefined()
    {
        $omise_prestashop_version = 'OmisePrestaShop/' . Omise::MODULE_VERSION;

        $omise_user_agent_suffix = explode(' ', OMISE_USER_AGENT_SUFFIX);

        $this->assertEquals($omise_prestashop_version, $omise_user_agent_suffix[0]);
    }

    /**
      * @runInSeparateProcess
      * @preserveGlobalState disabled
      */
    public function testOmiseUserAgentSuffix_beforeCreateOmiseCharge_prestaShopVersionForOmiseUserAgentSuffixMustBeDefined()
    {
        $prestashop_version = 'PrestaShop/' . _PS_VERSION_;

        $omise_user_agent_suffix = explode(' ', OMISE_USER_AGENT_SUFFIX);

        $this->assertEquals($prestashop_version, $omise_user_agent_suffix[1]);
    }
}
