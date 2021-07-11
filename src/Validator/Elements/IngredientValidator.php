<?php
declare(strict_types=1);

namespace Pizzeria\Validator\Elements;

use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\IngredientRepository;
use Pizzeria\Validator\ElementValidator;

final class IngredientValidator extends ElementValidator
{
    public const ELEMENTS_GROUP = 'ingredients';

    public function __construct()
    {
        parent::__construct(
            new IngredientRepository(
                new DbConnection()
            )
        );
    }
}