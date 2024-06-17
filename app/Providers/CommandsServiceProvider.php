<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ModelSchemas\Commands\DbaCommand;
use ModelSchemas\Commands\GenerateClassesCommand;

class CommandsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Carrega comandos do diretório específico do módulo
        $this->loadCommands();
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
    
    /**
     * Carregar os comandos do módulo
     *
     * @return void
     */
    protected function loadCommands()
    {
        // Ajuste o caminho conforme necessário
        $this->commands(
            [
                DbaCommand::class,
                GenerateClassesCommand::class,
                // Adicione outros comandos conforme necessário
            ],
        );
    }
}
