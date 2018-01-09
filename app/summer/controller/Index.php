<?php

namespace app\summer\controller;

use service\services\BaseQuestionV2Service;
use service\services\KnowledgeV2Service;
use think\Request;
use service\services\summer\SummerQuestionService;
use service\services\QuestionService;
use service\services\TopicV2Service;
use service\algo\SummerAlgoLogic;
use service\services\UserService;
use service\services\summer\SummerUserService;
use think\Log;
use app\index\controller\Base;
use service\algo\AlgoLogic;

class Index extends Base
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }


    public function topicList()
    {
        $topicV2_service = new TopicV2Service();
        $topic_list = $topicV2_service->getTopicList();

        var_dump($topic_list);

    }

    public function index()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
        $user_id = $this->getUserId();

        $summer_user_service = new SummerUserService();
        $learned_info =$summer_user_service->getUserLearnedL1XianceInfo("",$topicId,$module_type);

        $learned_num =count($learned_info);

        if($learned_num>=1)
        {
            $this->redirect("Index/backTestIndex",["topicId"=>$topicId]);
        }else{
            $this->assign('topicId',$topicId);
            return $this->fetch("index");
        }



    }


    public function getExamQuestions()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
        $question_service = new QuestionService();
        $summer_question_service = new SummerQuestionService();
        $summer_user_service = new SummerUserService();
        $user_service = new UserService();
        $submodule_type = 1;
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);


        $has_answered_questions = $summer_user_service->getUserAnsweredQuestionsByModule("", $topicId, $batch_num,$module_type,$submodule_type);

        $right_num = 0;
        foreach ($has_answered_questions as $k=>$v) {
                if($v['is_right']==1)
                {
                    $right_num++;
                }
        }

        $has_answered_questions_num = count($has_answered_questions);
        if($has_answered_questions_num==0)
        {
            $right_scale = 0;
        }else{
            $right_scale = $right_num/$has_answered_questions_num;

        }
        $has_learedCode_scale = $summer_user_service->getUserHasLearnedCodeScale("",$topicId,$module_type);

        $remainder =  $has_answered_questions_num%15;
        $now_num = $has_answered_questions_num/15;
        $lastUserLog = $question_service->getLastUserExamActionLog($topicId, $module_type);
        $summer_user_service = new SummerUserService();
        $user_id = $this->getUserId();

        $row = $user_service->getUserStep($topicId, $user_id, $module_type);

        if ($row) {
            //如果找到并且已经结束
            if ($row["is_end"] == 1) {
                Log::record("------" . __FUNCTION__ . "---11111---");


                session($topicId.":middle_num",14);
                $returnData = array(
                    'has_answered_questions' => array(),
                    'is_end' => 1,
                    'question_list' => "",
                    'tag_code' => "",
                    'tag_name' => "",
                    'error'=>"您已做完L1"
                );
                echo json_encode($returnData);
                return;
            }else{
                $is_end =  $summer_user_service->getUserTinyStep($user_id,$topicId,$module_type,$submodule_type,$batch_num);

                Log::record("------" . __FUNCTION__ . "---2222---");

                if($is_end)
                {
                    session($topicId.":middle_num",11);
                    $returnData = array(
                        'has_answered_questions' => array(),
                        'is_end' => 1,
                        'question_list' => "",
                        'tag_code' => "",
                        'tag_name' => "",
                        'error'=>"您已做完L1的先行测试"
                    );
                    echo json_encode($returnData);
                    return;
                }else{
                    Log::record("------" . __FUNCTION__ . "---333---");

                    $jump_num =  session('jump_num');
                    if($remainder==0&&$jump_num!=$now_num)
                    {
                        session($topicId.":middle_num",10);
                        session('jump_num',$jump_num);
                        $returnData = array(
                            'has_answered_questions' => array(),
                            'is_end' => 1,
                            'question_list' => "",
                            'tag_code' => "",
                            'tag_name' => "",
                            'error'=>"您已做完L1的先行测试"
                        );
                        echo json_encode($returnData);
                        return;
                    }
                }

            }
        }else{
            $is_end =  $summer_user_service->getUserTinyStep($user_id,$topicId,$module_type,$submodule_type,$batch_num);
            Log::record("------" . __FUNCTION__ . "---4444---");


            if($is_end)
            {
                session($topicId.":middle_num",11);
                $returnData = array(
                    'has_answered_questions' => array(),
                    'is_end' => 1,
                    'question_list' => "",
                    'tag_code' => "",
                    'tag_name' => "",
                    'error'=>"您已做完L1的先行测试"
                );
                echo json_encode($returnData);
                return;
            }else{
                Log::record("------" . __FUNCTION__ . "---555---");

                $jump_num =  session('jump_num');
                if($remainder==0&&$jump_num!=$now_num)
                {
                    session($topicId.":middle_num",10);
                    session('jump_num',$now_num);
                    $returnData = array(
                        'has_answered_questions' => array(),
                        'is_end' => 1,
                        'question_list' => "",
                        'tag_code' => "",
                        'tag_name' => "",
                        'error'=>"您已做完L1的先行测试"
                    );
                    echo json_encode($returnData);
                    return;
                }
            }
        }

        if (!empty($lastUserLog) && $lastUserLog['is_submit'] != 1) {
            Log::record("------" . __FUNCTION__ . "---6666---");

            session('tag_code', $lastUserLog['tag_code']);
            $tag_code=session('tag_code');
            $knowledge_v2_service =  new KnowledgeV2Service();
            $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
            $tag_name  = $tag_info['tag_name'];
            $question_v2_service = new BaseQuestionV2Service();
            $question_list=$question_v2_service->getQuestionById($lastUserLog['question_id']);
            $return_data = array(
                "is_end" => 0,
                "question_list" => $question_list,
                "has_answered_questions" => $has_answered_questions,
                "tag_code" => $lastUserLog['tag_code'],
                "tag_name"=>$tag_name
            );
        } else {
            Log::record("------" . __FUNCTION__ . "---7777---");

            $summer_algo_logic  = new SummerAlgoLogic();
            $next_tag_code = $summer_algo_logic->get_summer__xiance_tagCode($topicId,$module_type,$submodule_type);
//            $next_tag_code= -1;
            $module_type = config('l1_module_type');
            $used_type = 1;
            //此处等于－1的时候，整个L1结束。
            if($next_tag_code==-1)
            {
                Log::record("------" . __FUNCTION__ . "---88888---");

                $weak_elements = $summer_algo_logic->getWeakElements($user_id,$topicId,$module_type,$submodule_type,$batch_num);
                $weak_num = count($weak_elements);
                //薄弱知识点为空的时候，L1学习模块结束。
                //表示L1结束了
                if($weak_num==0)
                {
                    session($topicId.":middle_num",14);
                    $SteplogService_id = $user_service->insertUserStep($topicId, $module_type, 0, 1);
                }else{
                    session($topicId.":middle_num",11);
                }
                $return_data = array(
                    'has_answered_questions' => array(),
                    'is_end' => 1,
                    'question_list' => "",
                    'tag_code' => "",
                    'tag_name' => ""
                );

                $is_end = 1;
                $summer_user_service->insertUserTinyStep($topicId, $module_type,$submodule_type,$batch_num,  $is_end);

            }else{
                Log::record("------" . __FUNCTION__ . "---9999---");

//            $tag_code_key = $topicId."_tag_code";
                session('tag_code',$next_tag_code);
                //结束。
                $summer_question_service = new SummerQuestionService();
                $question_service = new  QuestionService();
                $question_list =  $summer_question_service->getSummerXianceNextQuestion($topicId,$next_tag_code,$module_type,$used_type);
                //        $question_id = "589980b2f4aeb569992f0a01";//填空
//        $question_id = "58ad03fef4aeb573300b1c8f";//多个空
//                $question_id = "97192952543052058";//选择题


                $knowledge_v2_service =  new KnowledgeV2Service();
                $tag_info =$knowledge_v2_service->getKnowledgeByCode($next_tag_code);
                $tag_name  = $tag_info['tag_name'];
                $question_id = $question_list["id"];
//                $question_id = "97192952543052058";
//                $question_id = "97192952543052225";
                $question_service->insertUserExamActionLog('', $topicId, $module_type, $question_id, $question_list, $next_tag_code);
                $return_data = array(
                    'has_answered_questions' => $has_answered_questions,
                    'is_end' => 0,
                    'question_list' => $question_list,
                    'tag_code' => $next_tag_code,
                    'tag_name' => $tag_name
                );
            }
        }
        $return_data['right_scale'] = $right_scale;
        $return_data['has_learedCode_scale'] = $has_learedCode_scale;

        echo json_encode($return_data);
    }

    /**
     * L1先行测试提交
     */
    public function submitQuestion()
    {

//        $request = Request::instance();
        //$topicId=$this->getTopicId();
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $answer_content = input("answer_content/a");
//        session('tag_code',"c210301");
//        $tag_code = session('tag_code');
        $module_type = config('l1_module_type');
        $stage_code = config('xiance_section_code');
        $tag_code = session('tag_code');
        $used_type = 1;   //1 表示测试题,  2 表示练习题
        $question_service = new QuestionService();
        $submodule_type = 1;
        try {
            $isSuccess = $question_service->submitSummerQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type,$submodule_type,0,0,$stage_code);                                  
        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }
        echo json_encode($isSuccess);
    }




    /**
     * 先测报告页。
     */
    public function studyReport()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");

        $this->assign('topicId',$topicId);
        return $this->fetch("studyReport");
    }

    /**
     * 个人中心
     * @return mixed
     */
    public function reportCenter()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");

        $summer_user_service = new SummerUserService();
        $module_type = config("l1_module_type");
        $submodule_type = 1;
        $user_id = $this->getUserId();
        $user_xiance_last_exam_time = $summer_user_service->getUserL1XianceLastExamDetail("",$topicId,$module_type,$submodule_type);
        $l1_xiance_time = "";
        if(!empty($user_xiance_last_exam_time))
        {
            $l1_xiance_time = date("Y/m/d h:i:s", $user_xiance_last_exam_time['ctime']);
        }

        $submodule_type = 2;
        $user_studyModule_last_exam_time =$summer_user_service->getUserL1StudyModuleLastExamDetail("",$topicId,$module_type,$submodule_type);

        $l1_studyModule_time = "";
        if(!empty($user_studyModule_last_exam_time))
        {
            $l1_studyModule_time = date("Y/m/d h:i:s",$user_studyModule_last_exam_time['ctime']);
        }


        $xiance_module_type = config('l2_xiance_module_type');
        $bxbl_module_type = config('l2_bxbl_module_type');
        $jingsai_module_type = config('l2_jingsai_module_type');


        $algoLogic = new AlgoLogic();
        $weakElements = $algoLogic->getL2WeakElements("", $topicId);
        $knowledges_arr = $weakElements;

        $weak_num = count($knowledges_arr);

        if($weak_num == 0)
        {
            $is_show_l2_bxbl_report = false;
        }else{
            $is_show_l2_bxbl_report = true;
        }


        $user_service = new UserService();

        $xxcsStep=$user_service->getUserStep($topicId,$user_id,$xiance_module_type);//先行测试
        $l2_xiance_time = "";
        if(!empty($xxcsStep))
        {
            $l2_xiance_time = date("Y/m/d h:i:s",$xxcsStep['etime']);
        }

        $bxblStep=$user_service->getUserStep($topicId,$user_id,$bxbl_module_type);//边学边练
        $l2_bxbl_time = "";
        if(!empty($bxblStep))
        {
            $l2_bxbl_time = date("Y/m/d h:i:s",$bxblStep['etime']);
        }
        $jingsaiStep=$user_service->getUserStep($topicId,$user_id,$jingsai_module_type);//边学边练
        $l2_jingsai_time="";
        if(!empty($jingsaiStep))
        {
            $l2_jingsai_time = date("Y/m/d h:i:s",$jingsaiStep['etime']);
        }

        $l2_xiance_is_end = $xxcsStep['is_end'];
        $l2_bxbl_is_end = $bxblStep['is_end'];
        $l2_jingsai_is_end = $jingsaiStep['is_end'];
        $this->assign("l1_xiance_time",$l1_xiance_time);
        $this->assign("l1_studyModule_time",$l1_studyModule_time);
        $this->assign("l2_xiance_is_end",$l2_xiance_is_end);
        $this->assign("l2_bxbl_is_end",$l2_bxbl_is_end);
        $this->assign("l2_jingsai_is_end",$l2_jingsai_is_end);
        $this->assign("l2_xiance_time",$l2_xiance_time);
        $this->assign("l2_bxbl_time",$l2_bxbl_time);
        $this->assign("l2_jingsai_time",$l2_jingsai_time);
        $this->assign('topicId',$topicId);
        $this->assign('is_show_l2_bxbl_report',$is_show_l2_bxbl_report);
        $is_end = $summer_user_service->getUserTinyStepLog("", $topicId, $module_type, $submodule_type);
        if($is_end){
            return $this->fetch("reportCenter");
        }else{
//            $this->error('暂未得出薄弱知识点,请稍后再点击!!!');
//            $url = $_SERVER['HTTP_REFERER'];
//            $this->assign("url",$url);
            echo '<h1 style="text-align:center;">暂未得出薄弱知识点,请稍后再点击!!!</h1>';
//            echo '<script> window.setTimeout("location.href=\''.$url.'\'",3000)</script>';
        }
        
    }



    /**
     * 过渡页。
     */
    public function middlePage()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");

        $user_id = $this->getUserId();


