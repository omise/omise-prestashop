<?php
use \Mockery as m;

class OmiseTest extends PHPUnit_Framework_TestCase
{
    private $checkout_form;
    private $omise;
    private $omise_transaction_model;
    private $setting;
    private $smarty;

    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('PaymentModule')
            ->setMethods(
                array(
                    '__construct',
                    'display',
                    'displayConfirmation',
                    'install',
                    'l',
                    'registerHook',
                    'uninstall',
                    'unregisterHook',
                )
            )
            ->getMock();

        $this->checkout_form = $this->getMockBuilder(get_class(new CheckoutForm()))
            ->setMethods(
                array(
                    'getListOfExpirationYear',
                )
            )
            ->getMock();

        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->shouldReceive('deleteByName');

        $this->omise_transaction_model = $this->getMockedOmiseTransactionModel();

        $this->setting = $this->getMockedSetting();

        $this->smarty = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('Smarty')
            ->setMethods(
                array(
                    'assign',
                )
            )
            ->getMock();

        $this->omise = new Omise();
        $this->omise->context = $this->getMockedContext();
        $this->omise->setCheckoutForm($this->checkout_form);
        $this->omise->setSetting($this->setting);
        $this->omise->setSmarty($this->smarty);
        $this->omise->setOmiseTransactionModel($this->omise_transaction_model);
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
        $this->setting->method('isInternetBankingEnabled')->willReturn('internet_banking_status');
        $this->setting->method('isModuleEnabled')->willReturn('module_status');

        $this->smarty->expects($this->once())
            ->method('assign')
            ->with(array(
                'internet_banking_status' => 'internet_banking_status',
                'live_public_key' => 'live_public_key',
                'live_secret_key' => 'live_secret_key',
                'module_status' => 'module_status',
                'sandbox_status' => 'sandbox_status',
                'submit_action' => 'submit_action',
                'test_public_key' => 'test_public_key',
                'test_secret_key' => 'test_secret_key',
                'title' => 'title',
                'three_domain_secure_status' => 'three_domain_secure_status',
            ));

