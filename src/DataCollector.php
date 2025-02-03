<?php declare(strict_types=1);
/**
 * Artex Debug - Data Collector
 * 
 * @package    Artex\Debug
 * @link       https://github.com/artex-agency/artex-debug
 * @license    Apache License 2.0
 * @copyright  2025 Artex Agency Inc.
 */
namespace Artex\Debug;

use JG\Benchmark\Benchmark;

/**
 * DataCollector - Stores and manages debug-related information.
 *
 * This class is responsible for collecting logs, execution times, 
 * memory usage, and any additional debugging data.
 *
 * @version    1.0.0
 * @author     James Gober <me@jamesgober.com>
 */
class DataCollector
{
    private array $logs = [];
    private array $errors = [];
    private array $exceptions = [];
    private array $benchmarks = [];
    private Benchmark $benchmark;

    /**
     * Constructor initializes the Benchmark library.
     */
    public function __construct()
    {
        $this->benchmark = new Benchmark([
            'track_peak_memory' => true
        ]);
    }

    /**
     * Stores a log entry.
     *
     * @param string $level   Log severity level.
     * @param string $message Log message.
     * @param array  $context Additional context.
     * @return void
     */
    public function addLog(string $level, string $message, array $context = []): void
    {
        $this->logs[] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level'     => strtoupper($level),
            'message'   => $message,
            'context'   => $context
        ];
    }

    /**
     * Records an error.
     *
     * @param int    $severity Error severity.
     * @param string $message  Error message.
     * @param string $file     File where the error occurred.
     * @param int    $line     Line number.
     * @return void
     */
    public function addError(int $severity, string $message, string $file, int $line): void
    {
        $this->errors[] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'severity'  => $severity,
            'message'   => $message,
            'file'      => $file,
            'line'      => $line
        ];
    }

    /**
     * Records an exception.
     *
     * @param \Throwable $exception The thrown exception.
     * @return void
     */
    public function addException(\Throwable $exception): void
    {
        $this->exceptions[] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'type'      => get_class($exception),
            'message'   => $exception->getMessage(),
            'file'      => $exception->getFile(),
            'line'      => $exception->getLine()
        ];
    }

    /**
     * Starts a benchmark.
     *
     * @param string $name Benchmark name.
     * @return void
     */
    public function startBenchmark(string $name): void
    {
        $this->benchmark->start($name);
    }

    /**
     * Stops a benchmark and records it.
     *
     * @param string $name Benchmark name.
     * @return void
     */
    public function stopBenchmark(string $name): void
    {
        $this->benchmark->stop($name);
        $this->benchmarks[$name] = [
            'time'        => $this->benchmark->getTime($name),
            'memory'      => $this->benchmark->getMemory($name),
            'peak_memory' => $this->benchmark->getPeakMemory($name)
        ];
    }

    /**
     * Retrieves all logged data.
     *
     * @return array
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    /**
     * Retrieves all recorded errors.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Retrieves all recorded exceptions.
     *
     * @return array
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * Retrieves all recorded benchmarks.
     *
     * @return array
     */
    public function getBenchmarks(): array
    {
        return $this->benchmarks;
    }
}