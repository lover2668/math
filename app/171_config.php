<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用命名空间
    'app_namespace' => 'app',
    // 应用调试模式
    'app_debug' => true,
    // 应用Trace
    'app_trace' => false,
    // 应用模式状态
    'app_status' => '',
    // 是否支持多模块
    'app_multi_module' => true,
    // 注册的根命名空间
    'root_namespace' => [],
    // 扩展配置文件
    'extra_config_list' => ['database', 'route', 'validate','api_host_config','log_service_config'],
    // 扩展函数文件
    'extra_file_list' => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type' => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return' => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler' => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler' => 'callback',
    // 默认时区
    'default_timezone' => 'PRC',
    // 是否开启多语言
    'lang_switch_on' => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter' => '',
    // 默认语言
    'default_lang' => 'zh-cn',
    // 是否启用控制器类后缀
    'controller_suffix' => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module' => 'index',
    // 禁止访问模块
    'deny_module_list' => ['common'],
    // 默认控制器名
    'default_controller' => 'index',
    // 默认操作名
    'default_action' => 'index',
    // 默认验证器
    'default_validate' => '',
    // 默认的空控制器名
    'empty_controller' => 'Error',
    // 操作方法后缀
    'action_suffix' => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo' => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch' => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr' => '/',
    // URL伪静态后缀
    'url_html_suffix' => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param' => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type' => 0,
    // 是否开启路由
    'url_route_on' => true,
    // 是否强制使用路由
    'url_route_must' => false,
    // 域名部署
    'url_domain_deploy' => false,
    // 域名根，如.thinkphp.cn
    'url_domain_root' => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert' => true,
    // 默认的访问控制器层
    'url_controller_layer' => 'controller',
    // 表单请求类型伪装变量
    'var_method' => '_method',

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template' => [
        // 模板引擎类型 支持 php think 支持扩展
        'type' => 'Think',
        // 模板路径
        'view_path' => '',
        // 模板后缀
        'view_suffix' => 'php',
        // 模板文件名分隔符
        'view_depr' => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin' => '{',
        // 模板引擎普通标签结束标记
        'tpl_end' => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end' => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str' => [
        '__PUBLIC__' => '',
        '__ROOT__' => '/',
        'APP_PATH' => APP_PATH
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl' => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl' => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message' => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg' => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle' => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log' => [
        // 日志记录方式，支持 file socket
        'type' => 'File',
        // 日志保存目录
        'path' => LOG_PATH,
    ],

    // +----------------------------------------------------------------------
    // | Trace设置
    // +----------------------------------------------------------------------

    'trace' => [
        //支持Html Console
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

//    'cache' => [
//        // 驱动方式
//        'type' => 'File',
//        // 缓存保存目录
//        'path' => CACHE_PATH,
//        // 缓存前缀
//        'prefix' => '',
//        // 缓存有效期 0表示永久缓存
//        'expire' => 0,
//    ],
    'cache' => [
        // 驱动方式
        'type' => 'redis',
        'host' => '127.0.0.1',
        'port' => 6379,
        'select' => 0,
        'timeout' => 10,
        'expire' => 100,
        'persistent' => false,
        'prefix' => 'math:',
        //'password'   => 'yixuejiaoyu@201622',
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session' => [
        'id' => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix' => 'think',
        // 驱动方式 支持redis memcache memcached
        'type' => '',
        // 是否自动开启 SESSION
        'auto_start' => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie' => [
        // cookie 名称前缀
        'prefix' => '',
        // cookie 保存时间
        'expire' => 0,
        // cookie 保存路径
        'path' => '/',
        // cookie 有效域名
        'domain' => '',
        //  cookie 启用安全传输
        'secure' => false,
        // httponly设置
        'httponly' => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate' => [
        'type' => 'amazeui',
        'var_page' => 'page',
        'list_rows' => 15,
    ],
    //自定义的配置项
    "kmap_code" => "math_20160815_et",
    "api_url" => "http://139.196.92.195:8080",//测试机算法服务器 
    "math_classba_cn_api_url" => 'http://139.196.75.14:8080',//正式机算法服务器 
    "math_171xue_com_api_url" => 'http://139.196.75.14:8080',//正式机算法服务器 
    "xiance_module_type" => 1,
    "bxbl_module_type"=>2,
    "gaoxiao_module_type" => 2,  // 高效学习模块module_type,
    "xuexi_module_type" => 5,    // 学习检测模块 module_type,正确应该是5,目前测试先用 2.  提交的时候用这个。
//    "xuexi_module_type_moment" => 2,    // 学习检测模块 module_type,正确应该是5,目前测试先用 2.  取题的时候用这个。
    "zonghe_module_type" => 3,   // 综合提高模块 module_type
    "grip_module_type" => 4,     //招生抓手模块 module_type
    "test_question" => 1,
    "learn_question" => 2,
    //"zhlx_module_type" => 3,
    "elements_codes" => "cz1401,cz1402,cz1403,cz1404,cz1405,cz1406,cz1407,cz1408,cz1409,cz1410,cz1411,cz1412,cz1413,cz1414,cz1415,cz1416,cz1417,cz1418,cz1419,cz1420,cz1421,cz1422,cz1423,cz1424,cz1425,cz1426,cz1427,cz1428,cz1429,cz1430,cz1431,cz1432,cz1434",
    "question_server_host" => "http://input-math.classba.cn/",
    "level_mode" => 3,
    "assessment_size" => 1,
    "to_learn_num" => 3,   //高效学习每个知识点要学习的题的数量。
    "ability_standard" => 0.7,
    "ability_scale_standard" => 0.15,
    "tag_code" => "cn_1001",
    'token_key' => 'cxphp', //token加解密公钥
    'token_verify' => 'kunshan', //加盟校验证码,
    'is_open_testInfo_for_question_content'=>$_SERVER['HTTP_HOST']=='math.classba.com.cn'?true:false,   //控制试题ID和知识点的测试内容是否显示
    'default_diffculty' => '2',
    'api_server_user'=>'http://api.171xue.com/api.php',//天王星老师端api正式接口
//    'api_server_user'=>'http://api.classba.cn/api.php',//天王星老师端api正式接口
    'api_server_user_test'=>'http://test-sms2.classba.com.cn/api.php',//测试老师端api测试接口
    'api_server_user_171xue'=>'http://api.171xue.com/api.php',//171xue老师端api接口
    'sys_code'=>1,
    'logo_url'=>'javascript:void(0);'
];
