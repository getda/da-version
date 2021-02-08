<?php

namespace app\controller;

use app\BaseController;
use app\business\Base as BaseBusiness;

class Base extends BaseController
{
    // 初始化
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 接口数据返回
     * @param $code
     * @param string $message
     * @param array $data
     * @param string $url
     * @param int $httpCode
     * @param array $header
     * @return \think\response\Json
     */
    public function apiReturn ($code, $message = 'ok', $data = [], $url = '', $httpCode = 200, $header = [])
    {

        if(is_array($code)) {
            // 数组下标名
            $sub_name = ['code', 'message', 'data', 'url', 'httpCode', 'header'];
            // 删除多余下标
            array_splice($sub_name, count($code));
            // 合并新数组 ['code' => xxx, ...]
            $merge_arr = array_combine($sub_name, $code);
            // 取出数组值赋值给键名且作为变量
            extract($merge_arr);
        } else {
            $code = (int) $code;
        }

        $result = [
            'code'      => $code,
            'message'   => $message,
            'data'      => $data
        ];

        $url && $result['url'] = (string) $url;

        return json($result, $httpCode, $header);
    }

    /**
     * 执行登录
     * @param int $id
     * @param bool $is_remember
     */
    public function runLogin (int $id = 0, $is_remember = false)
    {
        $baseBusiness = new BaseBusiness();
        $baseBusiness->runLogin($id, $is_remember);
    }

    /**
     * 判断是否登录
     * @return bool
     */
    public function isLogin ()
    {
        $baseBusiness = new BaseBusiness();
        return $baseBusiness->isLogin();
    }

}