<?php


namespace saber\WorkWechat\Core;


use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;
use saber\WorkWechat\Core\Exceptions\AccessTokenNotFindExceptions;
use saber\WorkWechat\Core\Exceptions\InvalidAccessTokenExceptions;
use saber\WorkWechat\Core\Exceptions\NotInstanceofExceptions;
use saber\WorkWechat\Core\Interfaces\TokenHandleInterface;
use saber\WorkWechat\Core\Traits\HasHttpRequests;

class HttpCent
{
    use HasHttpRequests {
        request as baseRequest;
    }


    /**
     *
     * @var ServiceContainer $app
     */
    protected $app;


    /**
     * 前置url
     * @var array
     */
    protected $baseUri = 'https://qyapi.weixin.qq.com';


    protected $options = [];

    /**
     * http 客户端
     * @var \GuzzleHttp\Client $Cent
     */
    protected $httpClient;


    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }


    /**
     *
     * GET 请求
     * @param string $url
     * @param array $query
     * @return array|mixed|object|ResponseInterface|Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function httpGet(string $url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * post request.
     * @param string $url
     * @param array $data
     * @param array $query
     * @return array|mixed|object|ResponseInterface|Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function httpPost(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['form_params' => $data, 'query' => $query]);
    }

    /**
     * JSON request.
     * @param string $url
     * @param array $data
     * @param array $query
     * @return array|mixed|object|ResponseInterface|Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function httpPostJson(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query, 'json' => $data]);
    }


    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @param bool $returnRaw
     * @return array|mixed|object|ResponseInterface|Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws NotInstanceofExceptions
     * @throws AccessTokenNotFindExceptions
     */
    protected function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $token_handle = $this->app->config['token_handle'];
        if (!class_exists($token_handle)) {
            $this->app['logger']->error("$token_handle  not Instanceof TokenHandleInterface ");
            throw new NotInstanceofExceptions("$token_handle  not Instanceof \saber\WorkWechat\Core\Interfaces\TokenHandleInterface ");
        }

        $ref = new \ReflectionClass($token_handle);
        /**
         * @var $handle TokenHandleInterface
         */
        $handle = $ref->newInstanceWithoutConstructor();
        if (!$handle instanceof TokenHandleInterface) {
            $this->app['logger']->error("$token_handle  not Instanceof  TokenHandleInterface ");
            throw new NotInstanceofExceptions("$token_handle  not Instanceof TokenHandleInterface ");
        }

        if (!$access_token = $token_handle::getInstance()->getAccessToken()) {
            $this->app['logger']->error("AccessToken not find");
            throw new AccessTokenNotFindExceptions("AccessToken not find");
        }

        $options['query'] = array_merge(['access_token' => $access_token], $options['query']);

        $response = $this->baseRequest($url, $method, $options);
        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }


    /**
     *注册中间件
     */
    protected function registerHttpMiddlewares()
    {   // retry
        $this->pushMiddleware($this->retryMiddleware(), 'retry');
        //log
        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    /**
     * @return callable|\Closure
     */
    protected function logMiddleware()
    {
        $formatter = new \GuzzleHttp\MessageFormatter($this->app['config']['log']['template'] ?? \GuzzleHttp\MessageFormatter::CLF);
        return Middleware::log($this->app['logger'], $formatter, LogLevel::NOTICE);
    }



    protected function retryMiddleware()
    {
        return Middleware::retry(
            function (
                $retries,
                RequestInterface $request,
                ResponseInterface $response = null
            ) {
                // Limit the number of retries to 2
                if ($response && $body = $response->getBody()) {
                    // Retry on server errors
                    $response = json_decode($body, true);
                    if (!empty($response['errcode']) && in_array(abs($response['errcode']), [40001, 40014, 42001], true)) {
                        $this->app['logger']->debug($response['errmsg']);
                        throw new InvalidAccessTokenExceptions($response['errmsg']);

                    }

                    return  false;
                }

                return false;
            },
            function () {
                return abs($this->app->config->get('http.retry_delay', 500));
            }
        );
    }

}