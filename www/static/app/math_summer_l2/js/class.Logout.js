/**
 * Created by linxiao on 2016/8/27.
 */
function Logout(ui) {
    this.ui = ui;
    this.domReady();
};

Logout.prototype.domReady = function () {
    var ui =  this.ui;
    $('#logout',ui).click(function () {
        $.ajax({
            url: HOST+"index/login/loginOut",
            type:'POST',
            success: function(response){
                console.log("asdasd");
                window.open(HOST+"index/login/login.html","_self");
            },
            complete:function(){
            }
        });
    });
}