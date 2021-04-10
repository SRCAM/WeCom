<?php


namespace saber\WorkWechat\WorkWx\Department;


use saber\WorkWechat\Core\HttpCent;

class Client extends HttpCent
{
    /**
     * 创建部门
     * @param string $name 部门名称。同一个层级的部门名称不能重复。长度限制为1~32个字符，字符不能包括\:?”<>｜
     * @param int $parentid 父部门id，32位整型
     * @param array $param 其他额外参数
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create($name, $parentid, $param = [])
    {
        $data = [
            'name' => $name,
            'parentid' => $parentid
        ];
        return $this->httpPost('/cgi-bin/department/create', array_merge($data, $param));
    }

    /**
     * 更新部门
     * @param int $id 部门id
     * @param array $param 额外参数
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update($id, $param = [])
    {
        return $this->httpPost('/cgi-bin/department/update', array_merge(compact('id'), $param));
    }

    /**
     * 删除部门
     * @param int $id 部门id。（注：不能删除根部门；不能删除含有子部门、成员的部门）
     * @return array|bool|float|int|object|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($id)
    {
        return $this->httpGet('/cgi-bin/department/delete', compact('id'));
    }

    /**
     * 获取部门列表
     * @param int $id 获取部门列表
     * @return array|mixed|object|\Psr\Http\Message\ResponseInterface|\saber\WorkWechat\Core\Collection|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function list($id=0)
    {
        return $this->httpGet('/cgi-bin/department/delete', compact('id'));
    }



}