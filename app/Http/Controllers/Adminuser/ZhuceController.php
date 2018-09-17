<?php

namespace App\Http\Controllers\Adminuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class ZhuceController extends Controller
{
    public function zhuceye()
    {
       $user_phone = 1111111;
       $password = 2222;
       $new_yzm = 222;
       $downline = 0;
       $integral = 0;
       $recommend_money = 0;
       $totalmount = 0;
       $user_grade = "普通用户";
       $user_status = "账户正常";
       $data = [];
       $data['user_phone'] = $user_phone;         
       $data['password'] = $password;         
       $data['new_yzm'] = $new_yzm;         
       $data['yqm'] = 321;         
       $data['downline'] = $downline;                  
       $data['integral'] = $integral;         
       $data['recommend_money'] = $recommend_money;         
       $data['totalmount'] = $totalmount;         
       $data['user_grade'] = $user_grade;
       $data['user_status'] = $user_status;
       $data['register_time'] = time();
       //获取父级信息
       $users = DB::table("user")->where("old_yqm","=",$data["yqm"])->first();
       $upid = $users->id;
       $data["pid"] = $upid;
       /*self::status($data["yqm"]);
       die;*/
       //进行以上每一级线下人数加1
       self::downline($data["yqm"]);
       die;
       if($uid = DB::table("user")->insertGetId($data)){
        //获取刚刚注册会员的信息
           $user = DB::table("user")->where("id","=",$uid)->first();
        //获取yzm 并修改  拼接级别路径
           $new_yzm = $user->new_yzm;
           $dat['old_yqm'] = $uid.$new_yzm;
           $dat["path"] = $users->path.",".$upid;
           DB::table("user")->where("id","=",$uid)->update($dat);
           //调用status方法，进行等级评定
           //self::status($data["yqm"]);
       }else{
           echo 2;
       }         
    }

    public static function downline($yqm)
    {
         //用邀请码来搜索老用户的
        if($up_level = DB::table("user")->where("old_yqm","=",$yqm)->first()){
              //查询本级的yqm = 上一级的old_yqm
             $up_old_yqm = $up_level->yqm;
             //查询每上一级的总积分
             $totalmount = $up_level->totalmount;
             //在原有的线下人数上加1 
             $down_line = $up_level->downline;
             $cat["downline"] = $down_line + 1;
             $dda = [];
             //首先要保证大于3人的上线每一个人都要加10%
             if($down_line > 3){
                  $tot =$totalmount + ($down_line - 3) * 0.1 * 1200;
                  $dda["totalmount"] = number_format($tot,2,".",""); 
                 //DB::table("user")->where("old_yqm","=",$yqm)->update($tat);
                  //var_dump($dda);
             }
                  $tat = [];
                  //如果城市管理中心存在 要加上额外的提成
                  if($down_line > 8 && $up_level->city_status == "已开通"){
                         $total = $totalmount + (0.1+0.01)*1200;
                         $tat["totalmount"] = number_format($total,2,".",""); 
                         //DB::table("user")->where("old_yqm","=",$yqm)->update($tat);
                          //var_dump($tat);
                         //如果合伙人服务中心存在 要加上额外的提成
                  }else if($down_line > 6 && $up_level->partner_status == "已开通"){
                         $total = $totalmount + 0.07 * 1200;
                         $tat["totalmount"] = number_format($total,2,".",""); 
                         //DB::table("user")->where("old_yqm","=",$yqm)->update($tat);
                         //var_dump($tat);
                  }
             //DB::table("user")->where("old_yqm","=",$yqm)->update($cat);
             self::downline($up_old_yqm);
             //var_dump($down_line);
        }   
    } 

