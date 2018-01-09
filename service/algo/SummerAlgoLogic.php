<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/8/18
 * Time: 下午6:12
 */
namespace service\algo;


use service\services\summer\SummerQuestionService;
use service\services\UserService;
use think\Log;
use service\log\LogService;

use service\services\summer\SummerUserService;
use service\services\KnowledgeV2Service;
use service\services\TopicV2Service;
use service\services\summer\SummerCindexService;
use think\Db;

class SummerAlgoLogic
{
    private $algoStorage;

    public function __construct()
    {
        $this->algoStorage = new SummerAlgoStorage();
        $this->algoService = new AlgoV2Service();
    }


    public function setStorageDiver(SummerAlgoStorage $algoStorage)
    {

        $this->algoStorage = $algoStorage;

    }


    public function getUserId()
    {

        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
        return $user_id;
    }




    /**
     * 获取session_id
     * @param $user_id
     * @param $kmap_code
     * @param $curriculum_id
     * @param $sys_code
     * @param $level_mode
     * @param $init_kstatus
     * @param $learn_times
     * @param $total_level
     * @return string
     */
    public function get_sessionId($user_id="",$topicId,$kmap_code,$curriculum_id,$sys_code,$level_mode,$init_kstatus,$learn_times,$total_level)
    {
        if(!$user_id)
        {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $user_algo_session = Db::name('user_algo_sessionid')->where($where)->find();
        log::record(__METHOD__." -----summer--algo_logic----get_sessionId----start--------111111-");
        if(empty($user_algo_session))
        {
            log::record(__METHOD__." -----summer--algo_logic----get_sessionId----start----2222-----");
            //发送给日志的额外数据。
            $log_option = array(
                "user_id"=>$user_id,//用户id
                "topicId"=>$topicId,//专题id
                "module_type"=>"",//模块id
                "kmap_code"=>$kmap_code,//知识图谱
            );
            log::record(__METHOD__." -----summer--algo_logic----get_sessionId----start--------33333--");

            $session_id=$this->algoService->call_algo_getSessionId($user_id,$kmap_code,$curriculum_id,$sys_code,$level_mode,$init_kstatus,$learn_times,$total_level,$log_option);
            log::record(__METHOD__." -----summer--algo_logic----get_sessionId----start----444-------");
            if($session_id)
            {
                log::record(__METHOD__." -----summer--algo_logic----有SESSION_ID-----");
                $data['user_id'] = $user_id;
                $data['topicId'] = $topicId;
                $data['algo_session_id'] = $session_id;
                $data['ctime']= time();
                Db::name('user_algo_sessionid')->insert($data);
            }else{
                log::record(__METHOD__." -----summer--algo_logic----算法返回SESSION_ID为空-----");
                //如果为空则不纪录。
            }
        }else{
            log::record(__METHOD__." -----summer--algo_logic---－－－－555555-----");

            $session_id = $user_algo_session['algo_session_id'];
        }

        return $session_id;
    }


    /**
     * @param $topicId
     * @param $module_type
     * @return mixed
     */
    public function get_summer__xiance_tagCode($topicId,$module_type,$submodule_type)
    {
        $session_id = $this->getSessionId($topicId);
        $user_id = $this->getUserId();

        //获取L1的大图谱。
        $topic_v2_service =  new TopicV2Service();
        $topic_info= $topic_v2_service->getTopicByTopicId($topicId);
        $kmap_enter_key = $topic_info['kmap_enter_key'];
        $kmap_code_list = $topic_info['kmap_code_list'];
        $kmap_enter_code_info = $kmap_code_list[$kmap_enter_key];
        //先测的入口图谱。
        $kmap_code = $kmap_enter_code_info['kmap_code'];

        $user_service = new UserService();
        $summer_question_service = new SummerQuestionService();
        $summer_user_service = new SummerUserService();
        $user_last_answer_info  = $summer_user_service->getUserLastExamInfo("",$topicId,$module_type);
        $is_right = $user_last_answer_info['is_right'];

        //发送给日志的额外数据。
        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );

        $return_data =$this->algoService->call_algo_revassess($session_id,$kmap_code,$is_right,$log_option);

        Log::record("------算法返回值-------".json_encode($return_data));

        if($return_data['next_node']== -1)
        {
            //等于－1时候，记录下来算法返回的值。
            $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);
            Log::record("------算法返回－1的时候的算法返回值-------".json_encode($return_data));
            $this->algoStorage->updateUserAlgoStatus($user_id,$topicId,$module_type,$submodule_type,$batch_num,$return_data);
        }

