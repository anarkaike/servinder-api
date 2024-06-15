<?php

namespace App\ModelSchemas\Commands;

use App\ModelSchemas\Commands\Contracts\DbaCommandInterface;
use App\ModelSchemas\Commands\Drivers\ColumnBackup;
use App\ModelSchemas\Commands\Drivers\ColumnManager;
use App\ModelSchemas\Commands\Drivers\SchemaInterpreter;
use App\ModelSchemas\Commands\Drivers\VersionManager;
use App\ModelSchemas\Commands\Processors\ModelProcessor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class DbaCommand extends Command implements DbaCommandInterface
{
    protected $signature = 'make:dba {--migrate} {--reset}';
    protected $description = 'Updates the database structure based on models and their schema properties';
    
    public function handle(): void
    {
        $this->runMigrationsIfNeeded();
        
        $columnBackup = new ColumnBackup();
        $schemaInterpreter = new SchemaInterpreter();
        $versionManager = new VersionManager();
        $columnManager = new ColumnManager($schemaInterpreter);
        
        $modelProcessor = new ModelProcessor(
            columnBackup: $columnBackup,
            columnManager: $columnManager,
            versionManager: $versionManager,
            schemaInterpreter: $schemaInterpreter
        );
        
        $modelProcessor->processModels(app_path('Models'));
        
        $modulesPath = base_path('Modules');
        $modules = File::directories($modulesPath);
        
        foreach ($modules as $module) {
            $modelProcessor->processModels($module . '/app/Models');
        }
    }
    
    private function runMigrationsIfNeeded(): void
    {
        if ($this->option('reset')) {
            $this->info('Resetting the database...');
            Artisan::call('migrate:reset');
        }
        
        if ($this->option('migrate')) {
            $this->info('Running migrations...');
            Artisan::call('migrate');
        }
    }
}
