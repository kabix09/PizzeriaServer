<?php
declare(strict_types=1);

namespace Pizzeria\Mapper;

class GenericMapper implements IMapper
{

    public static function arrayToNoSqlMap(array $object): array
    {
        // TODO: Implement arrayToNoSqlMap() method.
    }

    public static function noSqlMapToArray(array $noSqlMap, string $uuid): array
    {
        return array_merge(['id' => $uuid], $noSqlMap);
    }
}