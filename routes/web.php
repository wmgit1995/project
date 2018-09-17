<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::resource("/user","Adminuser\IndexController");
//后台用户管理
Route::resource("/adminuser","Adminuser\AdminuserController");
Route::get("/line/{id}","Adminuser\AdminuserController@lineover");
Route::get("/dongjie","Adminuser\AdminuserController@dong");
Route::get("/look_rule","Adminuser\AdminuserController@lookrule");
Route::get("/level_status","Adminuser\AdminuserController@levelstatus");
Route::get("/search","Adminuser\AdminuserController@result");
//合伙人等级管理
Route::resource("/level","Adminuser\LevelController");
//城市中心等级管理
Route::resource("/city_level","Adminuser\City_levelController");
//审核中心
Route::resource("/examine","Adminuser\ExamineController");
Route::get("/partner","Adminuser\ExamineController@partner_examine");
Route::get("/city","Adminuser\ExamineController@city_examine");
//Route::get("/rank_view/{id}","Adminuser\LevelController@partition");
Route::get("/rank_view","Adminuser\LevelController@partition");
Route::get("/chakan","Adminuser\LevelController@fenqu");
//用户中心基本信息
Route::get("/usermessage","User\UsermessageController@message");
//获取用户积分
Route::get("/userintegral","User\UsermessageController@integral");
//获取积分总量
Route::get("/usertotalmount","User\UsermessageController@totalmount");
//获取我的邀请码
Route::get("/useryqm","User\UsermessageController@old_yqm");
//获取用户密码判断用户密码是否重复
Route::get("/userpass","User\UsermessageController@user_password");
//添加银行卡
Route::get("/userbank","User\UsermessageController@bank_mess");
//添加收货地址
Route::get("/useraddress","User\UsermessageController@address");
//组织关系图
Route::get("/usergrade","User\UsermessageController@grade");
//判断是否可以达到开通C支线标准
Route::get("/userlevel","User\UsermessageController@ulevel");


Route::get("/ywd","Adminuser\IndexController@ywdaction");
Route::get("/grade","Adminuser\IndexController@membergrade");
Route::get("/jifen","Adminuser\IndexController@jiangli");
Route::get("/admin","Adminuser\UserController@admins");
Route::get("/zhuce","Adminuser\ZhuceController@zhuceye");
Route::get("/zongjf","Adminuser\UserController@suanjifen");
Route::get("/dengji","Adminuser\HuiyuanController@deng");
//Route::resource("/adminuser","Adminuser\UserController");
