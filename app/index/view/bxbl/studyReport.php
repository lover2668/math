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
    <link rel="stylesheet" href="{:loadResource('plugin/lib/uploadfile/css/uploadfile.css')}">
    <link href="{:loadResource('classba/app/common/yx_font/iconfont.css')}" rel="stylesheet">
    <!-- 选项按钮样式 -->
    <link rel="stylesheet" href="{:loadResource('plugin/math/css/bx_style.css')}">
    <style>
        #doc-modal-1 .am-modal-hd h3{margin:0;}
        .xx-mathJax{
            padding-top: 10px;
        }
        .xx-mathJax div{
            display: inline !important;
        }
        .xx-subject-module li.learned>a{
            color: #9cdf97;
        }
        .xx-subject-module li.learned>a:hover{
            color: #fff;
        }
        .xx-containers .MJXc-display{
            display: inline;
        }
        .knoledgement .fc-answer{
            font-size: 18px;
            margin:25px 0 0 20px;
            display: inline-block;
            font-family:"MicrosoftYaHei";
            font-weight: bold;
        }
        .am-topbar-right{
            margin-right: 40px;
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
        @media (max-width:1457px ){
            .xx-topbar-math{
                height:120px;
            }
        }
        .xx-report-module1 .xx-report-init.xx-report-module1-legend h3{
            margin-top: 36px;
        }
        .xx-report-module1 .xx-report-init.xx-report-module1-legend p.xx-legend{
            height:auto;
            width:250px;
            padding: 4px 0;
            margin-top: 10px;
            display: block;
            border-radius: 20px;
        }
        .xx-report-module1 .xx-report-init.xx-report-module1-legend p.xx-legend i{
            width:24px;
            height:24px;
            top:0;
        }
        .xx-report-module1 .xx-report-init.xx-report-module1-legend p.xx-legend span{
            margin-left: 30px;
        }
    </style>
</head>
<body>
<header class="xx-topbar-math">
    <div class="">
        <h1 class="am-topbar-brand xx-brand" style="margin-left: 40px;">
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
            {if $xiance_is_end eq 1}
            <li class="xx-subject-module-unit learned"><a target="_blank" href="{url link='index/Index/preReport' vars='topicId=$topicId' }?is_show_nextstep=0">2&nbsp;&nbsp;<span>测试报告</span></a></li>
<!--            <li class="xx-subject-module-unit learned"><a target="_blank" href="{url link='index/Index/preReport' vars='topicId=$topicId' }">2&nbsp;&nbsp;<span>测试报告</span></a></li>-->
            {/if}
            
            <li class="xx-subject-module-unit learned"><span>3</span>&nbsp;&nbsp;&nbsp;高效学习
            </li>
            <li class="xx-subject-module-unit learned"><span>4</span>&nbsp;&nbsp;&nbsp;学习检测</li>
            {if $bxbl_is_end eq 1}
            <li class="xx-subject-module-unit active"><span>5</span>&nbsp;&nbsp;&nbsp;学情报告</li>
                {if $zhlx_is_end eq 1}
                <li class="xx-subject-module-unit learned" style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>6</span>&nbsp;&nbsp;&nbsp;竞赛拓展</li>
                <li class="xx-subject-module-unit learned"><a target="_blank" href="{url link='index/Zhlx/zhlxReport' vars='topicId=$topicId' }?is_show_nextstep=0">7&nbsp;&nbsp;<span>竞赛拓展报告</span></a></li>
<!--                <li class="xx-subject-module-unit learned"><a target="_blank" href="{url link='index/Zhlx/zhlxReport' vars='topicId=$topicId' }">7&nbsp;&nbsp;<span>竞赛拓展报告</span></a></li>-->
                {/if}
                {if $mncs_is_end eq 1}
                <li class="xx-subject-module-unit learned" style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>6</span>&nbsp;&nbsp;&nbsp;模拟测试</li>
                <li class="xx-subject-module-unit learned"><a target="_blank" href="{url link='index/Mncs/mncsReport' vars='topicId=$topicId' }?is_show_nextstep=0">7&nbsp;&nbsp;<span>模拟测试报告</span></a></li>
                {/if}
            {/if}
            
            
        </ul>
        {/if}
    </div>
    <div class="">
        <ul class="am-topbar-right" id="collapse-head">
             {if condition="$is_show_report"}
            <li><a href='{url link="report/reportCenter"  vars='topicId=$topicId' suffix='true' domain='true'}' target='_blank'><i class="icon yxiconfont yx-logo">&#xe656;</i>&nbsp;我的报告</a></li>
            <li class="am-dropdown" data-am-dropdown style="height:80px;">
                <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;" style="line-height: 80px;color:#fff;">
                    <span class="am-icon-user"></span>  <?php echo session("real_name")?session("real_name"):session("username") ?><span class="am-icon-caret-down"></span>
                </a>
                <ul class="am-dropdown-content">
                    <li id="logout">&nbsp;&nbsp;&nbsp;<i class="am-icon-power-off"></i><a href="#">退出系统</a></li>
                </ul>
            </li>
             {/if}
        </ul>
    </div>
    <div style="clear: both"></div>
</header>
<div id="studyReport">
    <!--内容 start-->
    <div class="am-g doc-am-g xx-contain" style="padding-bottom: 80px">
        <div class="xx-content xx-title" id="title-report">
            <h1>学情报告</h1>
        </div>
        <div class="xx-report-module1" style="margin-top:20px;">
            <div class="xx-report-init xx-report-module1-chart" id="xx-learn-status"></div>
            <div class="xx-report-init xx-report-module1-legend">
                <h3>知识点总体掌握率</h3>
                <p class="xx-legend already"><i class=""></i><span>{$frist_has_learned_num}</span>个知识点原本已掌握</p>
                <p class="xx-legend learned"><i class=""></i><span>{$has_learned_weakElements_num}</span>个知识点通过学习掌握</p>
                <p class="xx-legend unlearned"><i class=""></i><span>{$not_learned_weakElements_num}</span>个知识点通过学习未掌握</p>
                <input  name="frist_has_learned_num"   type="hidden"  value="{$frist_has_learned_num}" />
                <input  name="has_learned_weakElements_num"   type="hidden"  value='{$has_learned_weakElements_num}' />
                <input  name="not_learned_weakElements_num"   type="hidden"  value='{$not_learned_weakElements_num}' />
            </div>
            <div class="xx-report-init xx-report-module1-text">
                <!--            <p>完成的太棒了！</p>-->
                <!--            <img src="__PUBLIC__/plugin/math/img/great.png">-->
                {if condition="$scale egt 80"}
                <span>成功=99%的汗水+1%的灵感</span>
                <img src="__PUBLIC__/static/math/img/great.png" alt="">
                {elseif condition="$scale egt 60"}
                <span>百尺竿头，更进一步</span>
                <img src="__PUBLIC__/static/math/img/ordinary.png" alt="">
                {else}
                <span>书山有路勤为径，学海无涯苦作舟</span>
                <img src="__PUBLIC__/static/math/img/worse.png" alt="">
                {/if}
                </h2>

            </div>
        </div>
        <div class="xx-report-module2" >
            <div  id="xx-learn-status-rate" style="width:100%;height:396px;"></div>
        </div>
        <div class="xx-content" id="xx-list">
            <div class="knoledgement scrollspy-nav" data-am-scrollspynav="{offsetTop: 45}" data-am-sticky>
                <div id="myChartRound" style="height:220px;width:220px;float: left"></div>
<!--                <div id="wrapper">-->
<!--                    <div id="myscrollbox">-->
<!--                        <ul>-->
<!--                            {volist name="has_answered_questions" id="vo" key="k" }-->
<!--                            <li class="{if condition="$vo.is_right eq 1"}right{else/}error{/if}  "><a href="javascript:;">{$k}</a><span></span></li>-->
<!--                            {/volist}-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="knowledge-btn">-->
<!--                    <a href="javascript:;" class="btn prev cur"></a>-->
<!--                    <a href="javascript:;" class="btn next"></a>-->
<!--                </div>-->
                <span class="fc-answer">答题正答率</span>
                <ul>
                    {volist name="has_answered_questions" id="vo" key="k" }
                    <li class="{if condition="$vo.is_right eq 1"}right{else/}error{/if}  "><a href="javascript:;">{$k}<input type="hidden" value="{$vo.id}" /></a></li>
                    {/volist}
                </ul>
            </div>

            <div class="panes">
                {volist name="has_answered_questions" id="vo" key="k" }
                <div class="pane" {if condition="$k eq 1"}style="display:block;"{/if}>
                <div class="subject">
                    {if condition="true"}
                    <div class="xx-list-question">
                        <span>第{$k}题</span>
                    </div>
                    <div class="xx-containers">
                        
                        {$vo.tag_name}
                        <h3>{$vo.content|html_replace}</h3>
                        <div>
                            {if condition="$vo.q_type eq 1"}
                            {foreach name="vo.options" item="answer"}
                            <div class="am-u-lg-12 xx-mathJax">
                                    {$answer.key}: {if condition="isset($answer['answer'])"}{$answer.answer}{/if}

                            </div>
                            {/foreach}
                            {elseif condition="$vo.q_type eq 2"/}
                            {else /}
                            {/if}
                        </div>
                    </div>
                    {/if}
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
                                    {if condition="$vo.user_answer neq '' "}
                                            {$vo.user_answer}
                                        {else/}
                                            <i  style="color:red">未作答</i>
                                    {/if}
                                {/if}
                                {if condition="$vo.q_type eq 2"}
                                    {assign name="num"  value="$vo.user_answer_base64|count" /}
                                    {if condition="$num neq 0 "}
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
                                        <i  style="color:red">未作答</i>
                                    {/if}
                                {/if}
                                </span>

                                {if condition=" $vo.is_right eq 1"}
                                    <i class="am-icon-check" style="color:#26b987"></i>
                                {else/}
                                    <i class="am-icon-close" style="color:red">
                                        {if condition=" $vo.is_view_answer eq 1"}
                                        (查看过答案)
                                        {/if}
                                    </i>
                                {/if}
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
                        <p> <!--<span>第{$i}步：</span>-->{$con.content}</p>
                        {/volist}
                        {/volist}
                    </div>
                </div>
            </div>
            {/volist}
        </div>
        <div style="clear: both"></div>
        <input  name="scale"   type="hidden"  value="{$scale}" />
        <input  name="tag_ability_report"   type="hidden"  value='{$tag_ability_report}' />
    </div>
    <div class="page-over">
        <div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
            <div class="am-modal-dialog">
                <div class="am-modal-hd">
                    <h3>恭喜你</h3>
                    <span>完成专题学习</span>
                </div>
                <div class="am-modal-bd">
                    <!-- 等于topicId==106不显示           <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close></a>-->
<!--                    <a class="pageClose" data-am-modal-close>你可以关闭本页面</a>-->
                    {if condition="$topicId==106||$topicId==142||$topicId==151||$topicId==106||$topicId==115||$topicId==124||$topicId==159||$topicId==169||$topicId==160||$topicId==213"}
                    {else}
                    <a class="continue_next_step" href="{$url}">进入竞赛拓展</a>
                    {/if}
                    
                </div>
            </div>
        </div>
         {if condition="$is_show_nextstep"}
<!--        <div class="xx-continue" data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 1, width: 600, height: 600}">-->
<!--            <div class="xx-continue-inner">结束</div>-->
<!--        </div>-->
        <div class="xx-continue" data-am-modal="">
            <div class="xx-continue-inner">结束</div>
        </div>
         {/if}
    </div>

    <!--内容 end-->
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
<!--                <form   class="am-g wrong-type" id="submit">-->
<!--                    <div class="type-check">-->
<!--                        <div class="radio-check am-u-lg-3">-->
<!--                            <input type="radio" id="radio-2-1" data-question_id="111" name="type" class="regular-radio big-radio" value="1"/>-->
<!--                            <label for="radio-2-1"></label>-->
<!--                            <span>题干错误</span>-->
<!--                        </div>-->
<!--                        <div class="radio-check am-u-lg-3">-->
<!--                            <input type="radio" id="radio-2-2" data-question_id="222" name="type" class="regular-radio big-radio" value="2"/>-->
<!--                            <label for="radio-2-2"></label>-->
<!--                            <span>答案错误</span>-->
<!--                        </div>-->
<!--                        <div class="radio-check am-u-lg-3">-->
<!--                            <input type="radio" id="radio-2-3" data-question_id="333" name="type" class="regular-radio big-radio"  checked="checked" value="3"/>-->
<!--                            <label for="radio-2-3"></label>-->
<!--                            <span>系统bug</span>-->
<!--                        </div>-->
<!--                        <div class="radio-check am-u-lg-3">-->
<!--                            <input type="radio" id="radio-2-4" data-question_id="444" name="type" class="regular-radio big-radio" value="4"/>-->
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
<input type="hidden" name="flow_id" value="{$flow_id}">
<input type="hidden" name="flow_url" value="{$url}">
<input type="hidden" name="topic_name" value="{$topic_name}">
<input type="hidden" name="topicId" value="{$topicId}">
<input type="hidden" name="accuracy" value="{$accuracy}">
<input type="hidden" name="module_type" value="{$module_type}" />
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
<script src="{:loadResource('static/lib/js/my.ui.js')}"></script>
<script src="{:loadResource('static/math/js/config.js')}"></script>
<script src="{:loadResource('static/math/js/class.Logout.js')}"></script>
<script src="{:loadResource('plugin/lib/echarts/echarts.min.js')}"></script>
<script src="{:loadResource('static/lib/js/jquery-form.js')}"></script>
<script src="{:loadResource('plugin/lib/uploadfile/js/jquery.uploadfile.js')}"></script>
<script src="{:loadResource('plugin/math/js/class.GxReport.js')}"></script>

