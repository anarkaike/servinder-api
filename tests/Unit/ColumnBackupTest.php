<?php
// tests/Unit/ColumnBackupTest.php

namespace Tests\Unit;

use App\Helpers\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Mockery;
use ModelSchemas\Commands\Drivers\ColumnBackup;
use PHPUnit\Framework\TestCase;

class ColumnBackupTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function testBackupColumnData()
    {
        $logger = Mockery::mock(Logger::class);
        $logger->shouldReceive('log')->once()->with('Realizando backup de dados da coluna: column na tabela: table');
        $logger->shouldReceive('log')->once()->with('tables_infos table already exists');
        $logger->shouldReceive('log')->once()->with('Backup data stored for column: column in table: table');
        
        DB::shouldReceive('table->pluck->toJson')
          ->once()
          ->andReturn('{"1":"data1","2":"data2"}');
        
        Schema::shouldReceive('hasTable')
              ->once()
              ->with('tables_infos')
              ->andReturn(TRUE);
        
        DB::shouldReceive('table->insert')
          ->once();
        
        $columnBackup = new ColumnBackup($logger);
        $columnBackup->backupColumnData('table', 'column');
        
        // Adicionar asserção para verificar se nenhuma exceção foi lançada
        $this->assertTrue(TRUE);
    }
    
    public function testEnsureTablesInfosTableExistsCreatesTable()
    {
        $logger = Mockery::mock(Logger::class);
        $logger->shouldReceive('log')->once()->with('Creating tables_infos table');
        $logger->shouldReceive('log')->once()->with('tables_infos table created successfully');
        
        Schema::shouldReceive('hasTable')
              ->once()
              ->with('tables_infos')
              ->andReturn(FALSE);
        
        Schema::shouldReceive('create')
              ->once();
        
        $columnBackup = new ColumnBackup($logger);
        $columnBackup->ensureTablesInfosTableExists();
        
        // Adicionar asserção para verificar se nenhuma exceção foi lançada
        $this->assertTrue(TRUE);
    }
    
    public function testEnsureTablesInfosTableExistsTableAlreadyExists()
    {
        $logger = Mockery::mock(Logger::class);
        $logger->shouldReceive('log')->once()->with('tables_infos table already exists');
        
        Schema::shouldReceive('hasTable')
              ->once()
              ->with('tables_infos')
              ->andReturn(TRUE);
        
        $columnBackup = new ColumnBackup($logger);
        $columnBackup->ensureTablesInfosTableExists();
        
        // Adicionar asserção para verificar se nenhuma exceção foi lançada
        $this->assertTrue(TRUE);
    }
    
    public function testStoreBackupData()
    {
        $logger = Mockery::mock(Logger::class);
        $logger->shouldReceive('log')->once()->with('Backup data stored for column: column in table: table');
        
        DB::shouldReceive('table->insert')
          ->once();
        
        $columnBackup = new ColumnBackup($logger);
        $columnBackup->storeBackupData('table', 'column', '{"1":"data1","2":"data2"}');
        
        // Adicionar asserção para verificar se nenhuma exceção foi lançada
        $this->assertTrue(TRUE);
    }
}
