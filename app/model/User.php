<?php

namespace app\model;

use think\Model;

class User extends Model
{
    // JSON 字段
    protected $json = ['contact'];
    // 增加字段
    protected $append = ['is_admin_text', 'status_text'];

    /**
     * 根据用户名查询用户信息
     * @param $username
     * @param string $field
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfoByUsername (string $username, string $field = '')
    {
        return $this->where('username', '=', $username)
                    ->field($field)
                    ->findOrEmpty();
    }

    /**
     * 根据 ID 查询用户信息
     * @param int $id
     * @param string $field
     * @return array|Model
     */
    public function getInfoById (int $id, string $field = '')
    {
        return $this->where('id', $id)
            ->field($field)
            ->findOrEmpty();
    }

    /**
     * 根据 remember_token 查询用户信息
     * @param $token
     * @param string $field
     * @return array|Model
     */
    public function getInfoByRememberToken($token, string $field = '') {
        return $this->field($field)
            ->where('remember_token', $token)
            ->scope('remember_timeout')
            ->findOrEmpty();
    }

    /**
     * 判断 remember_token 是否存在
     * @param $token
     * @return mixed|string
     */
    public function hasRememberToken($token)
    {
        return $this->field('remember_token')
                    ->where('remember_token', $token)
                    ->whereTime('remember_timeout', '>', time())
                    ->findOrEmpty()
                    ->isEmpty();
    }

    /**
     * 根据 ID 更新 remember_token 字段
     * @param int $id
     * @param string $token
     * @param string $timeout
     * @return User
     */
    public function updateRememberTokenByid (int $id, string $token, string $timeout = '')
    {
        return $this->where('id', $id)
                    ->update(['remember_token'=> $token, 'remember_timeout' => $timeout]);
    }

    public function updateInfoById (int $id, $data)
    {
        return $this->where('id', $id)
                    ->update($data);
    }

    /**
     * username 搜索器
     * @param $query
     * @param $value
     */
    public function searchUsernameAttr($query, $value)
    {
        $value ? $query->whereLike('username', '%'. $value .'%') : '';
    }

    /**
     * status 搜索器
     * @param $query
     * @param $value
     */
    public function searchStatusAttr($query, $value = null)
    {
        $value !== null ? $query->whereIn('status', $value) : '';
    }

    /**
     * is_admin 搜索器
     * @param $query
     * @param $value
     */
    public function searchIsAdminAttr($query, $value = null)
    {
        $value !== null ? $query->whereIn('is_admin', $value) : '';
    }

    /**
     * last_login_time 获取器
     * @param $value
     * @return false|string
     */
    public function getLastLoginTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * status_text 虚拟字段获取器
     * @param $value
     * @param $data
     * @return string
     */
    public function getStatusTextAttr($value, $data)
    {
        $array = [0 => '禁用', 1 => '正常'];
        return $array[$data['status']];
    }

    /**
     * is_admin_text 虚拟字段获取器
     * @param $value
     * @param $data
     * @return string
     */
    public function getIsAdminTextAttr($value, $data)
    {
        $array = [0 => '普通用户', 1 => '管理员'];
        return $array[$data['is_admin']];
    }
}