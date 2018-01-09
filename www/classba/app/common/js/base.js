/*
 * 入口程序
 */
;
layui.config({
    base: './static/app/common/module/'
}).use(['jquery', 'element', 'layer', 'navtab'], function () {
    window.jQuery = window.$ = layui.jquery;
    window.layer = layui.layer;
    var element = layui.element();
    var navtab = layui.navtab({
        'container': '.xx-tab-box'
    });

    // 左侧导航向左折叠
    $('.xx-side-menu').click(function () {
        var sideWidth = $('#xx-side').width();
        if (sideWidth === 200) {
            $('#xx-body').animate({
                left: '0'
            }); //admin-footer
            $('#xx-footer').animate({
                left: '0'
            });
            $('#xx-side').animate({
                width: '0'
            });
        } else {
            $('#xx-body').animate({
                left: '200px'
            });
            $('#xx-footer').animate({
                left: '200px'
            });
            $('#xx-side').animate({
                width: '200px'
            });
        }
    });

    // 左侧菜单导航在navatab中打开
    $('#xx-nav-side').click(function () {
        if ($(this).attr('lay-filter') !== undefined) {
            $(this).children('ul').find('li').each(function () {
                var $this = $(this);
                if ($this.find('dl').length > 0) {
                    var $dd = $this.find('dd').each(function () {
                        $(this).click(function () {
                            var $a = $(this).children('a');
                            var href = $a.data('url');
                            var icon = $a.children('i:first').data('icon');
                            var title = $a.children('span').text();
                            var data = {
                                href: href,
                                icon: icon,
                                title: title
                            }
                            navtab.tabAdd(data);
                        });
                    });
                } else {
                    $this.click(function () {
                        var $a = $(this).children('a');
                        var href = $a.data('url');
                        var icon = $a.children('i:first').data('icon');
                        var title = $a.children('span').text();
                        var data = {
                            href: href,
                            icon: icon,
                            title: title
                        }
                        navtab.tabAdd(data);
                    });
                }
            });
        }
    });

    // iframe自适应
    $(window).on('resize', function () {
        var $content = $('#xx-tab .layui-tab-content');
        $content.height($(this).height() - 146);
        $content.find('iframe').each(function () {
            $(this).height($content.height());
        });
        tab_W = $('#xx-tab').width();
        // xx-footer：p-admin宽度设定
        var xxFoot = $('#xx-footer').width();
        $('#xx-footer p.p-admin').width(xxFoot - 300);
    }).resize();

    //--运行入口--
    $(function () {
        // 注入菜单
        // var $menu = $('.xx-tab-menu');
        // console.log($menu);
        // $('#xx-tab .layui-tab-title').append($menu);

    });
});