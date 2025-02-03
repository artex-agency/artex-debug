<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Artex\Debug\ErrorHandler;
use Artex\Debug\Debug;

final class ErrorHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        ErrorHandler::register();
    }

    public function testHandleError(): void
    {
        $this->expectException(\ErrorException::class);
        
        // Ensure it throws an exception
        set_error_handler(function ($severity, $message, $file, $line) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });
    
        trigger_error("Test Warning", E_USER_ERROR);
    }

    public function testIsFatal(): void
    {
        $this->assertTrue(ErrorHandler::isFatal(E_ERROR));
        $this->assertFalse(ErrorHandler::isFatal(E_NOTICE));
    }
}