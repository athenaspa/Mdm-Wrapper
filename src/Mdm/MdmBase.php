<?php

namespace Athena\Mdm;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Swagger\Client\ApiException;
use Swagger\Client\Configuration;
use Swagger\Client\Model\TokenRequest;
use Swagger\Client\Api\AuthApi;

/**
 * Class MdmBase
 * @package Athena\Mdm
 */
class MdmBase
{

    /**
     * @var int
     */
    const MAX_RETRIES = 3;

    /**
     * @var string
     */
    static $accesToken;

    /**
     * @var \Swagger\Client\Configuration
     */
    protected $config;

    /**
     * MdmService constructor.
     * @throws ApiException
     */
    public function __construct()
    {
        $this->config = $this->getConfig();
    }

    /**
     * @return \Swagger\Client\Configuration
     */
    public function getConfig()
    {
        if (empty($this->config)) {
            $config = new Configuration();
            $config->setHost(getenv('HOST'));
            $this->config = $config;
        }
        return $this->config;
    }

    /**
     * @return string
     * @throws ApiException
     */
    public function getAccesToken()
    {
        if (empty(self::$accesToken)) {
            $token_request = new TokenRequest();
            $token_request
                ->setGrantType('password_grant')
                ->setClientId(getenv('CLIENT_ID'))
                ->setClientSecret(getenv('CLIENT_SECRET'))
                ->setEmail(getenv('EMAIL'))
                ->setPassword(getenv('PASSWORD'));

            $auth_instance = new AuthApi();
            $auth_instance->getConfig()->setHost(getenv('HOST'));
            $token = $auth_instance->authTokenPost($token_request);
            self::$accesToken = $token->getAccessToken();
        }

        return self::$accesToken;
    }

    /**
     * @return HttpClient
     * @throws ApiException
     */
    protected function getHttpClient()
    {
        $handlerStack = HandlerStack::create(new CurlHandler());
        $handlerStack->push(Middleware::retry($this->retryDecider()));

        return new HttpClient(
            [
                'handler' => $handlerStack,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getAccesToken(),
                ],
            ]
        );
    }

    /**
     * @throws ApiException
     */
    protected function refreshClientAccessToken()
    {
        self::$accesToken = NULL;
        $this->httpClient = $this->getHttpClient();
    }

    /**
     * @return \Closure
     */
    private function retryDecider()
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) {
            // Limit the number of retries
            if ($retries >= self::MAX_RETRIES) {
                return false;
            }

            // Retry connection exceptions
            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response) {
                // Retry on server errors
                if ($response->getStatusCode() >= 500) {
                    return true;
                }
                // received 401, so we need to refresh the token
                if ($response->getStatusCode() === 401) {
                    $this->refreshClientAccessToken();
                    return true;
                }
            }

            return false;
        };
    }

}
