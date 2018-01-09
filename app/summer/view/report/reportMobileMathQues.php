<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>乂学寒假课</title>
        <meta http-equiv="x-rim-auto-match" content="none">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=320,maximum-scale=1.3,user-scalable=no">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
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
        <link rel="stylesheet" href="{:loadResource('classba/app/common/yx_font/iconfont.css')}">
        <link href="{:loadResource('classba/app/common/css/reset.css')}" rel="stylesheet">
        <link href="{:loadResource('classba/app/common/css/font-awesome.css')}" rel="stylesheet">
        <link href="{:loadResource('classba/app/common/css/reportMoblieMathQues.css')}" rel="stylesheet">
         <style type="text/css">
            .question-content
            {
                display: none;
            }
             .MJXc-display{
                 display: inline-block !important;
             }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content" id="content">
                <div class="main-content">
                    <!--position-->
                    <div class="pos">
                        <ul>
                            <li><span class="yxiconfont">&#xe653;</span></li>
                            <li class="return-top"><span class="yxiconfont">&#xe65d;</span></li>
                        </ul>
                    </div>
                    <!--banner-->
                    <div class="banner">
                        <h2>——  {$report_name} ——</h2>
                        <p>题目详情</p>
                    </div>
                    <!--list-->
                    <?php
                $startIndex2=$startIndex;
                ?>
                    <div class="list">
                        <ul>
                            {if condition="$hasAnswerQuestions"}
                            {volist name="$hasAnswerQuestions" id="question"}
                            {if condition="$question.is_right"}
                            <li class="active-true">{$startIndex++}</li>
                            {else/}
                            <li class="active-error">{$startIndex++}</li>
                            {/if}
                            {/volist}
                            {/if}

                        </ul>
                    </div>

                    <div class="question">
                        {volist name="hasAnswerQuestions" id="vo" key="k" }
                        <div class="question-content question-content-{$startIndex2}" style="{if condition='$k eq 1'}display:block{/if}">
                            <h2>{$startIndex2++}  {if condition="$vo.q_type eq 1"}
                                选择题
                                {elseif condition="$vo.q_type eq 2"/}
                                填空题
                                {else /}
                                {/if}</h2>
                            <ul>
                                <li>考察知识点：{$tag_names[$vo["tag_code"]]}</li>
                                <!--<li>考察能力：空间想象力</li>-->
                                <li>难度：
                                    {for start="0" end="$vo['difficulty']"}
                                    <i class="xx-icon">&#xe655;</i>
                                    {/for}
                                    {for start="$vo['difficulty']" end="9"}
                                    <i class="xx-icon">&#xe654;</i>
                                    {/for}


                                </li>
                            </ul>
                            <div class="question-title">

                                {$vo.content|html_replace}

                            </div>
                            <div class="subject-answer">
                              <!--<p>下列选文加点实词解释正确的一项（    ）</p>-->
                                {if condition="$vo.q_type eq 1"}
                                {foreach name="vo.options" item="answer"}
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="">
                                        {$answer.key}: {$answer.answer|htmlspecialchars_decode}
                                        <i class="xx-icon check-true">&#xe651;</i>
                                        <i class="xx-icon check-error">&#xe659;</i>
                                    </label>
                                </div>
                                {/foreach}
                                {elseif condition="$vo.q_type eq 2"/}

                                {else /}
                                {/if}

                                <!--                             <div class="checkbox">
                                                              <label>
                                                                <input type="checkbox" value="">
                                                               B．主持，管理
                                                               <i class="yxiconfont check-true">&#xe651;</i>
                                                                <i class="yxiconfont check-error">&#xe659;</i>
                                                              </label>
                                                            </div>
                                                             <div class="checkbox">
                                                              <label>
                                                                <input type="checkbox" value="">
                                                                C．知识
                                                                <i class="yxiconfont check-true">&#xe651;</i>
                                                                <i class="yxiconfont check-error">&#xe659;</i>
                                                              </label>
                                                            </div>
                                                             <div class="checkbox">
                                                              <label>
                                                                <input type="checkbox" value="">
                                                               D．通“智”，聪明，聪慧
                                                               <i class="yxiconfont check-true">&#xe651;</i>
                                                                <i class="yxiconfont check-error">&#xe659;</i>
                                                              </label>
                                                            </div>-->
                            </div>
                            <div class="answer-true">
                                <p>参考答案：<span>{if condition="$vo.q_type eq 1"}
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
                                        {/if}</span></p>
                                <p>我的答案：<span>{if condition="$vo.q_type eq 1"}
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
                                        {/if}</span>
                                    {if condition="$vo.is_right eq 1"}
                           <i class="xx-icon  check-true">&#xe651;</i>
                            {else/}
                            <i class="xx-icon check-error">&#xe659;</i>
                            {/if}
<!--                                    <i class="yxiconfont check-true">&#xe651;</i><i class="yxiconfont check-error">&#xe659;</i></p>-->
                            </div>
                            <div class="question-analysis">
                                <h3>题目解析</h3>
                                {volist name="vo.analyze" id="anal"  }
                                <?php $num = count($anal['content']); ?>
                                {volist name="anal.content" id="con" key="i" }

                                <p>{if condition="$num eq 1"}{else/}提示{$i}/{$num}{/if}</p>

                                {$con.content|htmlspecialchars_decode}

                                {/volist}
                                {/volist}
                            </div>
                            <!--                            <div class="question-analysis-error">
                                                            <h3>错因分析</h3>
                                                            <p>这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在于辟邪剑谱的最终章这道题目的精髓在</p>
                                                        </div>-->
                        </div>
                        {/volist}
                    </div>
                    <div class="footer">
                        <ul>
                            <li style='background: {if condition="$page_num eq 1"}#ccc{else/}#5da4ff{/if}' onclick="{if condition="$page_num egt 2"}location.href='{$prevPageUrl}'{else/}alert('到头了'){/if}"><span class="fa fa-chevron-left"></span>查看前20题</li>
                    <li style='background: {if condition="$page_num eq $totalPage"}#ccc{else/}#5da4ff{/if}' onclick="{if condition="$page_num elt $totalPage-1"}location.href='{$nextPageUrl}'{else/}alert('没有更多了'){/if}">查看后20题<span class="fa fa-chevron-right"></span></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
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
            //回顶部
            $(".return-top").click(function () {
                main.scrollTo(0, 0);
            });
            
            $(".list li").click(function () {
                var index = $(this).text();
                $(".question-content").hide();
                $(".question-content-" + index).show();

                main.refresh();

            });
        </script>
        <script src="{:loadResource('classba/assets/tools/xa.js')}"></script>
    </body>
</html>