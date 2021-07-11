<?php
declare(strict_types=1);

namespace Pizzeria\Validator\PaidModels;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\IngredientRepository;
use Pizzeria\Repository\PizzaRepository;
use Pizzeria\Validator\PriceValidator;

final class PizzaPriceValidator extends PriceValidator
{
    public function __construct()
    {
        parent::__construct(
            new IngredientRepository(
                new DbConnection()
            )
        );
    }

    /**
     * @param string $productName
     * @param array $productIngredientsList list of pizza ingredients id
     * @param int $clientPrice
     * @return bool
     * @throws DatabaseException
     */
    public function isPriceCorrect($productName, array $productIngredientsList, int $clientPrice) : bool
    {
        /** @var array $defaultPizzaSchema */
        $defaultPizzaSchema = (new PizzaRepository(new DbConnection()))->getByKey(strtoupper($productName), 'name');

        // compare default pizza ingredients list with pizza ingredients list to get missing ingredients
        $missingIngredients = $this->getDifferenceBetweenArrays($defaultPizzaSchema[0]['ingredients'], $productIngredientsList);

        // compare pizza ingredients list with default pizza ingredients list to get duplicated ingredients
        $duplicatedIngredients = $this->getDifferenceBetweenArrays($productIngredientsList, $defaultPizzaSchema[0]['ingredients']);

        return $clientPrice === ($defaultPizzaSchema[0]['price'] + $this->countElementsPrice($duplicatedIngredients) - $this->countElementsPrice($missingIngredients));
    }
}