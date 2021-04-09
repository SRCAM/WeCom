<?php


namespace saber\WorkWechat\Core\Providers;


use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use saber\WorkWechat\Core\Log\LogManager;

class LogProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        !isset($pimple['logger']) && $pimple['logger'] = function ($app) {
            return new LogManager($app);
        };
    }
}