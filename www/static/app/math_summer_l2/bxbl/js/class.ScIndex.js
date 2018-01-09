/**
 * Created by sks on 2016/11/1.
 */
function ScIndex(ui) {
    this.ui = ui;
    this.current_tag_code = 0;
    this.domReady();
}
ScIndex.prototype.domReady = function () {
    var thisObj = this;
    thisObj.getQuestion();

}
//提交操作
ScIndex.prototype.initSubmit = function (topicId, answer_content, module_type,flag,start_time) {
    try{
        var thisObj = this;
        var end_time = new Date().getTime();
        var spent_time = end_time - start_time;
        $_CONFIG.page_end_time=end_time;
        var index = layer.load(0, {shade: 0.8});
        $.ajax({
            url: HOST + 'index/bxbl/detectSubmitQuestion',
            data: {
                topicId: topicId,
                user_id:$_CONFIG.uid,
                answer_content: answer_content,
                module_type: module_type,
                spent_time:spent_time
            },
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if(typeof response.code != "undefined"){
                    if(response.code==0){
                        alert(response.msg);
                        setTimeout(function(){
                            location.href = "http://"+window.location.host + response.url
                        },response.wait*1000);
                    }
                }else{
                    if (response.isSuccess == 1) {
                        if (response.is_right == 0) {
                            layer.open({
                                content: '<div class="answer-wrong-next"></div>',
                                closeBtn:0,
                                time:2500,
                                title: false,
                                btn:"",
                                success: function(layero, index){
                                    thisObj.openAnalyse();
                                }
                            });
                        } else {
                            layer.open({
                                content: '<div class="answer-right"></div>',
                                closeBtn:0,
                                time:2500,
                                title: false,
                                btn:"",
                                success: function(layero, index){
                                    thisObj.openAnalyse();
                                }
                            });
                        }
                    } else {

                    }
                }

            },
            complete: function () {
                layer.close(index);
            }
        });
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
//获取题目
ScIndex.prototype.getQuestion = function () {
    try{
        var index = layer.load(0, {shade: 0.6});
        var thisObj = this;
        var topicId = $("input[name=topicId]").val();
        $.ajax({
            url: HOST+"/index/bxbl/GetDetectQuestion",
            data:{
                topicId: topicId,
            },
            type:'POST',
            dataType:'json',
            success: function(response){
                if(response.is_all_end == 0){
                    if (response.is_end == 0) {
                        if (!MY_UI.isEmpty(response.question_list)) {
                            var sheetArea = "",question_id=response.question_list.id;
                            $_CONFIG.question_id = question_id;
                            console.log(thisObj.current_tag_code);
                            if(thisObj.current_tag_code==0){
                                $("#xx-k-video-button").trigger("click");
                            }
                            thisObj.current_tag_code++;
                            if (response.question_list.q_type == 1) {
                                sheetArea += thisObj.initQuestionOption(response);
                                $(".xx-question-sheet").html(sheetArea);
                                /*单选选项*/
                                $(".rdolist").labelauty("rdolist", "rdo");
                            } else if (response.question_list.q_type == 2) {
                                sheetArea += thisObj.initQuestionInput(response);
                                $(".xx-question-sheet").html(sheetArea);
                                var ue = UE.getEditor('myEditor', {
                                    //这里可以选择自己需要的工具按钮名称,此处仅选择如下五个
                                    toolbars: [[
                                        'fullscreen', 'source', '|',
                                        'bold', 'italic', 'underline', '|', 'fontsize', '|', 'kityformula', 'preview'
                                    ]],
                                    //focus时自动清空初始化时的内容
                                    autoClearinitialContent: true,
                                    //关闭字数统计
                                    wordCount: false,
                                    //关闭elementPath
                                    elementPathEnabled: false,
                                    //默认的编辑区域高度
                                    initialFrameHeight: 300
                                    //更多其他参数，请参考ueditor.config.js中的配置项
                                });
                            } else  {
                                sheetArea += thisObj.initQuestionOptions(response);
                                $(".xx-question-sheet").html(sheetArea);
                                /*多选选项*/
                                $(".chklist").labelauty("chklist", "check");
                            }
                            MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
                            //试题题号
                            var questionlength = response.has_answered_questions.length + 1;
                            $(".xx-question-num").html(questionlength);
                            thisObj.initQuestionAnalyse(response);
                            $(".xx-question-sheet").append(thisObj.initSubmitButton());
                            // if(response.hasOwnProperty("tag_name")){
                            //     $(".xx-options>span").html(response.tag_name);
                            // }else{
                            //     $(".xx-options>span").html("知识点为空");
                            // }
                            $(".xx-bx-analyse").on("click", function () {
                                thisObj.openAnalyse("analyse");
                                $(".xx-question-analyse").slideDown("slow");
                                $(".xx-question-analyse-close").fadeIn("slow");
                            });
                            var redoHtml = $(".xx-question-sheet").html();
                            var type_check=thisObj.initErrorOption(response);
                            $("#form1").html(type_check);
                            $("#monent").focus(function(){
                                $(this).html("");
                            })
                            //报错错误类型选中切换
                            $(".radio-check>label").click(function(){
                                //$(this).parent(".radio-check").children(".regular-radio").removeAttr("checked")
                                $(this).parents("form").find(".radio-check").children(".regular-radio").attr("checked",false);
                                $(this).parent(".radio-check").children(".regular-radio").attr("checked",true);
                            });
                            $("#fileuploader").uploadFile({
                                url:HOST+"/index/index/submitFile",
                                fileName:"myfile",
                                onSuccess:function(files,data,xhr,pd){
                                    $("#option-page>p").hide();
                                },
                                showDelete: true,//删除按钮
                            });
                            $("#sure").click(function(){
                                $("#submit").ajaxSubmit({
                                    url: HOST+"index/Index/submitCorrection", /*设置post提交到的页面*/
                                    type: "post", /*设置表单以post方法提交*/
                                    dataType: "json", /*设置返回值类型为文本*/
                                    success: function (data) {
                                        $("#your-modal .am-modal-hd").find("a").trigger("click");
                                        console.log(data);
                                        $("#form1").html(type_check);
                                    },
                                    error: function (error) { alert(error); }
                                });
                            });
                            $("#cancel").click(function(){
                                $(".ajax-file-upload-red").trigger("click");
                                $("#your-modal .am-modal-hd").find("a").trigger("click");
                            });
                            var start_time = new Date().getTime();
                            //var second=0;
                            //var timer=setInterval(function(){
                            //    second+=1;
                            //},1);
                            $(".xx-continue").on("click", function () {
                                var answer_content = [];
                                var answer_val = "", type = "", question_id = "", answer_url = "", topicId = "";
                                type = $(".question-sheet").data("type");
                                question_id = $(".question-sheet").data("question_id");
                                topicId = $("input[name=topicId]").val();
                                var flag = $(this).data("flag");
                                if (type == 2) {
                                    $(".input-p").each(function (i, qv) {
                                        if ($(qv).find("img").length > 0) {
                                            var input_answer_val = '', input_answer_base64 = '';
                                            input_answer_base64 = $(qv).find("img").attr("src");
                                            input_answer_val = MY_UI.toSBC($(qv).find("img").attr("data-latex"));
                                            input_answer_val = input_answer_val == undefined ? '' : input_answer_val;
                                            input_answer_base64 = input_answer_base64 == undefined ? '' : input_answer_base64;
                                            if (i > 0 && !MY_UI.isEmpty(answer_val)) {
                                                answer_val += ";" + input_answer_val;
                                                answer_url += "@@@" + input_answer_base64;
                                            }else if(i>0 && MY_UI.isEmpty(answer_val)){
                                                answer_val  += ";"+input_answer_val;
                                                answer_url += "@@@"+input_answer_base64;
                                            } else {
                                                answer_val += input_answer_val;
                                                answer_url += input_answer_base64;
                                            }
                                        }else if(i>0){
                                            answer_val  += ";";
                                            answer_url += "@@@";

                                        }
                                    });
                                } else if (type == 1) {
                                    answer_val = $("label.checked").data("value");
                                    answer_val = answer_val == undefined ? '' : answer_val;
                                    question_id = question_id == undefined ? '' : question_id;
                                    type = type == undefined ? '' : type;
                                    //q_forms=q_forms==undefined ?'' : q_forms;
                                } else if (type == 3) {
                                    $("label.checked").each(function (i, qv) {
                                        if (i > 0 && !MY_UI.isEmpty(answer_val)) {
                                            answer_val += "###" + $(qv).data("value");
                                        } else {
                                            answer_val += $(qv).data("value");
                                        }
                                        answer_val = answer_val == undefined ? '' : answer_val;
                                        question_id = question_id == undefined ? '' : question_id;
                                        type = type == undefined ? '' : type;
                                        //q_forms=q_forms==undefined ?'' : q_forms;
                                    });

                                }
                                if (flag == 0 && (MY_UI.isEmpty(answer_val.replace(/(;)/gi, "")) || answer_val == '\\placeholder ')) {
                                    layer.open({
                                        type: 1
                                        , title: false //不显示标题栏
                                        , closeBtn: true
                                        , area: ['420px', '259px']
                                        , shade: 0.8
                                        , id: 'submit_confirm' //设定一个id，防止重复弹出
                                        , resize: false
                                        , btn: ['确认', '取消']
                                        , btnAlign: 'c'
                                        , moveType: 1 //拖拽模式，0或者1
                                        , content: '<div class="xx-logo-confirm"></div>你尚未填写答案，是否确认提交？'
                                        , success: function (layero) {
                                            //clearInterval(timer);
                                            //var spent_time=second;
                                            var btn = layero.find('.layui-layer-btn');
                                            btn.find('.layui-layer-btn0').on("click", function () {
                                                $(".xx-continue").data("flag", 2);
                                                answer_content.push({
                                                    question_id: question_id,
                                                    answer_base64: answer_url,
                                                    type: type,
                                                    answer: answer_val
                                                });
                                                thisObj.initSubmit(topicId, answer_content, flag,redoHtml,start_time);
                                            });
                                        }
                                    });

                                } else if (flag == 0 && !MY_UI.isEmpty(answer_val.replace(/(;)/gi, "")) && answer_val != '\\placeholder ') {
                                    $(".xx-continue").data("flag", 2);
                                    //clearInterval(timer);
                                    //var spent_time=second;
                                    answer_content.push({
                                        question_id: question_id,
                                        type: type,
                                        answer_base64: answer_url,
                                        answer: answer_val
                                    });
                                    thisObj.initSubmit(topicId, answer_content, flag,redoHtml,start_time);
                                } else {
                                    thisObj.getQuestion();
                                }
                            });
                            //知识点视频
                            thisObj.initKCarousel(response);
                        } else {
                            //thisObj.getQuestion();
                        }
                    } else {
                        window.open(HOST + "index/bxbl/bIndex/topicId/"+topicId, "_self");
                    }
                }else{
                    window.open(HOST + "index/bxbl/studyReport/topicId/"+topicId, "_self");
                }


            },
            error:function(){
                alert("系统繁忙，请刷新重试或重新登录。");
            },
            complete:function(){
                layer.close(index);
            }
        });
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}

//初始化填空题
ScIndex.prototype.initQuestionInput = function (param) {
    try {
        var content = "";
        var question_content = param.question_list.content;
        if(question_content.indexOf("##$$##")>0){
            var n = (question_content.length - question_content.replace(/##\$\$##/g, "").length) / 6;
            console.log(question_content);
            if (n > 0) {
                for (var i = 0; i < n; i++) {
                    content += question_content.split("##$$##")[i] + '<div onClick="showMathEdit(this)" data-num="input' + i + '" data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" id="textarea" class="input-p" style="padding:5px 30px;border: 1px solid #ccc;border-radius:4px;position: relative;color:#ccc;">请输入正确答案</div>';
                }
                content += question_content.split("##$$##")[n];
            } else {
                content = param.question_list.content;
            }

            var title = "<div class='question-sheet' data-type='" + param.question_list.q_type + "' data-question_id='" + param.question_list.id + "'><span class='q_type'>[填空题]</span><span class='question-id'>___("+param.question_list.id+")</span>" + content + "</div>"
            //+"<div style='color: red'>这是测试内容："+param.question_list.id+"&nbsp;知识点标签tag_code:"+param.tag_code+"&nbsp;知识点难度:"+param.question_list.difficulty+"&nbsp;答案："+param.question_list.answer+"</div>";

            return title;
        }else{
            var b=replaceAll(question_content,/[_]+[1-9]*[_]+/,'<div onClick="showMathEdit(this)" data-num="input' + i + '" data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" id="textarea" class="input-p" style="padding:5px 30px;border: 1px solid #ccc;border-radius:4px;position: relative;color:#ccc;">请输入正确答案</div>',param.question_list.q_type,param.question_list.id);
            var title = "<div class='question-sheet' data-type='" + param.question_list.q_type + "' data-question_id='" + param.question_list.id + "'><span class='q_type'>[填空题]</span><span class='question-id'>___("+param.question_list.id+")</span>" + b + "</div>"
            //+"<div style='color: red'>这是测试内容："+param.question_list.id+"&nbsp;知识点标签tag_code:"+param.tag_code+"&nbsp;知识点难度:"+param.question_list.difficulty+"&nbsp;答案："+param.question_list.answer+"</div>";

            return title;
        }
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
/*
 * 创建提交按钮
 */
ScIndex.prototype.initSubmitButton = function () {
    var submitButton = '<div class="xx-continue" data-flag="0"><div class="xx-continue-inner">提交</div></div>';
    return submitButton;
}
/*
 * 初始化题目(单选择题)
 */
ScIndex.prototype.initQuestionOption=function(param){
    try{
        var title ='<div class="question-sheet"  data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '"><span class="q_type">[单选题]</span><span class="question-id">___('+param.question_list.id+')</span>'+param.question_list.content +'</div>';
        var option = '',optionChild = "";
        var optionNum = param.question_list.options;
        console.log(optionNum);
        for(var i = 0;i< optionNum.length;i++){
            optionChild +=
                '<input type="radio" name="rdo" class="rdolist"/>'
                + '<label  class="rdobox unchecked" data-type="'+param.question_list.q_type+'" data-value="'+param.question_list.options[i].key+'" data-question_id="'+param.question_list.id +'" >'
                + '<span class="check-image"></span><span class="radiobox-content">'+param.question_list.options[i].key+'、'+MY_UI.htmlDecodeByRegExp(param.question_list.options[i].answer)+'</span>'
                + '</label>';
        }
        option = '<div class="rdo" data-type="'+param.question_list.q_type+'" data-question_id="'+param.question_list.id+'">'+optionChild+'</div>';
        return title+option;
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
/*
 * 初始化题目(多选择题)
 */
ScIndex.prototype.initQuestionOptions = function (param) {
    try{
        var title = '<div class="question-sheet"  data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" data-q_forms="' + param.question_list.q_forms + '"><span class="q_type">[多选题]</span>' + param.question_list.content + '</div>';
        var option = '', optionChild = "";
        var optionNum = param.question_list.options;
        //console.log(optionNum);
        for (var i = 0; i < optionNum.length; i++) {
            optionChild +=
                '<input type="checkbox" name="chk" class="chklist"/>'
                + '<label  class="chkbox unchecked" data-type="' + param.question_list.q_type + '" data-value="' + param.question_list.options[i].key + '" data-question_id="' + param.question_list.id + '" data-q_forms="' + param.question_list.q_forms + '" >'
                + '<span class="radiobox-content">' + param.question_list.options[i].key + '、' + MY_UI.htmlDecodeByRegExp(param.question_list.options[i].answer) + '</span>'
                + '</label>';

        }
        option = '<div class="rdo" data-type="' + param.question_list.type + '" data-question_id="' + param.question_list.question_id + '" data-q_forms="' + param.question_list.q_forms + '">' + optionChild + '</div>';
        return title + option;
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
/*
 * 初始化解析
 */
ScIndex.prototype.initQuestionAnalyse = function (param,type) {
    try{
        if($(".xx-question-analyse").css("display")!="none"){
            $(".xx-question-analyse").fadeOut();
        }
        var optionChild="";
        var liLength = param.question_list.analyze[0].content.length;
        for (var i = 0; i < liLength; i++) {
            var n = i+1;
            optionChild +=
                '<li  class="xx-analyse-step" data-is_has_answer="'+param.question_list.analyze[0].content[i].is_has_answer+'" data-step="'+i+'" style="display: none">' +
                '<p class="xx-step-name">步骤'+n+'/'+liLength+'</p>' +
                '<p class="xx-step-title">'+param.question_list.analyze[0].content[i].content+'</p>' +
                '</li>';
        }
        if(liLength<=1){
            $("#find_answer").fadeIn();
        }else{
            $("#next_step").fadeIn();
        }
        $(".xx-analyse-step-group").html(optionChild);
        var answer = "";
        if(param.question_list.q_type==2){
            // answer ='<li  class="xx-analyse-step" id="find-answer" style="display: none">' +
            //     '<p class="xx-step-name">正确答案</p>' +
            //     '<p class="xx-step-title"><img src="'+param.question_list.answer_base64+'" /></p>' +
            //     '</li>';
            if(param.question_list.answer_base64.length<1){
                $("#xx-step-right").append("null");
            }else{
                //for(var i=0;i<param.question_list.answer_base64.length;i++){
                //    for(var j=0;j<param.question_list.answer_base64[i].length;j++){
                //        answer+='<li  class=" find-answer" style="display: none">' +
                //            // '<p class="xx-step-name">正确答案</p>' +
                //            '<p class="xx-step-title"><img src="'+param.question_list.answer_base64[i][j]+'" /></p>' +
                //            '</li>';
                //    }
                //}
                //$(".xx-analyse-step-group").append("<p id='xx-step-right' style='display: none'>正确答案</p>"+answer);
                for(var i=0;i<param.question_list.answer_base64.length;i++){
                    for(var j=0;j<param.question_list.answer_base64[i].length;j++){
                        var answer_f = param.question_list.answer[i][j];
                        if(answer_f.indexOf("\\(")>-1||answer_f.indexOf("\\[")>-1){
                            answer+='<li  class=" find-answer" style="display: none">' +
                                    // '<p class="xx-step-name">正确答案</p>' +
                                    // '<p class="xx-step-title"><img src="'+param.question_list.answer_base64[i][j]+'" /></p>'
                                    //'<p class="xx-step-title">\\('+param.question_list.answer[i][j]+'\\)</p>'
                                '<p class="xx-step-title">'+param.question_list.answer[i][j]+'</p>'
                                +
                                '</li>';
                            console.log("asdasdasdasd="+answer);
                        }else{
                            answer+='<li  class=" find-answer" style="display: none">' +
                                    // '<p class="xx-step-name">正确答案</p>' +
                                    // '<p class="xx-step-title"><img src="'+param.question_list.answer_base64[i][j]+'" /></p>'
                                    //'<p class="xx-step-title">\\('+param.question_list.answer[i][j]+'\\)</p>'
                                '<p class="xx-step-title">\\('+(param.question_list.answer[i][j])+'\\)</p>'
                                +
                                '</li>';
                            console.log("jjjjjj="+answer);
                        }
                    }
                }
                $(".xx-analyse-step-group").append("<p id='xx-step-right' style='display: none'>正确答案</p>"+answer);
                MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
            }
        }else{
            answer ='<li  class="xx-analyse-step" id="find-answer" style="display: none">' +
                '<p class="xx-step-name">正确答案</p>' +
                '<p class="xx-step-title">'+(param.question_list.answer)+'</p>' +
                '</li>';
            $(".xx-analyse-step-group").append(answer);
        }
        // $(".xx-analyse-step-group").append(answer);
        $(".xx-analyse-step-group li.xx-analyse-step").eq(0).fadeIn();
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
ScIndex.prototype.openAnalyse = function(param){
    try{
        $(".xx-continue-inner").html("下一题");
        if(param=="analyse"){
            var step = 0;
            $("#next_step").on("click",function(){
                step++;
                var liLength = $(".xx-analyse-step-group li.xx-analyse-step").length;
                $(".xx-analyse-step-group li.xx-analyse-step").eq(step).fadeIn();
                if(step==(liLength-2)){
                    $("#next_step").fadeOut();
                    $("#find_answer").fadeIn().on("click",function(){
                        $("#find-answer").fadeIn();
                    });
                }
            });
        }else{
            $(".xx-container .xx-question-analyse .xx-analyse-next-step").fadeOut();
            $(".xx-question-analyse li.xx-analyse-step").each(function(i){
                $(this).fadeIn();
            });
            $("#xx-step-right").fadeIn();
            $(".find-answer").show();
        }
        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
        $('html,body').animate({scrollTop:$('.bottom').offset().top}, 800);
        $(".xx-question-analyse").slideDown("slow");
        $(".xx-question-analyse-close").fadeIn("slow");
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
/*
 * 初始化知识点
 */
ScIndex.prototype.initKCarousel = function (param) {
    try{
        var title = "";
        var $tag_list = param.tag_code_timeline,n = $tag_list.length;
        console.log(n)
        for(var i=0;i< n;i++){
            var current = "";
            title += '<li class="show-poster-3 '+current+'">' +
                '<p class="xx-k-video-select" style="margin-top: 35px">'+$tag_list[i].tag_name+'</p>' +
                '</li>';
        }
        console.log(title);
        $("#xx-video-select-bd .xx-slider-content .drama-poster-sc ul").html(title);
        if(n==2){
            $(".drama-poster-sc>ul>li:first-child").css("marginLeft","100px");
        }else if(n==1){
            $(".drama-poster-sc>ul>li:first-child").css("marginLeft","200px");
        }
        //弹框初始化
        var a = $(".drama-poster-sc ul>li");
        a.mouseover(function () {
            a.removeClass("current");
            $(this).addClass("current");
        });
        $(".xx-question-analyse-close").click(function(){
            $(".xx-question-analyse").slideUp("slow");
            $(".xx-question-analyse-close").fadeOut("slow");
        });
        $(".xx-bx-analyse").on("click",function(){
            $(".xx-question-analyse").slideDown("slow");
            $(".xx-question-analyse-close").fadeIn("slow");
        });
        $(".drama-poster-sc li").on("hover", function () {
            $(".current").removeClass("current");
            $(this).addClass("current")
        });
        $(".show-poster-3 a").on("click",function() {
            $("#xx-k-carousel-select").hide();
            $("#xx-video-select-bd").css("marginTop","0px");
        });
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}

// 报错弹框的
ScIndex.prototype.initErrorOption=function(param){
    try{
        var option='<form   class="am-g wrong-type" id="submit">'+
            '<div class="type-check">'+
            '<p class="am-u-lg-12">请选择错误类型</p>'+
            '<div class="radio-check am-u-lg-3">'+
            '<input type="radio" id="radio-2-1" data-question_id="111" name="type" class="regular-radio big-radio" value="1"/>'+
            '<label for="radio-2-1"></label>'+
            '<span>题干错误</span>'+
            '</div>'+
            '<div class="radio-check am-u-lg-3">'+
            '<input type="radio" id="radio-2-2" data-question_id="222" name="type" class="regular-radio big-radio" value="2"/>'+
            '<label for="radio-2-2"></label>'+
            '<span>答案错误</span>'+
            '</div>'+
            '<div class="radio-check am-u-lg-3">'+
            '<input type="radio" id="radio-2-3" data-question_id="333" name="type" class="regular-radio big-radio"  checked="checked" value="3"/>'+
            '<label for="radio-2-3"></label>'+
            '<span>系统bug</span>'+
            '</div>'+
            '<div class="radio-check am-u-lg-3">'+
            '<input type="radio" id="radio-2-4" data-question_id="444" name="type" class="regular-radio big-radio" value="4"/>'+
            '<label for="radio-2-4"></label>'+
            '<span>其他错误</span>'+
            '</div>'+
            '<div class="am-u-lg-12">'+
            '<textarea name="content" id="monent" style="font-size: 16px;">请输入错误内容</textarea>'+
            '</div>'+
            '<div class="am-u-lg-12" id="option-page">'+
            '<div class="add-option" id="fileuploader"></div>'+
            '<input type="hidden" name="file_path">'+
            '<input type="hidden" name="question_id" value="'+param.question_list.id+'">'+
            '<p>未选择任何文件，插入题目错误截图可以更好地帮助你反馈错误</p>'+
            '</div>'+
            '</div>'+
            '</form>';
        return option;
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
function replaceAll( content , oldReplace , newReplace,q_type,id)
{
    console.log(content.indexOf(oldReplace));
    var i;

    for( i = 0; i < content.length; i ++ ){
        if(content.indexOf(oldReplace)>0){
            content=content.replace(oldReplace,'<div onClick="showMathEdit(this)" data-num="input' + i + '" data-type="' + q_type + '" data-question_id="' + id + '" id="textarea" class="input-p" style="padding:5px 30px;border: 1px solid #ccc;border-radius:4px;position: relative;color:#ccc;">请输入正确答案</div>');
        }else{
            content=content.replace(oldReplace,'<div onClick="showMathEdit(this)" data-num="input' + i + '" data-type="' + q_type + '" data-question_id="' + id + '" id="textarea" class="input-p" style="padding:5px 30px;border: 1px solid #ccc;border-radius:4px;position: relative;color:#ccc;">请输入正确答案</div>');
        }
    }
    return content;
}