<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{block name="title"}标题{/block}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="/plugin/lib/i/yixue-tt-logo.png">
    <script type="text/javascript" src="__PUBLIC__/static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML"></script>
    <!--  <link rel="icon" type="image/png" href="assets/i/favicon.png">
      <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">
      <meta name="apple-mobile-web-app-title" content="Amaze UI" />-->
    <link rel="stylesheet" href="/static/lib/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/static/lib/css/admin.css">
    {block name="css"}{/block}
    <script src="/static/lib/js/jquery.min.js"></script>
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器， 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
    以获得更好的体验！</p>
<![endif]-->

{block name="menu"}
<header class="am-topbar am-topbar-inverse admin-header">
    <div class="am-topbar-brand">
        <strong>乂学教育</strong>
        <small>后台管理系统</small>
    </div>

    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only"
            data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span
            class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

        <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
           <!-- <li><a href="javascript:;"><span class="am-icon-envelope-o"></span> 收件箱 <span
                        class="am-badge am-badge-warning">5</span></a></li>-->
            <li class="am-dropdown" data-am-dropdown>
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
                    <span class="am-icon-users"></span> {$teacher} <!--<span class="am-icon-caret-down"></span>-->
                </a>
                <ul class="am-dropdown-content" style="display: none;">
                  <!--  <li><a href="#"><span class="am-icon-user"></span> 资料</a></li>
                    <li><a href="#"><span class="am-icon-cog"></span> 设置</a></li>-->
                    <li><a href="{:url('Login/logout')}"><span class="am-icon-power-off"></span> 退出</a></li>
                </ul>
            </li>
            <li class="am-hide-sm-only"><a href="javascript:;" id="admin-fullscreen"><span
                        class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>
        </ul>
    </div>
</header>
{/block}
<div class="am-cf admin-main">
    {block name="left"}
    <!-- sidebar start -->
    <div class="admin-sidebar am-offcanvas" id="admin-offcanvas">
        <div class="am-offcanvas-bar admin-offcanvas-bar">
            <ul class="am-list admin-sidebar-list">
                <li><a href="/teacher"><span class="am-icon-home"></span> 首页</a></li>
               <!-- <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-nav'}"><span class="am-icon-file"></span>
                        用户答题情况 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav">
                        <li><a href="{:url('examDetail','module_type=1')}" class="am-cf"><span class="am-icon-check"></span> 先行测试<span
                                    class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>
                        <li><a href="{:url('examDetail','module_type=2')}"><span class="am-icon-puzzle-piece"></span> 边学边练</a></li>
                        <li><a href="{:url('examDetail','module_type=3')}"><span class="am-icon-th"></span> 综合测试</a></li>

                        <li><a href="{:url('allReport')}"><span class="am-icon-line-chart"></span> 所有测试</a></li>


                    </ul>
                </li>-->

                <li class="admin-parent" style="display:none;">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-nav'}"><span class="am-icon-file"></span>
                        错题管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav">
                        <li><a href="{:url('ErrorManagement/classes')}" class="am-cf"><span class="am-icon-check"></span> 班级错题<span
                                    class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>
                        <li><a href="{:url('ErrorManagement/person')}"><span class="am-icon-puzzle-piece"></span> 个人错题</a></li>
                    </ul>
                </li>

                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-nav'}"><span class="am-icon-file"></span>
                        错题管理(新) <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav">
                        <li><a href="{:url('ErrorQuestion/classes')}" class="am-cf"><span class="am-icon-check"></span> 班级错题<span
                                    class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>
                    </ul>
                </li>
                <li class="admin-parent">
                    <a class="am-cf" data-am-collapse="{target: '#collapse-nav'}"><span class="am-icon-file"></span>
                        学生管理 <span class="am-icon-angle-right am-fr am-margin-right"></span></a>
                    <ul class="am-list am-collapse admin-sidebar-sub am-in" id="collapse-nav">
                        <li><a href="{:url('Mystudents/index')}" class="am-cf"><span class="am-icon-check"></span> 我的学生<span
                                    class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>
                    <!--    <li><a href="{:url('ErrorManagement/person')}"><span class="am-icon-puzzle-piece"></span> 个人错题</a></li>
-->


                    </ul>
                </li>

            </ul>

        </div>
    </div>
    <!-- sidebar end -->
    {/block}
    <!-- content start -->
    <div class="admin-content">
        <div class="admin-content-body">
            {block name="main"}

            {/block}
        </div>

        <footer class="admin-content-footer">
            {block name="footer"}
            {/block}
            <hr>
            <p class="am-padding-center" style="text-align: center">Copyright ©2015 上海乂学教育科技有限公司版权所有 </p>
        </footer>

    </div>
    <!-- content end -->

</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu"
   data-am-offcanvas="{target: '#admin-offcanvas'}"></a>


<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/static/lib/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->

<!--<![endif]-->
<script src="/static/lib/js/amazeui.min.js"></script>

<script src="/static/lib/js/app.js"></script>
{block name="js"}{/block}
</body>
</html>
