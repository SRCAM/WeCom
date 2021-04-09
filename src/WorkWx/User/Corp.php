<?php


namespace saber\WorkWechat\WorkWx\User;


use saber\WorkWechat\Core\HttpCent;

class Corp extends HttpCent
{
    /**
     *  获取加入企业二维码
     * @param string $size_type
     * @return array|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getJoinQrcode($size_type='')
    {
        return $this->httpGet('/cgi-bin/corp/get_join_qrcode',['size_type'=>$size_type]);
    }

}