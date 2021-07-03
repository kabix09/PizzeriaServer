<?php
declare(strict_types=1);

namespace Pizzeria\Mapper;

interface IMapper
{

    /**
     * @param array $data
     * @param array $model
     * @return array
     */
    public static function buildObject(array $data, array $model): array;
}