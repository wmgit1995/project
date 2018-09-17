@extends("Admin.AdminPublic.public")
@section('title','用户添加')
@section('content')
  <div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            添加列表
        </header>
        <div class="panel-body">
            <form action="/adminuser" class="form-horizontal adminex-form" method="post">
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
                        用户名
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="f-name" class="form-control" name="name" value="{{old('name')}}">
                    </div>
                </div>
                <div class="form-group has-error">
                    <label class="col-lg-2 control-label">
                       密码
                    </label>
                    <div class="col-lg-10">
                        <input type="password" placeholder="" id="l-name" class="form-control" name="pass" value="{{old('pass')}}">
                    </div>
                </div>
                 <div class="form-group has-error">
                    <label class="col-lg-2 control-label">
                       确认密码
                    </label>
                    <div class="col-lg-10">
                        <input type="password" placeholder="" id="l-name" class="form-control" name="repass" value="{{old('repass')}}">
                    </div>
                </div>

                <div class="form-group has-warning">
                    <label class="col-lg-2 control-label">
                        电话
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="email2" class="form-control" name="phone" value="{{old('phone')}}">
                    </div>
                </div>
                <div class="form-group has-warning">
                    <label class="col-lg-2 control-label">
                        邮箱
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="email2" class="form-control" name="email" value="{{old('email')}}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button class="btn btn-primary" type="submit">
                           添加
                        </button>
                    </div>
                </div>
                {{csrf_field()}}
            </form>
            <!--  用户信息添加结束 -->
        </div>
    </section>
</div>
@endsection