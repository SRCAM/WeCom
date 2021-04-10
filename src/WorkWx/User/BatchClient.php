<?php


namespace saber\WorkWechat\WorkWx\User;


use saber\WorkWechat\Core\HttpCent;

class BatchClient extends HttpCent
{
    /**
     * 邀请成员
     * @param array $user
     * @param array $party
     * @param array $tag
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function invite(array $user = [], array $party = [], array $tag = [])
    {
        return $this->httpPost('/cgi-bin/batch/invite', ['user' => $user, 'party' => $party, 'tag' => $tag]);
    }


    /**
     * 增量更新成员
     * @param $media_id
     * @param array $param
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function syncUser($media_id, $param = [])
    {
        return $this->httpPost('/cgi-bin/batch/syncuser', array_merge(['media_id' => $media_id], $param));
    }

    /**
     * 全量覆盖成员
     * @param $media_id
     * @param array $param
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function replaceUser($media_id, $param = [])
    {
        return $this->httpPost('/cgi-bin/batch/replaceuser', array_merge(['media_id' => $media_id], $param));
    }

    /**
     * 全量覆盖部门
     * @param $media_id
     * @param array $param
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function replaceparty($media_id, $param = [])
    {
        return $this->httpPost('/cgi-bin/batch/replaceparty', array_merge(['media_id' => $media_id], $param));
    }


    /**
     * 获取异步任务结果
     * @param $media_id
     * @param array $param
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getresult($media_id, $param = [])
    {
        return $this->httpPost('/cgi-bin/batch/getresult', array_merge(['media_id' => $media_id], $param));
    }


}