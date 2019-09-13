<?php

namespace App\DeathStar;

use App\DeathStar\LanguageConverters\LanguageConverterInterface;
use Psr\Http\Message\ResponseInterface;

class DeathStarService
{
    private $deathStarApiClient;
    private $deathStarAuthentication;

    /** @var LanguageConverterInterface */
    private $languageConverter;

    public function __construct(DeathStarApiClient $deathStarApiClient, DeathStarAuthentication $deathStarAuthentication)
    {
        $this->deathStarApiClient = $deathStarApiClient;
        $this->deathStarAuthentication = $deathStarAuthentication;
    }

    public function setLanguage(LanguageConverterInterface $languageConverter)
    {
        $this->languageConverter = $languageConverter;
    }

    public function setOAuthToken(DeathStarOAuthToken $deathStarOAuthToken): bool
    {
        return $this->deathStarApiClient->setOAuthToken($deathStarOAuthToken);
    }

    public function getOAuthToken($clientSecret, $clientId): DeathStarOAuthToken
    {
        return $this->deathStarAuthentication->getOAuthToken($clientSecret, $clientId);
    }

    protected function parseResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody()->getContents(), true);
        }

        throw new DeathStarApiException(sprintf('The Death Star responded with a non successful status code: %s. Reason: %s', $response->getStatusCode(), $response->getReasonPhrase()));
    }

    public function deleteExhaust(int $torpedoes = 2)
    {
        $response = $this->deathStarApiClient->delete('/reactor/exhaust/1', [
            'X-Torpedoes' => $torpedoes,
        ]);

        return $this->parseResponse($response);
    }

    public function getLeia()
    {
        $response = $this->deathStarApiClient->get('/prison/leia');

        $obj = $this->parseResponse($response);

        if (!$this->languageConverter) {
            return $obj;
        }

        return [
            'cell' => $this->languageConverter->convertDroidSpeak($obj['cell']),
            'block' => $this->languageConverter->convertDroidSpeak($obj['block']),
        ];
    }
}
