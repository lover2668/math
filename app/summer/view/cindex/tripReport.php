<!doctype html>
<html>

<head>
    <meta charset="utf-8"/>
    <title>测评报告 - {$cePingKeMu}</title>
    <link rel="icon" type="image/png" href="__PUBLIC__/plugin/lib/i/yixue-tt-logo.png">
    <link rel='stylesheet' href="__PUBLIC__/trip/js/fa/css/font-awesome.min.css"/>
    <link rel='stylesheet' href="__PUBLIC__/trip/style.css"/>
</head>

<body>
<div id="cover">
    <div id="cover_top" class="cover_bg">
        <div id="cover_info">
            <table class="info_table">
                <tr>
                    <td class="text_right">测评科目</td>
                    <td><img src="__PUBLIC__/trip/img/crossing.png"></td>
                    <td>{$cePingKeMu}</td>
                </tr>
                <tr>
                    <td class="text_right">测评内容</td>
                    <td><img src="__PUBLIC__/trip/img/crossing.png"></td>
                    <td>{$cePingContent}</td>
                </tr>
                <tr>
                    <td class="text_right">帐号</td>
                    <td><img src="__PUBLIC__/trip/img/crossing.png"></td>
                    <td>{$userName}</td>
                </tr>
                <tr>
                    <td class="text_right">日期</td>
                    <td><img src="__PUBLIC__/trip/img/crossing.png"></td>
                    <td>{$cePingDate}</td>
                </tr>
            </table>
        </div>
    </div>
    <div id="cover_title">
        <img src="__PUBLIC__/trip/img/title.png" id="cover_title_img">
    </div>
    <div id="cover_bottom"></div>
</div>

<div class="page_next"></div>

