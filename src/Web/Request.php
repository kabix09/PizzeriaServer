<?php
declare(strict_types=1);

namespace Pizzeria\Web;

class Request extends AbstractHttp
{
    public function __construct(
        string $uri = "",
        string $method = "",
        array $headers = [],
        array $data = [],
        array $cookies = []
    )
    {
        if(empty($headers)) $this->headers = $_SERVER ?? array();
        else $this->headers = $headers;

        if(empty($uri)) $this->uri = $this->headers['PHP_SELF'] ?? '';
        else $this->uri = $uri;

        if(empty($method)) $this->method = $this->headers['REQUEST_METHOD'] ?? self::METHOD_GET;
        else $this->method = $method;

        if(empty($data)) $this->data = $_REQUEST ?? array();
        else $this->data = $data;

        if(empty($cookies)) $this->cookies = $_COOKIE ?? array();
        else $this->cookies = $cookies;

        $this->setTransport();
    }
}