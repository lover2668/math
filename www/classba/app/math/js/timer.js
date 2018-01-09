/**
 * Created by linxiao on 17/2/17.
 * tagConfig {
 *      'question_id':'888',//当前题目id
 *      'tags':["submit",'xx_3','xx_1','xx_2','xx_4'],//埋点标签name
 *      'api_url':""//后台接口
 *  }
 */
function Timer(ui,tagConfig) {
    this.ui = ui;
    this.init(tagConfig);
    //new Select()
};
Timer.prototype.init = function (tagConfig) {
    var tagConfig = tagConfig;//埋点标签name 数据格式数组
    var api_url = tagConfig.api_url+"?";
    var ui =  this.ui;
    var tagArr = [];
    if(tagConfig.question_id){
        tagArr = tagConfig.tags;
    }else{
        return;
    }
    var start = Date.parse(new Date());
    var end;
    //全局事件集合
    var confbehaviourArr = localStorage.getItem("confbehaviourArr")?localStorage.getItem("confbehaviourArr"):"[]";
    confbehaviourArr = JSON.parse(confbehaviourArr);
    function revive(){
        start = Date.parse(new Date());
    }
    var img = new Image();
    var clickData = new Object;
    /*
     * 监听click,提交数据
     */
    $.each(tagArr,function(n,value) {
        $('[name='+value+']',ui).click(function(){
            end = Date.parse(new Date());
            var clickArr = [];
            clickData.name = value;
            clickData.start_time = start;
            clickData.end_time = end;
            clickArr.push(clickData);
            confbehaviourArr.push(clickData);
            localStorage.setItem("confbehaviourArr",JSON.stringify(confbehaviourArr));
            localStorage.setItem("clickData",JSON.stringify(clickData));
            img.src = api_url + "clickData="+clickData;//
            revive();
        });
    });
    //浏览器刷新等提交数据
    window.onbeforeunload = function(){
        end = Date.parse(new Date());
        clickData.name = "body";
        clickData.start_time = start;
        clickData.end_time = end;
        //console.log(confbehaviourArr);
        confbehaviourArr.push(clickData);
        localStorage.setItem("confbehaviourArr",JSON.stringify(confbehaviourArr));
        img.src = api_url + "clickData="+ confbehaviourArr;
    }
}