//        $key= $user_id."_".$topicId."_".config('algo_session_code');
//        $algo_session_code = session($key);

//        echo "测试数据：不必惊慌。。。";
//        var_dump($algo_session_code);

        $this->assign('topicId',$topicId);

        $user_service = new SummerUserService();
        $middleInfo = $user_service->getMiddleSetInfo($topicId);


//        var_dump($middleInfo);
//        var_dump(session('jump_num'));
        $this->assign('middleInfo',$middleInfo);
        return $this->fetch("middlePage");
    }



    /**
     * L1学习模块的路由入口。
     */
    public function studyGate()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
        $summer_user_service =  new SummerUserService();
        //大知识图谱的掌握率
//        $scale = $summer_user_service->getXianceLearnedAlgoScale("",$topicId,$module_type);
        $summer_logic_service  = new SummerAlgoLogic();
        $summer_question_service = new SummerQuestionService();
        $submodule_type = 2;
        $next_tag_code =$summer_logic_service->get_summer_l1Study_tag_code("",$topicId,$module_type,$submodule_type);
//        $next_tag_code = "-1";
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);
        //判断每次是否达到12个知识点，到达了则要进行后测了。
        $has_learned_tag_code =  $summer_user_service->getUserHasLearnedTagCode("", $topicId, $module_type,$submodule_type,$batch_num);
        $has_learned_num = count($has_learned_tag_code);


        //每学12个知识点后，就要进去后侧流程。或者学完所有的薄弱知识点，也进去后测。
        if($has_learned_num>=12||$next_tag_code==-1)
        {
            $is_end = 1;
            $summer_user_service->insertUserTinyStep($topicId, $module_type,$submodule_type,$batch_num,  $is_end);
            $algo_constructmap_return_data = $summer_logic_service->updateUserAlgoBtStatus($topicId,$module_type,$submodule_type);

            Log::record(__METHOD__."------updateUserAlgoBtStatus----".json_encode($algo_constructmap_return_data));

            $nmap_code=$algo_constructmap_return_data['nmap_code'];
            //当算法返回nmap_code =-1 和 next_tag_code = -1 时，直接进入L2
            if($nmap_code==-1&&$next_tag_code==-1)
            {
                session($topicId.":middle_num",14);
                $user_service = new UserService();
                $SteplogService_id = $user_service->insertUserStep($topicId, $module_type, 0, 1);

                Log::record(__METHOD__."-----结束了");
                $this->redirect("Index/middlePage",["topicId"=>$topicId]);
            }else{
                session($topicId.":middle_num",13);
                //算法返回－1，则不用进入后测。
                if($nmap_code==-1 ||$nmap_code=='-1')
                {
                    Log::record(__METHOD__."-----结束了");

                    $this->redirect("Index/studyGate",["topicId"=>$topicId]);
                }else{
                    Log::record(__METHOD__."-----进入后测");
                    $this->redirect("Index/backTestIndex",["topicId"=>$topicId]);
                }
            }

        }else{
            session('tag_code',$next_tag_code);
            //根据章节掌握率跳转地址。
            $topic_v2_service = new TopicV2Service();
            $chapter_info =   $topic_v2_service->getChapterForTagCode($topicId,$next_tag_code);
            if(empty($chapter_info))
            {
                $chapter_scale =0.4;
            }else{
                $chapter_code = $chapter_info[0];
                $chapter_scale = $summer_user_service->getUserChapterScale('',$topicId, $chapter_code);
            }
            //大于40，进入巩固阶段。
            if($chapter_scale>0.4)
            {
                $this->redirect("Index/ggStudyVideo",["topicId"=>$topicId]);
            }else{
                $this->redirect("Index/basicStudyVideo",["topicId"=>$topicId]);
            }
        }
    }

    /**
     * l1 视频页面。
     * @return mixed
     */
    public function basicStudyVideo()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $tag_code = session('tag_code');
        $knowledge_v2_service =  new KnowledgeV2Service();
        $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
        if(!empty($tag_info))
        {
            $tag_video_list = $tag_info['video'];
            $tag_name = $tag_info['tag_name'];
            $video_url = "";
            foreach ($tag_video_list as $k=>$v)
            {
                if(trim($v['description'])=="基础")
                {
                    $video_url = $v['video_url'];
                }
            }
        }else{
            $video_url = "";
        }


        $this->assign("tag_name",$tag_name);
        $this->assign("video_url",$video_url);
        $this->assign('topicId',$topicId);
        return $this->fetch("basicStudyVideo");
    }



    /**
     * 基础学习题页面。
     * @return mixed
     */
    public function basicStudy()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $this->assign('topicId',$topicId);
        return $this->fetch("basicStudy");
    }


    /**
     * 基础学习取题。
     */
    public function getBaseExamQuestions()
    {

        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
        $question_service = new QuestionService();
        $user_service = new UserService();
        $summer_user_service = new SummerUserService();
        $summer_question_service = new SummerQuestionService();
//        $has_answered_questions = $user_service->getUserAnsweredQuestionsByModule("", $topicId, $module_type);
        $lastUserLog = $question_service->getLastUserExamActionLog($topicId, $module_type);

        $submodule_type = 2;
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);

        $has_answered_questions = $summer_user_service->getUserAnsweredQuestionsByModule("", $topicId, $batch_num,$module_type,$submodule_type);



        $right_num = 0;
        foreach ($has_answered_questions as $k=>$v) {
            if($v['is_right']==1)
            {
                $right_num++;
            }
        }

        $has_answered_questions_num = count($has_answered_questions);
        if($has_answered_questions_num==0)
        {
            $right_scale = 0;
        }else{
            $right_scale = $right_num/$has_answered_questions_num;

        }

        $has_learedCode_scale = $summer_user_service->getUserHasLearnedCodeScale("",$topicId,$module_type);

        if (!empty($lastUserLog) && $lastUserLog['is_submit'] != 1) {
            session('tag_code', $lastUserLog['tag_code']);
            $tag_code=session('tag_code');
            $knowledge_v2_service =  new KnowledgeV2Service();
            $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
            $tag_video_list = $tag_info['video'];
            $tag_name = $tag_info['tag_name'];
            $video_url = "";
            foreach ($tag_video_list as $k=>$v)
            {
                if(trim($v['description'])=="基础")
                {
                    $video_url = $v['video_url'];
                }
            }

            $question_v2_service = new BaseQuestionV2Service();
            $question_list=$question_v2_service->getQuestionById($lastUserLog['question_id']);


            $return_data = array(
                "is_end" => 0,
                "question_list" => $question_list,
                "has_answered_questions" => $has_answered_questions,
                "tag_code" => $lastUserLog['tag_code'],
                "tag_name"=>$tag_name,
                "video_url"=>$video_url
            );
        } else
            {
                //是否应该下一个模块，两个标准，1: 是否做完两道学习题，两道练习题。 能力值是否达标。
                $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);
                $submodule_type = 2;   //l1学习模块是2
                $grandson_module_type = 1;
                $tag_code = session('tag_code');
                $is_end = $summer_user_service->getUserBaseStudyIsLearned("",$topicId,$batch_num,$tag_code,$module_type,$submodule_type,$grandson_module_type);


                if($is_end)
                {
                    $return_data = array(
                        'has_answered_questions' => "",
                        'is_end' => 1,
                        'question_list' => "",
                        'tag_code' => "",
                        'tag_name' => ""
                    );
                }else{
                    //结束。
                    $summer_question_service = new SummerQuestionService();
                    $submodule_type = 2;  //L1学习模块的学习阶段。
                    $question_list =$summer_question_service->getSummerBaseStudyNextQuestion($topicId,$tag_code,$module_type,$submodule_type);
                    $question_id = $question_list["id"];
                    // $question_id = '030f8ba4-c52e-11e7-a91b-00163e02f9d2',97192952543052171;
//                    $question_id = "97189236473594509";
                    $question_v2_service = new BaseQuestionV2Service();
                    $question_list=$question_v2_service->getQuestionById($question_id);
                    $question_service->insertUserExamActionLog('', $topicId, $module_type, $question_id, $question_list, $tag_code);

                    $knowledge_v2_service =  new KnowledgeV2Service();
                    $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
                    $tag_name  = $tag_info['tag_name'];

                    $knowledge_v2_service =  new KnowledgeV2Service();
                    $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
                    $tag_video_list = $tag_info['video'];
                    $tag_name = $tag_info['tag_name'];
                    $video_url = "";
                    foreach ($tag_video_list as $k=>$v)
                    {
                        if(trim($v['description'])=="基础")
                        {
                            $video_url = $v['video_url'];
                        }
                    }
                    $return_data = array(
                        'has_answered_questions' => $has_answered_questions,
                        'is_end' => 0,
                        'question_list' => $question_list,
                        'tag_code' => $tag_code,
                        'tag_name' => $tag_name,
                        "video_url"=>$video_url
                    );
                }
        }
        $return_data['right_scale'] = $right_scale;
        $return_data['has_learedCode_scale'] = $has_learedCode_scale;

        echo json_encode($return_data);
    }


    /**
     * L1基础学习跳转的链接。
     */
    public function baseStudyRedirect()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
        $summer_user_service =  new SummerUserService();
        //大知识图谱的掌握率
        $scale = $summer_user_service->getXianceLearnedAlgoScale("",$topicId);
        $tag_code = session('tag_code');
        $submodule_type = 2;
        $grandson_module_type = 2;  //表示L1学习的巩固模块。
        $summer_question_service = new SummerQuestionService();
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);

        if($scale<0.6)
        {
            session($topicId.":middle_num",12);

            $this->redirect("Index/middlePage",["topicId"=>$topicId]);
        }else{
            $is_end = $summer_user_service->getUserGgStudyIsLearned("",$topicId,$batch_num,$tag_code,$module_type,$submodule_type,$grandson_module_type);
            if($is_end)
            {
                $this->redirect("Index/middlePage",["topicId"=>$topicId]);
            }else{
                $this->redirect("Index/ggStudyVideo",["topicId"=>$topicId]);
            }
        }
    }

    /**
     * L2学习跳转的链接
     */
    public function ggStudyRedirect()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
        $summer_user_service =  new SummerUserService();
        //大知识图谱的掌握率
        $scale = $summer_user_service->getXianceLearnedAlgoScale("",$topicId);
        $tag_code = session('tag_code');
        $submodule_type = 2;
        $grandson_module_type = 2;  //表示L1学习的巩固模块。

        //做完巩固直接进去过度页，过度页中可以进去学习模块的报告页。
        session($topicId.":middle_num",12);
        $this->redirect("Index/middlePage",["topicId"=>$topicId]);

    }



    /**
     * 巩固学习取题。
     */
    public function getGgExamQuestions()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
        $question_service = new QuestionService();
        $user_service = new UserService();
        $summer_user_service =  new SummerUserService();
        $summer_question_service = new SummerQuestionService();
