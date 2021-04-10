<?php


namespace saber\WorkWechat\WorkWx\Customer;


use saber\WorkWechat\Core\HttpCent;

class GroupChatClient extends HttpCent
{

    /**
     * 获取客户群列表
     *
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92120
     * @param int $status_filter 客户群跟进状态过滤。
     * @param array $owner_filter 群主过滤。
     * @param string $cursor 用于分页查询的游标，字符串类型，由上一次调用返回，首次调用不填
     * @param int $limit 分页，预期请求的数据量，取值范围 1 ~ 1000
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list($status_filter = 0, $owner_filter = [], $cursor = '', $limit = 10)
    {
        $data = [
            'status_filter' => $status_filter,
            'owner_filter' => $owner_filter,
            'cursor' => $cursor,
            'limit' => $limit
        ];
        return $this->httpPost('/cgi-bin/externalcontact/groupchat/list', $data);
    }

    /**
     * 获取客户群详情
     * @see  https://open.work.weixin.qq.com/api/doc/90000/90135/92122
     * @param string $chat_id 客户群ID
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($chat_id)
    {
        return $this->httpPost('/cgi-bin/externalcontact/groupchat/list', ['chat_id' => $chat_id]);
    }



}