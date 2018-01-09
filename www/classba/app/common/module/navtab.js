/**
 * 导航内容标签
 * 使用方法：
 * 1. 给navtab区域外围用xx-tab-box类包裹，或者初始化设置container
 * 2. 设置tab是否可关闭
 */

;
layui.define(['element', 'layer'], function (exports) {
    var module_name = 'navtab';
    var element = layui.element();
    var layer = layui.layer;
    var $ = layui.jquery;
    var layer = parent.layer || layui.layer;
    var globalTabIndex = 0;
    var _tab = {};	//tab对象
    var NavTab = function () {
        this.config = {
            'container': '.xx-tab-box', //navtab内容的容器对象类，默认为xx-tab-box
            'closable': true, //tab是否可关闭，true为可关闭（默认）
            'updatable': true, //tab是否可更新，true为可更新（默认）
            'auto_url': true, //自动注册超链接在iframe中打开（默认开启）
            'default_icon': '&#xe623;', //使用layui内置的对号图标
            '_iframe_class': '.xx-iframe', //iframe标识class名，默认为.xx-iframe
            '_url_tag': 'in-navtab' //给a标签添加的tag，标识在navtab中打开
        };
    };	//NavTab对象

    /**
     * 初始化参数 options
     */
    NavTab.prototype.init = function (options) {
        var _this = this;
        options = options || {};
        $.extend(true, _this.config, options);

        //注册a标签事件
        $(document).on('click', 'a[' + _this.config._url_tag + ']', function (e) {
            var $this = $(this);
            var href = $this.data('url') || $this.attr('href');
            var icon = $this.data('icon') || _this.config.default_icon;
            var title = $this.attr('title') || $this.children('span').text();
            title = $.trim(title);

            var data = {
                'href': href,
                'icon': icon,
                'title': title
            };

            _this.tabAdd(data);
            return false;
        });

        return _this;
    };

    /**
     * 初始化对象
     */
    NavTab.prototype._init_ = function () {
        var _this = this;
        var _config = _this.config;
        var _error = false;

        if (typeof (_config.container) !== 'string' && typeof (_config.container) !== 'object') {
            layer.alert('初始化参数格式错误！');
            _error = true;
        }

        var $container = {};

        if (typeof (_config.container) === 'string') {
            $container = $('' + _config.container + '');
        }

        if (typeof (_config.container) === 'object') {
            $container = _config.container;
        }

        if ($container.length === 0) {
            layer.alert('Tab选项卡参数有误！');
            _error = true;
        }

        var filter = $container.attr('lay-filter');
        if (!filter) {
            layer.alert('请为Tab容器设置一个lay-filter过滤器用于事件选择！');
            _error = true;
        }

        if (!_error) {
            _config.container = $container;
            _tab.titleBox = $container.children('ul.layui-tab-title');	//保持与layui一致
            _tab.contentBox = $container.children('div.layui-tab-content');	//保持与layui一致
            _tab.tabFilter = filter;
        }
        return _this;
    };

    /**
     * 检查是否存在Tab项，存在则返回其索引值，否则返回-1
     * @return {[type]} [description]
     */
    NavTab.prototype.exist = function (title) {
        var _this = _tab.titleBox || this._init_();
        var tabIndex = -1;
        _tab.titleBox.find('li').each(function (i, e) {
            var $em = $(this).children('em');
            if ($em.text() === title) {
                tabIndex = i;
                return false;
            }
        });
        return tabIndex;
    };

    /**
     * 添加选项卡，如果存在则增加突显样式
     * 参数data包括:
     * {href:iframe地址,icon:}
     */
    NavTab.prototype.tabAdd = function (data) {
        var _this = this;
        var tabIndex = _this.exist(data.title);

        if (tabIndex === -1) {
            globalTabIndex++;
            var content = '<iframe src="' + data.href + '" data-id="' + globalTabIndex + '" class="' + _this.config._iframe_class + '"></iframe>';	//iframe样式
            var title = '';

            //处理图标
            if (data.icon) {
                if (data.icon.indexOf('icon-') >= 0) {
                    title += '<i class="iconfont ' + data.icon + '"></i>';
                } else {
                    title += '<i class="layui-icon">' + data.icon + '</i>';
                }
            }

            //处理标签title
            title += '<em>' + data.title + '</em>';
            if (_this.config.closable) {
                title += '<i class="layui-icon layui-unselect layui-tab-close" data-id="' + globalTabIndex + '">&#x1006;</i>';	//添加关闭图标
            }

            //添加新tab
            element.tabAdd(_tab.tabFilter, {
                'title': title,
                'content': content
            });

            //处理iframe
            _tab.contentBox.find('iframe[data-id=' + globalTabIndex + ']').each(function () {
                $(this).height(_tab.contentBox.height());
            });

            //判断tab是否可关闭，添加关闭图标
            if (_this.config.closable) {
                //关闭事件
                $(document).on('click', 'i.layui-tab-close[data-id=' + globalTabIndex + ']', function (event) {
                    event.preventDefault();
                    element.tabDelete(_tab.tabFilter, $(this).parent('li').index()).init();
                });
            }

            //切换到新加tab上
            element.tabChange(_tab.tabFilter, _tab.titleBox.find('li').length - 1);
        } else {
            element.tabChange(_tab.tabFilter, tabIndex);
        }
    };

    //暴露函数
    exports(module_name, function (options) {
        var navTab = new NavTab();
        return navTab.init(options);
    });
});