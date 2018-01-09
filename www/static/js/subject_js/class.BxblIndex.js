/**
 * Created by linxiao on 2016/8/26.
 */
function BxblIndex(ui) {
    this.ui = ui;
    this.domReady();
    $("#edui31_body").trigger("click");
    //new Select()
};

BxblIndex.prototype.domReady = function () {
    var ui = this.ui;
    var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
    var thisObj = this;
    var topicId = $("input[name=topicId]").val();
    var initKStatus = $("input[name=initKStatus]").val();
    var is_view_analyze = 0;
    var is_view_answer = 0;
    $.ajax({
        url: HOST + "/index/Bxbl/getExamQuestions",
        data: {
            topicId: topicId
        },
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            var timer = "";
            if (response.is_enter == 0) {
                //timer = setTimeout(function () {
                //    $("#archives").fadeIn(500000);
                //}, 2000);
                timer=setTimeout(function(){$(".cont p").fadeIn(500000);}, 3000);
                if (!MY_UI.isEmpty(response.question_list)) {
                    var sheetArea = "";
                    if (response.question_list.q_type == 1) {
                        sheetArea += thisObj.initQuestionOption(response);
                    } else {
                        sheetArea += thisObj.initQuestionInput(response);
                    }
                    sheetArea += thisObj.initSubmitButton();
                    $("#analyze-sheet-area").html(thisObj.initQuestionAnalyzeTitle(response));
                    $("#sheet-area").html(sheetArea);
                    $("#view-analyze").on("click",function(){
                        is_view_analyze =1;
                    })
                    $(".payment-wizard").html(thisObj.initAnalyzeArea(response));
                    $(".payment-wizard").find("#findAnswer").click(function () {
                        is_view_answer = 1;
                        $(".question-sheet").find(".answer").show();
                        $(".weis").show();

                        $("#findAnswer").hide();
                        $("#continue").show();
                        $("#continue").on("click", function () {
                            $(".detail-left").css({left: "-75%", top: "-750px"}).stop(true, true).animate({
                                left: "-75%",
                                top: "-750px"
                            }, 800);
                            //thisObj.domReady();
                            $("#doc-oc-demo3").offCanvas('close');
                        });
                        $("#returns").on("click",function(){
                            $("#doc-oc-demo3").offCanvas('close');
                        });
                    })
                    $("#doc-oc-demo3").find("#adic").click(function () {
                        $("#doc-oc-demo3").offCanvas('close');
                    });
                    $("#tag-slider").html(thisObj.initTagNameList(response));
                    $("#tagName").html(thisObj.initTagName(response));
                    var a = $("#tagName").html();
                    $(".tagNames").html(a);
                    $("#have_learned").html(response.have_learned);
                    $(".answer").wrapAll("<div class='weis' style='font-weight: bold;color:#000;margin-top:30px;display: none;'>参考答案：</div>")
                    //$("#doc-video").html(thisObj.initVideo(response));
                    var myPlayer = _V_('example_video_1');  //初始化视频
                    myPlayer.src(response.tag_video);  //重置video的src
                    myPlayer.load(response.tag_video);  //使video重新加载
                    $('.cd-close').on('click', function (event) {
                        myPlayer.pause();
                    });
                    $('.am-slider').flexslider({itemWidth: 245, itemMargin: 3, slideshow: false});
                    $(".done").click(function () {
                        var nextBtn = $(this);
                        if (response.question_list.analyze[0].content[0].is_has_answer == 1) {
                            layer.confirm('此步骤有答案是否查看？', {
                                btn: ['是的', '取消'],//按钮
                                shift: 3
                            }, function (index) {
                                layer.close(index);
                                var this_li_ind = nextBtn.parent().parent("li").index();
                                if ($('.payment-wizard li').hasClass("jump-here")) {
                                    nextBtn.parent().parent("li").removeClass("active").addClass("completed");
                                    nextBtn.parent(".wizard-content").slideUp();
                                    $('.payment-wizard li.jump-here').removeClass("jump-here");
                                } else {
                                    nextBtn.parent().parent("li").removeClass("active").addClass("completed");
                                    nextBtn.parent(".wizard-content").slideUp();
                                    nextBtn.parent().parent("li").next("li:not('.completed')").addClass('active').children('.wizard-content').slideDown();
                                }
                            }, function (index) {
                                layer.close(index);
                            });

                        } else {
                            var this_li_ind = $(this).parent().parent("li").index();
                            if ($('.payment-wizard li').hasClass("jump-here")) {
                                $(this).parent().parent("li").removeClass("active").addClass("completed");
                                $(this).parent(".wizard-content").slideUp();
                                $('.payment-wizard li.jump-here').removeClass("jump-here");
                            } else {
                                $(this).parent().parent("li").removeClass("active").addClass("completed");
                                $(this).parent(".wizard-content").slideUp();
                                $(this).parent().parent("li").next("li:not('.completed')").addClass('active').children('.wizard-content').slideDown();
                            }
                        }

                    });
                    $('.payment-wizard li .wizard-heading').click(function () {
                        if ($(this).parent().hasClass('completed')) {
                            var this_li_ind = $(this).parent("li").index();
                            var li_ind = $('.payment-wizard li.active').index();
                            if (this_li_ind < li_ind) {
                                $('.payment-wizard li.active').addClass("jump-here");
                            }
                            $(this).parent().addClass('active').removeClass('completed');
                            $(this).siblings('.wizard-content').slideDown();
                        }
                    });
                    /*选项*/
                    $(".rdolist").labelauty("rdolist", "rdo");
                    //tijiao
                    $('.continue-topic', ui).click(function () {
                        $(".cont p").fadeOut();
                        clearTimeout(timer, 200);
                        var answer_val = "", question_type = "", question_id = "", answer_url = "";
                        var topicId = $("input[name=topicId]").val();
                        var answer_content = [];
                        question_type = $(".question-sheet").data("type");
                        question_id = $(".question-sheet").data("question_id");
                        if (response.question_list.q_type == 1) {

                            answer_val = $("label.checked").data("value");
                            answer_val = answer_val == undefined ? '' : answer_val;
                            question_id = question_id == undefined ? '' : question_id;
                            answer_url = "";
                            question_type = question_type == undefined ? '' : question_type;
                        } else if (response.question_list.q_type == 2) {
                            var input_answer_val = '', input_answer_base64 = '';
                            //question_id = $(".input-p").attr('data-question_id');
                            //question_type = $(".input-p").attr('data-type');
                            $("div[class='input-p']").each(function (i, qv) {
                                if ($(qv).find("img").length > 0) {
                                    $(qv).find("img").each(function (ii, v) {
                                        input_answer_base64 = v.getAttribute("src");
                                        input_answer_val = v.getAttribute("data-latex");
                                        input_answer_val = input_answer_val == undefined ? '' : input_answer_val;
                                        input_answer_base64 = input_answer_base64 == undefined ? '' : input_answer_base64;
                                    });
                                    if (i > 0 && !MY_UI.isEmpty(input_answer_val)) {
                                        input_answer_val += ";" + input_answer_val;
                                        input_answer_base64 += "@@@" + input_answer_base64;
                                    }
                                    answer_url = input_answer_base64;
                                    answer_val = input_answer_val;
                                } else {
                                    answer_val = "";
                                    answer_url = "";
                                }
                            });
                        }
                        console.log(answer_val);
                        //return false;

                        if (MY_UI.isEmpty(answer_val) || answer_val == '\\placeholder ') {
                            layer.confirm('任何任务都要勇于尝试，想破头想不出来听听老师怎么教，看解题思路也是好招数，不可以空白啊！', {
                                btn: ['是的'],//按钮
                                shift: 3
                            }, function (index) {
                                //layer.close(index);
                                //$('.continue-topic', ui).unbind("click");
                                //answer_content.push({
                                //    question_id: question_id,
                                //    answer_base64: answer_url,
                                //    type: question_type,
                                //    answer: answer_val,
                                //    is_view_answer: is_view_answer,
                                //    is_view_analyze: is_view_analyze
                                //});
                                //thisObj.initSubmit(topicId, answer_content);
                                layer.close(index);
                                timer=setTimeout(function(){$(".cont p").fadeIn(500000);}, 3000);
                            });
                        } else {
                            answer_content.push({
                                question_id: question_id,
                                type: question_type,
                                answer_base64: answer_url,
                                answer: answer_val,
                                is_view_answer: is_view_answer
                            });
                            thisObj.initSubmit(topicId, answer_content);
                        }

                    });
                } else {
                    thisObj.domReady();
                }
            } else if (response.is_enter == 1) {
                window.open(HOST + "index/Bxbl/ttqQuestion/topicId/" + topicId, "_self");
            }

        },
        complete: function () {
            layer.close(index);
        }
    });
}
/*
 * 初始化题目填空
 */
