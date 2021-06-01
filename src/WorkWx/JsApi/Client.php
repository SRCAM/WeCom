<?php

namespace saber\WorkWechat\WorkWx\JsApi;

use saber\WorkWechat\Core\HttpCent;

class Client extends HttpCent
{

    /**
     * 获取企业的ticket
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCorpTicket()
    {
        return $this->httpGet('/cgi-bin/get_jsapi_ticket');
    }


    /**
     *获取应用的Ticket
     */
    public function getAppTicket()
    {
        return $this->httpGet('/cgi-bin/ticket/get',['type'=>'agent_config']);
    }
}