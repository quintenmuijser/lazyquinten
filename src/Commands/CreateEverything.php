<?php

declare(strict_types=1);

namespace Quinten\Csr\Src\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class CreateEverything extends Command
{
    protected $signature = 'csr:gen {name : The name } {namespace? : The namespace}';

    protected $description = 'Generate everything you need hopefully dw about the potential errors';

    protected $type = 'Creates everything even bugs and errors';

    protected $paths = [
        'model' => 'Models',
        'controller' => 'Http/Controllers',
        'service' => 'Services',
        'service_interface' => 'Interfaces/Services',
        'repository' => 'Repositories',
        'repository_interface' => 'Interfaces/Repositories',
        'policy' => 'Policies',
    ];

    public function handle(): void
    {

        $config = $this->loadConfig();
        $this->displayLoadedConfig($config);

        foreach ($config['entities'] as $entity) {
            $name = ucfirst($entity['name']);
            $namespace = ucfirst($this->argument('namespace') ?? '');
            $this->createController($namespace, $name, $entity);
        }

        $this->alert('Generation complete, lets pray no bugs or errors were made!');
    }

    private function createController(string $namespace, string $name, $data): void
    {
        $this->call('csr:controller', [
            'name' => $this->paths['controller'] . '/' . $namespace . '/' . $name . 'Controller',
            'basename' => $name,
            'namespace' => $namespace,
            'data' => $data,
        ]);
    }

    private function loadConfig(): array
    {
        $configPath = config_path('csr.php');

        if (!File::exists($configPath)) {
            $this->error('Config file not found. Make sure it exists at config/csr.php.');

            // You may handle this situation differently based on your needs
            exit(1);
        }

        return include $configPath;
    }

    private function displayLoadedConfig(array $config): void
    {
        $this->info('Loaded Configuration:');
        $this->info('=====================');
        $this->info(json_encode($config, JSON_PRETTY_PRINT));
    }

    private function getModelConfig(array $entity): array
    {
        return $entity['database']['model'] ?? [];
    }

    private function getRelationshipsConfig(array $entity): array
    {
        return $entity['database']['relationships'] ?? [];
    }

    private function getControllerConfig(array $entity): array
    {
        return $entity['controller'] ?? [];
    }
}
