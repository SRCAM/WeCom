<?php
namespace saber\WorkWechat\WorkWx;

use saber\WorkWechat\Core\ServiceContainer;
use saber\WorkWechat\WorkWx\Customer\ContactWayClient;
use saber\WorkWechat\WorkWx\Customer\CustomerClient;
use saber\WorkWechat\WorkWx\Customer\GroupChatClient;
use saber\WorkWechat\WorkWx\Customer\MessageClient as CustomerMessageClient;
use saber\WorkWechat\WorkWx\Department\Client as DepartmentClient;
use saber\WorkWechat\WorkWx\Session\Client as SessionClient;
use saber\WorkWechat\WorkWx\User\BatchClient;
use saber\WorkWechat\WorkWx\User\CorpClient;
use saber\WorkWechat\WorkWx\User\TagClient;
use saber\WorkWechat\WorkWx\User\UserClient;
use saber\WorkWechat\WorkWx\LinkedCorp\Client as LinkedCorpClient;
use saber\WorkWechat\WorkWx\Agent\Client  as AgentClient;
use saber\WorkWechat\WorkWx\JsApi\Client  as JsApiClient;
use saber\WorkWechat\WorkWx\Message\Client  as MessageClient;
/**
 * @property UserClient                $user      用户
 * @property BatchClient               $batch     批量处理
 * @property CorpClient                $user_corp
 * @property TagClient                 $user_tag    用户标签
 * @property DepartmentClient          $department  部门
 * @property LinkedCorpClient          $linked_corp 企业互联
 * @property CustomerMessageClient     $customer_message 企业互联
 * @property GroupChatClient           $group_chat 企业互联
 * @property CustomerClient            $customer 客户
 * @property ContactWayClient          $contact_way 联系方式
 * @property SessionClient             $session 会话存档
 * @property AgentClient               $agent 应用
 * @property JsApiClient               $js_api jsapi
 * @property MessageClient             $message 应用消息
 *
 * Class Application
 * @package saber\WorkWechat\WorkWx
 */
class Application extends ServiceContainer
{


    protected $providers=[
        \saber\WorkWechat\WorkWx\User\ServiceProvider::class,
        \saber\WorkWechat\WorkWx\Department\ServiceProvider::class,
        \saber\WorkWechat\WorkWx\LinkedCorp\ServiceProvider::class,
        \saber\WorkWechat\WorkWx\Customer\ServiceProvider::class,
        \saber\WorkWechat\WorkWx\Session\ServiceProvider::class,
        \saber\WorkWechat\WorkWx\Agent\ServiceProvider::class,
        \saber\WorkWechat\WorkWx\JsApi\ServiceProvider::class,
        \saber\WorkWechat\WorkWx\Message\ServiceProvider::class,
    ];
}