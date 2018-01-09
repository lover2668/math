<?php

namespace app\index\controller;

use  service\services\QuestionService;
use service\services\UserService;
use think\Request;
use think\Response;
use service\lib\RouterMiddleware;
use  service\algo\SummerAlgoLogic;
use  service\services\TopicV2Service;
use think\Session;

class Common extends \think\Controller
{
    protected $userInfo = array();  //用户全局基本信息，包括已经学过的状态

    protected function _initialize()
    {
        session('');
        // session_start();
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
        $topic_info= $topic_v2_service->getTopicByTopicId($topicId);
        $kmap_enter_key = $topic_info['kmap_enter_key'];
        $kmap_code_list = $topic_info['kmap_code_list'];
        $kmap_enter_code_info = $kmap_code_list[$kmap_enter_key];
        $kmap_enter_code = $kmap_enter_code_info['kmap_code'];
        $kmap_code = $kmap_enter_code;
        $sys_code = config("sys_code");
        $level_mode = config("level_mode_1");
        $init_kstatus = config('init_kstatus');
        $learn_times = 1;//学习当前专题的次数
        $total_level = config("total_level");//难度级别总数
        $algoLogic = new SummerAlgoLogic();
        $algo_session_id = $algoLogic->get_sessionId("$user_id",$topicId, $kmap_code, $curriculum_id, $sys_code, $level_mode, $init_kstatus, $learn_times, $total_level);

        return $algo_session_id;
    }


}