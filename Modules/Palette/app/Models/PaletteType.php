<?php

namespace Modules\Palette\app\Models;

/**
 * Tipo de Paleta representa o aspecto que mudara no model:
 *
 * Cores, Fontes, Tamanhos e Estilos (Por enquanto)
 *
 * Essa tabela armazena parametros relacionado ao tipo, como que campos mostrar
 */
class PaletteType extends Model
{
    use PaletteSchemeTrait;
}
