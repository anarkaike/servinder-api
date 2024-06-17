<?php

namespace ModelSchemas\Enums;

enum ESchemaKey
{
    const AUTO_INCREMENT   = 'auto_increment';
    const PRIMARY_KEY      = 'primary_key';
    const SYNC_PAUSED      = 'sync_paused';         // Schema does not manage (sinonimous of ONLY_DESCRIPTION)
    const AFTER            = 'after';               // Schema does not manage (sinonimous of ONLY_DESCRIPTION)
    const BEFORE           = 'before';              // Schema does not manage (sinonimous of ONLY_DESCRIPTION)
    const ONLY_DESCRIPTION = 'ONLY_DESCRIPTION';    // Schema does not manage (sinonimous of SYNC_PAUSED)
    const VERSIONED        = 'versioned';
    const POSITION         = 'position';
    const TYPE             = 'type';
    const LABEL            = 'label';
    const NOT_NULL         = 'not_null';
    const DESCRIPTION      = 'description';
    const LENGTH           = 'length';
    const UNIQUE           = 'unique';
    const NULLABLE         = 'nullable';
    const DEFAULT          = 'default';
    const REFERENCES       = 'references';
    
    const VERSION = 'VERSION';
    
    
    const ON            = 'on';
    const ON_TABLE      = 'table';
    const ON_FK         = 'table';
    const ON_REFERENCES = 'references';
    const ON_COLUMN     = 'references';
    const ON_DELETE     = 'on_delete';
    const ON_UPDATE     = 'on_delete';
    const ON_ALL        = 'on_all';
    const VALUES        = 'values';
    const PRECISION     = 'precision';
    const SCALE         = 'scale';
    
}
