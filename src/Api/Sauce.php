<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\SauceRepository;
use Pizzeria\Validator\SauceValidator;

class Sauce extends GenericApi
{
    /**
     * Sauce constructor.
     * @param DbConnection $dbConnection
     */
    public function __construct(DbConnection $dbConnection)
    {
        parent::__construct($dbConnection, new SauceRepository($dbConnection), new SauceValidator());
    }

    /**
     * @param array $newElement
     * @return string
     * @throws \Kreait\Firebase\Exception\DatabaseException
     */
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