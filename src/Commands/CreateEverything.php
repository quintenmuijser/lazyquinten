<?php

declare(strict_types=1);

namespace Quinten\Csr\Src\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class CreateEverything extends Command
{
    protected $signature = 'csr:gen {namespace? : The namespace}';

    protected $description = 'Generate everything you need hopefully dw about the potential errors';

    protected $type = 'Creates everything even bugs and errors';

    protected array $paths = [
        'model' => 'Models',
        'controller' => 'Http/Controllers',
        'request' => 'Http/Requests',
        'service' => 'Services',
        'service_interface' => 'Interfaces/Services',
        'repository' => 'Repositories',
        'repository_interface' => 'Interfaces/Repositories',
        'policy' => 'Policies',
        'migration' => '/../database/migrations',
    ];

    public function handle(): void
    {

        $config = $this->loadConfig();

        foreach ($config['entities'] as $entity) {
            $name = ucfirst($entity['name']);
            $namespace = ucfirst($this->argument('namespace') ?? '');

            $this->createMigration($namespace,$name,$entity);
            $this->createModel($namespace,$name,$entity);

            if($entity['crud']) {
                $this->createRequest($namespace,$name,$entity, 'Store');
                $this->createRequest($namespace,$name,$entity, 'Update');
                $this->createRequest($namespace,$name,$entity, 'Delete');

                //policie should be used here aswell
            }

            $this->createRepository($namespace, $name, $entity);
            $this->createService($namespace, $name, $entity);
            $this->createController($namespace, $name, $entity);
        }

        $this->alert('Generation complete, lets pray no bugs or errors were made!');
    }

    private function createController(string $namespace, string $name, $entity): void
    {
        $this->call('csr:controller', [
            'name' => $this->paths['controller'] . '/' . $namespace . '/' . $name . 'Controller',
            'basename' => $name,
            'namespace' => $namespace,
            'entity' => $entity,
        ]);
    }

    private function createRepository(string $namespace, string $name, $entity): void
    {
        $this->call('csr:irepository', [
            'name' => $this->paths['repository_interface'] . '/' . $namespace . '/I' . $name . 'Repository',
            'basename' => $name,
            'namespace' => $namespace,
            'entity' => $entity,
        ]);

        $this->call('csr:repository', [
            'name' => $this->paths['repository'] . '/' . $namespace . '/' . $name . 'Repository',
            'basename' => $name,
            'namespace' => $namespace,
            'entity' => $entity,
        ]);
    }

    private function createService(string $namespace, string $name, $entity): void
    {
        $this->call('csr:iservice', [
            'name' => $this->paths['service_interface'] . '/' . $namespace . '/I' . $name . 'Service',
            'basename' => $name,
            'namespace' => $namespace,
            'entity' => $entity,
        ]);

        $this->call('csr:service', [
            'name' => $this->paths['service'] . '/' . $namespace . '/' . $name . 'Service',
            'basename' => $name,
            'namespace' => $namespace,
            'entity' => $entity,
        ]);
    }

    private function createMigration(string $namespace, string $name, $entity): void
    {
        $this->call('csr:migration', [
            'name' => $this->paths['migration'] . '/' . $name . 'MigrationByCSR',
            'basename' => $name,
            'namespace' => $namespace,
            'entity' => $entity,
        ]);
    }

    private function createModel(string $namespace, string $name, $entity): void
    {
        $this->call('csr:model', [
            'name' => $this->paths['model'] . '/' . $namespace . '/' . $name,
            'basename' => $name,
            'namespace' => $namespace,
            'entity' => $entity,
        ]);
    }

    private function createPolicy(string $namespace, string $name, $entity): void
    {
        $this->call('csr:policy', [
            'name' => $this->paths['policy'] . '/' . $namespace . '/' . $name . 'Policy',
            'basename' => $name,
            'namespace' => $namespace,
            'entity' => $entity,
        ]);
    }

    private function createRequest(string $namespace, string $name, $entity, $action): void
    {
        $this->call('csr:request', [
            'name' => $this->paths['request'] . '/' . $namespace . '/' . $action . $name . 'Request',
            'basename' => $name,
            'namespace' => $namespace,
            'entity' => $entity,
            'action' => $action,
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
}
