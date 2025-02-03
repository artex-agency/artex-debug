<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Artex\Debug\Debug;
use JG\Benchmark\Benchmark;

/**
 * DebugTest - Unit tests for the Debug class.
 *
 * @package    Artex\Debug\Tests
 * @author     James Gober
 * @version    1.0.0
 */
class DebugTest extends TestCase
{
    private Debug $debug;

    protected function setUp(): void
    {
        $this->debug = Debug::getInstance();
    }

    public function testSingletonInstance(): void
    {
        $instance1 = Debug::getInstance();
        $instance2 = Debug::getInstance();

        $this->assertSame($instance1, $instance2, "Debug should return the same singleton instance.");
    }

    public function testLogMessage(): void
    {
        $this->expectNotToPerformAssertions(); // We assume log works, but no direct return to test.
        $this->debug->log('info', 'This is a test log message.');
    }

    public function testInvalidLogLevelThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->debug->log('invalid_level', 'This should throw an exception.');
    }

    public function testDebugModeConfig(): void
    {
        $debugMode = $this->debug->getInstance();
        $this->assertInstanceOf(Debug::class, $debugMode);
    }

    public function testBenchmarkFunctionality(): void
    {
        $this->debug->startBenchmark('testBenchmark');
        usleep(50000); // Simulate some execution time
        $this->debug->stopBenchmark('testBenchmark');

        $time = $this->debug->getBenchmarkTime('testBenchmark');
        $memory = $this->debug->getBenchmarkMemory('testBenchmark');
        $peakMemory = $this->debug->getBenchmarkPeakMemory('testBenchmark');

        $this->assertNotEmpty($time, "Benchmark time should not be empty.");
        $this->assertNotEmpty($memory, "Benchmark memory usage should not be empty.");
        $this->assertNotEmpty($peakMemory, "Benchmark peak memory usage should not be empty.");
    }

    public function testRender(): void
    {
        $this->expectNotToPerformAssertions(); // No direct output verification
        $this->debug->render();
    }
}