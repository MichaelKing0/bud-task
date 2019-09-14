<?php

namespace App\DeathStar;

use App\DeathStar\Loggers\DeathStarApiLogger;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class DeathStarApiClient
{
    private $client, $baseUrl, $clientCert, $clientCertPassword, $clientSslKey, $clientSslKeyPassword;

    /** @var DeathStarOAuthToken */
    private $deathStarOAuthToken;

    /** @var DeathStarApiLogger */
    private $logger;

    public function __construct(Client $client, $baseUrl, $clientCert, $clientCertPassword, $clientSslKey, $clientSslKeyPassword)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->clientCert = $clientCert;
        $this->clientCertPassword = $clientCertPassword;
        $this->clientSslKey = $clientSslKey;
        $this->clientSslKeyPassword = $clientSslKeyPassword;
    }

    public function setLogger(DeathStarApiLogger $deathStarApiLogger)
    {
        $this->logger = $deathStarApiLogger;
    }

    public function setOAuthToken(DeathStarOAuthToken $deathStarOAuthToken): bool
    {
        $this->deathStarOAuthToken = $deathStarOAuthToken;

        return true;
    }

    protected function parseResponse(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        $headers = $response->getHeaders();
        $body = json_decode($response->getBody()->getContents(), true);

        if ($this->logger) {
            $this->logger->response($statusCode, $headers, $body);
        }

        if ($statusCode == 200) {
            return $body;
        }

        throw new DeathStarApiException(sprintf('The Death Star responded with a non successful status code: %s. Reason: %s', $statusCode, $response->getReasonPhrase()));
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

    public function get(string $endpoint, array $headers = [], bool $useToken = true)
    {
        $params = $this->getBaseRequestParams($headers, $useToken);

        if ($this->logger) {
            $this->logger->request('GET', $endpoint, $params['headers'], []);
        }

        return $this->parseResponse($this->client->get($this->baseUrl . $endpoint, $params));
    }

    public function post(string $endpoint, array $body, array $headers = [], $useJson = true, bool $useToken = true)
    {
        $params = $this->getBaseRequestParams($headers, $useToken);

        if ($useJson) {
            $params[RequestOptions::JSON] = $body;
        } else {
            $params[RequestOptions::FORM_PARAMS] = $body;
        }

        if ($this->logger) {
            $this->logger->request('POST', $endpoint, $params['headers'], $body);
        }

        return $this->parseResponse($this->client->post($this->baseUrl . $endpoint, $params));
    }

    public function delete(string $endpoint, array $headers = [], bool $useToken = true)
    {
        $params = $this->getBaseRequestParams($headers, $useToken);

        if ($this->logger) {
            $this->logger->request('GET', $endpoint, $params['headers'], []);
        }

        return $this->parseResponse($this->client->delete($this->baseUrl . $endpoint, $params));
    }
}
