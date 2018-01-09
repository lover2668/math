<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>暑期课数学</title>
    <meta name="keywords" content="上海乂学教育科技有限公司-暑期课数学"/>
    <meta name="description" content="上海乂学教育科技有限公司-暑期课程系列（数学）"/>
    <meta name="renderer" content="webkit">
    <meta name="force-rendering" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="__PUBLIC__/classba/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="__PUBLIC__/classba/assets/video/video-js.css">
    <link rel="stylesheet" href="__PUBLIC__/classba/app/common/css/x_video.css">
    <link href="__PUBLIC__/classba/app/math/css/math.css" rel="stylesheet">
</head>
<body>
<div class="xx-header">
    <div class="xx-navbar">
        <div class="xx-navbar-left xx-navbar-logo">
        </div>
        <div class="xx-navbar-left xx-nav">如何使用公式编辑器</div>
        <!--<ul class="xx-navbar-right xx-navbar-menu">-->
            <!--<li class="xx-navbar-left xx-report">-->
                <!--<i class="xx-icon">&#xe656;</i>&nbsp;我的报告-->
            <!--</li>-->
            <!--<div class="hover-li">点击此处可查看所有本课程学习报告<i class="yxiconfont">&#xe63c;</i></div>-->
            <!--<li class="xx-navbar-left xx-account"><i class="xx-icon">&#xe635;</i>&nbsp;吴晓磊</li>-->
        <!--</ul>-->
    </div>
</div>
<div class="xx-container" style="position: relative">
    <div class="alert xx-alert-default animated" role="alert">
        开始学习前，让我们先来看看知识点视频吧
    </div>
    <div class="xx-video-container">
        <p style="padding: 24px 0 16px 40px; font-size:24px;color: #333333;">如何使用公式编辑器</p>
        <video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="868" height="488" poster="" data-setup="{}">
            <source src="http://media1.classba.cn/maths4.0.mp4" type="video/mp4">
            <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
        </video>
    </div>
    <div class="xx-continue" name="xx-continue">
        <div class="xx-continue-button" style="letter-spacing: 0;">
            操作练习&nbsp;<i class="xx-icon">&#xe663;</i>
        </div>
    </div>
</div>
<div class="xx-footer"></div>
<script src="__PUBLIC__/classba/assets/jquery/jquery.js"></script>
<script src="__PUBLIC__/classba/assets/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="__PUBLIC__/classba/assets/video/video.js"></script>
<!--<script type="text/javascript" src="../../static/app/common/js/x_videov1.1.js"></script>-->
<script>
    $(".xx-alert-default").addClass("fadeInDown");
    $(".xx-alert-default").on("click",function(){
        $(this).removeClass("fadeInDown").addClass("fadeOutUp");
    });
    $(".xx-continue-button").on("click",function(){
        window.open("http://"+window.location.host+"/index.php/summer/editorstudy/studyeditor","_self")
    })
</script>
</body>
</html>