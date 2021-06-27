<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

final class SauceValidator extends GenericValidator
{
    public const REQUIRED_VARS = ["name", "price"];

    protected const ERRORS = ["missing_property" => "missing sauce property: %s"];

    /**
     * @inheritDoc
     */
    public function validate(array $newProduct): bool
    {
        parent::validate($newProduct);
    }
}