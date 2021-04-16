<?php


namespace saber\WorkWechat\WorkWx\Agent;


use saber\WorkWechat\Core\HttpCent;

class AgentClient extends HttpCent
{
    /**获取应用
     * @param  int $agentId
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @see  https://work.weixin.qq.com/api/doc/90001/90143/90363
     */
    public function get( $agentId ){
        return $this->httpGet('/cgi-bin/agent/get', ['agent_id'=>$agentId]);
    }


}