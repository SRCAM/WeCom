<?php

namespace saber\WorkWechat\WorkWx\User;

use Psr\Http\Message\ResponseInterface;
use saber\WorkWechat\Core\HttpCent;

/**
 * 用户管理
 * Class Client
 * @package saber\WorkWechat\WorkWx\UserClient
 */
class UserClient extends HttpCent
{

    /**
     * 创建成员
     * @param string|int $userid 成员UserID
     * @param string $name
     * @param int $mobile
     * @param array $department
     * @param string $email
     * @param array $user_info 额外参数
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create($userid, $name, $mobile, $department, $email = null, $user_info = [])
    {
        $data = [
            'userid' => $userid,
            'name' => $name,
            'mobile' => $mobile,
            'department' => $department,
            'email' => $email
        ];
        $data = array_merge($user_info, $data);
        return $this->httpPost('/cgi-bin/user/create', $data);
    }


    /**
     * 读取成员
     * @param $userid
     * @return array|ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($userid)
    {
        return $this->httpGet('/cgi-bin/user/get', ['userid' => $userid]);
    }

    /**
     * 更新成员
     * @param string $userid 成员UserID
     * @param array $user_info
     * @return array|ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update($userid, $user_info = [])
    {
        $data = [
            'userid' => $userid,
        ];
        $data = array_merge($user_info, $data);

        return $this->httpPost('/cgi-bin/user/update', $data);
    }

    /**
     * 删除成员
     * @param string $userid
     * @return array|ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($userid)
    {
        return $this->httpPost('/cgi-bin/user/update', ['userid' => $userid]);
    }


    /**
     * 批量删除成员
     * @param array $useridlist
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function batchdelete(array $user_ids)
    {
        return $this->httpPostJson('/cgi-bin/user/batchdelete', ['useridlist' => $user_ids]);
    }

    /**
     * 获取部门成员
     * @param int $department_id 获取的部门id
     * @param int $fetch_child 是否递归获取子部门下面的成员：1-递归获取，0-只获取本部门
     * @return array|bool|float|int|object|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function simpleList($department_id, $fetch_child = 0)
    {
        return $this->httpGet('/cgi-bin/user/batchdelete', ['department_id' => $department_id, 'fetch_child' => $fetch_child]);
    }

    /**
     * 获取部门成员详情
     * @param $department_id
     * @param int $fetch_child
     * @return array|bool|float|int|object|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function userlist($department_id, $fetch_child = 0)
    {
        return $this->httpGet('/cgi-bin/user/list', ['department_id' => $department_id, 'fetch_child' => $fetch_child]);
    }

    /**
     * userid转openid
     * @param $user_id
     * @return array|ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function convertToOpenid($user_id)
    {
        return $this->httpPost('/cgi-bin/user/convert_to_openid', ['department_id' => $user_id]);
    }

    /**
     *
     * userid转openid
     * @param $user_id
     * @return array|ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function convertToUserid($open_id)
    {
        return $this->httpPost('/cgi-bin/user/convert_to_userid', ['openid' => $open_id]);
    }


    /**
     * 获取企业活跃成员数
     * @param $date
     * @return array|ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get_active_stat($date)
    {
        return $this->httpPost('/cgi-bin/user/convert_to_userid', ['date' => $date]);
    }


}