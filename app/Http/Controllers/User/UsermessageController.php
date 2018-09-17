<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;
class UsermessageController extends Controller
{
       //1.点击我的，获取用户基本信息，积分，收益
       public function message()
       {
            $user = DB::table("user")->where("id","=",$id)->first();
            return json_encode($user);
       }
       //2.点击积分，获取积分
       public function integral()
       {
            $user = DB::table("user")->where("id","=",$id)->first();
            $integral = $user->integral;
            return json_encode($integral);
       }
       //3.点击提现，获取积分总量
       public function totalmount()
       {
            $user = DB::table("user")->where("id","=",$id)->first();
            $totalmount = $user->totalmount;
            return json_encode($totalmount);
       }
       
       //7.点击我的邀请码获取邀请码
       public function old_yqm()
       {
            $user = DB::table("user")->where("id","=",$id)->first();
            $old_yqm = $user->old_yqm;
            return json_encode($old_yqm);
       }
       //8.密码修改点击确认修改，需要确认原密码是否正确，正确的话修改密码
       public function user_password(Request $request)
       {
            //将获得的密码加密
            $pass = Hash::make($request->input('password'));
            $user = DB::table("user")->where("id","=",$id)->first();
            if($pass == $user->user_password){
                 return 1;//原密码正确
            }
            
       }
       //9.银行卡：添加银行卡
       public function bank_mess(Request $request)
       {
            $card_number = $request->input("card_number");
            $user_name = $request->input("user_name");
            $phone = $request->input("phone");
            //$uid
            if(DB::table("bank_mess")->insert($data)){
                  return 1;//添加银行卡成功
            }
       }

       //11记录用户收货地址
       public function address(Request $request)
       {
            $address = $request->input("address");
            $recipient_phone = $request->input("recipient_phone");
            $sender_phone = $request->input("sender_phone");
            //$uid
            if(DB::table("user_address")->insert($data)){
                  return 1;//添加收件人地址成功
            }
       }
      //12.点击团队关系图，获取用户下级信息
       public function grade()
       {
           //获取一级会员
            $fuser = DB::table('user')->where("id","=",1)->first();
           // var_dump($fuser);die; 
          //获取二级会员
            $user = DB::table('user')->where("yqm","=",$fuser->old_yqm)->get();
              $data = [];
              foreach($user as $val){
                 //获取三级会员
                 $val->sub = DB::table('user')->where("yqm","=",$val->old_yqm)->get();
                 $data[]=$val; 
              }
              var_dump($data);
          // return view("/team/map",['fuser'=>$fuser,'data'=>$data,'user'=>$user]);
           
        }
       public static function getlevel($yqm)
        {
            //获取顶级分类
            $user=DB::table("user")->where("yqm","=",$yqm)->get();
            //var_dump($user);die;
            $data=[];
            //遍历等级分类进行下一级查找
            foreach($user as $key=>$val){
                //var_dump($val);die;
                $val->sub=self::getcatebypid($val->old_yqm);
                $data[]=$val;
            }
            return $data;   
        }
       //是否可以开通C支线
        public function ulevel(Request $request)
        {
             $id = $request->only("level_id");
             $yonghu = DB::table("user")->where("id","=",$id)->first();
             $yqm = $yonghu->old_yqm;
                 //调用邀请码进行赋值 $pwd
                $cate=self::getlevel($yqm);
                //dd($cate);
                //将所得到的对象转化成为数组
                $arr = json_decode(json_encode($cate), true);
                $data = [];
                foreach($arr as $val){
                    //根据邀请码条件进行以下等级查询A区B区分条数
                    $cnt = 0;
                    array_walk_recursive($val, function($v, $k) use (&$cnt) { if($k == 'yqm') $cnt++;});
                    //将得到的AB区分总条数放进数组中
                    $data[] = $cnt;
                }
               //计算总支线数量
               $cnt = count($data);
                if($cnt == 3){
                      /*$data[0] = 500;
                      $data[1] = 500;
                      $data[2] = 300;
                        */              
                        if($data[0] >= $data[1]){
                                  $num = $data[1];
                              }else{
                                  $num = $data[0];
                              }
                      //获取三个审核对应的节点人数
                      $C_numb = DB::table("C_number")->first();
                      $city_cent = $C_numb->city_center;
                      //判断三支线AB区最短线是否都超过375节点
                      if($num < $city_cent){
                            return 1;//不可以申请城市管理中心
                      }else if($data[2] > 300 && $yonghu->city_status == "未开通"){
                            return 2;//可以申请城市管理中心
                      }
                }else if($cnt == 2){
                                   //当$cnt =2为两条支线
                                   //AB两区间取区间最小值
                                   if($data[0] >= $data[1]){
                                          $num = $data[1];
                                      }else{
                                          $num = $data[0];
                                      }
                   if($num > 20 && $yonghu->partner == "未开通"){
                                 return 3;//可开通C支线
                    }else{
                                 return 4;//不可开通C支线
                   }
                }
        }
}
