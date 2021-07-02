<?php
declare(strict_types=1);

namespace Pizzeria\Validator\PaidModels;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\SauceRepository;
use Pizzeria\Validator\PriceValidator;

final class OrderPriceValidator extends PriceValidator
{
    public function __construct()
    {
        parent::__construct(
            new SauceRepository(
                new DbConnection()
            )
        );
    }

    /**
     * @param array $orderedPizzas
     * @param array $orderedSaucesList
     * @param int $clientPrice
     * @return bool
     * @throws DatabaseException
     */
    public function isPriceCorrect($orderedPizzas, array $orderedSaucesList, int $clientPrice): bool
    {
        // sum all pizzas price
        $orderedPizzasPrice = $this->orderedPizzasPrice($orderedPizzas);

        return $clientPrice === $orderedPizzasPrice + $this->countElementsPrice($orderedSaucesList);
    }

    /**
     * @param array $orderedPizzas
     * @return int
     */
    public function orderedPizzasPrice(array $orderedPizzas): int
    {
        return array_reduce($orderedPizzas, static function($calculatedPrice, $orderedPizza){
            return $calculatedPrice + $orderedPizza['price'];
        });
    }
}