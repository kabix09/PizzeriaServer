<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\GenericRepository;
use Pizzeria\Repository\IngredientRepository;
use Pizzeria\Validator\IngredientValidator;
use Pizzeria\Web\Request;

final class Ingredient extends GenericApi
{

    /**
     * Ingredient constructor.
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
    {
        parent::__construct($dbConnection, new IngredientRepository($dbConnection), new IngredientValidator());
    }

    /**
     * @param Request $request
     * @return string
     * @throws DatabaseException
     */
    public function post(Request $request): string
    {
        parent::post($request);

        $newElement = $request->getData();
        $this->validator->validate($newElement);    // if not validate - throw exception

        /** @var string $ingredientName */
        $ingredientName = $newElement["id"];

        $this->repository->create(
            $ingredientName,
            array_slice($newElement, 1, count($newElement)-1,  true)
        );

        return $this->repository->getByName($ingredientName);
    }
}