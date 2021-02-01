<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class Login extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username'  => 'require',
        'password'  => 'require',
        // 'captcha'   => 'require|captcha|token',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'username.require'      => '用户名不能为空',
        'password.require'      => '密码不能为空',
        'captcha.require'       => '验证码不能为空',
        'captcha.captcha'       => '验证码错误',
        'captcha.token'         => '令牌验证失败, 请刷新'
    ];
}
