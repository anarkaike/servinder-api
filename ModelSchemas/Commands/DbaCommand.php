<?php

namespace ModelSchemas\Commands;

use App\Helpers\Logger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ModelSchemas\Commands\Contracts\DbaCommandInterface;
use ModelSchemas\Commands\Drivers\ColumnBackup;
use ModelSchemas\Commands\Drivers\ColumnManager;
use ModelSchemas\Commands\Drivers\SchemaInterpreter;
use ModelSchemas\Commands\Drivers\VersionManager;
use ModelSchemas\Commands\Processors\ModelProcessor;

class DbaCommand extends Command implements DbaCommandInterface
{
    protected $signature   = 'make:dba {--migrate} {--reset} {--debug}';
    protected $description = 'Updates the database structure based on models and their schema properties';
    
    public function handle(): void
    {
        $debug = $this->option('debug');
        $logger = new Logger($debug);
        
        $this->runMigrationsIfNeeded();
        
        $columnBackup = new ColumnBackup($logger);
        $schemaInterpreter = new SchemaInterpreter($logger);
        $versionManager = new VersionManager($logger);
        $columnManager = new ColumnManager($schemaInterpreter, $logger);
        
        // Ensure the tables_infos table exists before processing models
        $columnBackup->ensureTablesInfosTableExists();
        
        $modelProcessor = new ModelProcessor(
            columnBackup     : $columnBackup,
            columnManager    : $columnManager,
            versionManager   : $versionManager,
            schemaInterpreter: $schemaInterpreter,
            logger           : $logger,
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
    }
}
