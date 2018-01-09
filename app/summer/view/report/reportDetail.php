<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>寒假课报告页</title>
    <meta name="keywords" content="上海乂学教育科技有限公司-暑期课系列"/>
    <meta name="description" content="上海乂学教育科技有限公司-暑期课程系列"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="../../classba/app/physical/img/logo.svg" rel="shortcut icon" />
    <link rel="stylesheet" type="text/css" href="{:loadResource('classba/assets/bootstrap/css/bootstrap.min.css')}">
    <link rel="stylesheet" type="text/css" href="{:loadResource('classba/app/physical/css/style.css')}">
    <link rel="stylesheet" type="text/css" href="{:loadResource('classba/app/common/css/report_yx_detail.css')}">
    <style>
        .fc-report-subject .fc-page-look>p{
            display: inline-block;
            cursor: pointer;
        }
        .fc-report-subject .fc-page-look>p span{
            position: relative;
        }
        .fc-report-subject .fc-page-look>p span i{
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #60b8ff;
            display: inline-block;
            position: absolute;
            left: 3px;
            top:3px;
            display: none;
        }
        .nav-page-list li.am-active {
            width: 40px;
            height: 40px;
            background: #68e695;
            border-radius: 20px;
            font-size: 16px;
            color: #ffffff;
            letter-spacing: 0;
            line-height: 38px;
            text-align: center;
        }
        .nav-page-list li, .nav-page-list input{
            background: #ffffff;
            border-radius: 20px;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 38px;
            font-size: 16px;
            border: 1px solid #cccccc;
            color: #ccc;
        }
        .nav-page-list li a, .nav-page-list input a{
            color: #ccc;
        }
        .nav-page-list li.am-active a{
            color: #fFFFFF;
        }

    </style>
</head>
<body>
    <div class="nav-header">
        <div id="fc-header">
            <div class="fc-class-report">
                <p>— {$report_name} —</p>
                <p>题目详情</p>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="fc-content fc-report-content">
            <div class="fc-report-subject">
                <h2>我的正确率</h2>
                <h2>{$accuracy}%</h2>
                <ul class="fc-report-subject-child">
                    <li>总作答 {$sum_num}题</li>
                    <li>答对题数 {$right_num}题</li>
                    <li>答错题数 {$err_num}题</li>
                </ul>
                <form action="{:url('reportDetail')}">
                    <input type="hidden" name="topicId" value="{$topicId}"/>
                    <input type="hidden" name="user_id" value="{$user_id}"/>
                    <input type="hidden" name="batch_num" value="{$batch_num}"/>
                    <input type="hidden" name="report_num" value="{$report_num}"/>
                    <input type="hidden" name="module_type" value="{$module_type}"/>
                    <input type="hidden" name="submodule_type" value="{$submodule_type}"/>
                    <input type="hidden" name="page" value="{$page_num}"/>
                    <input type="hidden" name="is_error" value="{$is_error}"/>
                    <div class="fc-page-look">
                        <span>每页查看</span>
                        <div class="btn-group">
                            <select name="page_size" id="page_size">
                                <option {if condition="$page_size eq 5"}selected{/if} value="5"> &nbsp;5 题</option>
                                <option  {if condition="$page_size eq 10"}selected{/if}   value="10"> &nbsp;10 题</option>
                            </select>
                        </div>

                        <p id="find-error">

                            <span class="xx-round"><i class="round-check" {if condition="$is_error"} style="display: inline"{/if}></i></span>
                            <span class="xx-round-test">只显示错题</span>
                        </p>
                    </div>

                </form>
            </div>
            <hr>
            {volist name="hasAnswerQuestions" id="vo" key="k" }
                   <div class="fc-report-subject fc-analysis-coment">
                <h2>{$startIndex++}&nbsp;{if condition="$vo.q_type eq 1"}
                    选择题
                    {elseif condition="$vo.q_type eq 2"/}
                    填空题
                    {else /}
                    {/if}</h2>
                <div class="fc-ratio">
                    <span>考察知识点：{$tag_names[$vo["tag_code"]]}</span>
                    <p>
                        <span>难度</span>
                        {for start="0" end="$vo['difficulty']"}
                         <i class="icon yxiconfont">&#xe655;</i>
                        {/for}
                        {for start="$vo['difficulty']" end="9"}
                         <i class="icon yxiconfont">&#xe654;</i>
                        {/for}
                    </p>
                </div>
                <div class="fc-practice-question">
                    <p>
                        {$vo.content|html_replace}
                    </p>
                    
                    {if condition="$vo.q_type eq 1"}
                    {foreach name="vo.options" item="answer"}
                   <li class=""> {$answer.key}: {$answer.answer|htmlspecialchars_decode}</li>
                    {/foreach}
                    {elseif condition="$vo.q_type eq 2"/}

                    {else /}
                    {/if}

                </div>
                <div class=" fc-analysis-content">
                    <div class="fc-question">
                        <h3>正确答案</h3>
                        <p>{if condition="$vo.q_type eq 1"}
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
                                    {/if}</p>
             
                        <h3>我的答案</h3>
                        <p><span class="{if condition=" $vo.is_right eq 1"}answer_right{else/}answer_wrong{/if}">
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
                                                              {if condition=" $vo.is_right eq 1"}<i class="xx-icon" style="color:#26b987">&#xe651;</i>{else/}<i class="xx-icon" style="color:red">&#xe659;</i>{/if}</p>
                    </div>
                </div>
