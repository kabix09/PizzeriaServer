<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\GenericRepository;
use Pizzeria\Repository\IngredientRepository;
use Pizzeria\Validator\IngredientValidator;

final class Ingredient extends GenericApi
{

    public function __construct(DbConnection $dbConnection)
    {
        parent::__construct($dbConnection, new IngredientRepository($dbConnection), new IngredientValidator());
    }

    public function post(array $newElement): string
    {
        parent::post($newElement);

        $this->validator->validate($newElement);    // if not validate - throw exception

        /** @var string $ingredientName */
        $ingredientName = $newElement["id"];

        $this->repository->create(
            $ingredientName,
            array_slice($newElement, 1, count($newElement)-1,  true)
        );

        return json_encode($this->repository->getByName($ingredientName));
    }
}