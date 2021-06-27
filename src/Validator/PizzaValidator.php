<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

final class PizzaValidator extends GenericValidator
{
    public const REQUIRED_VARS = ["name", "ingredients", "price"];

    protected const ERRORS = ["missing_property" => "missing pizza property: %s", "nonexistent_ingredient" => "this ingredient don't exists: %s"];

    /**
     * @param array $newProduct
     * @param array $existingIngredients
     * @return array
     */
    public function validate(array $newProduct, array $existingIngredients = []): bool
    {
        parent::validate($newProduct);

        $result = $this->areIngredientsExist($newProduct['ingredients'], function ($ingredientUuid) use ($existingIngredients) {
            return in_array($ingredientUuid, $existingIngredients, true);
        });

        if(!empty($result)) {
            throw new \RuntimeException(sprintf(self::ERRORS['nonexistent_ingredient'], implode(",", $result)));     // error - nonexistent element
        }

        return true;
    }

    private function areIngredientsExist(array $ingredients, callable $callback): array
    {
        if(empty($ingredients)) {
            return array();
        }

        $result = array_filter($ingredients, $callback);

        return array_diff($ingredients, $result);
    }
}
