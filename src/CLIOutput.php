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
 * CLIOutput - Handles colored and formatted debug output in CLI mode.
 *
 * Provides a consistent and readable way to display debug messages, 
 * warnings, and errors in the command line.
 *
 * @version    1.0.0
 * @author     James Gober <me@jamesgober.com>
 */
class CLIOutput
{
    /**
     * Outputs a formatted message to the CLI.
     *
     * @param string $message The message to display.
     * @param string $type The type of message (info, success, warning, error).
     * @return void
     */
    public static function write(string $message, string $type = 'info'): void
    {
        if (php_sapi_name() !== 'cli' || !Config::get('cli_output', true)) {
            return;
        }

        $colors = [
            'info'    => "\033[36m", // Cyan
            'success' => "\033[32m", // Green
            'warning' => "\033[33m", // Yellow
            'error'   => "\033[31m", // Red
        ];

        $colorCode = $colors[$type] ?? "\033[0m";
        echo "{$colorCode}{$message}\033[0m\n";
    }

    /**
     * Outputs an error message.
     *
     * @param string $message The error message.
     * @return void
     */
    public static function error(string $message): void
    {
        self::write("[ERROR] {$message}", 'error');
    }

    /**
     * Outputs a warning message.
     *
     * @param string $message The warning message.
     * @return void
     */
    public static function warning(string $message): void
    {
        self::write("[WARNING] {$message}", 'warning');
    }

    /**
     * Outputs an info message.
     *
     * @param string $message The info message.
     * @return void
     */
    public static function info(string $message): void
    {
        self::write("[INFO] {$message}", 'info');
    }

    /**
     * Outputs a success message.
     *
     * @param string $message The success message.
     * @return void
     */
    public static function success(string $message): void
    {
        self::write("[SUCCESS] {$message}", 'success');
    }
}