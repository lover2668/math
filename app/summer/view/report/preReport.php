<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>寒假课报告页</title>
    <meta name="keywords" content="上海乂学教育科技有限公司-暑期课系列"/>
    <meta name="description" content="上海乂学教育科技有限公司-暑期课程系列"/>
    <meta name="renderer" content="webkit">
    <meta name="force-rendering" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{:loadResource('classba/app/common/yx_font/iconfont.css')}" rel="stylesheet">
    <link href="{:loadResource('classba/app/common/css/yx_report.css')}" rel="stylesheet">
    <style>
        .panel-content{
            position: relative;
        }
        .chart-panel-style{
            height:1200px;
            overflow: hidden;
        }
        .panel-content>button{
            width:60px;
            height:32px;
            color: #2dc893;
            position: absolute;
            right:10px;
            bottom:0;
            border:1px solid #2dc893;
            background-color: transparent;
            cursor: pointer;
            border-radius: 4px;
        }
        *{
            list-style: none;
        }
        .panel-icon{
            height:30px;
            padding: 0 32px;
        }
        .panel-icon li{
            display: inline-block;
            font-size: 14px;
            margin-right: 20px;
            margin-top: 20px;
        }
        .panel-icon li>span{
            display: inline-block;
            width:10px;
            height:10px;
            border-radius: 100%;
        }
        .panel-icon li.already-point>span{
            border:1px solid #68e695;
        }
        .panel-icon li.no-point>span{
             border:1px solid red;
         }
        .panel-icon li.no-study-point>span{
            border:1px solid #000;
        }
    </style>
</head>
<body>
<div class="title_page">
    <p class="course-name">{$topic_name}</p>

    <p class="report-name">基础先行测试报告</p>

    <p class="en-report-name">BASIC PRE-TEST REPORT</p>

    <div class="user-info">
        <span class="user-id">姓名：{$user_name} </span>
        <span class="">｜</span>
        <span class="time">日期：{$date_time}</span>
    </div>
    <img src="{:url('qrCode',['url'=>$url_code])}"
         style="width:70px;height:70px;top: 815px;position: relative;left:50%;margin-left: -35px;">
</div>
<div class="page_next"></div>
<div class="page page_1">
    <div class="section_title" id="page_1_section_title">
        <div class="section_title_line">
            <span class="section_title_text">总体作答情况</span>
        </div>
    </div>
    <div class="section_content">
        <div class="section-cell section-cell-left">
            <p class="section-cell-top">我的用时</p>

            <p class="section-cell-middle">{$spent_time}</p>

            <p class="section-cell-bottom">建议用时：{$estimates_time}</p>
        </div>
        <div class="section-cell section-cell-right">
            <p class="section-cell-top">答题效率</p>

            <p class="section-cell-middle">{$xiaolv}</p>

            <p class="section-cell-bottom">0.1~0.3 优秀｜0.4~0.6 良好｜0.7~1 需努力</p>
        </div>
    </div>
    <div class="section_title">
        <div class="section_title_line">
            <span class="section_title_text">知识点掌握情况</span>
        </div>
    </div>
    <div class="section_content section_1">
        <div class="section-tag-charts">
            <div class="section-charts" id="section-charts">
                <div class="wrap">
                    <div class="circle">
                        <div class="section-tag-percent left"></div>
                        <div class="section-tag-percent right wth0"></div>
                    </div>
                    <div class="section-tag-num"><p></p></div>
                </div>
            </div>
            <div class="section-detail">
                <p><span class="section-detail-left">全部</span><span
                        class="section-detail-right">{$knowledge_list_num}</span></p>

                <p><span class="section-detail-left">已掌握</span><span class="section-detail-right">{$new_knowledgeList_num}</span>
                </p>

                <p><span class="section-detail-left">未掌握</span><span
                        class="section-detail-right">{$weakElements_num}</span></p>
            </div>
        </div>
        <hr/>
