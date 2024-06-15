<?php

namespace App\Providers;

use App\ModelSchemas\Commands\DbaCommand;
use Illuminate\Support\ServiceProvider;

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
                // Adicione outros comandos conforme necessário
            ],
        );
    }
}
