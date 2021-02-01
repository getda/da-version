<?php
namespace app\controller;

use think\facade\View;

class Index extends Base
{
    public function index()
    {
        return redirect(url('center'));
    }

    public function center()
    {
        return View::fetch('static_page/center');
    }
}
