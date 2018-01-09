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
    <link rel="stylesheet" href="{:loadResource('plugin/lib/labelauty/css/labelauty.css')}">
    <link rel="stylesheet" href="{:loadResource('plugin/lib/flowplayer-6.0.5/skin/functional.css')}">
    <link rel="stylesheet" href="{:loadResource('plugin/lib/uploadfile/css/uploadfile.css')}">
    <link href="{:loadResource('classba/app/common/yx_font/iconfont.css')}" rel="stylesheet">
    <!-- 选项按钮样式 -->
    <link rel="stylesheet" href="{:loadResource('plugin/math/css/bx_style.css')}">
    <style>
        .xx-analyse-step-group #xx-step-right{
            font-family: "微软雅黑";
            font-size: 12px;
            font-weight: bold;
            color: #26b987;
            letter-spacing: 0px;
            text-align: left;
        }
        .radiobox-content div,.question-sheet .MathJax_Display{
            display: inline !important;
        }
        .rdobox, .chkbox{
            height: auto !important;
        }
        .xx-analyse-step p div{
            display: inline !important;
        }
        .xx-subject-module li.learned>a{
            color: #9cdf97;
        }
        .xx-subject-module li.learned>a:hover{
            color: #fff;
        }
        .xx-options{
            position: absolute;
            top:-40px;
            left:15px;
            font-size: 16px;
        }
        .xx-analyse-title>p{
            display: inline;
        }
        .radiobox-content .MJXc-display{
            display: inline;
        }
        .question-sheet .MJXc-display{
            display: inline;
        }
        .xx-analyse-step .MJXc-display{
            display: inline;
        }
        .xx-question-analyse .xx-analyse-step-group{
            width:80%;
        }
        .edui-default .edui-for-kityformula .edui-dialog-content{
            height:330px !important;
        }
        .am-modal.am-modal-no-btn .xx-k-carousel-content .xx-k-carousel-title p{
            line-height: 50px;
        }
        .xx-container.xx-check-container{
            margin:20px 60px;
        }
        .am-topbar-right>li{
            float: left;

        }
        .am-topbar-right>li:first-child{
            margin-right: 25px;
        }
        .am-topbar-right>li:first-child>a{
            line-height: 80px;
            color: #fff;
        }
    </style>
</head>
<body>
<header class="xx-topbar-math">
    <div class="am-u-lg-9">
        <h1 class="am-topbar-brand xx-brand" style="margin-left: 40px;">
            <a href="<?php echo config('logo_url'); ?>" class="am-text-ir"></a>
        </h1>
        <div class="xx-logo-badge">
            <p class="xx-badge-title">课次</p>
            <p class="xx-badge-title-num">1</p>
        </div>
        <div class="xx-subject">{$topic_name}</div>
        <ul class="xx-subject-module">
            <li class="xx-subject-module-unit learned"><span>1</span>&nbsp;&nbsp;&nbsp;先行测试</li>
            <li class="xx-subject-module-unit learned"><a href="javascript:;">2&nbsp;&nbsp;<span>测试报告</span></a></li>
            <li class="xx-subject-module-unit learned"><span>3</span>&nbsp;&nbsp;&nbsp;高效学习</li>
            <li class="xx-subject-module-unit active" style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>4</span>&nbsp;&nbsp;&nbsp;学习检测</li>
<!--            <li class="xx-subject-module-unit unlearned" style="border-top-left-radius: 16px;border-bottom-left-radius: 16px;border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>5</span>&nbsp;&nbsp;&nbsp;学情报告</li>-->
        </ul>
    </div>
    <div class="am-u-lg-3">
        <ul class="am-topbar-right" id="collapse-head">
            <li><a href='{url link="report/reportCenter"  vars='topicId=$topicId' suffix='true' domain='true'}' target='_blank'><i class="icon yxiconfont yx-logo">&#xe656;</i>&nbsp;我的报告</a></li>
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
<!--内容 start-->
<div class="am-g doc-am-g xx-contain">
    <div class="fc-content">
        <div class="xx-time-charts" id="xx-time-charts" style="">

        </div>
    </div>

    <div class="xx-container xx-check-container">
