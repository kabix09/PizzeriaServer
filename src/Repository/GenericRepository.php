<?php
declare(strict_types=1);

namespace Pizzeria\Repository;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;
use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;

abstract class GenericRepository implements IRepository
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
     * @param string $name
     * @return mixed
     * @throws DatabaseException
     */

    public function getAll()
    {
        return $this->firebase
            ->getReference(static::DB_NAME)
            ->getValue();
    }

    /**
     * @param string $name
     * @return mixed
     * @throws DatabaseException
     */
    public function getByName(string $name)
    {
        return $this->firebase
            ->getReference(static::DB_NAME)
            ->getChild($name)
            ->getValue();
    }

    /**
     * @param string $name
     * @param array $properties
     * @return Reference
     * @throws DatabaseException
     */
    public function create(string $name, array $properties): Reference
    {
        return $this->firebase
            ->getReference(static::DB_NAME)
            ->getChild($name)
            ->set($properties);
    }

    /**
     * @param string $name
     * @param array $newElement
     * @return Reference
     * @throws DatabaseException
     */
    public function update(string $name, array $newElement): Reference
    {
        return $this->firebase
            ->getReference(self::DB_NAME . '/' . $name)
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
            ->getReference(self::DB_NAME)
            ->getChild($name)
            ->remove();
    }

    /**
     * @param string $name
     * @return bool
     * @throws DatabaseException
     */
    public function isExists(string $name): bool
    {
        return $this->firebase
            ->getReference(static::DB_NAME)
            ->getSnapshot()
            ->hasChild($name);
    }
}