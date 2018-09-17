<?php

namespace App\Http\Controllers\Adminuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LevelInsertRequest;
//use App\Http\Requests\AdminuserUpdateRequest;
use DB;
use Hash;
class LevelController extends Controller
{
  
    public function index(Request $request)
    {

        $level = DB::table("level")->paginate(5);
         return view("Admin.Level.index",["level"=>$level,'request'=>$request->all()]);
    }

    public function create()
    {
       return view("Admin.Level.add");
    }

    public function store(Request $request)
    {
          //获取全部添加数据
    	  $data = $request->all();
          //计算月积分
          $data['monthly_income'] = $data["day"] * 30;
          $data['daily_income'] = $data["day"];
          unset($data['_token']);
          unset($data['day']);
          if(DB::table("level")->insert($data)){
                return redirect("/level")->with("success","添加成功");
            }else{
                return redirect("/level/create")->with("error","添加失败");
            }
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $info = DB::table("level")->where("id","=",$id)->first();
    	return view("Admin.Level.edit",["info"=>$info]);
    }

    public function update(Request $request,$id)
    {
        //获取全部添加数据
          $data = $request->all();
          //计算月积分
          $data['monthly_income'] = $data["day"] * 30;
          $data['daily_income'] = $data["day"];
          unset($data['_token']);
          unset($data['day']);
          unset($data['_method']);
          if(DB::table("level")->where("id","=",$id)->update($data)){
                return redirect("/level")->with("success","修改成功");
            }else{
                return redirect("/level")->with("error","修改失败");
            }

    }
    public function destroy($id)
    {
        if(DB::table('level')->where("id","=",$id)->delete()){
            return redirect("/level")->with("success","删除成功");
        }else{
            return redirect("/level")->eith("error","删除失败");
        }
    }
    
    public static function getcatebypid($yqm)
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

