<?php
namespace service\log;

use service\lib\RabbitClientService;

class TichiApiLogService extends  BaseLogService
{

    private  static  $prefix = "call_tichi_";
    public   $scheme="tichi_log";
    private static $api_arr;

    function __construct()
    {
        $tichi_api_arr = config('log_service_config.tichi_api_arr');
        if($tichi_api_arr)
        {
            if(is_array($tichi_api_arr))
            {
                $this::$api_arr =$tichi_api_arr;
            }else{
                die("tichi_api_arr 的配置不是数组。");
            }
        }else{
            $this::$api_arr = array();
        }
    }

    public  function getApiArr()
    {
        return  self::$api_arr;
    }

    /**
     * @param $msg
     * @return array
     */
    public function initData($msg){
        $data =  array(
            'user_id'=>isset($msg['user_id'])?$msg['user_id']:"",
            'request_data'=>isset($msg['request_data'])?$msg['request_data']:"",   //请求数据,
            'response_data'=>isset($msg['response_data'])?$msg['response_data']:"",  //响应数据
            'stime'=>isset($msg['stime'])?$msg['stime']:"",     // 接口开始时间
            'etime'=>isset($msg['etime'])?$msg['etime']:"",     // 接口结束时间
            'ctime'=>isset($msg['ctime'])?$msg['ctime']:""      // 创建时间。
        );
        $data['scheme']= $this->scheme;
        return $data;
    }

    public function sendMessage($topic,$msg,$api_method)
    {
//        $message = $this->initData($msg);
//        $message['request_api'] = self::$prefix .$api_method;
//        $this->publish($topic,$message);
    }

}
