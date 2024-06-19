<?php

namespace ModelSchemas\Commands\Updaters;

use App\Helpers\Logger;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use ModelSchemas\Commands\Builders\SchemaDefinitionBuilder;
use ModelSchemas\Commands\Contracts\ColumnBackupInterface;
use ModelSchemas\Commands\Contracts\ColumnManagerInterface;
use ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use ModelSchemas\Commands\Contracts\SchemaUpdaterInterface;
use ModelSchemas\Commands\Contracts\VersionManagerInterface;
use ModelSchemas\Enums\ESchemaKey;
use ReflectionClass;
use function implode;

class SchemaSynchronizer implements SchemaUpdaterInterface
{
    private ColumnBackupInterface      $columnBackup;
    private ColumnManagerInterface     $columnManager;
    private VersionManagerInterface    $versionManager;
    private SchemaInterpreterInterface $schemaInterpreter;
    private Logger                     $logger;
    
    public function __construct(
        ColumnBackupInterface      $columnBackup,
        ColumnManagerInterface     $columnManager,
        VersionManagerInterface    $versionManager,
        SchemaInterpreterInterface $schemaInterpreter,
        Logger                     $logger,
    )
    {
        $this->columnBackup = $columnBackup;
        $this->columnManager = $columnManager;
        $this->versionManager = $versionManager;
        $this->schemaInterpreter = $schemaInterpreter;
        $this->logger = $logger;
    }
    
