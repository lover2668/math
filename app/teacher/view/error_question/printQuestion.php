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
        table td
        {
            border: none!important;
        }
    </style>
</head>
<body>

<h1 style="font-size: 20px;text-align: center;margin: 20px;">
    错题详情
</h1>
{volist name="data" id="vo"}
<table class="am-table" >

    <tbody>
    <tr>
        <td style="width:6em">题目({$key+1})</td>
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
        <td>            
        <?php
                                if($vo['user_answer_base64']){
                                        if(is_array($vo['user_answer_base64'])){
                                            foreach ($vo['user_answer_base64'] as $vvv){
                                                if(!is_array($vvv))echo '<img src="'.$vvv.'" />';
                                            }
                                        }else{
                                            $image=explode('@@@', $vo['user_answer_base64']);
                                            if(count($image)>1){
                                                foreach ($image as $key => $value) {
                                                    if($value)echo '<img src="'.$value.'" />';
                                                }
                                            }else{
                                                echo '<img src="'.$vo['user_answer_base64'].'" />';
                                            }
                                            //echo '<img src="'.$vo['user_answer_base64'].'" />';
                                        }
                                    }else{
                                        if($vo['user_answer']){
                                            if(is_array($vo['user_answer'])){
                                                foreach($vo['user_answer'] as $vvv){
                                                    if(!is_array($vvv))echo $vvv;
                                                }
                                            }else{
                                                echo $vo['user_answer'];
                                            }
                                        }else{
                                            echo "你没有作答";
                                        }
                                    }
                                
                                ?>
              <?php if($vo['is_right'] == '1'){echo '<b style="color:#058F33;">√</b>';}else{echo '<b style="color:#f00;">X</b>';} ?>
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
<hr/>
{/volist}
<script type="text/javascript">
    
    if(document.readyState == "complete") //当页面加载状态 
    {
        window.print();
    }
    
</script>
</body>
</html>