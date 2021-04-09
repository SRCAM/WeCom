<?php

namespace saber\WorkWechat\Core\Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use saber\WorkWechat\Core\Config;

class ConfigProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        if (!isset($pimple['config']) ){
            $pimple['config'] = function ($app) {
                return new Config($app->getConfig());
            };
        }
    }
}