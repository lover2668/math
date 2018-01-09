/**
 * Created by sks on 2016/11/1.
 */
function GxIndex(ui) {
    try{
        this.ui = ui;
        this.current_tag_code = 0;
        this.domReady();
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
GxIndex.prototype.domReady = function () {
    try{
        var thisObj = this;
        localStorage.clear();
        thisObj.getQuestion();
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
//提交操作
GxIndex.prototype.initSubmit = function (topicId, answer_content, flag,redoHtml,start_time) {
    try{
        var thisObj = this;
        var end_time = new Date().getTime();
        var spent_time = end_time - start_time;
        $_CONFIG.page_end_time=end_time;
        var index = layer.load(0, {shade: 0.8});
        $.ajax({
            url: HOST + 'index/bxbl/submitQuestion',
            data: {
                topicId: topicId,
                user_id:$_CONFIG.uid,
                answer_content: answer_content,
                spent_time:spent_time
            },
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                //console.log(flag);
                if (response.isSuccess == 1) {
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
                    if (answer_content[0].is_view_answer==0&&response.is_right == 0 && flag == 0) {

                        var content_html = "";
                        console.log("qweqweqwe="+answer_content[0].answer.replace(/(;)/gi, ""));
                        if(MY_UI.isEmpty(answer_content[0].answer.replace(/(;)/gi, ""))){
                            // content_html = '<div class="answer-wrong-next" id="error-cont"></div>';
                            var wrong1Gif=$("input[name=wrong2]").val();
                            $(".gif-img").html("<img src='"+wrong1Gif+"' id='testImg'/>");
                        }else{
                            // content_html = '<div class="answer-wrong" id="error-cont"></div>';
                            var wrong2Gif=$("input[name=wrong1]").val();
                            $(".gif-img").html("<img src='"+wrong2Gif+"' id='testImg'/>");
                        }
                        var imgGif=document.getElementById("testImg");
                        imgGif.play();
                        setTimeout(function(){
                            imgGif.stop();
                            $(".gif").css("display","none");
                            if(MY_UI.isEmpty(answer_content[0].answer.replace(/(;)/gi, ""))){
                                thisObj.openAnalyse();
                            }else{
                                $(".xx-question-sheet").html(redoHtml);
                                MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
                                $(".xx-continue-inner").html("提交");
                                if (answer_content[0].type == 1) {
                                    /*单选选项*/
                                    $(".rdolist").labelauty("rdolist", "rdo");
                                } else if (answer_content[0].type == 2) {
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
                                } else if (answer_content[0].type == 3) {
                                    /*duo选选项*/
                                    $(".chklist").labelauty("chklist", "check");
                                }
                                $(".xx-continue").on("click", function () {
                                    var answer_content = [];
                                    var answer_val = "", type = "", question_id = "", answer_url = "", topicId = "";
                                    type = $(".question-sheet").data("type");
                                    question_id = $(".question-sheet").data("question_id");
                                    topicId = $("input[name=topicId]").val();
                                    var flag = $(this).data("flag");
                                    console.log("type---"+type);
                                    if (type == 2) {
                                        question_id = $(".input-p").attr('data-question_id');
                                        $(".input-p").each(function (i, qv) {
                                            console.log("input---"+i);
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
                                            } else if(i>0){
                                                answer_val  += ";";
                                                answer_url += "@@@";

                                            }
                                            console.log("answer_val===="+answer_val)
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
                                    $(".xx-continue").data("flag", 2);
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
                                                var btn = layero.find('.layui-layer-btn');
                                                btn.find('.layui-layer-btn0').on("click", function () {
                                                    answer_content.push({
                                                        question_id: question_id,
                                                        answer_base64: answer_url,
                                                        type: type,
                                                        answer: answer_val
                                                    });
                                                    thisObj.initRedoSubmit(topicId, answer_content, flag,redoHtml);
                                                });
                                            }
                                        });

                                    } else if (flag == 0 && !MY_UI.isEmpty(answer_val.replace(/(;)/gi, "")) && answer_val != '\\placeholder ') {
                                        answer_content.push({
                                            question_id: question_id,
                                            type: type,
                                            answer_base64: answer_url,
                                            answer: answer_val
                                        });
                                        thisObj.initRedoSubmit(topicId, answer_content, flag,redoHtml);
                                    } else {
                                        thisObj.getQuestion();
                                    }
                                });
                            }
                        },2000);
                        // var image_error = document.getElementById("testImg");
                        // image_error.play();

                        // layer.open({
                        //     content: content_html,
                        //     closeBtn:0,
                        //     time:1000,
                        //     title: false,
                        //     btn:"",
                        //     success: function(layero, index){
                        //         if(MY_UI.isEmpty(answer_content[0].answer.replace(/(;)/gi, ""))){
                        //             thisObj.openAnalyse();
                        //         }else{
                        //             $(".xx-question-sheet").html(redoHtml);
                        //             MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
                        //             $(".xx-continue-inner").html("提交");
                        //             if (answer_content[0].type == 1) {
                        //                 /*单选选项*/
                        //                 $(".rdolist").labelauty("rdolist", "rdo");
                        //             } else if (answer_content[0].type == 2) {
                        //                 var ue = UE.getEditor('myEditor', {
                        //                     //这里可以选择自己需要的工具按钮名称,此处仅选择如下五个
                        //                     toolbars: [[
                        //                         'fullscreen', 'source', '|',
                        //                         'bold', 'italic', 'underline', '|', 'fontsize', '|', 'kityformula', 'preview'
                        //                     ]],
                        //                     //focus时自动清空初始化时的内容
                        //                     autoClearinitialContent: true,
                        //                     //关闭字数统计
                        //                     wordCount: false,
                        //                     //关闭elementPath
                        //                     elementPathEnabled: false,
                        //                     //默认的编辑区域高度
                        //                     initialFrameHeight: 300
                        //                     //更多其他参数，请参考ueditor.config.js中的配置项
                        //                 });
                        //             } else if (answer_content[0].type == 3) {
                        //                 /*duo选选项*/
                        //                 $(".chklist").labelauty("chklist", "check");
                        //             }
                        //             $(".xx-continue").on("click", function () {
                        //                 var answer_content = [];
                        //                 var answer_val = "", type = "", question_id = "", answer_url = "", topicId = "";
                        //                 type = $(".question-sheet").data("type");
                        //                 question_id = $(".question-sheet").data("question_id");
                        //                 topicId = $("input[name=topicId]").val();
                        //                 var flag = $(this).data("flag");
                        //                 console.log("type---"+type);
                        //                 if (type == 2) {
                        //                     question_id = $(".input-p").attr('data-question_id');
                        //                     $(".input-p").each(function (i, qv) {
                        //                         console.log("input---"+i);
                        //                         if ($(qv).find("img").length > 0) {
                        //                             var input_answer_val = '', input_answer_base64 = '';
                        //                             input_answer_base64 = $(qv).find("img").attr("src");
                        //                             input_answer_val = $(qv).find("img").attr("data-latex");
                        //                             input_answer_val = input_answer_val == undefined ? '' : input_answer_val;
                        //                             input_answer_base64 = input_answer_base64 == undefined ? '' : input_answer_base64;
                        //                             if (i > 0 && !MY_UI.isEmpty(answer_val)) {
                        //                                 answer_val += ";" + input_answer_val;
                        //                                 answer_url += "@@@" + input_answer_base64;
                        //                             }else if(i>0 && MY_UI.isEmpty(answer_val)){
                        //                                 answer_val  += ";"+input_answer_val;
                        //                                 answer_url += "@@@"+input_answer_base64;
                        //                             } else {
                        //                                 answer_val += input_answer_val;
                        //                                 answer_url += input_answer_base64;
                        //                             }
                        //                         } else if(i>0){
                        //                             answer_val  += ";";
                        //                             answer_url += "@@@";
                        //
                        //                         }
                        //                         console.log("answer_val===="+answer_val)
                        //                     });
                        //                 } else if (type == 1) {
                        //                     answer_val = $("label.checked").data("value");
                        //                     answer_val = answer_val == undefined ? '' : answer_val;
                        //                     question_id = question_id == undefined ? '' : question_id;
                        //                     type = type == undefined ? '' : type;
                        //                     //q_forms=q_forms==undefined ?'' : q_forms;
                        //                 } else if (type == 3) {
                        //                     $("label.checked").each(function (i, qv) {
                        //                         if (i > 0 && !MY_UI.isEmpty(answer_val)) {
                        //                             answer_val += "###" + $(qv).data("value");
                        //                         } else {
                        //                             answer_val += $(qv).data("value");
                        //                         }
                        //                         answer_val = answer_val == undefined ? '' : answer_val;
                        //                         question_id = question_id == undefined ? '' : question_id;
                        //                         type = type == undefined ? '' : type;
                        //                         //q_forms=q_forms==undefined ?'' : q_forms;
                        //                     });
                        //
                        //                 }
                        //                 $(".xx-continue").data("flag", 2);
                        //                 if (flag == 0 && (MY_UI.isEmpty(answer_val.replace(/(;)/gi, "")) || answer_val == '\\placeholder ')) {
                        //                     layer.open({
                        //                         type: 1
                        //                         , title: false //不显示标题栏
                        //                         , closeBtn: true
                        //                         , area: ['420px', '259px']
                        //                         , shade: 0.8
                        //                         , id: 'submit_confirm' //设定一个id，防止重复弹出
                        //                         , resize: false
                        //                         , btn: ['确认', '取消']
                        //                         , btnAlign: 'c'
                        //                         , moveType: 1 //拖拽模式，0或者1
                        //                         , content: '<div class="xx-logo-confirm"></div>你尚未填写答案，是否确认提交？'
                        //                         , success: function (layero) {
                        //                             var btn = layero.find('.layui-layer-btn');
                        //                             btn.find('.layui-layer-btn0').on("click", function () {
                        //                                 answer_content.push({
                        //                                     question_id: question_id,
                        //                                     answer_base64: answer_url,
                        //                                     type: type,
                        //                                     answer: answer_val
                        //                                 });
                        //                                 thisObj.initRedoSubmit(topicId, answer_content, flag,redoHtml);
                        //                             });
                        //                         }
                        //                     });
                        //
                        //                 } else if (flag == 0 && !MY_UI.isEmpty(answer_val.replace(/(;)/gi, "")) && answer_val != '\\placeholder ') {
                        //                     answer_content.push({
                        //                         question_id: question_id,
                        //                         type: type,
                        //                         answer_base64: answer_url,
                        //                         answer: answer_val
                        //                     });
                        //                     thisObj.initRedoSubmit(topicId, answer_content, flag,redoHtml);
                        //                 } else {
                        //                     thisObj.getQuestion();
                        //                 }
                        //             });
                        //         }
                        //     }
                        // });

                    }else if(answer_content[0].is_view_answer==1&&response.is_right == 0 && flag == 0){
                        var wrong2Gif=$("input[name=wrong2]").val();
                        $(".gif-img").html("<img src='"+wrong2Gif+"' id='testImg'/>");
                        var imgGif=document.getElementById("testImg");
                        imgGif.play();
                        setTimeout(function(){
                            imgGif.stop();
                            $(".gif").css("display","none");
                            thisObj.getQuestion();

                        },2000);
                        // layer.open({
                        //     content: '<div class="answer-wrong-next"></div>',
                        //     closeBtn:0,
                        //     time:2000,
                        //     title: false,
                        //     btn:"",
                        //     success: function(layero, index){
                        //         thisObj.getQuestion();
                        //     }
                        // });
                    }else {
                        // layer.open({
                        //     content: '<div class="answer-right"></div>',
                        //     closeBtn:0,
                        //     time:2000,
                        //     title: false,
                        //     btn:"",
                        //     success: function(layero, index){
                        //         thisObj.openAnalyse();
                        //     }
                        // });
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
                } else {

                }
            },
            complete: function () {
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
}
GxIndex.prototype.initCharts = function(){
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
//chongzuo提交操作
GxIndex.prototype.initRedoSubmit = function (topicId, answer_content, flag,redoHtml) {
    try{
        var thisObj = this;
        var index = layer.load(0, {shade: 0.8});
        $.ajax({
            url: HOST + 'index/bxbl/redoSubmitQuestion',
            data: {
                topicId: topicId,
                user_id:$_CONFIG.uid,
                answer_content: answer_content
            },
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                //console.log(flag);
                if (response.isSuccess == 1) {
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
                        // layer.open({
                        //     content: '<div class="answer-wrong-next"></div>',
                        //     closeBtn:0,
                        //     time:2000,
                        //     title: false,
                        //     btn:"",
                        //     success: function(layero, index){
                        //         thisObj.openAnalyse();
                        //     }
                        // });
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
                        // layer.open({
                        //     content: '<div class="answer-right"></div>',
                        //     closeBtn:0,
                        //     time:2000,
                        //     title: false,
                        //     btn:"",
                        //     success: function(layero, index){
                        //         //thisObj.getQuestion();
                        //         thisObj.openAnalyse();
                        //     }
                        // });
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
                } else {

                }
            },
            complete: function () {
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
}
//获取题目
GxIndex.prototype.getQuestion = function () {
    try{
        var index = layer.load(0, {shade: 0.8});
        var thisObj = this;
        var topicId = $("input[name=topicId]").val();
        $.ajax({
            url: HOST + "/index/bxbl/getExamQuestions",
            data: {
                topicId: topicId,
            },
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (response.is_enter == 0) {
                    if (!MY_UI.isEmpty(response.question_list)) {
                        var sheetArea = "";
                        var question_id=response.question_list.id;
                        $_CONFIG.question_id = question_id;
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
                        } else {
                            sheetArea += thisObj.initQuestionOptions(response);
                            $(".xx-question-sheet").html(sheetArea);
                            /*多选选项*/
                            $(".chklist").labelauty("chklist", "check");
                        }
                        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
                        //试题题号
                        var questionlength = response.has_answered_questions.length + 1;
                        $(".xx-question-num>span").html(questionlength);
                        thisObj.initKnOption();
                        thisObj.initQuestionAnalyse(response);
                        var is_view_analyze = 0;
                        $(".xx-question-sheet").append(thisObj.initSubmitButton());
                        var start_time = new Date().getTime();
                        var tag_name=response.tag_name;
                        $("#knowledge-point>span").html("知识点 "+tag_name);
                        var len=$("#knowledge-point>span").text().length,test=$("#dwmc").text();
                       if(len>17){
                           $('#knowledge-point').popover({
                               content: test,
                               trigger: 'hover focus'
                           })
                       }
                        if(response.hasOwnProperty("tag_name")){
                            $(".xx-options>span").html(response.tag_name+' ('+response.tag_code+')');
                        }else{
                            $(".xx-options>span").html("知识点为空");
                        }
                        $(".xx-bx-analyse").on("click", function () {
                            if($("#xx-question-analyse").css("display")=="none"){
                                thisObj.openAnalyse("analyse");
                                is_view_analyze = 1;
                                if(response.question_list.analyze[0].title==''){}else{
                                    $(".xx-analyse-step").hide();
                                }
                                $('html,body').animate({scrollTop:$('.bottom').offset().top}, 800);
                                $(".xx-question-analyse").slideDown("slow");
                                $(".xx-question-analyse-close").fadeIn("slow");
                            }
                        });
                        var redoHtml = $(".xx-question-sheet").html();
                        var is_view_answer=0;
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
                                , content: '<div class="xx-logo-confirm"></div>查看答案后该题将自动判错，是否查看？'
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
                                        if(response.question_list.q_type == 2){
                                            $("#xx-step-right").fadeIn()
                                        }
                                        $(".find-answer").fadeIn();
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
                                    $("#your-modal .am-modal-hd").find("a").trigger("click");
                                    console.log(data);
                                    $("#form1").html(type_check);
                                    $("#fileuploader").uploadFile({
                                        url:HOST+"/index/index/submitFile",
                                        fileName:"myfile",
                                        onSuccess:function(files,data,xhr,pd){
                                            $("#option-page>p").hide();
                                        },
                                        showDelete: true,//删除按钮
                                    });
                                },
                                error: function (error) { alert(error); }
                            });
                        });
                        $("#cancel").click(function(){
                            // $("#your-modal .am-modal-hd").find("a").trigger("click");
                            $(".ajax-file-upload-red").trigger("click");
                            $("#your-modal .am-modal-hd").find("a").trigger("click");
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
                        $(".tested").html("已学知识点<br/>"+has_learnedCode_scale+"%");
                        $(".xx-continue").on("click", function () {
                            var answer_content = [];
                            var answer_val = "", type = "", question_id = "", answer_url = "", topicId = "";
                            type = $(".question-sheet").data("type");
                            question_id = $(".question-sheet").data("question_id");
                            topicId = $("input[name=topicId]").val();
                            var flag = $(this).data("flag");
                            console.log("input---"+type);
                            if (type == 2) {
                                $(".input-p").each(function (i, qv) {
                                    console.log("input--i-"+$(qv).find("img").length);
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
                                        console.log("answer_val==="+answer_val);
                                    }
                                    else if(i>0){
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
                            //$(".xx-continue").data("flag", 2);
                            console.log(is_view_answer);
                            if(is_view_answer==1&&flag == 0 && (MY_UI.isEmpty(answer_val.replace(/(;)/gi, "")) || answer_val == '\\placeholder ')){
                                $(".xx-continue").data("flag", 2);
                                //clearInterval(timer);
                                //var spent_time=second;
                                clearInterval(times);
                                answer_content.push({
                                    question_id: question_id,
                                    type: type,
                                    answer_base64: answer_url,
                                    answer: answer_val,
                                    is_view_answer:is_view_answer,
                                    is_view_analyze:is_view_analyze
                                });
                                thisObj.initSubmit(topicId, answer_content, flag,redoHtml,start_time);

                            }else if (is_view_answer==0&&flag == 0 && (MY_UI.isEmpty(answer_val.replace(/(;)/gi, "")) || answer_val == '\\placeholder ')) {
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
                                        clearInterval(times);
                                        var btn = layero.find('.layui-layer-btn');
                                        btn.find('.layui-layer-btn0').on("click", function () {
                                            clearInterval(times);
                                            $(".xx-continue").data("flag", 2);
                                            answer_content.push({
                                                question_id: question_id,
                                                answer_base64: answer_url,
                                                type: type,
                                                answer: answer_val,
                                                is_view_answer:is_view_answer,
                                                is_view_analyze:is_view_analyze
                                            });
                                            thisObj.initSubmit(topicId, answer_content, flag,redoHtml,start_time);
                                        });
                                    }
                                });

                            } else if (flag == 0 && !MY_UI.isEmpty(answer_val.replace(/(;)/gi, "")) && answer_val != '\\placeholder ') {
                                clearInterval(times);
                                $(".xx-continue").data("flag", 2);

                                answer_content.push({
                                    question_id: question_id,
                                    type: type,
                                    answer_base64: answer_url,
                                    answer: answer_val,
                                    is_view_answer:is_view_answer,
                                    is_view_analyze:is_view_analyze
                                });
                                thisObj.initSubmit(topicId, answer_content, flag,redoHtml,start_time);
                            } else {
                                thisObj.getQuestion();
                            }
                        });
                        //知识点视频
                        thisObj.initKCarousel(response);


                        var $modal2 = $('#doc-modal-3');
                        $modal2.on('open.modal.amui', function(){
                            if(!MY_UI.isEmpty(response.tag_video)){
                                var myPlayer = _V_('example_video_1');  //初始化视频
                                console.log(myPlayer);
                                var video_link=response.tag_video;
                                myPlayer.src(video_link);  //重置video的src
                                myPlayer.load(video_link);  //使video重新加载
                                $("#xx-video-close-3").on("click",function(event){
                                    myPlayer.pause();
                                });
                            }
                        });
                        // $(".xx-bx-analyse-video").on("click", function (e) {
                        //     var $target = $(e.target);
                        //     console.log(($target).hasClass('js-modal-open'));
                        //     console.log(($target).hasClass('js-modal-close'));
                        //     console.log($modal2);
                        //     if (($target).hasClass('js-modal-open')) {
                        //         $modal2.modal();
                        //     } else if (($target).hasClass('js-modal-close')) {
                        //         $modal2.modal('close');
                        //     } else {
                        //         $modal2.modal('toggle');
                        //     }
                        // });
                        //巩固视频，基础视频
                        var myPlayers=_V_('example_video_2');  //初始化视频
                        $("#xx-video-return").on("click",function(event){
                            myPlayers.pause();
                        });
                    } else {
                        //thisObj.getQuestion();
                    }
                } else {
                    window.open(HOST + "index/bxbl/bTest/topicId/"+topicId, "_self");
                }

            },
            complete: function () {
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
}
/*
 * 创建提交按钮
 */
GxIndex.prototype.initSubmitButton = function () {
    try{
        var submitButton = '<div class="xx-continue" data-flag="0" onclick="_czc.push([&apos;_trackEvent&apos;, &apos;高效学习提交点击按钮&apos;, &apos;点击&apos;, &apos;提交&apos;,&apos;5&apos;]);"><div class="xx-continue-inner">提交</div></div>';
        return submitButton;
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
//初始化填空题
GxIndex.prototype.initQuestionInput = function (param) {
    try{
        var content = "";
        var question_content = param.question_list.content;
        if(question_content.indexOf("##$$##")>0){
            var n = (question_content.length - question_content.replace(/##\$\$##/g, "").length) / 6;
            if (n > 0) {
                for (var i = 0; i < n; i++) {
                    content += question_content.split("##$$##")[i] + '<div onClick="showMathEdit(this)" data-num="input' + i + '" data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" id="" class="input-p textarea" style="padding:5px 30px;border: 1px solid #ccc;border-radius:4px;position: relative;color:#ccc;">请输入正确答案</div>';
                }
                content += question_content.split("##$$##")[n];
            } else {
                content = param.question_list.content;
            }

            var title = "<div class='question-sheet' data-type='" + param.question_list.q_type + "' data-question_id='" + param.question_list.id + "'><span class='q_type'>[填空题]</span><span class='question-id'>___("+param.question_list.id+")</span>" + content + "</div>"
            //+"<div style='color: red'>这是测试内容："+param.question_list.id+"&nbsp;知识点标签tag_code:"+param.tag_code+"&nbsp;知识点难度:"+param.question_list.difficulty+"&nbsp;答案："+param.question_list.answer+"</div>";

            return title;
        }else{
            var b=replaceAll(question_content,/[_]+[1-9]*[_]+/,'<div onClick="showMathEdit(this)" data-num="input' + i + '" data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" id="" class="input-p textarea" style="padding:5px 30px;border: 1px solid #ccc;border-radius:4px;position: relative;color:#ccc;">请输入正确答案</div>',param.question_list.q_type,param.question_list.id);
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
 * 初始化题目(单选择题)
 */
GxIndex.prototype.initQuestionOption = function (param) {
    try{
        var title = '<div class="question-sheet"  data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" data-q_forms="' + param.question_list.q_forms + '"><span class="q_type">[单选题]</span><span class="question-id">___('+param.question_list.id+')</span>' + param.question_list.content + '</div>';
        var option = '', optionChild = "";
        var optionNum = param.question_list.options;
        //console.log(optionNum);
        for (var i = 0; i < optionNum.length; i++) {
            optionChild +=
                '<input type="radio" name="rdo" class="rdolist"/>'
                + '<label  class="rdobox unchecked" data-type="' + param.question_list.q_type + '" data-value="' + param.question_list.options[i].key + '" data-question_id="' + param.question_list.id + '" data-q_forms="' + param.question_list.q_forms + '" >'
                + '<span class="radiobox-content">' + param.question_list.options[i].key + '、' + (param.question_list.options[i].answer) + '</span>'
                + '</label>';
        }
        option = '<div class="rdo" data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" data-q_forms="' + param.question_list.q_forms + '">' + optionChild + '</div>';
        return title + option;
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
/*
 * 初始化题目(多选择题)
 */
GxIndex.prototype.initQuestionsOptions = function (param) {
    try{
        var title = '<div class="question-sheet"  data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" data-q_forms="' + param.question_list.q_forms + '"><span class="q_type">[多选题]</span>' + param.question_list.content + '</div>';
        var option = '', optionChild = "";
        var optionNum = param.question_list.options;
        //console.log(optionNum);
        for (var i = 0; i < optionNum.length; i++) {
            optionChild +=
                '<input type="checkbox" name="chk" class="chklist"/>'
                + '<label  class="chkbox unchecked" data-type="' + param.question_list.q_type + '" data-value="' + param.question_list.options[i].key + '" data-question_id="' + param.question_list.id + '" data-q_forms="' + param.question_list.q_forms + '" >'
                + '<span class="radiobox-content">' + param.question_list.options[i].key + '、' + (param.question_list.options[i].answer) + '</span>'
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
GxIndex.prototype.initQuestionAnalyse = function (param,type) {
    try{
        if($(".step-analysis").css("display")!="none"){
            $(".step-analysis").hide();
            $("#xx-question-analyse").hide();
        }
        var optionChild="";
        var liLength = param.question_list.analyze[0].content.length;
        var coL=liLength+1;
        if(param.question_list.analyze[0].title==''){
            for (var i = 0; i < liLength; i++) {
                var n = i+1;
                optionChild +=
                    '<li  class="xx-analyse-step" data-is_has_answer="'+param.question_list.analyze[0].content[i].is_has_answer+'" data-step="'+i+'" style="display: none">' +
                    '<p class="xx-step-name">步骤'+n+'/'+liLength+'</p>' +
                    '<p class="xx-step-title">'+(param.question_list.analyze[0].content[i].content)+'</p>' +
                    '</li>';
            }
        }else{
            for (var i = 0; i < liLength; i++) {
                var n = i+1;
                var m=i+2;
                optionChild +=
                    '<li  class="xx-analyse-step" data-is_has_answer="'+param.question_list.analyze[0].content[i].is_has_answer+'" data-step="'+n+'" style="display: none">' +
                    '<p class="xx-step-name">步骤'+m+'/'+coL+'</p>' +
                    '<p class="xx-step-title">'+(param.question_list.analyze[0].content[i].content)+'</p>' +
                    '</li>';
            }
        }
        var buttonOption='<div class="box bottom"></div><div class="xx-analyse-next-step" style="display: none" id="next_step">下一步</div>'+
            '<div class="xx-analyse-next-step" style="display: none" id="find_answer">查看答案</div>'+
            '<div class="xx-analyse-next-step" style="display: none" id="next_q">继续答题</div>';
        $("#optionButton").html(buttonOption);
        if(liLength<1){
            $("#find_answer").show();
        }else{
            $("#next_step").show();
        }
        if(param.question_list.analyze[0].title==''){
            $(".xx-analyse-step-group").html(optionChild);
        }else{
            $(".xx-analyse-step-group").html('<li class="xx-analyse-title"><p style="font-size: 12px;font-weight: bold;color:#26b987;display: block;">步骤1/'+coL+'</p>'+'分析：'+param.question_list.analyze[0].title+'</li>'+optionChild);
        }
        $(".xx-analyse-step-group li.xx-analyse-step").eq(0).show();
        var answer = "";
        if(param.question_list.q_type==2){
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
            answer ='<li  class=" find-answer" style="display: none">' +
                '<p class="xx-step-name">正确答案</p>' +
                '<p class="xx-step-title">'+(param.question_list.answer)+'</p>' +
                '</li>';
            $(".xx-analyse-step-group").append(answer);
        }
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
GxIndex.prototype.openAnalyse = function(param){
    try{
        if(param=="analyse"){
            $("#next_step").unbind("click");
            if($('.xx-analyse-title').length && $('.xx-analyse-title').length>0){
                var step = 0;
                var $num = 0;
                var liLength = $(".xx-analyse-step-group li.xx-analyse-step").length;
                console.log(liLength);
                var a=$("li.xx-analyse-step").data("step");
                if(liLength>2){
                    $("#next_step").on("click",function(){
                        // step++;
                        $num++;
                        // $(".xx-analyse-step-group li.xx-analyse-step").eq(step).show();
                        console.log("当前步数=="+$num);
                        var a=$(".xx-analyse-step-group li[data-step="+$num+"]").show();

                        console.log($num+'='+(liLength));
                        if($num==(liLength)){
                            $("#next_step").hide();
                            $("#find_answer").show();
                        }
                    });
                }else if(liLength==2){
                    $("#next_step").on("click",function(){
                        // step++;
                        $num++;
                        // $(".xx-analyse-step-group li.xx-analyse-step").eq(step).show();
                        console.log($num);
                        var a=$(".xx-analyse-step-group li[data-step="+$num+"]").show();
                        console.log($num+'='+(liLength));
                        if($num==(liLength)){
                            $("#next_step").hide();
                            $("#find_answer").show();
                        }
                    });
                }
                else{
                    $("#next_step").on("click",function(){
                        var liLength = $(".xx-analyse-step-group li.xx-analyse-step").length;
                        console.log(step);
                        $(".xx-analyse-step-group li.xx-analyse-step").eq(step).show();
                        if(step==(liLength-1)){
                            $("#next_step").hide();
                            $("#find_answer").show();
                        }

                    });
                }
            }else{
                var step = 0;
                var $num = 0;
                var liLength = $(".xx-analyse-step-group li.xx-analyse-step").length-1;
                var a=$("li.xx-analyse-step").data("step");
                if(liLength>1){
                    $("#next_step").on("click",function(){
                        // step++;
                        $num++;
                        // $(".xx-analyse-step-group li.xx-analyse-step").eq(step).show();
                        var a=$(".xx-analyse-step-group li[data-step="+$num+"]").show();
                        console.log($num+'='+(liLength-1));
                        if($num==(liLength-1)){
                            $("#next_step").hide();
                            $("#find_answer").show();
                        }
                    });
                }else{
                }
                $("#next_step").on("click",function(){
                    step++;
                    var liLength = $(".xx-analyse-step-group li.xx-analyse-step").length;
                    $(".xx-analyse-step-group li.xx-analyse-step").eq(step).show();
                    if(step==(liLength-1)){
                        $("#next_step").hide();
                        $("#find_answer").show();
                    }
                });
            }
        }else{
            //console.log("openAnalyse");
            $(".xx-continue-inner").html("下一题");
            $(".xx-container .xx-question-analyse .xx-analyse-next-step").fadeOut();
            $(".xx-question-analyse li.xx-analyse-step").each(function(i){
                $(this).fadeIn();
            });
            $("#xx-step-right").fadeIn();
            $(".find-answer").show();
        }
        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
        $(".xx-question-analyse").slideDown("slow");
        $(".xx-question-analyse-close").fadeIn("slow");
        $('html,body').animate({scrollTop:$('.bottom').offset().top}, 800,function(){
            console.log("animateanimateanimate")
            $('html,body').stop();

        });
    }catch(err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}
/*
 * 初始化知识点视频
 */
GxIndex.prototype.initKCarousel = function (param) {
    try{
        var title = "";
        if(param.tag_video_list){
            var $tag_video_list = param.tag_video_list, n = $tag_video_list.length;
            for (var i = 0; i < n; i++) {
                var current = "";
                if ($tag_video_list[i].is_selected == 1) {
                    current = "active";
                    $("#learn-video").html($tag_video_list[i].description+"视频")
                } else {
                    current = "";
                }
                title += '<li class="show-poster-3 ' + current + '">' +
                    '<span class="xx-k-video-select">' + $tag_video_list[i].description + '视频</span>' +
                    '<a href="#" data-videourl="' + $tag_video_list[i].video_url + '" data-am-modal="{target: \'#doc-modal-2\', closeViaDimmer: 0,width:810,height: 487}" class="img"><img original="' + $tag_video_list[i].image_url + '" style="display: inline-block;" src="' + $tag_video_list[i].image_url + '" /></a> ' +
                    '</li>';
            }
            $("#xx-video-select-bd .xx-slider-content .drama-poster ul").html(title);
            if(n==1){
                $(".drama-poster ul>li:first").css("marginLeft","150px");
            }
            //弹框初始化
            var a = $(".drama-poster ul>li");
            a.mouseover(function () {
                a.removeClass("current");
                $(this).addClass("current")
            });
            $(".drama-slide li.next a").click(function () {
                var b = $(".drama-poster ul>li:first"), c = $(".drama-poster ul .current").index();
                $(".drama-poster ul>li:last").after(b);
                $(".drama-poster ul li").removeClass("current");
                $(".drama-poster ul").find("li").eq(c).addClass("current")
            });
            $(".drama-slide li.prev a").click(function () {
                var c = $(".drama-poster ul>li:last"), b = $(".drama-poster ul .current").index();
                $(".drama-poster ul>li:first").before(c);
                $(".drama-poster ul li").removeClass("current");
                $(".drama-poster ul").find("li").eq(b).addClass("current")
            });
            var tag_code = param.tag_code;
            var local_tag_code = localStorage.getItem("tag_code");
            console.log("localstorage="+localStorage.getItem("tag_code")+"noew_tag_code=="+tag_code+"aaaa="+($.trim(tag_code) != $.trim(local_tag_code)))
            var $modal = $('#doc-modal-1');
            $(".xx-slider-button-next").on("click", function (e) {
                var $target = $(e.target);
                console.log(($target).hasClass('js-modal-open'));
                console.log(($target).hasClass('js-modal-close'));
                console.log($modal);
                if (($target).hasClass('js-modal-open')) {
                    $modal.modal();
                } else if (($target).hasClass('js-modal-close')) {
                    $modal.modal('close');
                } else {
                    $modal.modal('toggle');
                }
            });
            $modal.siblings('.am-btn').on('click', function(e) {
                var $target = $(e.target);
                if (($target).hasClass('js-modal-open')) {
                    $modal.modal();
                } else if (($target).hasClass('js-modal-close')) {
                    $modal.modal('close');
                } else {
                    $modal.modal('toggle');
                }
            });
            if ($.trim(tag_code) != $.trim(local_tag_code)) {
                $modal.siblings('.am-btn').trigger("click");
            }
            localStorage.setItem("tag_code", param.tag_code);
            $(".xx-question-analyse-close").click(function () {
                $(".xx-question-analyse").slideUp("slow");
                $(".xx-question-analyse-close").fadeOut("slow");
                $("#find_answer").fadeOut();
                $(".find-answer").fadeOut();
                $("#next_step").fadeIn();
            });
            $(".drama-poster li").on("hover", function () {
                $(".current").removeClass("current");
                $(this).addClass("current")
            });
            $(".show-poster-3 a").on("click", function () {
                $("#xx-k-carousel-select").hide();
                $("#xx-video-select-bd").css("marginTop", "0px");
                var aPlayer = _V_('example_video_2');  //初始化视频
                var video_link= $(this).data("videourl");
                aPlayer.src(video_link);  //重置video的src
                aPlayer.load(video_link);  //使video重新加载
            });
            $("#xx-video-return").on("click", function () {
                $("#xx-k-carousel-select").show();
                $("#xx-video-select-bd").css("marginTop", "-20px")
                $("#doc-modal-2").modal('close');
            });

            $("#xx-video-close-3").on("click", function () {
                $("#doc-modal-3").modal('close');
            });
        }
    }catch (err){
        console.log("Error name: " + err.name + "");
        console.log("Error message: " + err.message);
    }finally {}
}


// 报错弹框的
GxIndex.prototype.initErrorOption=function(param){
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
}

//右侧步骤解析、知识点讲解视频图片
GxIndex.prototype.initKnOption=function(){
    var option = '<li><p>遇到困难了吗？</p><p>可以查看下列资料</p></li>'+
        '<li class="xx-bx-analyse-video js-modal-open" onclick="_czc.push([&apos;_trackEvent&apos;, &apos;高效学习知识点讲解视频点击按钮&apos;, &apos;点击&apos;, &apos;打开&apos;,&apos;5&apos;]);" data-am-modal="{target: \'#doc-modal-1\', closeViaDimmer: 0,width:810,height: 487}"><span></span>&nbsp;&nbsp;<a>知识点讲解视频</a></li>'+
        '<li class="xx-bx-analyse" onclick="_czc.push([&apos;_trackEvent&apos;, &apos;高效学习分步解析点击按钮&apos;, &apos;点击&apos;, &apos;打开&apos;,&apos;5&apos;]);"><span></span>&nbsp;&nbsp;<a href="javascript:;">分步解析</a></li>'+
        '<li class="xx-bx-more">敬请期待更多内容！！</li>';
    $(".xx_side_nav").html(option);
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

function gifPlay(){
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

    }
    return play();
}
function gifStop(){
    if ('getContext' in document.createElement('canvas')){
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
    return stop();
}