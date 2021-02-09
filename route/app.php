<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

// 首页
Route::get('/', '\app\controller\Index@index')->name('home');

// 登录
Route::get('/login', '\app\controller\Login@index')->name('login');
Route::post('/login', '\app\controller\Login@login')->name('login');
// 退出登录
Route::delete('/logout', '\app\controller\Login@logout')->name('logout');

// 管理中心首页
Route::get('/center', '\app\controller\Index@center')->name('center');

// 用户管理 资源路由
Route::resource('/user', '\app\controller\User')->name('user');
// 获取用户列表
Route::post('user/list', '\app\controller\User@list')->name('user.list');
// 批量删除用户
Route::delete('user', '\app\controller\User@deletion')->name('user.deletion');

// 系统设置
Route::get('setting', '\app\controller\Config@index')->name('setting');
// 获取所有配置
Route::get('config', '\app\controller\Config@allConfig')->name('config');
// 更新配置
Route::put('config', '\app\controller\Config@update')->name('config');

// 文件/图片上传
Route::post('upload/<type>', 'app\controller\Base@upload')->name('upload');