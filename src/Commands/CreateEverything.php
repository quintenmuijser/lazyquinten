<?php

declare(strict_types=1);

namespace Quinten\Csr\Src\Commands;

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
        $name = ucfirst($this->argument('name'));
        $namespace = ucfirst($this->argument('namespace') ?? '');
        $this->createController($namespace, $name);

        $this->alert('Generation complete, lets pray no bugs or errors were made!');
    }

    private function createController(string $namespace, string $name): void
    {
        $this->call('csr:controller', [
            'name' => $this->paths->controller . '/' . $namespace . '/' . $name . 'Controller',
            'basename' => $name,
            'namespace' => $namespace,
        ]);
    }
}
