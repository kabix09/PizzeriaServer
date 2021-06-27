<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

final class IngredientValidator extends GenericValidator
{
    public const REQUIRED_VARS = ["name", "price"];

    protected const ERRORS = ["missing_property" => "missing ingredient property: %s"];

    /**
     * @inheritDoc
     */
    public function validate(array $newProduct): bool
    {
        parent::validate($newProduct);
    }
}