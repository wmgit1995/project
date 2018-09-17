<?php

namespace App\Http\Controllers\Adminuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class IndexController extends Controller
{
    public static function getcatebypid($ywd)
    {
        //获取顶级分类
        $user=DB::table("user")->where("fywd","=",$ywd)->get();
        //var_dump($user);die;
        $data=[];
        //遍历等级分类进行下一级查找
        foreach($user as $key=>$val){
            //var_dump($val);die;
            $val->sub=self::getcatebypid($val->ywd);
            $data[]=$val;
        }
        return $data;   
    }
    public function index()
    {
         //调用邀请码进行赋值 $pwd
        $cate=self::getcatebypid(456);
        dd($cate);
        //将所得到的对象转化成为数组
        $arr = json_decode(json_encode($cate), true);
		//var_dump(count($arr,1));
        //根据邀请码条件进行以下等级查询总条数
       // printf("\$arr 共有一级元素 %d 个\n", count($arr));
		//printf("  其中无下级元素的 %d 个\n", count(array_filter($arr, function($t) { return ! isset($t['sub']);})));
		$cnt = 0;
		array_walk_recursive($arr, function($v, $k) use (&$cnt) { if($k == 'ywd') $cnt++;});
		//printf("共有成员数组 %d 个\n", $cnt);
		echo $cnt;

    }

    public function ywdaction()
    {
    	//查询邀请码使用次数
    	$ywd = DB::table("user")->where("fywd","=",456)->get();
    	$count = count($ywd);
        $cishu=self::membergrade();
        if($num >= 20){
            //开放第三只线
            if($count > 3){
                echo "对不起您的邀请码最多只能邀请注册三人";
            }
            //判断使用的邀请码是否大于两次
        }else if($count > 2){
            echo "对不起您的邀请码最多只能邀请注册两人";
        }else{
            echo $count;
        }	
    }

    public static function membergrade()
    {
        //新会员注册时进行判断
    	//获取到的邀请码
    	$ywd = 456;
         //调用邀请码进行赋值 $pwd
        $cate=self::getcatebypid($ywd);
        //将所得到的对象转化成为数组
        $arr = json_decode(json_encode($cate), true);
        $data = [];
        foreach($arr as $val){
        	//根据邀请码条件进行以下等级查询A区B区分条数
        	$cnt = 0;
			array_walk_recursive($val, function($v, $k) use (&$cnt) { if($k == 'ywd') $cnt++;});
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
               }  
        }
    }

    //发放积分制度
    public function jiangli()
    { 
        //获取老用户的信息
        $cat = DB::table("user_message")->where("uid","=",1)->first();
        //获取老用户的名下人数
        $num = $cat->num;
        //跟名下人数来判断用户的等级积分
        if($num >= 3 && $num <6){
            $jifen = $cat->jifen;
            $data = [];
            //每执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 40;
            DB::table("user_message")->update($data);
        }else if($num >= 6){
            $jifen = $cat->jifen;
            $data = [];
            //没执行一次方法数据库增加相应积分
            $data['jifen'] = $jifen + 144;
            DB::table("user_message")->update($data);
        }
        

    }
}
