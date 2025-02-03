<?php declare(strict_types=1);
# ¸_____¸____¸________¸___¸_¸  ¸__   
# |     |  __ \_¸  ¸_/  __|  \/  /   
# |  |  |     / |  | |  __|}    {    
# |__A__|__\__\ |__| |____|__/\__\
# ARTEX SOFTWARE ⦙⦙⦙⦙⦙⦙ PHP DEBUG UTIL
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

use Artex\Debug\ErrorHandler;
use Artex\Debug\DataCollector;
use Artex\Debug\DebugBar;
use Artex\Debug\Helpers;
use Artex\Debug\CLIOutput;
use Artex\Debug\Drivers\LogDriver;
use Artex\Debug\Drivers\FileDriver;
use Artex\Debug\Drivers\MemoryDriver;
use JG\Benchmark\Benchmark;

/**
 * Debug - Central Debugging Utility
 *
 * Handles logging, benchmarking, and debug data collection.
 *
 * @version    1.0.0
 * @author     James Gober <me@jamesgober.com>
 */
class Debug
{
    private static ?Debug $instance = null;
    private Config $config;
    private DataCollector $collector;
    private DebugBar $debugBar;
    private CLIOutput $cliOutput;
    private Benchmark $benchmark;

    private function __construct()
    {
        $this->config    = new Config();
        $this->collector = new DataCollector();
        $this->debugBar  = new DebugBar();
        $this->cliOutput = new CLIOutput();
        $this->benchmark = new Benchmark([
            'track_peak_memory' => true
        ]);

        // Register error handler
        ErrorHandler::register();
    }

    /**
     * Gets the singleton instance of Debug.
     *
     * @return Debug
     */
    public static function getInstance(): Debug
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Logs a message at a specified severity level.
     *
     * This method logs messages using a defined log level, ensuring that only valid PSR-3 log levels 
     * are accepted. If an invalid log level is provided, an exception is thrown.
     * 
     * Available log levels: debug, info, notice, warning, error, critical, alert, emergency.
     * 
     * @param string $level   The log severity level (e.g., "error", "info").
     * @param string $message The log message to store.
     * @param array  $context Additional context data for debugging (optional).
     * 
     * @throws \InvalidArgumentException If an invalid log level is provided.
     * 
     * @return void
     */
    public function log(string $level, string $message, array $context = []): void
    {
        $validLevels = [
            'debug', 'info', 'notice', 'warning',
            'error', 'critical', 'alert', 'emergency'
        ];
    
        if (!in_array(strtolower($level), $validLevels, true)) {
            throw new \InvalidArgumentException("Invalid log level: {$level}");
        }
    
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level'     => strtoupper($level),
            'message'   => $message,
            'context'   => $context
        ];
    
        // Store logs in DataCollector
        $this->collector->addLog($level, $message, $context);
    
        // Write logs to the correct Logger (Driver)
        $driverType = Config::get('log_driver', 'file');
    
        $driver = match ($driverType) {
            'file'   => new FileDriver(),
            'log'    => new LogDriver(),
            'memory' => new MemoryDriver(),
            default  => new FileDriver(),
        };
    
        $driver->writeLog($logEntry);
    }

    /**
     * Renders the debug output.
     *
     * @return void
     */
    public function render(): void
    {
        if (!Helpers::isCli() && Config::get('enable_debug_bar', true)) { 
            $this->debugBar->render($this->collector);
        }       
    }

    /**
     * Start a new benchmark.
     *
     * @param string $name Unique name for the benchmark.
     * @return void
     */
    public function startBenchmark(string $name): void
    {
        $this->benchmark->start($name);
    }

    /**
     * Stop a benchmark.
     *
     * @param string $name Name of the benchmark.
     * @return void
     */
    public function stopBenchmark(string $name): void
    {
        $this->benchmark->stop($name);
    }

    /**
     * Get the elapsed time of a benchmark.
     *
     * @param string $name Name of the benchmark.
     * @return string Elapsed time.
     */
    public function getBenchmarkTime(string $name): string
    {
        return $this->benchmark->getTime($name);
    }

    /**
     * Get memory usage of a benchmark.
     *
     * @param string $name Name of the benchmark.
     * @return string Memory usage.
     */
    public function getBenchmarkMemory(string $name): string
    {
        return $this->benchmark->getMemory($name);
    }

    /**
     * Get peak memory usage of a benchmark.
     *
     * @param string $name Name of the benchmark.
     * @return string Peak memory usage.
     */
    public function getBenchmarkPeakMemory(string $name): string
    {
        return $this->benchmark->getPeakMemory($name);
    }

    /**
     * Get the DataCollector instance.
     *
     * @return DataCollector
     */
    public function getCollector(): DataCollector
    {
        return $this->collector;
    }

}