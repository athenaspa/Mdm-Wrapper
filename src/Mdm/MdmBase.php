<?php

namespace Athena\Mdm;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;
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
    static $accessToken;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * MdmService constructor.
     */
    public function __construct()
    {
        $this->config = $this->getConfig();
    }

    /**
     * @return Configuration
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
    public function getAccessToken()
    {
        if (empty(self::$accessToken)) {
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
            self::$accessToken = $token->getAccessToken();
        }

        return self::$accessToken;
    }

    /**
     * Unset access token
     */
    private function refreshClientAccessToken()
    {
        self::$accessToken = NULL;
    }

    /**
     * @return HttpClient
     * @throws ApiException
     */
    public function getHttpClient()
    {
        $handlerStack = HandlerStack::create(new CurlHandler());
        $handlerStack->push(GuzzleRetryMiddleware::factory());

        return new HttpClient(
            [
                'handler' => $handlerStack,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getAccessToken(),
                ],
                'max_retry_attempts' => self::MAX_RETRIES,
                'retry_on_status' => [401, 500],
                'on_retry_callback' => $this->refreshClientAccessToken(),
                'retry_on_timeout' => true,
                'connect_timeout' => 20,
            ]
        );
    }

}
