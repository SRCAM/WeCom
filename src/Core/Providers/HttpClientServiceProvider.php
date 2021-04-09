<?php


namespace saber\WorkWechat\Core\Providers;


use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class HttpClientServiceProvider   implements ServiceProviderInterface
{

    /**
     * http 注册
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        !isset($pimple['http_client']) && $pimple['http_client'] = function ($app) {
            return new Client($app['config']->get('http', []));
        };
    }
}