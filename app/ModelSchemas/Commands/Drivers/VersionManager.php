<?php

namespace App\ModelSchemas\Commands\Drivers;

use App\ModelSchemas\Commands\Contracts\VersionManagerInterface;
use App\Helpers\Logger;
use Exception;
use Illuminate\Support\Facades\DB;

class VersionManager implements VersionManagerInterface
{
    private Logger $logger;
    
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    public function getCurrentVersion(string $tableName, string $columnName): ?int
    {
        try {
            return $this->fetchVersion($tableName, $columnName);
        } catch ( Exception $exception ) {
            $this->logger->error("Error fetching version for column: $columnName in table: $tableName - " . $exception->getMessage());
            throw $exception;
        }
    }
    
    public function updateVersion(string $tableName, string $columnName, int $version): void
    {
        try {
            $this->persistVersion($tableName, $columnName, $version);
        } catch ( Exception $exception ) {
            $this->logger->error("Error updating version for column: $columnName in table: $tableName - " . $exception->getMessage());
            throw $exception;
        }
    }
    
    private function fetchVersion(string $tableName, string $columnName): ?int
    {
        $version = DB::table('tables_infos')
                     ->where('table_name', $tableName)
                     ->where('column_name', $columnName)
                     ->where('type', 'infos')
                     ->value('data->version');
        
        return $version ? (int) $version : NULL;
    }
    
    private function persistVersion(string $tableName, string $columnName, int $version): void
    {
        DB::table('tables_infos')->updateOrInsert(
            ['table_name' => $tableName, 'column_name' => $columnName, 'type' => 'infos'],
            ['data' => json_encode(['version' => $version]), 'updated_at' => now()],
        );
    }
}
