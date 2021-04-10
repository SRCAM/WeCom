<?php
namespace saber\WorkWechat\WorkWx;

use saber\WorkWechat\Core\ServiceContainer;
use saber\WorkWechat\WorkWx\Department\Client as DepartmentClient;
use saber\WorkWechat\WorkWx\User\BatchClient;
use saber\WorkWechat\WorkWx\User\CorpClient;
use saber\WorkWechat\WorkWx\User\TagClient;
use saber\WorkWechat\WorkWx\User\UserClient;
use \saber\WorkWechat\WorkWx\LinkedCorp\Client as LinkedCorpClient;
/**
 * @property UserClient        $user      用户
 * @property BatchClient       $batch     批量处理
 * @property CorpClient        $user_corp
 * @property TagClient         $user_tag    用户标签
 * @property DepartmentClient  $department  部门
 * @property LinkedCorpClient  $linked_corp 企业互联
 * Class Application
 * @package saber\WorkWechat\WorkWx
 */
class Application extends ServiceContainer
{
    protected $providers=[
        \saber\WorkWechat\WorkWx\User\ServiceProvider::class,
        \saber\WorkWechat\WorkWx\Department\ServiceProvider::class,
        \saber\WorkWechat\WorkWx\LinkedCorp\ServiceProvider::class
    ];
}