//        $has_answered_questions = $user_service->getUserAnsweredQuestionsByModule("", $topicId, $module_type);
        $lastUserLog = $question_service->getLastUserExamActionLog($topicId, $module_type);

        $submodule_type = 2;
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);

        $has_answered_questions = $summer_user_service->getUserAnsweredQuestionsByModule("", $topicId, $batch_num,$module_type,$submodule_type);

        $right_num = 0;
        foreach ($has_answered_questions as $k=>$v) {
            if($v['is_right']==1)
            {
                $right_num++;
            }
        }

        $has_answered_questions_num = count($has_answered_questions);
        if($has_answered_questions_num==0)
        {
            $right_scale = 0;
        }else{
            $right_scale = $right_num/$has_answered_questions_num;

        }

        $has_learedCode_scale = $summer_user_service->getUserHasLearnedCodeScale("",$topicId,$module_type);

        if (!empty($lastUserLog) && $lastUserLog['is_submit'] != 1) {
            session('tag_code', $lastUserLog['tag_code']);
            $tag_code=session('tag_code');
            $knowledge_v2_service =  new KnowledgeV2Service();
            $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
            $tag_name  = $tag_info['tag_name'];
            $question_v2_service = new BaseQuestionV2Service();
            $question_list=$question_v2_service->getQuestionById($lastUserLog['question_id']);


            $knowledge_v2_service =  new KnowledgeV2Service();
            $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
            $tag_video_list = $tag_info['video'];
            $tag_name = $tag_info['tag_name'];
            $video_url = "";
            foreach ($tag_video_list as $k=>$v)
            {
                if(trim($v['description'])=="巩固")
                {
                    $video_url = $v['video_url'];
                }
            }

            $return_data = array(
                "is_end" => 0,
                "question_list" => $question_list,
                "has_answered_questions" => $has_answered_questions,
                "tag_code" => $lastUserLog['tag_code'],
                "tag_name"=>$tag_name,
                "video_url"=>$video_url
            );
        } else {
//            $tag_code = "cz003";
//            session('tag_code',$tag_code);
            //是否应该下一个模块，两个标准，1: 是否做完两道学习题，两道练习题。 能力值是否达标。
            $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);
            $submodule_type = 2;   //l1学习模块是2
            $grandson_module_type = 2;
            $tag_code = session('tag_code');
            $is_end = $summer_user_service->getUserGgStudyIsLearned("",$topicId,$batch_num,$tag_code,$module_type,$submodule_type,$grandson_module_type);


            if($is_end)
            {
                $return_data = array(
                    'has_answered_questions' => "",
                    'is_end' => 1,
                    'question_list' => "",
                    'tag_code' => "",
                    'tag_name' => ""
                );
            }else{
                //结束。
                $summer_question_service = new SummerQuestionService();
                $submodule_type = 2;  //L1学习模块的学习阶段。
                $question_list =$summer_question_service->getSummerGgStudyNextQuestion($topicId,$tag_code,$module_type,$submodule_type);
                $question_id = $question_list["id"];

                $question_v2_service = new BaseQuestionV2Service();
                $question_list=$question_v2_service->getQuestionById($question_id);
                $question_service->insertUserExamActionLog('', $topicId, $module_type, $question_id, $question_list, $tag_code);

                $knowledge_v2_service =  new KnowledgeV2Service();
                $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
                $tag_name  = $tag_info['tag_name'];


                $knowledge_v2_service =  new KnowledgeV2Service();
                $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
                $tag_video_list = $tag_info['video'];
                $tag_name = $tag_info['tag_name'];
                $video_url = "";
                foreach ($tag_video_list as $k=>$v)
                {
                    if(trim($v['description'])=="巩固")
                    {
                        $video_url = $v['video_url'];
                    }
                }

                $return_data = array(
                    'has_answered_questions' => $has_answered_questions,
                    'is_end' => 0,
                    'question_list' => $question_list,
                    'tag_code' => $tag_code,
                    'tag_name' => $tag_name,
                    'video_url'=>$video_url
                );
            }
        }
        $return_data['right_scale'] = $right_scale;
        $return_data['has_learedCode_scale'] = $has_learedCode_scale;

        echo json_encode($return_data);
    }


    /**
     * 巩固学习页面
     */
    public function ggStudy()
    {

        $request = Request::instance();
        $topicId = $request->param("topicId");

        $tag_code = session('tag_code');
        $knowledge_v2_service =  new KnowledgeV2Service();
        $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
        $tag_video_list = $tag_info['video'];
        $tag_name = $tag_info['tag_name'];
        $video_url = "";
        foreach ($tag_video_list as $k=>$v)
        {
            if(trim($v['description'])=="巩固")
            {
                $video_url = $v['video_url'];
            }
        }
        $this->assign("tag_name",$tag_name);
        $this->assign("video_url",$video_url);
        $this->assign('topicId',$topicId);

        $this->assign('topicId',$topicId);
        return $this->fetch("ggStudy");
    }





    /**
     * 巩固学习视频页面。
     */
    public function ggStudyVideo()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");


        $tag_code = session('tag_code');
        $knowledge_v2_service =  new KnowledgeV2Service();
        $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
        $video_url = "";
        if(!empty($tag_info))
        {
            $tag_video_list = $tag_info['video'];
            $tag_name = $tag_info['tag_name'];
            foreach ($tag_video_list as $k=>$v)
            {
                if(trim($v['description'])=="巩固")
                {
                    $video_url = $v['video_url'];
                }
            }
        }else{
            $video_url = "";
        }

        $this->assign("tag_name",$tag_name);
        $this->assign("video_url",$video_url);

        $this->assign('topicId',$topicId);
        return $this->fetch("ggStudyVideo");
    }



    /**
     * 高效学习重做接口。
     */
    public function redoSubmitQuestion() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $answer_content = input("answer_content/a");
        $tag_code = session('tag_code');
        $module_type = config('l1_module_type');
        $used_type = 2;   //1 表示测试题,  2 表示练习题
        $submodule_type = 1;  //只有高效学习需要试题重做。
        $question_service = new questionService();
        $isSuccess = $question_service->redoSubmitQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type, $submodule_type);
        echo json_encode($isSuccess);
    }



    public function baseSubmitQuestion()
    {
        $request = Request::instance();
        //$topicId=$this->getTopicId();
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $answer_content = input("answer_content/a");
//        session('tag_code',"c210301");
//        $tag_code = session('tag_code');
        $stage_code = config('learn_section_code');
        $module_type = config('l1_module_type');
        $tag_code = session('tag_code');
        $used_type = session('used_type');   //1 表示测试题,  2 表示练习题
        $used_type = 1;

        $question_service = new QuestionService();
        $submodule_type = 2;
        $grandson_module_type = 1;

        try {
            $isSuccess = $question_service->submitSummerQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type,$submodule_type,0,$grandson_module_type,$stage_code);
        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }
        echo json_encode($isSuccess);


    }



    public function ggSubmitQuestion()
    {

        $request = Request::instance();
        //$topicId=$this->getTopicId();
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $answer_content = input("answer_content/a");
//        session('tag_code',"c210301");
//        $tag_code = session('tag_code');
        $module_type = config('l1_module_type');
        $tag_code = session('tag_code');
//        $used_type = session('used_type');   //1 表示测试题,  2 表示练习题
        $used_type  = 1;
        $question_service = new QuestionService();
        $submodule_type = 2;
        $grandson_module_type = 2;
        try {
            $isSuccess = $question_service->submitSummerQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type,$submodule_type,0,$grandson_module_type);
        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }
        echo json_encode($isSuccess);

    }


    /**
     * 后测
     * @return mixed
     */
    public function backTestIndex()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
        //先测的入口图谱。
        $summer_user_service  = new SummerUserService();
        $summer_question_service = new SummerQuestionService();
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);
        $before_batch_num = $batch_num-1;
        $kmap_code = $summer_user_service->getUserBtKmapCode("",$topicId,$module_type,$before_batch_num);
        //如果是－1的话，直接进入L1学习模块。此环节不用再学习后测。

        Log::record(__METHOD__."------  ");
        if($kmap_code==-1)
        {
            $this->redirect("Index/studyGate",["topicId"=>$topicId]);
        }else{
            $this->assign('topicId',$topicId);
            return $this->fetch("backTestIndex");
        }


    }


    /**
     * 后测取题接口
     */
    public function getBtExamQuestions()
    {

        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
        $question_service = new QuestionService();
        $user_service = new UserService();
        $has_answered_questions = $user_service->getUserAnsweredQuestionsByModule("", $topicId, $module_type);
        $lastUserLog = $question_service->getLastUserExamActionLog($topicId, $module_type);
        $summer_user_service = new SummerUserService();
        $summer_question_service =  new SummerQuestionService();
        $user_id = $this->getUserId();
        $row = $user_service->getUserStep($topicId, $user_id, $module_type);
        $submodule_type = 1;    //后测和先测用同一个模块表示
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);
        $has_answered_questions = $summer_user_service->getUserAnsweredQuestionsByModule("", $topicId, $batch_num,$module_type,$submodule_type);

        $right_num = 0;
        foreach ($has_answered_questions as $k=>$v) {
            if($v['is_right']==1)
            {
                $right_num++;
            }
        }

        $has_answered_questions_num = count($has_answered_questions);
        if($has_answered_questions_num==0)
        {
            $right_scale = 0;
        }else{
            $right_scale = $right_num/$has_answered_questions_num;

        }

        $has_learedCode_scale = $summer_user_service->getUserHasLearnedCodeScale("",$topicId,$module_type);

        if ($row) {
            //如果找到并且已经结束
            if ($row["is_end"] == 1) {
                session($topicId.":middle_num",14);
                $returnData = array(
                    'has_answered_questions' => array(),
                    'is_end' => 1,
                    'question_list' => "",
                    'tag_code' => "",
                    'tag_name' => "",
                    'error'=>"您已做完L1"
                );
                echo json_encode($returnData);
                return;
            }else{
                $is_end =  $summer_user_service->getUserTinyStep($user_id,$topicId,$module_type,$submodule_type,$batch_num);
                session($topicId.":middle_num",11);
                if($is_end)
                {
                    $returnData = array(
                        'has_answered_questions' => array(),
                        'is_end' => 1,
                        'question_list' => "",
                        'tag_code' => "",
                        'tag_name' => "",
                        'error'=>"您已做完L1的先行测试"
                    );
                    echo json_encode($returnData);
                    return;
                }
            }
        }else{
            $is_end =  $summer_user_service->getUserTinyStep($user_id,$topicId,$module_type,$submodule_type,$batch_num);
            session($topicId.":middle_num",11);
            if($is_end)
            {
                $returnData = array(
                    'has_answered_questions' => array(),
                    'is_end' => 1,
                    'question_list' => "",
                    'tag_code' => "",
                    'tag_name' => "",
                    'error'=>"您已做完L1的先行测试"
                );
                echo json_encode($returnData);
                return;
            }
        }

        if (!empty($lastUserLog) && $lastUserLog['is_submit'] != 1) {
            session('tag_code', $lastUserLog['tag_code']);
            $tag_code=session('tag_code');
            $knowledge_v2_service =  new KnowledgeV2Service();
            $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
            $tag_name  = $tag_info['tag_name'];
            $question_v2_service = new BaseQuestionV2Service();
            $question_list=$question_v2_service->getQuestionById($lastUserLog['question_id']);
            $return_data = array(
                "is_end" => 0,
                "question_list" => $question_list,
                "has_answered_questions" => $has_answered_questions,
                "tag_code" => $lastUserLog['tag_code'],
                "tag_name"=>$tag_name
            );
        } else {
            $algo_logic  = new SummerAlgoLogic();
            $next_tag_code = $algo_logic->get_summer_backtest_tagCode($topicId,$module_type,$submodule_type);
            $module_type = config('l1_module_type');
            $used_type = 1;
            if($next_tag_code==-1)
            {
                session($topicId.":middle_num",11);
                $return_data = array(
                    'has_answered_questions' => array(),
                    'is_end' => 1,
                    'question_list' => "",
                    'tag_code' => "",
                    'tag_name' => ""
                );

                $is_end = 1;
                $summer_user_service->insertUserTinyStep($topicId, $module_type,$submodule_type,$batch_num,  $is_end);

            }else{
//            $tag_code_key = $topicId."_tag_code";
                session('tag_code',$next_tag_code);
                //结束。
                $summer_question_service = new SummerQuestionService();
                $question_service = new  QuestionService();
                $question_list =  $summer_question_service->getSummerXianceNextQuestion($topicId,$next_tag_code,$module_type,$used_type);
                //        $question_id = "589980b2f4aeb569992f0a01";//填空
//        $question_id = "58ad03fef4aeb573300b1c8f";//多个空
//            $question_id = "58be4084f4aeb556245316ec";//选择题

                $knowledge_v2_service =  new KnowledgeV2Service();
                $tag_info =$knowledge_v2_service->getKnowledgeByCode($next_tag_code);
                $tag_name  = $tag_info['tag_name'];
                $question_id = $question_list["id"];
//                $question_id = "58be4084f4aeb556245316ec";
                $question_service->insertUserExamActionLog('', $topicId, $module_type, $question_id, $question_list, $next_tag_code);
                $return_data = array(
                    'has_answered_questions' => $has_answered_questions,
                    'is_end' => 0,
                    'question_list' => $question_list,
                    'tag_code' => $next_tag_code,
                    'tag_name' => $tag_name
                );
            }
        }
        $return_data['right_scale'] = $right_scale;
        $return_data['has_learedCode_scale'] = $has_learedCode_scale;
        echo json_encode($return_data);

    }


    /**
     *后测提交
     */
    public function btSubmitQuestion()
    {
        $request = Request::instance();
        //$topicId=$this->getTopicId();
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $answer_content = input("answer_content/a");
//        session('tag_code',"c210301");
//        $tag_code = session('tag_code');
        $stage_code = config('houce_section_code');
        $module_type = config('l1_module_type');
        $tag_code = session('tag_code');
        $used_type = 1;   //1 表示测试题,  2 表示练习题
        $question_service = new QuestionService();
        $submodule_type = 1;
        Log::record("---------------btSubmitQuestion-------stage_code --$stage_code");
        try {
            $isSuccess = $question_service->submitSummerQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type,$submodule_type,0,0,$stage_code);                                  
        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }
        echo json_encode($isSuccess);

    }


    public function test()
    {
        $topicId = 9016;
        $module_type = 8;

        $algo_logic  = new SummerAlgoLogic();
//            $next_tag_code = $algo_logic->get_summer__xiance_tagCode($topicId,$module_type);
        $next_tag_code = $algo_logic->get_summer_backtest_tagCode($topicId,$module_type);

    }


}
