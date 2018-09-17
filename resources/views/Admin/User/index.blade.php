@extends("Admin.AdminPublic.public")
@section('title','用户列表')
@section('content')
  
  <div class="col-sm-12">
	    <section class="panel">
	    <header class="panel-heading">
	        用户列表
	        <span class="tools pull-right">
	            <a href="javascript:;" class="fa fa-chevron-down"></a>
	            <a href="javascript:;" class="fa fa-times"></a>
	         </span>
	    </header>
	    <div class="panel-body">
	    <div class="adv-table editable-table ">
	    <div class="clearfix">
	 
	    </div>
	    <div class="space15"></div>
	    
	    <div class="col-lg-6">
	    <!-- 搜索框开始 -->
	     <form action="/adminuser" method="get">
	    	<div class="dataTables_filter" id="editable-sample_filter">
	    		<div style="position:relative;left:485px;top:30px;">用户名</div>
	    		<input type="text" placeholder="按照用户名搜索" name="keywords" aria-controls="editable-sample" class="form-control medium" value="{{$request['keywords'] or ''}}" style="position:relative;left:550px;width:150px;">

	    		<input type="submit" value="搜索" class="btn btn-success" style="position:relative;left:750px;bottom:35px;">
	    	</div>
	     </form>
	     <form action="/search" method="get">
	    	<div class="dataTables_filter" id="editable-sample_filter">
	    		<div style="position:absolute;left:600px;bottom:80px;">ID</div>
	    		<input type="text" placeholder="按照用户ID搜索" name="keywordss" aria-controls="editable-sample" class="form-control medium" value="{{$request['keywordss'] or ''}}" style="position:absolute;left:663px;width:150px;bottom:75px;">

	    		<input type="submit" value="搜索" class="btn btn-success" style="position:relative;left:750px;bottom:60px;">
	    	</div>
	     </form>
	    <!-- 搜索框结束 -->
	    </div>
	</div>
	    <!-- 表格开始 -->
	    <table class="table table-striped table-hover table-bordered dataTable" id="editable-sample" aria-describedby="editable-sample_info">
	    <thead>
	    <tr role="row"><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Last Name: activate to sort column ascending" style="width:50px;">用户名</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 40px;">邀请码</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 40px;">线下人数</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 20px;">推荐奖</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 15px;">总金额</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 15px;">等级分区</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 20px;">级别路径</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 15px;">直属会员</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Delete: activate to sort column ascending" style="width: 115px;">操作</th></tr>
	    </thead>
	    
	    <tbody role="alert" aria-live="polite" aria-relevant="all">
	    	@foreach($user as $row)
	    		    <tr class="odd">
	    		        <td class=" " align="center" valign="middle">{{$row->user_phone}}</td>
	    		        <td class=" " align="center" valign="middle">{{$row->old_yqm}}</td>
	    		        <td class=" " align="center" valign="middle">{{$row->downline}}</td>
	    		        <td class=" " align="center" valign="middle">{{$row->recommend_money}}</td>
	    		        <td class=" " align="center" valign="middle">{{$row->totalmount}}</td>
	    		        <td class=" " align="center" valign="middle"><a href="javascript:void(0)" class="grade subb_{{$row->id}}" cat_id="{{$row->id}}">查看分区</a></td>
	    		        <td class=" " align="center" valign="middle"><a href="javascript:void(0);" class="btn btn-info su_{{$row->id}} rule" cate_id="{{$row->id}}">查看路径</a></td>
	    		        <td class=" " align="center" valign="middle"><a class="btn btn-info" href="/line/{{$row->id}}">查看</a></td>
	    		        <td class=" " align="center" valign="middle">
                            <form action="/adminuser/{{$row->id}}" method="post">
                            	{{csrf_field()}}
                            	{{method_field("DELETE")}}
                            	<div>
	                            	 <div style="width:50%;padding:0;margin:0;float:left;box-sizing:border-box;">
	                            	 	<button class="delete btn btn-danger" >Delete</button>
	                            	 </div>
	                            	 <div style="width:50%;padding:0;margin:0;float:left;box-sizing:border-box;">
	                            	 	<a class="edit btn btn-info sub_{{$row->id}}" href="javascript:void(0);" data_id="{{$row->id}}">{{$row->user_status}}</a>
	                            	 </div>
                                </div>
                            </form>
	    		        </td>
	    		    </tr>
	    	@endforeach	    
	    	
	</tbody>
	    	</table><div class="row"><div class="col-lg-6">
	    	<!-- 表格结束 -->	
	    	</div><div class="col-lg-6">
	    		<div class="dataTables_paginate paging_bootstrap pagination">
	    		{!!$user->appends($request)->render()!!}	
	    	</div></div></div></div>
	    </div>
	    </div>
	    </section>
  </div>
  	    		    <script>
	    		       $(".edit").click(function(){
	    		       	   var uid = $(this).attr('data_id');
	    		       	   var sub = "." + "sub_" + uid;
                           $.get("/dongjie",{"uid":uid},function(data){
                                    if(data == 1){
                                    	$(sub).html("已冻结(解冻)");
                                    }else if(data == 2){
                                    	$(sub).html("账户正常");
                                    }
                           });
	    		       });
	    		    	
	    		    </script>
	    		     <script>
	    		       $(".rule").click(function(){
	    		       	   var id = $(this).attr('cate_id');
	    		       	   var su = "." + "su_" + id;
                           $.get("/look_rule",{"id":id},function(data){
                                    alert(data);
                           });
	    		       });
	    		    	
	    		    </script>
	    		    <script>
	    		       $(".grade").click(function(){
	    		       	   var level_id = $(this).attr('cat_id');
	    		       	   var subb = "." + "subb_" + level_id;
                           $.get("/rank_view",{"level_id":level_id},function(data){
                                    if(data == 1){
                                    	alert("线下没有会员");
                                    }else if(data == 2){
                                    	window.location.href="/chakan?leval_id"+"="+level_id;
                                    }
                           });
	    		       });
	    		    	
	    		    </script>

@endsection