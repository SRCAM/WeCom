<?php


namespace saber\WorkWechat\Core\Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class GuzzleHandlerProviders implements ServiceProviderInterface
{

    public function register(Container $pimple)
    {

        if (!empty($pimple['config']['guzzle_handler'])) {
            $guzzle_handler = $pimple['config']['guzzle_handler'];
            !isset($pimple['guzzle_handler']) && $pimple['guzzle_handler'] = function ($app) use ($guzzle_handler) {
                return  new $guzzle_handler();
            };
        }

    }
}