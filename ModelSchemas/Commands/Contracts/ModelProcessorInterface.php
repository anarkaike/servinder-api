<?php

namespace ModelSchemas\Commands\Contracts;

/**
 * Interface para processadores de modelos
 *
 * Português
 * Esta interface define o contrato para classes que processam modelos.
 *
 * Espanhol
 * Esta interfaz define el contrato para clases que procesan modelos.
 *
 * Inglês
 * This interface defines the contract for classes that process models.
 *
 * Principais objetivos:
 * - Fornecer um padrão para processar modelos de dados.
 *
 * @author Júnio de Almeida Vitorino <anarkaike@gmail.com>
 */
interface ModelProcessorInterface
{
    /**
     * Processa modelos de dados a partir de um caminho especificado
     *
     * Português
     * Lê os modelos de dados a partir de um caminho especificado e realiza
     * alguma ação com eles.
     *
     * Espanhol
     * Lee los modelos de datos de un camino especificado y realiza
     * alguna acción con ellos.
     *
     * Inglês
     * Reads model data from a specified path and performs some action
     * with them.
     *
     * @param string $path O caminho onde os modelos de dados estão localizados
     *
     * @return void
     */
    public function processModels(string $path): void;
}
