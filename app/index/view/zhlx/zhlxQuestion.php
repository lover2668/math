<!DOCTYPE html>
<html lang="en">
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
    <link rel="stylesheet" href="{:loadResource('static/lib/layer/skin/layer.css')}">
    <link rel="stylesheet" href="{:loadResource('plugin/lib/labelauty/css/labelauty.css')}">
    <link rel="stylesheet" href="{:loadResource('static/lib/videojs/amazeui.videojs.css')}">
    <link href="{:loadResource('classba/app/common/yx_font/iconfont.css')}" rel="stylesheet">
    <!-- 选项按钮样式 -->
    <link rel="stylesheet" href="{:loadResource('static/math/css/xc_style.css')}">
    <link rel="stylesheet" href="{:loadResource('plugin/lib/uploadfile/css/uploadfile.css')}">
    <style>
        .question-sheet .input-p,.question-sheet p{
            display: inline-block;
        }
        .layui-layer-btn a:hover {
            opacity:1 !important;
            text-decoration: none !important;
        }
        #xx-left{
            background: #ffffff;
            border: 1px solid #6acea7;
            border-radius: 6px;
            min-height: 426px;
            /*margin-top: 60px;*/
            margin-right: 60px;
            max-width: none;
            width: inherit;
            padding: 60px;
            position: relative;
        }

        #modal-ready  .am-modal-dialog{
            width:600px;
            height:600px;
            background: url(__PUBLIC__/static/math/img/complete.png) no-repeat !important;
            background-color: transparent  !important;
        }
        #modal-ready h3{
            margin:0;
        }
        .radiobox-content div,.rdobox div{
            display: inline !important;
        }
        .rdobox, .chkbox{
            height: auto !important;
            margin-bottom: 20px;
        }
        /*.xx-analyse-step .MathJax{*/
            /*display: inherit !important;*/
        /*}*/
        .xx-analyse-step p span div,.question-sheet span .MathJax_Display{
            display: inline !important;
        }
        .question-step .MathJax{
             display: block !important;
         }
        .MJXc-display{
            display: inline-block !important;
        }
       .step-analysis .xx-analyse-step-group{
            width:80%;
        }
        .edui-default .edui-for-kityformula .edui-dialog-content{
            height:330px !important;
        }
        .fc-content{
            margin:0;
        }
        .container-in .xx-frame{
            margin:auto;
        }
        #logo .xx-equation-module{
            margin-left:180px;
            display: inline-block;
        }
        #logo .xx-equation{
            height: auto;
            line-height: 80px;
        }
        .am-topbar-right{
            position: absolute;
            right:10px;
            top:0;
        }
        #collapse-head>li{
            float: left;
            height:80px;
            line-height: 80px;
        }
        #collapse-head>li:first-child{
            margin-right: 25px;
        }
        #collapse-head>li>a{
            color: #fff;
        }
    </style>
</head>
<body>
<header class="am-topbar xx-topbar-math" id="logo">
    <div class="">

        <div class="banger-logo">
            <h1 class="am-topbar-brand xx-brand" style="margin-left: 10px;">
                <a href="<?php echo config('logo_url'); ?>" class="am-text-ir"></a>
            </h1>
            <div class="xx-logo-badges">
                <p class="xx-badge-title">课次</p>
                <p class="xx-badge-title-num">1</p>
            </div>
            <div class="xx-equation"></div>
            <div class="xx-equation-module">
                <span></span>正在进行竞赛拓展
            </div>
        </div>
    </div>
    <div class="">
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
</header>
<div class="container-in" id="zhq">
    <div class="xx-frame">
        <div class="fc-content">
            <div class="xx-time-charts" id="xx-time-charts" style="">

            </div>
        </div>
        <div  id="xx-left">
            <div class="xx-question-num" name="xx-question-type"></div>
            <div class="xx-question" id="question-sheet">

            </div>

            <div class="step-analysis" style="display: none;">
                <div class="xx-list-question">
                    <span>分步解析</span>
                </div>
                <ul class="xx-analyse-step-group">
                    <li  class="xx-analyse-step">
                        <p class="xx-step-name">步骤1/2</p>
                        <p class="xx-step-title">-(3*2)[x^(2*6)]</p>
                    </li>
                </ul>
                <div id="optionButton"></div>
<!--                <div class="xx-analyse-next-step" style="display: none" id="next_step">下一步</div>-->
<!--                <div class="xx-analyse-next-step" style="display: none" id="continue_q">继续答题</div>-->
<!--                <div class="xx-analyse-next-step" style="display: none" id="find_answer">查看答案</div>-->
<!--                <div class="xx-analyse-next-step" style="display: none" id="next_q">继续答题</div>-->
                <div id="optionButton"></div>
                <div class="icon-delete"></div>
            </div>
        </div>
        <div class=" right-img" >
