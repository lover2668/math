<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 17/5/31
 * Time: 22:58
 */

namespace service\services\summer;

use service\entity\User;
use service\org\util\TpString;
use service\algo\AlgoLogic;
use service\services\BaseQuestionV2Service;
use service\services\CommonService;
use service\services\QuestionService;
use service\services\TopicV2Service;
use Symfony\Component\PropertyAccess\Tests\Fixtures\TraversableArrayObject;
use think\Db;
use think\Log;
use think\Cache;
use service\log\LogService;

class SummerQuestionService  extends  CommonService
{

    public function __construct()
    {
    }


    public function getSummerXianceNextQuestion($topicId, $tag_code, $module_type, $used_type)
    {
        $question_service = new QuestionService();
        $question_v2_service  =  new  BaseQuestionV2Service();

        $xiance_module_type = config('xiance_module_type');

        //调试代码，后期删掉。
        $xiance_module_type = "";

        Log::info("---00000000000------getSummerXianceNextQuestion---------getQuestionsByKnowledge-----");
        $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $xiance_module_type, $used_type);
//        $questions_list = $question_service->getTestQuestionsList();
        Log::info("---00000000000------getSummerXianceNextQuestion---question_list--".json_encode($questions_list));
        $questions_id_arr = array();
        foreach ($questions_list as $key => $val) {
            $questions_id_arr[] = $val['id'];
        }
        //调试代码
        $hasAnsweredQuestionsId = $question_service->getUserHasAnsweredQuestions('', $topicId, $module_type,"");
        $not_answered_questionsId = array_diff($questions_id_arr, $hasAnsweredQuestionsId);
        $not_answered_questionsId_arr = array_merge($not_answered_questionsId, array());

//        $not_need_questionIds = array('57da676b14fef93f230202f2');
//        foreach ($not_answered_questionsId as $key=>$val)
//        {
//            if(in_array($val,$not_need_questionIds))
//            {
//                unset($not_answered_questionsId[$key]);
//            }
//        }

