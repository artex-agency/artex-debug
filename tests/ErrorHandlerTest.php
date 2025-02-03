<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Artex\Debug\ErrorHandler;
use Artex\Debug\Debug;
use Throwable;

final class ErrorHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        ErrorHandler::register();
    }

    public function testHandleError(): void
    {
        $this->expectException(ErrorException::class);
        trigger_error("Test Warning", E_USER_WARNING);
    }

    public function testHandleException(): void
    {
        $exception = new Exception("Test Exception");
        ob_start();
        ErrorHandler::handleException($exception);
        $output = ob_get_clean();

        $this->assertStringContainsString("Test Exception", $output);
        $this->assertStringContainsString("Exception", $output);
    }

    public function testHandleShutdown(): void
    {
        // Simulate a fatal error
        $error = [
            'type'    => E_ERROR,
            'message' => 'Simulated Fatal Error',
            'file'    => 'test.php',
            'line'    => 123
        ];

        // Inject the error manually
        set_error_handler(function () use ($error) {
            error_clear_last();
            trigger_error($error['message'], $error['type']);
        });

        ob_start();
        ErrorHandler::handleShutdown();
        $output = ob_get_clean();

        $this->assertStringContainsString("Fatal Error", $output);
        $this->assertStringContainsString("Simulated Fatal Error", $output);
    }

    public function testIsFatal(): void
    {
        $this->assertTrue(ErrorHandler::isFatal(E_ERROR));
        $this->assertFalse(ErrorHandler::isFatal(E_NOTICE));
    }
}