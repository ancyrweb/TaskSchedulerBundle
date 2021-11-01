<?php

namespace Rewieer\TaskSchedulerBundle\Tests\Services;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Rewieer\TaskSchedulerBundle\Services\SchedulerLogger;

class SchedulerLoggerTest extends TestCase
{
    public function testLoggingWorks(): void
    {
        $testHandler = new TestHandler();

        $logger = new Logger('', [$testHandler]);

        $schedulerLogger = new SchedulerLogger($logger);
        $schedulerLogger->onStart();

        $this->assertTrue($testHandler->hasInfoRecords());
        $this->assertFalse($testHandler->hasAlertRecords());
        $this->assertFalse($testHandler->hasCriticalRecords());
    }
}
