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
 * Config - Configuration handler for Artex Debug.
 *
 * Manages debug settings such as logging drivers, output format, and 
 * error handling mode.
 *
 * @version    1.0.0
 * @author     James Gober <me@jamesgober.com>
 */
class Config
{
    private static array $settings = [
        'debug_mode'    => true,  // Enable/Disable debug mode
        'log_driver'    => 'file', // Options: file, memory, log
        'log_path'      => '/tmp/debug.log', // Path for file logging
        'cli_output'    => true,  // Show debug output in CLI
        'error_capture' => true,  // Capture PHP errors into Debug
        'exception_capture' => true, // Capture uncaught exceptions into Debug
        'benchmarking'  => true,  // Enable execution time tracking
    ];

    /**
     * Get a config value.
     *
     * @param string $key     Config key to retrieve.
     * @param mixed  $default Default value if key is not set.
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$settings[$key] ?? $default;
    }

    /**
     * Set a config value.
     *
     * @param string $key   Config key to set.
     * @param mixed  $value Value to set.
     * @return void
     */
    public static function set(string $key, mixed $value): void
    {
        self::$settings[$key] = $value;
    }
}