    public function updateDatabaseSchema(string $modelClass, $recursive = FALSE): void
    {
        try {
            $this->logger->log("Iniciando atualização do esquema de banco de dados para o modelo: $modelClass");
            $reflection = new ReflectionClass($modelClass);
            $traits = $reflection->getTraitNames();
            
            if (!$this->shouldUpdateSchema($modelClass, $traits)) {
                $this->logger->log("Ignorando atualização de esquema para o modelo: $modelClass");
                return;
            }
            
            $modelInstance = new $modelClass;
            if (!method_exists($modelInstance, 'getSchema')) {
                $this->logger->log("Método getSchema não existe para o modelo: $modelClass");
                return;
            }
            
            $schema = $modelInstance->getSchema();
            if (empty($schema)) {
                $this->logger->log("O esquema está vazio para o modelo: $modelClass");
                return;
            }
            
            $tableName = Str::plural(Str::snake(class_basename($modelClass)));
            $this->logger->log("Nome da tabela determinado: $tableName para o modelo: $modelClass");
            
            if (!Schema::hasTable($tableName)) {
                $this->logger->log("Tabela $tableName não existe. Criando tabela.");
                $this->createTablesWithoutFKs([$tableName => $schema]);
            }
            
            $existingColumns = $this->getExistingColumns($tableName);
            $this->backupAndDropOldColumns($tableName, $schema, $existingColumns);
            $this->addNewColumns($tableName, $schema, $existingColumns);
            
            // Restore constraints after modifying columns
            $this->restoreConstraints($tableName);
            
        } catch ( Exception $e ) {
            $this->logger->error("Erro ao atualizar o esquema de banco de dados para o modelo: $modelClass - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function dropForeignKeyConstraints(string $tableName, string $columnName): void
    {
        // Drop foreign keys in the referencing tables
        $referencingKeys = DB::select(
            'SELECT constraint_name, table_name, column_name FROM information_schema.key_column_usage
             WHERE referenced_table_name = ? AND referenced_column_name = ? AND constraint_schema = ?',
            [$tableName, $columnName, config('database.connections.mysql.database')],
        );
        
        foreach ($referencingKeys as $key) {
            Schema::table(
                $key->table_name,
                function (Blueprint $table) use ($key)
                {
                    $table->dropForeign([$key->column_name]);
                },
            );
        }
        
        // Drop foreign keys in the current table
        $foreignKeys = DB::select(
            'SELECT constraint_name, column_name FROM information_schema.key_column_usage
             WHERE table_name = ? AND column_name = ? AND constraint_schema = ?',
            [$tableName, $columnName, config('database.connections.mysql.database')],
        );
        
        foreach ($foreignKeys as $foreignKey) {
            Schema::table(
                $tableName,
                function (Blueprint $table) use ($foreignKey, $tableName, $columnName)
                {
                    if ($foreignKey->constraint_name === 'PRIMARY') {
                        $primaryKeys = DB::select(
                            'SELECT column_name FROM information_schema.key_column_usage
                             WHERE constraint_name = ? AND table_name = ? AND constraint_schema = ?',
                            [$foreignKey->constraint_name, $tableName, config('database.connections.mysql.database')],
                        );
                        
                        if (count($primaryKeys) > 1) {
                            // Primary key is composite, so we need to drop the entire key
                            $table->dropPrimary([$columnName]);
                        }
                        else {
                            // Single column primary key with AUTO_INCREMENT
                            if ($this->isAutoIncrementColumn($tableName, $columnName)) {
                                
                                try {
                                    DB::statement("ALTER TABLE `$tableName` MODIFY `$columnName` bigint unsigned");
                                } catch ( Exception $e ) {
                                
                                }
                                
                            }
                            $table->dropPrimary();
                        }
                    }
                    else {
                        $constraint = str_replace([$table->getTable() . '_', '_foreign'], '', $foreignKey->constraint_name);
                        $table->dropForeign($constraint);
                    }
                },
            );
        }
        
        // Drop unique constraints if any
        $uniqueConstraints = DB::select(
            'SELECT index_name FROM information_schema.statistics
             WHERE table_name = ? AND column_name = ? AND non_unique = 0 AND table_schema = ?',
            [$tableName, $columnName, config('database.connections.mysql.database')],
        );
        
        foreach ($uniqueConstraints as $uniqueConstraint) {
            Schema::table(
                $tableName,
                function (Blueprint $table) use ($uniqueConstraint)
                {
                    $table->dropUnique($uniqueConstraint->index_name);
                },
            );
        }
    }
    
    private function isAutoIncrementColumn(string $tableName, string $columnName): bool
    {
        $column = DB::selectOne(
            'SHOW COLUMNS FROM ' . $tableName . ' WHERE Field = ?',
            [$columnName],
        );
        return strpos($column->Extra, 'auto_increment') !== FALSE;
    }
    
    private function buildCompositeUniqueConstraints(array $schema): array
    {
        $uniqueGroups = [];
        $currentGroup = [];
        
        foreach ($schema as $column => $definition) {
            if (isset($definition[ ESchemaKey::UNIQUE ]) && $definition[ ESchemaKey::UNIQUE ] === TRUE) {
                $currentGroup[] = $column;
            }
        }
        
        if (!empty($currentGroup)) {
            $uniqueGroups[] = $currentGroup;
        }
        
        return $uniqueGroups;
    }
    
    private function getColumnPositions(array $schema, array $orderedColumns): array
    {
        $columnPositions = [];
        foreach ($orderedColumns as $column) {
            if (isset($schema[ $column ][ ESchemaKey::POSITION ])) {
                $columnPositions[ $column ] = $schema[ $column ][ ESchemaKey::POSITION ];
            }
        }
        return $columnPositions;
    }
    
    private function moveIdColumnToFirstPosition(array $columnPositions): array
    {
        if (isset($columnPositions['id'])) {
            unset($columnPositions['id']);
            $columnPositions = ['id' => 0] + $columnPositions;
        }
        return $columnPositions;
    }
    
    private function buildAlterColumnSQL(string $tableName, string $column, string $definitionString, ?string $previousColumn): array
    {
        $alterColumnSQL = [];
        
        if ($column === 'id') {
            $columnData = DB::selectOne(
                'SELECT *
            FROM information_schema.columns
            WHERE table_schema = ?
              AND table_name = ?
              AND column_name = ?',
                [config('database.connections.mysql.database'), $tableName, 'id'],
            );
            
            // Verificar se a coluna já é chave primária e auto incremento
            if ($columnData->COLUMN_KEY !== 'PRI' || !str_contains($columnData->EXTRA, 'auto_increment')) {
                $alterColumnSQL[] = "ALTER TABLE $tableName MODIFY id INT UNSIGNED NOT NULL";                      // Corrigir o tipo da coluna
                $alterColumnSQL[] = "ALTER TABLE $tableName ADD PRIMARY KEY (id)";                                 // Adicionar chave primária
                $alterColumnSQL[] = "ALTER TABLE $tableName MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST"; // Tornar auto incremento
            }
            else {
                $this->logger->log('A coluna id já é chave primária AUTO_INCREMENT. Nenhuma alteração necessária.');
            }
        }
        else {
            $alterColumnSQL[] = "ALTER TABLE $tableName MODIFY $column $definitionString" . ($previousColumn ? " AFTER $previousColumn" : '');
        }
        return $alterColumnSQL;
    }
    
    private function dropConstraintsTemporarily(string $tableName, string $column): void
    {
        // Drop primary key if it exists
        $primaryKey = DB::selectOne(
            'SELECT constraint_name FROM information_schema.key_column_usage
    WHERE table_name = ? AND column_name = ? AND constraint_schema = ? AND constraint_name = "PRIMARY"',
            [$tableName, $column, config('database.connections.mysql.database')],
        );
        
        if ($primaryKey) {
            // Check if column has AUTO_INCREMENT attribute
            $columnDetails = DB::selectOne("SHOW COLUMNS FROM `$tableName` WHERE Field = ?", [$column]);
            
            // If it has, remove AUTO_INCREMENT attribute first
            if (strpos($columnDetails->Extra, 'auto_increment') !== FALSE) {
                
                try {
                    DB::statement("ALTER TABLE `$tableName` MODIFY `$column` bigint unsigned");
                } catch ( Exception $e ) {
                
                }
                
            }
            
            // Then drop the primary key
            $this->logger->log("Removendo a chave primária na coluna $column na tabela $tableName");
            
            try {
                DB::statement("ALTER TABLE `$tableName` DROP PRIMARY KEY");
            } catch ( Exception $e ) {
            
            }
        }
        
        // Drop foreign key if it exists
        $foreignKey = DB::selectOne(
            'SELECT constraint_name FROM information_schema.key_column_usage
    WHERE table_name = ? AND column_name = ? AND constraint_schema = ?',
            [$tableName, $column, config('database.connections.mysql.database')],
        );
        
        if ($foreignKey && $foreignKey->constraint_name !== 'PRIMARY') {
            $this->logger->log("Removendo chave estrangeira {$foreignKey->constraint_name} na coluna $column na tabela $tableName");
            
            try {
                DB::statement("ALTER TABLE `$tableName` DROP FOREIGN KEY `{$foreignKey->constraint_name}`");
            } catch ( Exception $e ) {
            
            }
        }
        
        // Drop unique key if it exists
        $uniqueKey = DB::selectOne(
            'SELECT constraint_name FROM information_schema.table_constraints
    WHERE table_name = ? AND constraint_type = "UNIQUE" AND constraint_schema = ?',
            [$tableName, config('database.connections.mysql.database')],
        );
        
        if ($uniqueKey) {
            $this->logger->log("Removendo chave única {$uniqueKey->constraint_name} na coluna $column na tabela $tableName");
            try {
                DB::statement("ALTER TABLE `$tableName` DROP INDEX `{$uniqueKey->constraint_name}`");
            } catch ( Exception $e ) {
            
            }
        }
        
        // Drop index if it exists
        $indexKey = DB::selectOne(
            'SELECT index_name FROM information_schema.statistics
    WHERE table_name = ? AND column_name = ? AND table_schema = ?',
            [$tableName, $column, config('database.connections.mysql.database')],
        );
        
        if ($indexKey) {
            $this->logger->log("Removendo índice {$indexKey->index_name} na coluna $column na tabela $tableName");
            try {
                DB::statement("ALTER TABLE `$tableName` DROP INDEX `{$indexKey->index_name}`");
            } catch ( Exception $e ) {
            
            }
            
        }
    }
    
    private function restoreConstraints(string $tableName): void
    {
        // Fetch all constraints for the table from information_schema
        $constraints = DB::select(
            '
        SELECT kcu.constraint_name, tc.constraint_type, kcu.referenced_table_name, kcu.column_name
        FROM information_schema.table_constraints AS tc
        JOIN information_schema.key_column_usage AS kcu
          ON tc.constraint_name = kcu.constraint_name AND tc.table_name = kcu.table_name
        WHERE tc.table_schema = ? AND tc.table_name = ?
    ',
            [config('database.connections.mysql.database'), $tableName],
        );
        
        $this->logger->log("Restoring constraints for table: $tableName");
        
        // Drop existing constraints (to avoid conflicts)
        foreach ($constraints as $constraint) {
            $constraintName = $constraint->constraint_name;
            $this->logger->log("Processing constraint: $constraintName (type: {$constraint->constraint_type})");
            
            Schema::disableForeignKeyConstraints();
            
            if ($constraint->constraint_type === 'PRIMARY KEY') {
                // Check if primary key actually exists in the table
                $pkExists = Schema::hasColumn($tableName, 'id'); // Assuming 'id' is your primary key column
                if ($pkExists) {
                    $pkColumn = $constraint->column_name;
                    
                    // Check if the column has AUTO_INCREMENT attribute
                    $columnDetails = DB::selectOne("SHOW COLUMNS FROM `$tableName` WHERE Field = ?", [$pkColumn]);
                    
                    if (strpos($columnDetails->Extra, 'auto_increment') !== FALSE) {
                        $this->logger->log("Removing AUTO_INCREMENT from primary key column: $pkColumn");
                        try {
                            DB::statement("ALTER TABLE `$tableName` MODIFY `$pkColumn` bigint unsigned");
                        } catch ( Exception $e ) {
                        
                        }
                        
                    }
                    
                    $this->logger->log('Dropping primary key constraint');
                    try {
                        DB::statement("ALTER TABLE `{$tableName}` DROP PRIMARY KEY");
                    } catch ( Exception $e ) {
                    
                    }
                }
                else {
                    $this->logger->log('Primary key does not exist in the table. Skipping drop.');
                }
            }
            elseif ($constraint->constraint_type === 'UNIQUE') {
                // Drop UNIQUE constraint using its index name (same as constraint name in this case)
                $this->logger->log("Dropping UNIQUE constraint: $constraintName");
                try {
                    DB::statement("ALTER TABLE `{$tableName}` DROP INDEX `{$constraintName}`");
                } catch ( Exception $e ) {
                
                }
                
            }
            else {
                $this->logger->log("Dropping constraint: $constraintName");
                try {
                    DB::statement("ALTER TABLE `{$tableName}` DROP CONSTRAINT `{$constraintName}`");
                } catch ( Exception $e ) {
                
                }
                
            }
            
            Schema::enableForeignKeyConstraints();
        }
        
        // Re-create constraints based on their type
        foreach ($constraints as $constraint) {
            $constraintName = $constraint->constraint_name;
            $constraintType = $constraint->constraint_type;
            $referencedTable = $constraint->referenced_table_name; // Use the correct column name
            
            switch ( $constraintType ) {
                case 'PRIMARY KEY':
                    $columns = $this->getConstraintColumns($tableName, $constraintName);
                    try {
                        DB::statement("ALTER TABLE `$tableName` ADD PRIMARY KEY (`id`);");
                        //DB::statement("ALTER TABLE `{$tableName}` ADD CONSTRAINT `{$constraintName}` PRIMARY KEY (`" . implode('`, `', $columns) . '`)');
                    } catch ( Exception $e ) {
                    
                    }
                
                break;
                case 'FOREIGN KEY':
                    $columns = $this->getConstraintColumns($tableName, $constraintName);
                    $referencedColumns = $this->getReferencedColumns($tableName, $constraintName);
                    
                    try {
                        DB::statement(
                            "ALTER TABLE `{$tableName}` ADD CONSTRAINT `{$constraintName}` FOREIGN KEY (`" .
                            implode('`, `', $columns) .
                            "`) REFERENCES `{$referencedTable}` (`" .
                            implode('`, `', $referencedColumns) .
                            '`)',
                        );
                    } catch ( Exception $e ) {
                    
                    }
                break;
                case 'UNIQUE':
                    $columns = $this->getConstraintColumns($tableName, $constraintName);
                    
                    
                    try {
                        DB::statement("ALTER TABLE `{$tableName}` ADD CONSTRAINT `{$constraintName}` UNIQUE (`" . implode('`, `', $columns) . '`)');
                    } catch ( Exception $e ) {
                    
                    }
                break;
                // Handle other constraint types (CHECK, etc.) if needed
            }
        }
    }
    
    // Helper function to get referenced columns for foreign keys
    private function getReferencedColumns(string $tableName, string $constraintName): array
    {
        return collect(
            DB::select(
                '
            SELECT referenced_column_name
            FROM information_schema.key_column_usage
            WHERE table_schema = ? AND table_name = ? AND constraint_name = ?
        ',
                [config('database.connections.mysql.database'), $tableName, $constraintName],
            ),
        )->pluck('REFERENCED_COLUMN_NAME')->toArray();
    }

// Helper to fetch columns associated with a constraint
    private function getConstraintColumns(string $tableName, string $constraintName): array
    {
        return collect(
            DB::select(
                '
            SELECT column_name
            FROM information_schema.key_column_usage
            WHERE table_schema = ? AND table_name = ? AND constraint_name = ?
',
                [config('database.connections.mysql.database'), $tableName, $constraintName],
            ),
        )->pluck('column_name')->toArray();
        
        
    }
    
    private function createTablesWithoutFKs(array $schemas): void
    {
        foreach ($schemas as $tableName => $schema) {
            Schema::create(
                $tableName,
                function (Blueprint $table) use ($schema)
                {
                    $definitionBuilder = new SchemaDefinitionBuilder();
                    foreach ($schema as $column => $definition) {
                        if (isset($definition[ ESchemaKey::ON ])) {
                            continue; // Ignorar colunas com FKs
                        }
                        $definitionString = $definitionBuilder->buildColumnDefinition($definition);
                        
                        // Verificar se a coluna é a chave primária e adicionar explicitamente
                        if (
                            isset($definition[ ESchemaKey::PRIMARY_KEY ]) &&
                            $definition[ ESchemaKey::PRIMARY_KEY ] &&
                            isset($definition[ ESchemaKey::AUTO_INCREMENT ]) &&
                            $definition[ ESchemaKey::AUTO_INCREMENT ]
                        ) {
                            $this->logger->log("Adicionando a coluna $column como chave primária AUTO_INCREMENT na tabela {$table->getTable()}");
                            $table->unsignedInteger($column)->autoIncrement()->primary();//->first();
                        }
                        else {
                            $this->columnManager->addColumn($table, $column, $definitionString, $definition);
                        }
                    }
                    // Adicionar chaves únicas compostas
                    $uniqueConstraints = $this->buildCompositeUniqueConstraints($schema);
                    $this->columnManager->addUniqueConstraints($table, $uniqueConstraints);
                },
            );
        }
    }
    
    private function dropUniqueConstraints(string $tableName, array $uniqueConstraints): void
    {
        if (!empty($uniqueConstraints)) {
            $schemaName = config('database.connections.mysql.database');
            foreach ($uniqueConstraints as $constraintColumns) {
                $constraintName = DB::table('information_schema.STATISTICS')
                                    ->select('INDEX_NAME')
                                    ->where('TABLE_SCHEMA', $schemaName)
                                    ->where('TABLE_NAME', $tableName)
                                    ->where('NON_UNIQUE', 0)
                                    ->whereIn('COLUMN_NAME', $constraintColumns)
                                    ->groupBy('INDEX_NAME')
                                    ->havingRaw('COUNT(*) = ?', [count($constraintColumns)])
                                    ->pluck('INDEX_NAME')
                                    ->first();
                if ($constraintName) {
                    $this->logger->log("Removendo a chave única: $constraintName nas colunas: " . implode(',', $constraintColumns));
                    DB::statement("ALTER TABLE `$tableName` DROP INDEX `$constraintName`");
                }
                else {
                    $this->logger->log('Nenhuma chave única encontrada para as colunas: ' . implode(',', $constraintColumns));
                }
            }
        }
    }
    
    private function restoreUniqueConstraints(string $tableName, array $uniqueConstraints): void
    {
        if (!empty($uniqueConstraints)) {
            Schema::table(
                $tableName,
                function (Blueprint $table) use ($uniqueConstraints)
                {
                    foreach ($uniqueConstraints as $constraintColumns) {
                        $this->logger->log('Adicionando novamente a chave única nas colunas: ' . implode(',', $constraintColumns));
                        $table->unique($constraintColumns);
                    }
                },
            );
        }
    }
    
    private function shouldUpdateSchema(string $modelClass, array $traits): bool
    {
        if (defined("$modelClass::SCHEME_SYNC_ACTIVED") && !$modelClass::SCHEME_SYNC_ACTIVED) {
            $this->logger->log("Sincronização desativada para o modelo: $modelClass");
            return FALSE;
        }
        
        return $this->usesSchemeTrait($traits);
    }
    
    private function usesSchemeTrait(array $traits): bool
    {
        foreach ($traits as $trait) {
            if (Str::endsWith($trait, 'SchemeTrait')) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    private function getExistingColumns(string $tableName): array
    {
        try {
            $this->ensureTableExists($tableName);
            
            $this->logger->log("Buscando colunas existentes para a tabela: $tableName");
            $columns = DB::select("SHOW COLUMNS FROM $tableName");
            if (empty($columns)) {
                throw new Exception("Nenhuma coluna encontrada para a tabela: $tableName");
            }
            return array_map(
                static function ($column)
                {
                    return $column->Field;
                },
                $columns,
            );
        } catch ( Exception $e ) {
            $this->logger->error("Erro ao buscar colunas existentes para a tabela: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function backupAndDropOldColumns(string $tableName, array $schema, array $existingColumns): void
    {
        try {
            $this->logger->log("Fazendo backup e removendo colunas antigas para a tabela: $tableName");
            $schemaColumns = array_keys($schema);
            $columnsToDrop = array_diff($existingColumns, $schemaColumns);
            
            foreach ($columnsToDrop as $column) {
                // Backup column data
                $this->columnBackup->backupColumnData($tableName, $column);
                
                // Check if the column is used in any foreign keys
                $foreignKeys = DB::select(
                    'SELECT constraint_name, table_name FROM information_schema.key_column_usage
             WHERE referenced_table_name = ? AND referenced_column_name = ? AND table_schema = ?',
                    [$tableName, $column, config('database.connections.mysql.database')],
                );
                
                // Drop foreign keys from other tables that reference this column
                foreach ($foreignKeys as $foreignKey) {
                    Schema::table(
                        $foreignKey->table_name,
                        function (Blueprint $table) use ($foreignKey)
                        {
                            $table->dropForeign([$foreignKey->constraint_name]);
                        },
                    );
                }
                
                // Check if the column is a foreign key in the current table
                if ($this->columnManager->hasForeignKey($tableName, $column)) {
                    $this->columnManager->dropForeignKey($tableName, $column);
                }
                
                // Drop the column
                Schema::table(
                    $tableName,
                    function (Blueprint $table) use ($column)
                    {
                        $table->dropColumn($column);
                    },
                );
            }
        } catch ( Exception $e ) {
            $this->logger->error("Erro ao fazer backup e remover colunas antigas para a tabela: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function addNewColumns(string $tableName, array $schema, array $existingColumns): void
    {
        try {
            $this->logger->log("Adicionando novas colunas para a tabela: $tableName");
            
            foreach ($schema as $column => $definition) {
                if (!in_array($column, $existingColumns)) {
                    $definitionString = (new SchemaDefinitionBuilder())->buildColumnDefinition($definition);
                    $afterColumn = $definition[ ESchemaKey::AFTER ] ?? NULL;
                    
                    try {
                        Schema::table(
                            $tableName,
                            function (Blueprint $table) use ($column, $definitionString, $definition, $afterColumn)
                            {
                                $this->columnManager->addColumn($table, $column, $definitionString, $definition, $afterColumn);
                            },
                        );
                    } catch (\Exception $e){}
                    // Restore column data if there's a backup
                    $this->columnManager->restoreColumnDataIfNeeded($tableName, $column);
                }
            }
            
            // Adicionar chaves estrangeiras após criar todas as colunas
            $this->columnManager->addForeignKeysToTables([$tableName => $schema]);
            
        } catch ( Exception $e ) {
            $this->logger->error("Erro ao adicionar novas colunas para a tabela: $tableName - " . $e->getMessage());
            throw $e;
        }
    }
    
    private function ensureTableExists(string $tableName): void
    {
        if (!Schema::hasTable($tableName)) {
            $this->logger->log("Tabela $tableName não existe. Criando tabela.");
            Schema::create(
                $tableName,
                function (Blueprint $table)
                {
                    $table->increments('id');
                },
            );
        }
    }
    
    private function findModelByTableName(string $tableName): ?string
    {
        $modelPaths = [
            app_path('Models'),
            base_path('Modules/*/app/Models'),
        ];
        
        foreach ($modelPaths as $path) {
            foreach (glob($path . '/*.php') as $file) {
                $modelClass = $this->getClassFromFile($file);
                if ($modelClass && Str::plural(Str::snake(class_basename($modelClass))) === $tableName) {
                    return $modelClass;
                }
            }
        }
        
        return NULL;
    }
    
    private function getClassFromFile(string $filePath): ?string
    {
        $fileParts = explode('/', $filePath);
        $model = str_replace('.php', '', $fileParts[ count($fileParts) - 1 ]);
        
        return $this->getNamespaceFromFile($filePath) . '\\' . $model;
    }
    
    private function getNamespaceFromFile($filePath)
    {
        $fileContent = file_get_contents($filePath);
        $namespaceRegex = '/namespace\s+([\w\\\]+);/';
        preg_match($namespaceRegex, $fileContent, $matches);
        return $matches[1] ?? NULL;
    }
}