        $tag_code = $return_data['next_node'];
        return  $tag_code;

    }


    public function testData($topicId,$module_type)
    {
        $user_id = $this->getUserId();
        $example_data = '{"code":"0","measure_code":"xalgo_mr_s7_01_all_2190_H61NTH","measure_nodes":["c010101","c010201","c010203","c010501","c020102","c020103","c020105","c020201","c020202","c020204","c020205","c020206","c040101","c040201","c040202","c040203","c040204","c040205","c040301","c040302","c040303","c040304","c040401","c040402","c040403","c040404","c040501","c040503","c050101","c050102","c050103","c050104","c050201","c050202","c050203","c050204","c050205","c050206","c050207","c060203","c100101","c100102","c100105","c100201","c100202","c100303","c100304","c100308","c110101","c110102","c110103","c110104","c110201","c110202","c110203","c110204","c110205","c110206","c110301","c110302","c110303","c110304","c110305","c110306","c110401","c110402","c120101","c120102","c120103","c120104","c120105","c120201","c120202","c120203","c120301","c120302","c120303","c120304","c120305","c120401","c120402","c120403","c120404","c120405","c120406","c120407","c120408","c120409","c120410","c120411","c150101","c150102","c150103","c150104","c150105","c150201","c150202","c150203","c150204","c150205","c150206","c150207","c190101","c190102","c190103","c190104","c200308","c210101","c210102","c210103","c210104","c210105","c210106","c210107","c210108","c210109","c210110","c210111","c210201","c210202","c210203","c210204","c210205","c210206","c210207","c210208","c210301","c210302","c210303","c210304","c210305","c210306","c210401","c210402","c320101","c320102","c320103","c320104","c320105","c320201","c320202","c320203","c320204","c320205","c320301","c320302","c320303","c320304","c320305","c320306"],"next_node":"-1","nlearn_weaks":["c210301","c210302","c040501","c040503","c210303","c210304","c040101","c210306","c320104","c320103","c320102","c320101","c100201","c110402","c110401","c100202","c320105","c120203","c120202","c120201","c210201","c210202","c210203","c210208","c040203","c040204","c040205","c210204","c210205","c210206","c040201","c210207","c040202","c320203","c320202","c320201","c020204","c020205","c020206","c110104","c110103","c150206","c100304","c110102","c150205","c100303","c110101","c150204","c150203","c150202","c100308","c150201","c320205","c320204","c120305","c120302","c120301","c120303","c150207","c210101","c210102","c210103","c210104","c210109","c040303","c040304","c210105","c210106","c210107","c210108","c120411","c050101","c050102","c120410","c050103","c320302","c320301","c110206","c110205","c110204","c110203","c110202","c110201","c150105","c150104","c120409","c150103","c320306","c120408","c150102","c320305","c150101","c320304","c320303","c060203","c120405","c120404","c120407","c120406","c120401","c120403","c120402","c200308","c050206","c210401","c040401","c040402","c040403","c040404","c210402","c050201","c050204","c050205","c050202","c050203","c110306","c110305","c100102","c110304","c100101","c110303","c210110","c110302","c210111","c110301","c100105","c190101","c190102","c190103","c120104","c120103","c120105","c120102","c120101"],"orign_weaks":["c210301","c210302","c040501","c040503","c210303","c210304","c040101","c210305","c210306","c320104","c320103","c320102","c320101","c100201","c110402","c110401","c100202","c320105","c120203","c120202","c120201","c210201","c210202","c210203","c210208","c040203","c040204","c040205","c210204","c210205","c210206","c040201","c210207","c040202","c320203","c320202","c320201","c020204","c020205","c020206","c110104","c110103","c150206","c100304","c110102","c150205","c100303","c110101","c150204","c150203","c150202","c100308","c150201","c320205","c320204","c120305","c120302","c120301","c120304","c120303","c150207","c210101","c210102","c210103","c210104","c210109","c040303","c040304","c210105","c210106","c210107","c210108","c120411","c050101","c050102","c120410","c050103","c320302","c320301","c110206","c110205","c110204","c110203","c110202","c110201","c150105","c150104","c120409","c150103","c320306","c120408","c150102","c320305","c150101","c320304","c320303","c060203","c120405","c120404","c120407","c120406","c120401","c120403","c120402","c200308","c050206","c050207","c210401","c040401","c040402","c040403","c040404","c210402","c050201","c050204","c050205","c050202","c050203","c110306","c110305","c100102","c110304","c100101","c110303","c210110","c110302","c210111","c110301","c100105","c190101","c190102","c190103","c190104","c120104","c120103","c120105","c120102","c120101"],"session_id":"3398104297704448"}';
        $return_data2 =json_decode($example_data);

        $submodule_type =1;
        $return_data = array(
            'code'=>$return_data2->code,
            'measure_code'=>$return_data2->measure_code,
            'measure_nodes'=>$return_data2->measure_nodes,
            'next_node'=>$return_data2->next_node,
            'nlearn_weaks'=>$return_data2->nlearn_weaks,
            'orign_weaks'=>$return_data2->orign_weaks,
            'session_id'=>$return_data2->session_id
        );
        $summer_question_service = new SummerQuestionService();
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);

        //等于－1时候，记录下来算法返回的值。
        Log::record("------算法返回－1的时候的算法返回值-------".json_encode($return_data));
        $this->algoStorage->updateUserAlgoStatus($user_id,$topicId,$module_type,$submodule_type,$batch_num,$return_data);
    }


    /**
     *获取算法的SESSION_ID
     */
    private function getSessionId($topicId)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $user_algo_session = Db::name('user_algo_sessionid')->where($where)->find();
        $session_id = $user_algo_session['algo_session_id'];
        return  $session_id;
    }

    /**
     * @param $user_id
     * @param $tag_code
     * @param $questions    array( questions=>{{diff:1,answer:0},{diff:3,answer:1}} )
     * @param $is_right
     * @param $used_type
     * @param $topicId
     * @param $module_type
     * @return mixed
     */
    public function updateAbility($user_id, $tag_code, $questions, $used_type, $topicId, $module_type,$submodule_type,$grandson_module_type,$stage_code)
    {
        if(empty($user_id))
        {
            $user_id = $this->getUserId();
        }
        $session_id = $this->getSessionId($topicId);
//        $stage_code = 1011;
        $node_code = $tag_code;
        $used_type = $used_type;

        //获取L1的大图谱。
        $topic_v2_service =  new TopicV2Service();
        $topic_info= $topic_v2_service->getTopicByTopicId($topicId);
//
//        $kmap_enter_key = $topic_info['kmap_enter_key'];
//        $kmap_code_list = $topic_info['kmap_code_list'];
//        $kmap_enter_code_info = $kmap_code_list[$kmap_enter_key];
//        $big_map_code = $kmap_code_list[200]['kmap_code'];
//
//        //先测的入口图谱。
//        $kmap_code = $kmap_enter_code_info['kmap_code'];

        $kmap_code = $topic_v2_service->getKmapCodeAll($topicId);

        //发送给日志的额外数据。
        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );
Log::record("=-----------stage_code-------$stage_code--------------");
        $algo_abilityx_return_data = $this->algoService->call_algo_ability($session_id,$kmap_code,$stage_code,$node_code,$questions,$used_type,$log_option);

        Log::record("-----3333333333333-------call_algo_ability-算法返回能力值为".json_encode($algo_abilityx_return_data));

        $this->algoStorage->updateAbility($user_id, $topicId, $module_type,$submodule_type,$grandson_module_type, $tag_code, $algo_abilityx_return_data);


        return $algo_abilityx_return_data;
    }


    /**
     * 获取L1学习阶段的知识点。
     */
    public function get_summer_l1Study_tag_code($user_id,$topicId,$module_type,$submodule_type)
    {
        if(empty($user_id))
        {
            $user_id = $this->getUserId();
        }
        $session_id = $this->getSessionId($topicId);

        $summer_user_service = new  SummerUserService();
        //获取算法返回的先测图谱。
        $xianceHasLearnedInfo = $summer_user_service->getXianceLearnedAlgoInfo($user_id,$topicId,$module_type);
        $map_code = $xianceHasLearnedInfo['measure_code'];

        //获取L1的大图谱。
        $topic_v2_service =  new TopicV2Service();
        $topic_info= $topic_v2_service->getTopicByTopicId($topicId);
        $kmap_enter_key = $topic_info['kmap_enter_key'];
        $kmap_code_list = $topic_info['kmap_code_list'];
        $kmap_enter_code_info = $kmap_code_list[$kmap_enter_key];
        $big_map_code = $kmap_code_list[200]['kmap_code'];

        //先测的入口图谱。
        $kmap_code = $kmap_enter_code_info['kmap_code'];

        $kmap_enter_key = $topic_info['kmap_enter_key'];
        $kmap_enter_code_info = $kmap_code_list[$kmap_enter_key];
        $kmap_enter_code = $kmap_enter_code_info['kmap_code'];
        //获取大指示图谱知识点。
        $module_type = config('l1_module_type');
        $sub_module_type = 1;
        $summer_question_service = new SummerQuestionService();
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);

        //获取薄弱知识点。
        $xiance_submodule_type = 1;
        $weak_elements = $this->algoStorage->getWeakElements($user_id, $topicId,$module_type,$xiance_submodule_type,$batch_num);


        //每次图谱变化都是算法返回的最后的一次图谱。
        $first_batch_num = 1;  //整个过程都是返回第一次算法返回的知识点范围。
        $user_algo_status= $this->algoStorage->getUserAlgoStatus($user_id, $topicId, $module_type,$first_batch_num);

        $elements_codes_arr =json_decode($user_algo_status['measure_nodes']);
        $elements_abilities = array();
        foreach ($elements_codes_arr as $tag_code) {
            $num = $summer_user_service->getUserHasAnsweredNumForTagCode($user_id, $topicId, $module_type, $tag_code,$submodule_type);
            $user_ability = $this->algoStorage->getUserAbility($user_id, $topicId, $module_type, $tag_code);

            $elements_abilitie = array(
                'code'=>$tag_code,
                'ability'=>$user_ability,
                'learn_times'=>$num
            );
            $elements_abilities[] = $elements_abilitie;
        }

        $summer_user_service = new SummerUserService();

        $summer_question_service = new SummerQuestionService();
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);

        $learned_elements_arr = $summer_user_service->getUserHasLearnedTagCode("", $topicId, $module_type,$submodule_type,$batch_num);
        $learned_elements = array();
        foreach ($learned_elements_arr as $k=>$v) {
            $learned_elements[] = $v['tag_code'];
        }
        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );
       $algo_info  =  $this->algoService->call_algo_learnrecomp($session_id,$map_code,$elements_abilities,json_decode($weak_elements),$learned_elements,$log_option);
       $next_code = $algo_info['next_node'];
       return $next_code;
    }


    /**
     * 获取L1的薄弱知识点。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $sub_module_type
     * @return mixed
     */
    public function getWeakElements($user_id, $topicId,$module_type,$sub_module_type,$batch_num)
    {
        $weak_elements = $this->algoStorage->getWeakElements($user_id, $topicId,$module_type,$sub_module_type,$batch_num);
        return json_decode($weak_elements);
    }
    /**
     * 获取L1的原始薄弱知识点。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $sub_module_type
     * @return mixed
     */
    public function getOrignWeakElements($user_id, $topicId,$module_type,$sub_module_type,$batch_num)
    {
        $weak_elements = $this->algoStorage->getOrignWeakElements($user_id, $topicId,$module_type,$sub_module_type,1);
        return json_decode($weak_elements);
    }



    /**
     * @param $topicId
     * @param $module_type
     * @return mixed get_summer__xiance_tagCode
     */
    public function get_summer_backtest_tagCode($topicId,$module_type,$submodule_type)
    {
        $session_id = $this->getSessionId($topicId);
        $user_id = $this->getUserId();
        $topic_v2_service =  new TopicV2Service();
        //先测的入口图谱。
        $summer_user_service  = new SummerUserService();
        $summer_question_service = new SummerQuestionService();
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);
        $before_batch_num = $batch_num-1;
        $kmap_code = $summer_user_service->getUserBtKmapCode("",$topicId,$module_type,$before_batch_num);

        $user_service = new UserService();