        $this->omise->getContent();
    }

    public function testGetContent_merchantSaveSetting_saveTheSettingData()
    {
        $this->setting->method('isInternetBankingEnabled')->willReturn('internet_banking_status');
        $this->setting->method('isSubmit')->willReturn(true);

        $this->setting->expects($this->once())->method('save');

        $this->omise->getContent();
    }

    public function testHookPayment_moduleIsInactivated_noPaymentDisplayed()
    {
        $this->omise->active = false;

        $this->assertNull($this->omise->hookPayment());
    }

    public function testHookPayment_moduleIsActivatedButCurrentCurrencyIsNotApplicable_displayInapplicablePayment()
    {
        $this->omise->active = true;
        $this->setting->method('isModuleEnabled')->willReturn(true);
        m::mock('alias:\OmisePluginHelperCharge')
            ->shouldReceive('isCurrentCurrencyApplicable')
            ->andReturn(false);
        $this->omise->method('display')->willReturn('inapplicable_payment');

        $this->assertEquals('inapplicable_payment', $this->omise->hookPayment());
    }

    public function testHookPayment_displayInapplicablePayment_omiseTitleMustBeDisplayed()
    {
        $this->omise->active = true;
        $this->setting->method('isModuleEnabled')->willReturn(true);
        m::mock('alias:\OmisePluginHelperCharge')
            ->shouldReceive('isCurrentCurrencyApplicable')
            ->andReturn(false);

        $this->smarty->expects($this->once())
            ->method('assign')
            ->with('omise_title', 'title');

        $this->omise->hookPayment();
    }

    public function testHookPayment_moduleIsActivatedAndCurrentCurrencyIsApplicableAndModuleStatusIsEnabled_displayPayment()
    {
        $this->omise->active = true;
        m::mock('alias:\OmisePluginHelperCharge')
            ->shouldReceive('isCurrentCurrencyApplicable')
            ->andReturn(true);
        $this->setting->method('isModuleEnabled')->willReturn(true);
        $this->setting->method('isInternetBankingEnabled')->willReturn(false);
        $this->omise->method('display')->willReturn('payment');

        $this->assertEquals('payment', $this->omise->hookPayment());
    }

    public function testHookPayment_displayPayment_theValueMustBeAssignedForDisplayPaymentForm()
    {
        $this->omise->active = true;
        m::mock('alias:\OmisePluginHelperCharge')
            ->shouldReceive('isCurrentCurrencyApplicable')
            ->andReturn(true);
        $this->setting->method('isModuleEnabled')->willReturn(true);
        $this->setting->method('isInternetBankingEnabled')->willReturn(false);

        $this->checkout_form->method('getListOfExpirationYear')->willReturn('list_of_expiration_year');
        $this->omise->context->link->method('getModuleLink')->willReturn('payment');

        $this->smarty->expects($this->exactly(3))
            ->method('assign')
            ->withConsecutive(
                array('action', 'payment'),
                array('list_of_expiration_year', 'list_of_expiration_year'),
                array('omise_public_key', 'omise_public_key')
            );

        $this->omise->hookPayment();
    }

    public function testHookPayment_moduleStatusIsEnabledAndInternetBankingIsEnabled_displayPaymentAndInternetBankingPayment()
    {
        $this->omise->active = true;
        m::mock('alias:\OmisePluginHelperCharge')
            ->shouldReceive('isCurrentCurrencyApplicable')
            ->andReturn(true);
        $this->setting->method('isModuleEnabled')->willReturn(true);
        $this->setting->method('isInternetBankingEnabled')->willReturn(true);
        $this->omise->method('display')->willReturnOnConsecutiveCalls('payment', 'paymentInternetBanking');

        $this->assertEquals('paymentpaymentInternetBanking', $this->omise->hookPayment());
    }

    public function testHookPayment_moduleStatusIsDisabledButInternetBankingIsEnabled_displayInternetBankingPayment()
    {
        $this->omise->active = true;
        m::mock('alias:\OmisePluginHelperCharge')
            ->shouldReceive('isCurrentCurrencyApplicable')
            ->andReturn(true);
        $this->setting->method('isModuleEnabled')->willReturn(false);
        $this->setting->method('isInternetBankingEnabled')->willReturn(true);
        $this->omise->method('display')->willReturn('paymentInternetBanking');

        $this->assertEquals('paymentInternetBanking', $this->omise->hookPayment());
    }

    public function testHookPayment_moduleStatusIsDisabledAndInternetBankingIsDisabled_noPaymentDisplayed()
    {
        $this->omise->active = true;
        m::mock('alias:\OmisePluginHelperCharge')
            ->shouldReceive('isCurrentCurrencyApplicable')
            ->andReturn(true);
        $this->setting->method('isModuleEnabled')->willReturn(false);
        $this->setting->method('isInternetBankingEnabled')->willReturn(false);

        $this->assertEquals('', $this->omise->hookPayment());
    }

    public function testHookPaymentOptions_moduleStatusIsDisabled_paymentOptionsIsNull()
    {
        $this->setting->method('isModuleEnabled')->willReturn(false);

        $payment_options = $this->omise->hookPaymentOptions();

        $this->assertNull($payment_options);
    }

    public function testHookPaymentOptions_moduleStatusIsEnabled_displayCardPaymentOption()
    {
        $this->setting->method('isModuleEnabled')->willReturn(true);
        m::mock('alias:\OmisePluginHelperCharge')
            ->shouldReceive('isCurrentCurrencyApplicable')
            ->andReturn(true);
        $this->omise->method('display')->willReturn('payment');

        m::mock('overload:PrestaShop\PrestaShop\Core\Payment\PaymentOption')
            ->shouldReceive('setCallToActionText')->with($this->setting->getTitle())->once()
            ->shouldReceive('setForm')->with('payment')->once()
            ->shouldReceive('setModuleName')->with(Omise::CARD_PAYMENT_OPTION_NAME);

        $payment_options = $this->omise->hookPaymentOptions();

        $this->assertNotNull($payment_options);
    }

    public function testInstall_installationIsSuccess_true()
    {
        $this->omise->method('install')->willReturn(true);
        $this->omise->method('registerHook')->will($this->onConsecutiveCalls(true, true, true, true));
        $this->omise_transaction_model->method('createTable')->willReturn(true);
        $this->setting->method('saveTitle')->with(Omise::DEFAULT_CARD_PAYMENT_TITLE)->willReturn(true);

        $this->assertTrue($this->omise->install());
    }

    public function testInstall_createTableIsFail_false()
    {
        $this->omise->method('install')->willReturn(true);
        $this->omise->method('registerHook')->will($this->onConsecutiveCalls(true, true, true, true));
        $this->omise_transaction_model->method('createTable')->willReturn(false);

        $this->assertFalse($this->omise->install());
    }

    public function testInstall_parentInstallationIsFail_false()
    {
        $this->omise->method('install')->willReturn(false);
        $this->omise->method('registerHook')->will($this->onConsecutiveCalls(true, true, true, true));
        $this->omise_transaction_model->method('createTable')->willReturn(true);

        $this->assertFalse($this->omise->install());
    }

    public function testInstall_registerHookForDisplayOrderConfirmationIsFail_false()
    {
        $this->omise->method('install')->willReturn(true);
        $this->omise->method('registerHook')->will($this->onConsecutiveCalls(false, true, true, true));
        $this->omise_transaction_model->method('createTable')->willReturn(true);

        $this->assertFalse($this->omise->install());
    }

    public function testInstall_registerHookForHeaderIsFail_false()
    {
        $this->omise->method('install')->willReturn(true);
        $this->omise->method('registerHook')->will($this->onConsecutiveCalls(true, false, true, true));
        $this->omise_transaction_model->method('createTable')->willReturn(true);

        $this->assertFalse($this->omise->install());
    }

    public function testInstall_registerHookForPaymentIsFail_false()
    {
        $this->omise->method('install')->willReturn(true);
        $this->omise->method('registerHook')->will($this->onConsecutiveCalls(true, true, false, true));
        $this->omise_transaction_model->method('createTable')->willReturn(true);

        $this->assertFalse($this->omise->install());
    }

    public function testInstall_registerHookForPaymentOptionsIsFail_false()
    {
        $this->omise->method('install')->willReturn(true);
        $this->omise->method('registerHook')->will($this->onConsecutiveCalls(true, true, true, false));
        $this->omise_transaction_model->method('createTable')->willReturn(true);

        $this->assertFalse($this->omise->install());
    }

    public function testInstall_saveDefaultCardPaymentTitleIsFail_false()
    {
        $this->omise->method('install')->willReturn(true);
        $this->omise->method('registerHook')->will($this->onConsecutiveCalls(true, true, true, true));
        $this->omise_transaction_model->method('createTable')->willReturn(true);
        $this->setting->method('saveTitle')->with(Omise::DEFAULT_CARD_PAYMENT_TITLE)->willReturn(false);

        $this->assertFalse($this->omise->install());
    }

    public function testUninstall_uninstallTheModule_theSettingMustBeDeleted()
    {
        $this->setting->expects($this->once())
            ->method('delete');

        $this->omise->uninstall();
    }

    public function testUninstall_uninstallIsSuccess_true()
    {
        $this->omise->method('uninstall')->willReturn(true);
        $this->omise->method('unregisterHook')->will($this->onConsecutiveCalls(true, true, true, true));

        $this->assertTrue($this->omise->uninstall());
    }

    public function testUninstall_parentUninstallIsFail_false()
    {
        $this->omise->method('uninstall')->willReturn(false);
        $this->omise->method('unregisterHook')->will($this->onConsecutiveCalls(true, true, true, true));

        $this->assertFalse($this->omise->uninstall());
    }

    public function testUninstall_unregisterHookForDisplayOrderConfirmationIsFail_false()
    {
        $this->omise->method('uninstall')->willReturn(true);
        $this->omise->method('unregisterHook')->will($this->onConsecutiveCalls(false, true, true, true));

        $this->assertFalse($this->omise->uninstall());
    }

    public function testUninstall_unregisterHookForHeaderIsFail_false()
    {
        $this->omise->method('uninstall')->willReturn(true);
        $this->omise->method('unregisterHook')->will($this->onConsecutiveCalls(true, false, true, true));

        $this->assertFalse($this->omise->uninstall());
    }

    public function testUninstall_unregisterHookForPaymentIsFail_false()
    {
        $this->omise->method('uninstall')->willReturn(true);
        $this->omise->method('unregisterHook')->will($this->onConsecutiveCalls(true, true, false, true));

        $this->assertFalse($this->omise->uninstall());
    }

    public function testUninstall_unregisterHookPaymentOptionsIsFail_false()
    {
        $this->omise->method('uninstall')->willReturn(true);
        $this->omise->method('unregisterHook')->will($this->onConsecutiveCalls(true, true, true, false));

        $this->assertFalse($this->omise->uninstall());
    }

    private function getMockedContext()
    {
        $currency = $this->getMockBuilder(get_class(new stdClass()));
        $currency->iso_code = 'THB';

        $link = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'getModuleLink',
                )
            )
            ->getMock();

        $context = $this->getMockBuilder(get_class(new stdClass()))->getMock();
        $context->currency = $currency;
        $context->link = $link;

        return $context;
    }

    private function getMockedOmiseTransactionModel()
    {
        $omise_transaction_model = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('OmiseTransactionModel')
            ->setMethods(
                array(
                    'add',
                    'createTable',
                )
            )
            ->getMock();

        return $omise_transaction_model;
    }

    private function getMockedSetting()
    {
        $setting = $this->getMockBuilder(get_class(new Setting()))
            ->setMethods(
                array(
                    'delete',
                    'getLivePublicKey',
                    'getLiveSecretKey',
                    'getPublicKey',
                    'getSubmitAction',
                    'getTestPublicKey',
                    'getTestSecretKey',
                    'getTitle',
                    'isInternetBankingEnabled',
                    'isModuleEnabled',
                    'isSandboxEnabled',
                    'isSubmit',
                    'isThreeDomainSecureEnabled',
                    'save',
                    'saveTitle',
                )
            )
            ->getMock();

        $setting->method('getLivePublicKey')->willReturn('live_public_key');
        $setting->method('getLiveSecretKey')->willReturn('live_secret_key');
        $setting->method('getPublicKey')->willReturn('omise_public_key');
        $setting->method('getSubmitAction')->willReturn('submit_action');
        $setting->method('getTestPublicKey')->willReturn('test_public_key');
        $setting->method('getTestSecretKey')->willReturn('test_secret_key');
        $setting->method('getTitle')->willReturn('title');
        $setting->method('isSandboxEnabled')->willReturn('sandbox_status');
        $setting->method('isThreeDomainSecureEnabled')->willReturn('three_domain_secure_status');

        return $setting;
    }

    public function tearDown()
    {
        m::close();
    }
}
