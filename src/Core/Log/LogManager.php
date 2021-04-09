<?php

namespace saber\WorkWechat\Core\Log;

use InvalidArgumentException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\FormattableHandlerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\WhatFailureGroupHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use saber\WorkWechat\Core\ServiceContainer;

class LogManager implements LoggerInterface
{

    /**
     * @var ServiceContainer
     */
    protected $app;

    /**
     * The array of resolved channels.
     *
     * @var array
     */
    protected $channels = [];

    /**
     * The registered custom driver creators.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug' => Logger::DEBUG,
        'info' => Logger::INFO,
        'notice' => Logger::NOTICE,
        'warning' => Logger::WARNING,
        'error' => Logger::ERROR,
        'critical' => Logger::CRITICAL,
        'alert' => Logger::ALERT,
        'emergency' => Logger::EMERGENCY,
    ];

    /**
     * LogManager constructor.
     *
     * @param \EasyWeChat\Kernel\ServiceContainer $app
     */
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * Create a new, on-demand aggregate logger instance.
     *
     * @param array       $channels
     * @param string|null $channel
     *
     * @return \Psr\Log\LoggerInterface
     *
     * @throws \Exception
     */
    public function stack(array $channels, $channel = null)
    {
        return $this->createStackDriver(compact('channels', 'channel'));
    }

    /**
     * Get a log channel instance.
     *
     * @param string|null $channel
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function channel($channel = null)
    {
        return $this->driver($channel);
    }

    /**
     * Get a log driver instance.
     *
     * @param string|null $driver
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function driver($driver = null)
    {

        return $this->get($driver ?? $this->getDefaultDriver());
    }

    /**
     * Attempt to get the log from the local cache.
     *
     * @param string $name
     *
     * @return \Psr\Log\LoggerInterface
     *
     * @throws \Exception
     */
    protected function get($name)
    {

        try {
            return $this->channels[$name] ?? ($this->channels[$name] = $this->resolve($name));
        } catch (\Throwable $e) {

            $logger = $this->createEmergencyLogger();

            $logger->emergency('Unable to create configured logger. Using emergency logger.', [
                'exception' => $e,
            ]);

            return $logger;
        }
    }

    /**
     * Resolve the given log instance by name.
     *
     * @param string $name
     *
     * @return \Psr\Log\LoggerInterface
     *
     * @throws InvalidArgumentException
     */
    protected function resolve($name)
    {



        $config = $this->app['config']->get(\sprintf('log.channels.%s', $name));

        if (is_null($config)) {
            throw new InvalidArgumentException(\sprintf('Log [%s] is not defined.', $name));
        }


        if (isset($this->customCreators[$config['driver']])) {
            return $this->callCustomCreator($config);
        }


        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (method_exists($this, $driverMethod)) {

            return $this->{$driverMethod}($config);
        }


        throw new InvalidArgumentException(\sprintf('Driver [%s] is not supported.', $config['driver']));
    }

    /**
     * Create an emergency log handler to avoid white screens of death.
     *
     * @return \Monolog\Logger
     *
     * @throws \Exception
     */
    protected function createEmergencyLogger()
    {

        $path = $this->getPath();
        return new Logger('WorkChat', $this->prepareHandlers([new StreamHandler(
            $path,
            $this->level(['level' => 'debug'])
        )]));
    }

    /**
     * Call a custom driver creator.
     *
     * @param array $config
     *
     * @return mixed
     */
    protected function callCustomCreator(array $config)
    {
        return $this->customCreators[$config['driver']]($this->app, $config);
    }

    /**
     * Create an aggregate log driver instance.
     *
     * @param array $config
     *
     * @return \Monolog\Logger
     *
     * @throws \Exception
     */
    protected function createStackDriver(array $config)
    {
        $handlers = [];

        foreach ($config['channels'] ?? [] as $channel) {
            $handlers = \array_merge($handlers, $this->channel($channel)->getHandlers());
        }

        if ($config['ignore_exceptions'] ?? false) {
            $handlers = [new WhatFailureGroupHandler($handlers)];
        }

        return new Logger($this->parseChannel($config), $handlers);
    }

    /**
     * Create an instance of the single file log driver.
     *
     * @param array $config
     *
     * @return \Psr\Log\LoggerInterface
     *
     * @throws \Exception
     */
    protected function createSingleDriver(array $config)
    {



        return new Logger($this->parseChannel($config), [
            $this->prepareHandler(new StreamHandler(
                $config['path'],
                $this->level($config),
                $config['bubble'] ?? true,
                $config['permission'] ?? null,
                $config['locking'] ?? false
            ), $config),
        ]);
    }

