<?php
declare(strict_types=1);

namespace Pizzeria\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class PizzeriaLogger
{
    public const PATH_TO_LOGS_FILE = DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'error_log.txt';

    /**
     * @var Logger
     */
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger('Pizzeria');
        $this->logger->pushHandler(new StreamHandler( dirname(__DIR__, 2) . self::PATH_TO_LOGS_FILE, Logger::ERROR));
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }
}