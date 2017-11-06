<?php
use \Mockery as m;

class SettingTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    private $setting;

    public function __construct()
    {
        $this->setting = new Setting();
    }

    public function testDelete_deleteSetting_deleteAllSetting()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('deleteByName')->with('omise_module_status')->once()
            ->shouldReceive('deleteByName')->with('omise_sandbox_status')->once()
            ->shouldReceive('deleteByName')->with('omise_test_public_key')->once()
            ->shouldReceive('deleteByName')->with('omise_test_secret_key')->once()
            ->shouldReceive('deleteByName')->with('omise_live_public_key')->once()
            ->shouldReceive('deleteByName')->with('omise_live_secret_key')->once()
            ->shouldReceive('deleteByName')->with('omise_title')->once()
            ->shouldReceive('deleteByName')->with('omise_three_domain_secure_status')->once()
            ->shouldReceive('deleteByName')->with('omise_internet_banking_status')->once();

        $this->setting->delete();
    }

    public function testGetLivePublicKey_getLivePublicKey_livePublicKey()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_live_public_key')
            ->once();

        $this->setting->getLivePublicKey();
    }

    public function testGetLiveSecretKey_getLiveSecretKey_liveSecretKey()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_live_secret_key')
            ->once();

        $this->setting->getLiveSecretKey();
    }

    public function testGetPublicKey_sandboxIsDisabled_livePublicKey()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_sandbox_status')
            ->andReturn(false)
            ->shouldReceive('get')
            ->with('omise_live_public_key')
            ->once();

        $this->setting->getPublicKey();
    }

    public function testGetPublicKey_sandboxIsEnabled_testPublicKey()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_sandbox_status')
            ->andReturn(true)
            ->shouldReceive('get')
            ->with('omise_test_public_key')
            ->once();

        $this->setting->getPublicKey();
    }

    public function testGetSecretKey_sandboxIsDisabled_liveSecretKey()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_sandbox_status')
            ->andReturn(false)
            ->shouldReceive('get')
            ->with('omise_live_secret_key')
            ->once();

        $this->setting->getSecretKey();
    }

    public function testGeSecretKey_sandboxIsEnabled_testSecretKey()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_sandbox_status')
            ->andReturn(true)
            ->shouldReceive('get')
            ->with('omise_test_secret_key')
            ->once();

        $this->setting->getSecretKey();
    }

    public function testGetSubmitAction_getSubmitAction_submitActionValue()
    {
        $this->assertEquals('omise_save_setting', $this->setting->getSubmitAction());
    }

    public function testGetTestPublicKey_getTestPublicKey_testPublicKey()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_test_public_key')
            ->once();

        $this->setting->getTestPublicKey();
    }

    public function testGetTestSecretKey_getTestSecretKey_testSecretKey()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_test_secret_key')
            ->once();

        $this->setting->getTestSecretKey();
    }

    public function testGetTitle_getTitle_title()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_title')
            ->once();

        $this->setting->getTitle();
    }

    public function testIsInternetBankingEnabled_internetBankingIsDisabled_false()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_internet_banking_status')
            ->andReturn(false);

        $this->assertFalse($this->setting->isInternetBankingEnabled());
    }

    public function testIsInternetBankingEnabled_internetBankingIsEnabled_true()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_internet_banking_status')
            ->andReturn(true);

        $this->assertTrue($this->setting->isInternetBankingEnabled());
    }

    public function testIsModuleEnabled_moduleIsDisabled_false()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_module_status')
            ->andReturn(false);

        $this->assertFalse($this->setting->isModuleEnabled());
    }

    public function testIsModuleEnabled_moduleIsEnabled_true()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_module_status')
            ->andReturn(true);

        $this->assertTrue($this->setting->isModuleEnabled());
    }

    public function testIsSandboxEnabled_sandboxIsDisabled_false()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_sandbox_status')
            ->andReturn(false);

        $this->assertFalse($this->setting->isSandboxEnabled());
    }

    public function testIsSandboxEnabled_sandboxIsEnabled_true()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_sandbox_status')
            ->andReturn(true);

        $this->assertTrue($this->setting->isSandboxEnabled());
    }

    public function testIsThreeDomainSecureEnabled_threeDomainSecureIsDisabled_false()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_three_domain_secure_status')
            ->andReturn(false);

        $this->assertFalse($this->setting->isThreeDomainSecureEnabled());
    }

    public function testIsThreeDomainSecureEnabled_threeDomainSecureIsEnabled_true()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('get')
            ->with('omise_three_domain_secure_status')
            ->andReturn(true);

        $this->assertTrue($this->setting->isThreeDomainSecureEnabled());
    }

    public function testIsSubmit_userSaveTheSetting_true()
    {
        m::mock('alias:\Tools')
            ->shouldReceive('isSubmit')
            ->andReturn(true);

        $this->assertTrue($this->setting->isSubmit());
    }

    public function testIsSubmit_userOpenTheSettingPage_false()
    {
        m::mock('alias:\Tools')
            ->shouldReceive('isSubmit')
            ->andReturn(false);

        $this->assertFalse($this->setting->isSubmit());
    }

    public function testSave_saveTheSetting_saveAllSetting()
    {
        m::mock('alias:\Tools')
            ->shouldReceive('getValue')->with('module_status')->andReturn('moduleStatus')
            ->shouldReceive('getValue')->with('sandbox_status')->andReturn('sandboxStatus')
            ->shouldReceive('getValue')->with('test_public_key')->andReturn('testPublicKey')
            ->shouldReceive('getValue')->with('test_secret_key')->andReturn('testSecretKey')
            ->shouldReceive('getValue')->with('live_public_key')->andReturn('livePublicKey')
            ->shouldReceive('getValue')->with('live_secret_key')->andReturn('liveSecretKey')
            ->shouldReceive('getValue')->with('title')->andReturn('title')
            ->shouldReceive('getValue')->with('three_domain_secure_status')->andReturn('threeDomainSecureStatus')
            ->shouldReceive('getValue')->with('internet_banking_status')->andReturn('internetBankingStatus');

        m::mock('alias:\Configuration')
            ->shouldReceive('updateValue')->with('omise_module_status', 'moduleStatus')->once()
            ->shouldReceive('updateValue')->with('omise_sandbox_status', 'sandboxStatus')->once()
            ->shouldReceive('updateValue')->with('omise_test_public_key', 'testPublicKey')->once()
            ->shouldReceive('updateValue')->with('omise_test_secret_key', 'testSecretKey')->once()
            ->shouldReceive('updateValue')->with('omise_live_public_key', 'livePublicKey')->once()
            ->shouldReceive('updateValue')->with('omise_live_secret_key', 'liveSecretKey')->once()
            ->shouldReceive('updateValue')->with('omise_title', 'title')->once()
            ->shouldReceive('updateValue')->with('omise_three_domain_secure_status', 'threeDomainSecureStatus')->once()
            ->shouldReceive('updateValue')->with('omise_internet_banking_status', 'internetBankingStatus')->once();

        $this->setting->save();
    }

    public function testSaveTitle_saveTheTitle_titleHasBeenSaved()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('updateValue')->with('omise_title', 'title')->once();

        $this->setting->saveTitle('title');
    }

    public function testSaveTitle_saveTheTitleIsFail_false()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('updateValue')->andReturn(false);

        $this->assertFalse($this->setting->saveTitle('title'));
    }

    public function testSaveTitle_saveTheTitleIsSuccess_true()
    {
        m::mock('alias:\Configuration')
            ->shouldReceive('updateValue')->andReturn(true);

        $this->assertTrue($this->setting->saveTitle('title'));
    }
}
