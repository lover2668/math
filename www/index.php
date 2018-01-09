<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../app/');
// 加载框架引导文件
//define('EXTEND_PATH', __DIR__ .'/../service/services');
define('SERVER_URL', isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST']);
include_once "front_version.php";
require __DIR__ . '/../thinkphp/start.php';


