<?php declare(strict_types=1);

/**
 * Artex Debug - LogDriver
 *
 * Integrates debug logging with the Artex Logger.
 *
 * @package    Artex\Debug
 * @version    1.0.0
 * @author     James Gober <me@jamesgober.com>
 * @license    Apache License 2.0
 * @link       https://github.com/artex-agency/artex-debug
 */

namespace Artex\Debug\Drivers;

use Artex\Logger\Logger;
use Artex\Debug\Config;

/**
 * LogDriver - Forwards debug logs to the Artex Logger.
 */
class LogDriver
{
    private Logger $logger;

    /**
     * Constructor initializes the logger.
     */
    public function __construct()
    {
        $this->logger = new Logger();
    }

    /**
     * Writes a log entry using the Artex Logger.
     *
     * @param array $logEntry The log entry to write.
     * @return void
     */
    public function writeLog(array $logEntry): void
    {
        $level = strtolower($logEntry['level'] ?? 'debug');
        $message = $logEntry['message'] ?? 'Undefined log entry';
        $context = $logEntry['context'] ?? [];

        $this->logger->log($level, $message, $context);
    }
}