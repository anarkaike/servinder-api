<?php

/**
 * Sou uma classe para processar modelos de acordo com as necessidades de esquema de banco de dados.
 * Soy una clase para procesar modelos según las necesidades de esquema de base de datos.
 * I am a class to process model schemas according to database requirements.
 */

namespace App\ModelSchemas\Commands\Processors;

use App\ModelSchemas\Commands\Contracts\ColumnBackupInterface;
use App\ModelSchemas\Commands\Contracts\ColumnManagerInterface;
use App\ModelSchemas\Commands\Contracts\ModelProcessorInterface;
use App\ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use App\ModelSchemas\Commands\Contracts\VersionManagerInterface;
use App\ModelSchemas\Commands\Updaters\SchemaSynchronizer;
use FilesystemIterator;
use Illuminate\Support\Facades\File;

class ModelProcessor implements ModelProcessorInterface
{
    private ColumnBackupInterface      $columnBackup;
    private ColumnManagerInterface     $columnManager;
    private VersionManagerInterface    $versionManager;
    private SchemaInterpreterInterface $schemaInterpreter;
    
    public function __construct(
        ColumnBackupInterface      $columnBackup,
        ColumnManagerInterface     $columnManager,
        VersionManagerInterface    $versionManager,
        SchemaInterpreterInterface $schemaInterpreter,
    )
    {
        $this->columnBackup = $columnBackup;
        $this->columnManager = $columnManager;
        $this->versionManager = $versionManager;
        $this->schemaInterpreter = $schemaInterpreter;
    }
    
    /**
     * Processa todos os arquivos de modelo no caminho fornecido.
     * Procesa todos los archivos de modelo en la ruta proporcionada.
     * I process all model files in the given path.
     *
     * @param string $path
     *
     * @throws \Exception
     */
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
    
    /**
     * Verifica se o diretório existe.
     * Verifica si el directorio existe.
     * I check if the directory exists.
     *
     * @param string $path
     *
     * @return bool
     */
    private function directoryExists(string $path): bool
    {
        return File::exists($path);
    }
    
    /**
     * Obtém todos os arquivos no diretório fornecido.
     * Obtiene todos los archivos en el directorio proporcionado.
     * I get all files in the given directory.
     *
     * @param string $path
     *
     * @return \Symfony\Component\Finder\SplFileInfo[]
     */
    private function getAllFilesInDirectory(string $path): array
    {
        return File::allFiles($path);
    }
    
    /**
     * Obtém o namespace do caminho do arquivo.
     * Obtiene el namespace del camino del archivo.
     * I get the namespace from the file path.
     *
     * @param string $filePath
     *
     * @return string
     */
    private function getNamespaceFromFilePath(string $filePath): string
    {
        $relativePath = str_replace([base_path() . '/', '.php'], '', $filePath);
        $namespace = str_replace('/', '\\', $relativePath);
        return ucfirst($namespace);
    }
    
    /**
     * Verifica se a classe existe para o namespace fornecido.
     * Verifica si la clase existe para el namespace proporcionado.
     * I check if the class exists for the given namespace.
     *
     * @param string $namespace
     *
     * @return bool
     */
    private function classExists(string $namespace): bool
    {
        return class_exists($namespace);
    }
    
    /**
     * Atualiza o esquema de banco de dados para o namespace fornecido.
     * Actualiza el esquema de base de datos para el namespace proporcionado.
     * I update the database schema for the given namespace.
     *
     * @param string $namespace
     *
     * @return void
     */
    private function updateDatabaseSchemaForNamespace(string $namespace): void
    {
        $schemaUpdater = new SchemaSynchronizer(
            columnBackup     : $this->columnBackup,
            columnManager    : $this->columnManager,
            versionManager   : $this->versionManager,
            schemaInterpreter: $this->schemaInterpreter,
        );
        $schemaUpdater->updateDatabaseSchema($namespace);
    }
}
