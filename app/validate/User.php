<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class User extends Validate
{

    protected $regex = [
        'username'  => '^[a-zA-Z][a-zA-Z0-9-_]{2,19}$',
        'password'  => '^[a-zA-Z0-9.*+_~!@#$%^&]{6,18}$',
        'email'     => '^((?=[a-zA-Z0-9])[a-zA-Z0-9-]{1,15}@)([a-zA-Z0-9-]{1,63})((\.[a-zA-Z]{2,4}){1,2}(?!\.))$',
        'qq'        => '^[1-9]([0-9]{4,10})$',
        'wx'        => '^[a-zA-Z-_][a-zA-Z0-9-_]{5,19}$',
        'phone'     => '^1[3-9]\d{9}$'
    ];
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username|用户名' => 'require|min:3|max:20|regex:username|unique:user|checkUnique',
        'password|密码' => 'require|min:6|max:18|regex:password',
        'email|邮箱' => 'require|max:255|regex:email|unique:user|checkUnique',
        'contact.qq|QQ' => 'regex:qq',
        'contact.wx|微信' => 'regex:wx',
        'contact.phone|手机' => 'regex:phone',
        'is_admin|权限'   => 'in:0,1',
        'status|状态'   => 'in:0,1',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'username.regex'        => '用户名仅支持以字母开头且不含特殊字符',
        'username.checkUnique'  => '用户名已存在',
        'password.regex'        => '密码包含不支持的特殊符号',
        'email.regex'           => '邮箱格式不支持或过长',
        'email.checkUnique'     => '邮箱已存在',
        'contact.qq.regex'   => 'QQ号格式不正确',
        'contact.wx.regex'      => '微信号格式不正确',
        'contact.phone.regex'   => '手机号格式不正确',
    ];

    // 更新验证场景
    public function sceneEdit()
    {
        return $this->only(['username','password', 'email', 'contact.qq', 'contact.wx', 'contact.phone', 'is_admin', 'status'])
            ->remove('username', 'unique')
            ->remove('password', 'require')
            ->remove('email', 'unique');
    }

    // 新增验证场景
    public function sceneCreate()
    {
        return $this->only(['username','password', 'email', "contact.qq", 'contact.wx', 'contact.phone', 'is_admin', 'status'])
            ->remove('username', 'checkUnique')
            ->remove('email', 'checkUnique');
    }

    // 修改时验证信息是否存在
    protected function checkUnique($value, $rule, $data = [], $field)
    {
        if (!isset($data['id']) ) return false;
        $result = $this->db->name('user')
            ->where([
                ['id', '<>', $data['id']],
                [$field, '=', $value]
            ])->value('id');

        return !$result;
    }
}
