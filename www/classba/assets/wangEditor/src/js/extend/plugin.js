/**
 * Created by linxiao on 2017/2/7.
 */
// 编辑器区域 text-selection toolbar
(function () {
    // 获取 wangEditor 构造函数和 jquery
    var E = window.wangEditor;
    var $ = window.jQuery;

    E.plugin(function () {
        var editor = this;
        var txt = editor.txt;
        var $txt = txt.$txt;
        var $currentTable;

        // 用到的dom节点
        var isRendered = false;
        var $toolbar = $('<div class="txt-toolbar"></div>');
        var $triangle = $('<div class="tip-triangle"></div>');
        var $delete = $('<a href="#"><i class="wangeditor-menu-img-trash-o"></i></a>');


        // 渲染到页面
        function render() {
            if (isRendered) {
                return;
            }

            // 绑定事件
            bindEvent();

            // 拼接 渲染到页面上
            $toolbar.append($triangle)
                .append($delete);
            editor.$editorContainer.append($toolbar);
            isRendered = true;
        }

        // 绑定事件
        function bindEvent() {
            // 统一执行命令的方法
            var commandFn;
            function command(e, callback) {
                if (commandFn) {
                    editor.customCommand(e, commandFn, callback);
                }
            }

            // 删除
            $delete.click(function (e) {
                commandFn = function () {
                    $currentTable.remove();
                };
                command(e, function () {
                    setTimeout(hide, 100);
                });
            });
        }

        // 显示 toolbar
        function show() {
            if ($currentTable == null) {
                return;
            }
            $currentTable.addClass('clicked');
            var tablePosition = $currentTable.position();
            var tableTop = tablePosition.top;
            var tableLeft = tablePosition.left;
            var tableHeight = $currentTable.outerHeight();
            var tableWidth = $currentTable.outerWidth();

            // --- 定位 toolbar ---

            // 计算初步结果
            var top = tableTop + tableHeight;
            var left = tableLeft;
            var marginLeft = 0;

            var txtTop = $txt.position().top;
            var txtHeight = $txt.outerHeight();
            if (top > (txtTop + txtHeight)) {
                // top 不得超出编辑范围
                top = txtTop + txtHeight;
            }

            // 显示（方便计算 margin）
            $toolbar.show();

            // 计算 margin
            var width = $toolbar.outerWidth();
            marginLeft = tableWidth / 2 - width / 2;

            // 定位
            $toolbar.css({
                top: top + 5,
                left: left,
                'margin-left': marginLeft
            });
        }

        // 隐藏 toolbar
        function hide() {
            if ($currentTable == null) {
                return;
            }
            $currentTable.removeClass('clicked');
            $currentTable = null;
            $toolbar.hide();
        }
        // click  事件
        $txt.unbind("mousedown").bind("contextmenu", function (e) {
            e.preventDefault();
            return false;
        });

        $txt.on('mousedown', 'p', function (e) {
            if(window.getSelection) {
                var textObj = document.getElementById("MathInput");
                var selectedText = window.getSelection().toString();
                //alert(selectedText);
                selectedText = "<span style='background:red'>"+selectedText+"</span>";

                var start = window.getSelection().anchorOffset;
                var end = window.getSelection().focusOffset;

                var tempStr1 = textObj.innerHTML.substring(0,start);
                var tempStr2 = textObj.innerHTML.substring(end);
                console.log(tempStr1 + selectedText + tempStr2)
                document.getElementById("MathInput").innerHTML = tempStr1 + selectedText + tempStr2 ;

            }

            var $table = $(e.currentTarget);
            if(3 == e.which){
                // 渲染
                render();
                if ($currentTable && ($currentTable.get(0) === $table.get(0))) {
                    setTimeout(hide, 100);
                    return;
                }
                // 显示 toolbar
                $currentTable = $table;
                show();
                // 阻止冒泡
                e.preventDefault();
                e.stopPropagation();

            }else if(1 == e.which){

            }
        }).on('click keypress scroll', function (e) {
            setTimeout(hide, 100);
        });
        E.$body.on('click keypress scroll', function (e) {
            setTimeout(hide, 100);
        });
    });

})();