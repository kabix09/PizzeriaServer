<?php
declare(strict_types=1);

namespace Pizzeria\Validator\Elements;

use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\SauceRepository;
use Pizzeria\Validator\ElementValidator;

final class SauceValidator extends ElementValidator
{
    public const ELEMENTS_GROUP = 'sauces';

    public function __construct()
    {
        parent::__construct(
            new SauceRepository(
                new DbConnection()
            )
        );
    }
}