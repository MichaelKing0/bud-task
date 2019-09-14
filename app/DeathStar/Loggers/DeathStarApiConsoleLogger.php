<?php

namespace App\DeathStar\Loggers;

use Symfony\Component\Console\Output\ConsoleOutput;

class DeathStarApiConsoleLogger implements DeathStarApiLogger
{
    private $console;

    public function __construct(ConsoleOutput $consoleOutput)
    {
        $this->console = $consoleOutput;
    }

    private function buildLogStringAsJson(array $keyValues)
    {
        if (!$keyValues) {
            return '';
        }

        return json_encode($keyValues);
    }

    private function buildLogString(array $keyValues)
    {
        $items = [];

        foreach ($keyValues as $key => $value) {
            $value = is_array($value) ? $value[0] : $value;
            $items[] = "$key: $value";
        }

        return implode('; ', $items);
    }

    public function request(string $method, string $endpoint, array $headers, array $body): void
    {
        $headerString = $this->buildLogString($headers);
        $bodyString = $this->buildLogStringAsJson($body);

        $this->console->writeln("<info>Request</info>\n<options=bold>Method:</> $method\n<options=bold>Endpoint:</> $endpoint\n<options=bold>Headers:</> $headerString\n<options=bold>Body:</> $bodyString\n");
    }

    public function response(int $code, array $headers, array $body): void
    {
        $headerString = $this->buildLogString($headers);
        $bodyString = $this->buildLogStringAsJson($body);

        $this->console->writeln("<info>Response</info>\n<options=bold>Code:</> $code\n<options=bold>Headers:</> $headerString\n<options=bold>Body:</> $bodyString\n");
    }
}
