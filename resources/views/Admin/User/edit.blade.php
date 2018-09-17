@extends("Admin.AdminPublic.public")
@section('title','用户修改')
@section('content')
  <div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            修改列表
        </header>
        <div class="panel-body">
            <form action="/adminuser/{{$info->id}}" class="form-horizontal adminex-form" method="post">
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
                     @endif
                <!-- 提示信息接结束 -->
                <div class="form-group has-success">
                    <label class="col-lg-2 control-label">
                        用户名
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="f-name" class="form-control" name="name" value="{{$info->name}}">
                    </div>
                </div>
                <div class="form-group has-error">
                    <label class="col-lg-2 control-label">
                        密码
                    </label>
                    <div class="col-lg-10">
                        <input type="password" placeholder="" id="l-name" class="form-control" name="pass">
                    </div>
                </div>
                
                <div class="form-group has-warning">
                    <label class="col-lg-2 control-label">
                        电话
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="email2" class="form-control" name="phone"  value="{{$info->phone}}">
                    </div>
                </div>
                <div class="form-group has-warning">
                    <label class="col-lg-2 control-label">
                        邮箱
                    </label>
                    <div class="col-lg-10">
                        <input type="text" placeholder="" id="email2" class="form-control" name="email"  value="{{$info->email}}">
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
        </div>
    </section>
</div>
	
@endsection