<?php
declare(strict_types=1);

namespace Pizzeria\Connection;

use Kreait\Firebase\Factory;

class DbConnection
{
    private $connection;

    public function __construct()
    {
        $this->connection = $this->setUpFactory()->createDatabase();
    }

    public function getFirebase() {
        return $this->connection;
    }

    private function setUpFactory() {
        return (new Factory)
            ->withServiceAccount(
                $this->getServiceAccountData()
            )
            ->withDatabaseUri('https://pizzeriaserver-default-rtdb.europe-west1.firebasedatabase.app');

    }

    private function getServiceAccountData(): string {
        return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'pizzeriaserver-firebase-adminsdk-z2w2z-edc96c2acf.json';
    }

}

//https://www.rafaelwendel.com/en/2020/05/php-how-to-resolve-the-curl-error-60-error/ - fix error for ssl

