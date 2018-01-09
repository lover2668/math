/**
 * Created by linxiao on 2017/5/20.
 */
function SummerAppBackIndex(opts){
    this.opts = opts;
    this.domReady();
}
SummerAppBackIndex.prototype.domReady = function () {
    try{
        var thisObj = this;
        var question = thisObj.getQuestion();
        var question_list = question.question_list,q_type = question_list.q_type,question_title = question_list.content,question_id=question_list.id;;
        thisObj.createDomTitle(question);
        if(q_type == "2"){
            $("[name='xx-question-option']").html("");
            var inputDom = '<span class="input_editor" >请输入答案</span>';
            question_title = MY_UI.rulesFilter(question_title,inputDom);
            $("[name='xx-question-title']").html(question_title).attr("data-q-type",q_type).attr("data-question-id",question_id);
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
        }else{
            thisObj.createDomOption(question);
        }
        var estimates_time = question.question_list.estimates_time;
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
            console.log(timeIndex)
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
        var right_scale = question.right_scale;
        right_scale = Math.round(right_scale*100);
        whiteCircle(canvas2,context2,centerX2,centerY2,startRad,endRad);
        innerCircle(canvas2,context2,centerX2,centerY2,Math.PI*(-1/2),endRad,endRad/100,right_scale);
        $(".xx-right").html("当前正答率<br/>"+right_scale+"%");

        var canvas3 = document.getElementById('xx-my-tested'),  //获取canvas元素
            context3 = canvas3.getContext('2d'),  //获取画图环境，指明为2d
            centerX3 = canvas3.width/2,   //Canvas中心点x轴坐标
            centerY3 = canvas3.height/2;  //Canvas中心点y轴坐标
        var has_learnedCode_scale = question.has_learedCode_scale;
        has_learnedCode_scale = Math.round(has_learnedCode_scale*100);
        whiteCircle(canvas3,context3,centerX3,centerY3,startRad,endRad);
        innerCircle(canvas3,context3,centerX3,centerY3,Math.PI*(-1/2),endRad,endRad/100,has_learnedCode_scale);
        $(".tested").html("已学知识点<br/>"+has_learnedCode_scale+"%");
        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
        thisObj.createDomContinue(times);
        $(".xx-report").mouseover(function(){
            $(".hover-li").show();
        }).mouseout(function(){
            $(".hover-li").hide();
        });
        $('#confirmTab').on('show.bs.modal', centerModals);
        //$('#knowledge-video').on('show.bs.modal', centerModals);
        $(window).resize(function () {
            $('#confirmTab').on('show.bs.modal', centerModals);
            //$('#knowledge-video').on('show.bs.modal', centerModals);
        });
        //$('#confirmTab').modal('show');
        /* center modal */
        function centerModals() {
            $('.modal').each(function(i) {
                var $clone = $(this).clone().css('display', 'block').appendTo('body'); var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
                top = top > 0 ? top : 0;
                $clone.remove();
                $(this).find('.modal-content').css("margin-top", top);
            });
        }
        $(window).on('resize', centerModals);
        setTimeout(function(){
            $("#loading").css("display","none");
        },300);
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
};
SummerAppBackIndex.prototype.initCharts = function(){
    $("#xx-time-charts").html("");
    var $chart = '<div style="width:100px;height:100px;float: left">' +
        '<canvas name="xx-my-time" id="xx-my-time" width="90" height="90" style="margin-top: 24px;"></canvas>' +
        '<div class="times" style="position: relative;top: -60px;left: -5px;text-align: center;font-size: 12px;"></div>' +
        '</div>' +
        '<div style="width:100px;height:100px;float: left">' +
        '<canvas name="xx-my-right" id="xx-my-right" width="90" height="90" style="margin-top: 24px;"></canvas>' +
        '<div class="xx-right" style="position: relative;top: -60px;left: -5px;text-align: center;font-size: 12px;"></div>' +
        '</div>' +
        '<div style="width:100px;height:100px;float: left">' +
        '<canvas name="xx-my-tested" id="xx-my-tested" width="90" height="90" style="margin-top: 24px;"></canvas>' +
        '<div class="tested" style="position: relative;top: -60px;left: -5px;text-align: center;font-size: 12px;"></div>' +
        '</div>';
    $("#xx-time-charts").html($chart);
}
var  question_answer = "";

SummerAppBackIndex.prototype.getQuestion = function () {
    var question;
    var opts = this.opts;
    var topicId = opts.topicId;
    var question_id = $('input[name=question_id]').val();
    var is_test = $('input[name=is_test]').val();
    url = "http://"+window.location.host+"/index.php/"+"summer/test/getExamQuestions";
    $.ajax({
        url:url,
        data: {
            topicId:topicId,
            question_id:question_id,
            is_test:is_test
        },
        type: 'POST',
        dataType: 'json',
        cache: false,
        async: false,
        success: function (response) {
            if(response.is_end == 0){
                try{
                    question = response;
                    question_answer  =  question.question_list.answer.toString();
                }catch(err){
                    console.log("Error name: " + err.name + "");
                    console.log("Error message: " + err.message);
                }finally {}
            }else if(response.is_end == 1){
                window.open("http://"+window.location.host+"/index.php/"+"/summer/index/middlePage/topicId/"+topicId,"_self")
            }
        },
        complete: function () {
        },
        error:function(){
            alert("系统繁忙，请刷新重试或重新登录。");
        }
    });
    return question;
}
// 提交试题
SummerAppBackIndex.prototype.initSubmit = function (topicId, answer_content,start_time) {
    try{
        var thisObj = this;
        var opts = thisObj.opts;
        var topicId = opts.topicId;
        var end_time = new Date().getTime();
        var spent_time = end_time - start_time;

        $("#loading").css("display","block");

        type = $('input[name=type]').val();
        if(type==1)
        {
            url = "http://"+window.location.host+"/index.php/"+"summer/test/submitQuestion";
        }else if(type == 2)
        {
            url = "http://"+window.location.host+"/index.php/"+"summer/test/submitLatexEqualQuestion";
        }
        // url = "http://"+window.location.host+"/index.php/"+"summer/test/submitQuestion";

        $.ajax({
            url: url,
            data: {
                topicId: topicId,
                answer_content: answer_content,
                spent_time:spent_time
            },
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.isSuccess == 1) {

                        answer_content_test = answer_content[0]['answer'].toString();
                        check_user_answer = response.check_user_answer;
                        check_tiku_answer = response.check_tiku_answer;
                        if(response.is_right)
                        {
                            msg ="答对了， 题库答案为：**************"+question_answer+"************* 用户输入答案：*********"+answer_content_test+" 结束***********";
                            msg += " 题库转换后的答案是：********"+check_tiku_answer+"**********,用户答案转换后：************ "+check_user_answer;

                            alert(msg);
                        }else{
                            msg ="答错错错错错错了， 题库答案为：**************"+question_answer+"************* 用户输入答案：*********"+answer_content_test+"结束*******";
                            msg += " 题库转换后的答案是：********"+check_tiku_answer+"**********,用户答案转换后：************ "+check_user_answer;

                            alert(msg);
                        }

                } else {}
            },
            complete: function () {
                $("#loading").css("display","none");
            }
        });
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
//渲染题干信息
SummerAppBackIndex.prototype.createDomTitle = function (opts) {
    try{
        if(opts){
            $("[name=xx-tagcode]").html(opts.tag_name)
            var id= opts.has_answered_questions.length,question_list = opts.question_list,q_type = question_list.q_type,q_type_name = "",
                qId = "",question_id=opts.question_list.id;
            id = id + 1;
            if(id<10){
                qId = "0"+id;
            }else{
                qId = id;
            }
            switch (q_type){
                case 1 :
                    q_type_name = "单选题";
                    break;
                case 2 :
                    q_type_name = "填空题";
                    break;
                default :
                    q_type_name = "多选题";
                    break;
            }
            $("[name='xx-question-type']").html(qId+"&nbsp;"+q_type_name);
            if(q_type != 2){
                var question_title = question_list.content;
                $("[name='xx-question-title']").html(question_title).attr("data-q-type",q_type).attr("data-question-id",question_id);
            }
        }else{
            alert("题目信息有误！")
        }
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
SummerAppBackIndex.prototype.createDomContinue = function (times) {
    try{
        var thisObj = this;
        $("[name='xx-continue']").html('<hr/><div name="continue" class="xx-continue-button">提交 </div>');
        var start_time = new Date().getTime();
        $("[name='continue']").on("click", function () {
            clearInterval(times);
            var answer_content = [];
            var answer_val = "",type="",question_id="",answer_url="",topicId="";
            type=$("[name='xx-question-title']").attr("data-q-type");
            topicId=$("input[name=topicId]").val();
            console.log(type);
            question_id = $("[name='xx-question-title']").attr('data-question-id');
            if(type==2){
                $(".input_editor").each(function(i,qv){
                    if($(qv).find("img").length>0){
                        var input_answer_val = '',input_answer_base64 = '';
                        input_answer_base64 = $(qv).find("img").attr("src");
                        input_answer_val = MY_UI.toSBC($(qv).find("img").attr("data-latex"));
                        input_answer_val  = input_answer_val==undefined ? '' : input_answer_val;
                        input_answer_base64 = input_answer_base64==undefined ? '' : input_answer_base64;
                        if(i>0 && !MY_UI.isEmpty(answer_val)) {
                            answer_val  += ";"+input_answer_val;
                            answer_url += "@@@"+input_answer_base64;
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
                answer_val = $("input:checked").val();
                answer_val = answer_val == undefined ? '' : answer_val;
                question_id = question_id == undefined ? '' : question_id;
                type = type == undefined ? '' : type;
                //q_forms=q_forms==undefined ?'' : q_forms;
                console.log(answer_val);
            }
            else if (type == 3) {
                $("input:checked").each(function (i, qv) {
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
            answer_content.push({question_id:question_id,type:type,answer_base64:answer_url,answer:answer_val});
            if(MY_UI.isEmpty(answer_val.replace(/(;)/gi, ""))||answer_val=='\\placeholder '){
                $('#confirmTab').on('show.bs.modal', centerModals);
                $('#confirmTab').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var modal = $(this);
                    modal.find('.xx-btn-confirm').unbind("click").on("click",function(){
                        $('#confirmTab').modal('hide');
                        thisObj.initSubmit(topicId,answer_content,start_time);
                    });
                })
                $(window).resize(function () {
                    $('#confirmTab').on('show.bs.modal', centerModals);
                    $('#knowledge-video').on('show.bs.modal', centerModals);
                });
                $('#confirmTab').modal('show');
            }else{
                thisObj.initSubmit(topicId,answer_content,start_time);
            }
        })
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
SummerAppBackIndex.prototype.createDomOption = function (opts) {
    try{
        $("[name='xx-question-option']").html("");
        var optionChild = "";
        var quetion_type = opts.question_list.q_type;
        if (quetion_type == 1) {
            var question_list = opts.question_list;
            var options = question_list.options,option_key;
            try {
                for (var i = 0; i < options.length; i++) {
                    var option_key = options[i].key,
                        option = options[i].answer;
                    optionChild += '<div class="xx-select-box">' +
                        '<label for="radio-'+option_key+'">' +
                        '<input id="radio-'+option_key+'" type="radio" name="optionsRadios" value="'+option_key+'">' +
                        '<div>'+option_key+'.&nbsp;'+option+'</div>' +
                        '</label>' +
                        '</div>';
                }
            } catch(err) {
                console.log("Error name: " + err.name + "");
                console.log("Error message: " + err.message);
            }
            finally{
                console.log("object is null");
            }
            $("[name='xx-question-option']").html(optionChild);
        } else if (quetion_type == 3) {
            var question_list = opts.question_list;
            var options = question_list.options,option_key;
            for (var i = 0; i < options.length; i++) {
                var option_key = options[i].key,
                    option = options[i].answer;
                optionChild += '<div class="xx-select-box">' +
                    '<label for="checkbox-'+option_key+'">' +
                    '<input id="checkbox-'+option_key+'" type="checkbox" value="B">' +
                    '<div>'+option_key+'.&nbsp;'+option+'</div>' +
                    '</label>' +
                    '</div>';
            }
            $("[name='xx-question-option']").html(optionChild);
        }
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
/* center modal */
function centerModals() {
    $('.modal').each(function(i) {
        var $clone = $(this).clone().css('display', 'block').appendTo('body'); var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
        top = top > 0 ? top : 0;
        $clone.remove();
        $(this).find('.modal-content').css("margin-top", top);
    });
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
    console.log(startRad+"-------------"+ n*rad)
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