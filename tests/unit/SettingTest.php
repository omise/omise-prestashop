<?php
if (!defined('_PS_VERSION_')) {
    define('_PS_VERSION_', 'TEST_VERSION');
}

class SettingTest extends PHPUnit_Framework_TestCase
{
    private $form_input;
    private $setting;

    public function setup()
    {
        $this->getMockBuilder(stdClass::class)
            ->setMockClassName('PaymentModule')
            ->setMethods(array('l'))
            ->getMock();

        $this->getMockBuilder(stdClass::class)
            ->setMockClassName('HelperForm')
            ->setMethods(array('generateForm'))
            ->getMock();

        $this->setting = new Setting();

        $fields = $this->setting->getFields();
        $this->form_input = $fields[0]['form']['input'];
    }

    public function testSubmitAction_omiseSaveSetting()
    {
        $this->assertEquals('omise_save_setting', $this->setting->getSubmitAction());
    }

    public function testGetFields_formMustHasInputForModuleStatus()
    {
        $input_module_status = $this->form_input['module_status'];

        $this->assertEquals('module_status', $input_module_status['name']);
    }

    public function testGetFields_formMustHasInputForSandboxStatus()
    {
        $input_sanbox_status = $this->form_input['sandbox_status'];

        $this->assertEquals('sandbox_status', $input_sanbox_status['name']);
    }

    public function testGetFields_formMustHasInputForTestPublicKey()
    {
        $input_test_public_key = $this->form_input['test_public_key'];

        $this->assertEquals('test_public_key', $input_test_public_key['name']);
    }

    public function testGetFields_formMustHasInputForTestSecretKey()
    {
        $input_test_secret_key = $this->form_input['test_secret_key'];

        $this->assertEquals('test_secret_key', $input_test_secret_key['name']);
    }

    public function testGetFields_formMustHasInputForLivePublicKey()
    {
        $input_live_public_key = $this->form_input['live_public_key'];

        $this->assertEquals('live_public_key', $input_live_public_key['name']);
    }

    public function testGetFields_formMustHasInputForLiveSecretKey()
    {
        $input_live_secret_key = $this->form_input['live_secret_key'];

        $this->assertEquals('live_secret_key', $input_live_secret_key['name']);
    }

    public function testGetFields_formMustHasInputForTitle()
    {
        $input_title = $this->form_input['title'];

        $this->assertEquals('title', $input_title['name']);
    }
}
