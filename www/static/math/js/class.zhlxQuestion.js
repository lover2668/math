/**
 * Created by sks on 2016/12/1.
 */
function ZhlxIndex(ui){
    try{
        this.ui=ui;
        this.domReady();
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}


ZhlxIndex.prototype.domReady=function(){
    try{
        var thisObj = this;
        thisObj.getQuestion();
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
};
ZhlxIndex.prototype.getQuestion=function(){
    try{
        var index = layer.load(0, {shade: 0.8});
        var thisObj = this;
        var topicId=$("input[name=topicId]").val();
        $.ajax({
            url: HOST+"/index/Zhlx/getExamQuestions",
            data:{
                //initKStatus: initKStatus,
                topicId: topicId
            },
            type:'POST',
            dataType:'json',
            success: function(response){
                if(response.is_end==0){
                    if(!MY_UI.isEmpty(response.question_list))
                    {
//                    $.get(HOST+"/index/Zhlx/getKnowledgeByCode?tag_code="+response.tag_code+"&topicId="+topicId,'',function(data){
//                        eval('data=('+data+')');
//                        $('.xx-equation').html(data.tag_name);
//                    });
                        //var sheetArea = thisObj.initQuestionTitle(response);
                        var sheetArea="";
                        var question_id=response.question_list.id;
                        $_CONFIG.question_id = question_id;
                        if(response.question_list.q_type == 1){
                            sheetArea += thisObj.initQuestionOption(response);
                            $("#question-sheet").html(sheetArea);
                        }else if(response.question_list.q_type == 2){
                            sheetArea += thisObj.initQuestionInput(response);
                            $("#question-sheet").html(sheetArea);
                        }else{
                            sheetArea += thisObj.initQuestionOptions(response);
                            $("#question-sheet").html(sheetArea);
                        }
                        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
                        // sheetArea +=thisObj.initSubmitButton();
                        /*选项*/
                        if(response.question_list.q_type == 1){
                            $(".rdolist").labelauty("rdolist", "rdo");
                        }else if(response.question_list.q_type == 2){
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
                            /*多选选项*/
                            $(".chklist").labelauty("chklist", "check");
                        }
                        // $(".right-img").html(thisObj.initKnOption());
                        //试题题号
                        var questionlength = response.has_answered_questions.length+1;
                        $(".xx-question-num").html(questionlength);
                        thisObj.initQuestionAnalyse(response);
                        $("#question-sheet").append(thisObj.initSubmitButton());
                        var is_view_analyze = 0;
                        $(".right-test-2").on("click", function () {
                            is_view_analyze=1;
                            thisObj.openAnalyse("analyse");
                            $('html,body').animate({scrollTop:$('.bottom').offset().top}, 800);
                            $(".step-analysis").slideDown("slow");
                            $(".xx-question-analyse-close").fadeIn("slow");
                        });
                        $(".icon-delete").click(function(){
                            $(".step-analysis").slideUp(500);
                        });
                        var is_view_answer=0;
                        console.log($("#find_answer"));
                        $("#find_answer").click(function(){
                            layer.open({
                                type: 1
                                , title: false //不显示标题栏
                                , closeBtn: true
                                , area: ['420px', '259px']
                                , shade: 0.8
                                , id: 'find_confirm' //设定一个id，防止重复弹出
                                , resize: false
                                , btn: ['查看', '取消']
                                , btnAlign: 'c'
                                , moveType: 1 //拖拽模式，0或者1
                                , content: '<div class="xx-logo-confirm"></div><p>查看答案后该题将自动判错，是否查看？</p>'
                                , success: function (layero) {
                                    var btn = layero.find('.layui-layer-btn');
                                    btn.find('.layui-layer-btn0').on("click", function () {
                                        is_view_answer=1;
                                        // var answer = "";
                                        // if(response.question_list.q_type==2){
                                        //     answer ='<li  class="xx-analyse-step" id="find-answer" style="display: none">' +
                                        //         '<p class="xx-step-name">正确答案</p>' +
                                        //         '<p class="xx-step-title"><img src="'+response.question_list.answer_base64+'" /></p>' +
                                        //         '</li>';
                                        // }else{
                                        //     answer ='<li  class="xx-analyse-step" id="find-answer" style="display: none">' +
                                        //         '<p class="xx-step-name">正确答案</p>' +
                                        //         '<p class="xx-step-title">'+MY_UI.htmlspecialcharsDecode(response.question_list.answer)+'</p>' +
                                        //         '</li>';
                                        // }
                                        // $(".xx-analyse-step-group").append(answer);
                                        $("#find-answer").fadeIn();
                                        $("#find_answer").fadeOut();
                                    });
                                }
                            });

                        });
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
                                    $("#doc-modal-2 .am-modal-hd").find("a").trigger("click");
                                    console.log(data);
                                    $("#form1").html(type_check);
                                },
                                error: function (error) { alert(error); }
                            });
                        });
                        $("#cancel").click(function(){
                            $(".ajax-file-upload-red").trigger("click");
                            $("#doc-modal-2 .am-modal-hd").find("a").trigger("click");
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

                        //var second=0;
                        //var timer=setInterval(function(){
                        //    second+=1;
                        //},1);
                        var start_time = new Date().getTime();
                        $(".xx-continue").on("click",function(){
                            var answer_content = [];
                            var answer_val = "",type="",question_id="",answer_url="",topicId="";
                            type=$(".question-sheet").data("type");
                            topicId=$("input[name=topicId]").val();
                            console.log(type);
                            var flag = $(this).data("flag");
                            if(type==2){
                                question_id = $(".question-sheet").attr('data-question_id');
                                console.log(question_id);
                                $(".input-p").each(function(i,qv){
                                    console.log($(qv));
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
                                        }else if(i>0 && MY_UI.isEmpty(answer_val)){
                                            answer_val  += ";"+input_answer_val;
                                            answer_url += "@@@"+input_answer_base64;
                                        } else{
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
                            }else if(type==1){
                                question_id =$(".question-sheet").attr('data-question_id');
                                answer_val = $("label.checked").data("value");
                                answer_val  = answer_val==undefined ? '' : answer_val;
                                question_id = question_id==undefined ? '' : question_id;
                                // question_type = question_type==undefined ? '' : question_type;
                            }

                            if(flag == 0 && (MY_UI.isEmpty(answer_val.replace(/(;)/gi, ""))||answer_val=='\\placeholder ')){
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
                                        //clearInterval(timer);
                                        //var spent_time=second;
                                        var btn = layero.find('.layui-layer-btn');
                                        btn.find('.layui-layer-btn0').on("click",function(){
                                            $(".xx-continue").data("flag", 2);
                                            clearInterval(times);
                                            answer_content.push({question_id:question_id,answer_base64:answer_url,type:type,answer:answer_val,is_view_answer:is_view_answer,is_view_analyze:is_view_analyze});
                                            thisObj.initSubmit(topicId,answer_content,start_time);
                                        });
                                    }
                                });

                            }else if (flag == 0 && !MY_UI.isEmpty(answer_val.replace(/(;)/gi, "")) && answer_val != '\\placeholder ') {
                                $(".xx-continue").data("flag", 2);
                                //clearInterval(timer);
                                //var spent_time=second;
                                answer_content.push({
                                    question_id: question_id,
                                    type: type,
                                    answer_base64: answer_url,
                                    answer: answer_val,
                                    is_view_answer:is_view_answer,
                                    is_view_analyze:is_view_analyze
                                });
                                clearInterval(times);
                                thisObj.initSubmit(topicId, answer_content,start_time);
                            } else {
                                thisObj.getQuestion();
                            }
                            // else{
                            //     answer_content.push({question_id:question_id,type:type,answer_base64:answer_url,answer:answer_val,is_view_answer:is_view_answer,is_view_analyze:is_view_analyze});
                            //     thisObj.initSubmit(topicId,answer_content);
                            // }
                        });
                    }else{
                        //thisObj.getQuestion();
                    }
                    // var myPlayer = _V_('example_video_1');  //初始化视频
                    // $("#xx-video-close-3").on("click",function(event){
                    //     myPlayer.pause();
                    // });
                    var $modal2 = $('#doc-modal-1')
                    $(".right-test-1").on("click", function (e) {
                        var $target = $(e.target);
                        console.log(($target).hasClass('js-modal-open'));
                        console.log(($target).hasClass('js-modal-close'));
                        console.log($modal2);
                        if (($target).hasClass('js-modal-open')) {
                            $modal2.modal();
                        } else if (($target).hasClass('js-modal-close')) {
                            $modal2.modal('close');
                        } else {
                            $modal2.modal('toggle');
                        }
                    });
                }else{
                    window.open(HOST+"index/zhlx/zhlxReport/topicId/"+topicId,"_self");
                    // $(".xx-next").html("结束");
                    // $(".am-btn").trigger("click");

                }

            },
            complete:function(){
                layer.close(index);
            },
            error:function(){
                alert("系统繁忙，请刷新重试或重新登录。");
            }
        });
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
};

