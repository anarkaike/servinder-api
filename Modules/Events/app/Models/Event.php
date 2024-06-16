<?php

namespace Modules\Events\app\Models;

class Event extends Model
{
    use EventSchemeTrait;
    
    const SCHEME_SYNC_ACTIVED      = TRUE;
    const SCHEME_SYNC_EDIT_ACTIVED = TRUE;
    
    protected $table = 'events'; // Certifique-se de que o nome da tabela está correto
    
}
