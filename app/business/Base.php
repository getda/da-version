<?php
namespace app\business;

use app\model\User;
use think\facade\Cookie;
use think\facade\Session;

class Base
{
    /**
     * 生成密码
     * @param $password
     * @param $salt
     * @return string
     */
    public function bePassword ($password, $salt)
    {
        return $salt . $password . $salt;
    }

    public function beSalt($num = 8)
    {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $init_salt = "";
        for ($i = 0; $i < $num; $i++) {
            $init_salt .= substr($str, rand(0, strlen($str) - 1), 1);
        }

        return $init_salt;
    }

    /**
     * 密码匹配
     * @param string $current_password 当前密码 (已加密)
     * @param string $password 密码传参前自行组合好盐值
     * @param string $type 加密方法
     * @return bool
     */
    protected function comparePassword ($current_password, $password, $type = 'md5')
    {
        if(empty($current_password) || empty($password)) {
            return false;
        }

        if(!function_exists($type)) {
            return false;
        }

        return $current_password === $type($password);
    }

    /**
     * 执行登录
     * @param int $id
     * @param bool $is_remember
     * @return bool
     */
    public function runLogin(int $id = 0, $is_remember = false)
    {
        $userModel = new User();
        // 判断是否传入 ID
        if ($id !== 0) {
            $user_info = $userModel->getInfoById($id, 'id');
            if ($is_remember) {
                // 生成过期时间 7天
                $cookie_timeout = 60*60*24*7;
                $timeout = time() + $cookie_timeout;
                // 生成持久登录 token
                $remember_token = md5($id . $user_info['username'] . $timeout);
                // 持久登录信息 更新到数据库
                $userModel->updateRememberTokenByid((int) $id, (string) $remember_token, $timeout);
                // cookie 存储 token 用于记录 7天 登录状态
                Cookie::set('remember', $remember_token, $cookie_timeout);
            }
            // session 存储用户信息
            Session::set('user_id', $user_info->id);
            return true;
        } else {
            if(Cookie::has('remember')) {
                $remember_token = Cookie::get('remember');
                $is_remember_token = $userModel->hasRememberToken($remember_token);
                // 如果持久登录 token 存在则直接登录
                if($is_remember_token) {
                    $user_info = $userModel->getInfoByRememberToken($remember_token, 'id');
                    // session 存储用户信息
                    Session::set('user_id', $user_info->id);
                    return true;
                }
            }
            Cookie::delete('remember');
            return false;
        }
    }

    /**
     * 判断是否登录
     * @return bool
     */
    public function isLogin ()
    {
        // 如果 session 存在则登录
        if(Session::has('user_id')) {
            return true;
        }
        return $this->runLogin();
    }

}