<!--            <div id="xx-right">-->
<!--                <div class="right-test">-->
<!--                    <p>遇到困难了吗？</p>-->
<!--                    <p>可以查看下列资料</p>-->
<!--                </div>-->
<!--                <div class="right-test-1">-->
<!--                    <span></span>-->
<!--                    <a data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 0, width: 820, height: 476}">知识点讲解视频</a>-->
<!--                </div>-->
<!--                <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">-->
<!--                    <div class="am-modal-dialog">-->
<!--                        <div class="am-modal-hd">-->
<!--                            <a href="javascript: void(0)" data-am-modal-close id="xx-video-close-3"></a>-->
<!--                        </div>-->
<!--                        <div class="am-modal-bd">-->
<!--                            <div id="anasy-video">-->
<!--                                <video id="example_video_1" class="video-js vjs-amazeui" controls preload="none" width="700" height="390"-->
<!--                                       poster="video.png"-->
<!--                                       data-setup="{}">-->
<!--                                    <source src="http://media1.classba.cn/dr.mp4" type='video/mp4'/>-->
<!--                                    <track kind="captions" src="video.js/demo.captions.vtt" srclang="en" label="English"></track>-->
<!--                                    <track kind="subtitles" src="video.js/demo.captions.vtt" srclang="en" label="English"></track>-->
<!--                                    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web-->
<!--                                        browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5-->
<!--                                            video</a></p>-->
<!--                                </video>-->
<!---->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="right-test-2">-->
<!--                    <span></span>-->
<!--                    <a>分步解析</a>-->
<!--                </div>-->
<!--                <div class="right-test-3">-->
<!--                    <span style="font-size: 12px;">更多敬请期待更多内容！！</span>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</div>
<div class="findwrongs">
    <div class="xx-feedback" data-am-modal="{target: '#doc-modal-2', closeViaDimmer: 0, width: 820, height: 478}">报错</div>
    <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-2">
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
<button
        type="button"
        class="am-btn am-btn-primary"
        data-am-modal="{target: '#your-modal', closeViaDimmer: 0, width: 600, height: 600}" style="display: none;">
    Modal
</button>

<div id="modal-ready">
<!--    <div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">-->
<!--        <div class="am-modal-dialog">-->
<!--            <div class="am-modal-hd">-->
<!---->
<!--            </div>-->
<!--            <div class="am-modal-bd">-->
<!---->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">
<!--                <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>-->
                <h3>太棒了</h3>
                <span>完成竞赛拓展</span>
            </div>
            <div class="am-modal-bd">

            </div>
        </div>
    </div>
</div>
<!--<div class="modal-container">-->
<!--    <div class="xx-continue" id="xx-over">-->
<!--        <div class="xx-next js-modal-open">提交</div>-->
<!--    </div>-->
<!--</div>-->
<div class="box bottom"></div>
<div class="gif" style="display: none">
    <div class="gif-img">

        <!--        <img id="testImg" src="{:loadResource('plugin/math/img/wrong2.gif')}" >-->
    </div>

</div>
<input type="hidden" value="{:loadResource('plugin/math/img/fw1.gif')}" name="wrong1">
<input type="hidden" value="{:loadResource('plugin/math/img/sw2.gif')}" name="wrong2">
<input type="hidden" value="{:loadResource('plugin/math/img/right-4.gif')}" name="right">
<input type="hidden" name="topicId" value="<?php echo input('topicId','1');?>">
<input type="hidden" name="initKStatus" value="1">
<input type="hidden" value="{$topic_name}" name="topic_name">
<!--<input type="hidden" value="{$topic_name}" name="topic_name">-->
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
<!--<script type="text/javascript" src="__PUBLIC__/plugin/lib/math_editor/mathjax/MathJax.js?config=TeX-AMS-MML_HTMLorMML">-->
<!--</script>-->
<script src="{:loadResource('static/lib/js/jquery.min.js')}"></script>
<!--<script>
    $.post("{url link='index/User/getUserNextModule' vars='topicId=$topicId'}",{topicId: {$topicId} },function(data){
        data=eval('('+data+')');
        if($('#module_type').val()!=data.module_type){
            window.location=data.url+'?topicId={$topicId}';
        }
    });
</script>-->
<script src="{:loadResource('static/lib/js/amazeui.min.js')}"></script>
<!--<script src="__PUBLIC__/plugin/lib/labelauty/js/jquery-labelauty.js"></script>-->
<script src="{:loadResource('static/lib/js/my.ui.js')}"></script>
<script src="{:loadResource('static/math/js/config.js')}"></script>
<script src="{:loadResource('static/math/js/class.Logout.js')}"></script>
<script src="{:loadResource('static/lib/videojs/video.js')}"></script>
<script src="{:loadResource('static/lib/labelauty/js/labelauty.js')}"></script>
<script src="{:loadResource('static/lib/layer/layer.js')}__PUBLIC__/static/lib/layer/layer.js"></script>
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
<script src="{:loadResource('static/math/js/class.zhlxQuestion.js')}"></script>
<script src="{:loadResource('plugin/lib/uploadfile/js/jquery.uploadfile.js')}"></script>
<script type="text/pn" id="myEditor">
        <p>这里我可以写一些lai输入提示</p>
</script>
<script>
    $(function(){
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
        var preIndex   = new ZhlxIndex( '#question-sheet');
        $("#myEditor").hide();
//        $(".right-test-2").click(function(){
//            $(".step-analysis").slideDown(500);
//        });
//        $(".icon-delete").click(function(){
//            $(".step-analysis").slideUp(500);
//        });


        var topic_name=$("input[name=topic_name]").val();
        if(topic_name.length>15){
            var test=topic_name.substring(0,14)+"...";
            $(".xx-equation").html(test);
        }else{
            $(".xx-equation").html(topic_name);
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