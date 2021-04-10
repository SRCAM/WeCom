<?php


namespace saber\WorkWechat\WorkWx\Customer;


use saber\WorkWechat\Core\HttpCent;

/**
 * 客户分配
 * Class TransferClient
 * @package saber\WorkWechat\WorkWx\Customer
 */
class TransferClient extends HttpCent
{
    /**
     * 分配在职成员的客户
     * @see  https://open.work.weixin.qq.com/api/doc/90000/90135/92125
     * @param string $handoverUserid 原跟进成员的userid
     * @param string $takeoverUserid 接替成员的userid
     * @param array $externalUserid 客户的external_userid列表，每次最多分配100个客户
     * @param string $transferSuccessMsg
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function transferCustomer($handoverUserid, $takeoverUserid, $externalUserid, $transferSuccessMsg = '')
    {
        $data = [
            'handover_userid' => $handoverUserid,
            'takeover_userid' => $takeoverUserid,
            'external_userid' => $externalUserid,
            'transfer_success_msg' => $transferSuccessMsg
        ];
        return $this->httpPost('/cgi-bin/externalcontact/transfer_customer', $data);
    }


    /**
     * 查询客户接替状态
     * @see  https://open.work.weixin.qq.com/api/doc/90000/90135/94088
     * @param string $handoverUserid 原跟进成员的userid
     * @param string $takeoverUserid 接替成员的userid
     * @param string $cursor 分页查询的cursor，每个分页返回的数据不会超过1000条；不填或为空表示获取第一个分页；
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function transferResult($handoverUserid, $takeoverUserid, $cursor = '')
    {
        $data = [
            'handover_userid' => $handoverUserid,
            'takeover_userid' => $takeoverUserid,
            'cursor' => $cursor
        ];
        return $this->httpPost('/cgi-bin/externalcontact/transfer_result', $data);
    }

    /**
     * 获取待分配的离职成员列表
     * @see  https://open.work.weixin.qq.com/api/doc/90000/90135/92124
     * @param string $pageId 分页查询，要查询页号，从0开始
     * @param string $pageSize 每次返回的最大记录数，默认为1000，最大值为1000
     * @param string $cursor 分页查询游标，字符串类型，适用于数据量较大的情况，如果使用该参数则无需填写page_id，该参数由上一次调用返回
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUnassignedList($pageId = '', $pageSize = '', $cursor = '')
    {
        $data = [
            'page_id' => $pageId,
            'page_size' => $pageSize,
            'cursor' => $cursor
        ];
        return $this->httpPost('/cgi-bin/externalcontact/get_unassigned_list', $data);
    }


    /**
     * 分配离职成员的客户
     * @param string $handoverUserid 原跟进成员的userid
     * @param string $takeoverUserid 接替成员的userid
     * @param array $externalUserid 客户的external_userid列表，每次最多分配100个客户
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function transferResignedCustomer($handoverUserid, $takeoverUserid, $externalUserid)
    {
        $data = [
            'handover_userid' => $handoverUserid,
            'takeover_userid' => $takeoverUserid,
            'external_userid' => $externalUserid,
        ];
        return $this->httpPost('/cgi-bin/externalcontact/resigned/transfer_customer', $data);
    }

    /**
     * 查询离职成员客户接替状态
     * @see  https://open.work.weixin.qq.com/api/doc/90000/90135/94088
     * @param string $handoverUserid 原跟进成员的userid
     * @param string $takeoverUserid 接替成员的userid
     * @param string $cursor 分页查询的cursor，每个分页返回的数据不会超过1000条；不填或为空表示获取第一个分页；
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function transferResignedResult($handoverUserid, $takeoverUserid, $cursor = '')
    {
        $data = [
            'handover_userid' => $handoverUserid,
            'takeover_userid' => $takeoverUserid,
            'cursor' => $cursor
        ];
        return $this->httpPost('/cgi-bin/externalcontact/resigned/transfer_result', $data);
    }

    /**
     * 分配离职成员的客户群
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92127
     * @param array $chat_id_list 需要转群主的客户群ID列表。取值范围： 1 ~ 100
     * @param string $newOwner 新群主ID
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function groupChatTransfer($chatIdList,$newOwner)
    {
        return $this->httpPost('/cgi-bin/externalcontact/resigned/transfer_result', ['chat_id_list'=>$chatIdList,'new_owner'=>$newOwner]);
    }
}