<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="ThemeBucket">
  <link rel="shortcut icon" href="#" type="image/png">


  <title>@yield("title")</title>

  <link href="/static/admins/b/css/style.css" rel="stylesheet">
  <link href="/static/admins/b/css/style-responsive.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]><![endif]-->
  <script src="/static/admins/b/js/html5shiv.js"></script>
  <script src="/static/admins/b/js/respond.min.js"></script>
  <script src="/static/admins/b/js/jquery-1.7.2.min.js"></script>
  
</head>

<body class="sticky-header">

<section>
    <!-- left side start-->
    <div class="left-side sticky-left-side">

        <!--logo and iconic logo start-->
        <div class="logo">
            <a href="/admins"><img src="/static/admins/b/images/logo.png" alt=""></a>
        </div>

        <div class="logo-icon text-center">
            <a href="/admins"><img src="/static/admins/b/images/logo_icon.png" alt=""></a>
        </div>
        <!--logo and iconic logo end-->


        <div class="left-side-inner">

            <!-- visible to small devices only -->
            <div class="visible-xs hidden-sm hidden-md hidden-lg">
                <div class="media logged-user">
                    <img alt="" src="/static/admins/b/images/photos/user-avatar.png" class="media-object">
                    <div class="media-body">
                        <h4><a href="#">John Doe</a></h4>
                        <span>"Hello There..."</span>
                    </div>
                </div>

                <h5 class="left-nav-title">Account Information</h5>
                <ul class="nav nav-pills nav-stacked custom-nav">
                    <li><a href="#"><i class="fa fa-user"></i> <span>个人中心</span></a></li>
                    <li><a href="#"><i class="fa fa-cog"></i> <span>设置</span></a></li>
                    <li><a href="#"><i class="fa fa-sign-out"></i> <span>退出登录</span></a></li>
                </ul>
            </div>

            <!--sidebar nav start-->
            <ul class="nav nav-pills nav-stacked custom-nav">
                <li><a href="/admins"><i class="fa fa-home"></i> <span>MY Home</span></a></li>
                <li class="menu-list nav-active"><a href=""><i class="fa fa-laptop"></i> <span>用户管理</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="/adminuser">用户列表</a></li>
                    </ul>
                </li>
                <li class="menu-list"><a href=""><i class="fa fa-book"></i> <span>合伙人服务中心管理</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="/level/create">等级添加</a></li>
                        <li><a href="/level">等级列表</a></li>
                    </ul>
                </li>
                <li class="menu-list"><a href=""><i class="fa fa-book"></i> <span>城市中心管理</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="/city_level/create">等级添加</a></li>
                        <li><a href="/city_level">等级列表</a></li>
                    </ul>
                </li>
                <li class="menu-list"><a href=""><i class="fa fa-book"></i> <span>审核中心</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="/examine">审核列表</a></li>
                    </ul>
                </li>
                
                
            </ul>
            <!--sidebar nav end-->

        </div>
    </div>
    <!-- left side end-->
    
    <!-- main content start-->
    <div class="main-content" >

        <!-- header section start-->
        <div class="header-section">

        <!--toggle button start-->
        <a class="toggle-btn"><i class="fa fa-bars"></i></a>
        <!--toggle button end-->

        <!--search start-->
        <!-- <form class="searchform" action="index.html" method="post">
            <input type="text" class="form-control" name="keyword" placeholder="Search here..." />
        </form> -->
        <!--search end-->

        <!--notification menu start -->
        <!--上面导航开始 -->
        <div class="menu-right">
            <ul class="notification-menu">
                <li>
                    <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                        <i class="fa fa-tasks"></i>
                        <span class="badge">8</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-head pull-right">
                        <h5 class="title">You have 8 pending task</h5>
                        <ul class="dropdown-list user-list">
                            <li class="new">
                                <a href="#">
                                    <div class="task-info">
                                        <div>Database update</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div style="width: 40%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" class="progress-bar progress-bar-warning">
                                            <span class="">40%</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="new">
                                <a href="#">
                                    <div class="task-info">
                                        <div>Dashboard done</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div style="width: 90%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="90" role="progressbar" class="progress-bar progress-bar-success">
                                            <span class="">90%</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="task-info">
                                        <div>Web Development</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div style="width: 66%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="66" role="progressbar" class="progress-bar progress-bar-info">
                                            <span class="">66% </span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="task-info">
                                        <div>Mobile App</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div style="width: 33%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="33" role="progressbar" class="progress-bar progress-bar-danger">
                                            <span class="">33% </span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="task-info">
                                        <div>Issues fixed</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div style="width: 80%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="80" role="progressbar" class="progress-bar">
                                            <span class="">80% </span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="new"><a href="">See All Pending Task</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge">5</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-head pull-right">
                        <h5 class="title">You have 5 Mails </h5>
                        <ul class="dropdown-list normal-list">
                            <li class="new">
                                <a href="">
                                    <span class="thumb"><img src="/static/admins/b/images/photos/user1.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">我在这<span class="badge badge-success">new</span></span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <span class="thumb"><img src="/static/admins/b/images/photos/user2.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Jonathan Smith</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <span class="thumb"><img src="/static/admins/b/images/photos/user3.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Jane Doe</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <span class="thumb"><img src="/static/admins/b/images/photos/user4.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Mark Henry</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <span class="thumb"><img src="/static/admins/b/images/photos/user5.png" alt="" /></span>
                                        <span class="desc">
                                          <span class="name">Jim Doe</span>
                                          <span class="msg">Lorem ipsum dolor sit amet...</span>
                                        </span>
                                </a>
                            </li>
                            <li class="new"><a href="">Read All Mails</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="badge">4</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-head pull-right">
                        <h5 class="title">Notifications</h5>
                        <ul class="dropdown-list normal-list">
                            <li class="new">
                                <a href="">
                                    <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                    <span class="name">Server #1 overloaded.  </span>
                                    <em class="small">34 mins</em>
                                </a>
                            </li>
                            <li class="new">
                                <a href="">
                                    <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                    <span class="name">Server #3 overloaded.  </span>
                                    <em class="small">1 hrs</em>
                                </a>
                            </li>
                            <li class="new">
                                <a href="">
                                    <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                    <span class="name">Server #5 overloaded.  </span>
                                    <em class="small">4 hrs</em>
                                </a>
                            </li>
                            <li class="new">
                                <a href="">
                                    <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                    <span class="name">Server #31 overloaded.  </span>
                                    <em class="small">4 hrs</em>
                                </a>
                            </li>
                            <li class="new"><a href="">See All Notifications</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <img src="/static/admins/b/images/photos/user-avatar.png" alt="" />
                       {{session('adminuser')}}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                        <li><a href="#"><i class="fa fa-user"></i>个人中心</a></li>
                        <li><a href="#"><i class="fa fa-cog"></i>设置</a></li>
                        <li><a href="/adminoutlogin"><i class="fa fa-sign-out"></i>退出登录</a></li>
                    </ul>
                </li>

            </ul>
        </div>
        <!--上面导航结束-->
        <!--notification menu end -->

        </div>
        <!-- header section end-->

        <!-- page heading start-->
        <div class="page-heading">
         
        </div>
        <!-- page heading end-->
        <!--body wrapper start-->
        <div class="wrapper">
                @if(session('success'))
                <div class="alert alert-success alert-block fade in">
                    <button type="button" class="close close-sm" data-dismiss="alert">
                        <i class="fa fa-times"></i>
                    </button>
                    <h4>
                        <i class="icon-ok-sign"></i>
                        Success!
                    </h4>
                    <p>{{session('success')}}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-warning fade in">
                    <button type="button" class="close close-sm" data-dismiss="alert">
                        <i class="fa fa-times"></i>
                    </button>
                    <strong>{{session('error')}}</strong>                    
                </div>
                @endif
                @section("content")
                @show           
        </div>
        <!--body wrapper end-->

        <!--footer section start-->
        <footer class="sticky-footer">
            2018 &copy; AdminEx by <a href="http://www.mycodes.net/" target="_blank">管理之家</a>
        </footer>
        <!--footer section end-->


    </div>
    <!-- main content end-->
