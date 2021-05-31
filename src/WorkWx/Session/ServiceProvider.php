<?php


namespace saber\WorkWechat\WorkWx\Session;


use Pimple\Container;
use Pimple\ServiceProviderInterface;


class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['session'] = function ($app) {
            return new Client($app);
        };

    }
}