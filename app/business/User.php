<?php
declare (strict_types = 1);
namespace app\business;

use app\model\User as UserModel;
use think\exception\ValidateException;
use app\validate\User as UserValidate;

class User extends Base
{
    /**
     * 获取用户列表
     * @param array $param
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list($param = [])
    {
        /*if(empty($where['__token__']) || request()->checkToken($where['__token__']) === false) {
            return [config('status.api.param_error'), '令牌验证失败'];
        }
        // 删除多余字段
        unset($where['__token__']);*/

        $userModel = new UserModel();
        $result = $userModel::withSearch(['username', 'status', 'is_admin'], $param)
            ->withoutField('password,salt,remember_token,remember_timeout')
            ->order('id desc')
            ->paginate([
                'page'      => $param['page_current'],
                'list_rows' => $param['page_size']
            ]);

        return [config('status.api.success'), '', $result];
    }

    /**
     * 新增用户
     * @param array $param
     * @return array
     */
    public function save($param = [])
    {
        // 验证数据
        try {
            $validate = validate(UserValidate::class);
            $validate->scene('create')->batch(true)->check($param);
        } catch (ValidateException $validateException) {
            return [config('status.api.opera_error'), $validateException->getError()];
        }

        // 只有ID为1的用户才能设置管理员
        if(session('user_id') !== 1) {
            $param['is_admin'] = 0;
        }

        // 获取盐值
        $salt = $this->beSalt();
        // 添加盐值
        $param['salt'] = $salt;
        // 加密密码
        $param['password'] = $this->bePassword($param['password'], $salt);

        // 新增数据
        $userModel = new UserModel();
        $result = $userModel::create($param);

        return isset($result->id) ? [config('status.api.success'), '创建成功'] : [config('status.api.un_error'), '创建失败'];
    }

    /**
     * 更新用户
     * @param array $param
     * @return array
     */
    public function update($param = [])
    {
        // 验证数据
        try {
            $validate = validate(UserValidate::class);
            $validate->scene('edit')->batch(true)->check($param);
        } catch (ValidateException $validateException) {
            return [config('status.api.opera_error'), $validateException->getError()];
        }

        /**
         * 这里需要验证是否为管理员, 否则只能修改自己的信息
         */
        if(false && $param['id'] != session('user_id')) {
            // return [config('status.api.auth_error'), '权限不足'];
        }

        // 只有ID为1的用户才能设置管理员
        if(session('user_id') !== 1) {
            $param['is_admin'] = 0;
        }

        // 判断是否传入主键
        if(!isset($param['id'])) {
            return [config('status.api.param_error'), '参数错误'];
        }

        $userModel = new UserModel();
        $user = $userModel::field('id,salt')->findOrEmpty((int) $param['id']);
        if ($user->isEmpty()) {
            return [config('status.api.data_error'), '用户信息不存在'];
        }

        if(isset($param['password'])) {
            // 加密密码
            $salt = $user->salt;
            $param['password'] = $this->bePassword($param['password'], $salt);
        } else {
            unset($param['password']);
        }

        // 修改数据
        $result = $userModel::update($param);

        return isset($result->id) ? [config('status.api.success'), '修改成功'] : [config('status.api.un_error'), '修改失败'];
    }

    /**
     * 删除用户
     * @param $id
     */
    public function delete($ids)
    {
        $userModel = new UserModel();
        $result = $userModel::destroy($ids);

        return $result ? [config('status.api.success'), '删除成功'] : [config('status.api.opera_error'), '删除失败'];
    }
}