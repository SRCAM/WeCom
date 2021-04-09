<?php


namespace saber\WorkWechat\WorkWx\User;


use Pimple\Container;
use Pimple\ServiceProviderInterface;


class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['user'] = function ($app) {
            return new User($app);
        };

        $pimple['user_corp'] = function ($app) {
            return new Corp($app);
        };
        $pimple['user_batch'] = function ($app) {
            return new Batch($app);
        };
    }
}