<?php
class OmiseEventHandlerTest extends PHPUnit\Framework\TestCase
{
    private $omise_event_handler;

    public function setup()
    {
        $this->omise_event_handler = new OmiseEventHandler();
    }

    public function testHandle_eventKeyHasNotBeHandled_false()
    {
        $event = array(
            'key' => 'unhandledKey',
        );

        $this->assertFalse($this->omise_event_handler->handle($event));
    }
}
