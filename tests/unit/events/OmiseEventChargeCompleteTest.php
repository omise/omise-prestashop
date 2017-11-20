<?php
use \Mockery as m;

class OmiseEventChargeCompleteTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    private $omise_base_event;
    private $omise_event_charge_complete;

    public function setup()
    {
        $this->omise_base_event = $this->getMockBuilder(get_class(new stdClass()))
            ->setMockClassName('OmiseBaseEvent')
            ->getMock();

        $omise_logger = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'add',
                )
            )
            ->getMock();

        $omise_transaction_model = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'getIdOrder',
                )
            )
            ->getMock();

        $payment_order = $this->getMockBuilder(get_class(new stdClass()))
            ->setMethods(
                array(
                    'updateStateToBeCanceled',
                    'updateStateToBeSuccess',
                )
            )
            ->getMock();

        m::mock('overload:\Order');

        $this->omise_event_charge_complete = new OmiseEventChargeComplete();
        $this->omise_event_charge_complete->omise_logger = $omise_logger;
        $this->omise_event_charge_complete->omise_transaction_model = $omise_transaction_model;
        $this->omise_event_charge_complete->payment_order = $payment_order;
    }

    public function testHandle_omiseChargeStatusIsFailed_updatePrestaShopOrderStatusToBeCanceled()
    {
        $event = $this->getEventFailedCharge();
        m::mock('alias:\Validate')
            ->shouldReceive('isLoadedObject')
            ->andReturn(true);

        $this->omise_event_charge_complete->payment_order
            ->expects($this->once())
            ->method('updateStateToBeCanceled');

        $this->omise_event_charge_complete->handle($event);
    }

    public function testHandle_omiseChargeStatusIsSuccess_updatePrestaShopOrderStatusToBeSuccess()
    {
        $event = $this->getEventSuccessfulCharge();
        m::mock('alias:\Validate')
            ->shouldReceive('isLoadedObject')
            ->andReturn(true);

        $this->omise_event_charge_complete->payment_order
            ->expects($this->once())
            ->method('updateStateToBeSuccess');

        $this->omise_event_charge_complete->handle($event);
    }

    public function testHandle_prestaShopOrderIsNotFound_false()
    {
        $event = $this->getEventSuccessfulCharge();
        m::mock('alias:\Validate')
            ->shouldReceive('isLoadedObject')
            ->andReturn(false);

        $this->assertFalse($this->omise_event_charge_complete->handle($event));
    }

    private function getEventFailedCharge()
    {
        $event = array(
            'object' => 'event',
            'id' => 'eventId',
            'data' => array(
                'object' => 'charge',
                'id' => 'chargeId',
                'status' => OmiseChargeClass::STATUS_FAILED,
            ),
        );

        return $event;
    }

    private function getEventSuccessfulCharge()
    {
        $event = array(
            'object' => 'event',
            'id' => 'eventId',
            'data' => array(
                'object' => 'charge',
                'id' => 'chargeId',
                'status' => OmiseChargeClass::STATUS_SUCCESSFUL,
            ),
        );

        return $event;
    }
}
