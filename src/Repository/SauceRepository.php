<?php
declare(strict_types=1);

namespace Pizzeria\Repository;

use Pizzeria\Connection\DbConnection;

class SauceRepository extends GenericRepository
{
    public const DB_NAME = 'sauce';

    /**
     * IngredientRepository constructor.
     * @param DbConnection $connection
     */
    public function __construct(DbConnection $connection)
    {
        parent::__construct($connection);
    }
}