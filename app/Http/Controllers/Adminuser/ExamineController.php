<?php

namespace App\Http\Controllers\Adminuser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminuserInsertRequest;
use App\Http\Requests\AdminuserUpdateRequest;
use DB;
use Hash;
class ExamineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = DB::table("user")->where("C_status","=","正在审核")->paginate(5);
        return view("Admin.Examine.index",["user"=>$user,'request'=>$request->all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminuserInsertRequest $request)
    {
    	
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminuserUpdateRequest $request, $id)
    {
        
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
    public static function getcatebypid($yqm)
    {
        //获取顶级分类
        $user=DB::table("user")->where("yqm","=",$yqm)->get();
        //var_dump($user);die;
        $data = [];
        //遍历等级分类进行下一级查找
        foreach($user as $key=>$val){
           $val->sub = self::getcatebypid($val->old_yqm);
           $data[] = $val;
        }var_dump($data);
         return $data;
    }
    public function partner_examine()
    {
        $old_yqm = 1;
        $cate = self::getcatebypid($old_yqm); 
        
            
    }
    public function city_examine($old)
    {
        //
    }
    

}
