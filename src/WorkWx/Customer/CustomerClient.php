<?php


namespace saber\WorkWechat\WorkWx\Customer;


use saber\WorkWechat\WorkWx\User\BatchClient;

class CustomerClient extends BatchClient
{
    /**
     * 获取客户列表
     *
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92113
     * @param string|int $userid 企业成员的userid
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list($userid)
    {
        return $this->httpGet('/cgi-bin/externalcontact/list', ['userid' => $userid]);
    }

    /**
     * 获取客户详情
     *
     * @see  https://open.work.weixin.qq.com/api/doc/90000/90135/92114
     * @param string $externaUserid 外部联系人的userid，注意不是企业成员的帐号
     * @param string $cursor 上次请求返回的next_cursor
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($externaUserid, $cursor = '')
    {
        return $this->httpGet('/cgi-bin/externalcontact/list', ['external_userid' => $externaUserid, 'cursor' => $cursor]);
    }

    /**
     * 批量获取客户详情
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92994
     * @param string $userid 企业成员的userid
     * @param string $cursor 用于分页查询的游标，字符串类型，由上一次调用返回，首次调用可不填
     * @param int $limit 返回的最大记录数，整型，最大值100，默认值50，超过最大值时取最大值
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getByUser($userid, $cursor = '', $limit = 100)
    {
        return $this->httpGet('/cgi-bin/externalcontact/batch/get_by_user', ['userid' => $userid, 'cursor' => $cursor, 'limit' => $limit]);
    }

    /**
     * 修改客户备注信息
     * @param string $userid 企业成员的userid
     * @param string $externalUserid 外部联系人userid
     * @param array $param   其他请求参数
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateRemark($userid,$externalUserid,$param=[])
    {
        $data =['userid' => $userid, 'external_userid' =>$externalUserid];
        return $this->httpGet('/cgi-bin/externalcontact/batch/get_by_user', array_merge($data,$param));
    }

}