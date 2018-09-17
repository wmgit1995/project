@extends("Admin.AdminPublic.public")
@section('title','等级修改')
@section('content')
  <div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            修改列表
        </header>
        <div class="panel-body">
            <form action="/city_level/{{$info->id}}" class="form-horizontal adminex-form" method="post">
            	<!-- 提示信息开始 -->
                     @if(count($errors)>0)	
	                 <div class="alert alert-warning fade in">
	                     <button type="button" class="close close-sm" data-dismiss="alert">
                                    <i class="fa fa-times"></i>
                         </button>
	                 @foreach($errors->all() as $error)
	                        <ul>
	                     	<li><strong>{{$error}}</strong></li>
	                     	</ul>
	                 @endforeach   
	                 </div>
	            <!-- 提示信息接结束 -->
            @endif
           <!--  用户信息添加开始 -->
                <div class="form-group has-success">
                    <label class="col-lg-2 control-label">
                        A区
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="f-name" class="form-control" name="A" value="{{$info->A}}">
                    </div>
                </div>
                <div class="form-group has-error">
                    <label class="col-lg-2 control-label">
                       B区
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="l-name" class="form-control" name="B" value="{{$info->B}}">
                    </div>
                </div>
                 <div class="form-group has-error">
                    <label class="col-lg-2 control-label">
                       C区
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="l-name" class="form-control" name="C" value="{{$info->C}}">
                    </div>
                </div>

                <div class="form-group has-warning">
                    <label class="col-lg-2 control-label">
                        日收入
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="email2" class="form-control" name="day" value="{{$info->daily_income}}">
                    </div>
                </div>
                <div class="form-group has-warning">
                    <label class="col-lg-2 control-label">
                        等级
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="email2" class="form-control" name="grade" value="{{$info->grade}}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button class="btn btn-primary" type="submit">
                           修改
                        </button>
                    </div>
                </div>
                {{method_field('PUT')}}
                {{csrf_field()}}
            </form>
            <!--  用户信息添加结束 -->
        </div>
    </section>
</div>
@endsection