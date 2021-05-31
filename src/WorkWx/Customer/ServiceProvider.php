<?php


namespace saber\WorkWechat\WorkWx\Customer;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use saber\WorkWechat\WorkWx\Department\Client;

class ServiceProvider implements ServiceProviderInterface
{


    public function register(Container $pimple)
    {
        $pimple['customer_message'] = function ($app) {
            return new MessageClient($app);
        };


        $pimple['group_chat'] = function ($app) {
            return new GroupChatClient($app);
        };

        $pimple['customer'] = function ($app) {
            return new CustomerClient($app);
        };

        $pimple['contact_way'] = function ($app) {
            return new CustomerClient($app);
        };
    }
}