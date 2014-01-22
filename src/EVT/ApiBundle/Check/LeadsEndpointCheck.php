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
    protected $levels = array('warning' => 0, 'error' => 0, 'alert' => 0, 'critical' => 0, 'emergency' => 0);

    public function __construct($path)
    {
        $this->logPath = $path;
        $this->result = $this->buildResult('OK', CheckResult::OK);
    }

    public function check()
    {
        $fh = @fopen($this->logPath, 'r');
        if (!$fh) {
            $err = $this->getLastError();
            if ($err) {
                return $err;
            }
            return $this->result;
        }

        try {
            while ($line = fgets($fh)) {
                $log = json_decode($line, true);
                switch ($log['level']) {
                    case Logger::WARNING:
                        $this->levels['warning']++;
                        break;
                    case Logger::ERROR:
                        $this->levels['error']++;
                        break;
                    case Logger::ALERT:
                        $this->levels['alert']++;
                        break;
                    case Logger::CRITICAL:
                        $this->levels['critical']++;
                        break;
                    case Logger::EMERGENCY:
                        $this->levels['emergency']++;
                        break;
                }
                $this->checkThreshold($log['message']);
            }
            return ($this->getLastError())?:$this->result;
        } catch (\Exception $e) {
        }
        fclose($fh);
        return $this->result;
    }

    public function getName()
    {
        return 'EVT Leads monitor';
    }

    protected function checkThreshold($msg)
    {
        if ($this->levels['emergency'] >= 1) {
            $this->result = $this->buildResult(sprintf('EMERGENCY: %s', $msg), CheckResult::CRITICAL);
            $this->rotateLog('EMERGENCY');
            throw new \RuntimeException($msg);
        }
        if ($this->levels['critical'] >= 1) {
            $this->result = $this->buildResult(sprintf('CRITICAL: %s', $msg), CheckResult::CRITICAL);
            $this->rotateLog('CRITICAL');
            throw new \RuntimeException($msg);
        }
        if ($this->levels['alert'] >= 1) {
            $this->result = $this->buildResult(sprintf('ALERT: %s', $msg), CheckResult::CRITICAL);
            $this->rotateLog('ALERT');
            throw new \RuntimeException($msg);
        }
        if ($this->levels['error'] >= 2) {
            $this->result = $this->buildResult(sprintf('ERROR: %s', $msg), CheckResult::CRITICAL);
            $this->rotateLog('ERROR');
            throw new \RuntimeException($msg);
        }
        if ($this->levels['warning'] >= 5) {
            $this->result = $this->buildResult(sprintf('WARNING: %s', $msg), CheckResult::WARNING);
            $this->rotateLog('WARNING');
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

    protected function getLastError()
    {
        $info = [];
        $name = basename($this->logPath);
        $dir = dirname($this->logPath);
        foreach ($this->levels as $level => $value) {
            $level = strtolower($level);
            $total = 0;
            $latest = 0;
            foreach (glob($dir . '/' . $level . '.*.' . $name) as $found) {
                $total++;
            }
            if (!$total) {
                continue;
            }
            $file = "$dir/$level.$total.$name";
            if (@filemtime($file) > $latest) {
                $latest         = filemtime($file);
                $lastLevel      = $level;
                $info['level']  = $lastLevel;
                $info['time']   = $latest;
                $info['status'] = CheckResult::WARNING;
            }
        }
        if (empty($info)) {
            return false;
        }
        if (time() - 1800 >= $latest) {
            return false;
        }
        return $this->buildResult(
            sprintf('Last status was %s at %s', $info['level'], date('Y-m-d H:i:s', $info['time'])),
            $info['status']
        );
    }
}
