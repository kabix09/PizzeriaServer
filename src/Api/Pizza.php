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

final class Pizza extends GenericApi
{
    protected const ERRORS = [
        "missing_name" => "missing pizza name",
        "wrong_name" => "wrong pizza name - this pizza doesn't exist"
    ];

    /**
     * @var PizzaRepository
     */
    private $pizzasRepository;

    /**
     * @var IngredientRepository
     */
    private $ingredientsRepository;

    public function __construct(DbConnection $dbConnection)
    {
        parent::__construct($dbConnection, new PizzaRepository($dbConnection), new PizzaValidator());

        $this->ingredientsRepository = new IngredientRepository($dbConnection);
    }


    /* -------- TOOLS FOR ADMIN -------- */

    /**
     * @param array $newElement
     * @return string
     * @throws DatabaseException
     */
    public function post(array $newElement): string
    {
        parent::post($newElement);

        $existingIngredients = $this->ingredientsRepository->getAll();
        $this->validator->validate($newElement, $existingIngredients);    // if not validate - throw exception

        /** @var string $pizzaName */
        $pizzaName = $newElement["id"];

        $this->repository->create(
            $pizzaName,
            array_slice($newElement, 1, count($newElement)-1,  true)
        );

        return json_encode($this->repository->getByName($pizzaName));
    }

}