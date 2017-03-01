<?php
class OmiseThreeDomainSecurePaymentModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $omiseThreeDomainSecurePaymentModuleFrontController;
    private $payment_order;

    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('OmiseBasePaymentModuleFrontController')
            ->setMethods(
                array(
                    'postProcess',
                    'setRedirectAfter',
                )
            )
            ->getMock();

        $this->omiseThreeDomainSecurePaymentModuleFrontController = new OmiseThreeDomainSecurePaymentModuleFrontController();
        $this->omiseThreeDomainSecurePaymentModuleFrontController->charge = $this->getMockedCharge();
        $this->omiseThreeDomainSecurePaymentModuleFrontController->payment_order = $this->getMockedPaymentOrder();
    }

    public function testPostProcess_createThreeDomainSecureCharge_saveAnOrderWithProcessingInProgressStatus()
    {
        $this->payment_order->expects($this->once())
            ->method('saveAsProcessing');

        $this->omiseThreeDomainSecurePaymentModuleFrontController->postProcess();
    }

    private function getMockedCharge()
    {
        $charge = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'getAuthorizeUri',
                )
            )
            ->getMock();

        return $charge;
    }

    private function getMockedPaymentOrder()
    {
        $this->payment_order = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'saveAsProcessing',
                )
            )
            ->getMock();

        return $this->payment_order;
    }
}
