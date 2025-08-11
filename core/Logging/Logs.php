<?php

namespace Rivulet\Logging;

use DateTime;

class Logs {
    protected $config;
    protected $path;
    protected $level;

    public function __construct($config) {
        $this->config = $config;
        $this->path = $config['channels']['file']['path'];
        $level = $config['channels']['file']['level'];
        $this->level = array_flip(['debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency'])[$level] ?? 0;
    }

    public function log($level, $message) {
        $levels = ['debug' => 0, 'info' => 1, 'notice' => 2, 'warning' => 3, 'error' => 4, 'critical' => 5, 'alert' => 6, 'emergency' => 7];
        if ($levels[$level] < $this->level) {
            return;
        }

        $date = new DateTime();
        $logFile = $this->getLogFile($date);

        $formatted = "[{$date->format('Y-m-d H:i:s')}] {$level}: {$message}\n";

        file_put_contents($logFile, $formatted, FILE_APPEND | LOCK_EX);
    }

    protected function getLogFile(DateTime $date) {
        $dir = dirname($this->path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        // Daily or monthly based on config (assume 'daily' or 'monthly' in logging.period, default daily)
        $period = $this->config['period'] ?? 'daily';
        $suffix = $period === 'monthly' ? $date->format('Y-m') : $date->format('Y-m-d');
        return $this->path . '-' . $suffix . '.log';
    }
}