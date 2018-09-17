<?php

namespace App\Http\Controllers\Adminuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class HuiyuanController extends Controller
{
     public function deng($id = 1)
     {
        //获取第一层用户信息
        $user = DB::table("user")->where("id","=",$id)->first();
        $old_yqm = $user->new_yzm;
         //获取第二层用户信息
         $cate = Db::table('user')->where("old_yqm","=",$old_yqm)->get();
         $data = [];
         foreach($cate as $val){
            //获取第三层用户信息
            $cat = Db::table('user')->where("old_yqm","=",$val->new_yzm)->first();
            $data[] = $cat;
         }
           var_dump($data);           
     }
    
     //线下人员情况区域
    public  function xianxia($id)
    {
        -$user = DB::table("user")->where("id","=",$id)->first();
        var_dump($user);die;
       /* if($user->status == 0){
                 $user->status = "普通用户";
             }if($user->status == 1){
                 $user->status = "体验人";
             }if($user->status == 2){
                 $user->status = "VIP体验人";
             }if($user->status == 3){
                 $user->status = "合伙人服务中心";
             }if($user->status == 4){
                 $user->status = "合伙人一级";
             }if($user->status == 5){
                 $user->status = "合伙人二级";
             }if($user->status == 6){
                 $user->status = "合伙人三级";
             }if($user->status == 7){
                 $user->status = "合伙人四级";
             }if($user->status == 8){
                 $user->status = "合伙人五级并可以申请城市管理中心";
             }if($user->status == 9){
                 $user->status = "城市管理中心";
             }if($user->status == 10){
                 $user->status = "成为县级管理";
             }if($user->status == 11){
                 $user->status = "成为市级管理";
             }if($user->status == 12){
                 $user->status = "成为省级管理";
             }if($user->status == 13){
                 $user->status = "全国以上公司董事";
             }
             $cate=self::getcatebypid($user->new_yzm);
           var_dump(session("data"));*/
       // return view("Admin.User.ind",['user'=>$user]);
    }

}
