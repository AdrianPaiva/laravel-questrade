<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use ScoutElastic\Facades\ElasticClient;

class SetupElasticSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create ElasticSearch Indexes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('working...');

        $this->createIndexes();

        $this->populateIndexes();
    }

    private function createIndexes()
    {
        if (!ElasticClient::indices()->exists(['index' => 'user'])) {
            Artisan::call("elastic:create-index", ['index-configurator' => "App\Search\UserIndexConfigurator"]);
        }

        $this->line('indexes created');
    }

    private function populateIndexes()
    {
        Artisan::call("scout:import", ['model' => "App\Models\User"]);

        $this->line('indexes populated');
    }
}
