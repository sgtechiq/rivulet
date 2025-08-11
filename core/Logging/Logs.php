<?php
namespace Rivulet\Logging;

use DateTime;

/**
 * Logging Handler
 *
 * Provides file-based logging with:
 * - Multiple log levels (debug to emergency)
 * - Daily/monthly log file rotation
 * - Configurable log level filtering
 */
class Logs
{
    /** @var array Configuration settings */
    protected $config;

    /** @var string Base log file path */
    protected $path;

    /** @var int Minimum log level threshold */
    protected $level;

    /** @var array Log level priority mapping */
    protected $levels = [
        'debug'     => 0,
        'info'      => 1,
        'notice'    => 2,
        'warning'   => 3,
        'error'     => 4,
        'critical'  => 5,
        'alert'     => 6,
        'emergency' => 7,
    ];

    /**
     * Initialize logging handler
     *
     * @param array $config {
     *     @var array  $channels File channel configuration
     *     @var string $period   Log rotation period (daily/monthly)
     * }
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->path   = $config['channels']['file']['path'];
        $this->setLogLevel($config['channels']['file']['level'] ?? 'debug');
    }

    /**
     * Set minimum log level threshold
     *
     * @param string $level Log level name
     */
    protected function setLogLevel(string $level): void
    {
        $this->level = $this->levels[$level] ?? 0;
    }

    /**
     * Write log message if level meets threshold
     *
     * @param string $level   Log level
     * @param string $message Log message
     * @throws \RuntimeException If log file cannot be written
     */
    public function log(string $level, string $message): void
    {
        $levelPriority = $this->levels[$level] ?? 0;
        if ($levelPriority < $this->level) {
            return;
        }

        $date    = new DateTime();
        $logFile = $this->getLogFile($date);

        $formatted = sprintf(
            "[%s] %s: %s\n",
            $date->format('Y-m-d H:i:s'),
            strtoupper($level),
            $message
        );

        if (file_put_contents($logFile, $formatted, FILE_APPEND | LOCK_EX) === false) {
            throw new \RuntimeException("Failed to write to log file: {$logFile}");
        }
    }

    /**
     * Get log file path based on rotation period
     *
     * @param DateTime $date Current date
     * @return string Full log file path
     */
    protected function getLogFile(DateTime $date): string
    {
        $dir = dirname($this->path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $period = $this->config['period'] ?? 'daily';
        $suffix = $period === 'monthly'
        ? $date->format('Y-m')
        : $date->format('Y-m-d');

        return "{$this->path}-{$suffix}.log";
    }

    /**
     * Shortcut methods for each log level
     */
    public function debug(string $message): void
    {$this->log('debug', $message);}
    public function info(string $message): void
    {$this->log('info', $message);}
    public function notice(string $message): void
    {$this->log('notice', $message);}
    public function warning(string $message): void
    {$this->log('warning', $message);}
    public function error(string $message): void
    {$this->log('error', $message);}
    public function critical(string $message): void
    {$this->log('critical', $message);}
    public function alert(string $message): void
    {$this->log('alert', $message);}
    public function emergency(string $message): void
    {$this->log('emergency', $message);}
}
