<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Artex\Debug\Profiler;
use JG\Benchmark\Benchmark;

final class ProfilerTest extends TestCase
{
    private Profiler $profiler;

    protected function setUp(): void
    {
        $this->profiler = new Profiler();
    }

    public function testStartAndStopBenchmark(): void
    {
        $this->profiler->start('test_benchmark');
        usleep(50000); // Simulate work
        $this->profiler->stop('test_benchmark');

        $benchmarks = $this->profiler->getBenchmarks();

        $this->assertArrayHasKey('test_benchmark', $benchmarks, "Benchmark should be recorded.");
        $this->assertNotEmpty($benchmarks['test_benchmark']['time'], "Execution time should be recorded.");
        $this->assertNotEmpty($benchmarks['test_benchmark']['memory'], "Memory usage should be recorded.");
    }

    public function testGetBenchmarks(): void
    {
        $this->profiler->start('bench1');
        usleep(10000);
        $this->profiler->stop('bench1');

        $this->profiler->start('bench2');
        usleep(20000);
        $this->profiler->stop('bench2');

        $benchmarks = $this->profiler->getBenchmarks();

        $this->assertCount(2, $benchmarks, "There should be two recorded benchmarks.");
        $this->assertArrayHasKey('bench1', $benchmarks);
        $this->assertArrayHasKey('bench2', $benchmarks);
    }

    public function testExceptionOnUnstoppedBenchmark(): void
    {
        $this->profiler->start('unstopped');

        $this->expectException(\LogicException::class);
        $this->profiler->getBenchmarks()['unstopped']['time']; // Should throw exception
    }

    public function testResetBenchmarks(): void
    {
        $this->profiler->start('test_reset');
        usleep(5000);
        $this->profiler->stop('test_reset');

        $this->profiler->resetBenchmarks();
        $benchmarks = $this->profiler->getBenchmarks();

        $this->assertEmpty($benchmarks, "Benchmarks should be reset.");
    }
}