<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

interface IValidator
{
    /**
     * @param array $newProduct
     * @param bool $isSchemaRequired
     * @return bool
     */
    public function validate(array $newProduct, bool $isSchemaRequired = true): bool;
}