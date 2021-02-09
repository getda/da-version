<?php
namespace app\business;

use think\facade\Config as ConfigFac;

class Config extends Base
{
    /**
     * 获取网站配置信息
     * @return array
     */
    public function allConfig()
    {
        $all_config = ConfigFac::get('siteconfig');

        return !empty($all_config) ? [config('status.api.success'), "获取成功", $all_config] : [config('status.api.un_error'), "获取配置信息失败"];
    }

    /**
     * 更新网站配置文件
     * @param array $param
     * @return array
     */
    public function update($param = [])
    {
        if(!is_array($param)) {
            return [config('status.api.param_error'), '配置信息有误'];
        }
        $config_str = "<?php \n return ".var_export($param,true)."; \n";
        $result = file_put_contents(app()->getConfigPath() . 'siteconfig.php', $config_str);

        return $result !== false ? [config('status.api.success'), '保存成功'] : [config('status.api.un_error'), '保存失败, 可能文件路径权限不足'];
    }
}