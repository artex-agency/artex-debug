<?php declare(strict_types=1);

/**
 * Artex Debug - MemoryDriver
 *
 * Stores debug logs temporarily in memory.
 *
 * @package    Artex\Debug
 * @version    1.0.0
 * @author     James Gober <me@jamesgober.com>
 * @license    Apache License 2.0
 * @link       https://github.com/artex-agency/artex-debug
 */

namespace Artex\Debug\Drivers;

/**
 * MemoryDriver - Stores log entries in memory for fast retrieval.
 */
class MemoryDriver
{
    /**
     * Holds log entries in memory.
     *
     * @var array<int, array<string, mixed>>
     */
    private static array $logs = [];

    /**
     * Writes a log entry to memory.
     *
     * @param array $logEntry The log entry to store.
     * @return void
     */
    public function writeLog(array $logEntry): void
    {
        self::$logs[] = $logEntry;
    }

    /**
     * Retrieves all stored log entries.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getLogs(): array
    {
        return self::$logs;
    }

    /**
     * Clears all stored log entries.
     *
     * @return void
     */
    public function clear(): void
    {
        self::$logs = [];
    }
}