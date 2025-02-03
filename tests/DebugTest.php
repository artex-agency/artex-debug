<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Artex\Debug\Debug;
use Artex\Debug\DataCollector;
use Artex\Debug\Config;

final class DebugTest extends TestCase
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

        $this->assertSame($instance1, $instance2, "Debug must be a singleton.");
    }

    public function testLogMessage(): void
    {
        $this->debug->log('info', 'Test log message', ['key' => 'value']);

        $collector = new DataCollector();
        $logs = $collector->getLogs();

        $this->assertNotEmpty($logs, "Log should be stored.");
        $this->assertSame('INFO', $logs[0]['level'], "Log level should match.");
        $this->assertSame('Test log message', $logs[0]['message'], "Log message should match.");
    }

    public function testInvalidLogLevelThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->debug->log('invalid_level', 'This should fail');
    }

    public function testDebugModeConfig(): void
    {
        Config::set('debug_mode', false);
        $this->assertFalse(Config::get('debug_mode'), "Debug mode should be disabled.");
    }

    public function testRender(): void
    {
        ob_start();
        $this->debug->render();
        $output = ob_get_clean();

        $this->assertNotEmpty($output, "Debug render output should not be empty.");
    }
}