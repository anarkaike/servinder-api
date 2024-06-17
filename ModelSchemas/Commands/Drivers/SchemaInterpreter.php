<?php

namespace ModelSchemas\Commands\Drivers;

use App\Helpers\Logger;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use ModelSchemas\Commands\Contracts\SchemaInterpreterInterface;
use ModelSchemas\Enums\EColumnType;
use ModelSchemas\Enums\ESchemaKey;

class SchemaInterpreter implements SchemaInterpreterInterface
{
    private array $columnTypesWithLength = [
        EColumnType::CHAR,
        EColumnType::DECIMAL,
        EColumnType::DOUBLE,
        EColumnType::FLOAT,
        EColumnType::STRING,
        EColumnType::VARCHAR,
        EColumnType::SET,
        EColumnType::ENUM,
        EColumnType::UNSIGNED_DECIMAL,
        EColumnType::UUID,
    ];
    
    private array $typeMap = [
        EColumnType::BIG_INTEGER             => 'BIGINT',
        EColumnType::BINARY                  => 'BLOB',
        EColumnType::BOOLEAN                 => 'TINYINT(1)',
        EColumnType::CHAR                    => 'CHAR',
        EColumnType::DATE                    => 'DATE',
        EColumnType::DATE_TIME               => 'DATETIME',
        EColumnType::DATE_TIME_TZ            => 'DATETIME',
        EColumnType::DECIMAL                 => 'DECIMAL',
        EColumnType::DOUBLE                  => 'DOUBLE',
        EColumnType::ENUM                    => 'ENUM',
        EColumnType::FLOAT                   => 'FLOAT',
        EColumnType::GEOMETRY                => 'GEOMETRY',
        EColumnType::GEOMETRY_COLLECTION     => 'GEOMETRYCOLLECTION',
        EColumnType::INTEGER                 => 'INT',
        EColumnType::IP_ADDRESS              => 'VARCHAR(45)',
        EColumnType::JSON                    => 'JSON',
        EColumnType::JSONB                   => 'JSON',
        EColumnType::LINE_STRING             => 'LINESTRING',
        EColumnType::LONG_TEXT               => 'LONGTEXT',
        EColumnType::MAC_ADDRESS             => 'VARCHAR(17)',
        EColumnType::MEDIUM_INTEGER          => 'MEDIUMINT',
        EColumnType::MEDIUM_TEXT             => 'MEDIUMTEXT',
        EColumnType::MULTI_LINE_STRING       => 'MULTILINESTRING',
        EColumnType::MULTI_POINT             => 'MULTIPOINT',
        EColumnType::MULTI_POLYGON           => 'MULTIPOLYGON',
        EColumnType::POINT                   => 'POINT',
        EColumnType::POLYGON                 => 'POLYGON',
        EColumnType::REMEMBER_TOKEN          => 'VARCHAR(100)',
        EColumnType::SET                     => 'SET',
        EColumnType::SMALL_INTEGER           => 'SMALLINT',
        EColumnType::SOFT_DELETES            => 'TIMESTAMP',
        EColumnType::SOFT_DELETES_TZ         => 'TIMESTAMP',
        EColumnType::STRING                  => 'VARCHAR',
        EColumnType::TEXT                    => 'TEXT',
        EColumnType::TIME                    => 'TIME',
        EColumnType::TIME_TZ                 => 'TIME',
        EColumnType::TIMESTAMP               => 'TIMESTAMP',
        EColumnType::TIMESTAMP_TZ            => 'TIMESTAMP',
        EColumnType::TINY_INTEGER            => 'TINYINT',
        EColumnType::UNSIGNED_BIG_INTEGER    => 'BIGINT UNSIGNED',
        EColumnType::UNSIGNED_DECIMAL        => 'DECIMAL UNSIGNED',
        EColumnType::UNSIGNED_INTEGER        => 'INT UNSIGNED',
        EColumnType::UNSIGNED_MEDIUM_INTEGER => 'MEDIUMINT UNSIGNED',
        EColumnType::UNSIGNED_SMALL_INTEGER  => 'SMALLINT UNSIGNED',
        EColumnType::UNSIGNED_TINY_INTEGER   => 'TINYINT UNSIGNED',
        EColumnType::UUID                    => 'CHAR(36)',
        EColumnType::YEAR                    => 'YEAR',
    ];
    