//提交
ZhlxIndex.prototype.initSubmit=function(topicId,answer_content,start_time){
    try{
        var thisObj = this;
        var end_time = new Date().getTime();
        var index = layer.load(0, {shade: 0.8});
        var spent_time = end_time -start_time;
        $_CONFIG.page_end_time=end_time;
        $.ajax({
            url: HOST+"/index/Zhlx/submitQuestion",
            data:{
                topicId: topicId,
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
                        if ('getContext' in document.createElement('canvas')) {
                            HTMLImageElement.prototype.play = function() {
                                if (this.storeCanvas) {
                                    // 移除存储的canvas
                                    this.storeCanvas.parentElement.removeChild(this.storeCanvas);
                                    this.storeCanvas = null;
                                    // 透明度还原
                                    image.style.opacity = '';
                                }
                                if (this.storeUrl) {
                                    this.src = this.storeUrl;
                                }
                            };
                            HTMLImageElement.prototype.stop = function() {
                                var canvas = document.createElement('canvas');
                                // 尺寸
                                var width = this.width, height = this.height;
                                if (width && height) {
                                    // 存储之前的地址
                                    if (!this.storeUrl) {
                                        this.storeUrl = this.src;
                                    }
                                    // canvas大小
                                    canvas.width = width;
                                    canvas.height = height;
                                    // 绘制图片帧（第一帧）
                                    canvas.getContext('2d').drawImage(this, 0, 0, width, height);
                                    // 重置当前图片
                                    try {
                                        this.src = canvas.toDataURL("img/gif");
                                    } catch(e) {
                                        // 跨域
                                        this.removeAttribute('src');
                                        // 载入canvas元素
                                        canvas.style.position = 'absolute';
                                        // 前面插入图片
                                        this.parentElement.insertBefore(canvas, this);
                                        // 隐藏原图
                                        this.style.opacity = '0';
                                        // 存储canvas
                                        this.storeCanvas = canvas;
                                    }
                                }
                            };
                        }
                        $(".gif").css("display","block");
                        if (response.is_right == 0) {
                            var wrong2Gif=$("input[name=wrong2]").val();
                            $(".gif-img").html("<img src='"+wrong2Gif+"' id='testImg'/>");
                            var imgGif=document.getElementById("testImg");
                            imgGif.play();
                            setTimeout(function(){
                                imgGif.stop();
                                $(".gif").css("display","none");
                                thisObj.openAnalyse();

                            },2000);
                        } else {
                            var rightGif=$("input[name=right]").val();
                            $(".gif-img").html("<img src='"+rightGif+"' id='testImg'/>");
                            var imgGif=document.getElementById("testImg");
                            imgGif.play();
                            setTimeout(function(){
                                imgGif.stop();
                                $(".gif").css("display","none");
                                thisObj.openAnalyse();

                            },2000);
                        }
                    }else {

                    }
                }

            },
            complete:function(){
                layer.close(index);
            },
            error:function(){
                alert("系统繁忙，请刷新重试或重新登录。");
            }
        });
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
};