<div id="page_1" class="page">
    <div id="page_1_title" class="page_title">学科测试分析报告</div>
    <div id="page_1_chart" class="page_box">
        <div id="page_1_pie" class="page_box_inner">
            <div class="chart_div">
                <div class="chart_title">知识点掌握率</div>
                <div id="page_1_pie1" style="width:80%;height:300px;text-align:center;"></div>
            </div>
            <div class="chart_div">
                <div class="chart_title">正答率</div>
                <div id="page_1_pie2" style="width:80%;height:300px;text-align:center;"></div>
            </div>

        </div>
        <div style="clear:both"></div>
        <div class="page_box_text">
            <span class="text_label">测试简评：</span>
            <span id="page_1_pie_text" class="text_content">
                <br/>
                <br/>
            {volist name="jianPing" id="jianPingItem"}
                    {$jianPingItem}<br/>
                {/volist}
            </span>
        </div>
    </div>
    <div id="page_1_list" class="page_box" style="margin-top:15px;">
        <div class="list_title">纳米级知识点掌握情况</div>
        <table class="table_list table_stripped">
            <thead>
            <tr>
                <th width="40">序</th>
                <th>知识点名称</th>
                <th width="250">掌握程度</th>
            </tr>
            </thead>
            <tbody>
            {volist name="allKnowledge" id="allKnowledgeItem"}
            <tr>
                <td>{$key+1}</td>
                <td>{$allKnowledgeItem.tag_name}</td>
                <td class="star">
                    {switch name="allKnowledgeItem.rating"}
                        {case value="0"}
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        {/case}
                        {case value="1"}
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-o"></i>
                            <i class="fa fa-star-o"></i>
                        {/case}
                        {case value="2"}
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star-o"></i>
                        {/case}
                        {case value="3"}
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        {/case}
                    {/switch}


                </td>
            </tr>
            {/volist}
            </tbody>
        </table>
    </div>
    <div id="page_1_legend" class="page_legend" style="margin-top:15px;">
        <div class="list_title main_color main_color_border font_bigger">图例说明</div>
        <table class="table_legend">
            <tr>
                <td class="star font_big"><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i
                        class="fa fa-star-o"></i></td>
            </tr>
            <tr>
                <td class="font_big">
                    这次测试的知识点没有掌握，对知识点的认识比较单薄，方法的运用有所欠缺。
                </td>
            </tr>

            <tr>
                <td class="star font_big"><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i
                        class="fa fa-star-o"></i></td>
            </tr>
            <tr>
                <td class="font_big">
                    这次测试的知识点掌握情况略有不足，对知识点基本理解，但是对方法的应用不够灵活，导致在做题时较多出现知识和方法的盲区。
                </td>
            </tr>

            <tr>
                <td class="star font_big"><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                        class="fa fa-star-o"></i></td>
            </tr>
            <tr>
                <td class="font_big">
                    这次测试的知识点掌握得较好，除一些综合拔高题之外，一般的题目已经可以从容解决，但还需要提高综合应用能力以及思维创新能力。
                </td>
            </tr>

            <tr>
                <td class="star font_big"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                </td>
            </tr>
            <tr>
                <td class="font_big">
                    这次测试的知识点掌握得非常好，接下来你可以在知识的拓展，引申和迁移上多加努力，形成一个系统的数学知识体系，达到灵活运用举一反三的境界哦。
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="page_next"></div>
<div id="page_2" class="page">
    <div id="page_2_title" class="page_title">测试题目解析</div>
    <div id="page_2_question_index" class="page_box no_padding">
        <div class="list_title" style="margin:15px 20px;">正答率<span id="page_2_right_ratio">{$rightPercent}%</span></div>
        <div id="page_2_question_index_list" class="page_index_wrap">
            <ul>
                {volist name="has_answered_questions" id="vo" key="k" }
                <li>
                    <span class="li_up">{$k}</span>
                    {if condition="$vo.is_right eq 1"}
                    <span class="li_down right"><i class="fa fa-check"></i></span>
                    {else /}
                    <span class="li_down wrong"><i class="fa fa-close"></i></span>
                    {/if}
                </li>

                {/volist}
            </ul>
        </div>
        <div style="clear:both"></div>
    </div>

    <div id="page_2_question" class="page_box no_padding" style="margin-top:15px;">
        <table style="width:100%;">
            {volist name="has_answered_questions" id="vo" key="k" }
            <tr>
                <td style="width:50px;background-color:#ECF8F8;">
                    <span class="question_index">{$k}</span>
                </td>
                <td>
                    <table style="width:100%;">
                        <tr>
                            <td style="padding:10px 15px;">
                                {$vo.content|html_replace}

                                {if condition="$vo.q_type eq 1"}
                                <div class="am-g question-option">
                                    {foreach name="vo.options" item="answer"}
                                    <div class="am-u-lg-6">
                                        {$answer.key}: {$answer.answer|htmlspecialchars_decode}
                                    </div>
                                    {/foreach}
                                </div>
                                {elseif condition="$vo.q_type eq 2"/}

                                {else /}

                                {/if}
                            </td>
                        </tr>


                        <tr>
                            <td style="padding:10px 15px;">
                                <table>
                                    <tr>
                                        <td style="width:60px;">
                                            <span class="border_span">你的答案</span>
                                        </td>
                                        <td style="padding:0 10px;">
                                            <span class="{if condition=" $vo.is_right eq 1"}right{else/}wrong{/if}">
                                            {if condition="$vo.q_type eq 1"}
                                            {$vo.user_answer}
                                            {/if}

                                            {if condition="$vo.q_type eq 2"}
                                            {if condition="$vo.user_answer_base64 neq '' "}
                                            {volist name="vo.user_answer_base64" id="user_answer_base64_item"}
                                            <img src="{$user_answer_base64_item}"/>
                                            {/volist}
                                            {/if}
                                            {/if}

                                            </span>
                                        </td>
                                        <td style="padding:0 10px;">
                                            {if condition="$vo.is_right eq 1"}
                                            <i class="fa fa-check right"></i>
                                            {else/}
                                            <i class="fa fa-close wrong"></i>
                                            {/if}


                                        </td>

                                        <td style="padding:0 10px;padding-left:30px;">
                                            <span class="border_span">参考答案</span>
                                        </td>
                                        <td style="padding:0 10px;" class="right">
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
                                            <!--|-->
                                            {/if}

                                            {/volist}
                                            {if condition="$j neq  $answer_num"}
                                            <!--,-->
                                            {/if}

                                            {/volist}

                                            {/if}

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px 15px;">
                                <table>
                                    <tr>
                                        <td style="padding-right:20px;">
                                            <span class="border_span" style="font-weight:bold;">解析</span>
                                        </td>
                                        <td>
                                            {volist name="vo.analyze" id="anal"  }
                                            {volist name="anal.content" id="con" key="i" }
                                                第{$i}步：{$con.content|htmlspecialchars_decode}<br>
                                            {/volist}
                                            {/volist}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100%;padding:10px 0;background-color:#F3FDFC;">
                                <table style="width:100%;text-align:center;margin:10px 0;">
                                    <tr>
                                        <td style="border-right:1px solid #ccc;width:25%;">
                                            <span class="span_label">用时</span>
                                            <span class="span_value">{:dateFormat($vo.stime,$vo.ctime)}</span>
                                        </td>
                                        <td style="border-right:1px solid #ccc;width:25%;">
                                            <span class="span_label">考察知识点</span>
                                            <span class="span_value">{$vo.tag_name}</span>
                                        </td>
                                        <td style="border-right:1px solid #ccc;width:25%;">
                                            <span class="span_label">先行知识点</span>
                                            <span class="span_value">

                                                {if condition="$vo.prevCode"}
                                                {:implode("、",$vo.prevCode)}
                                                {else/}
                                                暂无
                                                {/if}

                                            </span>
                                        </td>
                                        <td style="width:25%;">
                                            <span class="span_label">后行知识点</span>
                                            <span class="span_value">
                                                {if condition="$vo.afterCode"}
                                                {:implode("、",$vo.afterCode)}
                                                {else/}
                                                暂无
                                                {/if}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            {/volist}
        </table>
    </div>