<!--        <div class="xx-options">正在学习： <span></span></div>-->

        <div class="xx-question-num"  name="xx-question-type">1</div>
        <div class="xx-question-sheet">
        </div>
        <div class="xx-question-analyse" style="display: none">
            <div class="xx-question-analyse-close"></div>
            <div class="xx-question-analyse-badge">分步解析</div>
            <ul class="xx-analyse-step-group">
                <li  class="xx-analyse-step">
                    <p class="xx-step-name">步骤1/2</p>
                    <p class="xx-step-title">-(3*2)[x^(2*6)]</p>
                </li>
            </ul>
            <div class="xx-analyse-next-step">下一步</div>
            <!--<div class="xx-analyse-next-step">继续答题</div>-->
            <!--<div class="xx-analyse-next-step">查看答案</div>-->
        </div>
    </div>
    <button style="display: none"
            type="button"
            class="am-btn am-btn-primary" id="xx-k-video-button"
            data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 0,width:810,height: 380,}">
    </button>
    <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
        <div class="am-modal-dialog xx-k-carousel-content xx-k-carousel-content-sc" style="margin-top: 120px;">
            <div class="am-modal-hd" style="padding-bottom: 0px" id="xx-k-carousel-select">
                <div class="xx-xq-carousel-module"></div>
                <div class="xx-k-carousel-title" style="top:-164px;text-align: left">
                    <p style="text-align: center;">巩固知识点，检测学习后的掌握情况</p>
<!--                    <p style="text-align: left;padding-left: 100px">目标：解决未掌握知识点</p>-->
                </div>
                <a href="javascript: void(0)" class="xx-k-carousel-content-close" data-am-modal-close></a>
            </div>
            <div class="am-modal-bd" id="xx-video-select-bd" style="margin-top: -120px;">
                <div class="xx-slider-content" style="width: 700px;height: 200px">
                    <div class="xx-xq-title" style="font-family: '微软雅黑';margin-top:25px;font-size: 20px;color: #926944;line-height: 16px;text-align: center;">加油，接下来我们一起攻克这些知识点。</div>
                    <div class="drama-poster-sc">
                        <ul>
                            <li class="show-poster-3" >
                                <span class="xx-k-video-select">基础视频1</span>
                            </li>
                            <li class="show-poster-3" >
                                <span class="xx-k-video-select">基础视频2</span>
                            </li>
                            <li class="show-poster-3" >
                                <span class="xx-k-video-select">基础视频3</span>
                            </li>
                        </ul>
                    </div>
<!--                    <ul class="drama-slide">-->
<!--                        <li class="prev"><a href="javascript:;" title="上翻">上翻</a></li>-->
<!--                        <li class="next"><a href="javascript:;" title="下翻">下翻</a></li>-->
<!--                    </ul>-->
                </div>
            </div>
            <button type="button" class="xx-slider-button-next">下一步</button>
        </div>
    </div>
    <div style="clear: both"></div>
    <input type="hidden" name="topicId" value="{$topicId}">
</div>
<div class="findwrongs">
    <div class="xx-feedback" data-am-modal="{target: '#your-modal', closeViaDimmer: 0, width: 820, height: 478}">报错</div>
    <div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">
                <span></span>
                <a href="javascript: void(0)" data-am-modal-close></a>
            </div>
            <div class="am-modal-bd">
                <div id="form1"></div>
<!--                <form action="{url link='index/Index/submitCorrection'}" method="post" enctype="multipart/form-data" class="am-g wrong-type">-->
<!--                    <div class="type-check">-->
<!---->
<!--                    </div>-->
<!--                </form>-->
            </div>
            <div id="wrong-time">
                <button class="wrong-btn" type="submit" id="sure">确认</button>
                <a href="javascript:;" class="wrong-btn" id="cancel">取消</a>
            </div>
        </div>
    </div>
</div>
<div class="box bottom"></div>
<div class="gif" style="display: none">
    <div class="gif-img">

    </div>

</div>
<input type="hidden" value="{:loadResource('plugin/math/img/fw1.gif')}" name="wrong1">
<input type="hidden" value="{:loadResource('plugin/math/img/sw2.gif')}" name="wrong2">
<input type="hidden" value="{:loadResource('plugin/math/img/right-4.gif')}" name="right">
<!--<div class="xx-continue">-->
<!--    <div class="xx-continue-inner">提交</div>-->
<!--</div>-->
<!--内容 end-->
<!--<script type="text/javascript"-->
<!--        src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">-->
<!--</script>-->
<!--<script type="text/javascript" src="__PUBLIC__/plugin/lib/math_editor/mathjax/MathJax.js?config=TeX-AMS-MML_HTMLorMML">-->
<!--</script>-->
<input type="hidden" name="topic_name" value="{$topic_name}">
<script type="text/javascript" src="{:loadResource('static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML',false)}">
</script>
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
    tex2jax: {
      inlineMath: [ ['$','$'], ["\\(","\\)"] ],
      processEscapes: true
    }
  });
