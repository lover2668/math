/**
 * Created by linxiao on 17/2/17.
 */
function MathIndex(ui){
    this.ui = ui;
    this.domReady();
}
/*
 *初始化
 *
 */
MathIndex.prototype.domReady = function(){
    var thisObj = this;
    var ui =  this.ui;

    var topicId = "";
    var questionInfo = thisObj.getQuestion(topicId);
    var questionSheet = thisObj.initQuestionSheet();
    var submitButton = thisObj.submitButton();

    var html = "<div>"+questionSheet+submitButton+"</div>";
    $(ui).html(html);//渲染页面各模块
    thisObj.submitQuestion()

    ///*
    //    页面埋点
    // */
    //var tagConfig = {
    //    'question_id':'11',
    //    'tags':["submit"],
    //    'api_url':"./statistics.php"
    //};
    //var timer = new Timer(ui,tagConfig);
}
/*
 *获取试题
 *参数:topicId;
 *返回值:试题所有信息
 */
MathIndex.prototype.getQuestion = function(topicId){
    var thisObj = this;
    var ui =  this.ui;
    var response;
    $.ajax({
        type:"POST",
        async:false,
        url:"./statistics.php",
        data:{

        },
        success:function(data){
            if(data=="ok"){

            }
        }
    });
    return response;
}
/*

 */
MathIndex.prototype.initQuestionSheet = function(){
    var html = "<div id='idid'>##$$##sdkfjhksjdh___898___fkjshdfkjasd8yqw##$$##iueweyr</div>";
    html = MY_UI.rulesFilter(html,/##\$\$##/g);
    return html;
}
/*
 *提交按钮
 *
 */
MathIndex.prototype.submitButton = function(){
    var ui =  this.ui;
    var thisObj = this;
    var submitButton = '<div name="submit" style="background: #d58512;width: 100px;height:45px;"></div>';
    return submitButton;
}
/*
 *提交操作
 *
 */
var i = 1;
MathIndex.prototype.submitQuestion = function(param){
    var thisObj = this;
    var ui =  this.ui;

    $('[name=submit]',ui).on("click",function(){
        //thisObj.domReady();

        $.ajax({
            type:"POST",
            async:false,
            url:"./statistics.php",
            data:{

            },
            success:function(data){
                if(data){
                    console.log(i)
                    document.title = "第"+i+"题";
                    i++;
                    thisObj.domReady();
                }else {
                    //执行其他操作
                }
            }
        });
    })
}