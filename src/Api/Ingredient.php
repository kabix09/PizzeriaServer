<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\IngredientRepository;
use Pizzeria\Validator\Elements\IngredientValidator;

final class Ingredient extends GenericApi
{
    public const SCHEMA = ['id', 'name', 'price'];

    /**
     * Ingredient constructor.
     */
    public function __construct()
    {
        parent::__construct(new IngredientRepository(new DbConnection()), new IngredientValidator());
    }
}
