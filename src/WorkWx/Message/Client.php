<?php


namespace saber\WorkWechat\WorkWx\Message;


use saber\WorkWechat\Core\HttpCent;

class Client extends HttpCent
{
    /**发送应用消息
     * @param array $param
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see https://work.weixin.qq.com/api/doc/90001/90143/90372
     */
    public function send( $param ){
        return $this->httpPostJson('/cgi-bin/message/send', $param);
    }

    /**更新任务卡片消息
     * @param $param
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see https://work.weixin.qq.com/api/doc/90001/90143/91585
     */
    public function updateTaskCard( $param ){
        return $this->httpPostJson( '/cgi-bin/message/update_taskcard', $param);
    }


}