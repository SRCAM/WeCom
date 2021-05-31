<?php

namespace saber\WorkWechat\WorkWx\Session;

use saber\WorkWechat\Core\HttpCent;

class SessionCent extends HttpCent
{
    /**
     * 获取会话内容存档开启成员列表
     * @param null $type 拉取对应版本的开启成员列表。1表示办公版；2表示服务版；3表示企业版。非必填，不填写的时候返回全量成员列表。
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPermitUserList($type = null)
    {
        return $this->httpGet('/cgi-bin/msgaudit/get_permit_user_lis', ['type' => $type]);

    }

    /**
     * 获取会话同意情况
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */

    public function getCheckSingleAgree()
    {
        return $this->httpPost('/cgi-bin/msgaudit/check_single_agree');
    }


    /**
     * 获取会话内容存档内部群信息
     * 企业可通过此接口，获取会话内容存档本企业的内部群信息，包括群名称、群主id、公告、群创建时间以及所有群成员的id与加入时间。
     */
    public function getGroupChat($roomid)
    {
        return $this->httpPost('/cgi-bin/msgaudit/check_single_agree',['roomid'=>$roomid]);
    }
}