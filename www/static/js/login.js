/**
 * Created by linxiao on 16/7/26.
 */
$(function(){
    $('#loginform').submit(function(e){
        var username = $('input[name=username]').val(),
            password = $('input[name=password]').val();
        layer.msg(username)
        if(MY_UI.isEmpty(username)){
            layer.msg('用户名为空!', {
                icon: 2,
                time: 2000 //2秒关闭（如果不配置，默认是3秒）
            }, function(){
                return false;
//                        location.href = "login.html";
            });
        }else if (MY_UI.isEmpty(password)){
            layer.msg('密码为空!', {
                icon: 2,
                time: 2000 //2秒关闭（如果不配置，默认是3秒）
            }, function(){
                return false;
//                        location.href = "login.html";
            });
        }else{
            $.ajax({
                type: 'post', // 提交方式 get/post
                url: 'url', // 需要提交的 url
                data: {
                    'username': username,
                    'password': password
                },
                success: function(data) { // data 保存提交后返回的数据，一般为 json 数据
                    // 此处可对 data 作相关处理
                    layer.msg('提交成功!', {
                        icon: 3,
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function(){
//                        location.href = "login.html";
                    });
                },
                error:function(){
                    layer.msg("请求错误!")
                }
//                $(this).resetForm(); // 提交后重置表单
            });
        }
        return false;
    });
});