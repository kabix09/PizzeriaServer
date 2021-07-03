<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\PizzaRepository;
use Pizzeria\Validator\Models\PizzaValidator;

final class Pizza extends GenericApi
{
    public const SCHEMA = ['id', 'name', 'ingredients', 'price'];

    /** @var PizzaValidator */
    protected $validator;

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
}
