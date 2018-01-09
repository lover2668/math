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
<button type="button" class="am-btn am-btn-secondary">题&nbsp;&nbsp;目</button>
<table class="am-table">
    <?php 
        $knowledgeService=new service\services\KnowledgeService();
        $questionService=new service\services\QuestionService;
        ?>
    <tbody>
    <tr>
        <td>题目</td>
        <td>
            <?php $question=$questionService->getQuestionById($post['question_id']); ?>
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
            <div style="width:100%;float: left;"  class="question-sheet">
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
            <div style="display:none;"><?php print_r($question); ?></div>
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
<?php 
$tag_name='';
$gltag_name='';
if($user_exam_detail['tag_code']&&$user_exam_detail['topicId']){
    $knowledgeService=new service\services\KnowledgeService();
    $tag_namearr=$knowledgeService->getKnowledgeByCode($user_exam_detail['tag_code'],$user_exam_detail['topicId']);
    if(isset($tag_namearr['tag_name']))$tag_name=$tag_namearr['tag_name'];
    $topic_service=new \service\services\TopicService();
    $kmap_code = $topic_service->getKmapCodeByTopicId($user_exam_detail['topicId']);
    $algoLogic = new service\algo\AlgoLogic();
    $afterCodeResult=$algoLogic->getKnoledgeNode($kmap_code,"POSTREQ");//后行知识点
    if(isset($afterCodeResult['knodes'])){
            $knodes=json_decode($afterCodeResult['knodes'], true);
            if($knodes&& is_array($knodes)){
                $knodes= array_keys($knodes);
                foreach($knodes as $v){
                    if($user_exam_detail['tag_code']!=$v){
                        $tag_namearr=$knowledgeService->getKnowledgeByCode($v,$user_exam_detail['topicId'],false);
                        if(isset($tag_namearr['tag_name']))$gltag_name.=$tag_namearr['tag_name'].'    ';
                    }
                    $gltag_name=rtrim($gltag_name, '');
            }

        }
    }
}
?>
<button type="button" class="am-btn am-btn-success">班级报告</button>
<table class="am-table">

    <tbody>
    <tr>
        <td>考察知识点</td>
        <td>
            {$tag_name}        </td>
    </tr>


    <tr>
        <td>关联知识点</td>
        <td>

{$gltag_name}        </td>
    </tr>

    <tr>
        <td>答对学生</td>
        <td>
            <div style="display: none;"><?php print_r($user_answer_right_user); print_r($user_data); ?></div>
            {if condition="isset($user_answer_right_user)==false||$user_answer_right_user==false"}
            没有人答对
            {else}
            {if condition="isset($user_answer_right_user)&&is_array($user_answer_right_user)"}
                {foreach name="user_answer_right_user" item="vo" key="k"}
                {if condition="isset($user_data[$k])&&$user_data[$k]"}
                {$user_data[$k]} 
                {/if}
                {/foreach}
            {/if}
            {/if}
                    </td>
    </tr>

    <tr>
        <td>答错学生</td>
        <td>
            {if $user_answer_wrong_user eq false}
            没有人答错
            {else}
            {if condition="isset($user_answer_wrong_user)&&is_array($user_answer_wrong_user)"}
                {foreach name="user_answer_wrong_user" item="vo" key="k"}
                {if condition="isset($user_data[$k])"}
                {$user_data[$k]} 
                {/if}
                {/foreach}
            {/if}
            
            {/if}
        </td>
    </tr>


    </tbody>
</table>
</body>
</html>