<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/5/12
 * Time: 下午3:09
 */
namespace service\lib;
use think\Request;

use service\log\LogService;


class RouterMiddleware
{

    /**
     * RabbitLogService constructor.
     */
    public function __construct()
    {


    }

    /**
     * 日志分析
     * @param Request $request
     */
    public static function analyzeRequest(Request $request)
    {
        $msg = array(
            'url'=>$request->url(),
            'domain'=>$request->domain(),
            'param'=>$request->param(),
            'header'=>$request->header(),
            'baseUrl'=>$request->baseUrl(),
            'module'=>$request->module(),
            'controller'=>$request->controller(),
            'action'=>$request->action(),
            'server'=>$request->server(),
            'header'=>$request->header()
        );
        $log_service = new logService('user_action');
        $topic = "info";
        $log_service::sendMessage($topic,$msg);
    }


}


?>
