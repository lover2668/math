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
    .wrapper .book-text{
        width:250px;
    }
    .wrapper .target{
        position: absolute;
        right:0;
        top:165px;
    }
    .book-btn{
        position: absolute;
        right:0;
        top:170px;
    }
</style>
</head>
<body>
<div class="wrapper">
    <div class="am-g">

        <!--<div class="xx-book">-->

        <div class="book-img">
            <div class="increase">

            </div>
            <div class="xx-logo-badge">
                <p class="xx-badge-title">课次</p>
                <p class="xx-badge-title-num"></p>
            </div>
            <div class="book-text">
                <p>
                    {$topicName}
                </p>
            </div>
            <div class="target">
<!--                <p><span>目标</span> 检测知识点掌握情况</p>-->
<!--                <p><span>目的</span> 找出未掌握知识点</p>-->
                <p>检测薄弱知识点和掌握程度</p>
            </div>
            <div class="book-btn">
                <a href="{:url('preIndex',array('topicId'=>$topicId))}" type="button" class="am-btn">开始测试</a>
            </div>
        </div>

        <!--</div>-->
    </div>
</div>
<script src="__PUBLIC__/static/lib/js/jquery.min.js"></script>
<script src="__PUBLIC__/static/lib/js/amazeui.min.js"></script>
<script src="__PUBLIC__/static/math/js/config.js"></script>
<script src="__PUBLIC__/static/math/js/class.Logout.js"></script>
<script>
    $(function(){
        var logout   = new Logout( '#collapse-head');
    });
</script>
<script src="__PUBLIC__/classba/assets/tools/xa.js"></script>
</body>