<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

use Pizzeria\Repository\GenericRepository;

abstract class GenericValidator implements IValidator, IExistsElement
{
    public const ELEMENTS_GROUP = '';
    public const REQUIRED_VARS = [];
    protected const ERRORS = ["missing_property" => "missing %s property: %s"];

    /**
     * @var GenericRepository
     */
    protected $repository;

    /**
     * GenericValidator constructor.
     * @param GenericRepository $repository
     */
    public function __construct(GenericRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $newProduct
     * @param bool $isSchemaRequired
     * @return bool
     */
    public function validate(array $newProduct, bool $isSchemaRequired = true): bool
    {
        if (!$isSchemaRequired)
            return true;

        // check if default fields exists in submitted object - $newProduct and return arrays difference - missing properties
        $missingProperties = $this->checkDefaultObjectSchema(function ($key, $value) use ($newProduct) {
            return array_key_exists($key, $newProduct);
        });

        if (!empty($missingProperties)) {
            throw new \RuntimeException(sprintf(self::ERRORS['missing_property'], static::ELEMENTS_GROUP, implode(", ", $missingProperties)));     // error - missing element
        }

        return true;
    }

    /**
     * Return difference between required fields and existing fields, when return empty array all necessary fields exists
     *
     * @param callable $callback
     * @return array
     */
    private function checkDefaultObjectSchema(callable $callback): array
    {
        $result = array_filter(static::REQUIRED_VARS, $callback , ARRAY_FILTER_USE_BOTH);

        return array_diff(static::REQUIRED_VARS, $result);
    }

    /**
     * @param $checkedCollection
     * @param $defaultSchema
     * @return array
     */
    protected function getDifferenceBetweenArrays($checkedCollection, $defaultSchema): array
    {
        foreach ($defaultSchema as $element) {
            $key = array_search($element, $checkedCollection, true);

            if($key !== false) {
                unset($checkedCollection[$key]);
            }
        }

        return array_values($checkedCollection);
    }

    /**
     * @param array $elements
     * @param bool $areElementsRequired
     * @return bool
     */
    abstract public function areElementsExist(array $elements, bool $areElementsRequired = false): bool;
}