<?php

namespace App\DeathStar;

class DeathStarAuthentication
{
    private $deathStarApiClient;

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

        return DeathStarOAuthToken::createFromArray($result);
    }
}
