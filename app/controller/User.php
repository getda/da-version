<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;
use think\facade\View;
use app\business\User as UserBusiness;

class User extends Base
{
    /**
     * 显示资源列表
     *
     */
    public function index()
    {
        return View::fetch('user/index',[
            'title' => '用户管理'
        ]);
    }

    public function list(Request $request)
    {
        $request->filter(['trim', 'strip_tags']);
        $param = $request->param(['username', 'status', 'is_admin', 'page_size', 'page_current']);
        $token = $request->header('X-CSRF-TOKEN');
        if ($token) {
            $param['__token__'] = $token;
        }

        // 默认每页显示数量
        $param['page_size'] = $param['page_size'] ?? 10;
        // 默认当前页
        $param['page_current'] = $param['page_current'] ?? 1;

        $userBusiness = new UserBusiness();
        return $this->apiReturn($userBusiness->list($param));
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $request->filter(['trim', 'strip_tags']);
        $param = $request->param(['username', 'password', 'email', 'contact', 'is_admin', 'status']);

        $userBusiness = new UserBusiness();
        return $this->apiReturn($userBusiness->save($param));
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $request->filter(['trim', 'strip_tags']);
        $param = $request->param(['id', 'username', 'password', 'email', 'contact', 'is_admin', 'status']);

        $userBusiness = new UserBusiness();
        return $this->apiReturn($userBusiness->update($param));
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $userBusiness = new UserBusiness();
        return $this->apiReturn($userBusiness->delete($id));
    }

    /**
     * 批量删除资源
     * @param Request $request
     * @return \think\response\Json
     */
    public function deletion(Request $request)
    {
        $request->filter(['trim', 'strip_tags']);
        $param = $request->param(['ids']);

        $userBusiness = new UserBusiness();
        return $this->apiReturn($userBusiness->delete($param['ids']));
    }
}
