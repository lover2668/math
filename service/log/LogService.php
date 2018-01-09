<?php
namespace service\log;

use service\log\AlgoApiLogService;
use service\log\BaseLogService;
use service\log\TichiApiLogService;
use service\lib\RabbitClientService;

class LogService
{
    private static $log_service;
    private static $methond_prefix="sendLog_for_";
    private static  $scheme= "test";
    private static $topics = array('warning','error','info');
    public function __construct($service=null)
    {
        switch ($service) {
            case "tichi":
                self::$log_service = new  TichiApiLogService();
                break;
            case "algo":
                self::$log_service = new AlgoApiLogService();
                break;
            case "user_action":
                self::$log_service = new UserActionLogService();
                break;
            default:
                self::$log_service = new BaseLogService();
                break;
        }
    }


    public static function sendMessage($topic, $message, $api_method=null)
    {
        if(in_array($topic,self::$topics))
        {
            $api_arr = self::$log_service->getApiArr();
            if(!$api_method)
            {
                self::$log_service->sendMessage($topic,$message,$api_method);
            }else{
                if(in_array($api_method,$api_arr))
                {
                    self::$log_service->sendMessage($topic,$message,$api_method);
                }else{
//                    self::$log_service->publish('error',__METHOD__.",配置参数中没有此方法！: ");
                    die(__METHOD__.",配置参数中没有此方法！: ");
                }
            }
//            try {
//                call_user_func_array(array(self::$log_service, $method), array($topic, $message));
//            } catch (\Exception $e) {
//                echo __METHOD__.",error: ".$e->getMessage();
//            }
        }else{
            die("消息模块发送类型不对,只允许发送 warning, error, info类型的消息");
//            self::$log_service->publish('error',__METHOD__.",配置参数中没有此方法！: ");

        }
    }
}
