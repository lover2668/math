<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>寒假课数学</title>
    <meta name="keywords" content="上海乂学教育科技有限公司-暑期课数学"/>
    <meta name="description" content="上海乂学教育科技有限公司-暑期课程系列（数学）"/>
    <meta name="renderer" content="webkit">
    <meta name="force-rendering" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{:loadResource('classba/assets/bootstrap/css/bootstrap.min.css')}" rel="stylesheet">
    <link href="{:loadResource('classba/app/common/yx_font/iconfont.css')}" rel="stylesheet">
    {block name="css"}

    {/block}
<!--    <script>-->
<!--        //声明_czc对象:-->
<!--        var _czc = _czc || [];-->
<!--        host = window.location.host;-->
<!--        var reportjs_id = "";-->
<!--        switch (host){-->
<!--            case "math2.classba.cn" :-->
<!--                reportjs_id = "1263344959";-->
<!--                break;-->
<!--            case "math2.171xue.com" :-->
<!--                reportjs_id = "1263344959";-->
<!--                break;-->
<!--            case "en2.171xue.com" :-->
<!--                reportjs_id = "1263344995";-->
<!--                break;-->
<!--            case "en1.171xue.com" :-->
<!--                reportjs_id = "1263344995";-->
<!--                break;-->
<!--            case "en1.classba.cn" :-->
<!--                reportjs_id = "1263344995";-->
<!--                break;-->
<!--            case "en-reading.classba.cn" :-->
<!--                reportjs_id = "1263344995";-->
<!--                break;-->
<!--            case "cn2.171xue.com" :-->
<!--                reportjs_id = "1263345020";-->
<!--                break;-->
<!--            case "cn2.classba.cn" :-->
<!--                reportjs_id = "1263345020";-->
<!--                break;-->
<!--            case "phy.171xue.com" :-->
<!--                reportjs_id = "1263345031";-->
<!--                break;-->
<!--            case "phy.classba.cn" :-->
<!--                reportjs_id = "1263345031";-->
<!--                break;-->
<!--            case "localhost.math" :-->
<!--                reportjs_id = "1263351340";-->
<!--                break;-->
<!--            case "math.local":-->
<!--                reportjs_id = "1263358369";-->
<!--                break;-->
<!--            default :-->
<!--                reportjs_id = "1263345112";-->
<!--                break;-->
<!--        }-->
<!--        //绑定siteid，请用您的siteid替换下方"XXXXXXXX"部分-->
<!--        _czc.push(["_setAccount", reportjs_id]);-->
<!--    </script>-->
</head>
<body>
<div class="xx-header">
    <div class="xx-navbar">
        <div class="xx-navbar-left xx-navbar-logo">
        </div>
        <div class="xx-navbar-left xx-nav">
            {block name="title"}

            {/block}
        </div>
        <ul class="xx-navbar-right xx-navbar-menu">
            <li class="xx-navbar-left xx-report">
                <i class="xx-icon">&#xe656;</i>&nbsp;我的报告
            </li>
            <div class="hover-li">点击此处可查看所有本课程学习报告<i class="yxiconfont">&#xe65f;</i></div>
            <li class="xx-navbar-left xx-account"><i class="xx-icon">&#xe65b;</i>&nbsp; <?php echo session("real_name")?session("real_name"):session("username") ?></li>
        </ul>
    </div>
</div>
    {block name="menu"}
    {/block}
<!--</header>-->
<!--内容 start-->
{block name="mainContent"}

{/block}
<!--内容 end-->
<script>
    var $url_config = {
        "get_pre_exam_questions":"{:url('Index/getExamQuestions')}",
        "submit_pre_exam_question":"{:url('Index/submitQuestion')}",
        "get_basic_exam_questions":"{:url('Index/getBaseExamQuestions')}",
        "submit_base_exam_question":"{:url('Index/baseSubmitQuestion')}",
        "get_gg_exam_questions":"{:url('Index/getGgExamQuestions')}",
        "submit_gg_exam_question":"{:url('Index/ggSubmitQuestion')}",
        "submit_redo_exam_questions":"{:url('Index/redoSubmitQuestion')}",
        "get_bt_exam_question":"{:url('Index/getBtExamQuestions')}",
        "submit_bt_exam_questions":"{:url('Index/btSubmitQuestion')}",
        "get_check_exam_questions":"{:url('Test/getExamQuestions')}",
        "submit_check_exam_questions":"{:url('Test/submitQuestion')}"

    };
    var $_CONFIG = {

    };
    $_CONFIG.ui = "[name='xx-question-type']";
</script>
<script type="text/javascript" src="{:loadResource('static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML',false)}">
</script>
<script src="{:loadResource('classba/assets/jquery/jquery.js')}"></script>
<script src="{:loadResource('classba/assets/tools/classba.ui.js')}"></script>
<script src="{:loadResource('classba/assets/bootstrap/js/bootstrap.js')}"></script>
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
    tex2jax: {
      inlineMath: [ ['$','$'], ["\\(","\\)"] ],
      processEscapes: true
    }
  });
</script>
<!--<script src="__PUBLIC__/classba/app/math/js/xx-charts.js"></script>-->
{block name="js"}

{/block}
<script>
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
    var topicId = $("input[name=topicId]").val();

    $(".xx-report").on("click",function(){
        window.open("http://"+window.location.host+"/index.php/"+"/summer/index/reportCenter/topicId/"+topicId,"_blank")
    })
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