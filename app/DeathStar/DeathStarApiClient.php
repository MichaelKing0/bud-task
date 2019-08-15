<?php

namespace App\DeathStar;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class DeathStarApiClient
{
    protected $client, $baseUrl, $clientCert, $clientCertPassword, $clientSslKey, $clientSslKeyPassword;

    /** @var DeathStarOAuthToken */
    protected $deathStarOAuthToken;

    public function __construct(Client $client, $baseUrl, $clientCert, $clientCertPassword, $clientSslKey, $clientSslKeyPassword)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->clientCert = $clientCert;
        $this->clientCertPassword = $clientCertPassword;
        $this->clientSslKey = $clientSslKey;
        $this->clientSslKeyPassword = $clientSslKeyPassword;
    }

    public function setOAuthToken(DeathStarOAuthToken $deathStarOAuthToken): bool
    {
        $this->deathStarOAuthToken = $deathStarOAuthToken;

        return true;
    }

    protected function getBaseRequestParams(array $headers = [], bool $useToken = true): array
    {
        if ($useToken && !$this->deathStarOAuthToken) {
            throw new \Exception('You need to set an OAuth token.');
        }

        $headers = array_merge([
            'Content-Type' => 'application/json'
        ], $headers);

        $params = [
            'cert' => [$this->clientCert, $this->clientCertPassword],
            'ssl_key' => [$this->clientSslKey, $this->clientSslKeyPassword],
            'headers' => $headers,
        ];

        if ($useToken) {
            $params['headers'] = array_merge([
                'Authorization' => $this->deathStarOAuthToken->getTokenType() . ' ' . $this->deathStarOAuthToken->getAccessToken()
            ], $params['headers']);
        }

        return $params;
    }

    public function get(string $endpoint, array $headers = [], bool $useToken = true): ResponseInterface
    {
        $params = $this->getBaseRequestParams($headers, $useToken);
        return $this->client->get($this->baseUrl . $endpoint, $params);
    }

    public function post(string $endpoint, array $body, array $headers = [], $useJson = true, bool $useToken = true): ResponseInterface
    {
        $params = $this->getBaseRequestParams($headers, $useToken);

        if ($useJson) {
            $params[RequestOptions::JSON] = $body;
        } else {
            $params[RequestOptions::FORM_PARAMS] = $body;
        }

        return $this->client->post($this->baseUrl . $endpoint, $params);
    }

    public function delete(string $endpoint, array $headers = [], bool $useToken = true): ResponseInterface
    {
        $params = $this->getBaseRequestParams($headers, $useToken);
        return $this->client->delete($this->baseUrl . $endpoint, $params);
    }
}
