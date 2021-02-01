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