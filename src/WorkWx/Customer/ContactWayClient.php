<?php


namespace saber\WorkWechat\WorkWx\Customer;


use saber\WorkWechat\Core\HttpCent;

/**
 * Class ContactWayClient
 * @package saber\WorkWechat\WorkWx\CustomerClient
 */
class ContactWayClient extends HttpCent
{
    /**
     * @see  https://open.work.weixin.qq.com/api/doc/90000/90135/92570
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFollowUserList()
    {
        return $this->httpGet('/cgi-bin/externalcontact/get_follow_user_list');
    }

    /**
     * 配置客户联系「联系我」方式
     *
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92572#%E9%85%8D%E7%BD%AE%E5%AE%A2%E6%88%B7%E8%81%94%E7%B3%BB%E3%80%8C%E8%81%94%E7%B3%BB%E6%88%91%E3%80%8D%E6%96%B9%E5%BC%8F
     * @param $type
     * @param $scene
     * @param array $param
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addContactWay($type, $scene, $param = [])
    {
        $data = [
            'type' => $type,
            'scene' => $scene,
        ];
        return $this->httpPostJson('/cgi-bin/externalcontact/add_contact_way', array_merge($data, $param));
    }


    /**
     * 获取企业已配置的「联系我」方式
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92572#%E8%8E%B7%E5%8F%96%E4%BC%81%E4%B8%9A%E5%B7%B2%E9%85%8D%E7%BD%AE%E7%9A%84%E3%80%8C%E8%81%94%E7%B3%BB%E6%88%91%E3%80%8D%E6%96%B9%E5%BC%8F
     * @param $configId
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getContactWay($configId)
    {
        return $this->httpPostJson('/cgi-bin/externalcontact/get_contact_way', ['config_id' => $configId]);
    }


    /**
     * 更新企业已配置的「联系我」方式
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92572#%E6%9B%B4%E6%96%B0%E4%BC%81%E4%B8%9A%E5%B7%B2%E9%85%8D%E7%BD%AE%E7%9A%84%E3%80%8C%E8%81%94%E7%B3%BB%E6%88%91%E3%80%8D%E6%96%B9%E5%BC%8F
     * @param $configId
     * @param array $param
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateContactWay($configId, $param = [])
    {
        $data = ['config_id' => $configId];
        return $this->httpPostJson('/cgi-bin/externalcontact/get_contact_way', array_merge($data, $param));
    }

    /**
     * 删除企业已配置的「联系我」方式
     * @see  https://open.work.weixin.qq.com/api/doc/90000/90135/92572#%E5%88%A0%E9%99%A4%E4%BC%81%E4%B8%9A%E5%B7%B2%E9%85%8D%E7%BD%AE%E7%9A%84%E3%80%8C%E8%81%94%E7%B3%BB%E6%88%91%E3%80%8D%E6%96%B9%E5%BC%8F
     * @param $configId
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delContactWay($configId)
    {
        return $this->httpPostJson('/cgi-bin/externalcontact/del_contact_way', ['config_id' => $configId]);
    }
}