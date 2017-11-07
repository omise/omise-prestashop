<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (! defined('_PS_VERSION_')) {
    define('_PS_VERSION_', 'TEST_VERSION');
}

class UnitTestHelper extends PHPUnit_Framework_TestCase
{
    public function getMockedCharge()
    {
        $charge = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'getAuthorizeUri',
                    'getErrorMessage',
                    'getId',
                    'isFailed',
                )
            )
            ->getMock();

        $charge->method('getId')->willReturn('omiseChargeId');

        return $charge;
    }

    public function getMockedOmiseBasePaymentModuleFrontController()
    {
        $omise_base_payment_module_front_controller = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('OmiseBasePaymentModuleFrontController')
            ->setMethods(
                array(
                    '__construct',
                    'addOmiseTransaction',
                    'l',
                    'postProcess',
                    'setRedirectAfter',
                    'validateCart',
                )
            )
            ->getMock();

        return $omise_base_payment_module_front_controller;
    }

    public function getMockedPaymentModule()
    {
        $payment_module = $this->getMockBuilder(get_class(new stdClass()))
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

        return $payment_module;
    }

    public function getMockedPaymentOrder()
    {
        $payment_order = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'getOrderStateAcceptedPayment',
                    'getOrderStateProcessingInProgress',
                    'save',
                    'updatePaymentTransactionId',
                    'updateStateToBeCanceled',
                    'updateStateToBeSuccess',
                )
            )
            ->getMock();

        $payment_order->method('getOrderStateAcceptedPayment')->willReturn('orderStateAcceptedPayment');
        $payment_order->method('getOrderStateProcessingInProgress')->willReturn('orderStatusProcessingInProgress');

        return $payment_order;
    }

    public function getMockedSetting()
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
}
