<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>乂学-数学</title>
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
    <link rel="stylesheet" href="{:loadResource('static/lib/css/amazeui.min.css')}">
<!--    <link rel="stylesheet" href="__PUBLIC__/plugin/lib/labelauty/css/jquery-labelauty.css">-->
    <!-- 选项按钮样式 -->
    <link rel="stylesheet" href="{:loadResource('static/math/css/xc_style.css')}">
    <link rel="stylesheet" href="{:loadResource('static/math/css/style.css')}">
<style>
    .am-container{
        width:95%;
        max-width: 1900px;
    }
    .xx-breadcrumb li{
        width: 140px;
        height: 40px;
        border: 1px dashed #fff;
        border-radius: 40px;
        line-height: 40px;
        /*margin-left: 40px;*/
        margin-top: 10px;
    }
    .xx-breadcrumb li a{
        cursor: default;
    }
    #collapse-head .am-dropdown{
        /*bottom:30px;*/
    }
    .xx-topbar-math a{
        margin-top: 0;
    }
    [class*=am-u-]+[class*=am-u-]:last-child{
        float: left;
    }
    @media only screen and (min-width: 641px){
        [class*=am-u-] {
             padding-left: 1.5rem;
             padding-right: 1.5rem;
        }
    }
    [class*=am-u-] {
        padding-left: 0;
        padding-right: 0;
    }
</style>
</head>
<body>
<header class="am-topbar xx-topbar-math">
    <div class="am-container">
        <div class="am-g xx-question-tab">
            <div class="am-u-lg-8">
                <h1 class="am-topbar-brand xx-brand">
                    <a href="<?php echo config('logo_url'); ?>" class="am-text-ir"></a>
                </h1>
                <ol class="am-breadcrumb xx-breadcrumb">
                    <li class="active"><a href="javascript:;">专题选择</a></li>
                </ol>
            </div>
            <div class="am-u-lg-4">
                <ul class="am-topbar-right" id="collapse-head">
                    <li class="am-dropdown" data-am-dropdown style="height:80px;">
                        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;" style="line-height: 80px;color:#fff;">
                            <span class="am-icon-user"></span> <?php echo session("real_name")?session("real_name"):session("username") ?><span class="am-icon-caret-down"></span>
                        </a>
                        <ul class="am-dropdown-content">
                            <li id="logout">&nbsp;&nbsp;&nbsp;<i class="am-icon-power-off"></i><a href="#">退出系统</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</header>
<div class="am-g am-container">
    <div class="am-u-lg-12 am-u-lg-centered">
        {volist name="result" id="item"}
        <div class="am-u-lg-3 xx-subject-wrap">
            <div class="am-u-lg-12 xx-subject">
                <a href="{:url('choose',array('topicId'=>$item.id))}" target="_self"><img src="{$item.pic_url}"></a>
                <div class="cover-info">
                    <a href="{:url('choose',array('topicId'=>$item.id))}" target="_self"><h4>{$item.topic_name}</h4></a>
                    <!--                        <small>说明</small>-->
                </div>
                <!--                    <div class="cover-fields">-->
                <!--                        <i class="fa fa-list-ul"></i> &nbsp;-->
                <!--                        语文-->
                <!--                    </div>-->
                <!--                    <div class="cover-stat">-->
                <!--                        <i class="fa fa-eye"></i><span class="f10"> &nbsp;7809</span>-->
                <!--                        <i class="fa fa-heart"></i><span class="f10"> &nbsp;34</span>-->
                <!--                        <div class="cover-yh">-->
                <!--                            <a href="mem55395" data-container="body" data-toggle="popover" data-placement="top" data-content="temp " data-original-title="" title="">-->
                <!--                                <i class="fa fa-user-secret"></i>-->
                <!--                            </a>-->
                <!--                        </div>-->
                <!--                    </div>-->
            </div>
        </div>
        {/volist}

    </div>
</div>
<script src="{:loadResource('static/lib/js/jquery.min.js')}"></script>
<script src="{:loadResource('static/lib/js/amazeui.min.js')}"></script>
<script src="{:loadResource('static/app/math_summer_l2/js/config.js')}"></script>
<script src="{:loadResource('static/app/math_summer_l2/js/class.Logout.js')}"></script>
<script>
    $(function(){
        var logout   = new Logout( '#collapse-head');
    });
</script>
</body>