<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="/static/lib/css/amazeui.min.css"/>
    <script type="text/javascript" src="__PUBLIC__/static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML"></script>
    <style type="text/css">
        #options p
        {
            display: inline;
        }
    </style>
</head>
<body>
<table class="am-table">

    <tbody>
    <tr>
        <td>题目</td>
        <td>
            <h3>{$vo.content|htmlspecialchars_decode_and_replace}</h3>
            <div>
                {if condition="$vo.q_type eq 1"}
                {foreach name="vo.options" item="answer"}
                <div class="am-u-lg-6" id="options">
                    {$answer.key}: {$answer.answer|htmlspecialchars_decode}
                </div>
                {/foreach}
                {elseif condition="$vo.q_type eq 2"/}

                {else /}
                {/if}
            </div>
        </td>
    </tr>


    <tr>
        <td>正确答案</td>
        <td><p><span>
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



                                                </span></p></td>
    </tr>

    <tr>
        <td>学生答案</td>
        <td><span class="{if condition=" $vo.is_right eq 1"}right{else/}wrong{/if}">
        <?php
             if($vo['user_answer_base64']){
                    echo '<img src="'.$vo['user_answer_base64'].'" />';
                }else{
                    if($vo['user_answer']){
                        echo $vo['user_answer'];
                    }else{
                        echo "没有作答";
                    }
//                    echo $data['user_answer'];
//                   echo str_replace('"','',str_replace('###', ' ', $data['user_answer'])); ?> <?php if($vo['user_answer']==false)echo "没有作答";  
                }
              ?>    
        </td>
    </tr>

    <tr>
        <td>解析</td>
        <td> {volist name="vo.analyze" id="anal"  }
            {volist name="anal.content" id="con" key="i" }
            <p> <!--<span>第{$i}步：</span>-->{$con.content|htmlspecialchars_decode}</p>
            {/volist}
            {/volist}</td>
    </tr>


    </tbody>
</table>
</body>
</html>