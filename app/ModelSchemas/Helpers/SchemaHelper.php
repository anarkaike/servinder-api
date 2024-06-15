<?php

namespace App\ModelSchemas\Helpers;

use Illuminate\Support\Str;
use function strrpos;
use function strtolower;
use function substr;

class SchemaHelper
{
    static public function table($className, $pluralize = TRUE)
    {
        $lastBackslashPos = strrpos($className, "\\");
        
        $lastWord = $className;
        if ($lastBackslashPos !== FALSE) {
            $lastWord = substr($className, $lastBackslashPos + 1);
        }
        
        return strtolower(Str::plural($lastWord));
    }
}
