<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <script type="text/javascript" src="/static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML"></script>
    <link rel="stylesheet" href="/plugin/lib/css/amazeui.min.css"/>
    <style>
        .vjs-amazeui.vjs-big-play-centered#example_video_2 .vjs-big-play-button{
            margin-top: -29%;
        }
        .xx-analyse-step-group #xx-step-right{
            font-family: "微软雅黑";
            font-size: 12px;
            font-weight: bold;
            color: #26b987;
            letter-spacing: 0px;
            text-align: left;
        }
        .vjs-amazeui .vjs-control-bar{
            height: 7em;
        }
        .vjs-amazeui .vjs-control{
            height:4em;
        }
        .vjs-amazeui .vjs-play-control,.vjs-amazeui .vjs-time-controls,.vjs-time-divider,.vjs-amazeui .vjs-duration,.vjs-live-control,.vjs-amazeui .vjs-mute-control,.vjs-amazeui .vjs-volume-control,.vjs-amazeui .vjs-fullscreen-control{
            top:2em;
            position: relative;
        }
        .radiobox-content div,li.xx-analyse-step>p>span div{
            display: inline !important;
        }
        .rdobox, .chkbox{
            height: auto !important;
        }
        .xx-analyse-step p div{
            display: inline !important;
        }
        .am-topbar-right>li{
            display: inline-block;
        }
        .am-topbar-right>li>a{
            color: #fff;
            font-size: 14px;
        }
        .xx-subject-module li.learned>a{
            color: #9cdf97;

        }
        .xx-subject-module li.learned>a:hover{
            color: #fff;
        }
        .xx-options{
            position: absolute;
            top:-40px;
            left:15px;
            font-size: 16px;
        }
        .xx-analyse-title>p{
            display: inline;
        }
        .xx-analyse-step .MathJax{
            display: block !important;
        }
        .radiobox-content .MJXc-display{
            display: inline;
        }
        .MJXc-display{
            display: inline !important;
            text-align: inherit;
            margin: 0;
        }
        .edui-default .edui-for-kityformula .edui-dialog-content{
            height:330px !important;
        }
    </style>
</head>
<body>
    <div style="display: none;">
    <?php print_r($question); ?>
    </div>
<table class="am-table am-table-bordered" style="margin-top: 10px;">

    <tbody>
    <tr>
        <td style="width:10em;">题目</td>
        <td>
           {$question.content|htmlspecialchars_decode_and_replace}
        </td>
    </tr>
    {if $question.q_type neq 2}
    <tr>
        <td style="width:10em;">选项</td>
        <td style="text-align: left;">
           {foreach name="question.options" item="vvvv"}
           {if condition="isset($vvvv['key'])"}{$vvvv.key}:     &nbsp;&nbsp;&nbsp;&nbsp;{/if}
           {if condition="isset($vvvv['answer'])"}
           <?php 
           echo strip_tags(htmlspecialchars_decode_and_replace($vvvv['answer']),'<img>');
           ?>
           {/if}
           <br />
           {/foreach}
            
        </td>
    </tr>
    {/if}
    <tr>
        <td style="width:10em;">正确答案</td>
        <td>
          <?php 
                    $right_answer_base64=$question['answer_base64'];
                    if($right_answer_base64==false){
                        if($question['answer']&& is_array($question['answer'])){
                            foreach($question['answer'] as $k=>$v){
                                if(is_array($v)){
                                    foreach ($v as $vv) {
                                        if(!is_array($vv)){
                                            echo $vv.' ';
                                        }
                                    }
                                }else{
                                    echo $v.' ';
                                }
                            }
                        }else if(isset ($question['answer'])&&!is_array($question['answer'])){
                            echo $question['answer'];
                        }
                    }else{
                        if($question['answer_base64']&& is_array($question['answer_base64'])){
                            foreach($question['answer_base64'] as $k=>$v){
                                if(is_array($v)){
                                    foreach ($v as $vv) {
                                        if(!is_array($vv)){
                                            echo '<img src="'.$vv.'" />  ';
                                        }
                                    }
                                }else{
                                    echo '<img src="'.$v.'" />  ';
                                }
                            }
                        }
                    }

                ?>
        </td>
    </tr>
    <tr>
        <td> 
            {if $question.analyze}解析
            {/if}</td>
        <td>
        {if $question.analyze}
            {if condition="$question['analyze']&&is_array($question['analyze'])"}
                {foreach name="question.analyze" item="vo"}
                {if condition="isset($vo['content'])&&is_array($vo['content'])"}
                    {foreach name="vo.content" item="vv"}
                        {if condition="isset($vv['content'])&&!is_array($vv['content'])"}
                        {$vv['content']|htmlspecialchars_decode}
                        {/if}
                    {/foreach}
                {else}
                {$vo['content']|htmlspecialchars_decode}
                {/if}
                {/foreach}
                {/if}
            {else}
            {/if}
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
