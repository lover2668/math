/**
 * Created by linxiao on 2017/2/4.
 * 公式编辑器v1.0
 */
(function () {

    // 获取 wangEditor 构造函数和 jquery
    var E = window.wangEditor;
    var $ = window.jQuery;

    // 用 createMenu 方法创建菜单
    E.createMenu(function (check) {

        // 定义菜单id，不要和其他菜单id重复。编辑器自带的所有菜单id，可通过『参数配置-自定义菜单』一节查看
        var menuId = 'editor';

        // check将检查菜单配置（『参数配置-自定义菜单』一节描述）中是否该菜单id，如果没有，则忽略下面的代码。
        if (!check(menuId)) {
            return;
        }

        // this 指向 editor 对象自身
        var editor = this;

        // 创建 menu 对象
        var menu = new E.Menu({
            editor: editor,  // 编辑器对象
            id: menuId,  // 菜单id
            title: '公式编辑器', // 菜单标题

            // 正常状态和选中装下的dom对象，样式需要自定义
            $domNormal: $('<a href="#" tabindex="-1"><i class="wangeditor-menu-img-sigma"></i></a>'),
            $domSelected: $('<a href="#" tabindex="-1" class="selected"><i class="wangeditor-menu-img-sigma"></i></a>')
        });
        $.getScript("/static/assets/wangEditor/src/js/extend/math_editor/script/formula-editor.js");
        // panel 内容
        var $container = $('<div></div>');

        var $body =$("body");
        $body.append("<div id='FormulaEditorPanel' style='display: none;z-index:999;border:1px solid #eeeeee;background-color:#FFF8F8;border-radius: 3px;width:700px;position: absolute;'></div>");
        // 菜单正常状态下，点击将触发该事件
        menu.clickEvent = function (e) {
            $("#FormulaEditorPanel").html("<div id='FormulaEditor'></div>");
            //初始化编辑器
            KeleFE.Base('#FormulaEditor', {
                handlers: {
                    change: function(latex){
                        console.log('change: ', latex);
                    },
                    completed: function(latex){
                        console.log('completed: ', latex);
                        editor.command(e, 'insertHtml', "\\("+latex+"\\)");
                        $("#FormulaEditorPanel").fadeOut("slow")
                    },
                    cancel:function(latex){
                        $("#FormulaEditorPanel").fadeOut("slow");
                    },
                }
            });
            if($("#FormulaEditorPanel").is(":hidden")){
                $("#FormulaEditorPanel").fadeIn("slow").css({
                    position:'absolute',
                    left: ($(window).width() - $('#FormulaEditorPanel').outerWidth())/2,
                    top: ($(window).height() - $('#FormulaEditorPanel').outerHeight())/2 + $(document).scrollTop()
                });    //如果元素为隐藏,则将它显现
            }else{
                $("#FormulaEditorPanel").fadeOut("slow");     //如果元素为显现,则将其隐藏
            }
        };

        // 添加panel
        menu.dropPanel = new E.DropPanel(editor, menu, {
            $content: $container,
            width: 350
        });

        // 增加到editor对象中
        editor.menus[menuId] = menu;
    });

})();
