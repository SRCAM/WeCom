<?php


namespace saber\WorkWechat\WorkWx\User;


use Pimple\Container;
use Pimple\ServiceProviderInterface;


class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['user'] = function ($app) {
            return new UserClient($app);
        };

        $pimple['user_corp'] = function ($app) {
            return new CorpClient($app);
        };
        $pimple['batch'] = function ($app) {
            return new BatchClient($app);
        };

        $pimple['user_tag'] = function ($app) {
            return new TagClient($app);
        };
    }
}