<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Mapper\GenericMapper;
use Pizzeria\Repository\GenericRepository;
use Pizzeria\Validator\GenericValidator;
use Pizzeria\Web\Request;
use Ramsey\Uuid\Uuid;

abstract class GenericApi implements IApi
{
    public const ID_FIELD = 'id';
    public const NAME_FIELD = 'name';
    public const SCHEMA = [];
    protected const ERRORS = [
        'missing_id'=>'missing %s id property', 'invalid_id'=>'nonexistent %s id: %s',
        'missing_name' => 'missing %s name', 'invalid_name' => 'nonexistent %s name: %s'
    ];

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
     * @param GenericRepository $repository
     * @param GenericValidator $validator
     */
    public function __construct(GenericRepository $repository, GenericValidator $validator)
    {
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
            $fetchObject = $this->repository->getByKey(strtoupper($name), static::NAME_FIELD);
            if(empty($fetchObject)) {
                throw new \RuntimeException(sprintf(self::ERRORS['invalid_name'], $this->validator::ELEMENTS_GROUP, $name));
            }
            return $fetchObject;
        }

        return $this->repository->get();
    }

    /**
     * @param Request $request
     * @return array
     * @throws DatabaseException
     */
    public function post(Request $request): array
    {
        $newElement = GenericMapper::buildObject($request->getData(), static::SCHEMA);

        /** @var string|null $elementsUuid */
        $elementsUuid = $request->getDataByKey(self::ID_FIELD);

        $this->validator->validate($newElement);    // if not validate - throw exception

        if(!empty($elementsUuid) && $this->repository->isExists($elementsUuid, 'id')) {
            $this->repository->update($elementsUuid, $newElement);
        }else {
            $elementsUuid = $this->generateUuid();

            // add 'id' field to new object
            $newElement['id'] = $elementsUuid;

            $this->repository->create($elementsUuid, $newElement);
        }

        return $this->repository->getByKey($elementsUuid);
    }

    /**
     * @param Request $request
     * @return array
     * @throws DatabaseException
     */
    public function put(Request $request): array
    {
        /** @var array $newElement */
        $newElement = GenericMapper::buildObject($request->getData(), static::SCHEMA);

        $this->validator->validate($newElement);    // if not validate - throw exception

        $elementsUuid = $this->generateUuid();

        // add 'id' field to new object
        $newElement['id'] = $elementsUuid;

        $this->repository->create($elementsUuid, $newElement);

        return $this->repository->getByKey($elementsUuid);
    }

    /**
     * @param Request $request
     * @return bool
     * @throws DatabaseException
     */
    public function delete(Request $request): array
    {
        $id = $request->getDataByKey(static::ID_FIELD);

        if(empty($id) || !isset($id)) {
            throw new \RuntimeException(sprintf(self::ERRORS['missing_id'], $this->validator::ELEMENTS_GROUP));     // error - required name
        }

        if(!$this->repository->isExists($id, self::ID_FIELD)) {
            throw new \RuntimeException(sprintf(self::ERRORS['invalid_id'], $this->validator::ELEMENTS_GROUP, $id));    // error - required name
        }

        $this->repository->remove($id);

        return ['id' => $id];
    }

    protected function generateUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}