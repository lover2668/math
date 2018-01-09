<?php
namespace service\services;
use think\Cache;
use  think\Db;
use think\Session;

use think\Log;

class CommonService
{

    public function __construct()
    {

    }



    /**
     * 获取用户的SESSION信息
     */
    public function getUserSession()
    {

        $user_info = session('userInfo');
        return $user_info;

    }

    /**
     * 获取用户ID.
     */
    public function getUserId()
    {
        $user_info = session('userInfo');
        $user_id = $user_info['user_id'];
        return $user_id;
    }


    /**
     * 销毁用户的session信息.
     */
    public function destroyUserSession()
    {
        session('userInfo', null);
    }

    /**
     * 设置用户的SESSION信息
     */
    public function setUserSessionInfo($data)
    {
        $user_data['username'] = $data['username'];
        $user_data['user_id'] = $data['user_id'];
        $user_data['topic_id'] = $data['topic_id'];
        ///////////////////////
        $user_data['course_id'] = isset($data['course_id'])?$data['course_id']:'0';
        $user_data['course_name'] = isset($data['course_name'])?$data['course_name']:'';
        $user_data['section_id'] = isset($data['section_id'])?$data['section_id']:'0';
        $user_data['section_name'] = isset($data['section_name'])?$data['section_name']:'';
        $user_data['class_id'] = isset($data['class_id'])?$data['class_id']:'0';
        $user_data['class_name'] = isset($data['class_name'])?$data['class_name']:'';

        $user_service = new UserService();
        $user_info =  $user_service->getUserName($data['user_id']);
        if(!empty($user_info))
        {
            $real_name =  $user_info['real_name'];
        }else{
            $real_name= $user_data['username'];
        }




//        $time = date("Ymd", time());;
//        $sessionId = $data['user_id']."-".$time;
//        session_id($sessionId);
//        session_start();
        ///////////////／
        $oldSid = $this->getSidByUid($user_data['user_id']);
        Log::record("old Sid:".$oldSid." for uid:".$user_data['user_id']);


//        if(!$oldSid)
//        {
//            $oldSid = session_id();
//        }
        //$ret = $this->cleanSid($oldSid);
        Log::record("old Sid:".$oldSid." for uid:".$user_data['user_id']." clean res:");
//        session_regenerate_id();
//        session_destroy();

        $newSid =session_id();

        Log::record("new------ Sid:".$newSid);

//        session_start();
        session('userInfo', $user_data);
        session('username', $user_data['username']);
        session('user_id',$data['user_id']);
        session('real_name', $real_name);
        session('topicId', $data['topic_id']);

	    $_SESSION['userInfo']=$user_data;
        $_SESSION['username']=$user_data['username'];
        $_SESSION['user_id']=$data['user_id'];
        $_SESSION['real_name']=$real_name;
        $_SESSION['topicId']=$data['topic_id'];


        $debug = isset($data['debug'])?$data['debug']:0;
        session('debug',$debug);
        $_SESSION['debug'] = $debug;

        Log::record("new Sid:".$newSid." for uid:".$user_data['user_id']);

        $ret = $this->saveSidByUid($user_data['user_id'], $newSid);
        Log::record("new Sid:".$newSid." for uid:".$user_data['user_id']." save res:".$ret.",userdata:".json_encode($user_data).",data:".json_encode($data).",sdata:".json_encode($_SESSION)); 
//        $sessionId =session_id();
//        $oldsid = getoldsidbyuid(uid);
//        cleansid($oldsid);
//        savesid（uid，$sid）；

    }

    public function getSidByUid($uid)
    {
        //Todo::: get Sid by uid from cache
        $sid = Cache::get('uid_sid_map_'.$uid);
        return $sid;
    }

    public function saveSidByUid($uid, $sid)
    {
        //Todo::: get Sid by uid from cache
        $ret = Cache::set('uid_sid_map_'.$uid, $sid,3600*24);

        return $ret;
    }

    private function cleanSid($sid)
    {
        $session_id_to_destroy = $sid;
// 1. commit session if it's started.
//        if (session_id()) {
//            session_commit();
//        }

// 2. store current session id
//        session_start();
//        $current_session_id = session_id();
//        session_commit();

// 3. hijack then destroy session specified.
        if($sid)
        {
//            session_destroy();
            session_id($session_id_to_destroy);
//            @session_start();
            //不清空原有数据。
            session_regenerate_id(false);
        }else{
//            @session_start();
            session_regenerate_id(true);
            Log::record(" 用户第一次进来没有session_id,自动生成了一个");


        }


//        session_destroy();
//        session_commit();
// 4. restore current session id. If don't restore it, your current session will refer     to the session you just destroyed!
//        session_id($current_session_id);
//        session_start();
//        session_commit();

        return true;
    }
}
