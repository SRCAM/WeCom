<?php


namespace saber\WorkWechat\WorkWx\Session;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use saber\WorkWechat\WorkWx\LinkedCorp\Client;


class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['linked_corp'] = function ($app) {
            return new Client($app);
        };

    }
}