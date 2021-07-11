<?php
declare(strict_types=1);

namespace Pizzeria\Validator\Models;

use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\PizzaRepository;
use Pizzeria\Validator\Elements\IngredientValidator;
use Pizzeria\Validator\PaidProductsValidator;
use Pizzeria\Validator\PaidModels\PizzaPriceValidator;

final class PizzaValidator extends PaidProductsValidator
{
    public const ELEMENTS_GROUP = 'pizzas';
    public const REQUIRED_VARS = ["name", "ingredients", "price"];

    public function __construct()
    {
        parent::__construct(
            new PizzaRepository(new DbConnection()),
            new PizzaPriceValidator()
        );

        $this->addDecoratedValidators(new IngredientValidator());
    }

    /**
     * @param array $newPizza
     * @param bool $isSchemaRequired
     * @return bool
     */
    public function validate(array $newPizza, bool $isSchemaRequired = true): bool
    {
        parent::validate($newPizza, $isSchemaRequired);

        // check order price only if it's existing model
        if($this->checkPriceFlag === true){
            $this->checkPrice($newPizza[self::REQUIRED_VARS[0]], $newPizza[self::REQUIRED_VARS[1]], $newPizza[self::REQUIRED_VARS[2]]);
        }

        return true;
    }
}