<?php
declare (strict_types = 1);

namespace app\controller;

use app\controller\Base;
use think\facade\View;
use think\Request;
use app\business\Login as LoginBusiness;

class Login extends Base
{
    /**
     * 登录页面
     * @return string|\think\response\Redirect
     */
    public function index()
    {
        if ($this->isLogin()) {
            return redirect((string) url('center'));
        }
        return View::fetch('login/index');
    }

    /**
     * 登录 post
     * @param Request $request
     * @return \think\response\Json
     */
    public function login(Request $request)
    {
        $request->filter(['trim', 'strip_tags']);
        $param = $request->param(['username', 'password', 'captcha', 'remember']);
        $token = $request->header('X-CSRF-TOKEN');
        if ($token) {
            $param['__token__'] = $token;
        }

        $loginBusiness = new LoginBusiness();
        return $this->apiReturn($loginBusiness->login($param));
    }

    public function logout()
    {

    }
}
