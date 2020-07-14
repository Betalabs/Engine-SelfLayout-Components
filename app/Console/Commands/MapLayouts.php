<?php

namespace Betalabs\EngineSelfLayoutComponents\Console\Commands;

use Betalabs\EngineSelfLayoutComponents\Services\Helpers\Engine;
use Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mapper;
use Betalabs\LaravelHelper\Models\Tenant;
use Illuminate\Console\Command;
use Laravel\Passport\Passport;

class MapLayouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'map:layouts {tenantId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Map layouts to Engine instance';
    /**
     * @var \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mapper
     */
    private $mapper;

    /**
     * Create a new command instance.
     *
     * @param \Betalabs\EngineSelfLayoutComponents\Services\Layouts\Mapper $mapper
     */
    public function __construct(Mapper $mapper)
    {
        parent::__construct();
        $this->mapper = $mapper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->authenticate();
        $this->mapper->map();

        $this->info('Layouts mapped successfully.');
    }

    private function authenticate()
    {
        $tenant = Tenant::query()->findOrFail(
            $this->argument('tenantId')
        );

        Passport::actingAs($tenant);
        Engine::auth($tenant);
    }
}
