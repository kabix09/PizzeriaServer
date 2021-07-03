<?php
declare(strict_types=1);

namespace Pizzeria\Repository;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Kreait\Firebase\Database;

final class PizzaRepository extends GenericRepository
{
    public const DB_NAME = 'pizza';

    /**
     * PizzaRepository constructor.
     * @param DbConnection $connection
     */
    public function __construct(DbConnection $connection)
    {
        parent::__construct($connection);
    }

    /**
     * @param string $pizzaName
     * @param array $newIngredientsList
     * @return Database\Reference
     * @throws DatabaseException
     */
    public function updateIngredients(string $pizzaName, array $newIngredientsList): Database\Reference
    {
        return $this->firebase
            ->getReference(self::DB_NAME . '/' . $pizzaName . '/ingredients')
            ->set($newIngredientsList);
    }

    /**
     * @param string $pizzaName
     * @param int $newPrice
     * @return Database\Reference
     * @throws DatabaseException
     */
    public function updatePrice(string $pizzaName, int $newPrice): Database\Reference
    {
        return $this->firebase
            ->getReference(self::DB_NAME . '/' . $pizzaName . '/price')
            ->set($newPrice);
    }
}