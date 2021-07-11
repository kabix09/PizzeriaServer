<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\SauceRepository;
use Pizzeria\Validator\Elements\SauceValidator;
use Pizzeria\Web\Request;

class Sauce extends GenericApi
{
    /**
     * Sauce constructor.
     */
    public function __construct()
    {
        parent::__construct(new SauceRepository(new DbConnection()), new SauceValidator());
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