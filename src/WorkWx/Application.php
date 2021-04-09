<?php
namespace saber\WorkWechat\WorkWx;

use saber\WorkWechat\Core\ServiceContainer;
use saber\WorkWechat\WorkWx\User\ServiceProvider;

/**
 * @property \saber\WorkWechat\WorkWx\User\User $user
 * @property \saber\WorkWechat\WorkWx\User\Batch $user_batch
 * @property \saber\WorkWechat\WorkWx\User\Corp  $user_corp
 * Class Application
 * @package saber\WorkWechat\WorkWx
 */
class Application extends ServiceContainer
{
    protected $providers=[
        \saber\WorkWechat\WorkWx\User\ServiceProvider::class
    ];
}