<!--        <div class="section-tag">-->
<!--            <p>未掌握知识点</p>-->
<!---->
<!--            <div class="tag-list">-->
<!--                {volist name="weakElements" id="vo" key="k" }-->
<!--                <span>{$tag_names[$vo]}</span>-->
<!--                {/volist}-->
<!--            </div>-->
<!--            <p style="margin-top: 34px;">已掌握知识点</p>-->
<!---->
<!--            <div class="tag-list learned">-->
<!--                {volist name="right_list" id="vo" key="k" }-->
<!--                <span>{$tag_names[$vo]}</span>-->
<!--                {/volist}-->
<!--            </div>-->
<!--        </div>-->
<!--        <hr/>-->
        <div class="panel-content" style="">
            <div class="panel-icon">
                <ul>
                    <li class="already-point">已掌握的知识点：<span></span></li>
                    <li class="no-point">未掌握知识点：<span></span></li>
                    <li class="no-study-point">未学的知识点：<span></span></li>
                </ul>
            </div>
            <div class="section-tag" id="chart-panel" style="width: 728px;">

            </div>

        </div>

        <hr/>
        <div class="section-tag">
            <p>纳米级知识点掌握程度</p>
            <table width="666">
                <thead>
                <tr>
                    <td style="width: 10%">序号</td>
                    <td style="width: 35%">知识点名称</td>
                    <td style="width: 55%">掌握程度</td>
                </tr>
                </thead>
                <tbody>
                {volist name="user_ability_info" id="user_ability" key="k" }
                <tr>
                    <td>{$k}</td>
                    <td>{$tag_names[$user_ability["tag_code"]]}</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar" style="width: {$user_ability['ability']}%">
                                <span class="progress-content">{$user_ability["ability"]}%</span>
                            </div>
                        </div>
                    </td>
                </tr>
                {/volist}


                </tbody>
            </table>
        </div>
    </div>
    <div class="page_next"></div>
    <div class="section_title" style="margin-top: 48px;">
        <div class="section_title_line">
            <span class="section_title_text">题目分析报告</span>
        </div>
    </div>
    <div class="section_content section_2">
        <div class="section-tag" style="padding-top: 0">
            <div class="section-tag-charts">
                <div class="section-charts" id="section-2-charts">
                    <div class="wrap">
                        <div class="circle">
                            <div class="section-tag-percent left"></div>
                            <div class="section-tag-percent right wth0"></div>
                        </div>
                        <div class="section-tag-num"><p></p></div>
                    </div>
                </div>
                <div class="section-detail">
                    <p><span class="section-detail-left">共作答</span><span class="section-detail-right">{$sum_num}</span>
                    </p>
                    <!--<p>考察能力：空间想象能力</p>-->
                    <p><span class="section-detail-left">答对题数</span><span
                            class="section-detail-right">{$right_num}</span></p>

                    <p><span class="section-detail-left">答错题数</span><span class="section-detail-right">{$sum_num-$right_num}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page page_2">
    <div class="question-list-title">我的答题本<span class="question-list-title-tip">默认显示5题</span></div>
    <div class="section_content section_3">
        <?php
        $end_num = 5;
        if ($sum_num < 5)
            $end_num = $sum_num;
        ?>
        {for start="0" end="$end_num" name="k"}
        <?php $vo = $hasAnswerQuestions[$k] ?>
        <div class="question_id_type">{$k+1}&nbsp;{if condition="$vo.q_type eq 1"}
            选择题
            {elseif condition="$vo.q_type eq 2"/}
            填空题
            {else /}
            {/if}
        </div>
        <div class="question_tag">
            <p>考察知识点：{$tag_names[$vo["tag_code"]]}</p>