    private Logger $logger;
    
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }
    
    public function getColumnType(string $type): string
    {
        return $this->typeMap[ $type ] ?? $type;
    }
    
    public function buildColumnDefinitionString(array $schemaDefinition): string
    {
        $type = $this->getColumnType($schemaDefinition[ ESchemaKey::TYPE ]);
        $length = $this->getColumnLength($type, $schemaDefinition);
        $nullability = $this->getColumnNullability($schemaDefinition);
        
        return "$type$length $nullability";
    }
    
    private function getColumnLength(string $type, array $schemaDefinition): string
    {
        if (isset($schemaDefinition[ ESchemaKey::LENGTH ]) && in_array(strtolower($type), $this->columnTypesWithLength)) {
            return "({$schemaDefinition[ESchemaKey::LENGTH]})";
        }
        return '';
    }
    
    private function getColumnNullability(array $schemaDefinition): string
    {
        return isset($schemaDefinition[ ESchemaKey::NOT_NULL ]) && $schemaDefinition[ ESchemaKey::NOT_NULL ] ? 'NOT NULL' : 'NULL';
    }
    
    public function applyColumnType(Blueprint $table, string $column, string $type, array $definition, ?string $afterColumn = NULL): void
    {
        $method = $this->getColumnMethod($type);
        if (method_exists($this, $method)) {
            $columnInstance = $this->$method($table, $column, $definition);
            if ($afterColumn) {
                $columnInstance->after($afterColumn);
            }
        }
        else {
            throw new Exception("Unsupported column type: $type");
        }
    }
    
    private function getColumnMethod(string $type): string
    {
        return 'apply' . ucfirst(strtolower($type)) . 'Type';
    }
    
    private function applyIncrementsType(Blueprint $table, string $column, array $definition)
    {
        return $table->increments($column);
    }
    
    private function applyBigIntegerType(Blueprint $table, string $column, array $definition)
    {
        return $table->bigInteger($column);
    }
    
    private function applyBinaryType(Blueprint $table, string $column, array $definition)
    {
        return $table->binary($column);
    }
    
    private function applyBooleanType(Blueprint $table, string $column, array $definition)
    {
        return $table->boolean($column);
    }
    
    private function applyCharType(Blueprint $table, string $column, array $definition)
    {
        return $table->char($column, $definition[ ESchemaKey::LENGTH ] ?? NULL);
    }
    
    private function applyDateType(Blueprint $table, string $column, array $definition)
    {
        return $table->date($column);
    }
    
    private function applyDateTimeType(Blueprint $table, string $column, array $definition)
    {
        return $table->dateTime($column);
    }
    
    private function applyDateTimeTzType(Blueprint $table, string $column, array $definition)
    {
        return $table->dateTimeTz($column);
    }
    
    private function applyDecimalType(Blueprint $table, string $column, array $definition)
    {
        return $table->decimal($column, $definition[ ESchemaKey::PRECISION ] ?? 8, $definition[ ESchemaKey::SCALE ] ?? 2);
    }
    
    private function applyDoubleType(Blueprint $table, string $column, array $definition)
    {
        return $table->double($column, $definition[ ESchemaKey::PRECISION ] ?? 8, $definition[ ESchemaKey::SCALE ] ?? 2);
    }
    
    private function applyEnumType(Blueprint $table, string $column, array $definition)
    {
        return $table->enum($column, $definition[ ESchemaKey::VALUES ] ?? []);
    }
    
    private function applyFloatType(Blueprint $table, string $column, array $definition)
    {
        return $table->float($column, $definition[ ESchemaKey::PRECISION ] ?? 8, $definition[ ESchemaKey::SCALE ] ?? 2);
    }
    
    private function applyForeignIdType(Blueprint $table, string $column, array $definition)
    {
        return $table->foreignId($column);
    }
    
    private function applyGeometryType(Blueprint $table, string $column, array $definition)
    {
        return $table->geometry($column);
    }
    
    private function applyGeometryCollectionType(Blueprint $table, string $column, array $definition)
    {
        return $table->geometryCollection($column);
    }
    
    private function applyIntegerType(Blueprint $table, string $column, array $definition)
    {
        return $table->integer($column);
    }
    
    private function applyIpAddressType(Blueprint $table, string $column, array $definition)
    {
        return $table->ipAddress($column);
    }
    
    private function applyJsonType(Blueprint $table, string $column, array $definition)
    {
        return $table->json($column);
    }
    
    private function applyJsonbType(Blueprint $table, string $column, array $definition)
    {
        return $table->jsonb($column);
    }
    
    private function applyLineStringType(Blueprint $table, string $column, array $definition)
    {
        return $table->lineString($column);
    }
    
    private function applyLongTextType(Blueprint $table, string $column, array $definition)
    {
        return $table->longText($column);
    }
    
    private function applyMacAddressType(Blueprint $table, string $column, array $definition)
    {
        return $table->macAddress($column);
    }
    
    private function applyMediumIntegerType(Blueprint $table, string $column, array $definition)
    {
        return $table->mediumInteger($column);
    }
    
    private function applyMediumTextType(Blueprint $table, string $column, array $definition)
    {
        return $table->mediumText($column);
    }
    
    private function applyMultiLineStringType(Blueprint $table, string $column, array $definition)
    {
        return $table->multiLineString($column);
    }
    
    private function applyMultiPointType(Blueprint $table, string $column, array $definition)
    {
        return $table->multiPoint($column);
    }
    
    private function applyMultiPolygonType(Blueprint $table, string $column, array $definition)
    {
        return $table->multiPolygon($column);
    }
    
    private function applyPointType(Blueprint $table, string $column, array $definition)
    {
        return $table->point($column);
    }
    
    private function applyPolygonType(Blueprint $table, string $column, array $definition)
    {
        return $table->polygon($column);
    }
    
    private function applyRememberTokenType(Blueprint $table, string $column, array $definition)
    {
        return $table->rememberToken();
    }
    
    private function applySetType(Blueprint $table, string $column, array $definition)
    {
        return $table->set($column, $definition[ ESchemaKey::VALUES ] ?? []);
    }
    
    private function applySmallIntegerType(Blueprint $table, string $column, array $definition)
    {
        return $table->smallInteger($column);
    }
    
    private function applySoftDeletesType(Blueprint $table, string $column, array $definition)
    {
        return $table->softDeletes($column);
    }
    
    private function applySoftDeletesTzType(Blueprint $table, string $column, array $definition)
    {
        return $table->softDeletesTz($column);
    }
    
    private function applyStringType(Blueprint $table, string $column, array $definition)
    {
        return $table->string($column, $definition[ ESchemaKey::LENGTH ] ?? 255);
    }
    
    private function applyTextType(Blueprint $table, string $column, array $definition)
    {
        return $table->text($column);
    }
    
    private function applyTimeType(Blueprint $table, string $column, array $definition)
    {
        return $table->time($column);
    }
    
    private function applyTimeTzType(Blueprint $table, string $column, array $definition)
    {
        return $table->timeTz($column);
    }
    
    private function applyTimestampType(Blueprint $table, string $column, array $definition)
    {
        return $table->timestamp($column);
    }
    
    private function applyTimestampTzType(Blueprint $table, string $column, array $definition)
    {
        return $table->timestampTz($column);
    }
    
    private function applyTinyIntegerType(Blueprint $table, string $column, array $definition)
    {
        return $table->tinyInteger($column);
    }
    
    private function applyUnsignedBigIntegerType(Blueprint $table, string $column, array $definition)
    {
        return $table->unsignedBigInteger($column);
    }
    
    private function applyUnsignedDecimalType(Blueprint $table, string $column, array $definition)
    {
        return $table->unsignedDecimal($column, $definition[ ESchemaKey::PRECISION ] ?? 8, $definition[ ESchemaKey::SCALE ] ?? 2);
    }
    
    private function applyUnsignedIntegerType(Blueprint $table, string $column, array $definition)
    {
        return $table->unsignedInteger($column);
    }
    
    private function applyUnsignedMediumIntegerType(Blueprint $table, string $column, array $definition)
    {
        return $table->unsignedMediumInteger($column);
    }
    
    private function applyUnsignedSmallIntegerType(Blueprint $table, string $column, array $definition)
    {
        return $table->unsignedSmallInteger($column);
    }
    
    private function applyUnsignedTinyIntegerType(Blueprint $table, string $column, array $definition)
    {
        return $table->unsignedTinyInteger($column);
    }
    
    private function applyUuidType(Blueprint $table, string $column, array $definition)
    {
        return $table->uuid($column);
    }
    
    private function applyYearType(Blueprint $table, string $column, array $definition)
    {
        return $table->year($column);
    }
}
