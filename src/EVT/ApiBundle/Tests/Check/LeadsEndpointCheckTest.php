<?php

namespace EVT\ApiBundle\Tests\Check;

use EVT\ApiBundle\Check\LeadsEndpointCheck;
use Liip\Monitor\Result\CheckResult;
use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;

class LeadsEndpointCheckTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        @unlink('/tmp/check.test.log');
    }

    public function testCheckOk()
    {
        $fh = fopen('/tmp/check.test.log', 'w+');
        $check = new LeadsEndpointCheck('/tmp/check.test.log');
        $this->assertEquals($check->check()->getStatus(), CheckResult::OK);
    }

    public function testCheckWarning()
    {
        $formatter = new JsonFormatter();
        $fh = fopen('/tmp/check.test.log', 'w+');
        $record = array(
            'message' => 'test',
            'context' => [],
            'level' => 300,
            'level_name' => 'WARNING',
            'channel' => 'lead',
            'datetime' => \DateTime::createFromFormat(
                'U.u',
                sprintf('%.6F', microtime(true)),
                new \DateTimeZone('UTC')
            )->setTimezone(new \DateTimeZone('UTC')),
            'extra' => array(),
        );

        fwrite($fh, $formatter->format($record) . PHP_EOL);
        fwrite($fh, $formatter->format($record) . PHP_EOL);
        fwrite($fh, $formatter->format($record) . PHP_EOL);
        fwrite($fh, $formatter->format($record) . PHP_EOL);
        fwrite($fh, $formatter->format($record) . PHP_EOL);
        fwrite($fh, $formatter->format($record) . PHP_EOL);

        $check = new LeadsEndpointCheck('/tmp/check.test.log');
        $this->assertEquals($check->check()->getStatus(), CheckResult::WARNING, 'Expected CheckResult::WARNING');
    }

    public function testCheckEmergency()
    {
        $formatter = new JsonFormatter();
        $fh = fopen('/tmp/check.test.log', 'w+');
        $record = array(
            'message' => 'test',
            'context' => [],
            'level' => 600,
            'level_name' => 'EMERGENCY',
            'channel' => 'lead',
            'datetime' => \DateTime::createFromFormat(
                'U.u',
                sprintf('%.6F', microtime(true)),
                new \DateTimeZone('UTC')
            )->setTimezone(new \DateTimeZone('UTC')),
            'extra' => array(),
        );

        fwrite($fh, $formatter->format($record) . PHP_EOL);

        $check = new LeadsEndpointCheck('/tmp/check.test.log');
        $this->assertEquals($check->check()->getStatus(), CheckResult::CRITICAL, 'Expected CheckResult::EMERGENCY');
        $this->assertFileExists('/tmp/emergency.1.check.test.log');
        unlink('/tmp/emergency.1.check.test.log');
        $this->assertFileNotExists('/tmp/check.test.log');
    }
}
