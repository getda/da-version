<?php
declare (strict_types = 1);

namespace app\controller;

use think\facade\View;
use think\Request;
use app\business\Config as ConfigBusiness;

class Config extends Base
{
    /**
     * 显示配置
     * @return string
     */
    public function index()
    {
        return View::fetch('config/index',[
            'title' => '系统设置'
        ]);
    }

    /**
     * 获取所有配置信息
     * @return \think\response\Json
     */
    public function allConfig() {
        $configBusiness = new ConfigBusiness();
        return $this->apiReturn($configBusiness->allConfig());
    }

    /**
     * 更新配置
     * @param Request $request
     * @return \think\response\Json
     */
    public function update(Request $request)
    {
        $request->filter(['trim', 'strip_tags']);
        $param = $request->param('config/a');

        $configBusiness = new ConfigBusiness();
        return $this->apiReturn($configBusiness->update($param));
    }

}
