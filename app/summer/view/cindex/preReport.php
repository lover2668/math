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
    <!--    <link rel="stylesheet" href="__PUBLIC__/plugin/lib/labelauty/css/jquery-labelauty.css">-->
    <meta name="msapplication-TileColor" content="#0e90d2">
<!--    <link rel="stylesheet" href="__PUBLIC__/static/lib/css/amazeui.min.css">-->
    <!-- 选项按钮样式 -->
    <link rel="stylesheet" href="{:loadResource('plugin/lib/uploadfile/css/uploadfile.css')}">
    <link rel="stylesheet" href="{:loadResource('static/app/math_summer_l2/css/xc_style.css')}">
    <link href="{:loadResource('classba/app/math/css/header.css')}" rel="stylesheet">
<!--    <link rel="stylesheet" href="__PUBLIC__/math/css/style.css">-->
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
        .allKnowledge{
            margin-top: 30px;
            display: inline-block;
            width:50%;
        }
        .container .report-chart{
            /*width:70%;*/
        }
        @media only screen and (max-width: 1023px) {
        .allKnowledge{
            margin-left: 120px;
        }
        }
        .allKnowledge h3{
            margin:0;
        }
        .allKnowledge>div{
            margin-top: 10px;
        }
        .allKnowledge>div.xx-already>p{
            color: #7CDAED;
        }
        .allKnowledge>div.xx-nothing>p{
            color: #F9383C;
        }
        .allKnowledge>div>p{
            display: inline;
            font-weight: bold;
        }
        .allKnowledge>div>div>span{
            display: inline-block;
            padding:0 12px 0 0;
            font-size: 14px;
            border-right: 1px solid #BDEADA ;
            margin:0 10px 0 0;
        }
        .choose-options p,.choose-options div{
            display: inline !important;
        }
        .question-banner{
            clear: both;
        }
        .question-banner p{
            padding: 0;
        }
        .question-step .MJXc-display{
            display: inline;
        }
        .xx-containers p span.MJXc-display{
            display: inline !important;
        }
    </style>
</head>
<body>
<div class="xx-header">
    <div class="xx-navbar">
        <div class="xx-navbar-left xx-navbar-logo">
        </div>
        <div class="xx-navbar-left xx-nav"></div>
        <ul class="xx-navbar-right xx-navbar-menu">
            <li class="xx-navbar-left xx-report">
                {php}
                if($is_show_report)
                {
                    echo "<i class='xx-icon'>&#xe656;</i>&nbsp;我的报告";
                }
                {/php}
            </li>
<!--                        <div class="hover-li">点击此处可查看所有本课程学习报告<i class="yxiconfont">&#xe65f;</i></div>-->
            <li class="xx-navbar-left xx-account"><i class="xx-icon">&#xe65b;</i>&nbsp;<?php echo session("real_name")?session("real_name"):session("username") ?></li>
        </ul>
    </div>
</div>
<div class="container">
    <div class="am-g">
        <div class="xx-content xx-title" id="title-report">
            <h1>测试报告</h1>
        </div>
        <div class="xx-content xx-frame" id="xx-report">
            <div class="report-chart">
                <div id="main" ></div>

            </div>
            <div class="allKnowledge">
                <h3>知识点总体掌握率</h3>
                <!--                    <div >-->
                <!--                        <p><i></i><b class="already"></b><span>{$has_learned_num}</span>个知识点已掌握</p>-->
                <!--                        <p><i></i><b class="nothing"></b><span>{$weakElements_num}</span>个知识点未掌握</p>-->
                <!--                    </div>-->
                <div class="xx-already">
                    <p>已掌握知识点&nbsp;&nbsp;{$has_learned_num}</p>
                    <div class="already-options">
                        {foreach name="knowledgeList_tag_name" item="vo"}
                        <span>{$vo.tag_name}</span>
                        {/foreach}
<!--                        <span>二次根式的乘法</span>
                        <span>幂的运算</span>
                        <span>二次函数</span>
                        <span>二次根式的性质</span>
                        <span>二次根式的除法</span>
                        <span>二次根式的性质</span>
                        <span>二次根式的除法</span>-->
                    </div>
                </div>
                <div class="xx-nothing">
                    <p>未掌握知识点&nbsp;&nbsp;{$weakElements_num}</p>
                    <div class="nothing-options">
                         {foreach name="weakElements_tag_name" item="vo"}
                        <span>{$vo.tag_name}</span>
                        {/foreach}
<!--                        <span>二次根式的乘法</span>
                        <span>幂的运算</span>
                        <span>二次函数</span>
                        <span>二次根式的性质</span>
                        <span>二次根式的除法</span>-->
                    </div>
                </div>
            </div>
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
                    </h2>

                </div>
            </div>
        </div>

        <div class="xx-content" id="xx-list">
            <div class="fc-nav scrollspy-nav" style="padding:20px;min-height: 220px;background-color: #fff;margin: 0px; border: 1px solid rgb(106, 206, 167); border-radius: 6px; position: relative" data-am-scrollspynav="{offsetTop: 45}" data-am-sticky>
                <div id="xx-learn-status-rate" style="width:220px;height:220px;float: left"></div>
                <span style="top: 20px;font-size:18px ;font-family: 'MicrosoftYaHei';font-weight: bold">答题正答率</span>
                <ul class="" style="border: none;margin-top: 30px;">

                    {volist name="has_answered_questions" id="vo" key="k" }
                    <li class="{if condition="$vo.is_right eq 1"}right{else/}error{/if}  "><a href="javascript:;">{$k}</a><span></span></li>
                    {/volist}
                </ul>
            </div>

                            <div class="panes">
                                {volist name="has_answered_questions" id="vo" key="k" }
                                     <div class="pane" {if condition="$k eq 1"}style="display:block;"{/if}>
                                    <div class="subject">
                                        <div class="xx-list-question">
                                            <span>第{$k}题</span>
                                        </div>
                                        <div class="xx-containers">
