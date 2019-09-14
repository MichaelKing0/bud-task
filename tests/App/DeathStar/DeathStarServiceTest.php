<?php

namespace Tests\App\DeathStar;

use App\DeathStar\DeathStarApiClient;
use App\DeathStar\DeathStarApiException;
use App\DeathStar\DeathStarAuthentication;
use App\DeathStar\DeathStarOAuthToken;
use App\DeathStar\DeathStarService;
use App\DeathStar\LanguageConverters\GalacticBasic;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\stream_for;

class DeathStarServiceTest extends \TestCase
{
    public function testDeleteExhaust()
    {
        $fakeToken = DeathStarOAuthToken::createFromParams('test', 9999999, 'Bearer', 'Test');

        $deathStarApiClientMock = \Mockery::mock(DeathStarApiClient::class);

        $deathStarApiClientMock->shouldReceive('setOAuthToken')
            ->once()
            ->with($fakeToken)
            ->andReturn(true);

        $deathStarApiClientMock->shouldReceive('delete')
            ->once()
            ->with('/reactor/exhaust/1', [
                'X-Torpedoes' => 2,
            ])
            ->andReturn(new Response());

        $deathStarAuthenticationMock = \Mockery::mock(DeathStarAuthentication::class)
            ->shouldReceive('getOAuthToken')
            ->once()
            ->andReturn($fakeToken)
            ->getMock();

        $deathStarService = new DeathStarService($deathStarApiClientMock, $deathStarAuthenticationMock);

        $token = $deathStarService->getOAuthToken('Alderaan', 'R2D2');
        $deathStarService->setOAuthToken($token);
        $deathStarService->deleteExhaust();
    }

    public function testGetLeia()
    {
        $fakeToken = DeathStarOAuthToken::createFromParams('test', 9999999, 'Bearer', 'Test');

        $deathStarApiClientMock = \Mockery::mock(DeathStarApiClient::class);

        $deathStarApiClientMock->shouldReceive('setOAuthToken')
            ->once()
            ->with($fakeToken)
            ->andReturn(true);

        $leiaResponseMock = json_decode('{"cell": "01000011 01100101 01101100 01101100 00100000 00110010 00110001 00111000 0110111", "block": "01000100 01100101 01110100 01100101 01101110 01110100 01101001 01101111 01101110 00100000 01000010 01101100 01101111 01100011 01101011 00100000 01000001 01000001 00101101 00110010 00110011 00101100"}', true);

        $deathStarApiClientMock->shouldReceive('get')
            ->once()
            ->with('/prison/leia')
            ->andReturn($leiaResponseMock);

        $deathStarAuthenticationMock = \Mockery::mock(DeathStarAuthentication::class)
            ->shouldReceive('getOAuthToken')
            ->once()
            ->andReturn($fakeToken)
            ->getMock();

        $deathStarService = new DeathStarService($deathStarApiClientMock, $deathStarAuthenticationMock);
        $deathStarService->setLanguage(new GalacticBasic());

        $token = $deathStarService->getOAuthToken('Alderaan', 'R2D2');
        $deathStarService->setOAuthToken($token);
        $response = $deathStarService->getLeia();

        $this->assertEquals([
            'cell' => 'Cell 2187',
            'block' => 'Detention Block AA-23,',
        ], $response);
    }

    public function testGetLeiaWithRequestError()
    {
        $exceptionMessage = 'The Death Star responded with a non successful status code: 500. Reason: The Death Star experienced an error.';
        $this->expectExceptionObject(new DeathStarApiException($exceptionMessage));

        $fakeToken = DeathStarOAuthToken::createFromParams('test', 9999999, 'Bearer', 'Test');

        $deathStarApiClientMock = \Mockery::mock(DeathStarApiClient::class);

        $deathStarApiClientMock->shouldReceive('setOAuthToken')
            ->once()
            ->with($fakeToken)
            ->andReturn(true);

        $deathStarApiClientMock->shouldReceive('get')
            ->once()
            ->with('/prison/leia')
            ->andThrowExceptions([new DeathStarApiException($exceptionMessage)]);

        $deathStarAuthenticationMock = \Mockery::mock(DeathStarAuthentication::class)
            ->shouldReceive('getOAuthToken')
            ->once()
            ->andReturn($fakeToken)
            ->getMock();

        $deathStarService = new DeathStarService($deathStarApiClientMock, $deathStarAuthenticationMock);
        $deathStarService->setLanguage(new GalacticBasic());

        $token = $deathStarService->getOAuthToken('Alderaan', 'R2D2');
        $deathStarService->setOAuthToken($token);
        $response = $deathStarService->getLeia();

        // Exception expected (set at the beginning of this method)
    }
}
