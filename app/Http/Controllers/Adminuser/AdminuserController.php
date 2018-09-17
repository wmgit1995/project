<?php

namespace App\Http\Controllers\Adminuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Requests\AdminuserInsertRequest;
// use App\Http\Requests\AdminuserUpdateRequest;
use DB;
use Hash;
class AdminuserController extends Controller
{
   
    public function index(Request $request)
    {
    	if($users = DB::table("user")->where("downline",">",3)->get()){
            $data = [];
            foreach($users as $val){
                    //线下总人数
                    $nums = $val->downline;
                    $id = $val->id;
                    //计算线下人数任务健康分享3人第4人开始提成10%奖励
                    $number = ($nums -3 ) * 0.1 * 1200;  
                    $data['recommend_money']=number_format($number,2,".",""); 
                    DB::table("user")->where("id","=",$id)->update($data);
            }
        }
        //获取搜索关键字
        $k=$request->input("keywords");
        //进行数据库连接 搜索 分页
        $user = DB::table("user")->where("user_phone","like","%".$k."%")->paginate(5);
        return view("Admin.User.index",["user"=>$user,'request'=>$request->all()]);
    }
    public function result(Request $request)
    {   
        if($users = DB::table("user")->where("downline",">",3)->get()){
            $data = [];
            foreach($users as $val){
                    //线下总人数
                    $nums = $val->downline;
                    $id = $val->id;
                    //计算线下人数任务健康分享3人第4人开始提成10%奖励
                    $number = ($nums -3 ) * 0.1 * 1200;  
                    $data['recommend_money']=number_format($number,2,".",""); 
                    DB::table("user")->where("id","=",$id)->update($data);
            }
        }
        //获取搜索关键字
        $kk=$request->input("keywordss");
        $user = DB::table("user")->where("id","=",$kk)->paginate(1);
        return view("Admin.User.index",["user"=>$user,'request'=>$request->all()]);
    }
    public function dong(Request $request)
    {
         $uid = $request->only("uid");
         $user = DB::table("user")->where("id","=",$uid)->first();
         $status = $user->user_status;
         if($status == "账户正常"){
            $data['user_status'] = "已冻结(解冻)";
            if(DB::table("user")->where("id","=",$uid)->update($data)){
               echo 1;
            }
        }else if($status == "已冻结(解冻)"){
            $data['user_status'] = "账户正常";
            if(DB::table("user")->where("id","=",$uid)->update($data)){
               echo 2;
            }
        }
         
    }

