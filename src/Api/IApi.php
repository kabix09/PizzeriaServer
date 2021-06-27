<?php
declare(strict_types=1);

namespace Pizzeria\Api;

interface IApi
{
    public function getAll(): string;

    public function getByName(string $name): string;

    public function post(array $newElement): string;

    public function delete(string $name): bool;
}