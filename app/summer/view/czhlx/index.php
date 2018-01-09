<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>乂学-数学</title>
    <meta name="renderer" content="webkit">
    <!-- No Baidu Siteapp-->
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="__PUBLIC__/plugin/lib/i/yixue-tt-logo.png">
    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="乂学教育-数学产品"/>
    <meta name="msapplication-TileColor" content="#0e90d2">
    <link rel="stylesheet" href="__PUBLIC__/static/lib/css/amazeui.min.css">
<!--    <link rel="stylesheet" href="__PUBLIC__/plugin/lib/labelauty/css/jquery-labelauty.css">-->
    <!-- 选项按钮样式 -->
    <link rel="stylesheet" href="__PUBLIC__/static/math/css/xc_style.css">
    <link rel="stylesheet" href="__PUBLIC__/static/math/css/style.css">

<style>
    .wrapper .target{
        margin-top:-15px;
    }
</style>
</head>
<body>
<input type="hidden" id="module_type"    value="<?php echo config('zonghe_module_type'); ?>" />
<div class="wrapper">
    <div class="am-g">

        <!--<div class="xx-book">-->

        <div class="book-img">
            <div class="increase-1">

            </div>
            <div class="xx-logo-badge">
                <p class="xx-badge-title">课次</p>
                <p class="xx-badge-title-num">1</p>
            </div>
            <div class="book-text">

            </div>
            <div class="target">
                <p>想成为学霸吗？<br>这里能实现你的梦想！</p>
<!--                <p><span>目标</span> 解决难题，成为学霸</p>-->
<!--                <p><span>目的</span> 挑战难题，提升自我</p>-->
            </div>
            <div class="book-btn">
                <a href="{url link='zhlxQuestion' vars='topicId=$topicId'}" type="button" class="am-btn">开始测试</a>
            </div>
        </div>

        <!--</div>-->
    </div>
</div>

<script src="__PUBLIC__/static/lib/js/jquery.min.js"></script>
<!--<script>
$.post("{url link='index/User/getUserNextModule' vars='topicId=$topicId'}",{topicId: {$topicId} },function(data){
    data=eval('('+data+')');
    if($('#module_type').val()!=data.module_type){
        window.location=data.url+'?topicId={$topicId}';
    }
});
</script>-->
<script src="__PUBLIC__/classba/assets/tools/xa.js"></script>
</body>