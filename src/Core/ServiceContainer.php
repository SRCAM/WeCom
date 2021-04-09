<?php


namespace saber\WorkWechat\Core;


use Monolog\Logger;
use Pimple\Container;
use saber\WorkWechat\Core\Providers\ConfigProvider;
use saber\WorkWechat\Core\Providers\EventDispatcherServiceProvider;
use saber\WorkWechat\Core\Providers\GuzzleHandlerProviders;
use saber\WorkWechat\Core\Providers\HttpClientServiceProvider;
use saber\WorkWechat\Core\Providers\LogProvider;

class ServiceContainer extends Container
{
    /**
     * 配置文件
     * @var array
     */
    protected $config = [];

    /**
     * 注册树
     * @var
     */
    protected $providers = [];


    /**
     * ServiceContainer constructor.
     * @param array $config
     * @param array $values
     */
    public function __construct(array $config = [], array $values = [])
    {
        $this->config = $config;
        parent::__construct($values);
        $this->registerProviders($this->getProviders());

    }

    /**
     * 获取配置文件
     * @return array
     */
    public function getConfig()
    {
        $base = [
            // http://docs.guzzlephp.org/en/stable/request-options.html
            'http' => [
                'timeout' => 30.0,
                'base_url' => 'https://qyapi.weixin.qq.com'
            ],

            'log.default'=>'default',
            'log.channels.default'=>[
                'driver'=>'Single',
                'path'=>'F:/test/WorkWx/WorkChat/default.log'
            ]

        ];


        return array_replace_recursive($base, $this->config);
    }


    /**
     * Return all providers.
     *
     * @return array
     */
    public function getProviders()
    {
        return array_merge([
            ConfigProvider::class,
            HttpClientServiceProvider::class,
            GuzzleHandlerProviders::class,
            LogProvider::class
        ], $this->providers);
    }


    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }


    public function shouldDelegate($id)
    {
        $this->config->get('delegation.enabled');
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}