<?php
if (! defined('_PS_VERSION_')) {
    define('_PS_VERSION_', 'TEST_VERSION');
}

class OmiseTest extends PHPUnit_Framework_TestCase
{
    private $checkout_form;
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
                    'l',
                )
            )
            ->getMock();

        $this->checkout_form = $this->getMockBuilder(CheckoutForm::class)
            ->setMethods(
                array(
                    'getListOfExpirationYear',
                )
            )
            ->getMock();

        $this->setting = $this->getMockBuilder(Setting::class)
            ->setMethods(
                array(
                    'getLivePublicKey',
                    'getLiveSecretKey',
                    'getSubmitAction',
                    'getTestPublicKey',
                    'getTestSecretKey',
                    'getTitle',
                    'isModuleEnabled',
                    'isSandboxEnabled',
                    'isSubmit',
                    'save',
                )
            )
            ->getMock();

        $this->smarty = $this->getMockBuilder(stdClass::class)
            ->setMockClassName('Smarty')
            ->setMethods(
                array(
                    'assign',
                )
            )
            ->getMock();

        $this->omise = new Omise();
        $this->omise->setCheckoutForm($this->checkout_form);
        $this->omise->setSetting($this->setting);
        $this->omise->setSmarty($this->smarty);
    }

    public function testConstructor_whenInitiateTheNewInstance_theDefaultValueOfTheAttributeSettingMustBeAvailable()
    {
        $omise = new Omise();

        $setting = $omise->getSetting();

        $this->assertInstanceOf(get_class(new Setting()), $setting);
    }

    public function testName_theNameThatUsedToReferenceInTheProgramMustBe_omise()
    {
        $this->assertEquals('omise', $this->omise->name);
    }

    public function testDisplayName_theNameThatUsedToDisplayToTheMerchantMustBe_Omise()
    {
        $this->assertEquals('Omise', $this->omise->displayName);
    }

    public function testNeedInstance_configureTheModule_noNeedToLoadModuleAtTheBackendModulePage()
    {
        $this->assertEquals(0, $this->omise->need_instance);
    }

    public function testBootstrap_configureTheModule_bootstrapTemplateIsRequired()
    {
        $this->assertEquals(true, $this->omise->bootstrap);
    }

    public function testGetContent_merchantOpenTheSettingPage_retrieveSettingDataFromTheDatabaseAndDisplayOnThePage()
    {
        $this->setting->method('getLivePublicKey')->willReturn('live_public_key');
        $this->setting->method('getLiveSecretKey')->willReturn('live_secret_key');
        $this->setting->method('isModuleEnabled')->willReturn('module_status');
        $this->setting->method('isSandboxEnabled')->willReturn('sandbox_status');
        $this->setting->method('getSubmitAction')->willReturn('submit_action');
        $this->setting->method('getTestPublicKey')->willReturn('test_public_key');
        $this->setting->method('getTestSecretKey')->willReturn('test_secret_key');
        $this->setting->method('getTitle')->willReturn('title');

        $this->smarty->expects($this->once())
            ->method('assign')
            ->with(array(
                'live_public_key' => 'live_public_key',
                'live_secret_key' => 'live_secret_key',
                'module_status' => 'module_status',
                'sandbox_status' => 'sandbox_status',
                'submit_action' => 'submit_action',
                'test_public_key' => 'test_public_key',
                'test_secret_key' => 'test_secret_key',
                'title' => 'title',
            ));

        $this->omise->getContent();
    }

    public function testGetContent_merchantSaveSetting_saveTheSettingData()
    {
        $this->setting->method('isSubmit')->willReturn(true);

        $this->setting->expects($this->once())->method('save');

        $this->omise->getContent();
    }

    public function testHookPayment_moduleIsActivatedAndTheSettingOfModuleStatusIsEnabled_displayThePaymentTemplateFile()
    {
        $this->omise->active = true;
        $this->setting->method('isModuleEnabled')->willReturn(true);
        $this->omise->method('display')->willReturn('payment_template_file');

        $this->assertEquals('payment_template_file', $this->omise->hookPayment(''));
    }

    public function testHookPayment_moduleIsActivatedAndTheSettingOfModuleStatusIsEnabled_displayCheckoutForm()
    {
        $this->omise->active = true;
        $this->setting->method('isModuleEnabled')->willReturn(true);
        $this->setting->method('getTitle')->willReturn('title_at_header_of_checkout_form');
        $this->checkout_form->method('getListOfExpirationYear')->willReturn('list_of_expiration_year');

        $this->smarty->expects($this->exactly(2))
            ->method('assign')
            ->withConsecutive(
                 array('list_of_expiration_year', 'list_of_expiration_year'),
                 array('omise_title', 'title_at_header_of_checkout_form')
             );

        $this->omise->hookPayment();
    }

    public function testHookPayment_moduleIsActivatedButTheSettingOfModuleStatusIsDisabled_paymentFormMustNotBeDisplayed()
    {
        $this->omise->active = true;
        $this->setting->method('isModuleEnabled')->willReturn(false);

        $this->assertNull($this->omise->hookPayment());
    }

    public function testHookPayment_moduleIsInactivatedButTheSettingOfModuleStatusIsEnabled_paymentFormMustNotBeDisplayed()
    {
        $this->omise->active = false;
        $this->setting->method('isModuleEnabled')->willReturn(true);

        $this->assertNull($this->omise->hookPayment());
    }

    public function testHookPayment_moduleIsInactivatedAndTheSettingOfModuleStatusIsDisabled_paymentFormMustNotBeDisplayed()
    {
        $this->omise->active = false;
        $this->setting->method('isModuleEnabled')->willReturn(false);

        $this->assertNull($this->omise->hookPayment());
    }
}
