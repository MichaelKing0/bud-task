<?php

namespace Tests\App\DeathStar;

use App\DeathStar\DeathStarApiClient;
use App\DeathStar\DeathStarAuthentication;
use App\DeathStar\DeathStarOAuthToken;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\stream_for;
use TestCase;

class DeathStarAuthenticationTest extends TestCase
{
    public function testGetOAuthToken()
    {
        $oAuthToken = DeathStarOAuthToken::createFromParams('MTQ0NjJkZmQ5OTM2NDE1ZTZjNGZmZjI3', 9999999, 'bearer', 'TheForce');

        $deathStarApiClient = \Mockery::mock(DeathStarApiClient::class)
            ->shouldReceive('post')
            ->with('/token', [
                    'grant_type' => 'client_credentials',
                ], [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic aWQ6c2VjcmV0'
                ], false, false)
            ->andReturn(new Response(200, [], stream_for(json_encode($oAuthToken->getAsArray()))))
            ->getMock();

        /** @var DeathStarAuthentication $deathStarAuthentication */
        $deathStarAuthentication = $this->app->make(DeathStarAuthentication::class, ['deathStarApiClient' => $deathStarApiClient]);

        $response = $deathStarAuthentication->getOAuthToken('secret', 'id');

        $this->assertEquals($oAuthToken, $response);
    }

    public function testGetOAuthTokenWithGuzzleResponse()
    {
        $oAuthToken = DeathStarOAuthToken::createFromParams('MTQ0NjJkZmQ5OTM2NDE1ZTZjNGZmZjI3', 9999999, 'bearer', 'TheForce');

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], stream_for(json_encode($oAuthToken->getAsArray()))),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $deathStarApiClient = new DeathStarApiClient($client, 'https://death.star.api', 'cert-path', 'cert-password', 'ssl-key-path', 'ssl-key-password');

        /** @var DeathStarAuthentication $deathStarAuthentication */
        $deathStarAuthentication = $this->app->make(DeathStarAuthentication::class, ['deathStarApiClient' => $deathStarApiClient]);

        $response = $deathStarAuthentication->getOAuthToken('client-secret', 'client-id');

        $this->assertEquals($oAuthToken, $response);
    }
}
