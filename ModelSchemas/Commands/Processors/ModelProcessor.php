<?php

namespace ModelSchemas\Commands\Processors;

use App\Helpers\Logger;
use Illuminate\Support\Facades\File;
use ModelSchemas\Commands\Contracts\ColumnBackupInterface;
use ModelSchemas\Commands\Contracts\ColumnManagerInterface;
use ModelSchemas\Commands\Contracts\ModelProcessorInterface;
use ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use ModelSchemas\Commands\Contracts\VersionManagerInterface;
use ModelSchemas\Commands\Updaters\SchemaSynchronizer;

class ModelProcessor implements ModelProcessorInterface
{
    
    public function __construct(
        private ColumnBackupInterface      $columnBackup,
        private ColumnManagerInterface     $columnManager,
        private VersionManagerInterface    $versionManager,
        private SchemaInterpreterInterface $schemaInterpreter,
        private Logger                     $logger,
    )
    {
        $this->logger = $logger;
    }
    
    public function processModels(string $path): void
    {
        if (!$this->directoryExists($path)) {
            return;
        }
        
        $files = $this->getAllFilesInDirectory($path);
        
        foreach ($files as $file) {
            $namespace = $this->getNamespaceFromFilePath($file->getPathname());
            if ($this->classExists($namespace)) {
                $this->updateDatabaseSchemaForNamespace($namespace);
            }
        }
    }
    
    private function directoryExists(string $path): bool
    {
        return File::exists($path);
    }
    
    private function getAllFilesInDirectory(string $path): array
    {
        return File::allFiles($path);
    }
    
    private function getNamespaceFromFilePath(string $filePath): string
    {
        $relativePath = str_replace([base_path() . '/', '.php'], '', $filePath);
        $namespace = str_replace('/', '\\', $relativePath);
        return ucfirst($namespace);
    }
    
    private function classExists(string $namespace): bool
    {
        return class_exists($namespace);
    }
    
    private function updateDatabaseSchemaForNamespace(string $namespace): void
    {
        $schemaUpdater = new SchemaSynchronizer(
            columnBackup     : $this->columnBackup,
            columnManager    : $this->columnManager,
            versionManager   : $this->versionManager,
            schemaInterpreter: $this->schemaInterpreter,
            logger           : $this->logger,
        );
        $schemaUpdater->updateDatabaseSchema($namespace);
    }
    
    private function log(string $message): void
    {
        $this->logger->log($message);
    }
}