    public static function getcatebypid($yqm)
    {
        //获取顶级分类
        $user=DB::table("user")->where("old_yqm","=",$yqm)->get();
        //var_dump($user);die;
        $data=[];
        //遍历等级分类进行下一级查找
        foreach($user as $key=>$val){
            //统计yqm下的人数共邀请了多少人
            //$user=DB::table("user")->where("yqm","=",$yqm)->get();
            $val->sub=self::getcatebypid($val->yqm);
            $data[]=$val;
        }
        return $data;   
    }
     public static function status($yqm)
    {
        //新会员注册时进行判断
         //调用邀请码进行赋值 $pwd
        $cate=self::getcatebypid($yqm);
        dd($cate);
        die;
        /*//将所得到的对象转化成为数组
        $arr = json_decode(json_encode($cate), true);
        $data = [];
        foreach($arr as $val){
          //根据邀请码条件进行以下等级查询A区B区分条数
          $cnt = 0;
      array_walk_recursive($val, function($v, $k) use (&$cnt) { if($k == 'yqm') $cnt++;});
            //将得到的AB区分总条数放进数组中
            $data[] = $cnt;
        }
        //var_dump($data);$die;
        $data[0] = 800;
        $data[1] = 700;
        $data[2] = 1300;
        //AB两区间取区间最小值
        if($data[0] >= $data[1]){
            $num = $data[1];
        }else{
            $num = $data[0];
        }
    //判断AB区各达到多少人，进行等级评定
        if(($num >= 3 && $num < 6)){
               echo "成为体验人";
        }else if(($num >= 6 && $num < 20)){
               echo "成为合VIP体验人";
        }else if(($num >= 20 && $num < 40)){
               echo "成为合伙人服务中心";
        }else if(($num >= 40 && $num < 75)){
               echo "成为合伙人一级";
        }else if(($num >= 75 && $num < 150)){
               echo "成为合伙人二级";
        }else if(($num >= 150 && $num < 250)){
               echo "成为合伙人三级";
        }else if(($num >= 250 && $num < 375)){
               echo "成为合伙人四级";
        }else if($num >= 375 && $num < 700){
               if($data[2] <= 300){
                  echo "成为合伙人五级并可以申请城市管理中心";
               }else if($data[2] >= 300 && $data[2] <= 600){
                  echo "成为城市管理中心";
               }
        }else if($num >= 700 && $num < 1400){
              if($data[2] <= 300){
                  echo "成为合伙人五级并可以申请城市管理中心";
               }else if($data[2] >= 300 && $data[2] <= 600){
                  echo "成为城市管理中心";
               }else if($data[2] >= 600){
                  echo "成为县级管理";
               }
        }else if($num >= 1400 && $num < 2100){
               if($data[2] <= 300){
                  echo "成为合伙人五级并可以申请城市管理中心";
               }else if($data[2] >= 300 && $data[2] <= 600){
                  echo "成为城市管理中心";
               }else if($data[2] >= 600 && $data[2] <= 1200){
                  echo "成为县级管理";
               }else if($data[2] >= 1200){
                  echo "成为市级管理";
               }
        }else if($num >= 2100 && $num < 2800){
               if($data[2] <= 300){
                  echo "成为合伙人五级并可以申请城市管理中心";
               }else if($data[2] >= 300 && $data[2] <= 600){
                  echo "成为城市管理中心";
               }else if($data[2] >= 600 && $data[2] <= 1200){
                  echo "成为县级管理";
               }else if($data[2] >= 1200 && $data[2] <= 1800){
                  echo "成为市级管理";
               }else if($data[2] >= 1800){
                  echo "成为省级管理";
               }
        }else if($num >= 2800){
              if($data[2] <= 300){
                  echo "成为合伙人五级并可以申请城市管理中心";
               }else if($data[2] >= 300 && $data[2] <= 600){
                  echo "成为城市管理中心";
               }else if($data[2] >= 600 && $data[2] <= 1200){
                  echo "成为县级管理";
               }else if($data[2] >= 1200 && $data[2] <= 1800){
                  echo "成为市级管理";
               }else if($data[2] >= 1800 && $data[2] <= 2400){
                  echo "成为省级管理";
               }else if($data[2] >= 2400){
                  echo "全国以上成为公司董事";
               }  */
        }
}
