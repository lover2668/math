/**
 * Created by linxiao on 2016/8/22.
 */
function PreIndex(ui) {
    this.ui = ui;
    this.domReady();
}
PreIndex.prototype.domReady = function () {
    var ui = this.ui;
    var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
    var thisObj = this;
    var topicId = $("input[name=topicId]").val();
    var initKStatus = $("input[name=initKStatus]").val();
    $.ajax({
        url: HOST+"/index/index/getExamQuestions",
        data:{
            initKStatus: initKStatus,
            topicId: topicId
        },
        type:'POST',
        dataType:'json',
        success: function(response){
            var timer="";
            if(response.is_end==0){
                timer=setTimeout(function(){$(".cont p").fadeIn(500000);}, 3000);
                if(!MY_UI.isEmpty(response.question_list))
                {
                    $("#qu-list").html(thisObj.questionList(response));
                    var sheetArea = "";
                    if(response.question_list.q_type == 1){
                        sheetArea += thisObj.initQuestionOption(response);
                    }else{
                        sheetArea += thisObj.initQuestionInput(response);
                    }
                    sheetArea +=thisObj.initSubmitButton();
                    $(".sheet-area").html(sheetArea);
                    if(response.has_answered_questions.length===0){
                        $(".am-help").trigger("click");
                        $('.continue-topic').popover({
                            content: '在星际冒险的旅途上，<br/>一切都是不可更改的！<br/>少年啊，提交了答案以后，<br/>就不能修改了哦！！',
                            trigger: 'hover focus',
                            theme:'warning'
                        })
                    }
                    /*选项*/
                    if(response.question_list.q_type == 1){
                        $(".rdolist").labelauty("rdolist", "rdo");
                    }else{
                        var ue =UE.getEditor('myEditor',{
                            //这里可以选择自己需要的工具按钮名称,此处仅选择如下五个
                            toolbars: [[
                                'fullscreen', 'source', '|',
                                'bold', 'italic', 'underline', '|', 'fontsize', '|', 'kityformula', 'preview'
                            ]],
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
                    }
                    //tijiao
                    $('.continue-topic',ui).click(function(){
                        $(".cont p").fadeOut();
                        clearTimeout(timer,200);
                        var answer_val = "",question_type="",question_id="",answer_url="";
                        var topicId = $("input[name=topicId]").val();
                        var answer_content = [];
                        if(response.question_list.q_type == 1){
                            question_type = $(".rdo").data("type");
                            question_id = $(".rdo").data("question_id");
                            answer_val = $("label.checked").data("value");
                            answer_val  = answer_val==undefined ? '' : answer_val;
                            question_id = question_id==undefined ? '' : question_id;
                            answer_url = "";
                            question_type = question_type==undefined ? '' : question_type;
                        }else if(response.question_list.q_type == 2){
                            question_id = $(".input-p").attr('data-question_id');
                            question_type = $(".input-p").attr('data-type');
                            $("div[class='input-p']").each(function(i,qv){
                                if($(qv).find("img").length>0){
                                    var input_answer_val = '',input_answer_base64 = '';
                                    input_answer_base64 = $(qv).find("img").attr("src");
                                    input_answer_val = $(qv).find("img").attr("data-latex");
                                    input_answer_val  = input_answer_val==undefined ? '' : input_answer_val;
                                    input_answer_base64 = input_answer_base64==undefined ? '' : input_answer_base64;
                                    if(i>0 && !MY_UI.isEmpty(answer_val)) {
                                        answer_val  += ";"+input_answer_val;
                                        answer_url += "@@@"+input_answer_base64;
                                    }else{
                                        answer_val  += input_answer_val;
                                        answer_url += input_answer_base64;
                                    }
                                    console.log(answer_val);
                                }else{
                                    answer_val  = "";
                                    answer_url = "";
                                }
                            });
                        }
                        if(MY_UI.isEmpty(answer_val)||answer_val=='\\placeholder '){
                            layer.confirm('您是答案为空，是否确认提交？', {
                                btn: ['是的','取消'] ,//按钮
                                shift:3
                            }, function(index){
                                layer.close(index);
                                answer_content.push({question_id:question_id,answer_base64:answer_url,type:question_type,answer:answer_val});
                                thisObj.initSubmit(topicId,answer_content);
                            }, function(index){
                                layer.close(index);
                            });
                        }else{
                            answer_content.push({question_id:question_id,type:question_type,answer_base64:answer_url,answer:answer_val});
                            thisObj.initSubmit(topicId,answer_content);
                        }

                    });
                }else{
                    thisObj.domReady();
                }
            }else{
                window.open(HOST+"index/index/preReport/topicId/"+topicId,"_self");
            }

        },
        complete:function(){
            layer.close(index);
        }
    });
}
/*
* 初始化题目标题
 */
PreIndex.prototype.initQuestionTitle = function (param) {
    var title = "<div class='question-sheet'>"+param.question_list.content+"</div>"
        +"<div style='color: red'>这是测试内容："+param.question_list.id+"&nbsp;知识点标签tag_code:"+param.tag_code+"&nbsp;知识点难度:"+param.question_list.difficulty+"</div>";
    return title;
}
/*
 * 初始化题目填空
 */
PreIndex.prototype.initQuestionInput = function (param) {
    var content = "";
    var question_content =MY_UI.htmlspecialcharsDecode(param.question_list.content);
    var n =(question_content.length-question_content.replace(/##\$\$##/g, "").length)/6;
    if(n>0){
        for(var i= 0;i < n;i++){
            content += question_content.split("##$$##")[i]+'<div onClick="showMathEdit(this)" data-num="input'+i+'" data-type="'+param.question_list.q_type+'" data-question_id="'+param.question_list.id+'" id="textarea" class="input-p" style="min-height:45px;border-bottom: 1px solid #000;"></div>';
        }
        content+=question_content.split("##$$##")[n];
    }else{
        content = MY_UI.htmlspecialcharsDecode(param.question_list.content);
    }

    var title = "<div class='question-sheet'>"+content+"</div>"
        //+"<div style='color: red'>这是测试内容："+param.question_list.id+"&nbsp;知识点标签tag_code:"+param.tag_code+"&nbsp;知识点难度:"+param.question_list.difficulty+"&nbsp;答案："+param.question_list.answer+"</div>";

    return title;
}
/*
 * 初始化题目选项
 */
PreIndex.prototype.initQuestionOption = function (param) {
    var title = "<div class='question-sheet'>"+MY_UI.htmlspecialcharsDecode(param.question_list.content).replace(/##\$\$##/g, "_____")+"</div>"
        //+"<div style='color: red'>这是测试内容："+param.question_list.id+"&nbsp;知识点标签tag_code:"+param.tag_code+"&nbsp;知识点难度:"+param.question_list.difficulty+"</div>";
    var option = '',optionChild = "";
    var optionNum = param.question_list.options;
    for(var i = 0;i< optionNum.length;i++){
        optionChild += '<input type="radio" name="rdo" class="rdolist"/>'
            + '<label data-type="'+param.question_list.q_type+'" data-question_id="'+param.question_list.id+'" data-value="'+param.question_list.options[i].key+'" class="rdobox">'
            + '<span class="radiobox-content">'+param.question_list.options[i].key+'、'+MY_UI.htmlspecialcharsDecode(param.question_list.options[i].content)+'</span>'
            + '</label>';
    }
    option = '<div class="rdo" data-type="'+param.question_list.q_type+'" data-question_id="'+param.question_list.id+'">'+optionChild+'</div>';
    return title+option;
}
/*
 * 创建提交按钮
 */
PreIndex.prototype.initSubmitButton = function () {
    var submitButton = '<div class="amz-toolbar" id="amz-toolbar" ><div class="continue-topic"></div></div>';
    return submitButton;
}
/*
 * 提交操作
 */
PreIndex.prototype.initSubmit = function (topicId,answer_content) {
    var thisObj = this;
    $.ajax({
        url: HOST+"/index/index/submitQuestion",
        data:{
            topicId: topicId,
            answer_content: answer_content
        },
        type:'POST',
        dataType:'json',
        success: function(response){
            if(response.isSuccess==1){
                thisObj.domReady();
            }else {

            }
        },
        complete:function(){

        }
    });
}
/*
 * 进度
 */
PreIndex.prototype.questionList = function (param) {
    //console.log(param);
    var questionlength = param.has_answered_questions.length;
    var progress = questionlength/(questionlength+1)*100+"%";
    //console.log(questionlength);
    //var questionList = '<label style="float: right" for="" class=""></label>';
    //for(var i=0;i<questionlength;i++){
    //    questionList += '<label style="float: right" for="" class="add"></label>';
    //}
    questionList = '<div class="am-progress am-progress-xs">'
        +'<div class="am-progress-bar" style="width: '+progress+'"></div>'
        +'</div>'
    return questionList;
}