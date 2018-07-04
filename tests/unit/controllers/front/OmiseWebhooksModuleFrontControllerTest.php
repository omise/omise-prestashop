<?php
class OmiseWebhooksModuleFrontControllerTest extends PHPUnit\Framework\TestCase
{
    private $omise_event;
    private $omise_webhooks;
    private $omise_webhooks_module_front_controller;

    public function setup()
    {
        $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('ModuleFrontController')
            ->setMethods(
                array(
                    '__construct',
                    'setTemplate',
                )
            )
            ->getMock();

        $this->omise_event = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'handle',
                )
            )
            ->getMock();

        $this->omise_webhooks = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('OmiseWebhooks')
            ->setMethods(
                array(
                    'getRequestBody',
                    'sendRawHeaderAsBadRequest',
                )
            )
            ->getMock();

        $this->omise_webhooks_module_front_controller = new OmiseWebhooksModuleFrontController();
        $this->omise_webhooks_module_front_controller->setOmiseEventHandler($this->omise_event);
        $this->omise_webhooks_module_front_controller->setOmiseWebhooks($this->omise_webhooks);
    }

    public function testPostProcess_theRequestBodyHasNoAttributeNamedKey_sendRawHeaderAsBadRequest()
    {
        $this->omise_webhooks
            ->method('getRequestBody')
            ->willReturn(array());

        $this->omise_webhooks
            ->expects($this->once())
            ->method('sendRawHeaderAsBadRequest');

        $this->omise_webhooks_module_front_controller->postProcess();
    }

    public function testPostProcess_theRequestBodyIsNotTheOmiseEventObject_sendRawHeaderAsBadRequest()
    {
        $this->omise_webhooks
            ->method('getRequestBody')
            ->willReturn(array(
                'object' => 'anyObject',
            ));

        $this->omise_webhooks
            ->expects($this->once())
            ->method('sendRawHeaderAsBadRequest');

        $this->omise_webhooks_module_front_controller->postProcess();
    }

    public function testPostProcess_theRequestBodyIsNull_sendRawHeaderAsBadRequest()
    {
        $this->omise_webhooks
            ->method('getRequestBody')
            ->willReturn(null);

        $this->omise_webhooks
            ->expects($this->once())
            ->method('sendRawHeaderAsBadRequest');

        $this->omise_webhooks_module_front_controller->postProcess();
    }

    public function testPostProcess_theRequestBodyIsValid_requestMustBeHandled()
    {
        $this->omise_webhooks
            ->method('getRequestBody')
            ->willReturn(array(
                'object' => 'event',
            ));

        $this->omise_event
            ->expects($this->once())
            ->method('handle');

        $this->omise_webhooks_module_front_controller->postProcess();
    }

    public function testPostProcess_theRequestBodyIsValid_templateMustBeSet()
    {
        $this->omise_webhooks
            ->method('getRequestBody')
            ->willReturn(array(
                'object' => 'event',
            ));

        $this->omise_webhooks_module_front_controller
            ->expects($this->once())
            ->method('setTemplate')
            ->with('module:omise/views/templates/front/webhooks.tpl');

        $this->omise_webhooks_module_front_controller->postProcess();
    }
}
