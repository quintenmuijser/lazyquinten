<?php

declare(strict_types=1);

namespace Quinten\Csr\Src;

use Quinten\Csr\Commands\CreateModel;
use Quinten\Csr\Commands\CreatePolicy;
use Quinten\Csr\Commands\CreateService;
use Quinten\Csr\Src\Commands\CreateEverything;
use Quinten\Csr\Commands\CreateIService;
use Quinten\Csr\Commands\CreateRepository;
use Quinten\Csr\Commands\CreateController;
use Quinten\Csr\Commands\CreateIRepository;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class CsrServiceProvider extends LaravelServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->handleConfigs();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            CreateEverything::class,
        ]);
    }

    /**
     * Fetches and publishes config file
     *
     * @return void
     */
    private function handleConfigs(): void
    {
        $configPath = __DIR__ . '/../config/csr.php';
        $this->publishes([$configPath => config_path('csr.php')], 'laravel-csr');
    }
}
