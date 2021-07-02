<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\OrderRepository;
use Pizzeria\Validator\Models\OrderValidator;
use Pizzeria\Web\Request;

class Order extends GenericApi
{

    /**
     * Order constructor.
     */
    public function __construct()
    {
        parent::__construct(new OrderRepository(new DbConnection()), new OrderValidator());
    }

    /**
     * @param Request $request
     * @return array
     * @throws DatabaseException
     */
    public function post(Request $request): array
    {
        parent::post($request);

        $newOrder = $request->getData();
        $this->validator->validate($newOrder);

        // todo: generate renadom uuid with outside library
        $orderName = substr(str_shuffle("1234567890abcdefghijklmnoprstuwxqz()/$"), 0, 16);

        $this->repository->create(
            $orderName,
            array_slice($newOrder, 1, count($newOrder)-1,  true)
        );

        return $this->repository->getByName($orderName);

    }
}