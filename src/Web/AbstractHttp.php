<?php
declare(strict_types=1);

namespace Pizzeria\Web;

abstract class AbstractHttp implements Web
{
    /**
     * @var string $method
     */
    protected $method;

    /**
     * @var string $uri
     */
    protected $uri;

    /**
     * @var string $transport
     */
    protected $transport;

    /**
     * @var array $headers
     */
    protected $headers;

    /**
     * @var array $cookies
     */
    protected $cookies;

    /**
     * @var array $metaData
     */
    protected $metaData;

    /**
     * @var array $data
     */
    protected $data;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method ?? self::METHOD_GET;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @param array $params
     */
    public function setUri(string $uri, array $params = []): void
    {
        $this->uri = $uri;

        if(!empty($params)) {
            $this->uri .= '?' . http_build_query($params);
        }
    }

    /**
     * @return string
     */
    public function getTransport(): string
    {
        return $this->transport;
    }

    /**
     * @param string $transport
     */
    public function setTransport(string $transport = ""): void
    {
        if(!empty($transport)) {

            $this->transport = $transport;
        } else if(strpos($this->uri, self::TRANSPORT_HTTPS) === 0) {

            $this->transport = self::TRANSPORT_HTTPS;
        } else {

            $this->transport = self::TRANSPORT_HTTP;
        }
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getHeaderByKey(string $key): string
    {
        return $this->headers[$key];
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function setHeaderByKey(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getDataByKey(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * @param array $cookies
     */
    public function setCookies(array $cookies): void
    {
        $this->cookies = $cookies;
    }

    /**
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }

    /**
     * @param array $metaData
     */
    public function setMetaData(array $metaData): void
    {
        $this->metaData = $metaData;
    }

}