</script>
<script src="{:loadResource('plugin/lib/js/jquery.min.js')}"></script>
<script src="{:loadResource('plugin/lib/js/amazeui.ie8polyfill.min.js')}"></script>
<script src="{:loadResource('plugin/lib/js/amazeui.min.js')}"></script>
<script src="{:loadResource('plugin/lib/layer/layer.js')}"></script>
<script src="{:loadResource('plugin/lib/videojs/video.js')}"></script>
<script src="{:loadResource('plugin/lib/labelauty/js/labelauty.js')}"></script>
<script src="{:loadResource('static/lib/js/my.ui.js')}"></script>
<script src="{:loadResource('static/math/js/config.js')}"></script>
<script src="{:loadResource('static/math/js/class.Logout.js')}"></script>
<script src="{:loadResource('static/lib/js/jquery-form.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('static/lib/ueditor1_4_3_3-src/ueditor.config.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('static/lib/ueditor1_4_3_3-src/_examples/editor_api.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('static/lib/ueditor1_4_3_3-src/kityformula-plugin/addKityFormulaDialog.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('static/lib/ueditor1_4_3_3-src/kityformula-plugin/getKfContent.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('static/lib/ueditor1_4_3_3-src/kityformula-plugin/defaultFilterFix.js')}"></script>
<script>
    var $_CONFIG = {

    };
    $_CONFIG.ui = "[name='xx-question-type']";
</script>
<script src="{:loadResource('plugin/math/js/class.ScIndex.js')}"></script>
<script src="{:loadResource('plugin/lib/uploadfile/js/jquery.uploadfile.js')}"></script>
<script type="text/pn" id="myEditor">
        <p>这里我可以写一些lai输入提示</p>
</script>
<!-- 选项按钮JS -->
<script>
    $(document).ready(function(){
        var school= '<?php echo config("school");?>';
        if(school=="171xue"){
            $("body").css({
                "-moz-user-select":"none",
                "-webkit-user-select":"none",
                "-ms-user-select":"none",
                "-khtml-user-select":"none",
                "-o-user-select":"none",
                "user-select":"none"
            });
        }
        var logout   = new Logout( '#collapse-head');
        var gxIndex = new ScIndex(".xx-contain");
        $("#myEditor").hide();
        $(".xx-slider-button-next").on("click", function () {
            $("#doc-modal-1").modal('close');
        });
        $("#xx-video-close-3").on("click", function () {
            $("#doc-modal-3").modal('close');
        });
        $(".xx-bx-analyse-video").on("click", function () {
            $("#doc-modal-2").modal('open');
        });
        var topicId=$("input[name=topicId]").val();
        $(".xx-subject-module li.learned").find("a").click(function(){
            window.open(HOST+"index/index/preReport/topicId/"+topicId,"_blank");
        });
        var topic_name=$("input[name=topic_name]").val();
        if(topic_name.length>15){
            var test=topic_name.substring(0,14)+"...";
            $(".xx-subject").html(test);
        }else{
            $(".xx-subject").html(topic_name);
        }
    });
    var screenH=document.documentElement.clientHeight ;
    function showMathEdit (dom){
        var bh = dom.getAttribute("data-num");
        localStorage.setItem("data-num",bh);
        $("#edui31_body").trigger("click").attr("data-num",bh);
        var a=$(".question-sheet").offset().top;
        var offH=$(".question-sheet").height();
        var top=offH+178;
        var tops=a+offH+10;
        var b=screenH-400;
        if((screenH-tops)>=400){
            $(".edui-state-centered").css({
                "top":tops
            });
        }
        else{
            $(".edui-state-centered").css({
                "top":b
            });
        }
    }
    var topicId = $("input[name=topicId]").val();
    $_CONFIG.topic_id = topicId;;
    $_CONFIG.uid = "<?php echo session("user_id") ?>"
    $_CONFIG.user_name = "<?php echo session("username") ?>"
    $_CONFIG.section_id="<?php echo session("section_id") ?>"
    $_CONFIG.course_id="<?php echo session("course_id") ?>"
    console.log($_CONFIG);
</script>
<script src="{:loadResource('classba/app/common/js/x_analyse.js')}"></script>
<script src="{:loadResource('classba/assets/tools/xa.js')}"></script>
</body>
</html>