BxblIndex.prototype.initQuestionInput = function (param) {
    var content = "";
    var question_content = MY_UI.htmlspecialcharsDecode(param.question_list.content);
    var n = (question_content.length - question_content.replace(/##\$\$##/g, "").length) / 6;
    console.log(question_content);
    if (n > 0) {
        for (var i = 0; i < n; i++) {
            content += question_content.split("##$$##")[i] + '<div onClick="showMathEdit(this)" data-num="input' + i + '" data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" id="textarea" class="input-p" style="min-height:45px;border-bottom: 1px solid #000;"></div>';
        }
        content += question_content.split("##$$##")[n];
    } else {
        content = MY_UI.htmlspecialcharsDecode(param.question_list.content);
    }

    var title = "<div class='question-sheet' data-type='" + param.question_list.q_type + "' data-question_id='" + param.question_list.id + "'>" + content + "</div>"
    //+"<div style='color: red'>这是测试内容："+param.question_list.id+"&nbsp;知识点标签tag_code:"+param.tag_code+"&nbsp;知识点难度:"+param.question_list.difficulty+"&nbsp;答案："+param.question_list.answer+"</div>";

    return title;
}
/*
 * 初始化题目选项
 */
BxblIndex.prototype.initQuestionOption = function (param) {
    var title = "<div class='question-sheet' data-type='" + param.question_list.q_type + "' data-question_id='" + param.question_list.id + "'>" + MY_UI.htmlspecialcharsDecode(param.question_list.content) + "</div>"
    //+"<div style='color: red'>这是测试内容："+param.question_list.id+"&nbsp;知识点标签tag_code:"+param.tag_code+"&nbsp;知识点难度:"+param.question_list.difficulty+"</div>";
    var option = '', optionChild = "";
    var optionNum = param.question_list.options;
    for (var i = 0; i < optionNum.length; i++) {
        optionChild += '<input type="radio" name="rdo" class="rdolist"/>'
            + '<label data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" data-value="' + param.question_list.options[i].key + '" class="rdobox">'
            + '<span class="radiobox-content">' + param.question_list.options[i].key + '、' + MY_UI.htmlspecialcharsDecode(param.question_list.options[i].content) + '</span>'
            + '</label>';
    }
    option = '<div class="rdo" data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '">' + optionChild + '</div>';
    return title + option;
}
/*
 * 初始化解析题目内容
 */
BxblIndex.prototype.initQuestionAnalyzeTitle = function (param) {
    console.log(param);
    var content = "";
    var question_content = MY_UI.htmlspecialcharsDecode(param.question_list.content);
    var n = (question_content.length - question_content.replace(/##\$\$##/g, "").length) / 6;
    console.log(n);
    if (n > 0) {
        for (var i = 0; i < n; i++) {
            content += question_content.split("##$$##")[i] + '<div style="min-height:90px;border-bottom: 1px solid #000;background: #FFFFFF;"></div>';
        }
        content += question_content.split("##$$##")[n];
        for (var i = 0; i < n; i++) {
            content += '<div class="answer" style="font-size:1.2em;color:#000;margin-top:20px;display: none;">' + '<img src="' + param.question_list.answer_base64[i] + '" max-width="100%">' + ';' + '</div>';
        }

    } else {
        var option = '', optionChild = "";
        var optionNum = param.question_list.options;
        for (var i = 0; i < optionNum.length; i++) {
            optionChild += '<label data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '" data-value="' + param.question_list.options[i].key + '" class="rdobox">'
                + '<span class="radiobox-content">' + param.question_list.options[i].key + '、' + MY_UI.htmlspecialcharsDecode(param.question_list.options[i].content) + '</span>'
                + '</label>';
        }
        option = '<div class="rdo" data-type="' + param.question_list.q_type + '" data-question_id="' + param.question_list.id + '">' + optionChild + '</div>';
        content = MY_UI.htmlspecialcharsDecode(param.question_list.content + option) + '<div class="answer" style="font-size:1.2em;color:#000;margin-top:20px;display: none;">' + param.question_list.answer[0] + '</div>';
    }
    var title = "<div class='question-sheet question-analysis'>" +
        "<span class='am-badge am-badge-danger am-text-xl'>题目：</span>"
        + content + "</div><div id='adic' style='margin-top:50px;'>"+"<button class='am-btn am-btn-success' id='returns' style='padding:8px 35px;' type='button'>返回</button>" + "</div>" + "</div>";

    return title;
}
/*
 * 创建提交按钮
 */
BxblIndex.prototype.initSubmitButton = function () {
    var submitButton = '<div class="amz-toolbar" id="amz-toolbar"><div class="continue-topic"></div></div>';
    return submitButton;
}
/*
 * 解析下一步
 */
BxblIndex.prototype.initAnalyzeArea = function (param) {
    var analyze = param.question_list.analyze[0].content;
    console.log(analyze);
    var analyzeContent = '<li class="active"><div class="wizard-heading"><span class="icon-mode"></span></div><div class="wizard-content">' +
        '<p>' + '提示' + MY_UI.htmlspecialcharsDecode(param.question_list.analyze[0].title) + '</p><button class="btn-green done" type="submit">下一步</button></div></li>';
    for (var i = 0; i < analyze.length; i++) {
        if (i == (analyze.length - 1)) {
            analyzeContent += '<li><div class="wizard-heading"><span class="icon-mode"></span></div><div class="wizard-content">' +
                '<p>' + MY_UI.htmlspecialcharsDecode(analyze[i].content) + '</p><button id="findAnswer" class="btn-green" type="submit">查看答案</button><button style="display:none" class="btn-green" id="continue"  type="submit">返回重做</button></div></li>';
        } else {
            analyzeContent += '<li><div class="wizard-heading"><span class="icon-mode"></span></div><div class="wizard-content">' +
                '<p>' + MY_UI.htmlspecialcharsDecode(analyze[i].content) + '</p><button class="btn-green done" type="submit">下一步</button></div></li>';
        }
    }
    return analyzeContent;
}

BxblIndex.prototype.initAnalyzeSubm = function (param) {
    $(".wizard-content").find(".done").click(function () {

    });
}
/*
 * 提交操作
 */
BxblIndex.prototype.initSubmit = function (topicId, answer_content) {
    var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
    var thisObj = this;
    $.ajax({
        url: HOST + "/index/Bxbl/submitQuestion",
        data: {
            topicId: topicId,
            answer_content: answer_content,
        },
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            if (response.isSuccess == 1) {
                if (response.is_right == 1) {
                    var $btns = $('#animation-group').find('.right');
                    var dfds = [];
                    var animating = false;
                    var animation = 'am-animation-scale-up';
                    $btns.css("display", "block");
                    if (!animating) {
                        animating = true;
                        $btns.each(function () {
                            var dfd = new $.Deferred();
                            dfds.push(dfd);
                            var $this = $(this);
                            if ($.AMUI.support.animation) {
                                $this.addClass(animation).one($.AMUI.support.animation.end, function () {
                                    $this.removeClass(animation);
                                    dfd.resolve();
                                });
                            }
                        });

                        $.when.apply(null, dfds).done(function () {
                            animating = false;
                            console.log('[AMUI] - 所有动画执行完成');
                            timer = setTimeout(function () {
                                $btns.css("display", "none");
                                thisObj.domReady();
                            }, 2000);
                            dfds = [];
                        });
                    }
                } else {
                    var $btns = $('#animation-group').find('.error');
                    var dfds = [];
                    var animating = false;
                    var animation = 'am-animation-scale-up';
                    $btns.css("display", "block");
                    if (!animating) {
                        animating = true;
                        $btns.each(function () {
                            var dfd = new $.Deferred();
                            dfds.push(dfd);
                            var $this = $(this);
                            if ($.AMUI.support.animation) {
                                $this.addClass(animation).one($.AMUI.support.animation.end, function () {
                                    $this.removeClass(animation);
                                    dfd.resolve();
                                });
                            }
                        });

                        $.when.apply(null, dfds).done(function () {
                            animating = false;
                            console.log('[AMUI] - 所有动画执行完成');
                            timer = setTimeout(function () {
                                $btns.css("display", "none");
                                //thisObj.domReady();
                                var param = $("#sheet-area").html();
                                $("#doc-oc-demo3").offCanvas('open');
                                $("#sheet-area").html();
                                thisObj.initReDom(param);
                            }, 2000);
                            dfds = [];
                        });
                    }
                }
                is_view_answer = 0;
                //thisObj.domReady();
            } else {
                console.log(response.error)
            }
        },
        complete: function () {
            layer.close(index);
        }
    });
}
/*
 * 初始化知识点列表
 */
BxblIndex.prototype.initTagNameList = function (param) {
    console.log(param);
    var tagList = "";
    for (var i = 0; i < param.tag_code_timeline.length; i++) {
        tagList += '<li class="am-img-thumbnail tag-name" >' + param.tag_code_timeline[i].tag_name + '</li>';
    }
    //for(var i = 0;i< 3;i++){
    //    tagList += '<li class="am-img-thumbnail tag-name" >'+param.tag_code_timeline[0].tag_name+'</li>';
    //}
    return tagList;
}
/*
 * 重做功能
 */
BxblIndex.prototype.initReDom = function (param) {
    var thisObj = this;
    $("#sheet-area").html(param);
    $(".rdolist").labelauty("rdolist", "rdo");
    $('.continue-topic').click(function () {
        $("#archives").fadeOut();
        var answer_val = "", question_type = "", question_id = "", answer_url = "";
        var topicId = $("input[name=topicId]").val();
        var answer_content = [];
        question_type = $(".question-sheet").data("type");
        question_id = $(".question-sheet").data("question_id");
        if (question_type == 1) {
            answer_val = $("label.checked").data("value");
            answer_val = answer_val == undefined ? '' : answer_val;
            question_id = question_id == undefined ? '' : question_id;
            answer_url = "";
            question_type = question_type == undefined ? '' : question_type;
        } else if (question_type == 2) {
            var input_answer_val = '', input_answer_base64 = '';
            //question_id = $(".input-p").attr('data-question_id');
            //question_type = $(".input-p").attr('data-type');
            $("div[class='input-p']").each(function (i, qv) {
                if ($(qv).find("img").length > 0) {
                    $(qv).find("img").each(function (ii, v) {
                        input_answer_base64 = v.getAttribute("src");
                        input_answer_val = v.getAttribute("data-latex");
                        input_answer_val = input_answer_val == undefined ? '' : input_answer_val;
                        input_answer_base64 = input_answer_base64 == undefined ? '' : input_answer_base64;
                    });
                    if (i > 0 && !MY_UI.isEmpty(input_answer_val)) {
                        input_answer_val += ";" + input_answer_val;
                        input_answer_base64 += "@@@" + input_answer_base64;
                    }
                    answer_url = input_answer_base64;
                    answer_val = input_answer_val;
                } else {
                    answer_val = "";
                    answer_url = "";
                }
            });
        }
        console.log(answer_val);
        //return false;
        if (MY_UI.isEmpty(answer_val) || answer_val == '\\placeholder ') {
            layer.confirm('您是答案为空，是否确认提交？', {
                btn: ['是的', '取消'],//按钮
                shift: 3
            }, function (index) {
                layer.close(index);
                $('.continue-topic').unbind("click");
                answer_content.push({
                    question_id: question_id,
                    answer_base64: answer_url,
                    type: question_type,
                    answer: answer_val
                });
                $.ajax({
                    url: HOST + "/index/Index/isRight",
                    data: {
                        answer_content: answer_content
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        thisObj.domReady();
                    }
                });
            }, function (index) {
                layer.close(index);
            });
        } else {
            answer_content.push({
                question_id: question_id,
                type: question_type,
                answer_base64: answer_url,
                answer: answer_val
            });
            $.ajax({
                url: HOST + "/index/Index/isRight",
                data: {
                    answer_content: answer_content
                },
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    thisObj.domReady();
                }
            });
        }
    })

}
//弹框的文案
BxblIndex.prototype.initTagName = function (param) {
    return param.tag_name;
}

//右侧视频
BxblIndex.prototype.initVideo = function (param) {
    console.log(param);
    var video = '<video id="example_video_1" class="video-js vjs-amazeui" controls preload="none" width="640" height="384" poster="src/images/bg.jpg" data-setup="{}">' + +'<source src="' + param.question_list.analyze_link + '"type="video/mp4"/>' +
        '<source src="' + param.question_list.analyze_link + '" type="video/webm"/>' +
        '<source src="' + param.question_list.analyze_link + '" type="video/ogg"/>' +
        '<track kind="captions" src="video.js/demo.captions.vtt" srclang="en" label="English"></track>' + <!-- Tracks need an ending tag thanks to IE9 -->
        '<track kind="subtitles" src="video.js/demo.captions.vtt" srclang="en" label="English"></track>' + <!-- Tracks need an ending tag thanks to IE9 -->
        '<p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>' +
        '</video>';
    return video;
    //return param.tag_video;
}