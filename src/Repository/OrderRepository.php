<?php
declare(strict_types=1);

namespace Pizzeria\Repository;

use Pizzeria\Connection\DbConnection;

class OrderRepository extends GenericRepository
{
    public const DB_NAME = 'order';

    /**
     * OrderRepository constructor.
     * @param DbConnection $connection
     */
    public function __construct(DbConnection $connection)
    {
        parent::__construct($connection);
    }


}