/*
 * 创建提交按钮
 */
ZhlxIndex.prototype.initSubmitButton = function () {
    var submitButton = '<div class="modal-container"> <div class="xx-continue" data-flag="0" id="xx-over"> <div class="xx-next js-modal-open">提交</div> </div> </div>';
    return submitButton;
}

/*
 * 初始化题目填空
 */
ZhlxIndex.prototype.initQuestionInput = function (param) {
    try{
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
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
/*
 * 初始化题目选项
 */
ZhlxIndex.prototype.initQuestionOption = function (param) {
    try{
        var title = "<div class='question-sheet' data-type='" + param.question_list.q_type + "' data-question_id='" + param.question_list.id + "'><span class='q_type'>[单选题]</span><span class='question-id'>___("+param.question_list.id+")</span>"+param.question_list.content.replace(/##\$\$##/g, "_____")+"</div>"
        //+"<div style='color: red'>这是测试内容："+param.question_list.id+"&nbsp;知识点标签tag_code:"+param.tag_code+"&nbsp;知识点难度:"+param.question_list.difficulty+"</div>";
        var option = '',optionChild = "";
        var optionNum = param.question_list.options;
        for(var i = 0;i< optionNum.length;i++){
            optionChild += '<input type="radio" name="rdo" class="rdolist"/>'
                + '<label class="rdobox unchecked" data-type="'+param.question_list.q_type+'" data-question_id="'+param.question_list.id+'" data-value="'+param.question_list.options[i].key+'" class="rdobox">'
                + '<span class="radiobox-image">'+param.question_list.options[i].key+'、'+param.question_list.options[i].answer+'</span>'
                + '</label>';
        }
        option = '<div class="rdo" data-type="'+param.question_list.q_type+'" data-question_id="'+param.question_list.id+'">'+optionChild+'</div>';
        return title+option;
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
// 多选题题目
ZhlxIndex.prototype.initQuestionOptions=function(param){
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
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
};
ZhlxIndex.prototype.initCharts = function(){
    $("#xx-time-charts").html("");
    var $chart = '<div style="width:100px;height:100px;float: left">' +
        '<canvas name="xx-my-time" id="xx-my-time" width="90" height="90" style=""></canvas>' +
        '<div class="times" style="position: relative;top: -60px;left: -5px;text-align: center;font-size: 12px;"></div>' +
        '</div>' +
        '<div style="width:100px;height:100px;float: left">' +
        '<canvas name="xx-my-right" id="xx-my-right" width="90" height="90" style=""></canvas>' +
        '<div class="xx-right" style="position: relative;top: -60px;left: -5px;text-align: center;font-size: 12px;"></div>' +
        '</div>';
    $("#xx-time-charts").html($chart);
}
/*
 * 初始化解析
 */
ZhlxIndex.prototype.initQuestionAnalyse = function (param,type)
{
    try{
        if($(".step-analysis").css("display")!="none"){
            $(".step-analysis").hide();
            $("#xx-question-analyse").hide();
        }
        var optionChild="";
        var liLength = param.question_list.analyze[0].content.length;
        for (var i = 0; i < liLength; i++) {
            var n = i+1;
            optionChild +=
                '<li  class="xx-analyse-step" data-is_has_answer="'+param.question_list.analyze[0].content[i].is_has_answer+'" data-step="'+i+'" style="display: none">' +
                '<p class="xx-step-name">步骤'+n+'/'+liLength+'</p>' +
                '<p class="xx-step-title">'+(param.question_list.analyze[0].content[i].content)+'</p>' +
                '</li>';
        }
        var buttonOption='<div class="box bottom"></div><div class="xx-analyse-next-step" style="display: none" id="next_step">下一步</div>'+
            '<div class="xx-analyse-next-step" style="display: none" id="find_answer">查看答案</div>'+
            '<div class="xx-analyse-next-step" style="display: none" id="next_q">继续答题</div>';
        $("#optionButton").html(buttonOption);
        if(liLength<=1){
            $("#find_answer").show();
        }else{
            $("#next_step").show();
        }
        $(".xx-analyse-step-group").html(optionChild);
        var answer = "";
        if(param.question_list.q_type==2){
            if(param.question_list.answer_base64.length<1){
                $("#xx-step-right").append("null");
            }else{
                for(var i=0;i<param.question_list.answer_base64.length;i++){
                    for(var j=0;j<param.question_list.answer_base64[i].length;j++){
                        var answer_f = param.question_list.answer[i][j];
                        if(answer_f.indexOf("\\(")>-1||answer_f.indexOf("\\[")>-1){
                            answer+='<li  class=" find-answer" style="display: none">' +
                                    // '<p class="xx-step-name">正确答案</p>' +
                                    // '<p class="xx-step-title"><img src="'+param.question_list.answer_base64[i][j]+'" /></p>'
                                    //'<p class="xx-step-title">\\('+param.question_list.answer[i][j]+'\\)</p>'
                                '<p class="xx-step-title">'+(param.question_list.answer[i][j])+'</p>'
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
            answer ='<li  class="xx-analyse-step find-answer" style="display: none">' +
                '<p class="xx-step-name">正确答案</p>' +
                '<p class="xx-step-title">'+param.question_list.answer+'</p>' +
                '</li>';
            $(".xx-analyse-step-group").append(answer);
        }
        $(".xx-analyse-step-group li.xx-analyse-step").eq(0).show();
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}

ZhlxIndex.prototype.openAnalyse = function(param){
    try{
        if(param=="analyse"){
            var step = 0;
            var $num = 0;
            var liLength = $(".xx-analyse-step-group li.xx-analyse-step").length-1;
            console.log(liLength);
            if(liLength>1){
                $("#next_step").on("click",function(){
                    $num++;
                    $(".xx-analyse-step-group li[data-step="+$num+"]").show();
                    if($num==(liLength-1)){
                        $("#next_step").hide();
                        $("#find_answer").show().on("click",function(){
                            $("#find-answer").show();
                        });
                    }
                });
            }else{
            }
            $("#next_step").on("click",function(){
                step++;
                $(".xx-analyse-step-group li.xx-analyse-step").eq(step).show();
                if(step==(liLength-1)){
                    $("#next_step").hide();
                    $("#find_answer").show().on("click",function(){
                        $("#find-answer").show();
                    });
                }

            });
        }else{
            $(".xx-next").html("下一题");
            $(".container-in .step-analysis .xx-analyse-next-step").fadeOut();
            $(".step-analysis li.xx-analyse-step").each(function(i){
                $(this).fadeIn();
            })
            $("#xx-step-right").fadeIn();
            $(".find-answer").show();
        }
        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
        $(".step-analysis").slideDown("slow");
        $(".icon-delete").fadeIn("slow");
        $('html,body').animate({scrollTop:$('.bottom').offset().top}, 800,function(){
            console.log("animateanimateanimate")
            $('html,body').stop();

        });
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}

// 报错弹框的
ZhlxIndex.prototype.initErrorOption=function(param){
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
};
//右侧步骤解析、知识点讲解视频图片
ZhlxIndex.prototype.initKnOption=function(){
    var option='<div id="xx-right">'+
        '<div class="right-test">'+
        '<p>遇到困难了吗？</p>'+
    '<p>可以查看下列资料</p>'+
    '</div>'+
    '<div class="right-test-1 js-modal-open">'+
        '<span></span>'+
        '<a >知识点讲解视频</a>'+
        '</div>'+
        '<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">'+
        '<div class="am-modal-dialog">'+
        '<div class="am-modal-hd">'+
        '<a href="javascript: void(0)" data-am-modal-close id="xx-video-close-3"></a>'+
        '</div>'+
        '<div class="am-modal-bd">'+
        '<div id="anasy-video">'+
        '<video id="example_video_1" class="video-js vjs-amazeui" controls preload="none" width="700" height="390" poster="video.png" data-setup="{}">'+
        '<source src="http://media1.classba.cn/dr.mp4" type="video/mp4"/>'+
        '<track kind="captions" src="video.js/demo.captions.vtt" srclang="en" label="English"></track>'+
    '<track kind="subtitles" src="video.js/demo.captions.vtt" srclang="en" label="English"></track>'+
    '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p> </video>'+

    '</div>'+

    '</div>'+
    '</div>'+
    '</div>'+
    '<div class="right-test-2">'+
        '<span></span>'+
        '<a>分步解析</a>'+
        '</div>'+
        '<div class="right-test-3">'+
        '<span style="font-size: 12px;">更多敬请期待更多内容！！</span>'+
    '</div>'+
    '</div>';
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