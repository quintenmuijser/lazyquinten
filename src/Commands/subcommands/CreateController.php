<?php

declare(strict_types=1);

namespace Quinten\Csr\Commands;

use Illuminate\Support\Str;

class CreateController extends CsrGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csr:controller
                            {name : The name of the controller to be created}
                            {basename : The name of the controller to be created}
                            {namespace? : The namespace and folder to place the item in}
                            {crud? : use Crud}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if($this->arguments()['crud']) {
            return  __DIR__ . '/../stubs/files/backend/controller/controllerCrud.stub';
        }

        return  __DIR__ . '/../stubs/files/backend/controller/controller.stub';
    }

    protected function buildReplacements(array $replace)
    {
        $className = $this->validateName($this->argument('name'));
        $namespace = $this->argument('namespace');
        $baseName = $this->argument('basename');
        $crud = $this->argument('crud');

        return array_merge($replace, [
            '{{BaseName}}' => $baseName,
            '{{baseName}}' => lcfirst($baseName),
            '{{Variable}}' => ucfirst(class_basename($className)),
            '{{NamespaceShort}}' => $namespace ? ucfirst(strtolower($namespace)) . '\\' : '',
        ]);
    }
}