<?php
declare(strict_types=1);

namespace Pizzeria\Repository;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;
use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;

class GenericRepository implements IRepository
{
    /**
     * @var Database
     */
    protected $firebase;

    public function __construct(DbConnection $connection)
    {
        $this->firebase = $connection->getFirebase();
    }

    /**
     * @return mixed
     * @throws DatabaseException
     */

    public function get()
    {
        return $this->firebase
            ->getReference(static::DB_NAME)
            ->getValue();
    }

    public function getByKey(string $value, string $key = 'id'): array
    {
        return array_values($this->firebase
            ->getReference(static::DB_NAME)
            ->orderByChild($key)
            ->equalTo($value)
            ->getValue());
    }

    /**
     * @param array $properties
     * @param string $index
     * @return Reference
     * @throws DatabaseException
     */
    public function create(string $index, array $properties): Reference
    {
        return $this->firebase
            ->getReference(static::DB_NAME)
            ->getChild($index)
            ->set($properties);
    }

    /**
     * @param string $index
     * @param array $newElement
     * @return Reference
     * @throws DatabaseException
     */
    public function update(string $index, array $newElement)
    {
        return $this->firebase
            ->getReference(static::DB_NAME . "/" . $index)
            ->update($newElement);
    }

    /**
     * @param string $name
     * @return Reference
     * @throws DatabaseException
     */
    public function remove(string $name): Reference
    {
        return $this->firebase
            ->getReference(static::DB_NAME)
            ->getChild($name)
            ->remove();
    }

    /**
     * @param string $value
     * @param string $key
     * @return bool
     * @throws DatabaseException
     */
    public function isExists(string $value, string $key = 'name'): bool
    {
        $queryResult = $this->firebase->getReference(static::DB_NAME)->orderByChild($key)->equalTo($value)->getValue();

        return !empty($queryResult);
    }

    public function count(): int
    {
        return $this->firebase
            ->getReference(static::DB_NAME)
            ->getSnapshot()
            ->numChildren();
    }
}