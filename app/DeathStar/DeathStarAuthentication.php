<?php

namespace App\DeathStar;

class DeathStarAuthentication
{
    protected $deathStarApiClient;

    public function __construct(DeathStarApiClient $deathStarApiClient)
    {
        $this->deathStarApiClient = $deathStarApiClient;
    }

    public function getOAuthToken($clientSecret, $clientId): DeathStarOAuthToken
    {
        $result = $this->deathStarApiClient->post('/token', [
            'grant_type' => 'client_credentials',
        ], [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret)
        ], false, false);

        if ($result->getStatusCode() === 200) {
            $token = json_decode($result->getBody()->getContents(), true);
            return DeathStarOAuthToken::createFromArray($token);
        }

        throw new DeathStarApiException(sprintf('Error when trying to retrieve access token. Response: %s. Reason: %s', $result->getStatusCode(), $result->getReasonPhrase()));
    }
}
