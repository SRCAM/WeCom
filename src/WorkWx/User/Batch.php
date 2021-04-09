<?php


namespace saber\WorkWechat\WorkWx\User;


use saber\WorkWechat\Core\HttpCent;

class Batch extends HttpCent
{
    /**
     * 邀请成员
     * @param array $user
     * @param array $party
     * @param array $tag
     * @return array|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function invite(array $user=[],array $party=[],array $tag=[])
    {
        return $this->httpPost('/cgi-bin/batch/invite',['user'=>$user,'party'=>$party,'tag'=>$tag]);
    }


}