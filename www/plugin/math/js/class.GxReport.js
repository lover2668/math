/**
 * Created by sks on 2017/1/4.
 */
function initErrorOption(){
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
        '<p>未选择任何文件，插入题目错误截图可以更好地帮助你反馈错误</p>'+
        '</div>'+
        '</div>'+
        '</form>';
    return option;
}

$(function () {
    $("#form1").html(initErrorOption());

    //报错错误类型选中切换
    $(".radio-check>label").click(function(){
        //$(this).parent(".radio-check").children(".regular-radio").removeAttr("checked")
        $(this).parents("form").find(".radio-check").children(".regular-radio").attr("checked",false);
        $(this).parent(".radio-check").children(".regular-radio").attr("checked",true);
    });
    $("#monent").focus(function(){
        $(this).html("");
    })
    $("#fileuploader").uploadFile({
        url:HOST+"/index/index/submitFile",
        fileName:"myfile",
        onSuccess:function(files,data,xhr,pd){
            $("#option-page>p").hide();
        },
        showDelete: true,//删除按钮
    });
    $("#sure").click(function(){
//            $(".wrong-type").submit();
        $("#submit").ajaxSubmit({
            url: HOST+"index/Index/submitCorrection", /*设置post提交到的页面*/
            type: "post", /*设置表单以post方法提交*/
            dataType: "json", /*设置返回值类型为文本*/
            success: function (data) {
                $("#your-modal .am-modal-hd").find("a").trigger("click");
                console.log(data);
                $("#form1").html(initErrorOption());
            },
            error: function (error) { alert(error); }
        });
    });
    $("#cancel").click(function(){
        $(".ajax-file-upload-red").trigger("click");
        $("#your-modal .am-modal-hd").find("a").trigger("click");
    });
});