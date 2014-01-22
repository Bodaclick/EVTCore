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
        @unlink('/tmp/warning.1.check.test.log');
        @unlink('/tmp/emergency.1.check.test.log');
    }

    public function testCheckOk()
    {
        $fh = fopen('/tmp/check.test.log', 'w+');
        fclose($fh);
        $check = new LeadsEndpointCheck('/tmp/check.test.log');
        $this->assertEquals(CheckResult::OK, $check->check()->getStatus(), 'Expected OK');
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
        fclose($fh);

        $check = new LeadsEndpointCheck('/tmp/check.test.log');
        $this->assertEquals(CheckResult::WARNING, $check->check()->getStatus(), 'Expected CheckResult::WARNING');
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
        fclose($fh);

        $check = new LeadsEndpointCheck('/tmp/check.test.log');
        $this->assertEquals(CheckResult::CRITICAL, $check->check()->getStatus(), 'Expected CheckResult::EMERGENCY');
        $this->assertFileExists('/tmp/emergency.1.check.test.log');
        unlink('/tmp/emergency.1.check.test.log');
        $this->assertFileNotExists('/tmp/check.test.log');
    }

    public function testShowLastFailure()
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
        fclose($fh);

        $check = new LeadsEndpointCheck('/tmp/check.test.log');
        $check->check();
        $rto = $check->check();
        unlink('/tmp/emergency.1.check.test.log');
        $this->assertEquals(CheckResult::WARNING, $rto->getStatus(), 'Expected CheckResult::WARNING');
    }
}
