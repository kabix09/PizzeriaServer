<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

abstract class GenericValidator implements IValidator
{
    /**
     * @param array $newProduct
     * @return bool
     */
    public function validate(array $newProduct): bool
    {
        $result = $this->areRequiredFieldsExists(function ($key, $value) use ($newProduct) {
            return array_key_exists($key, $newProduct);
        });

        if (!empty($result)) {
            throw new \RuntimeException(sprintf(static::ERRORS['missing_property'], implode(",", $result)));     // error - missing element
        }

        return true;
    }

    /**
     * Return difference between required fields and existing fields, when return empty array all necessary fields exists
     *
     * @param callable $callback
     * @return array
     */
    protected function areRequiredFieldsExists (callable $callback): array
    {
        $result = array_filter(static::REQUIRED_VARS, $callback , ARRAY_FILTER_USE_BOTH);

        return array_diff(static::REQUIRED_VARS, $result);
    }
}