<!--                <div class=" fc-analysis-content">-->
<!--                    <div class="fc-question">-->
<!--                        <h3>我的错因</h3>-->
<!--                        <p>这道题目的精髓在于辟邪剑谱的最终章这道题目的</p>-->
<!--                    </div>-->
<!--                </div>-->
                <div class=" fc-analysis-content">
                    <div class="fc-question">
                        <h3>分步解析</h3>
                        {volist name="vo.analyze" id="anal"  }
                        <?php $num = count($anal['content']); ?>
                        {volist name="anal.content" id="con" key="i" }
                        <li>
                            <p class="fc-step-name">{if condition="$num eq 1"}{else/}提示{$i}/{$num}{/if}</p>
                            <span class="fc-step-title">
                                {$con.content|htmlspecialchars_decode}
                            </span>
                        </li>
                        {/volist}
                        {/volist}
                        
<!--                        <li>
                            <p class="fc-step-name">提示2/2</p>
                            <span class="fc-step-title">
                                这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在
                            </span>
                        </li>-->
                    </div>
                </div>
<!--                <div class=" fc-analysis-content">-->
<!--                    <div class="fc-question">-->
<!--                        <h3>题目解析</h3>-->
<!--                        <p>这道题目的精髓在于辟邪剑谱的最终章这道题目的</p>-->
<!--                    </div>-->
<!--                </div>-->
            </div>         
            {/volist}
<!--            <div class="fc-report-subject fc-analysis-coment">
                <h2>1 填空题</h2>
                <div class="fc-ratio">
                    <span>考察知识点：一次函数</span>
                    <p>
                        <span>难度</span>
                        <i class="icon yxiconfont">&#xe655;</i>
                        <i class="icon yxiconfont">&#xe655;</i>
                        <i class="icon yxiconfont">&#xe654;</i>
                    </p>
                </div>
                <div class="fc-practice-question">
                    <p>
                        两个物体A、B，各自重力之比是5：3，置于同一水平面时，对水平面产生压强之比是2：3，则它们与水平面的接触面积之比是（1）_________
                    </p>
                </div>
                <div class=" fc-analysis-content">
                    <div class="fc-question">
                        <h3>正确答案</h3>
                        <p>3：5</p>
                    </div>
                </div>
                <div class=" fc-analysis-content">
                    <div class="fc-question">
                        <h3>我的错因</h3>
                        <p>这道题目的精髓在于辟邪剑谱的最终章这道题目的</p>
                    </div>
                </div>
                <div class=" fc-analysis-content">
                    <div class="fc-question">
                        <h3>分步解析</h3>
                        <li>
                            <p class="fc-step-name">提示1/2</p>
                            <span class="fc-step-title">
                                这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在
                            </span>
                        </li>
                        <li>
                            <p class="fc-step-name">提示2/2</p>
                            <span class="fc-step-title">
                                这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在
                            </span>
                        </li>
                    </div>
                </div>
                <div class=" fc-analysis-content">
                    <div class="fc-question">
                        <h3>题目解析</h3>
                        <p>这道题目的精髓在于辟邪剑谱的最终章这道题目的</p>
                    </div>
                </div>
            </div>-->