</div>

<div class="page_next"></div>

<div id="page_3" class="page">
    <div id="page_3_title" class="page_title">测试题目解析</div>
    <div id="page_3_text1" class="page_box no_padding">
        <table style="width:100%;">
            <tr>
                <td style="width:100%;padding:15px 20px;color:#fff;background-color:#14B5AD;">
                    <span
                        style="display:inline-block;padding:0 5px;border-left:1px solid #fff;border-right:1px solid #fff;margin-right:10px;">总结分析</span>
                    对本次测试水平的分析
                </td>
            </tr>
            <tr>
                <td style="width:100%;min-height:200px;padding:10px;" id="page3_text1">
                    <br/>
                    <br/>
                    {volist name="summary" id="summaryItem"}
                    {$summaryItem}<br/>
                    {/volist}
                </td>
            </tr>
        </table>
    </div>
    <div id="page_3_text2" class="page_box no_padding" style="margin-top:20px;">
        <table style="width:100%;">
            <tr>
                <td style="width:100%;padding:15px 20px;color:#fff;background-color:#14B5AD;">
                    <span
                        style="display:inline-block;padding:0 5px;border-left:1px solid #fff;border-right:1px solid #fff;margin-right:10px;">学习指导</span>
                    对本次测试得出的学习指导
                </td>
            </tr>
            <tr>
                <td style="width:100%;min-height:200px;padding:10px;" id="page3_text2">
                    <br/>
                    <br/>
                    {volist name="guidance" id="guidanceItem"}
                    {$guidanceItem}<br/>
                    {/volist}
                </td>
            </tr>
        </table>
    </div>

    <div id="page3_end" style="position:repeat;bottom:0;left:0;margin-top:60px;">
        <p style="padding:0;color:#14B5AD;font-size:16px;margin:10px 0;">
            <span
                style="display:inline-block;border-left:1px solid #14B5AD;border-right:1px solid #14B5AD;padding:0 5px;">感谢您参加智适应教育测评</span>
        </p>
        <p style="padding:0;color:#999;font-size:16px;margin:10px 0;">
            任何疑问，请联系智适应教育课程顾问给予专业咨询
        </p>
        <p style="padding:0;color:#999;font-size:16px;margin:10px 0;">
            谨上 www.zhishiying.com.cn
        </p>
    </div>
    <div style="position:absolute;right:0;bottom:0;width:120px;">
        <img src="__PUBLIC__/trip/img/zsy_wx.png"/>
    </div>
