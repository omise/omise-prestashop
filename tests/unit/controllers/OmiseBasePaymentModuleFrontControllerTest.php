<?php
use \Mockery as m;

class OmiseBasePaymentModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('ModuleFrontController')
            ->setMethods(
                array(
                    '__construct',
                )
            )
            ->getMock();

        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('PaymentModule')
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

    public function tearDown()
    {
        m::close();
    }
}