</section>
<!-- Placed js at the end of the document so the pages load faster -->
<script src="/static/admins/b/js/jquery-1.10.2.min.js"></script>
<script src="/static/admins/b/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="/static/admins/b/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/static/admins/b/js/bootstrap.min.js"></script>
<script src="/static/admins/b/js/modernizr.min.js"></script>
<script src="/static/admins/b/js/jquery.nicescroll.js"></script>

<!--easy pie chart-->
<script src="/static/admins/b/js/easypiechart/jquery.easypiechart.js"></script>
<script src="/static/admins/b/js/easypiechart/easypiechart-init.js"></script>

<!--Sparkline Chart-->
<script src="/static/admins/b/js/sparkline/jquery.sparkline.js"></script>
<script src="/static/admins/b/js/sparkline/sparkline-init.js"></script>

<!--icheck -->
<script src="/static/admins/b/js/iCheck/jquery.icheck.js"></script>
<script src="/static/admins/b/js/icheck-init.js"></script>

<!-- jQuery Flot Chart-->
<script src="/static/admins/b/js/flot-chart/jquery.flot.js"></script>
<script src="/static/admins/b/js/flot-chart/jquery.flot.tooltip.js"></script>
<script src="/static/admins/b/js/flot-chart/jquery.flot.resize.js"></script>


<!--Morris Chart-->
<script src="/static/admins/b/js/morris-chart/morris.js"></script>
<script src="/static/admins/b/js/morris-chart/raphael-min.js"></script>

<!--Calendar-->
<script src="/static/admins/b/js/calendar/clndr.js"></script>
<script src="/static/admins/b/js/calendar/evnt.calendar.init.js"></script>
<script src="/static/admins/b/js/calendar/moment-2.2.1.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/underscore./static/admins/b/js/1.5.2/underscore-min.js"></script>

<!--common scripts for all pages-->
<script src="/static/admins/b/js/scripts.js"></script>

<!--Dashboard Charts-->
<script src="/static/admins/b/js/dashboard-chart-init.js"></script>
</body>
</html>
