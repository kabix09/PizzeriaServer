<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\PizzaRepository;
use Pizzeria\Validator\Models\PizzaValidator;
use Pizzeria\Web\Request;

final class Pizza extends GenericApi
{
    protected const ERRORS = [
        "missing_name" => "missing pizza name",
        "wrong_name" => "wrong pizza name - this pizza doesn't exist"
    ];

    /**
     * Pizza constructor.
     */
    public function __construct()
    {
        parent::__construct(new PizzaRepository(new DbConnection()), new PizzaValidator());
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
        $this->validator->validate($newElement);    // if not validate - throw exception

        /** @var string $pizzaName */
        $pizzaName = $request->getDataByKey(self::ID_FIELD);

        $this->repository->create(
            $pizzaName,
            array_slice($newElement, 1, count($newElement)-1,  true)
        );

        return $this->repository->getByName($pizzaName);
    }

}