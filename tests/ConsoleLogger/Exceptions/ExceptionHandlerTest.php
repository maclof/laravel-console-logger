<?php

namespace Illuminated\Console\ConsoleLogger\Tests\Exceptions;

use Exception;
use Illuminated\Console\ConsoleLogger\Tests\TestCase;
use Illuminated\Console\Exceptions\ExceptionHandler;
use Illuminated\Console\Exceptions\RuntimeException;
use Mockery;
use Psr\Log\LoggerInterface;

class ExceptionHandlerTest extends TestCase
{
    /** @test */
    public function it_logs_an_error_for_all_occurred_application_notices_warnings_errors_and_exceptions()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('error')->with('Test exception', Mockery::subset([
            'code' => 111,
            'message' => 'Test exception',
            'file' => __FILE__,
        ]))->once();

        $handler = app(ExceptionHandler::class);
        $handler->setLogger($logger);
        $handler->report(new Exception('Test exception', 111));
    }

    /** @test */
    public function it_supports_custom_runtime_exception_which_has_ability_to_set_optional_context()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('error')->with('Test exception with context', Mockery::subset([
            'code' => 111,
            'message' => 'Test exception with context',
            'file' => __FILE__,
            'context' => [
                'foo' => 'bar',
                'baz' => 123,
                'faz' => true,
                'daz' => null,
            ],
        ]))->once();

        $handler = app(ExceptionHandler::class);
        $handler->setLogger($logger);
        $handler->report(new RuntimeException('Test exception with context', [
            'foo' => 'bar',
            'baz' => 123,
            'faz' => true,
            'daz' => null,
        ], 111));
    }
}