<!-- 选项按钮JS -->
<script>
    $(document).ready(function () {
//        var gxReport = new GxReport(".xx-contain");
//        $(".xx-continue").trigger("click");
        var logout   = new Logout( '#collapse-head');
        var myChart = echarts.init(document.getElementById("xx-learn-status"));
        var scale = $('input[name=scale]').val();
        var topicId=$("input[name=topicId]").val();
        var topic_name=$("input[name=topic_name]").val(),
            flow_id=$("input[name=flow_id]").val(),
            flow_url=$("input[name=flow_url]").val();
        if(topic_name.length>15){
            var test=topic_name.substring(0,14)+"...";
            $(".xx-subject").html(test);
        }else{
            $(".xx-subject").html(topic_name);
        }
        $(".xx-continue").on("click",function(){
            window.open(flow_url,"_self");
        });
//        if(flow_id==1){
//            $(".xx-continue").on("click",function(){
//                window.open(HOST+"index/zhlx/btips/topicId/"+topicId,"_self");
//            });
//        }else{
//            $(".xx-continue").on("click",function(){
//                window.open(HOST+"index/mncs/index/topicId/"+topicId,"_self");
//            });
//        }

        scale=Math.floor(scale*100)/100;

        var frist_has_learned_num = $('input[name=frist_has_learned_num]').val();
        var has_learned_weakElements_num = $('input[name=has_learned_weakElements_num]').val();
        var not_learned_weakElements_num = $('input[name=not_learned_weakElements_num]').val();
        option = {
            tooltip: {
                trigger: 'item',
                show: false,
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            title: {
                text: scale + '%',
                x: 'center',
                y: 'center',
                textStyle: {
                    color: '#333',
                    fontWeight: 'bolder',
                    fontSize: 26,
                }
            },
            series: [
                {
                    name: '掌握率',
                    type: 'pie',
                    color: ['#4fcceb', '#fedf00', '#fa494b'],
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
                    data: [
                        {value: frist_has_learned_num, name: '原本已掌握的知识点'},
                        {value: has_learned_weakElements_num, name: '通过学习掌握的知识点'},
                        {value: not_learned_weakElements_num, name: '学习后仍未掌握的知识点'}
                    ]
                }
            ]
        };

        myChart.setOption(option);
        var myChartRate = echarts.init(document.getElementById("xx-learn-status-rate"));
        var xData =  [];
        var xAbility=[];
        var tag_ability_report=$('input[name=tag_ability_report]').val();
        console.log(tag_ability_report);
        tag_ability_report=eval('('+tag_ability_report+')');
        console.log(tag_ability_report);
        for(var i=0;i<tag_ability_report.length;i++){
            var tagName=tag_ability_report[i].tag_name;
            xData.push(tagName);
            var ability=parseInt(tag_ability_report[i].ability*100);
            xAbility.push(ability);
        }

        console.log(xData);
        console.log(xAbility);
        optionRate = {
            title: {
                text: "单个知识点掌握率",
                x: "7%",
                y:"8%",
                textStyle: {
                    color: '#5f5f5f',
                    fontSize: '18'
                },
            },
            tooltip : {
                trigger: 'item',
                show:true,
                formatter: "{a} <br/>{b} : {c} %",
//                formatter: function (params) {
////                    console.log(JSON.stringify(params));
////                    return "所占的比例："+(params.data.value/all).toFixed(3)*100+'%'+'<br/>'+"<br/>"+ params.data.text;
//                }
            },
            grid: {
                borderWidth: 0,
                top: 110,
                bottom: 95,
                left:190,
                right:190,
                x: 40,
                x2: 100,
                y2: 150,// y2可以控制 X轴跟Zoom控件之间的间隔，避免以为倾斜后造成 label重叠到zoom上
                textStyle: {
                    color: "#fff"
                }
            },
            legend: {
//                x: '4%',
                top: '11%',
                textStyle: {
                    color: '#90979c',
                },
                data:['当前掌握率'],
                right:'4%',
            },


            calculable: true,
            xAxis: [{
                type: "category",
                axisLine: {
                    lineStyle: {
                        color: '#90979c'
                    }
                },
                splitLine: {
                    "show": false
                },
                axisTick: {
                    "show": false
                },
                splitArea: {
                    "show": false
                },
                axisLabel: {
                    "interval": 0,
                    margin:25,
                    rotate:10
                },
                data: xData,
            }],
            yAxis: [{
                type: "value",
//                "splitLine": {
//                    "show": false
//                },
                max:100,
                name:'通过率%',
                axisLine: {
                    lineStyle: {
                        color: '#90979c'
                    }
                },
                axisTick: {
                    "show": false
                },
                axisLabel: {
                    "interval": 0,

                },
                splitArea: {
                    "show": false
                },

            }],
//            dataZoom: [{
//                show: true,
//                height: 30,
//                xAxisIndex: [
//                    0
//                ],
//                bottom: 30,
//                start: 10,
//                end: 80,
//                handleIcon: 'path://M306.1,413c0,2.2-1.8,4-4,4h-59.8c-2.2,0-4-1.8-4-4V200.8c0-2.2,1.8-4,4-4h59.8c2.2,0,4,1.8,4,4V413z',
//                handleSize: '110%',
//                handleStyle:{
//                    color:"#d3dee5",
//
//                },
//                textStyle:{
//                    color:"#fff"
//                },
//                borderColor:"#90979c"
//
//
//            }, {
//                type: "inside",
//                show: true,
//                height: 15,
//                start: 1,
//                end: 35
//            }],
            series: [
                {
                    name: "标准掌握率",
                    type: "bar",
                    barMaxWidth: 35,
                    barGap: "10%",
                    data:xAbility,
                    markLine: {
                        symbol : 'none',
                        silent: true,
                        itemStyle : {
                            normal : {
                                color:'#1e90ff',
                                label : {
                                    show:true,
                                    formatter: function (param) {
                                        console.log(param);
                                        return param.seriesName;
                                    }
                                }
                            }
                        },
                        data: [{
                            yAxis: 70,
                        }]
                    },
//                    markLine : {
//                        symbol : 'none',
//                        itemStyle : {
//                            normal : {
//                                color:'#1e90ff',
//                                label : {
//                                    show:true,
//                                    formatter: function (param) {
//                                        return Math.round(param.value/10000) +
//                                    }
//                                }
//                            }
//                        },
//                        data : [
//                            {yAxis: 70,}
//                        ]
//                    },
                    itemStyle: {
                        normal: {
                            color: "#4fcceb",
                            label: {
                                show: true,
                                textStyle: {
                                    color: "#fff"
                                },
                                position: "insideTop",
                                formatter: function(p) {
                                    return p.value > 0 ? (p.value+"%") : '';
                                }
                            }
                        }
                    },

                },
            ]
        };
        myChartRate.setOption(optionRate);
        window.onresize = myChartRate.resize;

        var myChartRound=echarts.init(document.getElementById("myChartRound"));
        var accuracy=$("input[name=accuracy]").val();
        var allq=$(".knoledgement").find("li").length,
            alle=$(".knoledgement").find("li.error").length,
            allr=allq-alle;
        console.log(allq);
        optionRound = {
            tooltip: {
                trigger: 'item',
                show:false,
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            title: {
                text: accuracy + '%',
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
                        {value:allr, name:'2个知识点已掌握'},
                        {value:alle, name:'3个知识点未掌握'}
                    ]
                }
            ]
        };
        myChartRound.setOption(optionRound);
        var a = $(".xx-drama-poster ul>li");
        a.mouseover(function () {
            a.removeClass("current");
            $(this).addClass("current")
        });
        $(".xx-drama-slide li.next a").click(function () {
            var b = $(".xx-drama-poster ul>li:first"), c = $(".xx-drama-poster ul .current").index();
            $(".xx-drama-poster ul>li:last").after(b);
            $(".xx-drama-poster ul li").removeClass("current");
            $(".xx-drama-poster ul").find("li").eq(c).addClass("current")
        });
        $(".xx-drama-slide li.prev a").click(function () {
            var c = $(".xx-drama-poster ul>li:last"), b = $(".xx-drama-poster ul .current").index();
            $(".xx-drama-poster ul>li:first").before(c);
            $(".xx-drama-poster ul li").removeClass("current");
            $(".xx-drama-poster ul").find("li").eq(b).addClass("current");
        });
        //    slider
        $("#xx-list ul").find("li:first-child").addClass("cur");
        $("#xx-list ul").find("li:first-child span").addClass("native");
        var low=$(".knoledgement").offset();
        console.log(low.left);
        $('#xx-list ul li').click(function(){
            var left=$(this).offset();
//            console.log(left.left-low.left);
            var b=left.left-low.left;
            $(".panes").find(".sction").css("left",b);
            $(this).addClass('cur').siblings().removeClass('cur');
            $(this).children('span').addClass('native').parent('li').siblings().children('span').removeClass('native');
            $('.panes>div:eq('+$(this).index()+')').show().siblings().hide();
        })
        var i=14;
        var page=3;
        var page_last=Math.ceil($('#myscrollbox ul li').length/i);
        var jqWidth=$('#wrapper').width();
        console.log(jqWidth);


        var btn_lf = $("#xx-list .knowledge-btn .prev");
        var btn_rg = $("#xx-list .knowledge-btn .next");
        var problemul = $("#myscrollbox ul");
        var problemList = problemul.find("li");
        var length = problemList.length;
        var problemli = parseInt(problemList.css("width"));
        var problemMl =  parseInt(problemList.css("marginLeft"));
        var problemMr =  parseInt(problemList.css("marginRight"));
        var left = problemli + problemMl + problemMr + 5;
        var cur = $(".cur");
        var i=1;
        var left2;
        var low=$(".knoledgement").offset().left;
//        var lefst=$("#myscrollbox li.cur").offset().left;
//        var see= lefst-low;
        btn_lf.on('click',function() {
            if(i==0) {
                $(this).addClass("cur");
                i=1;
            }
            i--;
            left2 = i*left;
            $(this).siblings(".btn").removeClass("cur");

            if ($(this).hasClass("cur")){
                return false;
            } else {
                problemul.stop(true,true).animate({"marginLeft":-left2},100);
                return false;
            }
        });
        btn_rg.click(function() {
//            console.log(i);
            if(i == length-15) {
                i = length-15;
                $(this).addClass("cur");
                return false;
            }
            i++;
            left2 = i*left;
            $(this).siblings(".btn").removeClass("cur");
            if ($(this).hasClass("cur")){
                return false;
            } else {
                problemul.stop(true,true).animate({"marginLeft":-left2},100);
                if($("#myscrollbox li").hasClass("cur")){
                }else{

                }
                return false;
            }
        });
        function getQueryString(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }
        console.log(getQueryString("show"))
        if(getQueryString("show")==0){
            $(".xx-continue").css("display","none");
        }
        getNextStep();
        function getNextStep() {
            $.ajax({
                url: HOST + '/index/User/getUserNextModule',
                data: {
                    topicId: topicId,
//                    pre_module_type:$("input[name=module_type]").val()
                },
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    console.log(response)
                    if (!MY_UI.isEmpty(response)) {
                        if (response.is_end == 0) {
                            $(".xx-continue-inner").html("下一步");
//                            $("a.continue_next_step").attr('href',"http://"+response.url);
//                            $(".xx-continue").on("click", function () {
////                                window.open("http://"+response.url, "_self");
//                            });
                        } else if (response.is_end == 1) {
                            $(".xx-next").html("结束");
//            $(".page-over>a").trigger("click");
                            $("a.continue_next_step").css("display","none");
                            $(".xx-continue").on("click", function () {
                                $(".page-over>a").trigger("click");
                            });
                        }
                    }
                },
                complete: function () {

                }
            });
        }
//        $(".xx-subject-module li.learned").find("a").click(function(){
//            window.open(HOST+"index/index/preReport/topicId/"+topicId+"?show=0","_blank");
//        });
    });

</script>
<script src="{:loadResource('classba/assets/tools/xa.js')}"></script>
</body>
</html>