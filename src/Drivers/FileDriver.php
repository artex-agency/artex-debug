<?php declare(strict_types=1);

/**
 * Artex Debug - FileDriver
 *
 * Stores debug logs in a file.
 *
 * @package    Artex\Debug
 * @version    1.0.0
 * @author     James Gober <me@jamesgober.com>
 * @license    Apache License 2.0
 * @link       https://github.com/artex-agency/artex-debug
 */

namespace Artex\Debug\Drivers;

use Artex\Debug\Config;

/**
 * FileDriver - Stores debug logs in a file.
 */
class FileDriver
{
    private string $logPath;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->logPath = Config::get('log_path', '/tmp/debug.log');
    }

    /**
     * Writes a log entry to the debug file.
     *
     * @param array $logEntry The log entry to write.
     * @return void
     */
    public function writeLog(array $logEntry): void
    {
        $logLine = json_encode($logEntry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;
        file_put_contents($this->logPath, $logLine, FILE_APPEND | LOCK_EX);
    }
}