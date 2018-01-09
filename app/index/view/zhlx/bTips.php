<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>乂学-数学</title>
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">
    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="{:loadResource('plugin/lib/i/yixue-tt-logo.png')}">
    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="乂学教育-数学产品"/>
    <meta name="msapplication-TileColor" content="#0e90d2">
    <link rel="stylesheet" href="{:loadResource('plugin/lib/css/amazeui.min.css')}">
    <link rel="stylesheet" href="{:loadResource('plugin/lib/uploadfile/css/uploadfile.css')}">
    <!-- 选项按钮样式 -->
    <link rel="stylesheet" href="{:loadResource('plugin/math/css/bx_style.css')}">
    <style>
        .xx-subject-module li.learned>a{
            color: #9cdf97;
        }
        .xx-subject-module li.learned>a:hover{
            color: #fff;
        }
        .worse{
            width:300px;
            height:300px;
            background: #EEEEEE;
            border-radius: 100%;
            margin:154px auto 0;
            position: relative;
        }
        .worse>.worse-img,.worse>.greate-img{
            margin:auto;
            position: relative;


        }
        .worse>.worse-img{
            background: url("{:loadResource('plugin/math/img/worse.png')}");
            top:58px;
            width:194px;
            height:120px;
        }
        .worse>.greate-img{
            background: url("{:loadResource('plugin/math/img/great.png')}");
            width:102px;
            height:160px;
            top: 20px;
        }
        .worse>.worse-test>.list>p{
            width:140px;
            height:48px;
            font-family: PingFang-SC-Regular;
            font-size: 14px;
            color: #333333;
            line-height: 24px;
            margin:auto;
        }
        .worse>.worse-test>.list{
            width:156px;
            background: #EEEEEE;
            position: absolute;
            left: 50%;
            margin-left: -78px;
            z-index:10;
            text-align: center;
        }
        .worse>.worse-test{
            width:220px;
            position: relative;
        }
        .worse>.worse-content{
            margin:74px auto 0;
        }
        .worse>.great-test{
            margin:34px auto 0;
        }
        .worse>.worse-test>span{
            width:220px;
            display: block;
            border: 1px solid #CCCCCC;
            position: absolute;
            top:25px;
        }
        .worse-jump{
            font-family: PingFang-SC-Regular;
            font-size: 14px;
            color: #26B987;
            letter-spacing: 0;
            line-height: 24px;
            margin-top: 16px;
        }
        .xx-subject-module>li:nth-child(2)>a{
            cursor: default;
        }
    </style>
</head>
<body>
<header class="xx-topbar-math">
    <div class="">
        <h1 class="am-topbar-brand xx-brand" style="margin-left: 40px;">
            <a href="<?php echo config('logo_url'); ?>" class="am-text-ir"></a>
        </h1>
        <div class="xx-logo-badge">
            <p class="xx-badge-title">课次</p>
            <p class="xx-badge-title-num">1</p>
        </div>
        <div class="xx-subject"></div>
        <ul class="xx-subject-module">
            <li class="xx-subject-module-unit learned"><span>1</span>&nbsp;&nbsp;&nbsp;先行测试</li>
<!--            <li class="xx-subject-module-unit learned"><a target="_blank" href="{url link='index/Index/preReport' vars='topicId=$topicId' }?show=0">2&nbsp;&nbsp;<span>测试报告</span></a></li>-->
            <li class="xx-subject-module-unit learned"><a  href="javascript:;">2&nbsp;&nbsp;<span>测试报告</span></a></li>
            
            <li class="xx-subject-module-unit learned"><span>3</span>&nbsp;&nbsp;&nbsp;高效学习
            </li>
            <li class="xx-subject-module-unit learned"><span>4</span>&nbsp;&nbsp;&nbsp;学习检测</li>
            <li class="xx-subject-module-unit active"><span>5</span>&nbsp;&nbsp;&nbsp;学情报告</li>

            
        </ul>

    </div>
    <div class="">
        <ul class="am-topbar-right" id="collapse-head">

            <li class="am-dropdown" data-am-dropdown style="height:80px;">
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;" style="line-height: 80px;color:#fff;">
                    <span class="am-icon-user"></span>  <?php echo session("real_name")?session("real_name"):session("username") ?><span class="am-icon-caret-down"></span>
                </a>
                <ul class="am-dropdown-content">
                    <li id="logout">&nbsp;&nbsp;&nbsp;<i class="am-icon-power-off"></i><a href="#">退出系统</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div style="clear: both"></div>
</header>
<div class="content">
     {if condition="$is_passed neq 1"}
    <div class="worse">
        <div class="worse-img"></div>
        <div class="clear" style="clear: both"></div>

        <div class="worse-test worse-content">
            <div class="list">
                <p>
                    知识点掌握率未达标
                    无法进入竞赛拓展！
                </p>
            </div>

            <span></span>
        </div>
    </div>
    <p class="worse-jump" style="text-align: center"><span>3</span>秒后返回至报告页</p>
    <!--内容 end-->
    {else/}
    <div class="worse">
        <div class="greate-img"></div>
        <div class="clear" style="clear: both"></div>

        <div class="worse-test great-test">
            <div class="list">
                <p>
                    恭喜你完成专题学习,
                    欢迎进入竞赛拓展！
                </p>
            </div>

            <span></span>
        </div>
    </div>
    <p class="worse-jump" style="text-align: center"><span>3</span>秒后进入竞赛拓展</p>
    {/if}
</div>

<input type="hidden" name="topicId" value="{$topicId}">
<input type="hidden" name="topic_name" value="{$topic_name}">
<input type="hidden" name="is_passed" value="{$is_passed}">
<script src="{:loadResource('plugin/lib/js/jquery.min.js')}"></script>
<script src="{:loadResource('plugin/lib/js/amazeui.ie8polyfill.min.js')}"></script>
<script src="{:loadResource('plugin/lib/js/amazeui.min.js')}"></script>
<script src="{:loadResource('static/lib/js/my.ui.js')}"></script>
<script src="{:loadResource('static/math/js/config.js')}"></script>
<script src="{:loadResource('static/math/js/class.Logout.js')}"></script>

<!-- 选项按钮JS -->
<script>
    $(document).ready(function () {
        var topic_name=$("input[name=topic_name]").val();
        if(topic_name.length>15){
            var test=topic_name.substring(0,14)+"...";
            $(".xx-subject").html(test);
        }else{
            $(".xx-subject").html(topic_name);
        }
        var topicId=$("input[name=topicId]").val(),is_passed=$("input[name=is_passed]").val();
        var i=0;
        var num=3;
        var timer=setInterval(function(){
            i++;
            if((num-i)==0){
                clearInterval(timer);
                if(is_passed==0){
                    window.open(HOST+"index/bxbl/studyReport/topicId/"+topicId,"_self");
                }else{
                    window.open(HOST+"index/zhlx/index/topicId/"+topicId,"_self");
                }
            }
            $(".worse-jump").find("span").html((num-i));
        },1000)
    });

</script>
<script src="{:loadResource('classba/assets/tools/xa.js')}"></script>
</body>
</html>