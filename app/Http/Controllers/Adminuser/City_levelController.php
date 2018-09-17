<?php

namespace App\Http\Controllers\Adminuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LevelInsertRequest;
//use App\Http\Requests\AdminuserUpdateRequest;
use DB;
use Hash;
class City_levelController extends Controller
{
  
    public function index(Request $request)
    {
        $level = DB::table("level_copy")->paginate(5);
         return view("Admin.City_level.index",["level"=>$level,'request'=>$request->all()]);
    }

    public function create()
    {
       return view("Admin.City_level.add");
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
          if(DB::table("level_copy")->insert($data)){
                return redirect("/city_level")->with("success","添加成功");
            }else{
                return redirect("/city_level/create")->with("error","添加失败");
            }
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
      $info = DB::table("level_copy")->where("id","=",$id)->first();
    	return view("Admin.City_level.edit",["info"=>$info]);
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
          if(DB::table("level_copy")->where("id","=",$id)->update($data)){
                return redirect("/city_level")->with("success","修改成功");
            }else{
                return redirect("/city_level")->with("error","修改失败");
            }

    }
    public function destroy($id)
    {
        if(DB::table('level_copy')->where("id","=",$id)->delete()){
            return redirect("/city_level")->with("success","删除成功");
        }else{
            return redirect("/city_level")->eith("error","删除失败");
        }
    }

}
