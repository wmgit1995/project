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
	     
	    <!-- 搜索框结束 -->
	    </div>
	</div>
	    <!-- 表格开始 -->
	    <table class="table table-striped table-hover table-bordered dataTable" id="editable-sample" aria-describedby="editable-sample_info">
	    <thead>
	    <tr role="row"><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Last Name: activate to sort column ascending" style="width:50px;">等级</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 40px;">A区</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 20px;">B区</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 15px;">C区</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Edit: activate to sort column ascending" style="width: 15px;">日收入</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="editable-sample" rowspan="1" colspan="1" aria-label="Delete: activate to sort column ascending" style="width: 115px;">操作</th></tr>
	    </thead>
	    
	    <tbody role="alert" aria-live="polite" aria-relevant="all">
	    	@foreach($level as $row)
	    		    <tr class="odd">
	    		        <td class=" " align="center" valign="middle">{{$row->grade}}</td>
	    		        <td class=" " align="center" valign="middle">{{$row->A}}</td>
	    		        <td class=" " align="center" valign="middle">{{$row->B}}</td>
	    		        <td class=" " align="center" valign="middle">{{$row->C}}</td>
	    		        <td class=" " align="center" valign="middle">{{$row->daily_income}}</td>
	    		        <td class=" " align="center" valign="middle">
                            <form action="/city_level/{{$row->id}}" method="post">
                            	{{csrf_field()}}
                            	{{method_field("DELETE")}}
                            	<div>
	                            	 <div style="width:50%;padding:0;margin:0;float:left;box-sizing:border-box;">
	                            	 	<button class="delete btn btn-danger" >Delete</button>
	                            	 </div>
	                            	 <div style="width:50%;padding:0;margin:0;float:left;box-sizing:border-box;">
	                            	 	<a class="edit btn btn-info" href="/city_level/{{$row->id}}/edit" data_id="{{$row->id}}">修改</a>
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
	    		{!!$level->appends($request)->render()!!}
	    	</div></div></div></div>
	    </div>
	    </div>
	    </section>
  </div>
@endsection