    public function lookrule(Request $request)
    {
         $id = $request->only("id");
         $user = DB::table("user")->where("id","=",$id)->first();
         echo $user->path;
         
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

    public function levelstatus(Request $request)
    {
         $id = $request->only("level_id");
         $user = DB::table("user")->where("id","=",$id)->first();
         $yqm = $user->old_yqm;
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
              //AB两区间取区间最小值
              $dat = [];
             if(empty($data[1]) || $data[1] < 3){ 
                 $status = 0;//"普通用户";
                 $dat['user_grade'] = $status;
                 DB::table("user")->where("id","=",$uid)->update($dat);
              }else{
                  if($data[0] >= $data[1]){
                      $num = $data[1];
                  }else{
                      $num = $data[0];
                  }
                   $dengji = DB::table("level")->get();
                   $da = [];
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
                    //var_dump($da[0]);die;
                   //判断AB区各达到多少人，进行等级评定
                      if(($num >= $da[0] && $num <  $da[1])){
                             $grade = DB::table("level")->where("A","=",$da[0])->first();
                             $status = $grade->grade;// "成为体验人";
                      }else if(($num >=  $da[1] && $num <  $da[2])){
                             $grade = DB::table("level")->where("A","=",$da[1])->first();
                             $status = $grade->grade;//"成为合VIP体验人";
                      }else if(($num >=  $da[2] && $num <  $da[3])){
                             $grade = DB::table("level")->where("A","=",$da[2])->first();
                             $status = $grade->grade;//"成为合伙人服务中心";
                      }else if(($num >=  $da[3] && $num <  $da[4])){
                             $grade = DB::table("level")->where("A","=",$da[3])->first();
                             $status = $grade->grade;//"成为合伙人一级";
                      }else if(($num >=  $da[4] && $num <  $da[5])){
                             $grade = DB::table("level")->where("A","=",$da[4])->first();
                             $status = $grade->grade;//"成为合伙人二级";
                      }else if(($num >= $da[5] && $num < $da[6])){
                             $grade = DB::table("level")->where("A","=",$da[5])->first();
                             $status = $grade->grade;//"成为合伙人三级";
                      }else if(($num >= $da[6] && $num < $da[7])){
                             $grade = DB::table("level")->where("A","=",$da[6])->first();
                             $status = $grade->grade;;//"成为合伙人四级";
                      }else if($num >= $da[7] && $num < $da[8]){
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
                      //$dat['user_grade'] = $status;
                      var_dump($status);
                      //DB::table("user")->where("id","=",$uid)->update($dat);
              }

    }

    public function create()
    {
        return view("Admin.Adminuser.add");
    }

    public function store(AdminuserInsertRequest $request)
    {
    	//获取添加的字段
    	$data=$request->only(['name','pass']);
        $data['pass']=Hash::make($data['pass']);
        //判断插入数据是否成功
        if(DB::table("admin_user")->insert($data)){
        	return redirect("/adminsuser")->with("success","添加成功");
        }else{
        	return redirect("/adminsuser/create")->with("error","添加失败");
        }
       
    }

   
    public function show($id)
    {
        //
    }

       public function edit($id)
    {
    	$inp=DB::table("admin_user")->where("id","=",$id)->first();
        return view("Admin.Adminuser.edit",['inp'=>$inp]);
    }

  
    public function update(AdminuserUpdateRequest $request, $id)
    {
        //获取数据库所有name字段值
        $list = DB::table('admin_user') -> pluck('name');
        //将获得的对象转化为数组
        $arr = $list ->toArray();
        //获取input中的数据
        $data = $request -> only('name','pass');
        $res = DB::table('admin_user') -> where('id','=',$id) -> value('name');
        //判断name时候否存在数据库中
        if(in_array($data['name'], $arr)){
            if($res == $data['name']){
              //不允许设置name
              unset($data['name']);
            }else{
               return redirect("/adminsuser/{$id}/edit") -> with('error','管理员名重复'); 
            }  
        }
        //将密码进行加密
        $data['pass'] = Hash::make($data['pass']);
        //判断数据库修改是否成功
        if(DB::table('admin_user') -> where('id','=',$id) -> update($data)){
            return redirect('/adminsuser') -> with('success','数据修改成功');
        }else{
            return redirect("/adminsuser/{$id}/edit") -> with('error','数据修改失败');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function delete(Request $request)
    {
    	//获取删除的id
         $id=$request->input("id");
        //判断删除数据是否成功
        if(DB::table("admin_user")->where("id","=",$id)->delete()){
        	echo "1";
        }else{
        	echo "0";
        }

    }

    public  function lineover($id)
    {
        //获取当前会员信息
        $user = DB::table("user")->where("id","=",$id)->first();
        $old_yqm = $user->old_yqm;
        //获取第二级用户信息
        $data = DB::table("user")->where("yqm","=",$old_yqm)->get();
       // var_dump($uuser);
       
       // $cate = self::overline($old_yqm); 
        return view("Admin.User.ind",['data'=>$data]);

    }

    public static function overline($old_yqm)
    {
       
        $data = [];
        foreach($uuser as $val){
             //获取第二级所有会员ID
             $old_yqm = $val->old_yqm;
             self::overline($old_yqm);
             $count = count($val);
             var_dump($val);
        }
         // return $data;
        
        //return redirect("/lineover",['dat]);a'=>$data
    }
}
