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
    <script type="text/javascript" src="/static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML"></script>
    <style type="text/css">
        .question-sheet .input-p,.question-sheet p{
            display: inline-block;
        }
        .layui-layer-btn a:hover {
            opacity:1 !important;
            text-decoration: none !important;
        }
        #xx-left{
            background: #ffffff;
            border: 1px solid #6acea7;
            border-radius: 6px;
            min-height: 426px;
            /*margin-top: 60px;*/
            margin-right: 60px;
            max-width: none;
            width: inherit;
            padding: 60px;
            position: relative;
        }

        #modal-ready  .am-modal-dialog{
            width:600px;
            height:600px;
            background: url(__PUBLIC__/static/math/img/complete.png) no-repeat !important;
            background-color: transparent  !important;
        }
        #modal-ready h3{
            margin:0;
        }
        .radiobox-content div,.rdobox div{
            display: inline !important;
        }
        .rdobox, .chkbox{
            height: auto !important;
            margin-bottom: 20px;
        }
        /*.xx-analyse-step .MathJax{*/
            /*display: inherit !important;*/
        /*}*/
        .xx-analyse-step p span div,.question-sheet span .MathJax_Display{
            display: inline !important;
        }
        .question-step .MathJax{
             display: block !important;
         }
        .MJXc-display{
            display: inline !important;
        }
       .step-analysis .xx-analyse-step-group{
            width:80%;
        }
        .edui-default .edui-for-kityformula .edui-dialog-content{
            height:330px !important;
        }
    </style>
</head>
<body>
<table class="am-table">
    <?php 
        $knowledgeService=new service\services\KnowledgeService();
        $questionService=new service\services\QuestionService;
        ?>
    <tbody>
    <tr>
        <td>题目</td>
        <td>
            <?php $question=$questionService->getQuestionById($user_exam_detail['question_id']); ?>
            <div style="display: none;"><?php print_r($question); ?></div>
            {if condition="isset($question['content'])"}
            <?php echo  str_replace('##$$##', '_______',htmlspecialchars_decode($question['content']));?>
            {else}
            试题内容为空 试题id：{$question['question_id']}
            {/if}
        </td>
    </tr>

    {if condition="isset($question['options'])&&is_array($question['options'])&&$question['q_type']!=2"}
    <tr>
        <td>选项</td>
        <td>
            {foreach name="question.options" item="options"}
            {if condition="isset($options['key'])&&isset($options['answer'])"}
            <div style="width:100%;float: left;" class="question-sheet">
                {$options['key']}&nbsp;:&nbsp;{$options['answer']|htmlspecialchars_decode}
            </div>
            {/if}
            {/foreach}
        </td>
    </tr>    
    {/if}
    <tr>
        <td>正确答案</td>
        <td>
            <div style="display: none;"><?php print_r($question['answer']); ?></div>
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
                        }else if(isset ($question['answer'])&& !is_array($question['answer'])){
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
        <td>学生答案</td>
        <td>
            <?php
                                if($user_exam_detail['user_answer_base64']){
                                    if(is_array($user_exam_detail['user_answer_base64'])){
                                            foreach($user_exam_detail['user_answer_base64'] as $kk=>$vv){
                                                    if(!is_array($vv))echo '<img src="'.$vv.'" />';
                                            }
                                    }else{
                                        
                                        $image=explode('@@@', $user_exam_detail['user_answer_base64']);
                                            if(count($image)>1){
                                                foreach ($image as $key => $value) {
                                                    if($value)echo '<img src="'.$value.'" />';
                                                }
                                            }else{
                                                echo '<img src="'.$user_exam_detail['user_answer_base64'].'" />';
                                            }
                                    }    
                                    
                                    }else{
                                        if($user_exam_detail['user_answer']){
                                            if(is_array($user_exam_detail['user_answer'])){
                                                foreach($user_exam_detail['user_answer'] as $vvv){
                                                    if(!is_array($vvv))echo $vvv;
                                                }
                                            }else{
                                                echo $user_exam_detail['user_answer'];
                                            }
                                        }else{
                                            echo "你没有作答";
                                        }
                                    }    
                                    
                                    ?> 
        </td>
    </tr>
    <tr>
        <td>解析</td>
        <td>
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
        </td>
    </tr>


    </tbody>
</table>

</body>
</html>