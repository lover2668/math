<?php

namespace app\index\controller;

use  service\services\QuestionService;
use service\services\UserService;
use think\Request;
use think\Response;
use service\lib\RouterMiddleware;
use  service\algo\SummerAlgoLogic;
use  service\services\TopicV2Service;
use service\services\CommonService;
use think\Log;
use think\Session;


class Base extends \think\Controller
{
    protected $userInfo = array();  //用户全局基本信息，包括已经学过的状态

    protected function _initialize()
    {
        session('');
        //设置日志的transId
        $trans_id  = buildTransId();
        config('log.trans_id',$trans_id);

        ini_set("precision", 16);//修复json_encode,decode 小数问题
//        if(empty(session_id()))
//        {
////            session_start();
//            Session::start();
//        }

        $ajax_uid = '';
        if(array_key_exists('user_id', $_REQUEST))
        {
            $ajax_uid = $_REQUEST['user_id'];
        }
        
        // session_start();
        //判断是否登录
        if (session('userInfo')) {

            $cur_sid = session_id();

            $commService = new CommonService();
            $saved_sid = $commService->getSidByUid($this->getUserId());

            Log::record('Login Check, cur_sid:'.$cur_sid.",saved_sid:".$saved_sid.",uid:".$this->getUserId().",sessiondata:".json_encode(session('userInfo')));

            //
            if(!empty($saved_sid) && $cur_sid != $saved_sid)
            {
                Log::record('Login Check, cur_sid:'.$cur_sid.",saved_sid:".$saved_sid.",uid:".$this->getUserId().",check failed");
//                echo "您的账号已经在其它地方登录,跳转到登录页面.";
                $this->error('您的账号已经在其它地方登录,跳转到登录页面.', 'Index/Login/login');
                exit;
            }

            $cur_uid = $this->getUserId();
            if(!empty($ajax_uid) && $cur_uid != $ajax_uid)
            {
                Log::record('Login Check ajax uid check fail, cur_uid:'.$cur_uid.",ajax_uid:".$ajax_uid);
//                echo "您的账号已经在其它地方登录,跳转到登录页面.";
                $this->error('您做的提交数据异常，请重新登录,跳转到登录页面.', 'Index/Login/login');
                exit;
            }


            //用户登陆成功的话，调用下
            $request = Request::instance();
            RouterMiddleware::analyzeRequest($request);
            $topicId = $request->param("topicId");
//            $debug = $request->param('debug');
            $debug = session('debug');
            $course_id = $request->param('course_id') ? $request->param('course_id') : "0";
            $section_id = $request->param('section_id') ? $request->param('section_id') : "0";
            session('course_id',$course_id);
            session('section_id',$section_id);
            $session_topic_id = session("topicId");
            if(!$debug)
            {
                //一个用户同一时间只能做一个专题，如果做了两个专题的话，会清空因上一个专题产生的session信息。

                Log::record('Login Check, session_topic_id:'.$session_topic_id.",topicId:".$topicId.",uid:".$this->getUserId());


                Log::record("session_topic_id---------".$session_topic_id."------topicId----".$topicId);

                if($session_topic_id)
                {
                    //topicId存储过session后，判断用户做的专题是否是存储的topicId
                    if($session_topic_id != $topicId)
                    {
                        exit("不能同时学习多个专题！,请退回去重新选择课程！");
                    }
                }
            }

            if ($topicId) {
                $curriculum_id = $course_id . "_" . $section_id . "_" . $topicId;
                session('topicId',$topicId);
                log::record(" -------取SESSION_ID-------START-");
                $topic_v2_service = new TopicV2Service();
                $topicInfo = $topic_v2_service->getTopicByTopicId($topicId);
                if(isset($topicInfo['flow_id']))
                {
                    $flow_id = $topicInfo['flow_id'];
                }else{
                    $flow_id =1;
                }
                //目前只有暑期流程用到新算法。以后有需要改动的时候再改！
                if($flow_id==5)
                {
                    $algo_session_id = $this->getAlgoSessionId($curriculum_id, $topicId);
                }
                log::record(" -------取SESSION_ID-------END---");
            } else {
                if($debug)
                {

                }else{
                    if(Request::instance()->isAjax())
                    {

                    }else{
                        die("没有选择课程（topicId），,请从学生端登录。");
                    }
                }
            }
        } else {
            echo "登录失败,跳转到登录页面.";
            $this->redirect('Index/Login/login');
        }


    }

    /**
     * 获取用户SESSION信息
     * @return mixed
     */
    public function getUserInfo()
    {
        $userInfo = session('userInfo');
        return $userInfo;
    }

    /**
     * 获取用户的user_id
     * @return user_id
     */
    public function getUserId()
    {
        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
        return $user_id;
    }

    /**
     * 404跳转页面
     */
    protected function error404()
    {

    }

    /**
     * 403跳转页面
     */
    protected function error403()
    {


    }


    function getTopicId()
    {
        $userService = new UserService();
        $topicId = $userService->getTopicId();
        return $topicId;
    }


    /**
     * 获取算法需要的algo_session_id;
     * @param $curriculum_id
     * @param $topicId
     * @return string
     */
    function getAlgoSessionId($curriculum_id, $topicId)
    {
        $user_id = $this->getUserId();
        $topic_v2_service = new TopicV2Service();
        $kmap_code = $topic_v2_service->getKmapCodeAll($topicId);
        $sys_code = config("sys_code");
        $level_mode = config("level_mode_1");
        $init_kstatus = config('init_kstatus');
        $learn_times = 1;//学习当前专题的次数
        $total_level = config("total_level");//难度级别总数
        $algoLogic = new SummerAlgoLogic();
        log::record(__METHOD__." -------algo_logic----get_sessionId----start-----");

        $algo_session_id = $algoLogic->get_sessionId("$user_id",$topicId, $kmap_code, $curriculum_id, $sys_code, $level_mode, $init_kstatus, $learn_times, $total_level);
        log::record(__METHOD__." -------algo_logic----get_sessionId----end-----");


        return $algo_session_id;
    }



}
