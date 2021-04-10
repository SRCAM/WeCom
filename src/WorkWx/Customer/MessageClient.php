<?php


namespace saber\WorkWechat\WorkWx\Customer;


use saber\WorkWechat\Core\HttpCent;

class MessageClient extends HttpCent
{
    /**
     * 创建企业群发
     * @param string $chat_type 群发任务的类型，默认为single，表示发送给客户，group表示发送给客户群
     * @param array $external_userid 客户的外部联系人id列表，仅在chat_type为single时有效，不可与sender同时为空，最多可传入1万个客户
     * @param string $sender 发送企业群发消息的成员userid，当类型为发送给客户群时必填
     * @param array $param 其他参数
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addMsgTemplate($chat_type, $external_userid = [], $sender = '', $param = [])
    {
        $data = [
            'chat_type' => $chat_type,
            'external_userid' => $external_userid,
            'sender' => $sender,
        ];
        return $this->httpPost('/cgi-bin/externalcontact/add_msg_template', array_merge($data, $param));
    }

    /**
     * 获取企业的全部群发记录
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/93338#%E8%8E%B7%E5%8F%96%E7%BE%A4%E5%8F%91%E8%AE%B0%E5%BD%95%E5%88%97%E8%A1%A8
     * @param string $chat_type 群发任务的类型，默认为single，表示发送给客户，group表示发送给客户群
     * @param string $start_time 群发任务记录开始时间
     * @param string $end_time 群发任务记录结束时间
     * @param string $cursor 用于分页查询的游标，字符串类型，由上一次调用返回，首次调用可不填
     * @param array $param 其他参数
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getGroupMsgList($chat_type, $start_time, $end_time, $cursor = '', $param = [])
    {
        $data = [
            'chat_type' => $chat_type,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'cursor' => $cursor
        ];
        return $this->httpPost('/cgi-bin/externalcontact/get_groupmsg_list_v2', array_merge($data, $param));
    }

    /**
     *
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/93338#%E8%8E%B7%E5%8F%96%E7%BE%A4%E5%8F%91%E6%88%90%E5%91%98%E5%8F%91%E9%80%81%E4%BB%BB%E5%8A%A1%E5%88%97%E8%A1%A8
     * @param string $msgid 群发消息的id，通过获取群发记录列表接口返回
     * @param int $limit 返回的最大记录数，整型，最大值1000，默认值500，超过最大值时取默认值
     * @param string $cursor 用于分页查询的游标，字符串类型，由上一次调用返回，首次调用可不填
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getGroupMsgTask($msgid, $limit = 500, $cursor = '')
    {
        $data = [
            'msgid' => $msgid,
            'limit' => $limit,
            'cursor' => $cursor
        ];

        return $this->httpPost('/cgi-bin/externalcontact/get_groupmsg_task', $data);
    }

    /**
     * 发送新客户欢迎语
     * @param string $welcome_code 通过添加外部联系人事件推送给企业的发送欢迎语的凭证，有效期为20秒
     * @param string $text 文字消息
     * @param array $param 其他参数
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendWelcomeMsg($welcome_code, $text = '', $param = [])
    {
        $this->httpPost('/cgi-bin/externalcontact/send_welcome_msg', array_merge(['welcome_code' => $welcome_code, 'text' => $text], $param));
    }
}