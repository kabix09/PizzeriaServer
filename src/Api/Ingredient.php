<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\IngredientRepository;
use Pizzeria\Validator\Elements\IngredientValidator;
use Pizzeria\Web\Request;

final class Ingredient extends GenericApi
{

    /**
     * Ingredient constructor.
     */
    public function __construct()
    {
        parent::__construct(new IngredientRepository(new DbConnection()), new IngredientValidator());
    }

    /**
     * @param Request $request
     * @return array
     * @throws DatabaseException
     */
    public function post(Request $request): array
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