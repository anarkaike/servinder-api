<?php
// ColumnBackup.php

namespace App\ModelSchemas\Commands\Drivers;

use App\ModelSchemas\Commands\Contracts\ColumnBackupInterface;
use App\Helpers\Logger;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ColumnBackup implements ColumnBackupInterface
{
    private Logger $logger;
    
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    public function backupColumnData(string $tableName, string $column): void
    {
        try {
            $this->logBackupStart($tableName, $column);
            $columnData = $this->fetchColumnData($tableName, $column);
            $this->ensureTablesInfosTableExists();
            $this->storeBackupData($tableName, $column, $columnData);
        } catch (Exception $exception) {
            $this->logBackupError($tableName, $column, $exception);
            throw $exception;
        }
    }
    
    protected function logBackupStart(string $tableName, string $column): void
    {
        $this->logger->log("Realizando backup de dados da coluna: $column na tabela: $tableName");
    }
    
    protected function fetchColumnData(string $tableName, string $column): string
    {
        return DB::table($tableName)->pluck($column, 'id')->toJson();
    }
    
    public function ensureTablesInfosTableExists(): void
    {
        try {
            if (!Schema::hasTable('tables_infos')) {
                $this->logger->log('Creating tables_infos table');
                Schema::create(
                    'tables_infos',
                    function (Blueprint $table) {
                        $table->bigIncrements('id');
                        $table->string('table_name');
                        $table->string('column_name')->nullable();
                        $table->enum('type', ['backup', 'infos', 'others']);
                        $table->json('data');
                        $table->timestamps();
                    }
                );
                $this->logger->log('tables_infos table created successfully');
            } else {
                $this->logger->log('tables_infos table already exists');
            }
        } catch (Exception $exception) {
            $this->logger->error('Error creating tables_infos table: ' . $exception->getMessage());
            throw $exception;
        }
    }
    
    public function storeBackupData(string $tableName, string $column, string $data): void
    {
        try {
            DB::table('tables_infos')->insert(
                [
                    'table_name'  => $tableName,
                    'column_name' => $column,
                    'type'        => 'backup',
                    'data'        => $data,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
            $this->logger->log("Backup data stored for column: $column in table: $tableName");
        } catch (Exception $exception) {
            $this->logger->error('Error storing backup data: ' . $exception->getMessage());
            throw $exception;
        }
    }
    
    protected function logBackupError(string $tableName, string $column, Exception $exception): void
    {
        $this->logger->error("Erro ao realizar backup de dados da coluna: $column na tabela: $tableName - " . $exception->getMessage());
    }
}