    /**
     * Create an instance of the daily file log driver.
     *
     * @param array $config
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createDailyDriver(array $config)
    {

//        $path = $this->getPath($config);
        return new Logger($this->parseChannel($config), [
            $this->prepareHandler(new RotatingFileHandler(

                $config['path'],
                $config['days'] ?? 7,
                $this->level($config),
                $config['bubble'] ?? true,
                $config['permission'] ?? null,
                $config['locking'] ?? false
            ), $config),
        ]);
    }


    /**
     * @param array $name
     * @return string
     */
    private function getPath($name=[]){

        $date = date("Y-m-d");
        $name = isset($name['level'])?isset($name['level']):'debug';
        return empty($this->app->config['log.path'])? dirname(dirname(dirname(__DIR__)))."/WorkChat/{$date}-{$name}.log":$this->app->config['log.path']."/{$date}-{$name}.log";
    }
    /**
     * Create an instance of the Slack log driver.
     *
     * @param array $config
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createSlackDriver(array $config)
    {
        return new Logger($this->parseChannel($config), [
            $this->prepareHandler(new SlackWebhookHandler(
                $config['url'],
                $config['channel'] ?? null,
                $config['username'] ?? 'EasyWeChat',
                $config['attachment'] ?? true,
                $config['emoji'] ?? ':boom:',
                $config['short'] ?? false,
                $config['context'] ?? true,
                $this->level($config),
                $config['bubble'] ?? true,
                $config['exclude_fields'] ?? []
            ), $config),
        ]);
    }

    /**
     * Create an instance of the syslog log driver.
     *
     * @param array $config
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createSyslogDriver(array $config)
    {

        return new Logger($this->parseChannel($config), [
            $this->prepareHandler(new SyslogHandler(
                'WorkChat',
                $config['facility'] ?? LOG_USER,
                $this->level($config)
            ), $config),
        ]);
    }

    /**
     * Create an instance of the "error log" log driver.
     *
     * @param array $config
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function createErrorlogDriver(array $config)
    {
        return new Logger($this->parseChannel($config), [
            $this->prepareHandler(
                new ErrorLogHandler(
                    $config['type'] ?? ErrorLogHandler::OPERATING_SYSTEM,
                    $this->level($config)
                )
            ),
        ]);
    }

    /**
     * Prepare the handlers for usage by Monolog.
     *
     * @param array $handlers
     *
     * @return array
     */
    protected function prepareHandlers(array $handlers)
    {
        foreach ($handlers as $key => $handler) {
            $handlers[$key] = $this->prepareHandler($handler);
        }

        return $handlers;
    }

    /**
     * Prepare the handler for usage by Monolog.
     *
     * @param \Monolog\Handler\HandlerInterface $handler
     *
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function prepareHandler(HandlerInterface $handler, array $config = [])
    {
        if (!isset($config['formatter'])) {
            if ($handler instanceof FormattableHandlerInterface) {
                $handler->setFormatter($this->formatter());
            }
        }

        return $handler;
    }

    /**
     * Get a Monolog formatter instance.
     *
     * @return \Monolog\Formatter\FormatterInterface
     */
    protected function formatter()
    {

        $formatter = new LineFormatter(null, null, true, true);
        $formatter->includeStacktraces();

        return $formatter;
    }

    /**
     * Extract the log channel from the given configuration.
     *
     * @param array $config
     *
     * @return string
     */
    protected function parseChannel(array $config)
    {
        return $config['name'] ?? 'EasyWeChat';
    }

    /**
     * Parse the string level into a Monolog constant.
     *
     * @param array $config
     *
     * @return int
     *
     * @throws InvalidArgumentException
     */
    protected function level(array $config)
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new InvalidArgumentException('Invalid log level.');
    }

    /**
     * Get the default log driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['log.default'];
    }

    /**
     * Set the default log driver name.
     *
     * @param string $name
     */
    public function setDefaultDriver($name)
    {
        $this->app['config']['log.default'] = $name;
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param string   $driver
     * @param \Closure $callback
     *
     * @return $this
     */
    public function extend($driver, \Closure $callback)
    {
        $this->customCreators[$driver] = $callback->bindTo($this, $this);

        return $this;
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function emergency($message, array $context = [])
    {
        return $this->driver()->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function alert($message, array $context = [])
    {
        return $this->driver()->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function critical($message, array $context = [])
    {
        return $this->driver()->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function error($message, array $context = [])
    {
        return $this->driver()->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function warning($message, array $context = [])
    {
        return $this->driver()->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function notice($message, array $context = [])
    {
        return $this->driver()->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function info($message, array $context = [])
    {
        return $this->driver()->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function debug($message, array $context = [])
    {
        return $this->driver()->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function log($level, $message, array $context = [])
    {
        return $this->driver()->log($level, $message, $context);
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function __call($method, $parameters)
    {

        return $this->driver()->$method(...$parameters);
    }
}