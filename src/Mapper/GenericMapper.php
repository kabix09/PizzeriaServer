<?php
declare(strict_types=1);

namespace Pizzeria\Mapper;

class GenericMapper implements IMapper
{
    public static function buildObject(array $data, array $model): array
    {
        $newObject = [];

        foreach ($model as $element) {
            if(isset($data[$element])) {
                $newObject[$element] = $data[$element];
            }
        }

        return $newObject;
    }
}
