<?php
if (! defined('_PS_VERSION_')) {
    define('_PS_VERSION_', 'TEST_VERSION');
}

class OmiseTest extends PHPUnit_Framework_TestCase
{
    private $omise;
    private $setting;
    private $smarty;

    public function setup()
    {
        $this->getMockBuilder(stdClass::class)
            ->setMockClassName('PaymentModule')
            ->setMethods(
                array(
                    '__construct',
                    'display',
                    'displayConfirmation',
                    'l'
                )
            )
            ->getMock();

        $this->setting = $this->getMockBuilder(Setting::class)
            ->setMethods(
                array(
                    'generateForm',
                    'getTitle',
                    'isModuleEnabled',
                    'isSubmit',
                    'save'
                )
            )
            ->getMock();

        $this->smarty = $this->getMockBuilder(stdClass::class)
            ->setMockClassName('Smarty')
            ->setMethods(array('assign'))
            ->getMock();

        $this->omise = new Omise();
        $this->omise->setSetting($this->setting);
        $this->omise->setSmarty($this->smarty);
    }

    public function testName_omise()
    {
        $this->assertEquals('omise', $this->omise->name);
    }

    public function testDisplayName_Omise()
    {
        $this->assertEquals('Omise', $this->omise->displayName);
    }

    public function testNeedInstance_noNeedToLoadModuleAtTheBackendModulePage()
    {
        $this->assertEquals(0, $this->omise->need_instance);
    }

    public function testBootstrap_bootstrapTemplateIsRequired()
    {
        $this->assertEquals(true, $this->omise->bootstrap);
    }

    public function testConstructor_whenNewTheInstance_theDefaultAttributeSettingMustBeAvailable()
    {
        $omise = new Omise();

        $setting = $omise->getSetting();

        $this->assertNotEmpty($setting);
    }

    public function testGetContent_merchantOpenTheSettingPage_settingMustNotBeSaved()
    {
        $this->setting->method('isSubmit')->willReturn(false);

        $this->setting->expects($this->never())->method('save');

        $this->omise->getContent();
    }

    public function testGetContent_merchantSaveSetting_settingMustBeSaved()
    {
        $this->setting->method('isSubmit')->willReturn(true);

        $this->setting->expects($this->once())->method('save');

        $this->omise->getContent();
    }

    public function testHookPayment_moduleIsActivatedAndEnabled_displayThePaymentForm()
    {
        $this->omise->active = true;
        $this->setting->method('isModuleEnabled')->willReturn(true);
        $this->omise->method('display')->willReturn('payment_form');

        $this->assertEquals('payment_form', $this->omise->hookPayment(''));
    }

    public function testHookPayment_moduleIsActivatedAndEnabled_displayTitle()
    {
        $this->omise->active = true;
        $this->setting->method('isModuleEnabled')->willReturn(true);
        $this->setting->method('getTitle')->willReturn('title_at_header_of_checkout_form');

        $this->smarty->expects($this->once())
            ->method('assign')
            ->with('omise_title', 'title_at_header_of_checkout_form');

        $this->omise->hookPayment('');
    }

    public function testHookPayment_moduleIsActivatedButModuleIsDisabled_paymentFormMustNotBeDisplayed()
    {
        $this->omise->active = true;
        $this->setting->method('isModuleEnabled')->willReturn(false);

        $this->assertNull($this->omise->hookPayment(''));
    }

    public function testHookPayment_moduleIsInactivatedButModuleIsEnabled_paymentFormMustNotBeDisplayed()
    {
        $this->omise->active = false;
        $this->setting->method('isModuleEnabled')->willReturn(true);

        $this->assertNull($this->omise->hookPayment(''));
    }

    public function testHookPayment_moduleIsInactivatedAndDisabled_paymentFormMustNotBeDisplayed()
    {
        $this->omise->active = false;
        $this->setting->method('isModuleEnabled')->willReturn(false);

        $this->assertNull($this->omise->hookPayment(''));
    }
}
