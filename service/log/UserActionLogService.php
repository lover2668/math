<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 17/4/24
 * Time: 11:42
 */

namespace service\log;


class UserActionLogService extends  BaseLogService
{
    private  static  $prefix = "";
    public   $scheme="user_action_log";

    /**
     * @param $msg
     * @return array
     */
    public function initData($msg){
        $data = array(
            'url'=>isset($msg['url'])?$msg['url']:"",
            'domain'=>isset($msg['domain'])?$msg['domain']:"",
            'param'=>isset($msg['param'])?$msg['param']:"",
            'baseUrl'=>isset($msg['baseUrl'])?$msg['baseUrl']:"",
            'module'=>isset($msg['module'])?$msg['module']:"",
            'controller'=>isset($msg['controller'])?$msg['controller']:"",
            'action'=>isset($msg['action'])?$msg['action']:"",
            'server'=>isset($msg['server'])?$msg['server']:"",
            'header'=>isset($msg['header'])?$msg['header']:""
        );
        $data['scheme']= $this->scheme;
        return $data;
    }

    public function sendMessage($topic,$msg,$api_method)
    {
        $message = $this->initData($msg);
        $this->publish($topic,$message);
    }









}