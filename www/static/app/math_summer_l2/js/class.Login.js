/**
 * Created by yuan on 16/8/15.
 */

function Login(ui) {
    this.ui = ui;
    this.domReady();
    //new Select()
};
Login.prototype.domReady = function () {
        var ui =  this.ui;
        $('[name=login_submit]',ui).click(function () {
            var username  = $('[name=username]',ui).val();
            var pwd = $('[name=pwd]',ui).val();
            var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
            $.ajax({
                url: HOST+"index/Login/loginIn",
                data:{
                    username: username,
                    pwd: pwd
                },
                type:'POST',
                dataType:'json',
                success: function(response){
                    console.log(response);
                    if(!response.isSuccess)
                    {
                        var err_code = response.err_code;
                        var err_info = response.err_info;
                        layer.msg(err_info);
                    }else{
                        window.open(HOST+"index/index/index","_self");
                    }
                },
                complete:function(){
                    layer.close(index);
                }
            });
        });
        $('#login #password').focus(function() {
            $('#owl-login').addClass('password');
        }).blur(function() {
            $('#owl-login').removeClass('password');
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                $("[name=login_submit]").click();
            }
        });
}
