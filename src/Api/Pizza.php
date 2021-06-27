<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Mapper\GenericMapper;
use Pizzeria\Repository\IngredientRepository;
use Pizzeria\Repository\PizzaRepository;
use Pizzeria\Validator\PizzaValidator;

class Pizza
{
    /**
     * @var Database
     */
    private $connection;
    
    /**
     * @var PizzaRepository
     */
    private $pizzasRepository;

    /**
     * @var PizzaValidator
     */
    private $validator;

    private const ERRORS = [
        "missing_name" => "missing pizza name",
        "wrong_name" => "wrong pizza name - this pizza doesn't exist"
    ];

    public function __construct(DbConnection $dbConnection)
    {
        $this->connection = $dbConnection->getFirebase();
        
        $this->pizzasRepository = new PizzaRepository($dbConnection);
        $this->ingredientsRepository = new IngredientRepository($dbConnection);

        $this->validator = new PizzaValidator();
    }

    /**
     * @return array
     */
    public function getAll(): string
    {
        $pizzas = $this->pizzasRepository->getAll();

        $resultArray = [];
        foreach ($pizzas as $key => $value) {
            $resultArray[] = GenericMapper::noSqlMapToArray($value, $key);
        }

        return json_encode($resultArray);
    }

    /**
     * @param string $pizzaName
     * @return string
     * @throws DatabaseException
     */
    public function getByName(string $pizzaName = ""): string
    {
        if($pizzaName && isset($pizzaName) && !$this->pizzasRepository->isExists($pizzaName)) {
            throw new \RuntimeException(self::ERRORS['wrong_name']);
        }

        // todo: set success Response - contain requested data
        $pizza = $this->pizzasRepository->getByName($pizzaName);

        return json_encode(GenericMapper::noSqlMapToArray($pizza, $pizzaName));
    }

    /* -------- TOOLS FOR ADMIN -------- */

    /**
     * @param array $newPizza
     * @return string
     * @throws DatabaseException
     */
    public function post(array $newPizza): string
    {
        if(empty($newPizza) || !isset($newPizza)) {
            throw new \RuntimeException(self::ERRORS['missing_name']);     // error - required name
        }

        //$existingIngredients = $this->ingredientsRepository->getAll();
        $this->validator->validate($newPizza, ["chees", "tomato", "onion", "potatoes"]);    // if not validate - throw exception

        /** @var string $pizzaName */
        $pizzaName = $newPizza["id"];

        $this->pizzasRepository->create(
            $pizzaName,
            array_slice($newPizza, 1, count($newPizza)-1,  true)
        );

        return json_encode($this->pizzasRepository->getByName($pizzaName));
    }

    /**
     * @param string $pizzaName
     * @return bool
     * @throws DatabaseException
     */
    public function delete(string $pizzaName): bool
    {
        if(empty($pizzaName) || !isset($pizzaName)) {
            throw new \RuntimeException(self::ERRORS['missing_name']);     // error - required name
        }

        if(!$this->pizzasRepository->isExists($pizzaName)) {
            throw new \RuntimeException(self::ERRORS['wrong_name']);    // error - required name
        }

        $this->pizzasRepository->remove($pizzaName);

        return true;
    }
}