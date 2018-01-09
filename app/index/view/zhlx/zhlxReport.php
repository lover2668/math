<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>乂学-数学</title>
    <link rel="icon" type="image/png" href="{:loadResource('plugin/lib/i/yixue-tt-logo.png')}">
    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="乂学教育-数学产品"/>
    <meta name="msapplication-TileColor" content="#0e90d2">
    <link rel="stylesheet" href="{:loadResource('static/lib/css/amazeui.min.css')}">
    <!--    <link rel="stylesheet" href="/plugin/lib/labelauty/css/jquery-labelauty.css">-->
    <meta name="msapplication-TileColor" content="#0e90d2">
<!--    <link rel="stylesheet" href="/static/lib/css/amazeui.min.css">-->
    <!-- 选项按钮样式 -->
    <link rel="stylesheet" href="{:loadResource('plugin/lib/uploadfile/css/uploadfile.css')}">
    <link href="{:loadResource('classba/app/common/yx_font/iconfont.css')}" rel="stylesheet">
    <link rel="stylesheet" href="{:loadResource('static/math/css/xc_style.css')}">
<!--    <link rel="stylesheet" href="/math/css/style.css">-->
    <style>
        #title-report{height:40px;}
        #title-report h1{
            float: left;
        }
        #title-report a{
            float: right;
            margin-top: 2px;
            cursor: pointer;
        }
        #doc-modal-1 .am-modal-hd h3{margin:0;}
        .xx-subject-module li.learned>a{
            color: #9cdf97;
        }
        .xx-subject-module li.learned>a:hover{
            color: #fff;
        }
        .xx-topbar-math a{
            float: none;
            margin-right: 0;
        }
        #xx-report>h3{
            /*margin-left: 200px;*/
            display: inline-block;
            margin-top: 90px;
        }
        #xx-list ul{
            border:none;
            background-color: transparent;
            padding: 0;
        }
        .container #xx-list li{
            width:100px;
            height: 49px;
            border-radius: 5px;
            border:1px solid #79D2B0;
        }
        .container #xx-list li.cur{
            background-color: #E5FBE3;
        }
        .container #xx-list li a{
            color:#6D6D6D;
            font-size: 16px;
            line-height: 49px;
        }
        .container #xx-list li.cur a{
            color: #535752;
        }
        .xx-containers h3{
            border:none;
            padding-bottom: 0;
        }
        .subjects-1{
            padding-bottom: 20px;
            border-bottom: 1px solid #6acea7;
        }
        .choose-options p,.choose-options div,,.question-sheet span .MathJax_Display,.xx-analyse-step p span div{
            display: inline !important;
        }
        .question-banner{
            clear: both;
        }
        .question-banner p{
            padding: 0;
        }
        /*.question-step .MathJax{*/
            /*display: inherit !important;*/
        /*}*/
        .choose-options{
            padding-bottom: 20px;
        }
        .choose-options p{
            padding: 0 !important;
            display: inline !important;
        }
        .choose-options .MathJax_Display{
            display: inline !important;
        }
        .MJXc-display{
            display: inline !important;
        }
        .question-step p span{
            color: #5f5f5f;
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
        .question-step span{
            color: #000;
        }
        .am-topbar-right{
           margin-right: 30px;
        }
        .xx-subject-module li.learned a,.xx-subject-module li{
            float: none;
        }
        .xx-subject-module li{
            display: inline;
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
        .xx-topbar-math{
            min-width: 1200px;
        }
        @media (max-width:1420px ){
            .xx-topbar-math{
                height:120px;
            }
        }
    </style>
</head>
<body>
<header class="am-topbar xx-topbar-math">
    <div class="header-title">
        <h1 class="am-topbar-brand xx-brand" style="margin-left: 70px;">
            <a href="<?php echo config('logo_url'); ?>" class="am-text-ir"></a>
        </h1>
        <div class="xx-logo-badge">
            <p class="xx-badge-title">课次</p>
            <p class="xx-badge-title-num">1</p>
        </div>
        <div class="xx-subject"></div>
        {if condition="$is_show_report"}
        <ul class="xx-subject-module">
            <li class="xx-subject-module-unit learned"><span>1</span>&nbsp;&nbsp;&nbsp;先行测试</li>
            <li class="xx-subject-module-unit learned"><a target="_blank" href="{url link='index/Index/preReport' vars='topicId=$topicId' }?is_show_nextstep=0">2&nbsp;&nbsp;<span>测试报告</span></a></li>
<!--            <li class="xx-subject-module-unit learned"><a target="_blank" href="{url link='index/Index/preReport' vars='topicId=$topicId' }">2&nbsp;&nbsp;<span>测试报告</span></a></li>-->
            <li class="xx-subject-module-unit learned"><span>3</span>&nbsp;&nbsp;&nbsp;高效学习
            </li>
            <li class="xx-subject-module-unit learned"><span>4</span>&nbsp;&nbsp;&nbsp;学习检测</li>
            <li class="xx-subject-module-unit learned"><a  target="_blank" href="{url link='index/bxbl/studyReport' vars='topicId=$topicId' }?is_show_nextstep=0">5&nbsp;&nbsp;<span>学情报告</span></a></li>
<!--            <li class="xx-subject-module-unit learned"><a  target="_blank" href="{url link='index/bxbl/studyReport' vars='topicId=$topicId' }">5&nbsp;&nbsp;<span>学情报告</span></a></li>-->
            <li class="xx-subject-module-unit learned" style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>6</span>&nbsp;&nbsp;&nbsp;竞赛拓展</li>
            <li class="xx-subject-module-unit active"><span>7</span>&nbsp;&nbsp;&nbsp;竞赛拓展报告</li>
<!--            <li class="xx-subject-module-unit unlearned" style="border-top-left-radius: 16px;border-bottom-left-radius: 16px;"><span>3</span>&nbsp;&nbsp;&nbsp;高效学习</li>-->
<!--            <li class="xx-subject-module-unit unlearned"><span>4</span>&nbsp;&nbsp;&nbsp;学习检测</li>-->
<!--            <li class="xx-subject-module-unit unlearned" style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>5</span>&nbsp;&nbsp;&nbsp;学情报告</li>-->
        </ul>
        {/if}
<!--    </div>-->
<!--    <div class="">-->
        <ul class="am-topbar-right" id="collapse-head">
            {if condition="$is_show_report"}
            <li><a href='{url link="report/reportCenter"  vars='topicId=$topicId' suffix='true' domain='true'}' target='_blank'><i class="icon yxiconfont yx-logo">&#xe656;</i>&nbsp;我的报告</a></li>
            <li class="am-dropdown" data-am-dropdown style="height:80px;line-height: 80px;">
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;" style="color:#fff;">
                    <span class="am-icon-user"></span>  <?php echo session("real_name")?session("real_name"):session("username") ?><span class="am-icon-caret-down"></span>
                </a>
                <ul class="am-dropdown-content">
                    <li id="logout">&nbsp;&nbsp;&nbsp;<i class="am-icon-power-off"></i><a href="#">退出系统</a></li>
                </ul>
            </li>
            {/if}
        </ul>
    </div>
</header>
<div class="container">
    <div class="am-g">
        <div class="xx-content xx-title" id="title-report">
            <h1>竞赛拓展报告</h1>
        </div>
        <div class="xx-content xx-frame" id="xx-report">
            <div class="report-chart">
                <div id="main" ></div>

<!--                <div class="allKnowledge">-->
<!--                    <h3>知识点总体掌握率</h3>-->
<!--                    <div >-->
<!--                        <p><i></i><b class="already"></b><span>0</span>个知识点已掌握</p>-->
<!--                        <p><i></i><b class="nothing"></b><span>6</span>个知识点未掌握</p>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
            <h3>答题正确率</h3>
            <div class="" id="master">
                <div class="xx-master">
                    {if condition="$has_learned_percent egt 80"}
                                        <h2>成功=99%的汗水+1%的灵感</h2>
                                        <img src="__PUBLIC__/static/math/img/great.png" alt="">
                                        {elseif condition="$has_learned_percent egt 60"}
                                        <h2>百尺竿头，更进一步</h2>
                                        <img src="__PUBLIC__/static/math/img/ordinary.png" alt="">
                                        {else}
                                        <h2>书山有路勤为径，学海无涯苦作舟</h2>
                                        <img src="__PUBLIC__/static/math/img/worse.png" alt="">
                                        {/if}

                </div>
            </div>
        </div>
        
        
        <div class="xx-content" id="xx-list">
            <ul class="scrollspy-nav" >
                {volist name="getZhlxQuestionIds" id="vo" key="k" }
                    {if $k eq 0}<li  class="cur "><a href="javascript:;">第{$k}组</a><span></span></li>{else}<li><a href="javascript:;">第{$k}组</a><span></span></li>{/if}

                 {/volist}
            </ul>
            
            
            <?php
$has_answered_questions_new=[];
foreach($has_answered_questions as $vvv){
	$has_answered_questions_new[$vvv['id']]=$vvv;
}
$aaa=0;
?>
                              <div class="panes">
                               {volist name="getZhlxQuestionIds" id="vo1" key="k1" }
                                     <div class="pane">
                                          
                                          <!------------------循环列--------------------->
                                          <?php $tinumm=0; $aaa++;?>
                                          {volist name="vo1" id="vo2" key="k2" }
                                          
                                          {foreach name="has_answered_questions_new" item="vo" key="newqk"}
                                          {if $newqk eq $vo2['id']}
                                          <?php unset($has_answered_questions_new[$newqk]); ?>
                                          <div class="subject">
                                        <div class="xx-list-question">
                                            <span>第<?php $tinumm++;echo $tinumm; ?>题</span>
                                        </div>
                                        <div class="xx-containers">
<!--                                            <p>-->
<!--                                                试题ID：{$vo.id}-->
<!--                                            </p>-->
                                            <h3>{$vo.content|html_replace}
                                            </h3>
                                            <div>
                                                {if condition="$vo.q_type eq 1"}
                                                {foreach name="vo.options" item="answer"}
                                                <div class="choose-options">
                                                {$answer.key}: {$answer.answer}
                                                </div>
                                                {/foreach}
                                                {elseif condition="$vo.q_type eq 2"/}

                                                {else /}
                                               {/if}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subjects">
                                        <div class="xx-containers" id="">
                                            <div class="question-banner">
                                                <p>正确答案 <span>
                                                   {if condition="$vo.q_type eq 1"}
                                                    {$vo.answer}
                                                    {/if}
                                                    {if condition="$vo.q_type eq 2"}
                                                    {assign name="i" value="1" /}
                                                    {assign name="j" value="1" /}
                                                    {volist name="vo.answer_base64" key="blank_num" id="ans"  }
                                                   {volist name="ans" key="answer_num" id="an"  }
                                                        {if condition="strstr($an,'png;base64')"}
                                                        <img  src="{$an}" />
                                                        {else/}
                                                        {$an}
                                                        {/if}

                                                   {if condition="$i neq  $answer_num"}
                                                    {/if}
                                                    {/volist}
                                                    {if condition="$j neq  $answer_num"}

                                                    {/if}
                                                    {/volist}
                                                  {/if}



                                                </span></p>
                                                <p>
                                                    你的答案 <span class="{if condition=" $vo.is_right eq 1"}right{else/}wrong{/if}">
                                                    {if condition="$vo.q_type eq 1"}
                                                        {if condition="$vo.user_answer"}
                                                            {$vo.user_answer}
                                                        {else/}
                                                            未作答
                                                        {/if}
                                                    {/if}
                                                    {if condition="$vo.q_type eq 2"}
                                                        {if condition="$vo.user_answer_base64"}
                                                            {volist name="vo.user_answer_base64" key="user_answer_base64_item_key" id="user_answer_base64_item"}
                                                                {if condition="$user_answer_base64_item"}
                                                                <img src="{$user_answer_base64_item}"/>
                                                                {else/}
                                                                　未做答
                                                                {/if}
                                                                {if condition="$user_answer_base64_item_key lt count($vo['user_answer_base64'])"}
                                                                ;
                                                                {/if}
                                                            {/volist}
                                                        {else/}
                                                            未作答
                                                        {/if}
                                                    {/if}
                                                    </span>
                                                    {if condition=" $vo.is_right eq 1"}<i class="am-icon-check" style="color:#26b987"></i>{else/}<i class="am-icon-close" style="color:red"></i>{/if}
                                                </p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="subjects-1">
                                        <div class="xx-list-question">
                                            <span>题目解析</span>

                                        </div>
                                        <div class="xx-containers question-step">
                                                {volist name="vo.analyze" id="anal"  }
                                                {volist name="anal.content" id="con" key="i" }
                                                 {$con.content}
                                                {/volist}
                                                {/volist}
                                        </div>
                                    </div>
                                    {/if}
                                    {/foreach}
                                    {/volist}
                                    <!------------------循环列--------------------->    
                                    
                                </div>

                                {/volist}
                            </div>
            
            
            
            
                        </div>

    </div>
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
                <!--                        <div class="radio-check am-u-lg-3">-->
                <!--                            <input type="radio" id="radio-2-1" data-question_id="111" name="type-1" class="regular-radio big-radio" />-->
                <!--                            <label for="radio-2-1"></label>-->
                <!--                            <span>题干错误</span>-->
                <!--                        </div>-->
                <!--                        <div class="radio-check am-u-lg-3">-->
                <!--                            <input type="radio" id="radio-2-2" data-question_id="222" name="type-2" class="regular-radio big-radio" />-->
                <!--                            <label for="radio-2-2"></label>-->
                <!--                            <span>答案错误</span>-->
                <!--                        </div>-->
                <!--                        <div class="radio-check am-u-lg-3">-->
                <!--                            <input type="radio" id="radio-2-3" data-question_id="333" name="type-3" class="regular-radio big-radio"  checked />-->
                <!--                            <label for="radio-2-3"></label>-->
                <!--                            <span>系统bug</span>-->
                <!--                        </div>-->
                <!--                        <div class="radio-check am-u-lg-3">-->
                <!--                            <input type="radio" id="radio-2-4" data-question_id="444" name="type-4" class="regular-radio big-radio" />-->
                <!--                            <label for="radio-2-4"></label>-->
                <!--                            <span>其他错误</span>-->
                <!--                        </div>-->
                <!--                        <div class="am-u-lg-12">-->
                <!--                            <textarea name="content" id="monent" style="font-size: 16px;">请输入错误内容</textarea>-->
                <!--                        </div>-->
                <!--                        <div class="am-u-lg-12" id="option-page">-->
                <!--                            <div class="add-option" id="fileuploader"></div>-->
                <!--                            <input type="hidden" name="file_path">-->
                <!--                            <p>未选择任何文件，插入题目错误截图可以更好地帮助你反馈错误</p>-->
                <!--                        </div>-->
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
<div class="page-over">
    <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">
                <h3>恭喜你</h3>
                <span>完成专题学习</span>
            </div>
            <div class="am-modal-bd">
                <!--            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close></a>-->
<!--                <a class="pageClose" data-am-modal-close>你可以关闭本页面</a>-->
                <a href="http://127.0.0.1:8200/index.php/Index/zhlx/index/topicId/21.html">进入竞赛拓展</a>
            </div>
        </div>
    </div>
    <a  data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 1, width: 600, height: 600}"></a>
</div>

<div id="modal-ready">
    <div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal-1">
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
    {if condition="$is_show_nextstep"}
<div class="xx-continue xx-btn js-modal-open" style="" >
    <div class="xx-next">结束</div>
</div>
    {/if}
<input type="hidden" name="topicId" value="{$topicId}">
<input type="hidden" name="is_all_right" value="0"/>
<input type="hidden" name="xiance_count" value="{$xiance_count}" />
<input type="hidden" value="{$topic_name}" name="topic_name">
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
<script src="{:loadResource('static/lib/js/echarts.min.js')}"></script>
<script src="{:loadResource('static/math/js/config.js')}"></script>
<script src="{:loadResource('static/math/js/class.Logout.js')}"></script>
<script src="{:loadResource('static/lib/js/jquery-form.js')}"></script>
<script src="{:loadResource('plugin/lib/layer/layer.js')}"></script>
<script src="{:loadResource('plugin/lib/uploadfile/js/jquery.uploadfile.js')}"></script>
<script src="{:loadResource('plugin/math/js/class.GxReport.js')}"></script>
<script>
    var myChart=echarts.init(document.getElementById('main'));
    option = {
        tooltip: {
            trigger: 'item',
            show:false,
            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        title: {
            text: {$daduibi} + '%',
            x: 'center',
            y: 'center',
            textStyle: {
                color: '#333',
                fontWeight: 'bolder',
                fontSize: 36,
            }
        },
        series: [
            {
                name:'访问来源',
                type:'pie',
                color:['#4FCCEB','#FA494B'],
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: false,
                        textStyle: {
                            fontSize: '30',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data:[
                    {value:{$daduitishuliang}, name:'{$daduitishuliang}个答对'},
                    {value:{$zongtishuliang-$daduitishuliang}, name:'{$zongtishuliang-$daduitishuliang}个答错'}
                ]
            }
        ]
    };
    myChart.setOption(option);
//    $('.am-slider').flexslider({itemWidth: 60, itemMargin: 100, slideshow: true});
    $(function(){
        var $modal = $('#your-modal-1');

        $modal.parent("#modal-ready").siblings('.xx-btn').on('click', function(e) {
            var $target = $(e.target);
            if (($target).hasClass('js-modal-open')) {
                $modal.modal();
            } else if (($target).hasClass('js-modal-close')) {
                $modal.modal('close');
            } else {
                $modal.modal('toggle');
            }
        });
        var logout   = new Logout( '#collapse-head');
        $("#xx-list ul").find("li:first-child").addClass("cur");
//        $("#xx-list ul").find("li:first-child span").addClass("native");
        $(".pane:first-child").show();
        $('#xx-list ul li').click(function(){
            $(this).addClass('cur').siblings().removeClass('cur');
//            $(this).children('span').addClass('native').parent('li').siblings().children('span').removeClass('native');
            $('.panes>div:eq('+$(this).index()+')').show().siblings().hide();
        });
        var topicId=$("input[name=topicId]").val();

        var is_all_right=$("input[name=is_all_right]").val();
//        if(is_all_right==0){
//            $(".xx-continue").on("click",function(){
//                window.open(HOST+"index/bxbl/bIndex/topicId/"+topicId,"_self");
//            });
//        }else{
//            $(".xx-next").html("结束");
////            $(".page-over>a").trigger("click");
//            $(".xx-continue").on("click",function(){
//                $(".page-over>a").trigger("click");
//            });
//        }
        var topicId=$("input[name=topicId]").val();
        function getQueryString(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }
        console.log(getQueryString("show"))
        if(getQueryString("show")==0){
            $(".xx-continue").css("display","none");
        }
//        var xiance_count=$("input[name=xiance_count]").val();
//        if(xiance_count>0){
//            $(".xx-subject-module li.learned").find("a").click(function(){
//                window.open(HOST+"index/index/preReport/topicId/"+topicId+"?show=0","_blank");
//            });
//        }else{
//            $(".xx-subject-module li.learned").find("a").click(function(){
//                layer.msg("先行测试未做！");
//            });
//        }

        var topic_name=$("input[name=topic_name]").val();
        if(topic_name.length>15){
            var test=topic_name.substring(0,14)+"...";
            $(".xx-subject").html(test);
        }else{
            $(".xx-subject").html(topic_name);
        }
    });
    var gesuy=$('#xx-list').find('.scrollspy-nav').find('li').length-$('#xx-list').find('.panes').find('.pane').length;
    if(gesuy>0){
        for(var i=$('#xx-list').find('.scrollspy-nav').find('li').length;i>$('#xx-list').find('.panes').find('.pane').length;i--){
            $('#xx-list').find('.scrollspy-nav').find('li').eq(i-1).css('display','none');
        }
    }
</script>
<!--<script src="{:loadResource('classba/app/common/js/x_analyse.js')}"></script>-->
<script src="{:loadResource('classba/assets/tools/xa.js')}"></script>
</body>
</html>