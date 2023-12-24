<?php

declare(strict_types=1);

namespace Quinten\Csr\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use InvalidArgumentException;

abstract class CsrGeneratorCommand extends GeneratorCommand
{
    protected $description = 'Create a new file';

    protected $type = 'Mixed';

    public function handle(): void
    {
        parent::handle();
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

    protected function buildClass($name)
    {
        $replace = [];
        $replace = $this->buildReplacements($replace);

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    protected abstract function buildReplacements(array $replace);

    protected function validateName($name)
    {
        $name = trim(str_replace('/', '\\', $name), '\\');

        if (!Str::startsWith($name, $rootNamespace = $this->laravel->getNamespace())) {
            $name = $rootNamespace . $name;
        }

        return $name;
    }
}
