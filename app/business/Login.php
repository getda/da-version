<?php
declare (strict_types = 1);
namespace app\business;

use app\validate\Login as LoginValidate;
use think\exception\ValidateException;
use app\model\User as UserModel;
use think\facade\Session;

class Login extends Base
{
    public function login(array $param = [])
    {
        if(session('?user_id')) {
            return [config('status.api.opera_error'), '无需重复登录'];
        }

        if(empty($param)) {
            return [config('status.api.param_error'), '参数不能为空'];
        }

        try {
            $validate = validate(LoginValidate::class);
            $validate->check($param);
        } catch (ValidateException $validateException) {
            return [config('status.api.opera_error'), $validateException->getError()];
        }

        // 根据用户名查询用户数据
        $userModel = new UserModel();
        $info = $userModel->getInfoByUsername((string) $param['username'], 'id, username, password, salt');

        if($info->isEmpty()) {
            return [config('status.api.data_error'), '用户名不存在'];
        }

        $salt = $info['salt'];
        // 按约定格式生成密码
        $password = $this->bePassword($param['password'], $salt);

        // 匹配密码是否一致
        if(!$this->comparePassword($info['password'], $password)) {
            return [config('status.api.data_error'), '用户名密码不匹配'];
        }

        // 执行登录
        $id = $info['id'];
        $is_remember = isset($param['remember']) ? $param['remember'] : false;
        $this->runLogin($id, $is_remember);

        // 更新登录记录
        $userModel->updateInfoById($id, [
            'last_login_ip'     => request()->ip(),
            'last_login_time'   => time()
        ]);

        return [config('status.api.success'), '登录成功', [], url('center')];

    }

    /**
     * 退出登录 delete
     */
    public function logout()
    {
        if(session('?user_id')) {
            $userModel = new UserModel();
            $id = session('user_id');
            // remember_token 过期时间设置为 0
            $userModel->updateInfoById($id, [
                'remember_timeout'     => 0,
            ]);
        }
        session('user_id', null);
        cookie('remember', null);
        Session::flash('success','退出登录成功');
    }
}