<!--                                            <p>-->
<!--                                                试题ID：{$vo.id}-->
<!--                                            </p>-->
                                                            {$vo.tag_name}
                                            <h3>{$vo.content|html_replace}
                                            </h3>
                                            <div>
                                                {if condition="$vo.q_type eq 1"}
                                                {foreach name="vo.options" item="answer"}
                                                <div class="am-u-lg-6 choose-options">
                                                {$answer.key}: {$answer.answer|htmlspecialchars_decode}
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
                                                 {$con.content|htmlspecialchars_decode}
                                                {/volist}
                                                {/volist}
                                        </div>
                                    </div>
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
                 {if condition="$topicId==106||$topicId==142||$topicId==151||$topicId==106||$topicId==115||$topicId==124||$topicId==159||$topicId==169||$topicId==160||$topicId==213"}
                 {else}
                 <a class="continue_next_step" href="{url link="Index/zhlx/index"  vars="topicId=$topicId" suffix='true' domain='true'}">进入竞赛拓展</a>
                 {/if}
                
            </div>
        </div>
    </div>
    <a  data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 1, width: 600, height: 600}"></a>
</div>

{php}
    if($is_show_nextstep)
    {
        echo " <div class=\"xx-continue\"> <div class=\"xx-next\">下一步</div> </div>";
    }
{/php}

<input type="hidden" name="topicId" value="{$topicId}">
<input type="hidden" name="accuracy" value="{$accuracy}">
<input type="hidden" name="is_all_right" value="{$is_all_right}"/>
<input type="hidden" id="weakElements_num" value="{$weakElements_num}" />
<input type="hidden" name="module_type" value="{$module_type}" />
<!--<script type="text/javascript"-->
<!--        src="http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">-->
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
<script src="{:loadResource('static/lib/js/my.ui.js')}"></script>
<script src="{:loadResource('plugin/lib/echarts/echarts.min.js')}"></script>
<script src="{:loadResource('static/app/math_summer_l2/js/config.js')}"></script>
<script src="{:loadResource('static/app/math_summer_l2/js/class.Logout.js')}"></script>
<script src="{:loadResource('static/lib/js/jquery-form.js')}"></script>
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
            text: {$has_learned_percent} + '%',
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
                    {value:{$has_learned_num}, name:'{$has_learned_num}个知识点已掌握'},
                    {value:{$weakElements_num}, name:'{$weakElements_num}个知识点未掌握'}
                ]
            }
        ]
    };
    myChart.setOption(option);
    var accuracy=$("input[name=accuracy]").val();
    var allq=$(".fc-nav").find("li").length,
        alle=$(".fc-nav").find("li.error").length,
        allr=allq-alle;
    console.log(allq);
    var myChartRate = echarts.init(document.getElementById("xx-learn-status-rate"));
    optionRate = {
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
                    {value:allr, name:'{$has_learned_num}个知识点已掌握'},
                    {value:alle, name:'{$weakElements_num}个知识点未掌握'}
                ]
            }
        ]
    };
    myChartRate.setOption(optionRate);
//    $('.am-slider').flexslider({itemWidth: 60, itemMargin: 100, slideshow: true});
    $(function(){
        var logout   = new Logout( '#collapse-head');
        $("#xx-list ul").find("li:first-child").addClass("cur");
//        $("#xx-list ul").find("li:first-child span").addClass("native");
        $('#xx-list ul li').click(function(){
            $(this).addClass('cur').siblings().removeClass('cur');
//            $(this).children('span').addClass('native').parent('li').siblings().children('span').removeClass('native');
            $('.panes>div:eq('+$(this).index()+')').show().siblings().hide();
        });
        $("#xx-list ul").find("li:first-child").trigger("click");
        var topicId = $("input[name=topicId]").val();
        getNextStep();
        var is_all_right = $("input[name=is_all_right]").val();
        function getQueryString(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }
        console.log(getQueryString("show"))
        if(getQueryString("show")==0){
            $(".xx-continue").css("display","none");
        }
        function getNextStep() {
            $.ajax({
                url: HOST + '/summer/User/getUserNextModule',
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
                            $(".xx-continue").on("click", function () {
                                window.open(response.url, "_self");
                            });
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
    });

</script>
<script>
    var topicId = $("input[name=topicId]").val();
    $(".xx-report").on("click",function(){
        window.open("http://"+window.location.host+"/index.php/"+"/summer/index/reportCenter/topicId/"+topicId,"_blank")
    })
</script>
<script src="{:loadResource('classba/assets/tools/xa.js')}"></script>
</body>
</html>