<?php


namespace saber\WorkWechat\WorkWx\Message;


use Pimple\Container;
use Pimple\ServiceProviderInterface;


/**
 * 部门
 * Class ServiceProvider
 * @package saber\WorkWechat\WorkWx\Department
 */
class ServiceProvider implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {
        $pimple['message'] = function ($app) {
            return new Client($app);
        };
    }
}