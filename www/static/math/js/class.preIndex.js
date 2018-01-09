/**
 * Created by sks on 2016/11/22.
 */
function PreIndex(ui){
    try{
        this.ui = ui;
        this.getQuestion();
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
PreIndex.prototype.domReady=function(){
    try{
        var thisObj = this;
        thisObj.getQuestion();
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
};
PreIndex.prototype.getQuestion=function(){
    try{
        var index = layer.load(0, {shade: 0.8});
        var thisObj = this;
        var topicId = $("input[name=topicId]").val();
        var initKStatus = $("input[name=initKStatus]").val();
        $.ajax({
            url: HOST+"/index/index/getExamQuestions",
            data:{
                initKStatus: initKStatus,
                topicId: topicId,
            },
            type:'POST',
            dataType:'json',
            success: function(response){
                if(response.is_end==0){
                    if(!MY_UI.isEmpty(response.question_list))
                    {
                        var sheetArea = "";
                        var question_id=response.question_list.id;
                        $_CONFIG.question_id = question_id;
                        if(response.question_list.q_type == 1){
                            sheetArea += thisObj.initQuestionOption(response);
                            $("#question-sheet").html(sheetArea);
                            /*单选选项*/
                            $(".rdolist").labelauty("rdolist", "rdo");
                        }else if(response.question_list.q_type == 2){
                            sheetArea += thisObj.initQuestionInput(response);
                            $("#question-sheet").html(sheetArea);
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

                        }else{
                            sheetArea+=thisObj.initQuestionOptions(response);
                            $("#question-sheet").html(sheetArea);
                            /*多选选项*/
                            $(".chklist").labelauty("chklist", "check");
                        }
                        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
                        //试题题号
                        var questionlength = response.has_answered_questions.length+1;
                        $(".xx-question-num").html(questionlength);
                        $(".xx-question-num-list a").html(questionlength);
                        $("#question-sheet").append(thisObj.initSubmitButton());
                        var type_check=thisObj.initErrorOption(response);
                        $("#form1").html(type_check);
                        $("#monent").focus(function(){
                            $(this).html("");
                        });
                        if(response.hasOwnProperty("tag_name")){
                            $(".xx-options>span").html(response.tag_name+' ('+response.tag_code+')');
                        }else{
                            $(".xx-options>span").html("知识点为空");
                        }
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
                                //console.log(data)
                            },
                            showDelete: true,//删除按钮
                        });
                        var estimates_time = response.question_list.estimates_time;
                        thisObj.initCharts();
                        var canvas = document.getElementById('xx-my-time'),  //获取canvas元素
                            context = canvas.getContext('2d'),  //获取画图环境，指明为2d
                            centerX = canvas.width/2,   //Canvas中心点x轴坐标
                            centerY = canvas.height/2,  //Canvas中心点y轴坐标
                            rad = Math.PI* 1.5/estimates_time, //将360度分成100份，那么每一份就是rad度
                            speed = 0.1; //加载的快慢就靠它了
                        //window.requestAnimationFrame(drawFrame, canvas);
                        context.clearRect(0, 0, canvas.width, canvas.height);
                        var startRad = Math.PI*(3/4);
                        var endRad = Math.PI*(1/4);
                        whiteCircle(canvas,context,centerX,centerY,startRad,endRad);
                        var timeIndex=0;
                        function setTime(){
                            //console.log(timeIndex)
                            var hour = parseInt(timeIndex / 3600);    // 计算时
                            var minutes = parseInt((timeIndex % 3600) / 60);    // 计算分
                            var seconds = parseInt(timeIndex % 60);    // 计算秒
                            hour = hour < 10 ? "0" + hour : hour;
                            minutes = minutes < 10 ? "0" + minutes : minutes;
                            seconds = seconds < 10 ? "0" + seconds : seconds;
                            $(".times").html(hour + ":" + minutes + ":" + seconds);
                            var n = hour + ":" + minutes + ":" + seconds;
                            blueCircle(canvas,context,centerX,centerY,rad,timeIndex,estimates_time);
                            timeIndex++;
                        }
                        setTime();
                        var times  = setInterval(setTime, 1000);
                        var canvas2 = document.getElementById('xx-my-right'),  //获取canvas元素
                            context2 = canvas2.getContext('2d'),  //获取画图环境，指明为2d
                            centerX2 = canvas2.width/2,   //Canvas中心点x轴坐标
                            centerY2 = canvas2.height/2;  //Canvas中心点y轴坐标
                        startRad = 0;
                        endRad = Math.PI*2;
                        var right_scale = response.right_scale;
                        right_scale = Math.round(right_scale*100);
                        whiteCircle(canvas2,context2,centerX2,centerY2,startRad,endRad);
                        innerCircle(canvas2,context2,centerX2,centerY2,Math.PI*(-1/2),endRad,endRad/100,right_scale);
                        $(".xx-right").html("当前正答率<br/>"+right_scale+"%");

                        var canvas3 = document.getElementById('xx-my-tested'),  //获取canvas元素
                            context3 = canvas3.getContext('2d'),  //获取画图环境，指明为2d
                            centerX3 = canvas3.width/2,   //Canvas中心点x轴坐标
                            centerY3 = canvas3.height/2;  //Canvas中心点y轴坐标
                        var has_learnedCode_scale = response.has_learedCode_scale;
                        has_learnedCode_scale = Math.round(has_learnedCode_scale*100);
                        whiteCircle(canvas3,context3,centerX3,centerY3,startRad,endRad);
                        innerCircle(canvas3,context3,centerX3,centerY3,Math.PI*(-1/2),endRad,endRad/100,has_learnedCode_scale);
                        $(".tested").html("已测知识点<br/>"+has_learnedCode_scale+"%");

                        //thisObj.createDomContinue(times);

                        var start_time = new Date().getTime();
                        $(".xx-continue").on("click",function(){
                            var answer_content = [];
                            var answer_val = "",type="",question_id="",answer_url="",topicId="";
                            type=$(".question-sheet").data("type");
                            topicId=$("input[name=topicId]").val();
                            console.log(type);
                            question_id = $(".question-sheet").attr('data-question_id');
                            if(type==2){
                                console.log(question_id);
                                $(".input-p").each(function(i,qv){
                                    if($(qv).find("img").length>0){
                                        var input_answer_val = '',input_answer_base64 = '';
                                        input_answer_base64 = $(qv).find("img").attr("src");
                                        input_answer_val = MY_UI.toSBC($(qv).find("img").attr("data-latex"));
                                        input_answer_val  = input_answer_val==undefined ? '' : input_answer_val;
                                        input_answer_base64 = input_answer_base64==undefined ? '' : input_answer_base64;
                                        if(i>0 && !MY_UI.isEmpty(answer_val)) {
                                            answer_val  += ";"+input_answer_val;
                                            answer_url += "@@@"+input_answer_base64;
                                            console.log("有答案");
                                        } else if(i>0 && MY_UI.isEmpty(answer_val)){
                                            answer_val  += ";"+input_answer_val;
                                            answer_url += "@@@"+input_answer_base64;
                                        }else{
                                            answer_val  += input_answer_val;
                                            answer_url += input_answer_base64;

                                            console.log(answer_val);
                                        }
                                        console.log(answer_val);
                                    }else if(i>0){
                                        answer_val  += ";";
                                        answer_url += "@@@";

                                    }

                                });
                            }else if (type == 1) {
                                answer_val = $("label.checked").data("value");
                                answer_val = answer_val == undefined ? '' : answer_val;
                                question_id = question_id == undefined ? '' : question_id;
                                type = type == undefined ? '' : type;
                                //q_forms=q_forms==undefined ?'' : q_forms;
                                console.log(answer_val);
                            }
                            else if (type == 3) {
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
                            if(MY_UI.isEmpty(answer_val.replace(/(;)/gi, ""))||answer_val=='\\placeholder '){
                                layer.open({
                                    type: 1
                                    ,title: false //不显示标题栏
                                    ,closeBtn: true
                                    ,area: ['420px', '259px']
                                    ,shade: 0.8
                                    ,id: 'submit_confirm' //设定一个id，防止重复弹出
                                    ,resize: false
                                    ,btn: ['确认', '取消']
                                    ,btnAlign: 'c'
                                    ,moveType: 1 //拖拽模式，0或者1
                                    ,content: '<div class="xx-logo-confirm"></div><p>你尚未填写答案，是否确认提交？</p>'
                                    ,success: function(layero){

                                        //var spent_time=second;
                                        var btn = layero.find('.layui-layer-btn');
                                        btn.find('.layui-layer-btn0').on("click",function(){
                                            clearInterval(times);
                                            answer_content.push({question_id:question_id,answer_base64:answer_url,type:type,answer:answer_val});
                                            thisObj.initSubmit(topicId,answer_content,start_time);
                                        });
                                    }
                                });

                            }else{
                                clearInterval(times);
                                //var spent_time=second;
                                answer_content.push({question_id:question_id,type:type,answer_base64:answer_url,answer:answer_val});
                                thisObj.initSubmit(topicId,answer_content,start_time);
                            }
                        });
                        $("#sure").click(function(){
                            $("#submit").ajaxSubmit({
                                url: HOST+"index/Index/submitCorrection", /*设置post提交到的页面*/
                                type: "post", /*设置表单以post方法提交*/
                                dataType: "json", /*设置返回值类型为文本*/
                                success: function (data) {
                                    $("#doc-modal-2 .am-modal-hd").find("a").trigger("click");
                                    console.log(data);
                                    $("#form1").html(type_check);
                                },
                                error: function (error) { console.log(error); }
                            });
                        });
                        $("#cancel").click(function(){
                            $(".ajax-file-upload-red").trigger("click");
                            $("#doc-modal-2 .am-modal-hd").find("a").trigger("click");
                        });
                    }else{
                        //thisObj.getQuestion();
                    }
                    $("#hidden_question_id").val(response.question_list.question_id);

                }else{
                    window.open(HOST+"index/index/preReport/topicId/"+topicId,"_self");
                }

            },
            complete:function(){
                layer.close(index);
            },
            error:function(){
                alert("系统繁忙，请刷新重试或重新登录。");
            }
        });
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
};

// 提交试题
PreIndex.prototype.initSubmit=function(topicId,answer_content,start_time){
    try{
        var thisObj = this;
        var end_time = new Date().getTime();
        var spent_time = end_time -start_time;
        var index = layer.load(0, {shade: 0.8});
        $_CONFIG.page_end_time=end_time;
        $.ajax({
            url: HOST+"/index/index/submitQuestion",
            data:{
                topicId: topicId,
                user_id:$_CONFIG.uid,
                answer_content: answer_content,
                spent_time:spent_time
            },
            type:'POST',
            dataType:'json',
            success: function(response){
                if(typeof response.code != "undefined"){
                    if(response.code==0){
                        alert(response.msg);
                        setTimeout(function(){
                            location.href = "http://"+window.location.host + response.url
                        },response.wait*1000);
                    }
                }else{
                    if(response.isSuccess==1){
                        thisObj.getQuestion();
                    }else {

                    }
                }
            },
            complete:function(){
                layer.close(index)
            },
            error:function(){
                alert("系统繁忙，请刷新重试或重新登录。");
            }
        });
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}

PreIndex.prototype.initCharts = function(){
    $("#xx-time-charts").html("");
    var $chart = '<div style="width:100px;height:100px;float: left">' +
        '<canvas name="xx-my-time" id="xx-my-time" width="90" height="90" style=""></canvas>' +
        '<div class="times" style="position: relative;top: -60px;left: -5px;text-align: center;font-size: 12px;"></div>' +
        '</div>' +
        '<div style="width:100px;height:100px;float: left">' +
        '<canvas name="xx-my-right" id="xx-my-right" width="90" height="90" style=""></canvas>' +
        '<div class="xx-right" style="position: relative;top: -60px;left: -5px;text-align: center;font-size: 12px;"></div>' +
        '</div>' +
        '<div style="width:100px;height:100px;float: left">' +
        '<canvas name="xx-my-tested" id="xx-my-tested" width="90" height="90" style=""></canvas>' +
        '<div class="tested" style="position: relative;top: -60px;left: -5px;text-align: center;font-size: 12px;"></div>' +
        '</div>';
    $("#xx-time-charts").html($chart);
}

// 单选题目
PreIndex.prototype.initQuestionOption=function(param){
    try{
        var title ='<div class="question-sheet"  data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" data-q_forms="' + param.question_list.q_forms + '"><span class="q_type">[单选题]</span><span class="question-id">___('+param.question_list.id+')</span>'+param.question_list.content +'</div>';
        var option = '',optionChild = "";
        var optionNum = param.question_list.options;
        for(var i = 0;i< optionNum.length;i++){
            //console.log(param.question_list.options[i].answer)
            optionChild +=
                '<input type="radio" name="rdo" class="rdolist"/>'
                + '<label  class="rdobox unchecked" data-type="'+param.question_list.q_type+'" data-value="'+param.question_list.options[i].key+'" data-question_id="'+param.question_list.id +'" data-q_forms="'+param.question_list.q_forms+'" >'
                    // + '<span class="check-image"></span><span class="radiobox-content">'+param.question_list.options[i].key+'、'+MY_UI.setContent(MY_UI.htmlspecialcharsDecode(param.question_list.options[i].answer))+'</span>'
                + '<span class="check-image"></span><span class="radiobox-content">'+param.question_list.options[i].key+'、'+param.question_list.options[i].answer+'</span>'
                + '</label>';
        }
        option = '<div class="rdo" data-type="'+param.question_list.q_type+'" data-question_id="'+param.question_list.id+'" data-q_forms="'+param.question_list.q_forms+'">'+optionChild+'</div>';
        return title+option;
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}

// 多选题题目
PreIndex.prototype.initQuestionOptions=function(param){
    try{
        var title = '<div class="question-sheet"  data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" data-q_forms="' + param.question_list.q_forms + '"><span class="q_type">[多选题]</span>' + param.question_list.content + '</div>';
        var option = '', optionChild = "";
        var optionNum = param.question_list.options;
        //console.log(optionNum);
        for (var i = 0; i < optionNum.length; i++) {
            optionChild +=
                '<input type="checkbox" name="chk" class="chklist"/>'
                + '<label  class="chkbox unchecked" data-type="' + param.question_list.q_type + '" data-value="' + param.question_list.options[i].key + '" data-question_id="' + param.question_list.id + '" data-q_forms="' + param.question_list.q_forms + '" >'
                + '<span class="radiobox-content">' + param.question_list.options[i].key + '、' + param.question_list.options[i].answer + '</span>'
                + '</label>';

        }
        option = '<div class="rdo" data-type="' + param.question_list.type + '" data-question_id="' + param.question_list.question_id + '" data-q_forms="' + param.question_list.q_forms + '">' + optionChild + '</div>';
        return title + option;
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
};

PreIndex.prototype.initQuestionInput = function (param) {
    try{
        var content = "";
        var question_content = param.question_list.content;
        if(question_content.indexOf("##$$##")>0){
            var n = (question_content.length - question_content.replace(/##\$\$##/g, "").length) / 6;
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

// 创建提交按钮
PreIndex.prototype.initSubmitButton=function(){
    try{
        var submitButton='<div class="xx-continue" onclick="_czc.push([&apos;_trackEvent&apos;, &apos;先行测试提交点击按钮&apos;, &apos;点击&apos;, &apos;提交&apos;,&apos;5&apos;]);"> <div class="xx-next">提交</div> </div>';
        return submitButton;
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
};

// 报错弹框的
PreIndex.prototype.initErrorOption=function(param){
    console.log(param.question_list.id);
    var option='<form   class="am-g wrong-type" id="submit">'+
        '<div class="type-check">'+
        '<p class="am-u-lg-12">请选择错误类型</p>'+
        '<div class="radio-check am-u-lg-3">'+
        '<input type="radio" id="radio-2-1" data-question_id="'+param.question_list.id+'" name="type" class="regular-radio big-radio" value="1"/>'+
        '<label for="radio-2-1"></label>'+
        '<span>题干错误</span>'+
        '</div>'+
        '<div class="radio-check am-u-lg-3">'+
        '<input type="radio" id="radio-2-2" data-question_id="'+param.question_list.id+'" name="type" class="regular-radio big-radio" value="2"/>'+
        '<label for="radio-2-2"></label>'+
        '<span>答案错误</span>'+
        '</div>'+
        '<div class="radio-check am-u-lg-3">'+
        '<input type="radio" id="radio-2-3" data-question_id="'+param.question_list.id+'" name="type" class="regular-radio big-radio"  checked="checked" value="3"/>'+
        '<label for="radio-2-3"></label>'+
        '<span>系统bug</span>'+
        '</div>'+
        '<div class="radio-check am-u-lg-3">'+
        '<input type="radio" id="radio-2-4" data-question_id="'+param.question_list.id+'" name="type" class="regular-radio big-radio" value="4"/>'+
        '<label for="radio-2-4"></label>'+
        '<span>其他错误</span>'+
        '</div>'+
        '<div class="am-u-lg-12">'+
        '<textarea name="content" id="monent" style="font-size: 16px;" placeholder="请输入错误内容"></textarea>'+
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

//绘制蓝色外圈
function blueCircle(canvas,context,centerX,centerY,rad,n,estimates_time){
    if(n<=estimates_time){
        context.save();
        context.strokeStyle = "#16CC6C"; //设置描边样式
        context.lineWidth = 5; //设置线宽
        context.beginPath(); //路径开始
        context.arc(centerX, centerY, 40 , Math.PI*(3/4), Math.PI*(3/4) +n*rad, false); //用于绘制圆弧context.arc(x坐标，y坐标，半径，起始角度，终止角度，顺时针/逆时针)
        context.stroke(); //绘制
        context.restore();
    }
}
function innerCircle(canvas,context,centerX,centerY,startRad,endRad,rad,n){
    context.save();
    context.strokeStyle = "#16CC6C"; //设置描边样式
    context.lineWidth = 5; //设置线宽
    context.beginPath(); //路径开始
    //console.log(startRad+"-------------"+ n*rad)
    context.arc(centerX, centerY, 40 , startRad, startRad+n*rad, false); //用于绘制圆弧context.arc(x坐标，y坐标，半径，起始角度，终止角度，顺时针/逆时针)
    context.stroke(); //绘制
    context.restore();
}
//绘制白色外圈
function whiteCircle(canvas,context,centerX,centerY,startRad,endRad){
    context.save();
    context.beginPath();
    context.strokeStyle = "#eaeaea";
    context.lineWidth = 5; //设置线宽
    context.arc(centerX, centerY, 40 , startRad, endRad, false);
    context.stroke();
    context.restore();
}