<!--            <span class="">｜</span>

            <p>考察能力：空间想象能力</p>-->

            <p>难度
                {for start="0" end="$vo['difficulty']"}
                <i class="xx-icon">&#xe655;</i>
                {/for}
                {for start="$vo['difficulty']" end="9"}
                <i class="xx-icon">&#xe654;</i>
                {/for}
            </p>
        </div>
        <div class="question_title">{$vo.content|html_replace}
            {if condition="$vo.q_type eq 1"}
            {foreach name="vo.options" item="answer"}
            <div class="am-u-lg-6 choose-options" style="padding: 5px 10px;">
                {$answer.key}: {$answer.answer|htmlspecialchars_decode}
            </div>
            {/foreach}
            {elseif condition="$vo.q_type eq 2"/}

            {else /}
            {/if}


        </div>
        <div class="question_analyse">
            <p class="question_analyse_title">分步解析</p>
            <ul>
                {volist name="vo.analyze" id="anal" }
                <?php $num = count($anal['content']); ?>
                {volist name="anal.content" id="con" key="i" }
                <li>
                    <div class="media">
                        <div class="media-left"><span class="label xx-label-step">{if condition="$num eq 1"}{else/}步骤{$i}/{$num}{/if}</span>
                        </div>
                        <div class="media-body"><p>{$con.content|htmlspecialchars_decode}</p></div>
                    </div>
                </li>

                {/volist}
                {/volist}
                <li>
                    <div class="media">
                        <div class="media-left"><span class="label xx-label-step">正确答案</span></div>
                        <div class="media-body">{if condition="$vo.q_type eq 1"}
                            {$vo.answer}
                            {/if}
                            {if condition="$vo.q_type eq 2"}
                            {assign name="i" value="1" /}
                            {assign name="j" value="1" /}
                            {volist name="vo.answer_base64" key="blank_num" id="ans" }
                            {volist name="ans" key="answer_num" id="an" }
                            {if condition="strstr($an,'png;base64')"}
                            <img src="{$an}"/>
                            {else/}
                            {$an}
                            {/if}

                            {if condition="$i neq $answer_num"}
                            {/if}
                            {/volist}
                            {if condition="$j neq $answer_num"}

                            {/if}
                            {/volist}
                            {/if}
                        </div>
                    </div>
                </li>
                <li>
                    <div class="media">
                        <div class="media-left"><span class="label xx-label-step">你的答案</span></div>
                        <div class="media-body"><span class="{if condition=" $vo.is_right eq 1"}answer_right{else/}answer_wrong{/if}">
                            {if condition="$vo.q_type eq 1"}
                            {if condition="$vo.user_answer"}
                            {$vo.user_answer}
                            {else/}
                            未作答
                            {/if}
                            {/if}
                            {if condition="$vo.q_type eq 2"}
                            {if condition="$vo.user_answer_base64"}
                            {volist name="vo.user_answer_base64" key="user_answer_base64_item_key"
                            id="user_answer_base64_item"}
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
                            {if condition=" $vo.is_right eq 1"}<i class="xx-icon" style="color:#26b987">&#xe651;</i>{else/}<i
                                class="xx-icon" style="color:red">&#xe659;</i>{/if}
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!--                        <hr style="position: relative;width: 728px;margin-left: -33px;"/>-->
        {/for}
    </div>
</div>
<div class="fixed">
    <ul>
        <li id="print"><i class="xx-icon">&#xe64d;</i>打印报告</li>
        <!--<li><i class="yxiconfont">&#xe653;</i>分享报告</li>-->
        <li id="return-top"><i class="xx-icon">&#xe65d;</i>返回顶部</li>
    </ul>
</div>
<div class="report-footer">
    <a href="{:url('Report/reportDetail',['topicId'=>$topicId,'user_id'=>$user_id,'submodule_type'=>$submodule_type,'batch_num'=>$batch_num,'report_num'=>2])}">在线查看更多题目详情
        <i class="xx-icon">&#xe663;</i></a>
    <span style="font-size: 32px;" class="">｜</span>
    <span style="position: relative;top: -5px;" class="">智适应教育<i class="xx-icon">&#xe670;</i></span>
