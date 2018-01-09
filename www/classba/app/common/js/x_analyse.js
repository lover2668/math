/**
 * Created by linxiao on 17/7/15.
 */
(function(){


    var jqueryReady = function(callback) {
        if (window.jQuery) {
            callback(jQuery);
        }
        else {
            // 加载jquery
            var script = document.createElement("SCRIPT");
            script.src = 'https://cdn.bootcss.com/jquery/1.12.3/jquery.min.js';
            script.type = 'text/javascript';
            document.getElementsByTagName("head")[0].appendChild(script);
            window.setTimeout(function() { jqueryReady(callback); }, 20);
        }
    };

    jqueryReady(function($) {
        $(function() {
            var params = {};
            //获取url
            if(document) {
                params.url = document.URL || '';
            }
            if($_CONFIG){
                params.userid = $_CONFIG.uid || '';    //用户id，int类型
                params.user_name = $_CONFIG.user_name || '';
                params.ui = $_CONFIG.ui || '';
                params.course_type = $_CONFIG.course_type || '';// 课程类别，int类型（对应cid，1数学，2英语，3语文）
                params.course_id = $_CONFIG.course_id || '';// 课程id，int类型
                params.section_id = $_CONFIG.section_id || '';// 课次id，int类型（对应module_id）
                params.topic_id = $_CONFIG.topic_id || '';// 专题id，int类型
                params.question_id = $_CONFIG.question_id || '';// 题目id，
                //params.page_begin_time = "";    // 页面进入时间, int时间戳
                params.page_begin_time = Date.parse(new Date())||'';
                //params.page_begin_time = $_CONFIG.page_begin_time||'';
                params.video_url = $_CONFIG.video_url||'';
                params.video_played_time = $_CONFIG.video_played_time||'';
                params.video_pause_times = $_CONFIG.video_pause_times||'';
                params.actions = "";//行为动作，json文本，（暂时留空）
                //params.page_end_time = $_CONFIG.page_end_time ||''
            }

            /*
             $_CONFIG.ui:配置刷新的DOM id
             */
            if($_CONFIG){
                //局部刷新:需要配置监听的板块id:$_CONFIG.ui
                //var args = '';
                //for(var i in params) {
                //    if(args != '') {
                //        args += '&';
                //    }
                //    args += i + '=' + encodeURIComponent(params[i]);
                //}
                var title = $($_CONFIG.ui);//监控的节点
                title.bind('DOMNodeInserted', function(e) {
                    //拼接参数串
                    params.page_end_time = $_CONFIG.page_end_time ||'';
                    params.question_id = $_CONFIG.question_id || '';
                    var args = '';
                    for(var i in params) {
                        if(args != '') {
                            args += '&';
                        }
                        args += i + '=' + encodeURIComponent(params[i]);
                    }
                    //通过Image对象请求后端脚本
                    //title.unbind('DOMNodeInserted')
                    var img = new Image(1, 1);
                    img.src = 'http://pool.51yxedu.com/index.php/pool/Index/p?' + args;
                });
            }
            //else{
            //    //页面跳转
            //    window.onbeforeunload = function() {
            //        params.page_end_time = Date.parse(new Date())||'';
            //        //拼接参数串
            //        var args = '';
            //        for(var i in params) {
            //            if(args != '') {
            //                args += '&';
            //            }
            //            args += i + '=' + encodeURIComponent(params[i]);
            //        }
            //        //通过Image对象请求后端脚本
            //        var img = new Image(1, 1);
            //        img.src = 'http://pool.51yxedu.com/index.php/index/Probe/p?' + args;
            //    };
            //}
            window.onbeforeunload  = function() {
                if($_CONFIG){
                    params.userid = $_CONFIG.uid || '';    //用户id，int类型
                    params.user_name = $_CONFIG.user_name || '';
                    params.ui = $_CONFIG.ui || '';
                    params.course_type = $_CONFIG.course_type || '';// 课程类别，int类型（对应cid，1数学，2英语，3语文）
                    params.course_id = $_CONFIG.course_id || '';// 课程id，int类型
                    params.section_id = $_CONFIG.section_id || '';// 课次id，int类型（对应module_id）
                    params.topic_id = $_CONFIG.topic_id || '';// 专题id，int类型
                    params.question_id = $_CONFIG.question_id || '';// 题目id，
                    //params.page_begin_time = "";    // 页面进入时间, int时间戳
                    //params.page_begin_time = $_CONFIG.page_begin_time||'';
                    params.video_url = $_CONFIG.video_url||'';
                    params.video_played_time = $_CONFIG.video_played_time||'';
                    params.video_pause_times = $_CONFIG.video_pause_times||'';
                    params.actions = "";//行为动作，json文本，（暂时留空）
                    //params.page_end_time = $_CONFIG.page_end_time ||''
                }
                params.page_end_time = Date.parse(new Date())||'';
                //拼接参数串
                var args = '';
                for(var i in params) {
                    if(args != '') {
                        args += '&';
                    }
                    args += i + '=' + encodeURIComponent(params[i]);
                }
                //通过Image对象请求后端脚本
                var img = new Image(1, 1);
                img.src = 'http://pool.51yxedu.com/index.php/pool/Index/p?' + args;
            };
        });
    });


})();