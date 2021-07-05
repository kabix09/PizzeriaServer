<?php
declare(strict_types=1);

namespace Pizzeria\Validator;

use Pizzeria\Repository\GenericRepository;
use Pizzeria\Logger\ClientDataException;

class PaidProductsValidator extends ModelValidator
{
    public const ERRORS = ['invalid_price' => 'miscalculated %s price: %d'];

    /**
     * @var PriceValidator
     */
    private $priceValidator;

    /**
     * @var bool
     */
    protected $checkPriceFlag;

    public function __construct(GenericRepository $repository, PriceValidator $priceValidator)
    {
        parent::__construct($repository);
        $this->priceValidator = $priceValidator;
    }

    /**
     * @param bool $checkPriceFlag
     */
    public function willPriceBeChecked(bool $checkPriceFlag): void
    {
        $this->checkPriceFlag = $checkPriceFlag;
    }

    /**
     * @param $object
     * @param array $additionalElementsList
     * @param int $clientPrice
     * @throws ClientDataException
     */
    protected function checkPrice($object, array $additionalElementsList , int $clientPrice): void
    {
        if(!$this->priceValidator->isPriceCorrect($object, $additionalElementsList, $clientPrice)) {
            throw new ClientDataException(sprintf(static::ERRORS['invalid_price'], static::ELEMENTS_GROUP, $clientPrice));
        }
    }
}