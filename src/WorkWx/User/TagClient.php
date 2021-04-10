<?php


namespace saber\WorkWechat\WorkWx\User;


use saber\WorkWechat\Core\HttpCent;

/**
 * 标签
 * Class TagClient
 * @package saber\WorkWechat\WorkWx\UserClient
 */
class TagClient extends HttpCent
{
    /**
     * 创建
     * @param string $tagname 标签名称
     * @param int|string $tagid
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create($tagname, $tagid = '')
    {
        return $this->httpPost('/cgi-bin/tag/create', ['tagname' => $tagname, 'tagid' => $tagid]);
    }

    /**
     * 更新标签名字
     * @param $tagid
     * @param $tagname
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update($tagid, $tagname)
    {
        return $this->httpPost('/cgi-bin/tag/update', ['tagname' => $tagname, 'tagid' => $tagid]);
    }

    /**
     * 删除标签
     * @param int $tagid
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($tagid)
    {
        return $this->httpGet('/cgi-bin/tag/delete', ['tagid' => $tagid]);
    }

    /**
     * 获取标签成员
     * @param $tagid
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($tagid)
    {
        return $this->httpGet('/cgi-bin/tag/get', ['tagid' => $tagid]);
    }


    /**
     * 增加标签成员
     * @param int $tagid 标签ID
     * @param array $userlist 企业成员ID列表，注意：userlist、partylist不能同时为空，单次请求个数不超过1000
     * @param array $partylist 企业部门ID列表，注意：userlist、partylist不能同时为空，单次请求个数不超过100
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addtagusers($tagid, $userlist = [], $partylist = [])
    {
        return $this->httpPost('/cgi-bin/tag/addtagusers', ['tagid' => $tagid, 'userlist' => $userlist, 'partylist' => $partylist]);
    }

    /**
     * 获取标签列表
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list()
    {
       return $this->httpGet('/cgi-bin/tag/list');
    }
}