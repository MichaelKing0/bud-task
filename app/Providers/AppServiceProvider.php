<?php

namespace App\Providers;

use App\DeathStar\DeathStarApiClient;
use App\DeathStar\DeathStarService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DeathStarApiClient::class, function ($app) {
            $baseUrl = env('DEATHSTAR_BASE_URL');
            $clientCert = base_path(env('DEATHSTAR_CLIENT_CERT_PATH'));
            $clientCertPassword = env('DEATHSTAR_CLIENT_CERT_PASSWORD');
            $clientSslKey = base_path(env('DEATHSTAR_CLIENT_KEY_PATH'));
            $clientSslKeyPassword = env('DEATHSTAR_CLIENT_KEY_PASSWORD');

            return new DeathStarApiClient(new Client(), $baseUrl, $clientCert, $clientCertPassword, $clientSslKey, $clientSslKeyPassword);
        });
    }
}
