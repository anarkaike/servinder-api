<?php

namespace ModelSchemas\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Ainda não é hora de criar isso. Vou terminar uma estrutura com tudo
 * para depois criar esse gerador.
 */
class GenerateClassesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:classes {module} {model?}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all classes for a specific module using stubs';
    
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $module = $this->argument('module');
        $model = $this->argument('model');
        $modulePath = base_path("Modules/{$module}");
        
        if (!File::exists($modulePath)) {
            $this->error("Module {$module} does not exist.");
            return 1;
        }
        
        if ($model) {
            $modelPath = $this->findModelPath($model);
            if (!$modelPath) {
                $this->error("Model {$model} does not exist.");
                return 1;
            }
            $this->generateClassFromModel($module, $modelPath);
        }
        else {
            $this->generateClass($module, 'Http/Controllers', 'controller.stub');
            $this->generateClass($module, 'Models', 'model.stub');
            $this->generateClass($module, 'Http/Requests', 'request.stub');
            $this->generateClass($module, 'Http/Resources', 'resource.stub');
            $this->generateClass($module, 'Policies', 'policy.stub');
            $this->generateClass($module, 'Collections', 'collection.stub');
        }
        
        $this->info("All classes for module {$module} have been generated successfully.");
        
        return 0;
    }
    
    /**
     * Generate a class from a stub.
     *
     * @param string $module
     * @param string $directory
     * @param string $stub
     *
     * @return void
     */
    protected function generateClass($module, $directory, $stub)
    {
        $modulePath = base_path("Modules/{$module}/app/{$directory}");
        $stubPath = resource_path("stubs/{$stub}");
        
        if (!File::exists($modulePath)) {
            File::makeDirectory($modulePath, 0755, TRUE);
        }
        
        $className = ucfirst(basename($directory));
        $classPath = "{$modulePath}/{$className}.php";
        $stubContent = File::get($stubPath);
        
        $classContent = str_replace(
            ['{{ className }}', '{{ namespace }}'],
            [$className, "Modules\\{$module}\\app\\" . str_replace('/', '\\', $directory)],
            $stubContent,
        );
        
        File::put($classPath, $classContent);
        $this->info("Generated {$className} in module {$module}");
    }
    
    /**
     * Find the path of the model.
     *
     * @param string $model
     *
     * @return string|null
     */
    protected function findModelPath($model)
    {
        $model = str_replace('\\', '/', $model);
        $paths = [
            app_path("Models/{$model}.php"),
            ...glob(base_path('Modules/*/app/Models/' . $model . '.php')),
        ];
        
        foreach ($paths as $path) {
            if (File::exists($path)) {
                return $path;
            }
        }
        
        return NULL;
    }
    
    /**
     * Generate classes based on the model schema.
     *
     * @param string $module
     * @param string $modelPath
     *
     * @return void
     */
    protected function generateClassFromModel($module, $modelPath)
    {
        $modelName = basename($modelPath, '.php');
        $modelNamespace = $this->getNamespaceFromPath($modelPath);
        
        // Implement logic to read the model schema and generate classes based on it
        // For example, you can use reflection to read the model properties and methods
        
        // Example:
        $this->generateClass($module, 'Http/Controllers', 'controller.stub');
        $this->generateClass($module, 'Http/Requests', 'request.stub');
        $this->generateClass($module, 'Http/Resources', 'resource.stub');
        $this->generateClass($module, 'Policies', 'policy.stub');
        $this->generateClass($module, 'Collections', 'collection.stub');
    }
    
    /**
     * Get the namespace from the file path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getNamespaceFromPath($path)
    {
        $content = File::get($path);
        if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
            return $matches[1];
        }
        return '';
    }
}
