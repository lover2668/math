/**
 * Created by linxiao on 17/2/17.
 */
function MathIndex(ui){
    this.ui = ui;
    this.domReady();
}
/*
 *
 *
 */
MathIndex.prototype.domReady = function(){
    var thisObj = this;
    thisObj.getQuestion();

}
/*
 *
 *
 */
MathIndex.prototype.getQuestion = function(){
    var thisObj = this;
    thisObj.submitButton();
    thisObj.classbaTimer();
}
/*
 *
 *
 */
MathIndex.prototype.submitButton = function(startTime){
    var ui =  this.ui;
    $(ui).html('<div name="submit" style="background: #d58512;width: 100px;height:45px;"></div>')
}
MathIndex.prototype.classbaTimer = function(){
    var ui =this.ui;
    var second = 0;
    window.setInterval(function () {
        second ++;
    }, 1000);
    var tjArr = [];
    $('[name=submit]',ui).on("click",function(){
        var tjArr = localStorage.getItem("jsArr") ? localStorage.getItem("jsArr") : '[{}]';
        var dataArr = {
            'url' : "",
            'time' : second,
            'timeIn' : Date.parse(new Date()),
            'timeOut' : Date.parse(new Date()) + (second * 1000)
        };
        tjArr = eval('(' + tjArr + ')');
        tjArr.push(dataArr);
        tjArr= JSON.stringify(tjArr);
        localStorage.setItem("jsArr", tjArr);
    });
    return
}