</div>
<input type="hidden" value="{$url}" name="page_url" data-text="页面url">
<input type="hidden" value="{$zhangwolv}" data-text="知识点掌握率">
<input type="hidden" value="{$accuracy}" data-text="正答率">
<input type="hidden" value='{$chapter_data}' name="chapter_data" data-text="章节数据">

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
<script src="{:loadResource('classba/assets/jquery/jquery.js')}"></script>
<script src="{:loadResource('classba/assets/tools/classba.ui.js')}"></script>
<script language="javascript" src="{:loadResource('classba/assets/echarts/echarts.mins.js')}"></script>
<!--<script language="javascript" src="__PUBLIC__/classba/assets/jqprint/jQuery.print.js"></script>-->
<script>
    (function ($) {
        //                    $(".title_page").print();
//                    var myChart = echarts.init(document.getElementById('section-brume-charts'));
//                    var brume_option = {
//                        title: {
//                            text: ''
//                        },
//                        tooltip: {
//                            trigger: 'axis'
//                        },
//                        legend: {
//                            show: false,
//                        },
//                        toolbox: {
//                            show: false,
//                            //                feature : {
//                            //                    mark : {show: true},
//                            //                    dataView : {show: true, readOnly: false},
//                            //                    restore : {show: true},
//                            //                    saveAsImage : {show: true}
//                            //                }
//                        },
//                        calculable: true,
//                        polar: [
//                            {
//                                indicator: [
//                                    {text: '数感', max: 100},
//                                    {text: '空间意识', max: 100},
//                                    {text: '运算能力', max: 100},
//                                    {text: '推理能力', max: 100},
//                                    {text: '应用意识', max: 100},
//                                    {text: '创新意识', max: 100},
//                                    {text: '模型思想', max: 100},
//                                    {text: '数据分析观念', max: 100},
//                                    {text: '几何直观', max: 100},
//                                    {text: '符号意识', max: 100}
//                                ],
//                                radius: 130
//                            }
//                        ],
//                        series: [
//                            {
//                                name: '布鲁姆认知层次图',
//                                type: 'radar',
//                                itemStyle: {
//                                    normal: {
//                                        areaStyle: {
//                                            type: 'default'
//                                        }
//                                    }
//                                },
//                                data: [
//                                    {
//                                        value: [97, 42, 88, 94, 90, 86],
//                                        name: '布鲁姆认知层次图'
//                                    }
//                                ]
//                            }
//                        ]
//                    };
//                    myChart.setOption(brume_option);
        var perRight = Math.round({$right_num} * 100 / {$sum_num})
        var myChart = echarts.init(document.getElementById('section-2-charts'));
        option = {
            //标题组件，包含主标题和副标题。
            title: {
                text: perRight + '%',
                x: 'center',
                y: 'center',
                textStyle: {
                    color: '#333',
                    fontWeight: 'bolder',
                    fontSize: 26,
                }
            },
            color: ['#16cc6c', '#ECEAE8'],
            series: [
                {
                    name: '答题正确率',
                    type: 'pie',
                    radius: ['78%', '100%'],
                    avoidLabelOverlap: true,
                    legendHoverLink: false,
                    hoverAnimation: false,
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
                        },
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data: [
                        {value: {$right_num}, name: '答对题数'},
                        {value: {$sum_num-$right_num}, name: '答错题数'},
                    ]
                }
            ]
        };
        myChart.setOption(option);
        var perlearned = Math.round({$new_knowledgeList_num} * 100 / {$knowledge_list_num})
        var learnedChart = echarts.init(document.getElementById('section-charts'));
        optionLearn = {
            //标题组件，包含主标题和副标题。
            title: {
                text: perlearned + '%',
                x: 'center',
                y: 'center',
                textStyle: {
                    color: '#333',
                    fontWeight: 'bolder',
                    fontSize: 26,
                }
            },
            color: ['#16cc6c', '#ECEAE8'],
            series: [
                {
                    name: '答题正确率',
                    type: 'pie',
                    radius: ['78%', '100%'],
                    avoidLabelOverlap: true,
                    legendHoverLink: false,
                    hoverAnimation: false,
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
                        },
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data: [
                        {value: {$new_knowledgeList_num}, name: '掌握个知识点'},
                        {value: {$weakElements_num}, name: '未掌握个知识点'},
                    ]
                }
            ]
        };
        learnedChart.setOption(optionLearn);

        var data2 = $('input[name=chapter_data]').val();
        var borderColor1={
            "normal":{
            "borderColor":"red",
            "borderWidth":1,
            "borderType":"solid"
            }
        },
            borderColor2={
                "normal":{
                    "borderColor":"#68e695",
                    "borderWidth":1,
                    "borderType":"solid"
                }
            }
        if(!MY_UI.isEmpty(data2)){
            data2 = eval("("+data2+")");
            var num=parseInt(data2.num),height=num*40;
            $("#chart-panel").height(height);
            var content=data2.children;
            console.log(content);
            for(var i=0;i<content.length;i++){
                for(var j=0;j<content[i].children.length;j++){
                    if(content[i].children[j].name.length>11){
                        var str=content[i].children[j].name.substring(0,10)+"...";
                        content[i].children[j].name=str;
                    }
                    if(content[i].children[j].status==1){
                        content[i].children[j].itemStyle=borderColor1;
                    }else if(content[i].children[j].status==2){
                        content[i].children[j].itemStyle=borderColor2;
                    }
                }
            }
            //树形图
            var chartPanel=echarts.init(document.getElementById('chart-panel'));
            chartPanel.showLoading();
            chartPanel.hideLoading();

            chartPanel.setOption(option = {
                tooltip: {
                    trigger: 'item',
                    formatter: "{c}"
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                series: [

                    {
                        type: 'tree',
//                name: 'tree2',
                        data: [data2],

                        top: '0',
                        //left: '5%',
//                bottom: '22%',
//                right: '18%',
                        itemStyle: {
                            normal: {
                                color: {
                                    image: 'image://http://img.ph.126.net/wIjj_obvlVrnFJuHG5eAvA==/1509550300099644221.jpg', // 支持为 HTMLImageElement, HTMLCanvasElement，不支持路径字符串
                                    repeat: 'no-repeat' // 是否平铺, 可以是 'repeat-x', 'repeat-y', 'no-repeat'
                                },
                                borderColor: '#000',
                                borderWidth: 1,
                                borderType: 'solid',
                            }
                        },
                        symbolSize: 10,
                        initialTreeDepth: 10,
                        expandAndCollapse:false,
                        label: {
                            normal: {
                                position: 'bottom',
                                //verticalAlign: 'middle',
                                align: 'center'
                            }
                        }
                    }
                ]
            });
//            if($("#chart-panel").height()>1200){
//                $(".panel-content").addClass("chart-panel-style").append('<button class="chart-panel-btn" id="chart-btn">展开</button>');
//                $("#chart-btn").on("click",function(){
//                    if($(".panel-content").hasClass("chart-panel-style")){
//                        $(this).text("收起");
//                        $(".panel-content").removeClass("chart-panel-style");
//                    }else{
//                        $(this).text("展开");
//                        $(".panel-content").addClass("chart-panel-style");
//                    }
//                })
//            }
        }
        $("#print").click(function () {
            // 打印
            window.print();
        });
        $(window).scroll(function(){
            var top = 400+$(window).scrollTop();
            $(".fixed").css("top",top+"px");
            /*实时获取滚动条的高度，然后不断的变换absolute的top值，从而达到与fixed同等的效果*/
        });
        //返回顶部
        $("#return-top").click(function () {
            //var sc=$(window).scrollTop();
            $('body,html').animate({scrollTop: 0}, 500);
        })
    })(jQuery)

</script>
<!-- JiaThis Button BEGIN -->
<script type="text/javascript">
    var page_url = $("[name=page_url]").val()
    var jiathis_config = {
        url: page_url,
        data_track_clickback: true,
        summary: "",
        showClose: true,
        shortUrl: false,
        hideMore: false
    }
</script>
<script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?uid=2135407&btn=r.gif&move=1"
        charset="utf-8"></script>
<!-- JiaThis Button END -->
<script src="{:loadResource('classba/assets/tools/xa.js')}"></script>
</body>
</html>