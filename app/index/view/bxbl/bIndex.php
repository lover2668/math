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
    <link rel="stylesheet" href="{:loadResource('plugin/lib/videojs/amazeui.videojs.css')}">
    <link rel="stylesheet" href="{:loadResource('plugin/lib/uploadfile/css/uploadfile.css')}">
    <link href="{:loadResource('classba/app/common/yx_font/iconfont.css')}" rel="stylesheet">
    <!-- 选项按钮样式 -->
    <link rel="stylesheet" href="{:loadResource('plugin/math/css/bx_style.css')}">
    <style>
        .vjs-amazeui.vjs-big-play-centered#example_video_2 .vjs-big-play-button{
            margin-top: -29%;
        }
        .xx-analyse-step-group #xx-step-right{
            font-family: "微软雅黑";
            font-size: 12px;
            font-weight: bold;
            color: #26b987;
            letter-spacing: 0px;
            text-align: left;
        }
        .vjs-amazeui .vjs-control-bar{
            height: 7em;
        }
        .vjs-amazeui .vjs-control{
            height:4em;
        }
        .vjs-amazeui .vjs-play-control,.vjs-amazeui .vjs-time-controls,.vjs-time-divider,.vjs-amazeui .vjs-duration,.vjs-live-control,.vjs-amazeui .vjs-mute-control,.vjs-amazeui .vjs-volume-control,.vjs-amazeui .vjs-fullscreen-control{
            top:2em;
            position: relative;
        }
        .radiobox-content div,li.xx-analyse-step>p>span div{
            display: inline !important;
        }
        .rdobox, .chkbox{
            height: auto !important;
        }
        .xx-analyse-step p div{
            display: inline !important;
        }
        .am-topbar-right>li{
            display: inline-block;
        }
        .am-topbar-right>li>a{
            color: #fff;
            font-size: 14px;
        }
        .xx-subject-module li.learned>a{
            color: #9cdf97;

        }
        .xx-subject-module li.learned>a:hover{
            color: #fff;
        }

        .xx-analyse-title>p{
            display: inline;
        }
        .xx-analyse-step .MathJax{
            display: block !important;
        }
        .radiobox-content .MJXc-display{
            display: inline;
        }
        .MJXc-display{
            display: inline !important;
            text-align: inherit;
            margin: 0;
        }
        .edui-default .edui-for-kityformula .edui-dialog-content{
            height:330px !important;
        }
        .find-answer>p.xx-step-name{
            font-size: 14px;
            color: #26b987;
            font-weight: bold;
        }
        .xx-question-analyse>ul>li img{
            max-width: 100%;
        }
        .xx-topbar-math{
            margin-bottom: 1.6rem;
        }
        .question-sheet p{
            max-width:100%;
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
        <div class="xx-subject"></div>
        <ul class="xx-subject-module">
            <li class="xx-subject-module-unit learned"><span>1</span>&nbsp;&nbsp;&nbsp;先行测试</li>
            <li class="xx-subject-module-unit learned cur"><a href="javascript:;">2&nbsp;&nbsp;<span>测试报告</span></a></li>
            <li class="xx-subject-module-unit active" style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>3</span>&nbsp;&nbsp;&nbsp;高效学习</li>
<!--            <li class="xx-subject-module-unit unlearned" style="border-top-left-radius: 16px;border-bottom-left-radius: 16px;"><span>4</span>&nbsp;&nbsp;&nbsp;学习检测</li>-->
<!--            <li class="xx-subject-module-unit unlearned" style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>5</span>&nbsp;&nbsp;&nbsp;学情报告</li>-->
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
        <div class="xx-options">正在学习： <span></span></div>
        <div class="xx-time-charts" id="xx-time-charts" style="">

        </div>
    </div>
    <div class="xx-container xx-study-container">
        <div class="xx-question-num" name="xx-question-type">
            <span></span>
        </div>
        <div class="xx-question-sheet">
        </div>
        <div class="xx-question-analyse" id="xx-question-analyse" style="display: none">
            <div class="xx-question-analyse-close"></div>
            <div class="xx-question-analyse-badge">分步解析</div>
            <ul class="xx-analyse-step-group">
                <li  class="xx-analyse-step">
                    <p class="xx-step-name">步骤1/2</p>
                    <p class="xx-step-title">-(3*2)[x^(2*6)]</p>
                </li>
            </ul>

            <div id="optionButton"></div>
<!--            <div class="xx-analyse-next-step" style="display: none" id="next_step">下一步</div>-->
<!--            <div class="xx-analyse-next-step" style="display: none" id="find_answer">查看答案</div>-->
<!--            <div class="xx-analyse-next-step" style="display: none" id="next_q">继续答题</div>-->
        </div>
    </div>
<!--    <div class="xx-koption"></div>-->
    <div class="xx-tab" id="xx_wrapper">
        <ul class="xx_side_nav">
<!--            <li><p>遇到困难了吗？</p><p>可以查看下列资料</p></li>-->
<!--            <li class="xx-bx-analyse-video js-modal-open" ><span></span>&nbsp;&nbsp;<a>知识点讲解视频</a></li>-->
<!--            <li class="xx-bx-analyse"><span></span>&nbsp;&nbsp;<a href="javascript:;">分步解析</a></li>-->
<!--            <li class="xx-bx-more">敬请期待更多内容！！</li>-->
        </ul>
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-3">
            <div class="am-modal-dialog xx-k-carousel-content">
                <div class="am-modal-hd">
                    <a href="javascript: void(0)" class="xx-k-carousel-content-close" data-am-modal-close id="xx-video-close-3"></a>
                </div>
                <div class="am-modal-bd">
                        <video id="example_video_1" class="video-js vjs-amazeui" controls preload="none" width="640"
                               height="384"
                               poster=""
                               data-setup="{}" style="margin: auto">
                            <source src="" type='video/mp4'/>
                            <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading
                                to a web browser that <a href="http://videojs.com/html5-video-support/"
                                                         target="_blank">supports HTML5 video</a></p>
                        </video>
                </div>
            </div>
        </div>
    </div>
    <button style="display: none"
            type="button"
            class="am-btn am-btn-primary js-modal-open" id="xx-k-video-button">
    </button>
    <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
        <div class="am-modal-dialog xx-k-carousel-content">
            <div class="am-modal-hd" style="padding-bottom: 0px" id="xx-k-carousel-select">
                <div class="xx-k-carousel-module"></div>
                <div class="xx-k-carousel-title">
                    <p style="line-height: 56px;">针对薄弱知识点进行强化学习</p>
<!--                    <p>根据你的完成情况</p>-->
<!--                    <p>智适应系统推荐你观看<span id="learn-video"></span></p>-->
                    <div class="xx-k-carousel-title-h2">
                        <div class="xx-k-carousel-title-h2bg"></div>
                        <div class="xx-k-carousel-title-h2bd" id="knowledge-point"><span id="dwmc" style="display: inline-block;padding: 0 30px;"></span></div>
                    </div>
                </div>
                <a href="javascript: void(0)" class="xx-k-carousel-content-close" data-am-modal-close></a>
            </div>
            <div class="am-modal-bd" id="xx-video-select-bd" style="margin-top: -20px;">
                <div class="xx-slider-content">
                    <div class="drama-poster">
                        <ul>

                        </ul>
                    </div>
                    <ul class="drama-slide">
                        <li class="prev"><a href="javascript:;" title="上翻">上翻</a></li>
                        <li class="next"><a href="javascript:;" title="下翻">下翻</a></li>
                    </ul>
                </div>

            </div>
            <button type="button" class="xx-slider-button-next js-modal-close">下一步</button>
        </div>
    </div>
<!--    <div class="am-modal am-modal-no-btn" tabindex="-2" id="doc-modal-2">-->
<!--        <div class="am-modal-dialog xx-k-carousel-content">-->
<!--            <div class="am-modal-hd">-->
<!--                <a href="javascript: void(0)" class="xx-k-carousel-content-return" id="xx-video-return"></a>-->
<!--            </div>-->
<!--            <div class="am-modal-bd">-->
<!--                <video id="example_video_2" class="video-js vjs-amazeui" controls-->
<!--                       preload="none" width="700" height="400"-->
<!--                       poster="" data-setup="{}" style="margin: auto">-->
<!--                    <source src="" type='video/mp4'/>-->
<!--                </video>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="am-modal am-modal-no-btn" tabindex="-2" id="doc-modal-2">
        <div class="am-modal-dialog xx-k-carousel-content">
            <div class="am-modal-hd">
                <a href="javascript: void(0)" class="xx-k-carousel-content-return" id="xx-video-return"></a>
            </div>
            <div class="am-modal-bd">
                <video id="example_video_2" class="video-js vjs-amazeui" controls
                       preload="none" width="700" height="400"
                       poster="" data-setup="{}" style="margin: auto">
                    <source src="" type='video/mp4'/>
                </video>
            </div>
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
<div class="gif" style="display: none">
    <div class="gif-img">

<!--        <img id="testImg" src="{:loadResource('plugin/math/img/wrong2.gif')}" >-->
    </div>

</div>
<input type="hidden" value="{:loadResource('plugin/math/img/fw1.gif')}" name="wrong1">
<input type="hidden" value="{:loadResource('plugin/math/img/sw2.gif')}" name="wrong2">
<input type="hidden" value="{:loadResource('plugin/math/img/right-4.gif')}" name="right">
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
<script src="{:loadResource('plugin/lib/js/amazeui.js')}"></script>
<script src="{:loadResource('plugin/lib/layer/layer.js')}"></script>
<script src="{:loadResource('plugin/lib/videojs/video.js')}"></script>
<script src="{:loadResource('plugin/lib/labelauty/js/labelauty.js')}"></script>
<script src="{:loadResource('static/lib/js/my.ui.js')}"></script>
<script src="{:loadResource('static/math/js/config.js')}"></script>
<script src="{:loadResource('static/math/js/class.Logout.js')}"></script>
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
<script src="{:loadResource('plugin/math/js/class.GxIndex.js')}"></script>
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
        var gxIndex = new GxIndex(".xx-contain");
        $("#myEditor").hide();
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
        console.log(screenH);
        var bh = dom.getAttribute("data-num");
        localStorage.setItem("data-num",bh);
        $("#edui31_body").trigger("click").attr("data-num",bh);
        var a=$(".question-sheet").offset().top;
        var offH=$(".question-sheet").height();
        var top=offH+178;
        var tops=a+offH+10;
        var b=screenH-400;
//        if(screenH-top>300){
//            $(".edui-state-centered").css({
//                "top":top
//            });
//        }
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