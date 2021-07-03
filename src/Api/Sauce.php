<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Pizzeria\Connection\DbConnection;
use Pizzeria\Repository\SauceRepository;
use Pizzeria\Validator\Elements\SauceValidator;

class Sauce extends GenericApi
{
    public const SCHEMA = ['id', 'name', 'price'];

    /**
     * Sauce constructor.
     */
    public function __construct()
    {
        parent::__construct(new SauceRepository(new DbConnection()), new SauceValidator());
    }
}
