<?php
declare(strict_types=1);

namespace Pizzeria\Mapper;

interface IMapper
{
    /**
     * Convert array to json's data to map complies with firebase structure
     *
     * @param array $object
     * @return array
     */
    public static function arrayToNoSqlMap(array $object): array;

    /**
     * Convert map returned from firebase to associated array
     *
     * @param array $noSqlMap
     * @param string $uuid
     * @return array
     */
    public static function noSqlMapToArray(array $noSqlMap, string $uuid): array;
}