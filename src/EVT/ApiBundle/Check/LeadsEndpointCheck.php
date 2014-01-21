<?php

namespace EVT\ApiBundle\Check;

use Liip\Monitor\Check\Check;
use Liip\Monitor\Exception\CheckFailedException;
use Liip\Monitor\Result\CheckResult;
use Monolog\Logger;

class LeadsEndpointCheck extends Check
{
    protected $logPath;
    protected $result;

    public function __construct($path)
    {
        $this->logPath = $path;
        $this->result = $this->buildResult('OK', CheckResult::OK);
    }

    public function check()
    {
        $fh = fopen($this->logPath, 'r');
        $warnings = 0;
        $errors = 0;
        $alerts = 0;
        $crits = 0;
        $emergencies = 0;

        try {
            while ($line = fgets($fh)) {
                $log = json_decode($line, true);
                switch ($log['level']) {
                    case Logger::WARNING:
                        $warnings++;
                        break;
                    case Logger::ERROR:
                        $errors++;
                        break;
                    case Logger::ALERT:
                        $alerts++;
                        break;
                    case Logger::CRITICAL:
                        $crits++;
                        break;
                    case Logger::EMERGENCY:
                        $emergencies++;
                        break;
                }
                $this->checkThreshold($log['msg'], $warnings, $errors, $alerts, $crits, $emergencies);
            }
            return $this->result;
        } catch (\Exception $e) {
        }
        fclose($fh);
        return $this->result;
    }

    public function getName()
    {
        return 'EVT Leads monitor';
    }

    protected function checkThreshold($msg, $warnings, $errors, $alerts, $crits, $emergencies)
    {
        if ($emergencies >= 1) {
            $this->result = $this->buildResult(sprintf('EMERGENCY: %s', $msg), CheckResult::CRITICAL);
            $this->rotateLog('EMERGENCY');
            throw new \RuntimeException($msg);
        }
        if ($crits >= 1) {
            $this->result = $this->buildResult(sprintf('CRITICAL: %s', $msg), CheckResult::CRITICAL);
            throw new \RuntimeException($msg);
        }
        if ($alerts >= 1) {
            $this->result = $this->buildResult(sprintf('ALERT: %s', $msg), CheckResult::CRITICAL);
            throw new \RuntimeException($msg);
        }
        if ($errors >= 2) {
            $this->result = $this->buildResult(sprintf('ERROR: %s', $msg), CheckResult::CRITICAL);
            throw new \RuntimeException($msg);
        }
        if ($warnings >= 5) {
            $this->result = $this->buildResult(sprintf('WARNING: %s', $msg), CheckResult::WARNING);
            throw new \RuntimeException($msg);
        }
    }

    protected function rotateLog($level)
    {
        $level = strtolower($level);
        $name = basename($this->logPath);
        $dir = dirname($this->logPath);
        $prev = 1;

        foreach (glob($dir . '/' . $level . '.*.' . $name) as $name) {
            $prev++;
        }

        rename($this->logPath, "$dir/$level.$prev.$name");
    }
}
