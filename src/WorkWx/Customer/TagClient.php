<?php


namespace saber\WorkWechat\WorkWx\Customer;


use saber\WorkWechat\WorkWx\User\BatchClient;

class TagClient extends BatchClient
{
    /**
     * 获取企业标签库
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92116#%E8%8E%B7%E5%8F%96%E4%BC%81%E4%B8%9A%E6%A0%87%E7%AD%BE%E5%BA%93
     * @param array $tagId 要查询的标签id
     * @param array $groupId 要查询的标签组id，返回该标签组以及其下的所有标签信息
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCorpTagList($tagId, $groupId)
    {
        $this->httpPost('/cgi-bin/externalcontact/get_corp_tag_list', ['tag_id' => $tagId, 'group_id' => $groupId]);
    }

    /**
     * 添加企业客户标签
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92116#%E6%B7%BB%E5%8A%A0%E4%BC%81%E4%B8%9A%E5%AE%A2%E6%88%B7%E6%A0%87%E7%AD%BE
     * @param array $tag 添加的标签名称
     * @param array $param 额外参数
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addCorpTag($tag, $param = [])
    {
        $data = [
            'tag' => $tag,
        ];
        return $this->httpPost('/cgi-bin/externalcontact/add_corp_tag', array_merge($data, $param));
    }

    /**
     * 编辑企业客户标签
     * @see  https://open.work.weixin.qq.com/api/doc/90000/90135/92116#%E7%BC%96%E8%BE%91%E4%BC%81%E4%B8%9A%E5%AE%A2%E6%88%B7%E6%A0%87%E7%AD%BE
     * @param string $tagId 标签或标签组的id
     * @param array $param 其他参数
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function editCorpTag($tagId, $param = [])
    {
        return $this->httpPost('/cgi-bin/externalcontact/edit_corp_tag', array_merge(['id' => $tagId], $param));
    }

    /**
     * 删除企业客户标签
     * @see https://open.work.weixin.qq.com/api/doc/90000/90135/92116#%E5%88%A0%E9%99%A4%E4%BC%81%E4%B8%9A%E5%AE%A2%E6%88%B7%E6%A0%87%E7%AD%BE
     * @param string $tagId 标签或标签组的id
     * @param array $param  其他参数
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delCorpTag($tagId, $param = [])
    {
        return $this->httpPost('/cgi-bin/externalcontact/del_corp_tag', array_merge(['id' => $tagId], $param));
    }


    /**
     * 编辑客户企业标签
     * @see  https://open.work.weixin.qq.com/api/doc/90000/90135/92118
     * @param string $userid 添加外部联系人的userid
     * @param string $externalUserid 	外部联系人userid
     * @param array $param  其他参数
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function markTag($userid,$externalUserid,$param=[])
    {
        return $this->httpPost('/cgi-bin/externalcontact/mark_tag', array_merge(['userid' => $userid,'external_userid'=>$externalUserid], $param));
    }
}