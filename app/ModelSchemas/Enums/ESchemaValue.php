<?php

namespace App\ModelSchemas\Enums;

enum ESchemaValue
{
    const ON_CASCADE  = 'cascade';
    const ON_SET_NULL = 'set null';
    const ON_RESTRICT = 'restrict';
    const ON_NO_ACTON = 'no action';
    
}
