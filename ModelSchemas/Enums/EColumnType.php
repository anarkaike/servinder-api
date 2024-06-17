<?php

namespace ModelSchemas\Enums;

enum EColumnType
{
    const PRIMARY_KEY             = 'primary_key';
    const INCREMENTS              = 'increments';
    const BIG_INTEGER             = 'bigInteger';
    const BINARY                  = 'binary';
    const BOOLEAN                 = 'boolean';
    const CHAR                    = 'char';
    const DATE                    = 'date';
    const DATE_TIME               = 'dateTime';
    const DATE_TIME_TZ            = 'dateTimeTz';
    const DECIMAL                 = 'decimal';
    const DOUBLE                  = 'double';
    const ENUM                    = 'enum';
    const FLOAT                   = 'float';
    const FOREIGN_ID              = 'foreignId';
    const GEOMETRY                = 'geometry';
    const GEOMETRY_COLLECTION     = 'geometryCollection';
    const INTEGER                 = 'integer';
    const IP_ADDRESS              = 'ipAddress';
    const JSON                    = 'json';
    const JSONB                   = 'jsonb';
    const LINE_STRING             = 'lineString';
    const LONG_TEXT               = 'longText';
    const MAC_ADDRESS             = 'macAddress';
    const MEDIUM_INTEGER          = 'mediumInteger';
    const MEDIUM_TEXT             = 'mediumText';
    const MORPHS                  = 'morphs';
    const MULTI_LINE_STRING       = 'multiLineString';
    const MULTI_POINT             = 'multiPoint';
    const MULTI_POLYGON           = 'multiPolygon';
    const NULLABLE_MORPHS         = 'nullableMorphs';
    const NULLABLE_TIMESTAMPS     = 'nullableTimestamps';
    const POINT                   = 'point';
    const POLYGON                 = 'polygon';
    const REMEMBER_TOKEN          = 'rememberToken';
    const SET                     = 'set';
    const SMALL_INTEGER           = 'smallInteger';
    const SOFT_DELETES            = 'softDeletes';
    const SOFT_DELETES_TZ         = 'softDeletesTz';
    const STRING                  = 'string';
    const TEXT                    = 'text';
    const TIME                    = 'time';
    const TIME_TZ                 = 'timeTz';
    const TIMESTAMP               = 'timestamp';
    const TIMESTAMP_TZ            = 'timestampTz';
    const TIMESTAMPS              = 'timestamps';
    const TINY_INTEGER            = 'tinyInteger';
    const UNSIGNED_BIG_INTEGER    = 'unsignedBigInteger';
    const UNSIGNED_DECIMAL        = 'unsignedDecimal';
    const UNSIGNED_INTEGER        = 'unsignedInteger';
    const UNSIGNED_MEDIUM_INTEGER = 'unsignedMediumInteger';
    const UNSIGNED_SMALL_INTEGER  = 'unsignedSmallInteger';
    const UNSIGNED_TINY_INTEGER   = 'unsignedTinyInteger';
    const UUID                    = 'uuid';
    const YEAR                    = 'year';
    const VARCHAR                 = 'varchar';
}
