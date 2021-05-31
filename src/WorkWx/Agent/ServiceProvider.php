<?php


namespace saber\WorkWechat\WorkWx\Agent;


use Pimple\Container;
use Pimple\ServiceProviderInterface;


class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['agent'] = function ($app) {
            return new Client($app);
        };

    }
}