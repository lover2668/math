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
    <link rel="stylesheet" href="{:loadResource('static/lib/layer/skin/layer.css')}">
    <link rel="stylesheet" href="{:loadResource('plugin/lib/labelauty/css/labelauty.css')}">
    <link rel="stylesheet" href="{:loadResource('plugin/lib/uploadfile/css/uploadfile.css')}">
    <!-- 选项按钮样式 -->
    <link rel="stylesheet" href="{:loadResource('static/math/css/xc_style.css')}">
    <link href="{:loadResource('classba/app/common/yx_font/iconfont.css')}" rel="stylesheet">
    <style>
       .question-sheet .input-p,.question-sheet p{
            display: inline-block;
        }
       .layui-layer-btn a:hover {
           opacity:1 !important;
           text-decoration: none !important;
       }
       #question-sheet{
           /*margin-top: 15px;*/
           font-size:18px !important;
       }
       #question-sheet p,#question-sheet span{
           /*font-size:18px !important;*/
       }
       .checked{
           background:#dbf9d9 !important;
           color:#26b987;
           border-radius:35px;
       }
       .unchecked{
           background-color: #fff;
           border:1px solid #8edf88;
           border-radius:35px;
       }
       .rdobox, .chkbox{
           margin:0 0 30px 0;
       }
       label>span.check-image{
            display: none;
       }
       .radiobox-content div{
           display: inline !important;
       }
       .rdobox, .chkbox{
           height: auto !important;
       }
       .question-sheet  span.MathJax{
           padding: 10px !important;
           /*font-size:14px !important;*/
       }
       /*.xx-options{*/
           /*position: absolute;*/
           /*top:-40px;*/
           /*left:15px;*/
           /*font-size: 16px;*/
       /*}*/
       .xx-analyse-title>p{
           display: inline;
       }
       .question-sheet .MJXc-display{
           display: inline;
       }
       .edui-default .edui-for-kityformula .edui-dialog-content{
           height:330px !important;
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
<header class="am-topbar xx-topbar-math">
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
            <li class="xx-subject-module-unit active"style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>1</span>&nbsp;&nbsp;&nbsp;先行测试</li>
<!--            <li class="xx-subject-module-unit unlearned" style="border-top-left-radius: 16px;border-bottom-left-radius: 16px;"><span>2</span>&nbsp;&nbsp;&nbsp;测试报告</li>-->
<!--            <li class="xx-subject-module-unit unlearned"><span>3</span>&nbsp;&nbsp;&nbsp;高效学习</li>-->
<!--            <li class="xx-subject-module-unit unlearned"><span>4</span>&nbsp;&nbsp;&nbsp;学习检测</li>-->
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


</header>
<div class="container">
    <div class="fc-content">
        <div class="xx-options">正在学习： <span></span></div>

        <div class="xx-time-charts" id="xx-time-charts" style="">

        </div>
    </div>
    <div class="xx-container">

        <div class="xx-question-num" name="xx-question-type">1</div>
        <div class="xx-question" id="question-sheet">

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
<!--                        <form action="{url link='index/Index/submitCorrection'}" method="post" enctype="multipart/form-data" class="am-g wrong-type">-->
<!--                            <div class="type-check">-->
<!---->
<!--                            </div>-->
<!--                        </form>-->
                    </div>
                    <div id="wrong-time">
                        <button class="wrong-btn" type="submit" id="sure">确认</button>
                        <a href="javascript:;" class="wrong-btn" id="cancel">取消</a>
                    </div>
                </div>
            </div>
        </div>
<!--        <div class="xx-continue">-->
<!--            <div class="xx-next">提交</div>-->
<!--        </div>-->
        <button
            type="button"
            class="am-btn am-btn-warning"
            id="doc-confirm-toggle" style="display: none;">
            Confirm
        </button>
        <div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
            <div class="am-modal-dialog">
                <div class="am-modal-hd">
                    <span class="xx-warning"></span>
                    <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close></a>
                </div>
                <div class="am-modal-bd">
                    <span>你尚未填写答案，是否确认提交？</span>
                </div>
                <div class="am-modal-footer">
                    <span class="am-modal-btn" id="determine" data-am-modal-confirm>确定</span>
                    <span class="am-modal-btn" id="cancel" data-am-modal-cancel>取消</span>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="topicId" value="{$topicId}">
<input type="hidden" name="topic_name" value="{$topic_name}">
<input type="hidden" name="initKStatus" value="1">
<!--<script type="text/javascript" src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">-->
<!--</script>-->
<!--<script type="text/javascript" src="__PUBLIC__/plugin/lib/math_editor/mathjax/MathJax.js?config=TeX-AMS-MML_HTMLorMML">-->
<!--</script>-->
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
<script src="{:loadResource('static/lib/js/jquery.min.js')}"></script>
<script src="{:loadResource('static/lib/js/amazeui.min.js')}"></script>
<script>
    var $_CONFIG = {

    };
    $_CONFIG.ui = "[name='xx-question-type']";
</script>
<!--<script src="__PUBLIC__/plugin/lib/labelauty/js/jquery-labelauty.js"></script>-->
<script src="{:loadResource('static/lib/js/my.ui.js')}"></script>
<script src="{:loadResource('static/math/js/config.js')}"></script>
<script src="{:loadResource('static/math/js/class.Logout.js')}"></script>
<script src="{:loadResource('static/lib/layer/layer.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('static/lib/ueditor1_4_3_3-src/ueditor.config.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('static/lib/ueditor1_4_3_3-src/_examples/editor_api.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('static/lib/ueditor1_4_3_3-src/kityformula-plugin/addKityFormulaDialog.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('static/lib/ueditor1_4_3_3-src/kityformula-plugin/getKfContent.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('static/lib/ueditor1_4_3_3-src/kityformula-plugin/defaultFilterFix.js')}"></script>
<script src="{:loadResource('static/math/js/class.preIndex.js')}"></script>
<script src="{:loadResource('static/lib/labelauty/js/labelauty.js')}"></script>
<script src="{:loadResource('plugin/lib/uploadfile/js/jquery.uploadfile.js')}"></script>
<script src="{:loadResource('static/lib/js/jquery-form.js')}"></script>
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
        var preIndex   = new PreIndex( '#question-sheet');
        $("#myEditor").hide();
    });
    var topic_name=$("input[name=topic_name]").val();
    if(topic_name.length>15){
        var test=topic_name.substring(0,14)+"...";
        $(".xx-subject").html(test);
    }else{
        $(".xx-subject").html(topic_name);
    }
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
        console.log(tops);
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