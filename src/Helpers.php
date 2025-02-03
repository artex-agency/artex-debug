<?php declare(strict_types=1);
/**
 * Artex Debug
 * 
 * @package    Artex\Debug
 * @link       https://github.com/artex-agency/artex-debug
 * @link       https://artexsoftware.com
 * @license    Apache License 2.0
 * @copyright  2025 Artex Agency Inc.
 */
namespace Artex\Debug;

/**
 * Helpers - Utility functions for Artex Debug.
 *
 * Provides simple helper methods for debugging and formatting.
 *
 * @package    Artex\Debug
 * @version    1.0.0
 * @author     James Gober <me@jamesgober.com>
 * @license    Apache License 2.0
 */
class Helpers
{
    /**
     * Converts a variable to a readable JSON string.
     *
     * @param mixed $data Data to convert.
     * @return string JSON encoded string.
     */
    public static function toJson(mixed $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Formats bytes into a human-readable format.
     *
     * @param int $bytes Number of bytes.
     * @param int $precision Decimal places.
     * @return string Formatted size string.
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen((string) $bytes) - 1) / 3);
        return sprintf("%.{$precision}f %s", $bytes / (1024 ** $factor), $units[$factor]);
    }

    /**
     * Dumps a variable for debugging and optionally exits.
     *
     * @param mixed $var Variable to dump.
     * @param bool $exit Whether to stop execution.
     * @return void
     */
    public static function dump(mixed $var, bool $exit = false): void
    {
        echo '<pre>' . self::toJson($var) . '</pre>';
        if ($exit) {
            exit;
        }
    }

    /**
     * Checks if the current execution environment is CLI.
     *
     * @return bool True if running in CLI mode.
     */
    public static function isCli(): bool
    {
        return PHP_SAPI === 'cli' || defined('STDIN');
    }
}