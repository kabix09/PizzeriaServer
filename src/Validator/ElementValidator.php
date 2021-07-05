<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Logger\ClientDataException;
use Pizzeria\Repository\GenericRepository;

class ElementValidator extends GenericValidator
{
    public const ELEMENTS_GROUP = '';
    public const ELEMENT_KEY = 'id';

    public const REQUIRED_VARS = ["name", "price"];

    protected const ERRORS = ["nonexistent_element" => "this %s don't exists: %s"];

    /**
     * ElementsValidator constructor.
     * @param GenericRepository $repository
     */
    public function __construct(GenericRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Validate - main validator's function, valid single user element
     * @param array $clientsElement
     * @param bool $isSchemaRequired
     * @return bool
     */
    public function validate(array $clientsElement, bool $isSchemaRequired = true): bool
    {
        return parent::validate($clientsElement, $isSchemaRequired);
    }

    /**
     * @param array $elements - list of element's id
     * @param bool $areElementsRequired
     * @return bool
     * @throws DatabaseException
     * @throws ClientDataException
     */
    public function areElementsExist(array $elements = [], bool $areElementsRequired = false): bool
    {
        /** @var array $elementsKeysList */
        $elementsKeysList = array_map(
            static function($element) { return $element[static::ELEMENT_KEY]; },
            $this->repository->get()
        );

        $result = $this->compareElementsIdList($elements, function ($elementsUuid) use ($elementsKeysList) {
            return in_array($elementsUuid, $elementsKeysList, true);
        });

        if(!empty($result)) {
            if($areElementsRequired) {
                throw new ClientDataException(sprintf(
                        self::ERRORS['nonexistent_element'],
                        static::ELEMENTS_GROUP,
                        implode(", ", $result)
                    )
                );
            }

            return false;
        }

        return true;
    }

    /**
     * @param array $elements
     * @param callable $comparisonFunction
     * @return array
     */
    private function compareElementsIdList(array $elements, callable $comparisonFunction ): array
    {
        if(empty($elements)) {
            return array();
        }

        $result = array_filter($elements, $comparisonFunction);

        return array_diff($elements, $result);
    }
}