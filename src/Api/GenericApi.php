<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Mapper\GenericMapper;
use Pizzeria\Repository\GenericRepository;
use Pizzeria\Validator\GenericValidator;
use Pizzeria\Web\Request;
use Pizzeria\Web\Response;
use Pizzeria\Web\Server;

abstract class GenericApi implements IApi
{
    public const ID_FIELD = 'id';
    public const NAME_FIELD = 'name';
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
     * @param GenericValidator $validator
     */
    public function __construct(DbConnection $dbConnection, GenericRepository $repository, GenericValidator $validator)
    {
        $this->connection = $dbConnection->getFirebase();
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return array
     * @throws DatabaseException
     */
    public function get(Request $request): array
    {
        $name = $request->getDataByKey(static::NAME_FIELD);

        if($name && isset($name)) {
             return $this->getByName($name);
        }

        return $this->getAll();
    }

    /**
     * @return array
     * @throws DatabaseException
     */
    public function getAll(): array
    {
        $elements = $this->repository->getAll();

        $resultArray = [];
        foreach ($elements as $key => $value) {
            $resultArray[] = GenericMapper::noSqlMapToArray($value, $key);
        }

        return $resultArray;
    }

    /**
     * Return one element matched by name / id
     *
     * @param string $name
     * @return array
     * @throws DatabaseException
     */
    public function getByName(string $name): array
    {
        if($name && isset($name) && !$this->repository->isExists($name)) {
            throw new \RuntimeException(static::ERRORS['wrong_name']);
        }

        // todo: set success Response - contain requested data
        $result = $this->repository->getByName($name);

        // build response
        return GenericMapper::noSqlMapToArray($result, $name);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function post(Request $request): array
    {
        $newElement = $request->getData();

        if(empty($newElement) || !isset($newElement)) {
            throw new \RuntimeException(static::ERRORS['missing_name']);     // error - required name
        }

        return [];
    }

    /**
     * @param Request $request
     * @return bool
     * @throws DatabaseException
     */
    public function delete(Request $request): bool
    {
        $id = $request->getDataByKey(static::ID_FIELD);

        if(empty($id) || !isset($id)) {
            throw new \RuntimeException(static::ERRORS['missing_name']);     // error - required name
        }

        if(!$this->repository->isExists($id)) {
            throw new \RuntimeException(static::ERRORS['wrong_name']);    // error - required name
        }

        $this->repository->remove($id);

        return true;
    }
}