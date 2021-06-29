<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\DatabaseException;
use Lcobucci\JWT\Validation\Validator;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Mapper\GenericMapper;
use Pizzeria\Repository\IngredientRepository;
use Pizzeria\Repository\PizzaRepository;
use Pizzeria\Validator\PizzaValidator;
use Pizzeria\Web\Request;
use Pizzeria\Web\Response;

final class Pizza extends GenericApi
{
    protected const ERRORS = [
        "missing_name" => "missing pizza name",
        "wrong_name" => "wrong pizza name - this pizza doesn't exist"
    ];

    /**
     * @var IngredientRepository
     */
    private $ingredientsRepository;

    /**
     * Pizza constructor.
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
    {
        parent::__construct($dbConnection, new PizzaRepository($dbConnection), new PizzaValidator());

        $this->ingredientsRepository = new IngredientRepository($dbConnection);
    }


    /* -------- TOOLS FOR ADMIN -------- */

    /**
     * Return one element
     *
     * @param Request $request
     * @return array
     * @throws DatabaseException
     */
    public function post(Request $request): array
    {
        parent::post($request);

        $newElement = $request->getData();
        $existingIngredients = $this->ingredientsRepository->getAll();
        $this->validator->validate($newElement, $existingIngredients);    // if not validate - throw exception

        /** @var string $pizzaName */
        $pizzaName = $request->getDataByKey(self::ID_FIELD);

        $this->repository->create(
            $pizzaName,
            array_slice($newElement, 1, count($newElement)-1,  true)
        );

        return $this->repository->getByName($pizzaName);
    }

}