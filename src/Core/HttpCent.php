<?php


namespace saber\WorkWechat\Core;


use GuzzleHttp\Middleware;
use MessageFormatter;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LogLevel;
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
     *
     * @return array|bool|float|int|object|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function httpGet(string $url, array $query = [])
    {
        return $this->request($url, 'GET', ['query' => $query]);
    }


    /**
     * 请求处理
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return ResponseInterface
     */
    protected function afterRequestHandle($response): ResponseInterface
    {
        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }

    /**
     *
     * post request.
     * @param string $url
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function httpPost(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['form_params' => $data, 'query' => $query]);
    }

    /**
     * JSON request.
     *
     * @param string $url
     * @param array $data
     * @param array $query
     *
     * @return array
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }
        $response = $this->baseRequest($url, $method, $options);
        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }


    /**
     *注册中间件
     */
    protected function registerHttpMiddlewares()
    {
        $this->pushMiddleware($this->logMiddleware(), 'log');
    }

    protected function logMiddleware()
    {
        $formatter = new \GuzzleHttp\MessageFormatter($this->app['config']['log']['template'] ?? \GuzzleHttp\MessageFormatter::CLF);
        return Middleware::log($this->app['logger'], $formatter, LogLevel::NOTICE);
    }

}