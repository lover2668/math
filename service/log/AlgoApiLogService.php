<?php
namespace service\log;
use service\lib\RabbitClientService;

class AlgoApiLogService extends BaseLogService
{
    private static $prefix = "call_algo_";
    public  $scheme = "algo_log";
    private static $api_arr;
    function __construct()
    {
        $algo_api_arr = config('log_service_config.algo_api_arr');
        if($algo_api_arr)
        {
            if(is_array($algo_api_arr))
            {
                $this::$api_arr =$algo_api_arr;
            }else{
                die("algo_api_arr 的配置不是数组。");
            }
        }else{
            $this::$api_arr = array();
        }
    }

    public  function getApiArr()
    {
        return  self::$api_arr;
    }


    public function getPrefix()
    {
        return  self::$prefix;
    }

    function initData($msg=array())
    {
        $data = array(
            "user_id"=>isset($msg["user_id"])?$msg["user_id"]:"",//用户id
            "topicId"=>isset($msg['topicId'])?$msg["topicId"]:"",//专题id
            "module_type"=>isset($msg['module_type'])?$msg["module_type"]:"",//模块id
            "kmap_code"=>isset($msg['kmap_code'])?$msg["kmap_code"]:"",//知识图谱
            'request_data' => isset($msg["request_data"])?$msg["request_data"]:"",   //请求数据
            'response_data' =>isset($msg["response_data"])?$msg["response_data"]:"",  //响应数据
            'stime' =>isset($msg["stime"])?$msg["stime"]:"",     // 接口开始时间
            'etime' => isset($msg['etime'])?$msg["etime"]:"",     // 接口结束时间
            'ctime' =>isset($msg['ctime'])?$msg["ctime"]:""      // 创建时间。
        );
        $data['scheme']= $this->scheme;
        return $data;
    }


    public function sendMessage($topic,$msg,$api_method)
    {
        $message = $this->initData($msg);
        $message['request_api'] = self::$prefix .$api_method;
        $this->publish($topic,$message);
    }


    /**
     * 静态调用
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
//        if (in_array($method, self::$type)) {
//            array_push($args, $method);
//            return call_user_func_array('\\think\\Log::record', $args);
//        }

    }



//    public function publish($topic,$msg)
//    {
//        $rabbit_server = new  RabbitClientService();
//        $msg['scheme'] = self::$scheme;
//        $message = json_encode($msg);
//        $rabbit_server->publish($topic,$message);
//    }



}
