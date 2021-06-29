<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\SauceRepository;
use Pizzeria\Validator\SauceValidator;
use Pizzeria\Web\Request;

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
        $ingredientName = $request->getDataByKey(self::ID_FIELD);

        $this->repository->create(
            $ingredientName,
            array_slice($newElement, 1, count($newElement)-1,  true)
        );

        return $this->repository->getByName($ingredientName);
    }
}