<!--            <div class="fc-report-subject fc-analysis-coment">
                <h2>1 选择题</h2>
                <div class="fc-ratio">
                    <span>考察知识点：一次函数</span>
                    <p>
                        <span>难度</span>
                        <i class="icon yxiconfont">&#xe655;</i>
                        <i class="icon yxiconfont">&#xe655;</i>
                        <i class="icon yxiconfont">&#xe654;</i>
                    </p>
                </div>
                <div class="fc-practice-question">
                    <p>
                        两个物体A、B，各自重力之比是5：3，置于同一水平面时，对水平面产生压强之比是2：3，则它们与水平面的接触面积之比是（1）_________
                    </p>
                    <ul class="answer-option">
                        <li class="user_answer_right">A.  ①, ③, ④&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="icon yxiconfont">&#xe651;</i></li>
                        <li class="user_answer_error">A.  ①, ③, ④&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="icon yxiconfont">&#xe659;</i></li>
                        <li class="right_answer">A.  ①, ③, ④</li>
                        <li class="">A.  ①, ③, ④</li>
                    </ul>
                </div>
                <div class=" fc-analysis-content">
                    <div class="fc-question">
                        <h3>正确答案</h3>
                        <p>3：5</p>
                    </div>
                </div>
                                <div class=" fc-analysis-content">
                                    <div class="fc-question">
                                        <h3>我的错因</h3>
                                        <p>这道题目的精髓在于辟邪剑谱的最终章这道题目的</p>
                                    </div>
                                </div>
                <div class=" fc-analysis-content">
                    <div class="fc-question">
                        <h3>分步解析</h3>
                        <li>
                            <p class="fc-step-name">提示1/2</p>
                            <span class="fc-step-title">
                                这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在
                            </span>
                        </li>
                        <li>
                            <p class="fc-step-name">提示2/2</p>
                            <span class="fc-step-title">
                                这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在
                            </span>
                        </li>
                    </div>
                </div>
                                <div class=" fc-analysis-content">
                                    <div class="fc-question">
                                        <h3>题目解析</h3>
                                        <p>这道题目的精髓在于辟邪剑谱的最终章这道题目的</p>
                                    </div>
                                </div>
            </div>-->
        </div>
        <div class="fc-content nav-page-list">
            {$page}
