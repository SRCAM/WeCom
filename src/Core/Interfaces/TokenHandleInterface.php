<?php
namespace saber\WorkWechat\Core\Interfaces;
/**
 * 获取access_token 接口
 * Interface TokenHandleInterface
 */
interface TokenHandleInterface
{


    public static  function  getInstance():self ;
    /**
     * @return string 获取token
     */
    public function getAccessToken():string;
}