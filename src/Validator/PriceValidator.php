<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Repository\GenericRepository;

abstract class PriceValidator extends GenericValidator
{
    public const ELEMENTS_GROUP = 'price';

    /**
     * PriceValidator constructor.
     * @param GenericRepository $repository
     */
    public function __construct(GenericRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * @param $object
     * @param array $additionalElementsList
     * @param int $clientPrice
     * @return bool
     */
    abstract public function isPriceCorrect($object, array $additionalElementsList , int $clientPrice): bool;

    /**
     * @param array $inputList
     * @return int
     * @throws DatabaseException
     */
    protected function countElementsPrice(array $inputList): int
    {
        if(empty($inputList)) {
            return 0;
        }

        /** @var array $elementsList */
        $elementsList = $this->repository->get();

        return array_reduce($inputList, function($calculatedPrice, $elementsUuid) use ($elementsList) {
            return $calculatedPrice + $elementsList[$elementsUuid][self::ELEMENTS_GROUP];
        });
    }

    /**
     * @param array $elements
     * @param bool $areElementsRequired
     * @return bool
     */
    public function areElementsExist(array $elements, bool $areElementsRequired = false): bool
    {
        return true;
    }
}