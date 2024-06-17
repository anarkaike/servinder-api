<?php

namespace ModelSchemas\Commands\Contracts;

/**
 * Interface para comandos de banco de dados (DBA)
 *
 * Português
 * Esta interface define o contrato para classes de comando de banco de dados.
 *
 * Espanhol
 * Esta interfaz define el contrato para las clases de comando de base de datos.
 *
 * Inglês
 * This interface defines the contract for database command classes.
 *
 * Principais objetivos:
 * - Definir um padrão de comandos para operações de banco de dados.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
interface DbaCommandInterface
{
    /**
     * Executa o comando de banco de dados
     *
     * Português
     * Executa a ação principal do comando de banco de dados.
     *
     * Espanhol
     * Ejecuta la acción principal del comando de base de datos.
     *
     * Inglês
     * Executes the main action of the database command.
     *
     * @return mixed
     */
    public function handle();
}
