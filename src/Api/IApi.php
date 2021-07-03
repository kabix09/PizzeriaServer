<?php
declare(strict_types=1);

namespace Pizzeria\Api;

use Pizzeria\Web\Request;

interface IApi
{
    /**
     * @param Request $request
     * @return array
     */
    public function get(Request $request): array;

    /**
     * @param Request $request
     * @return array
     */
    public function post(Request $request): array;

    /**
     * @param Request $request
     * @return array
     */
    public function put(Request $request): array;

    /**
     * @param Request $request
     * @return bool
     */
    public function delete(Request $request): array;
}