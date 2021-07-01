<?php


namespace saber\WorkWechat\WorkWx\Customer;


use saber\WorkWechat\Core\HttpCent;

/**
 * 用户行为统计
 * Class BehaviorClient
 * @package saber\WorkWechat\WorkWx\Customer
 */
class BehaviorClient extends HttpCent
{

    /**
     * @param int $start_time
     * @param int $end_time
     * @param array $userid
     * @param array $partyid
     */
    public function getUserBehaviorData(int $start_time,int $end_time,array $userid =[],array $partyid=[])
    {
        $data =[
            'userid'  => $userid,
            'partyid' => $partyid,
            'start_time'=>$start_time,
            'end_time'  =>$end_time
        ];

        $this->httpPostJson('/cgi-bin/externalcontact/get_user_behavior_data',$data);
    }


}