<!--            <ul>
                <li class="default"><i class="glyphicon glyphicon-triangle-left xx-icon"></i></li>
                <li class="unchecked">1</li>
                <li class="checked">2</li>
                <li class="unchecked">3</li>
                <li class="unchecked">4</li>
                <li class="unchecked">5</li>
                <span class="xx-option">...</span>
                <li class="unchecked">12</li>
                <li class="default"><i class="glyphicon glyphicon-triangle-right xx-icon"></i></li>
                <span class="xx-test">跳转到</span>
                <input type="text" class="default">
                <li class="default"><i class="icon yxiconfont">&#xe663;</i></li>
            </ul>-->
        </div>
    </div>
    <input type="hidden" value="{$url}" name="page_url" data-text="页面url">
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
<script src="{:loadResource('classba/assets/jquery/jquery.js')}" type="text/javascript"></script>
<script src="{:loadResource('classba/assets/bootstrap/js/bootstrap.min.js')}" type="text/javascript"></script>
<script type="text/javascript" src="{:loadResource('classba/assets/particles/particles.js')}"></script>
<script>
    $(document).ready(function(){
        particlesJS('fc-header',
                {
                    "particles": {
                        "number": {
                            "value": 80,
                            "density": {
                                "enable": true,
                                "value_area": 700
                            }
                        },
                        "color": {
                            "value": "#ccc"
                        },
                        "shape": {
                            "type": "circle",
                            "stroke": {
                                "width": 0,
                                "color": "#000000"
                            },
                            "polygon": {
                                "nb_sides": 5
                            },
                            "image": {
                                "src": "img/github.svg",
                                "width": 100,
                                "height": 100
                            }
                        },
                        "opacity": {
                            "value": 3,
                            "random": false,
                            "anim": {
                                "enable": false,
                                "speed": 1,
                                "opacity_min": 0.1,
                                "sync": false
                            }
                        },
                        "size": {
                            "value": 8,
                            "random": true,
                            "anim": {
                                "enable": false,
                                "speed": 40,
                                "size_min": 0.1,
                                "sync": false
                            }
                        },
                        "line_linked": {
                            "enable": true,
                            "distance": 150,
                            "color": "#ddd",
                            "opacity": 0.5,
                            "width": 1
                        },
                        "move": {
                            "enable": true,
                            "speed": 1,
                            "direction": "none",
                            "random": false,
                            "straight": false,
                            "out_mode": "out",
                            "attract": {
                                "enable": false,
                                "rotateX": 600,
                                "rotateY": 1200
                            }
                        }
                    },
                    "interactivity": {
                        "detect_on": "canvas",
                        "events": {
                            "onhover": {
                                "enable": false,
                                "mode": "repulse"
                            },
                            "onclick": {
                                "enable": true,
                                "mode": "push"
                            },
                            "resize": true
                        },
                        "modes": {
                            "grab": {
                                "distance": 400,
                                "line_linked": {
                                    "opacity": 1
                                }
                            },
                            "bubble": {
                                "distance": 400,
                                "size": 40,
                                "duration": 2,
                                "opacity": 8,
                                "speed": 3
                            },
                            "repulse": {
                                "distance": 200
                            },
                            "push": {
                                "particles_nb": 4
                            },
                            "remove": {
                                "particles_nb": 2
                            }
                        }
                    },
                    "retina_detect": true,
                    "config_demo": {
                        "hide_card": false,
                        "background_color": "red",
                        "background_image": "",
                        "background_position": "50% 50%",
                        "background_repeat": "no-repeat",
                        "background_size": "contain"
                    }
                }
        );
        var page_url = $("[name=page_url]").val();
        console.log(page_url)
        $("ul.am-fr").append('<span class="xx-test">跳转到</span>' +
            '<input type="text" name="page_num" class="default">' +
            '<li name="page_jump" class="default"><i class="icon yxiconfont">&#xe663;</i>' +
            '</li>');

        $("[name=page_jump]").on("click",function(){
            var page_num = $("[name=page_num]").val();
            if(checkRate(page_num)){
                if(isContains(page_url,"?page")){
                    page_url = page_url.split("?page")[0]+"?page=";
                    location.href = page_url+page_num;
                }else{
                    page_url = page_url+"?page=";
                    location.href = page_url+page_num;
                }
            }else{
                alert("请输入数字");
                $("[name=page_num]").val("")
            }
        });
        $("#find-error").click(function(){

            if($(".round-check").is(":hidden"))
            {
                $(".round-check").show();
                $("input[name='is_error']").val(1);
            }else
            {
                $(".round-check").hide();
                $("input[name='is_error']").val(0);
            }
            $("input[name='page']").val(1);
            $("form").submit();

        })

        $("#page_size").change(function () {
            $("input[name='page']").val(1);
            $("form").submit();
        })
        function isContains(str, substr) {
            return str.indexOf(substr) >= 0;
        }
        function checkRate(input) {
            var re = /^[0-9]+.?[0-9]*$/; //判断字符串是否为数字 //判断正整数 /^[1-9]+[0-9]*]*$/

            if (!re.test(input)) {
                return false;
            }else{
                return true
            }
        }
    });
</script>
    <script src="{:loadResource('classba/assets/tools/xa.js')}"></script>
</body>
</html>