<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

interface IValidator
{
    public const REQUIRED_VARS = [];

    /**
     * @param array $newProduct
     * @return array
     */
    public function validate(array $newProduct): bool;
}