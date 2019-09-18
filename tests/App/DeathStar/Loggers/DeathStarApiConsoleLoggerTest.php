<?php

namespace Tests\App\DeathStar\Loggers;

use App\DeathStar\Loggers\DeathStarApiConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

class DeathStarApiConsoleLoggerTest extends \TestCase
{
    public function testRequest()
    {
        $mock = \Mockery::mock(ConsoleOutput::class)
            ->shouldReceive('writeln')
            ->with("<info>Request</info>\n<options=bold>Method:</> POST\n<options=bold>Endpoint:</> http://deathstar.com/test\n<options=bold>Headers:</> test-key: test-value\n<options=bold>Body:</> {\"test-key\":\"test-value\"}\n")
            ->once()
            ->getMock();

        $logger = new DeathStarApiConsoleLogger($mock);
        $logger->request('POST', 'http://deathstar.com/test', ['test-key' => 'test-value'], ['test-key' => 'test-value']);
    }

    public function testResponse()
    {
        $mock = \Mockery::mock(ConsoleOutput::class)
            ->shouldReceive('writeln')
            ->with("<info>Response</info>\n<options=bold>Code:</> 200\n<options=bold>Headers:</> test-key: test-value\n<options=bold>Body:</> {\"test-key\":\"test-value\"}\n")
            ->once()
            ->getMock();

        $logger = new DeathStarApiConsoleLogger($mock);
        $logger->response(200, ['test-key' => 'test-value'], ['test-key' => 'test-value']);
    }

    public function testResponseWithEmptyArrays()
    {
        $mock = \Mockery::mock(ConsoleOutput::class)
            ->shouldReceive('writeln')
            ->with("<info>Response</info>\n<options=bold>Code:</> 200\n<options=bold>Headers:</> \n<options=bold>Body:</> \n")
            ->once()
            ->getMock();

        $logger = new DeathStarApiConsoleLogger($mock);
        $logger->response(200, [], []);
    }
}
