<?php

namespace App\Console\Commands;

use App\DeathStar\DeathStarService;
use Illuminate\Console\Command;

class DeathStarDestroy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deathstar:destroy {--torpedoes=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destroy the death star with x amount of torpedoes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DeathStarService $deathStarService)
    {
        $token = $deathStarService->getOAuthToken(env('DEATHSTAR_CLIENT_SECRET'), env('DEATHSTAR_CLIENT_ID'));
        $deathStarService->setOAuthToken($token);

        $deathStarService->deleteExhaust($this->option('torpedoes'));
        $deathStarService->getLeia();
    }
}
