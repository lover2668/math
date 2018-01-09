<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>暑期课数学</title>
    <meta name="keywords" content="上海乂学教育科技有限公司-暑期课数学"/>
    <meta name="description" content="上海乂学教育科技有限公司-暑期课程系列（数学）"/>
    <meta name="renderer" content="webkit">
    <meta name="force-rendering" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="__PUBLIC__/classba/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="__PUBLIC__/classba/app/common/yx_font/iconfont.css" rel="stylesheet">
    <link href="__PUBLIC__/classba/app/math/css/math.css" rel="stylesheet">
</head>
<body>
<div class="xx-header">
    <div class="xx-navbar">
        <div class="xx-navbar-left xx-navbar-logo">
        </div>
        <div class="xx-navbar-left xx-nav"></strong></div>
        <ul class="xx-navbar-right xx-navbar-menu">

        </ul>
    </div>
</div>
<div class="xx-container">
    <div class="xx-question-tools">

    </div>
    <h1 name="xx-question-type"></h1>

    <div class="xx-question-title" name="xx-question-title">
        <p style="font-size: 16px;">
            答案是“$\frac {3} {4}$”，请输入<span class="input_editor" >答案</span>
        </p>
    </div>
    <div class="xx-continue" name="xx-continue">
        <hr/>
        <div class="prompt prompt-wam"><i class="xx-icon">&#xe648;</i>不能为空哦！<b></b></div>
        <div class="prompt prompt-true"><i class="xx-icon">&#xe647;</i>太棒了！回答正确<b></b></div>
        <div class="prompt prompt-error"><i class="xx-icon">&#xe646;</i>答错了<b></b></div>
        <div class="btn xx-continue-button" style="padding: 0" id="submit">
            提交
        </div>
        <div class="btn xx-continue-button" id="next" style="padding: 0;display: none">
            下一个
        </div>
    </div>
</div>
<script src="__PUBLIC__/classba/assets/mathjax/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
    tex2jax: {
      inlineMath: [ ['$','$'], ["\\(","\\)"] ],
      processEscapes: true
    }
  });
</script>
<script src="__PUBLIC__/classba/assets/jquery/jquery.js"></script>
<script src="__PUBLIC__/classba/assets/bootstrap/js/bootstrap.js"></script>
<script src="__PUBLIC__/classba/assets/tools/classba.ui.js"></script>
<script type="text/javascript" src="__PUBLIC__/classba/assets/editor/ueditor.config.js"></script>
<script type="text/javascript" src="__PUBLIC__/classba/assets/editor/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8"
        src="__PUBLIC__/classba/assets/editor/kityformula-plugin/addKityFormulaDialog.js"></script>
<script type="text/javascript" charset="utf-8"
        src="__PUBLIC__/classba/assets/editor/kityformula-plugin/getKfContent.js"></script>
<script type="text/javascript" charset="utf-8"
        src="__PUBLIC__/classba/assets/editor/kityformula-plugin/defaultFilterFix.js"></script>
<script id="editor" type="text/plain" style="width:1024px;height:500px;display:none"></script>
<script type="text/javascript">
    (function($){
        var question_config = [{
            "question_content":"答案是“$ \\frac {3} {4} $”， 请输入",
            "answer":"\\frac {3} {4}"
        },{
            "question_content":"答案是“$ \\sqrt[{3}] {5} $”， 请输入",
            "answer":"\\sqrt[{3}] {5}"
        },{
            "question_content":"答案是“$ \\left ( {a+b} \\right )^{3} $”， 请输入",
            "answer":"\\left ( {a+b} \\right )^{3}"
        },{
            "question_content":"答案是“$ 6{y}^{3} $”， 请输入",
            "answer":"6{y}^{3}"
        },{
            "question_content":"答案是“$ 2a{b}^{2} $”， 请输入",
            "answer":"2a{b}^{2}"
        },{
            "question_content":"答案是“$ \\frac {\\sqrt {5}} {4} $”， 请输入",
            "answer":"\\frac {\\sqrt {5}} {4}"
        }];
        var current_num = localStorage.getItem("editor_index")?localStorage.getItem("editor_index"):0;

        initQuestion(current_num);

        function initQuestion(param){
            var q_num = parseInt(param)+1;
            $("[name=xx-question-type]").html("练习&nbsp;"+q_num)
            $("[name=xx-question-title]").html(question_config[param].question_content+'<span class="input_editor" data-answer="'+question_config[param].answer+'">答案</span>');
            MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
            var ue = UE.getEditor('editor',{
                //这里可以选择自己需要的工具按钮名称,此处仅选择如下五个
                toolbars:[['Bold']],
                //focus时自动清空初始化时的内容
                autoClearinitialContent:true,
                //关闭字数统计
                wordCount:false,
                //关闭elementPath
                elementPathEnabled:false,
                //默认的编辑区域高度
                initialFrameHeight:300
                //更多其他参数，请参考ueditor.config.js中的配置项
            });
            $(".input_editor").on("click",function(){
                $(this).attr("data-num","1");
                localStorage.setItem("data-num","1");
                var screenH=document.documentElement.clientHeight ;
                $("#edui10_body").trigger("click");
                var a=$("[name='xx-question-title']").offset().top;
                var offH=$("[name='xx-question-title']").height();
                var top=offH+178;
                var tops=a+offH+10;
                console.log(tops);
                var b=screenH-400;
                if((screenH-tops)>=400){
                    $(".edui-state-centered").css({
                        "top":tops
                    });
                }
                else{
                    $(".edui-state-centered").css({
                        "top":b
                    });
                }
            });
        }
        function submitQuestion(user_answer,answer){
            user_answer = user_answer.replace(/ /g,"");
            answer = answer.replace(/ /g,"");
            console.log(user_answer == answer)
            if(user_answer == answer){
                return true;
            }else{
                return false;
            }
            console.log(user_answer)
        }
        $('#submit').click(function (e) {
            console.log(e)
            if(current_num < question_config.length-1){
                var user_answer = $(".input_editor").find("img").attr("data-latex");
                var answer = $(".input_editor").attr("data-answer");
                if(MY_UI.isEmpty(user_answer)){
                    $(".prompt").css("display","none");
                    $(".prompt-wam").css("display","block");
                }else{
                    $(".prompt").css("display","none");
                    $('#submit').css("display","none");
                    if(submitQuestion(user_answer,answer)){
                        $(".prompt-true").css("display","block")
                    }else{
                        $(".prompt-error").css("display","block")
                    }
                    $('#next').css("display","block").unbind("click");
                    $('#next').on("click",function(){
                        $('#submit').css("display","block");
                        $(this).css("display","none");
                        $(".prompt").css("display","none");
                        var index = ++current_num;
                        localStorage.setItem("editor_index",index);
                        initQuestion(index);
                    });

                }

            }else{
                $('.btn').css("display","none")
            }
        });

    })(jQuery)
</script>
</body>
</html>