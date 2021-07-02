<?php
declare(strict_types=1);

namespace Pizzeria\Validator\Models;

use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\OrderRepository;
use Pizzeria\Validator\Elements\SauceValidator;
use Pizzeria\Validator\GenericValidator;
use Pizzeria\Validator\PaidModels\OrderPriceValidator;
use Pizzeria\Validator\PaidProductsValidator;

final class OrderValidator extends PaidProductsValidator
{
    public const ELEMENTS_GROUP = 'orders';
    public const REQUIRED_VARS = ["pizzas", "sauces", "price"];

    public function __construct()
    {
        parent::__construct(
            new OrderRepository(new DbConnection()),
            new OrderPriceValidator()
        );

        $this->addDecoratedValidators(new PizzaValidator());
        $this->addDecoratedValidators(new SauceValidator());

        $this->willPriceBeChecked(true);    // always check order's price in both update and insert new order action
    }

    /**
     * @param array $newOrder
     * @param bool $isSchemaRequired
     * @return bool
     */
    public function validate(array $newOrder, bool $isSchemaRequired = true): bool
    {
        // valid order's schema
        parent::validate($newOrder, $isSchemaRequired);

        // validate each pizza && sauce
        /** @var GenericValidator $decoratedValidator */
        foreach ($this->decoratedValidators as $decoratedValidator) {

            /** @var array  $elementsList */
            $elementsList = $newOrder[$decoratedValidator::ELEMENTS_GROUP];

            /** require to check price child-elements if it's possible */
            if($decoratedValidator instanceof PaidProductsValidator) {
                $decoratedValidator->willPriceBeChecked(false);
            }

            /**
             * np $this->areElementsValid($newOrder['pizzas'], 'pizza', function($pizza) { $this->pizzaValidator->validate($pizza); });
             */
            $this->areElementsValid(
                $elementsList,
                $decoratedValidator::ELEMENTS_GROUP,
                function($ordersElement) use ($decoratedValidator) { $decoratedValidator->validate($ordersElement); }
            );
        }

        // check order price
        if($this->checkPriceFlag === true) {
            $this->checkPrice($newOrder[self::REQUIRED_VARS[0]], $newOrder[self::REQUIRED_VARS[1]], $newOrder[self::REQUIRED_VARS[2]]);
        }

        return true;
    }

    /**
     * Pizzas and sauces are list of elements but parent::areElementsExists work only for one element
     * @param array $newProduct
     * @param bool $areElementsRequired
     * @return bool
     */
    public function areElementsExist(array $newProduct, bool $areElementsRequired = true): bool
    {
        foreach ($this->decoratedValidators as $decoratedValidator) {
            // if $newProduct is single object use parent method's implementation
            if(array_key_exists('id', $newProduct[$decoratedValidator::ELEMENTS_GROUP])) {
                $decoratedValidator->areElementsExist($newProduct[$decoratedValidator::ELEMENTS_GROUP]);
            } else {
                // check each object form list independently
                foreach ($newProduct[$decoratedValidator::ELEMENTS_GROUP] as $key => $listsElement) {
                    $decoratedValidator->areElementsExist($listsElement, $areElementsRequired);
                }
            }
        }

        return true;
    }

    /**
     * check if each ordered pizza and sauces are valid
     *
     * @param array $orderElements
     * @param string $elementName
     * @param callable $validatorFunction
     */
    private function areElementsValid(array $orderElements, string $elementName, callable $validatorFunction): void
    {
        foreach ($orderElements as $index => $element)
        {
            try {
                $validatorFunction($element);
            } catch (\Exception $exception) {
                throw new \RuntimeException(sprintf("order - %s: %s", $elementName, $exception->getMessage()));
                break;
            }
        }
    }
}