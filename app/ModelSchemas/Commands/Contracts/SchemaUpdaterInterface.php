<?php

namespace App\ModelSchemas\Commands\Contracts;

/**
 * Interface para atualizadores de esquema
 *
 * Português
 * Esta interface define o contrato para classes que atualizam esquemas de banco de dados.
 *
 * Espanhol
 * Esta interfaz define el contrato para clases que actualizan esquemas de base de datos.
 *
 * Inglês
 * This interface defines the contract for classes that update database schemas.
 *
 * Principais objetivos:
 * - Fornecer um padrão para atualizar esquemas de banco de dados.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
interface SchemaUpdaterInterface
{
    /**
     * Atualiza o esquema de banco de dados a partir de uma classe de modelo
     *
     * Português
     * Atualiza o esquema de banco de dados correspondente à classe de modelo fornecida.
     *
     * Espanhol
     * Actualiza el esquema de base de datos correspondiente a la clase de modelo proporcionada.
     *
     * Inglês
     * Updates the corresponding database schema from the provided model class.
     *
     * @param string $modelClass A classe de modelo
     *
     * @return void
     */
    public function updateDatabaseSchema(string $modelClass): void;
}
