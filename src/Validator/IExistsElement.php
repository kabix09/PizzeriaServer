<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

/**
 * Interface IExistsElement - implements composite pattern
 * @package Pizzeria\Validator
 */
interface IExistsElement
{
    /**
     * @param array $elements
     * @param bool $areElementsRequired
     * @return bool
     */
    public function areElementsExist(array $elements, bool $areElementsRequired = false): bool;
}