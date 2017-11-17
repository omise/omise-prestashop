<?php
class OmiseWebhooksModuleFrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $omise_webhooks_module_front_controller;

    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('ModuleFrontController')
            ->setMethods(
                array(
                    'setTemplate',
                )
            )
            ->getMock();

        $this->omise_webhooks_module_front_controller = new OmiseWebhooksModuleFrontController();
    }

    public function testPostProcess_postProcess_displayOmiseWebhooksPage()
    {
        $this->omise_webhooks_module_front_controller
            ->expects($this->once())
            ->method('setTemplate')
            ->with('module:omise/views/templates/front/webhooks.tpl');

        $this->omise_webhooks_module_front_controller->postProcess();
    }
}
