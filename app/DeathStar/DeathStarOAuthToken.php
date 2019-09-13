<?php

namespace App\DeathStar;

/**
 * Class DeathStarOAuthToken
 * @package App\DeathStar
 *
 * DTO object to represent an OAuth token. Static methods for building a token could be moved to a factory but as this
 * class isn't complex, I've kept them here for simplicity.
 */
class DeathStarOAuthToken
{
    private $accessToken, $expiresIn, $tokenType, $scope;

    public static function createFromArray(array $tokenArray): self
    {
        $token = new self();
        $token->accessToken = $tokenArray['access_token'];
        $token->expiresIn = $tokenArray['expires_in'];
        $token->tokenType = $tokenArray['token_type'];
        $token->scope = $tokenArray['scope'];
        return $token;
    }

    public static function createFromParams(string $accessToken, int $expiresIn, string $tokenType, string $scope): self
    {
        $token = new self();
        $token->accessToken = $accessToken;
        $token->expiresIn = $expiresIn;
        $token->tokenType = $tokenType;
        $token->scope = $scope;
        return $token;
    }

    public function getAsArray()
    {
        return [
            'access_token' => $this->accessToken,
            'expires_in' => $this->expiresIn,
            'token_type' => $this->tokenType,
            'scope' => $this->scope,
        ];
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    public function getTokenType()
    {
        return $this->tokenType;
    }

    public function getScope()
    {
        return $this->scope;
    }
}
