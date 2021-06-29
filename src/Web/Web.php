<?php
declare(strict_types=1);

namespace Pizzeria\Web;

interface Web
{
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_DELETE = 'DELETE';

    public const TRANSPORT_HTTP = 'http';
    public const TRANSPORT_HTTPS = 'https';

    public const HEADER_CONTENT_TYPE = 'Content-Type';
    public const CONTENT_TYPE_HTML = 'text/html';
    public const CONTENT_TYPE_JSON = 'application/json';
    public const CONTENT_TYPE_FORM_URL_ENCODED = 'application/x-www-form-urlencoded';

    public const STATUS_200 = '200';
    public const STATUS_400 = '400';
    public const STATUS_401 = '401';
    public const STATUS_500 = '500';
    public const STATUS_501 = '501';
}