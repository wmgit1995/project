<?php

namespace App\Http\Controllers\Adminuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use DB;
use App\Http\Requests\UsersInsertRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    
    public function admins()
    {  
          return view("Admin.AdminPublic.public");
    }
    public static function getcatebypid($ywd)
    {
        //获取顶级分类
        $user=DB::table("user")->where("fywd","=",$ywd)->get();
            $data=[];
            //遍历等级分类进行下一级查找
            foreach($user as $key=>$val){
                $val->sub=self::getcatebypid($val->ywd);
                $data[]=$val;
            }
            return $data;      
    }
    public function index(Request $request)
    {  
         $user = DB::table("user")->get();
         $users = $user->toArray($user); 
         $data = [];
         foreach($users as $val){
            $jifen = $val->jifen;
            $fywd = $val->fywd;
            $uid = $val->id;
            //调用邀请码进行赋值 $pwd
            $cate=self::getcatebypid($fywd);
            //dd($cate);
            //将所得到的对象转化成为数组
            $arr = json_decode(json_encode($cate), true);
            //var_dump(count($arr,1));
            //根据邀请码条件进行以下等级查询总条数
           // printf("\$arr 共有一级元素 %d 个\n", count($arr));
            //printf("  其中无下级元素的 %d 个\n", count(array_filter($arr, function($t) { return ! isset($t['sub']);})));
            $cnt = 0;
            array_walk_recursive($arr, function($v, $k) use (&$cnt) { if($k == 'ywd') $cnt++;});
            //printf("共有成员数组 %d 个\n", $cnt);
            $data['num'] = $cnt -1;
            //var_dump($data);
            //修改每个会员的线下人数
             DB::table("user")->where("id","=",$uid)->update($data);
             //调用每个会员的等级
             self::membergrade($fywd,$uid);
             //调用大于3人后的每增加一人的推荐奖
             self::tuijian($data['num'],$uid);
             if($val->status == 0){
                 $val->status = "普通用户";
             }else if($val->status == 1){
                 $val->status = "体验人";
             }else if($val->status == 2){
                 $val->status = "VIP体验人";
             }else if($val->status == 3){
                 $val->status = "合伙人服务中心";
             }else if($val->status == 4){
                 $val->status = "合伙人一级";
             }else if($val->status == 5){
                 $val->status = "合伙人二级";
             }else if($val->status == 6){
                 $val->status = "合伙人三级";
             }else if($val->status == 7){
                 $val->status = "合伙人四级";
             }else if($val->status == 8){
                 $val->status = "合伙人五级并可以申请城市管理中心";
             }else if($val->status == 9){
                 $val->status = "城市管理中心";
             }else if($val->status == 10){
                 $val->status = "成为县级管理";
             }else if($val->status == 11){
                 $val->status = "成为市级管理";
             }else if($val->status == 12){
                 $val->status = "成为省级管理";
             }else if($val->status == 13){
                 $val->status = "全国以上公司董事";
             }

         }
         var_dump($user);
         // return view("Admin.User.index",['user'=>$user]);
          
    }

     public static function membergrade($fywd,$uid)
    {
        //新会员注册时进行判断
        //获取到的邀请码
         //调用邀请码进行赋值 $pwd
        $cate=self::getcatebypid($fywd);
        //将所得到的对象转化成为数组
        $arr = json_decode(json_encode($cate), true);
        $data = [];
        foreach($arr as $val){
            //根据邀请码条件进行以下等级查询A区B区分条数
            $cnt = 0;
            array_walk_recursive($val, function($v, $k) use (&$cnt) { if($k == 'ywd') $cnt++;});
            //将得到的AB区分总条数放进数组中
            $data[] = $cnt - 1;
        }
        //var_dump($data);
        
        //AB两区间取区间最小值
        $dat = [];
       if(empty($data[1]) || $data[1] < 3){ 
           $status = 0;//"普通用户";
           $dat['status'] = $status;
           DB::table("user")->where("id","=",$uid)->update($dat);
        }else{
            if($data[0] >= $data[1]){
                $num = $data[1];
            }else{
                $num = $data[0];
            }
             //$dengji = DB::table("dengji")->get();
             //var_dump($dengji);
             //判断AB区各达到多少人，进行等级评定
                if(($num >= 3 && $num < 6)){
                       $status =1;// "成为体验人";
                }else if(($num >= 6 && $num < 20)){
                       $status = 2;//"成为合VIP体验人";
                }else if(($num >= 20 && $num < 40)){
                       $status = 3;//"成为合伙人服务中心";
                }else if(($num >= 40 && $num < 75)){
                       $status = 4;//"成为合伙人一级";
                }else if(($num >= 75 && $num < 150)){
                       $status = 5;//"成为合伙人二级";
                }else if(($num >= 150 && $num < 250)){
                       $status = 6;//"成为合伙人三级";
                }else if(($num >= 250 && $num < 375)){
                       $status = 7;//"成为合伙人四级";
                }else if($num >= 375 && $num < 700){
                       if($data[2] <= 300){
                          $status = 8;//"成为合伙人五级并可以申请城市管理中心";
                       }else if($data[2] >= 300 && $data[2] <= 600){
                          $status =9;//"成为城市管理中心";
                       }
                }else if($num >= 700 && $num < 1400){
                      if($data[2] <= 300){
                          $status = 8;//"成为合伙人五级并可以申请城市管理中心";
                       }else if($data[2] >= 300 && $data[2] <= 600){
                          $status = 9;//"成为城市管理中心";
                       }else if($data[2] >= 600){
                          $status = 10;//"成为县级管理";
                       }
                }else if($num >= 1400 && $num < 2100){
                       if($data[2] <= 300){
                          $status = 8;//"成为合伙人五级并可以申请城市管理中心";
                       }else if($data[2] >= 300 && $data[2] <= 600){
                          $status = 9;//"成为城市管理中心";
                       }else if($data[2] >= 600 && $data[2] <= 1200){
                          $status = 10;//"成为县级管理";
                       }else if($data[2] >= 1200){
                          $status = 11;//"成为市级管理";
                       }
                }else if($num >= 2100 && $num < 2800){
                       if($data[2] <= 300){
                          $status = 8;//"成为合伙人五级并可以申请城市管理中心";
                       }else if($data[2] >= 300 && $data[2] <= 600){
                          $status = 9;//"成为城市管理中心";
                       }else if($data[2] >= 600 && $data[2] <= 1200){
                          $status = 10;//"成为县级管理";
                       }else if($data[2] >= 1200 && $data[2] <= 1800){
                          $status = 11;//"成为市级管理";
                       }else if($data[2] >= 1800){
                          $status = 12;//"成为省级管理";
                       }
                }else if($num >= 2800){
                      if($data[2] <= 300){
                          $status = 8;//"成为合伙人五级并可以申请城市管理中心";
                       }else if($data[2] >= 300 && $data[2] <= 600){
                          $status = 9;//"成为城市管理中心";
                       }else if($data[2] >= 600 && $data[2] <= 1200){
                          $status = 10;//"成为县级管理";
                       }else if($data[2] >= 1200 && $data[2] <= 1800){
                          $status = 11;//"成为市级管理";
                       }else if($data[2] >= 1800 && $data[2] <= 2400){
                          $status = 12;//"成为省级管理";
                       }else if($data[2] >= 2400){
                          $status = 13;//"全国以上成为公司董事";
                       }  
                }
                $dat['status'] = $status;
                //var_dump($dat);
                DB::table("user")->where("id","=",$uid)->update($dat);
        }
    }
    
    public static function suanjifen()
    {
         $user = DB::table("user")->get();
         $users = $user->toArray($user); 
         $data = [];
         foreach($users as $val){
            $jifen = $val->jifen;
            $fywd = $val->fywd;
            $uid = $val->id;
            //调用邀请码进行赋值 $pwd
            $cate=self::getcatebypid($fywd);
            //dd($cate);
            //将所得到的对象转化成为数组
            $arr = json_decode(json_encode($cate), true);
            //var_dump(count($arr,1));
            //根据邀请码条件进行以下等级查询总条数
           // printf("\$arr 共有一级元素 %d 个\n", count($arr));
            //printf("  其中无下级元素的 %d 个\n", count(array_filter($arr, function($t) { return ! isset($t['sub']);})));
            $cnt = 0;
            array_walk_recursive($arr, function($v, $k) use (&$cnt) { if($k == 'ywd') $cnt++;});
            //printf("共有成员数组 %d 个\n", $cnt);
            $data['num'] = $cnt -1;
             //调用每个人达到线下人数相应增加的积分
             self::jifen($data['num'],$jifen,$uid,$val->status);
             
         }
    }  
    public static function jifen($num,$jifen,$uid,$status)
    {
        //echo $num;
        //跟名下人数来判断用户的等级积分
        if($status == 1){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 40;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 2){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 144;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 3){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 216;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 4){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 360;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 5){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 720;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 6){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 1080;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 7){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 1440;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 8){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 2160;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 9){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 3600;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 10){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 5400;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 11){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 7200;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 12){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 14400;
            DB::table("user")->where("id","=",$uid)->update($data);
        }else if($status == 13){
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 21600;
            DB::table("user")->where("id","=",$uid)->update($data);
        }
    }

    public static function tuijian($nums,$uid)
    {
        //;echo $nums;
        //进行大于3人后的推荐奖计算
        $money = [];
        if($nums > 3){
            $number = ($nums - 3) * 0.1 * 1200;
            $money['tuijianjiang'] = round($number,0);
            DB::table("user")->where("id","=",$uid)->update($money);
        }else{
            $number = 0;
            $money['tuijianjiang'] = $number;
            DB::table("user")->where("id","=",$uid)->update($money);
        }
    }
     
    public function create()
    {
       
    }

    /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersInsertRequest $request)
    {  

    }

    /**
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   

    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {   

    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   

    }
}
