<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Mapper\GenericMapper;
use Pizzeria\Repository\GenericRepository;
use Pizzeria\Validator\GenericValidator;

abstract class GenericApi implements IApi
{
    protected const ERRORS = [];

    /**
     * @var Database
     */
    protected $connection;

    /**
     * @var GenericRepository
     */
    protected $repository;

    /**
     * @var GenericValidator
     */
    protected $validator;

    /**
     * GenericApi constructor.
     * @param DbConnection $dbConnection
     * @param GenericRepository $repository
     */
    public function __construct(DbConnection $dbConnection, GenericRepository $repository, GenericValidator $validator)
    {
        $this->connection = $dbConnection->getFirebase();
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @return string
     * @throws DatabaseException
     */
    public function getAll(): string
    {
        $elements = $this->repository->getAll();

        $resultArray = [];
        foreach ($elements as $key => $value) {
            $resultArray[] = GenericMapper::noSqlMapToArray($value, $key);
        }

        return json_encode($resultArray);
    }

    /**
     * @param string $name
     * @return string
     * @throws DatabaseException
     */
    public function getByName(string $name): string
    {
        if($name && isset($name) && !$this->repository->isExists($name)) {
            throw new \RuntimeException(static::ERRORS['wrong_name']);
        }

        // todo: set success Response - contain requested data
        $result = $this->repository->getByName($name);

        return json_encode(GenericMapper::noSqlMapToArray($result, $name));
    }

    public function post(array $newElement): string
    {
        if(empty($newElement) || !isset($newElement)) {
            throw new \RuntimeException(static::ERRORS['missing_name']);     // error - required name
        }

        return "";
    }

    /**
     * @param string $name
     * @return bool
     * @throws DatabaseException
     */
    public function delete(string $name): bool
    {
        if(empty($name) || !isset($name)) {
            throw new \RuntimeException(static::ERRORS['missing_name']);     // error - required name
        }

        if(!$this->repository->isExists($name)) {
            throw new \RuntimeException(static::ERRORS['wrong_name']);    // error - required name
        }

        $this->repository->remove($name);

        return true;
    }
}