    public function partition(Request $request)
    {
         $id = $request->only("level_id");
         $yonghu = DB::table("user")->where("id","=",$id)->first();
         $yqm = $yonghu->old_yqm;
             //调用邀请码进行赋值 $pwd
            $cate=self::getcatebypid($yqm);
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
            //匹配ABC三区人数
            if($cnt == 3){
                $lev["A_area"] = $data[0];
                $lev["B_area"] = $data[1];
                $lev["C_area"] = $data[2];
            }else if($cnt == 2){
                $lev["A_area"] = $data[0];
                $lev["B_area"] = $data[1];
            }else if($cnt == 1){
                $lev["A_area"] = $data[0];
            }else if($cnt == 0){
                echo 1;
                die;
            }
            //修改ABC三区的单支线下人数
            DB::table("user")->where("id","=",$id)->update($lev);
  
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
                 //判断三支线AB区最短线不超过375节点
                       $dengji = DB::table("level")->get();
                       $da = [];
                       $dat = [];
                       //获取等级人数队列
                       foreach($dengji as $va){
                           $A = $va->A;
                           $da[] = $A;
                       } 
                       $count = count($da);
                       //将获取的索引数组值进行由小到大的排序
                       for($n=0;$n<$count-1;$n++){
                            //内层循环n-i-1
                             for($i=0;$i<$count-$n-1;$i++){
                                 //判断数组元素大小，交换位置，实现从小往大排序
                                if($da[$i]>$da[$i+1]){
                                    $temp=$da[$i+1];
                                    $da[$i+1]=$da[$i];
                                    $da[$i]=$temp; 
                                 }   
                             }
                        } 
                        //判断AB区各达到多少人，进行等级评定
                        for($a = 0; $a<$count;$a ++){
                             if($num < $da[$a]){
                                     $gra = DB::table("level")->where("A","=",$da[$a-1])->first();
                                      $user_grade = $gra->grade;
                                      $dat['user_grade'] = $user_grade;
                                      break; 
                             }
                        } 
                        //var_dump($user_grade);
                          //var_dump($dat);
                          DB::table("user")->where("id","=",$id)->update($dat);
                          echo 2;
              }else{
                      //判断三支线AB区最短线超过375节点
                      //当$cnt大于2的为三条支线
                       $dengji = DB::table("level_copy")->get();
                               $cate = [];
                               $cca = [];
                               //获取等级人数队列
                               foreach($dengji as $va){
                                   $A = $va->A;
                                   $cate[] = $A;
                               } 
                               $count = count($cate);
                               //将获取的索引数组值进行由小到大的排序
                               for($n=0;$n<$count-1;$n++){
                                    //内层循环n-i-1
                                     for($i=0;$i<$count-$n-1;$i++){
                                         //判断数组元素大小，交换位置，实现从小往大排序
                                        if($cate[$i]>$cate[$i+1]){
                                            $temp=$cate[$i+1];
                                            $cate[$i+1]=$cate[$i];
                                            $cate[$i]=$temp; 
                                         }   
                                     }
                                } 
                                //var_dump($cate);die;
                                //判断AB区各达到多少人，进行等级评定
                                for($a = 0; $a<$count;$a ++){
                                     if($num < $cate[$a]){
                                        $gra = DB::table("level_copy")->where("A","=",$cate[$a-1])->first();
                                        $user_grade = $gra->grade;
                                        $user_C = $gra->C;
                                        if($data[2] >= $user_C){
                                            break;
                                        }else{
                                           $user_grade = "合伙人五级";
                                           break;
                                        }
                                       
                                     }
                                } 
                                var_dump($user_grade);die;
                                $cca['user_grade'] = $user_grade;
                                  //var_dump($cca);die;
                                  DB::table("user")->where("id","=",$id)->update($cca);
                                  echo 2;
              }
            }else if($cnt == 2){
                               //当$cnt =2为两条支线
                               //AB两区间取区间最小值
                               if($data[0] >= $data[1]){
                                      $num = $data[1];
                                  }else{
                                      $num = $data[0];
                                  }
               if($num > 6){
                       /*//判断是否可以申请C支线或合伙人以及城市服务中心
                            $C_number = DB::table("C_number")->first();
                            $numb = $C_number->C;
                            $partner_center = $C_number->partner_center;
                            $city_center = $C_number->city_center;
                            if($num > $numb && $yonghu->C_status == "未开通"){
                                   echo 1;//可申请C支线
                            }
                            if($num > $partner_center && $yonghu->C_status == "已开通" && $num < $city_center){
                                  echo 2;//可以申请合伙人服务中心
                            }
                            if($num > $city_center && $yonghu->partner_status == "已开通"){
                                    echo 3;//可以申请城市服务中心
                            }*/  
            
                             $dengji = DB::table("level")->get();
                             $da = [];
                             $dat = [];
                             //获取等级人数队列
                             foreach($dengji as $va){
                                 $A = $va->A;
                                 $da[] = $A;
                             } 
                             $count = count($da);
                             //将获取的索引数组值进行由小到大的排序
                             for($n=0;$n<$count-1;$n++){
                                  //内层循环n-i-1
                                   for($i=0;$i<$count-$n-1;$i++){
                                       //判断数组元素大小，交换位置，实现从小往大排序
                                      if($da[$i]>$da[$i+1]){
                                          $temp=$da[$i+1];
                                          $da[$i+1]=$da[$i];
                                          $da[$i]=$temp; 
                                       }   
                                   }
                              } 
                              //判断AB区各达到多少人，进行等级评定
                              for($a = 0; $a<$count;$a ++){
                                   if($num < $da[$a]){
                                           $gra = DB::table("level")->where("A","=",$da[$a-1])->first();
                                            $user_grade = $gra->grade;
                                            $dat['user_grade'] = $user_grade;
                                            break;
                                   }
                              } 
                              //var_dump($user_grade);
                                //var_dump($dat);
                                DB::table("user")->where("id","=",$id)->update($dat);
                                echo 2; 
                }else if($data[0] + $data[1] >3){
                              //判断用户线下人数是否大于3
                             $va = [];
                             $va['user_grade'] = "体验人";
                             DB::table("user")->where("id","=",$id)->update($va);
                             echo 2;
               }
            }else if($cnt == 1){
                if($data[0] < 3){
                     //当$cnt小于2时为一条支线     
                     $dat['user_grade'] = "普通用户";
                     //var_dump($dat);
                     DB::table("user")->where("id","=",$id)->update($dat);
                     echo 2;
                }else{
                    //当$cnt小于2时为一条支线     
                     $dat['user_grade'] = "体验人";
                     //var_dump($dat);
                     DB::table("user")->where("id","=",$id)->update($dat);
                     echo 2;
                }
                   
            }  
    }

    public function fenqu()
    {
      $id =  $_GET["leval_id"];
      $row = DB::table("user")->where("id","=",$id)->first();
      return view("Admin.Partition.index",["row"=>$row]);
    }

}