        if (empty($not_answered_questionsId_arr)) {
            $num = count($questions_id_arr);
            $return_data['error'] = "题量不够出问题了,算法已推出,并且用户已做" . $num . "道题";
            Log::info("---111111------");
            Log::info($return_data['error']);
            Log::info("---111111------");
        } else {
            //老的执行代码
//            $next_question_id = $not_answered_questionsId_arr[0];
            //新的获取试题ID方式。
            $extraQuestions = array();
            $i = 0;
            foreach ($questions_list as $key => $val) {
                if (!in_array($val['id'], $hasAnsweredQuestionsId)) {
                    $extraQuestions[$i]["id"] = $val['id'];
                    $extraQuestions[$i]["difficulty"] = $val['difficulty'];
                    $i++;
                }
            }
            if (!empty($extraQuestions)) {
                //调用算法筛题.
                $module_type = config("xiance_module_type");
                $algoLogic = new AlgoLogic();
                $question_ids = $algoLogic->chooseQuestionsByAlgo("", $tag_code, $extraQuestions, $topicId,$module_type);
                if (empty($question_ids)) {
                    exit("算法错误---------算法未返回知识点.");
                }
            } else {
                exit("知识点取不到题,有BUG");
            }
            $next_question_id = $question_ids[0];

//            $next_question_id = "583b9cc914fef966e7446563";
//            $next_question_id = "583b9dd314fef96674001801";
//            $next_question_id = "6338a8c7-50e0-11e7-8c70-00163e1004d0";
            Log::info("---00000000000------getSummerXianceNextQuestion----");
            Log::info("---next_question_id------".$next_question_id);
            Log::info("---00000000000-----getSummerXianceNextQuestion-");
            //根据ID获取试题.
            Log::record("------before-------getQuestionById");
            $return_data = $question_v2_service->getQuestionById($next_question_id);
            Log::record("------after-------getQuestionById");
            $return_data['error'] = "";
            ///////////////////////////
            $log_service = new logService();
            $msg='会员id:'.$this->getUserId().'取题时间:'. date('Y-m-d H:i:s');
            $log_service::sendMessage('info',__METHOD__."取题###试题ID为: $next_question_id -------".$msg);
            ///////////////////////////
        }
        return $return_data;

    }


    /**
     * 获取暑期L1学习阶段的基础学习题。
     * @param $topicId
     * @param $tag_code
     * @param $module_type
     * @param $submodule_type
     */
    public function getSummerBaseStudyNextQuestion($topicId,$tag_code,$module_type,$submodule_type)
    {
        $question_list =  $this->getSummerBaseQuestion($topicId,$tag_code,$module_type,$submodule_type);

        $question_service = new QuestionService();
        $summer_user_service = new SummerUserService();
        $hasAnsweredQuestionsId = $summer_user_service->getUserHasAnsweredQuestions("", $topicId, $module_type,$submodule_type,$tag_code);
        $extraQuestions = array();
        $i = 0;
        foreach ($question_list as $key => $val) {
            if (!in_array($val['id'], $hasAnsweredQuestionsId)) {
                $extraQuestions[$i]["id"] = $val['id'];
                $extraQuestions[$i]["difficulty"] = $val['difficulty'];
                $extraQuestions[$i]["used_type"] = $val['used_type'];
                $i++;
            }
        }


        session('used_type',$extraQuestions[0]['used_type']);
        $return_data = $extraQuestions[0];
        return  $return_data;
    }


    /**
     * @return array
     */
    public function getSummerBaseQuestion($topicId,$tag_code,$module_type,$submodule_type)
    {
        //先取两道学习题。
//        $question_service = new QuestionService();
//        $questions_list = $question_service->getTestQuestionsList();
        $question_v2_service = new BaseQuestionV2Service();
        $tiku_base_module_type = config('tiku_base_module_type');
        $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $tiku_base_module_type);
        return  $questions_list;

    }

    /**
     * 获取暑期L1学习阶段的基础学习题。
     * @param $topicId
     * @param $tag_code
     * @param $module_type
     * @param $submodule_type
     */
    public function getSummerGgStudyNextQuestion($topicId,$tag_code,$module_type,$submodule_type)
    {
        $question_list =  $this->getSummerGgQuestion($topicId,$tag_code,$module_type,$submodule_type);
        $question_service = new QuestionService();
        $hasAnsweredQuestionsId = $question_service->getUserHasAnsweredQuestions("", $topicId, $module_type,$tag_code);
        $extraQuestions = array();
        $i = 0;
        foreach ($question_list as $key => $val) {
            if (!in_array($val['id'], $hasAnsweredQuestionsId)) {
                $extraQuestions[$i]["id"] = $val['id'];
                $extraQuestions[$i]["difficulty"] = $val['difficulty'];
                $extraQuestions[$i]["used_type"] = $val['used_type'];
                $i++;
            }
        }
        session('used_type',$extraQuestions[0]['used_type']);
        $return_data = $extraQuestions[0];
        return  $return_data;
    }

    public function getSummerGgQuestion($topicId,$tag_code,$module_type,$submodule_type)
    {
        //先取两道学习题。
//        $question_service = new QuestionService();
//        $questions_list = $question_service->getTestQuestionsList();
        $question_v2_service = new BaseQuestionV2Service();
        $tiku_gg_module_type = config('tiku_gg_module_type');
        $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $tiku_gg_module_type);
        return  $questions_list;

    }


    /**
     *
     * 获取用户
     * @param $topicId
     * @param $module_type
     * @return int
     */
    public function getBatchNum($topicId, $module_type)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] =  $module_type;
        $user_algo_bt_status_info =  Db::name('user_algo_bt_status')->where($where)->select();
        $num = count($user_algo_bt_status_info);
        $batch_num = $num+1;
        return  $batch_num;
    }

    public function getZhlxNextQuestion($module_type, $used_type = 2, $topicId)
    {
        $userInfo = session('userInfo');
        $user_id = $this->getUserId();
        $ttq_session_key = $user_id . "zhlx_num";
        $num = session($ttq_session_key);
        $question_list_key_val = '';
        $question_list = array();
//        $question_list = $this->getZhlxQuestionIds($topicId);//获取当前知识点下有没有做错的 如果有就是继续做
        $topic_v2_service = new  TopicV2Service();
        $zhlx_kmap_code_list = $topic_v2_service->getZhlxKmapCodeList($topicId);
        $zonghe_module_type = config('zonghe_module_type');
        $question_v2_service = new BaseQuestionV2Service();
        foreach ($zhlx_kmap_code_list as $k=>$v) {
            $question_list[] =  $question_v2_service->getQuestionsByKnowledge($v['tag_code'],$zonghe_module_type);
        }



        if ($question_list == false) $question_list = [];
        /******************埋点取得知识点第一个题id*********************/
        foreach ($question_list as $k => $v) {
            $first_user_exam_detail = Db::name("user_exam_detail")->where(['topicId' => $topicId, 'module_type' => $module_type, 'used_type' => $used_type, 'question_id' => $v[0]['id'], 'user_id' => $user_id])->field('right_answer_base64,user_answer_base64', true)->select();
            if (count($first_user_exam_detail) == 1 && $first_user_exam_detail[0]['is_right']) {
                unset($question_list[$k]);//删除组第一题答对的
            }
        }
        /******************埋点取得知识点第一个题id end*********************/
        $question_list_key_val = arr_foreachzh($question_list);//把二维数据换成一维
        $question_list = array_keys($question_list_key_val);
        $question_service = new QuestionService();
        $getUserHasAnsweredQuestions = $question_service->getUserHasAnsweredQuestions($user_id, $topicId, $module_type);//获取已经做过的题目
        //拿到用户做过的题的 问题id
        /*******************埋点循环比对判断当前答题列表的知识点和题id等于第一个知识并且做对的情况下清空同知识点下的所有试题id************************/
        /*******************埋点循环比对判断当前答题列表的知识点和题id等于第一个知识并且做对的情况下清空同知识点下的所有试题id end******************/
        $question_list = array_values(array_diff($question_list, $getUserHasAnsweredQuestions));
        $tag_code = '';
        $question_id = '';
        if (isset($question_list[0]) && isset($question_list_key_val[$question_list[0]])) {
            $tag_code = $question_list_key_val[$question_list[0]];
            $question_id = $question_list[0];
//            $question_id = "590a9915f4aeb57462445312";
            session('tag_code', $tag_code);
            ///////////////////////////
            $log_service = new logService();
            $msg='会员id:'.$this->getUserId().'取题时间:'. date('Y-m-d H:i:s');
            $log_service::sendMessage('info',__METHOD__."取题###试题ID为: $question_id -------".$msg);
            ///////////////////////////
        }
//        $question_id='58be4084f4aeb55624531878';
        return ['tag_code' => $tag_code, 'question_id' => $question_id];



    }


    /**
     *
     * 获取用户
     * @param $topicId
     * @param $module_type
     * @return int
     */
    public function getLastBatchNum($topicId, $module_type,$user_id=null)
    {
        if(!$user_id){
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] =  $module_type;
        $user_algo_bt_status_info =  Db::name('user_algo_bt_status')->where($where)->select(); 
        $num = 0;
        foreach ($user_algo_bt_status_info as $key => $value) {
            if($value["kmap_code"] != -1){
                $num++;
            }
        }
        return  $num;
    }
    /**
     *
     * 获取用户
     * @param $topicId
     * @param $module_type
     * @return int
     */
    public function checkBatchNum($user_id=null ,$topicId, $module_type,$is_end =null)
    {
        if(!$user_id){
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] =  $module_type;
        if($is_end !==null){
             $where['is_end'] = $is_end;
        }
        $step_log =  Db::name('user_exam_step_log')->where($where)->count();     
        if($step_log==0){
            $batch_num = $this->getBatchNum($topicId, $module_type);
        }else{
            $batch_num = $this->getLastBatchNum($topicId, $module_type, $user_id);
        }
        return  $batch_num;
    }
    
    



}