</div>
<script type="text/javascript" src="__PUBLIC__/static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<script type="text/javascript" src="__PUBLIC__/trip/js/echarts.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/trip/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugin/lib/jqprint/jquery.jqprint-0.3.js"></script>

<script type="text/javascript">

    //page pie 1
    var page_1_pie1 = echarts.init(document.getElementById('page_1_pie1'));
    var page_1_pie1_value = [{
        name: '掌握{$has_learned_num}个知识点',
        value: {$has_learned_num}
    }, {
        name: '未掌握{$weakElements_num}个知识点',
        value: {$weakElements_num}
    }];
    var option1 = {
        title: {
            show: false
        },
        tooltip: {
            show: false
        },
        legend: {
            show: true,
            left: 'center',
            bottom: 0,
            data: [{
                name: page_1_pie1_value[0]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }, {
                name: page_1_pie1_value[1]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }]
        },
        series: [{
            type: 'pie',
            radius: ['60%', '80%'],
            label: {
                normal: {
                    position: 'center'
                }
            },
            data: [{
                value: page_1_pie1_value[0]['value'],
                name: page_1_pie1_value[0]['name'],
                itemStyle: {
                    normal: {
                        color: '#00B0A6'
                    }
                },
                label: {
                    normal: {
                        formatter: function (v) {
                            return Math.round(v.percent) + '%';
                        },
                        textStyle: {
                            fontSize: 30,
                            fontWeight: 'bold',
                            color: '#00B0A6'
                        }
                    }
                }
            }, {
                value: page_1_pie1_value[1]['value'],
                name: page_1_pie1_value[1]['name'],
                itemStyle: {
                    normal: {
                        color: '#C2E4E3'
                    }
                },
                label: {
                    show: false,
                    normal: {
                        formatter: ''
                    }
                }

            }]
        }]
    };
    page_1_pie1.setOption(option1);


    //page pie 2
    var page_1_pie2 = echarts.init(document.getElementById('page_1_pie2'));
    var page_1_pie2_value = [{
        name: '正答{$rightAnswer}题',
        value:{$rightAnswer}
    }, {
        name: '答错{$wrongAnswer}题',
        value: {$wrongAnswer}
    }];
    var option2 = {
        title: {
            show: false
        },
        tooltip: {
            show: false
        },
        legend: {
            show: true,
            left: 'center',
            bottom: 0,
            data: [{
                name: page_1_pie2_value[0]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }, {
                name: page_1_pie2_value[1]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }]
        },
        series: [{
            type: 'pie',
            radius: ['60%', '80%'],
            label: {
                normal: {
                    position: 'center'
                }
            },
            data: [{
                value: page_1_pie2_value[0]['value'],
                name: page_1_pie2_value[0]['name'],
                itemStyle: {
                    normal: {
                        color: '#00B0A6'
                    }
                },
                label: {
                    normal: {
                        formatter: function (v) {
                            return Math.round(v.percent) + '%';
                        },
                        textStyle: {
                            fontSize: 30,
                            fontWeight: 'bold',
                            color: '#00B0A6'
                        }
                    }
                }
            }, {
                value: page_1_pie2_value[1]['value'],
                name: page_1_pie2_value[1]['name'],
                itemStyle: {
                    normal: {
                        color: '#C2E4E3'
                    }
                },
                label: {
                    show: false,
                    normal: {
                        formatter: ''
                    }
                }

            }]
        }]
    };
    page_1_pie2.setOption(option2);
    window.print();
    $("body").jqprint();
</script>
</body>

</html>
