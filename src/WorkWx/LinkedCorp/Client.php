<?php


namespace saber\WorkWechat\WorkWx\LinkedCorp;


use saber\WorkWechat\Core\HttpCent;

/**
 * 企业互联
 * Class Client
 * @package saber\WorkWechat\WorkWx\LinkedCorp
 */
class Client extends HttpCent
{
    /**
     * 获取应用的可见范围
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPermList()
    {
        return $this->httpPost('/cgi-bin/linkedcorp/agent/get_perm_list');
    }

    /**
     * 获取互联企业成员详细信息 userids
     * @param $userid
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userGet($userid)
    {
        return $this->httpPost('/cgi-bin/linkedcorp/user/get', ['userid' => $userid]);
    }


    /**
     * 获取互联企业部门成员
     * @param string $departmentId 该字段用的是互联应用可见范围接口返回的department_ids参数，用的是 linkedid + ’/‘ + department_id 拼成的字符串
     * @param int $fetchChild 是否递归获取子部门下面的成员：1-递归获取，0-只获取本部门，不传默认只获取本部门成员
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userSimpleList($departmentId, $fetchChild = 0)
    {
        return $this->httpPost('/cgi-bin/linkedcorp/user/simplelist', ['department_id' => $departmentId, 'fetch_child' => $fetchChild]);
    }

    /**
     * 获取互联企业部门成员详情
     * @param $departmentId
     * @param int $fetchChild
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userList($departmentId, $fetchChild = 0)
    {
        return $this->httpPost('/cgi-bin/linkedcorp/user/list', ['department_id' => $departmentId, 'fetch_child' => $fetchChild]);
    }

    /**
     * 获取互联企业部门列表
     * @param $departmentId
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function departmentList($departmentId)
    {
        return $this->httpPost('/cgi-bin/linkedcorp/department/list', ['department_id' => $departmentId]);
    }
}