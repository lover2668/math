<?php
namespace service\log;

use service\lib\RabbitClientService;

class BaseLogService
{
    public $scheme= "base_log";
    private static $api_arr="";

    /**
     * 获取发送日志的服务器IP
     */
    public static function getHostIp()
    {
        $host_ip = get_server_ip();
        return  $host_ip;
    }

    public  function getApiArr()
    {
        return  self::$api_arr;
    }



    public static function getClientIp()
    {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        }
        elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        }
        elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');

        }
        elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     *
     */
    public static function getHostDomain()
    {
        $host_domain = $_SERVER['SERVER_NAME'];
        return  $host_domain;
    }
    /**
     * 此处msg必须是string类型。
     * @param $topic
     * @param $msg
     */
    public function publish($topic,$msg)
    {
        $host_ip = self::getHostIp();
        $message = array();
        $message['host_domain'] = self::getHostDomain();
        $message['username'] = session('username');
        $message['user_id'] = (int)session('user_id');
        $message['host_ip'] = $host_ip;
        $message['client_ip'] = self::getClientIp();
        $message['day_time'] =date("Y-m-d",time());
        $message['scheme'] = $this->scheme;
        if(is_array($msg))
        {
            $message = array_merge($message,$msg);
        }else{
            $message['host_ip'] = $host_ip;
            $message['message'] = $msg;
        }
        $message['ctime'] = time();
        $message['topic'] = $topic;
        $message = json_encode($message);
        try {
            //如果是本机，直接发送消息，如果是线上服务器，走redis转发。
            $is_localhost = config("is_localhost");
            if($is_localhost)
            {
            $rabbit_server = new  RabbitClientService();
            $rabbit_server->publish($topic,$message);
            }else{
                $redis_log  = new RedisLog();
                $msg = array(
                    'topic'=>$topic,
                    'msg'=>$message
                );
                $redis_log->publish($topic,$msg);
            }



        } catch (\Exception $e) {
//            echo __METHOD__.",error: ".$e->getMessage();
            //如果服务停了要报警。
        }
    }

    public function sendMessage($topic,$msg,$api_method)
    {
        if(is_string($msg))
        {
            $this->publish($topic,$msg);
        }else{
            die(__METHOD__.", base log service 的msg信息必须是string");
        }
    }



    public function publishMsg()
    {


        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $count = $redis->exists('count') ? $redis->get('count') : 1;



    }

}
