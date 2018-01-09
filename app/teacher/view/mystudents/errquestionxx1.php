<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<script type="text/javascript" src="__PUBLIC__/static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML"></script>
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
    <table width="100%" border="1" class="table table-bordered">
        <tbody>
          <tr>
            <td width="15%"  align="right">题目</td>
            <td>
            <?php
                $QuestionService=new service\services\QuestionService();
                $QuestionService=$QuestionService->getQuestionById($id);//$data['question_id']
                if(isset($QuestionService['content']))echo str_replace('##$$##', '_______',htmlspecialchars_decode($QuestionService['content']));
            ?>
            </td>
          </tr>
          {if condition="isset($QuestionService['options'])&&is_array($QuestionService['options'])"}
        <tr>
            <td width="15%"  align="right">选项</td>
            <td>
                {foreach name="QuestionService.options" item="options"}
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
            <td align="right">正确答案</td>
            <td>
            <?php
                $right_answer=$QuestionService['answer_base64'];
                if($QuestionService['answer_base64']){
                    if(is_array($right_answer)){
                        foreach($right_answer as $kk=>$vv){
                            foreach ($vv as $key => $value) {
                                echo '<img src="'.$value.'" />';
                            }
                                
                        }
                    }
                }else{
                        if(is_array($right_answer)&&$right_answer){
                            foreach($right_answer as $kk=>$vv){
                                if(is_array($vv)){
                                    echo str_replace('"', ' ', $vv[0]);
                                }else{
                                    echo str_replace('"', ' ', $vv);
                                }
                            }
                        }else{
                            echo str_replace('"', "", $QuestionService['answer']);
                        }
                }
                
                ?>
            </td>
          </tr>
          <tr>
            <td align="right">试题解析</td>
            <td>
                <?php
                if(isset($QuestionService['analyze'][0]['content'][0]['content'])){
                    echo htmlspecialchars_decode($QuestionService['analyze'][0]['content'][0]['content']);
                }
                if(isset($QuestionService['analyze'][0]['title'])){
                    echo htmlspecialchars_decode($QuestionService['analyze'][0]['title']);
                }
                ?>
            </td>
          </tr>
        </tbody>
      </table>
    
    <div style="display: none;"><?php print_r($QuestionService); ?></div>
</body>
</html>
