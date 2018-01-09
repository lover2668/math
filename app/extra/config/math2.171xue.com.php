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

//    'log' => [
//        // 日志记录方式，支持 file socket
//        'type' => 'File',
//        // 日志保存目录
//        'path' => LOG_PATH,
//    ],

    'log' => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'         => 'YXLog\driver\YXLog',
        // 日志保存目录
        'path'         => LOG_PATH,
        // 日志记录级别，系统内置['log', 'error', 'info', 'sql', 'notice', 'alert', 'debug'];
        'level'        => [],
        //拆分日志的文件大小(默认2M)
        'file_size'    => 2097152,
        // 将错误、调试、日志单独记录
        'apart_level'  => [],
        // 日志文件前缀 (YXLog定义，如果没有指定或为空，则使用访问地址作为文件前缀)
        'prefix'       => 'math_',
        //是否保存tp产生的日志info信息 (YXLog定义)
        'save_tp_info' => true,
        'trans_id'=>"test"
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
        'host' => 'r-uf620b65e74fb164.redis.rds.aliyuncs.com',
        'port' => 6379,
        'select' => 0,
        'timeout' => 10,
        'expire' => 100,
        'persistent' => false,
        'prefix' => 'math_online:',
        'password'   => 'Pzh6537projectx',
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session' => [
        'id' => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        // 驱动方式 支持redis memcache memcached
        'type' => 'redis',
        // 是否自动开启 SESSION
        'auto_start' => true,
        'host'         => 'r-uf68bc2a5f89e984.redis.rds.aliyuncs.com', // redis主机
        'port'         => 6379, // redis端口
        'password'     => 'Pzh6537projectx', // 密码
        'expire'       => 3600, // 有效期(秒)
        'timeout'      => 60, // 超时时间(秒)
        'persistent'   => true, // 是否长连接
        'session_name' => 'math_online:'// sessionkey前缀
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
    "api_url" => "http://algo.171xue.com",//测试机算法服务器
    "math_classba_cn_api_url" => 'http://139.196.75.14:8080',//正式机算法服务器
    "math_171xue_com_api_url" => 'http://139.196.75.14:8080',//正式机算法服务器
    "xiance_module_type" => 1,
    "bxbl_module_type"=>2,
    "gaoxiao_module_type" => 2,  // 高效学习模块module_type,
    "xuexi_module_type" => 5,    // 学习检测模块 module_type,正确应该是5,目前测试先用 2.  提交的时候用这个。
    "mncs_module_type" => 5,    // 模拟测试模块 module_type
//    "xuexi_module_type_moment" => 2,    // 学习检测模块 module_type,正确应该是5,目前测试先用 2.  取题的时候用这个。
    "zonghe_module_type" => 3,   // 综合提高模块 module_type
    "grip_module_type" => 4,     //招生抓手模块 module_type
    "test_question" => 1,
    "learn_question" => 2,
    //"zhlx_module_type" => 3,
    "elements_codes" => "cz1401,cz1402,cz1403,cz1404,cz1405,cz1406,cz1407,cz1408,cz1409,cz1410,cz1411,cz1412,cz1413,cz1414,cz1415,cz1416,cz1417,cz1418,cz1419,cz1420,cz1421,cz1422,cz1423,cz1424,cz1425,cz1426,cz1427,cz1428,cz1429,cz1430,cz1431,cz1432,cz1434",
    "question_server_host" => "http://input-math.classba.cn/test/test",
    "level_mode" => 3,
    "assessment_size" => 1,
    "to_learn_num" => 3,   //高效学习每个知识点要学习的题的数量。
    "ability_standard" => 0.7,
    "ability_scale_standard" => 0.15,
    "tag_code" => "cn_1001",
    'token_key' => 'cxphp', //token加解密公钥
    'token_verify' => 'kunshan', //加盟校验证码,
//    'is_open_testInfo_for_question_content'=>$_SERVER['HTTP_HOST']=='math.classba.com.cn'?true:false,   //控制试题ID和知识点的测试内容是否显示
    'is_open_testInfo_for_question_content'=>false,   //控制试题ID和知识点的测试内容是否显示
    'default_diffculty' => '2',
    'api_server_user'=>'http://api3.171xue.com/api.php',//天王星老师端api正式接口
    'sys_code'=>1,
    'logo_url'=>'javascript:void(0);',
    'sys_code'=>"2020172001",
    'total_level'=>9,
    'init_kstatus'=>3,
    'new_algo_api_url'=>"http://algo.171xue.com",
    'new_topic_service_api_url'=>"http://api-topic.51yxedu.com",
    'algo_session_code'=>"algo_session_id",
    'level_mode_1'=>1,
    'l1_module_type'=>8,
    'l1_base_ability_standard'=>0.6,
    'l1_gg_ability_standard'=>0.8,
    'l1_module_type'=>8,   //L1基础学习测试
    'l2_xiance_module_type'=>9,   //L2学习测试的先行测试
    'l2_bxbl_module_type'=>10,   //L2学习测试的边学边练（没有学习检测）
    'l2_jingsai_module_type'=>11,  //L2学习测试的竞赛拓展
    //    'v2_question_server_host'=>"http://api-qb-math.51xstudy.com/",
    'v2_question_server_host'=>"http://api-qb.51xstudy.com/math/v2/",  //题库接口
    "is_open_tichi_api"=>false,
    'preview_class_module_type'=>12,
    'tiku_base_module_type'=>6, //题库中基础模块的值
    'tiku_gg_module_type'=>7,  //题库巩固模块的值。
    "estimates_time"=>120,//设置做题时间
    "demo_url"=>"http://demo.171xue.com/math/Login/loginIn/token/",
    "xiance_section_code" => 1011, //学前测试阶段编码
    "learn_section_code" => 1021, //学习阶段编码
    "houce_section_code" => 1031,  //学后测试阶段编码
    "school"=>"171xue", // 

];

