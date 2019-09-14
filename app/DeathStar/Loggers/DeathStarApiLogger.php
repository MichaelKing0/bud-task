<?php

namespace App\DeathStar\Loggers;

interface DeathStarApiLogger
{
    public function request(string $method, string $endpoint, array $headers, array $body): void;
    public function response(int $code, array $headers, array $body): void;
}
