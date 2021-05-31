<?php


namespace saber\WorkWechat\WorkWx\Agent;


use saber\WorkWechat\Core\HttpCent;

class Client extends HttpCent
{
    /**获取应用
     * @param int $agentId
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @link   https://work.weixin.qq.com/api/doc/90001/90143/90363
     */
    public function get($agentId)
    {
        return $this->httpGet('/cgi-bin/agent/get', ['agent_id' => $agentId]);
    }


    /**
     * 设置应用
     * @param $agentid
     * @param array $data
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @link  https://open.work.weixin.qq.com/api/doc/90000/90135/90228
     */
    public function set($agentid, $data = [])
    {
        return $this->httpPost('/cgi-bin/agent/set',array_merge([['agentid'=>$agentid],$data]));
    }



}