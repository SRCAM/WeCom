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
use saber\WorkWechat\Core\Config;
use saber\WorkWechat\Core\ServiceContainer;

class LogManager implements LoggerInterface
{

    /**
     * @var ServiceContainer
     */
    protected $app;




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
     * @param Config $config
     * @return Logger
     */
    public function createLogger( $config)
    {

        return new Logger('WorkWx', [
            $this->prepareHandler(new RotatingFileHandler(
                $config['log']['path'],
                $config['days'] ?? 7,
                $this->level($config->toArray()),
                $config['bubble'] ?? true,
                $config['permission'] ?? null,
                $config['locking'] ?? false
            ), $config->toArray()),
        ]);
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
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function emergency($message, array $context = [])
    {
        return $this->createLogger($this->app['config'])->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function alert($message, array $context = [])
    {
        return $this->createLogger($this->app['config'])->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function critical($message, array $context = [])
    {
        return $this->createLogger($this->app['config'])->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function error($message, array $context = [])
    {
        return $this->createLogger($this->app['config'])->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function warning($message, array $context = [])
    {
        return $this->createLogger($this->app['config'])->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function notice($message, array $context = [])
    {
        return $this->createLogger($this->app['config'])->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: UserClient logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function info($message, array $context = [])
    {
        return $this->createLogger($this->app['config'])->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function debug($message, array $context = [])
    {
        return $this->createLogger($this->app['config'])->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function log($level, $message, array $context = [])
    {
        return $this->createLogger($this->app['config'])->log($level, $message, $context);
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array $parameters
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