//        $user_last_answer_info  = $user_service->getUserLastExamInfo("",$topicId,$module_type);
        $user_last_answer_info  = $summer_user_service->getUserLastExamInfo("",$topicId,$module_type);

        $is_right = $user_last_answer_info['is_right'];

        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );

//        $return_data =$this->algoService->call_algo_revassess($session_id,$kmap_code,$is_right,$log_option);
        $return_data =$this->algoService->call_algo_learnassess($session_id,$kmap_code,$is_right,$log_option);


        if($return_data['next_node']== -1)
        {
//            $example_data = '{"code":"0","measure_code":"xalgo_mr_s7_01_all_2190_H61NTH","measure_nodes":["c010101","c010201","c010203","c010501","c020102","c020103","c020105","c020201","c020202","c020204","c020205","c020206","c040101","c040201","c040202","c040203","c040204","c040205","c040301","c040302","c040303","c040304","c040401","c040402","c040403","c040404","c040501","c040503","c050101","c050102","c050103","c050104","c050201","c050202","c050203","c050204","c050205","c050206","c050207","c060203","c100101","c100102","c100105","c100201","c100202","c100303","c100304","c100308","c110101","c110102","c110103","c110104","c110201","c110202","c110203","c110204","c110205","c110206","c110301","c110302","c110303","c110304","c110305","c110306","c110401","c110402","c120101","c120102","c120103","c120104","c120105","c120201","c120202","c120203","c120301","c120302","c120303","c120304","c120305","c120401","c120402","c120403","c120404","c120405","c120406","c120407","c120408","c120409","c120410","c120411","c150101","c150102","c150103","c150104","c150105","c150201","c150202","c150203","c150204","c150205","c150206","c150207","c190101","c190102","c190103","c190104","c200308","c210101","c210102","c210103","c210104","c210105","c210106","c210107","c210108","c210109","c210110","c210111","c210201","c210202","c210203","c210204","c210205","c210206","c210207","c210208","c210301","c210302","c210303","c210304","c210305","c210306","c210401","c210402","c320101","c320102","c320103","c320104","c320105","c320201","c320202","c320203","c320204","c320205","c320301","c320302","c320303","c320304","c320305","c320306"],"next_node":"-1","nlearn_weaks":["c210301","c210302","c040501","c040503","c210303","c210304","c040101","c210306","c320104","c320103","c320102","c320101","c100201","c110402","c110401","c100202","c320105","c120203","c120202","c120201","c210201","c210202","c210203","c210208","c040203","c040204","c040205","c210204","c210205","c210206","c040201","c210207","c040202","c320203","c320202","c320201","c020204","c020205","c020206","c110104","c110103","c150206","c100304","c110102","c150205","c100303","c110101","c150204","c150203","c150202","c100308","c150201","c320205","c320204","c120305","c120302","c120301","c120303","c150207","c210101","c210102","c210103","c210104","c210109","c040303","c040304","c210105","c210106","c210107","c210108","c120411","c050101","c050102","c120410","c050103","c320302","c320301","c110206","c110205","c110204","c110203","c110202","c110201","c150105","c150104","c120409","c150103","c320306","c120408","c150102","c320305","c150101","c320304","c320303","c060203","c120405","c120404","c120407","c120406","c120401","c120403","c120402","c200308","c050206","c210401","c040401","c040402","c040403","c040404","c210402","c050201","c050204","c050205","c050202","c050203","c110306","c110305","c100102","c110304","c100101","c110303","c210110","c110302","c210111","c110301","c100105","c190101","c190102","c190103","c120104","c120103","c120105","c120102","c120101"],"orign_weaks":["c210301","c210302","c040501","c040503","c210303","c210304","c040101","c210305","c210306","c320104","c320103","c320102","c320101","c100201","c110402","c110401","c100202","c320105","c120203","c120202","c120201","c210201","c210202","c210203","c210208","c040203","c040204","c040205","c210204","c210205","c210206","c040201","c210207","c040202","c320203","c320202","c320201","c020204","c020205","c020206","c110104","c110103","c150206","c100304","c110102","c150205","c100303","c110101","c150204","c150203","c150202","c100308","c150201","c320205","c320204","c120305","c120302","c120301","c120304","c120303","c150207","c210101","c210102","c210103","c210104","c210109","c040303","c040304","c210105","c210106","c210107","c210108","c120411","c050101","c050102","c120410","c050103","c320302","c320301","c110206","c110205","c110204","c110203","c110202","c110201","c150105","c150104","c120409","c150103","c320306","c120408","c150102","c320305","c150101","c320304","c320303","c060203","c120405","c120404","c120407","c120406","c120401","c120403","c120402","c200308","c050206","c050207","c210401","c040401","c040402","c040403","c040404","c210402","c050201","c050204","c050205","c050202","c050203","c110306","c110305","c100102","c110304","c100101","c110303","c210110","c110302","c210111","c110301","c100105","c190101","c190102","c190103","c190104","c120104","c120103","c120105","c120102","c120101"],"session_id":"3398104297704448"}';
//            $return_data =json_decode($example_data);
            //等于－1时候，记录下来算法返回的值。
            Log::record("------算法返回－1的时候的算法返回值-------".json_encode($return_data));
//            $batch_num =2;
            $this->algoStorage->updateUserAlgoStatusForBt($user_id,$topicId,$module_type,$submodule_type,$batch_num,$return_data);
        }


        $tag_code = $return_data['next_node'];
        return  $tag_code;

    }


    /**
     * 做完L1学习模块后生成新的
     * @param $topicId
     * @param $module_type
     * @param $submodule_type
     */
    public function updateUserAlgoBtStatus($topicId,$module_type,$submodule_type)
    {
        $session_id = $this->getSessionId($topicId);
        $user_id = $this->getUserId();
        $topic_v2_service =  new TopicV2Service();
        //先测的入口图谱。
        $kmap_code = $topic_v2_service->getMainKmapCode($topicId);

        $summer_user_service = new  SummerUserService();
        //获取算法返回的先测图谱。
        $xianceHasLearnedInfo = $summer_user_service->getXianceLearnedAlgoInfo($user_id,$topicId,$module_type);
        $map_code = $xianceHasLearnedInfo['measure_code'];

        //获取用户已经掌握的知识点。
        $summer_user_service = new SummerUserService();
        $nodes = $summer_user_service->getUserMasteryTagCode("", $topicId, $module_type,$submodule_type);

        //已经掌握的知识点，并且已经由算法生成过新图谱的，不需要再给算法传了，所以需要记录下来。
        $has_masteryed_code=  $summer_user_service->getUserHasMasteryTagCode("",$topicId);

        $summer_question_service = new SummerQuestionService();
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);

        $nodes_old = array_diff($nodes,$has_masteryed_code);
        $nodes = array_merge($nodes_old,array());
        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );

        $return_data =$this->algoService->call_algo_constructmap($session_id,$map_code,"POSTREQ",true,false,$nodes,false,$log_option);

        //已经掌握的知识点，并且已经由算法生成过新图谱的，不需要再给算法传了，所以需要记录下来。
        $summer_user_service->updateUserHasMasteryTagCode("",$topicId,$nodes);

        Log::record("------算法返回值-------".json_encode($return_data));
        $this->algoStorage->updateUserAlgoBtStatus($user_id,$topicId,$module_type,$batch_num,$return_data);
        return $return_data;
    }




    /**
     * 因为取专题接口方式变了。
     * @api {api} ---------- 能力估计算法
     * @apiVersion 0.0.1
     * @apiName  updateAbility   更新能力值
     * @apiGroup Algo/algoLogic
     * @apiParam {String} difficulty   学生做过的题目的难度 .
     * @apiParam {String} score   学生做过的题目对应的得分.
     * @apiParam {String} type  能力估计类型 .1 : 测试题  2: 练习题.
     * @apiSuccess {String} ability   能力值.
     * @apiSuccess {String} likelihood   该学生目前的能力值估计对应的最大似然函数概率值(99个).
     * @apiSuccess {String} abilityprob   整体评估能力可能性值.
     * @apiSuccess {String} error   错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *
     *     }
     */
    public function updateAbilityForSpring($user_id, $tag_code, $diffculty, $is_right, $used_type, $topicId, $module_type)
    {
        if(empty($user_id))
        {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
        }

        //获取用户的likelihood值.
        $user_ability_info = $this->algoStorage->getUserAbilityInfoByTagCode($user_id, $topicId, $module_type, $tag_code);
        if (!empty($user_ability_info)) {
            $likelihood = json_decode($user_ability_info['likelihood']);  //该学生目前的能力估计对应的最大似然函数概率值.
        } else {
            $likelihood = array();   //该学生目前的能力估计对应的最大似然函数概率值.
        }
        //从数据库获取,用户上次的这个值.
//        $likelihood  = 0;
        $algo_service = new AlgoService();
        $request_data['difficulty'] = array($diffculty);
        $request_data['score']      = array($is_right);
        $request_data['likelihood'] = $likelihood;
        $request_data['type']       = array($used_type);

        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>"",//知识图谱
        );

        $algo_abilityx_return_data = $algo_service->call_algo_abilityx($diffculty, $is_right, $likelihood, $used_type,$log_option);
        $summer_cindex_service = new SummerCindexService();
        $this->algoStorage->updateAbilityForSpring($user_id, $topicId, $module_type, $tag_code, $algo_abilityx_return_data);
        return $algo_abilityx_return_data;
    }


}