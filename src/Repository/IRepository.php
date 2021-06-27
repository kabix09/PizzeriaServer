<?php
declare(strict_types=1);

namespace Pizzeria\Repository;

interface IRepository
{
    public const DB_NAME = '';

    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param string $name
     * @return mixed
     */
    public function getByName(string $name);

    /**
     * @param string $name
     * @param array $properties
     * @return mixed
     */
    public function create(string $name, array $properties);

    /**
     * @param string $name
     * @param array $newElement
     * @return mixed
     */
    public function update(string $name, array $newElement);

    /**
     * @param string $name
     * @return mixed
     */
    public function remove(string $name);

    /**
     * @param string $name
     * @return bool
     */
    public function isExists(string $name): bool;
}