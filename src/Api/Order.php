<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\OrderRepository;
use Pizzeria\Validator\Models\OrderValidator;

class Order extends GenericApi
{
    public const SCHEMA = ['id', 'pizzas', 'sauces', 'price'];

    /** @var OrderValidator $validator */
    protected $validator;
    /**
     * Order constructor.
     */
    public function __construct()
    {
        parent::__construct(new OrderRepository(new DbConnection()), new OrderValidator());
    }
}
