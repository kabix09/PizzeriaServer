<?php
declare(strict_types=1);

namespace Pizzeria\Web;

use Kreait\Firebase\Exception\DatabaseException;
use Pizzeria\Api\GenericApi;
use Pizzeria\Logger\ClientDataException;
use Pizzeria\Logger\PizzeriaLogger;

class Server
{
    public const SERVER_ERROR_MES = 'Server error, please try again later';
    private const HEADER_SCHEMA = '%s: %s';

    /**
     * @var GenericApi
     */
    private $api;

    /**
     * @var PizzeriaLogger
     */
    private $logger;

    /**
     * Server constructor.
     * @param GenericApi $api
     */
    public function __construct(GenericApi $api)
    {
        $this->api = $api;
        $this->logger = new PizzeriaLogger();
    }

    /**
     * @throws DatabaseException
     */
    public function listen(): void
    {
        $request = new Request();
        $response = new Response($request);

        $jsonData = json_decode(
            file_get_contents('php://input'),
            true
        );

        $request->setData(
            array_merge($jsonData ?? array(), $_REQUEST ?? array())
        );

        try{
            $responseData = [];

            switch (strtoupper($request->getMethod())) {

                case Request::METHOD_GET: {
                    $responseData = array_values($this->api->get($request));
                    break;
                }

                case Request::METHOD_POST: {
                    $responseData = $this->api->post($request);
                    break;
                }

                case Request::METHOD_PUT: {
                    $responseData = $this->api->put($request);
                    break;
                }

                case Request::METHOD_DELETE: {
                    $responseData = $this->api->delete($request);
                    break;
                }
            }

            $response->setData($responseData);
            $response->setStatusCode(Response::STATUS_200);

        }catch (ClientDataException $clientDataException) {         // give the client info about request exception
            $response->setData([
                "message" => "request error: " . $clientDataException->getMessage()
            ]);
            $response->setStatusCode(Response::STATUS_400);
        } catch (\Exception $exception) {                           // catch exception message into file
            $this->logger->getLogger()->warning($exception->getMessage());

            $response->setData([
                "message" => static::SERVER_ERROR_MES
            ]);
            $response->setStatusCode(Response::STATUS_500);
        }

        $this->processResponse($response);

        printf(json_encode($response->getData()));
    }

    /**
     * @param Response $response
     */
    private function processResponse(Response $response): void
    {
        if(!empty($response->getHeaders())) {
            foreach ($response->getHeaders() as $key => $value) {
                self::setHeader($key, $value, true, (int)$response->getStatusCode());
            }
        }

        self::setHeader('Access-Control-Allow-Origin', '*');    // for allowing response for different clients
        self::setHeader('Access-Control-Allow-Headers', '*');   // for allowing ajax requests from client app
        self::setHeader(Response::HEADER_CONTENT_TYPE, Response::CONTENT_TYPE_JSON);

        if(!empty($response->getCookies())) {
            foreach ($response->getCookies() as $key => $value) {
                setcookie($key, $value);
            }
        }
    }

    /**
     * @param string $header
     * @param string $contentType
     * @param bool $replace
     * @param int|null $statusCode
     */
    public static function setHeader(string $header, string $contentType, bool $replace = true, $statusCode = null): void
    {
        header(
            sprintf(self::HEADER_SCHEMA, $header, $contentType),
            $replace,
            (int)$statusCode
        );
    }
}