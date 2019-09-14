<?php

namespace App\DeathStar;

use App\DeathStar\LanguageConverters\LanguageConverterInterface;

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

    public function deleteExhaust(int $torpedoes = 2)
    {
        return $this->deathStarApiClient->delete('/reactor/exhaust/1', [
            'X-Torpedoes' => $torpedoes,
        ]);
    }

    public function getLeia()
    {
        $response = $this->deathStarApiClient->get('/prison/leia');

        if (!$this->languageConverter) {
            return $response;
        }

        return [
            'cell' => $this->languageConverter->convertDroidSpeak($response['cell']),
            'block' => $this->languageConverter->convertDroidSpeak($response['block']),
        ];
    }
}
