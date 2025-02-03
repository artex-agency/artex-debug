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

use Throwable;
use ErrorException;

/**
 * ErrorHandler - Handles PHP errors and converts them into exceptions 
 * if necessary.
 *
 * This class captures all PHP errors and either logs them or escalates 
 * them as exceptions based on the severity and configuration.
 *
 * @version    1.0.0
 * @author     James Gober <me@jamesgober.com>
 */
class ErrorHandler
{
    private static array $fatalCallbacks = [];

    /**
     * Registers the error and exception handlers.
     *
     * @return void
     */
    public static function register(): void
    {
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    /**
     * Registers a callback to be executed before a fatal error or uncaught exception.
     *
     * @param callable $callback The callback function.
     * @return void
     */
    public static function onFatal(callable $callback): void
    {
        self::$fatalCallbacks[] = $callback;
    }

    /**
     * Executes registered fatal error callbacks.
     *
     * @return void
     */
    private static function triggerFatalCallbacks(): void
    {
        foreach (self::$fatalCallbacks as $callback) {
            $callback();
        }
    }

    /**
     * Handles PHP errors and converts them into exceptions if necessary.
     *
     * @param int    $severity The severity level of the error.
     * @param string $message  The error message.
     * @param string $file     The file in which the error occurred.
     * @param int    $line     The line number where the error occurred.
     * @return void
     * @throws ErrorException If the error is considered fatal.
     */
    public static function handleError(int $severity, string $message, string $file, int $line): void
    {
        if (!(error_reporting() & $severity)) {
            // Error is suppressed by @ operator
            return;
        }

        $logMessage = "[Error] Severity: $severity | Message: $message | File: $file | Line: $line";
        
        if (class_exists(Debug::class)) {
            Debug::log('error', $logMessage);
        } else {
            error_log($logMessage);
        }

        if (self::isFatal($severity)) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        }
    }

    /**
     * Handles uncaught exceptions.
     *
     * @param Throwable $exception The uncaught exception.
     * @return void
     */
    public static function handleException(Throwable $exception): void
    {
        self::triggerFatalCallbacks(); // 🔥 Execute fatal callbacks BEFORE logging the exception

        $logMessage = "[Exception] " . get_class($exception) . ": " . $exception->getMessage()
            . " in " . $exception->getFile() . " on line " . $exception->getLine();

        if (class_exists(Debug::class)) {
            Debug::log('critical', $logMessage);
        } else {
            error_log($logMessage);
        }
    }

    /**
     * Handles fatal errors on script shutdown.
     *
     * @return void
     */
    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null && self::isFatal($error['type'])) {
            self::triggerFatalCallbacks(); // 🔥 Execute fatal callbacks BEFORE handling error

            $logMessage = "[Fatal Error] Message: {$error['message']} | File: {$error['file']} | Line: {$error['line']}";

            if (class_exists(Debug::class)) {
                Debug::log('critical', $logMessage);
            } else {
                error_log($logMessage);
            }
        }
    }

    /**
     * Determines if an error is fatal.
     *
     * @param int $severity The error severity level.
     * @return bool True if the error is fatal, false otherwise.
     */
    private static function isFatal(int $severity): bool
    {
        return in_array($severity, [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true);
    }
}