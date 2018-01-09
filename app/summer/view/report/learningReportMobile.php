<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>乂学寒假课</title>
    <meta http-equiv="x-rim-auto-match" content="none">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=320,maximum-scale=1.3,user-scalable=no">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <!--禁止ios设备将数字作为拨号连接，邮箱自动发送，点击地图跳转-->
    <meta name="format-detection" content="telephone=no,email=no,adress=no">
    <!--强制全屏显示-->
    <meta name="full-screen" content="yes">
    <!--开启对webapp的支持-->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <!--web app应用下状态条(屏幕顶部条)的颜色,默认值为default(白色)-->
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!--禁止浏览器从缓存中访问页面内容-->
    <meta http-equiv="Pragma" content="no-cache">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <link rel="stylesheet" href="{:loadResource('classba/app/common/yx_font/demo.css')}">
    <link href="{:loadResource('classba/app/common/css/reset.css')}" rel="stylesheet">
    <link href="{:loadResource('classba/app/common/css/reportMoblie_math.css')}" rel="stylesheet">
    <style>
        .tag-item-lists {
            margin-left: 10px;
            margin-right: 10px;;
        }

        .tag-item-lists li {
            width: 47%;
            height: 32px;
            background: #F4F4F4;
            display: inline-block;
            margin-top: 5px;;
            line-height: 32px !important;
            text-align: left !important;
            position: relative;
            padding-left: 5px;
        }

        .tag-item-lists li:nth-of-type(odd) {
            margin-right: 2%;
        }

        .tag-item-lists li:nth-of-type(even) {
            margin-left: 2%
        }

        .tag-item-lists li span {
            position: absolute;
            display: block;
            background-image: linear-gradient(90deg, #2dc893 0%, #68e695 100%);
            width: 48px;
            height: 32px;
            left: 0;
            margin-left: 0 !important;
            z-index: 999;
        }

        .tag-item-lists li p {
            position: absolute;
            z-index: 999999;
            font-size:0.5112rem;
            text-overflow: ellipsis;
            white-space:nowrap ;
            width:90%;
            background: transparent;
            overflow: hidden;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content" id="content">
        <div class="main-content">
            <div class="pos">
                <ul>
                    <li><span class="yxiconfont">&#xe653;</span></li>
                    <li><span class="yxiconfont">&#xe65d;</span></li>
                </ul>
            </div>
            <div class="banner">
                <h2><img src="__PUBLIC__/classba/app/common/img/moblielogo.png" alt=""></h2>
                <ul>
                    <li>{$topic_name}</li>
                    <li>知 识 点 学 习 报 告</li>
                    <li>PRE-TEST REPORT</li>
                </ul>
                <ul>
                    <li>姓名：{$user_name}</li>
                    <li>日期：{$date_time}</li>
                </ul>
            </div>
            <div class="allAnswer">
                <div class="title">
                    <h2>—— <span>总体作答情况</span> ——</h2>
                </div>
                <div class="mytime">
                    <p>我的用时</p>

                    <h2>{$spent_time}</h2>

                    <p>建议用时<span>{$estimates_time}</span></p>
                </div>
                <div class="mytime">
                    <p>答题效率</p>

                    <h2>{$xiaolv}</h2>

                    <p>0.1~0.3 优秀｜0.4~0.6 良好｜0.7~1 需努力</p>
                </div>
            </div>
            <div class="knowMaster allAnswer">
                <div class="title">
                    <h2>—— <span>知识点掌握情况</span> ——</h2>
                </div>
                <div class="knowMaster-content">
<!--                    <div class="echartsImg">-->
<!--                        <div class="echartsP" id="echartsP" style="width:100%;height:200px;"></div>-->
<!--                        <ul>-->
<!--                            <li>全部知识点<span>80个</span></li>-->
<!--                            <li>已掌握知识点<span>80个</span></li>-->
<!--                            <li>未掌握知识点<span>80个</span></li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                    <div class="test-section">-->
<!--                        <div class="test-small">-->
<!--                            <p>未掌握知识点</p>-->
<!--                            <ul>-->
<!--                                <li>一次函数</li>-->
<!--                                <li>二元一次方程</li>-->
<!--                                <li>二次函数</li>-->
<!--                                <li>幂的指数</li>-->
<!--                                <li>三角形面积计算</li>-->
<!--                                <li>一元一次方程</li>-->
<!--                                <li>二次函数</li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                        <div class="test-small">-->
<!--                            <p>已掌握知识点</p>-->
<!--                            <ul>-->
<!--                                <li>一次函数</li>-->
<!--                                <li>二元一次方程</li>-->
<!--                                <li>二次函数</li>-->
<!--                                <li>幂的指数</li>-->
<!--                                <li>三角形面积计算</li>-->
<!--                                <li>一元一次方程</li>-->
<!--                                <li>二次函数</li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div style="border-top: 1px solid #cef3df;padding-top: 20px;padding-bottom: 20px;">
                        <p style="text-align: center;font-size:14px;color:#333333;">纳米级知识点掌握程度</p>
                        <ul class="tag-item-lists">
                            {volist name="user_ability_info" id="user_ability" key="k" }
                            <li><span style="width: {$user_ability['ability']}%"></span><p>{$tag_names[$user_ability["tag_code"]]}</p></li>
                            {/volist}
                            
<!--                            <li><span style="width: 9%"></span><p>一次函数</p></li>
                            <li><span style="width: 9%"></span><p>一次函数</p></li>
                            <li><span style="width: 9%"></span><p>一次函数</p></li>
                            <li><span style="width: 9%"></span><p>一次函数</p></li>-->
                        </ul>
                    </div>
                </div>
<!--                <div class="knowMaster allAnswer">
                    <div class="title">
                        <h2>—— <span>学习能力分布</span> ——</h2>
                    </div>
                    <div class="knowMaster-content">
                        <div class="echartsImg">
                            <div class="echartsF" id="section-brume-charts" style="width:100%;height:200px;">

                            </div>
                            <div class="section-tag">
                                <h2>识记<span>6</span></h2>
                                <p>文言文常见的150个实词以及重要的课外实词。包括一词多义，词类活用，通假字，古今异义</p>
                                <h2>理解<span>4</span></h2>
                                <p>文言文常见的150个实词以及重要的课外实词。包括一词多义，词类活用，通假字，古今异义</p>
                                <h2>运用<span>6</span></h2>
                                <p>文言文常见的150个实词以及重要的课外实词。包括一词多义，词类活用，通假字，古今异义</p>
                                <h2>分析<span>4</span></h2>
                                <p>文言文常见的150个实词以及重要的课外实词。包括一词多义，词类活用，通假字，古今异义</p>
                                <h2>综合<span>6</span></h2>
                                <p>文言文常见的150个实词以及重要的课外实词。包括一词多义，词类活用，通假字，古今异义</p>
                                <h2>评价<span>8</span></h2>
                                <p>文言文常见的150个实词以及重要的课外实词。包括一词多义，词类活用，通假字，古今异义</p>
                            </div>
                        </div>
                    </div>
                </div>-->
                <div style="clear:both;"></div>
                <div class="question-analysis">
                    <div class="title">
                        <h2>—— <span>题目分析报告</span> ——</h2>
                    </div>
                    <div class="moreEcharts">
                        <div class="echartsImg">
                            <div class="echarts1" id="echarts1" style="width:100%;height:200px;"></div>
                            <ul>
                                <li>共作答<span>{$sum_num}题</span></li>
                                <li>答对题数<span>{$right_num}题</span></li>
                                <li>答错题数<span>{$sum_num-$right_num}题</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="footer">
                    <input type="button" value="点击查看题目详情" onclick="location.href = '{:url('Report/reportMobileMathQues',['topicId'=>$topicId,'user_id'=>$user_id,'submodule_type'=>$submodule_type,'batch_num'=>$batch_num ,'report_num'=>3])}'"">
                    <p><img src="__PUBLIC__/classba/app/common/img/moblielogo.png" alt=""></p>
                </div>

            </div>
        </div>
    </div>
    <input type="hidden" value="{$zhangwolv}" data-text="知识点掌握率">
    <input type="hidden" value="{$accuracy}" data-text="正答率">
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
    <script src="{:loadResource('classba/app/common/js/iscroll5.js')}"></script>
    <script src="{:loadResource('classba/assets/jquery/jquery.min.js')}"></script>
    <script src="{:loadResource('classba/app/common/js/rem.js')}"></script>
    <script src="{:loadResource('classba/assets/echarts/echarts.min.js')}"></script>
    <script>
        //禁止微信端的下拉事件
        /*document.querySelector('body').addEventListener('touchmove',function(e){
         e.preventDefault();
         })*/
        document.addEventListener('touchmove', function (e) {
            e.preventDefault()
        }, false);
        //iscroll
        window.onload = function () {
            main = new IScroll("#content", {
                disableMouse: true,
                disablePointer: true,
            });
            setTimeout(function () {
                main.refresh();
            }, 100)
        }
        var myChart1 = echarts.init(document.getElementById('echarts1'));
        option1 = {
            //标题组件，包含主标题和副标题。
            title: {
                text: '{$accuracy}%',
                x: 'center',
                y: 'center',
                textStyle: {
                    color: '#333',
                    fontWeight: 'bolder',
                    fontSize: 26,
                }
            },
            color: ['#00DCC3', '#ECEAE8'],
            series: [
                {
                    name: '访问来源',
                    type: 'pie',
                    radius: ['58%', '70%'],
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
        myChart1.setOption(option1);
        var brume = echarts.init(document.getElementById('section-brume-charts'));
        var brume_option = {
            title: {
                text: ''
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                show: false,
            },
            toolbox: {
                show: false,
                //                feature : {
                //                    mark : {show: true},
                //                    dataView : {show: true, readOnly: false},
                //                    restore : {show: true},
                //                    saveAsImage : {show: true}
                //                }
            },
            calculable: true,
            polar: [
                {
                    indicator: [
                        {text: '数感', max: 100},
                        {text: '空间意识', max: 100},
                        {text: '运算能力', max: 100},
                        {text: '推理能力', max: 100},
                        {text: '应用意识', max: 100},
                        {text: '创新意识', max: 100},
                        {text: '模型思想', max: 100},
                        {text: '数据分析观念', max: 100},
                        {text: '几何直观', max: 100},
                        {text: '符号意识', max: 100}
                    ],
                    radius: 80
                }
            ],
            color: ['#16cc6c'],
            series: [
                {
                    name: '布鲁姆认知层次图',
                    type: 'radar',
                    itemStyle: {
                        normal: {
                            areaStyle: {
                                type: 'default'
                            }
                        }
                    },
                    data: [
                        {
                            value: [97, 42, 88, 94, 90, 86],
                            name: '布鲁姆认知层次图'
                        }
                    ]
                }
            ]
        };
        brume.setOption(brume_option);
    </script>
    <script src="{:loadResource('classba/assets/tools/xa.js')}"></script>
</body>
</html>