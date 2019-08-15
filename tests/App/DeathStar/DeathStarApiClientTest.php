<?php

namespace Tests\App\DeathStar;

use App\DeathStar\DeathStarApiClient;
use App\DeathStar\DeathStarOAuthToken;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class DeathStarApiClientTest extends \TestCase
{
    public function testGet()
    {
        $expectedParams = [
            'cert' => ['test-cert-path', 'test-cert-password'],
            'ssl_key' => ['test-ssl-key-path', 'test-ssl-key-password'],
            'headers' => [
                'Content-Type' => 'application/json',
                'Fake-Header' => 'fake',
                'Authorization' => 'Bearer faketoken',
            ]
        ];

        $mockClient = \Mockery::mock(Client::class)
            ->shouldReceive('get')
            ->once()
            ->with('https://death.star.api/test', $expectedParams)
            ->andReturn(new Response())
            ->getMock();

        $deathStarApiClient = new DeathStarApiClient($mockClient, 'https://death.star.api', 'test-cert-path', 'test-cert-password', 'test-ssl-key-path', 'test-ssl-key-password');
        $deathStarApiClient->setOAuthToken(DeathStarOAuthToken::createFromParams('faketoken', 99999999, 'Bearer', 'Test'));

        $deathStarApiClient->get('/test', ['Fake-Header' => 'fake']);
    }

    public function testPost()
    {
        $expectedParams = [
            'cert' => ['test-cert-path', 'test-cert-password'],
            'ssl_key' => ['test-ssl-key-path', 'test-ssl-key-password'],
            'headers' => [
                'Content-Type' => 'application/json',
                'Fake-Header' => 'fake',
                'Authorization' => 'Bearer faketoken',
            ],
            'json' => [
                'test' => 'param',
            ]
        ];

        $mockClient = \Mockery::mock(Client::class)
            ->shouldReceive('post')
            ->once()
            ->with('https://death.star.api/test', $expectedParams)
            ->andReturn(new Response())
            ->getMock();

        $deathStarApiClient = new DeathStarApiClient($mockClient, 'https://death.star.api', 'test-cert-path', 'test-cert-password', 'test-ssl-key-path', 'test-ssl-key-password');
        $deathStarApiClient->setOAuthToken(DeathStarOAuthToken::createFromParams('faketoken', 99999999, 'Bearer', 'Test'));

        $deathStarApiClient->post('/test', ['test' => 'param'], ['Fake-Header' => 'fake']);
    }

    public function testDelete()
    {
        $expectedParams = [
            'cert' => ['test-cert-path', 'test-cert-password'],
            'ssl_key' => ['test-ssl-key-path', 'test-ssl-key-password'],
            'headers' => [
                'Content-Type' => 'application/json',
                'Fake-Header' => 'fake',
                'Authorization' => 'Bearer faketoken',
            ]
        ];

        $mockClient = \Mockery::mock(Client::class)
            ->shouldReceive('delete')
            ->once()
            ->with('https://death.star.api/test', $expectedParams)
            ->andReturn(new Response())
            ->getMock();

        $deathStarApiClient = new DeathStarApiClient($mockClient, 'https://death.star.api', 'test-cert-path', 'test-cert-password', 'test-ssl-key-path', 'test-ssl-key-password');
        $deathStarApiClient->setOAuthToken(DeathStarOAuthToken::createFromParams('faketoken', 99999999, 'Bearer', 'Test'));

        $deathStarApiClient->delete('/test', ['Fake-Header' => 'fake']);
    }
}
