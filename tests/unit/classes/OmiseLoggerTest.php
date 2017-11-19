<?php
use \Mockery as m;

class OmiseLoggerTest extends Mockery\Adapter\Phpunit\MockeryTestCase
{
    private $omise_logger;

    public function setup()
    {
        $this->omise_logger = new OmiseLogger();
    }

    public function testAdd_addLogWithoutSeverityParameter_logMustBeAddedWithDefaultSeverity()
    {
        $message = 'message';

        m::mock('alias:\Logger')
            ->shouldReceive('addLog')
            ->with(
                $message,
                OmiseLogger::INFO,
                null,
                null,
                null,
                true
            );

        $this->omise_logger->add($message);
    }

    public function testAdd_addLogWithSeverityParameter_logMustBeAddedWithSpecifiedSeverityParameter()
    {
        $message = 'message';

        m::mock('alias:\Logger')
            ->shouldReceive('addLog')
            ->with(
                $message,
                OmiseLogger::ERROR,
                null,
                null,
                null,
                true
            );

        $this->omise_logger->add($message, OmiseLogger::ERROR);
    }
}
