<?php


namespace saber\WorkWechat;


use think\helper\Str;

/**
 * @method  static  \saber\WorkWechat\WorkWx\Application WorkWx(array $config)
 * Class Factory
 * @package saber\VoiceToText
 */
class Factory
{
    /**
     * @param string $name
     * @param array $config
     * @return mixed
     */
    public static function make($name, array $config)
    {
        $namespace = Str::studly($name);
        $application = "\\saber\\WorkWechat\\{$namespace}\\Application";
        return new $application($config);
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}