<?php
declare(strict_types=1);

namespace Pizzeria\Web;

class Response extends AbstractHttp
{
    /**
     * @var string $statusCode
     */
    private $statusCode;

    public function __construct(Request $request, string $statusCode = "", string $contentType = "")
    {
        if($request) {
            $this->uri = $request->getUri();
            $this->data = $request->getData();
            $this->method = $request->getMethod();
            $this->cookies = $request->getCookies();
            $this->setTransport();
        }

        $this->processHeaders($contentType);

        if($statusCode) {
            $this->setStatusCode($statusCode);
        }
    }

    private function processHeaders(string $contentType = "")
    {
        if($contentType) {
            $this->setHeaderByKey(static::HEADER_CONTENT_TYPE, $contentType);
        } else {
            $this->setHeaderByKey(static::HEADER_CONTENT_TYPE, static::CONTENT_TYPE_JSON);
        }
    }

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     */
    public function setStatusCode(string $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}