<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/8/18
 * Time: 下午6:12
 */
namespace service\algo;


use app\index\controller\User;
use service\org\util\TpString;
use service\services\ApiGateService;
use service\services\BaseQuestionV2Service;
use service\services\UserService;
use service\services\QuestionService;
use service\services\KnowledgeService;
use service\services\TopicService;
use think\Cache;
use think\Db;
use think\Log;
use service\log\LogService;
use service\log\Test;
use service\services\TopicV2Service;

class AlgoLogic
{
    private $algoStorage;

    public function __construct()
    {
        $this->algoStorage = new AlgoStorage();

    }


    public function setStorageDiver(AlgoStorage $algoStorage)
    {

        $this->algoStorage = $algoStorage;

    }


    /**
     * @api {api} ------  知识图谱计算法接口
     * @apiVersion 0.0.1
     * @apiName  get_xiance_tag_code  对接算法知识图谱计算法接口,先行测试调用
     * @apiGroup Algo/algoLogic
     * @apiParam {String} init_kstatus  学生所选自评水平.
     * @apiParam {String} kmap_code  知识图谱编码.
     * @apiParam {String} pre_knode  前一个知识点编码.
     * @apiParam {String} usr_ans  学生测试问题的答案编号.  有三种情况: 第一次的用户还没做题,直接取题的时候,传 ""  (即空), 已做题的话,传 "0"或"1" .
     * @apiParam {Number} level_mode  知识点难度级别
     * @apiSuccess {Number} usr_id   用户ID.
     * @apiSuccess {String} init_kstatus   用户对应的专题掌握的程度.
     * @apiSuccess {String} kmap_code   知识图谱编号.
     * @apiSuccess {Number} knode_toaskq 所得知识点
     * @apiSuccess {Number}  weak_elems  薄弱知识点列表.
     * @apiSuccess {Number}  sErrors    错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *
     *     }
     */
    public function get_xiance_tagCode_version01($topicId, $init_kstatus, $kmap_code, $level_mode = 1, $last_answer_is_right = 0)
    {
        $userInfo = session('userInfo');
        $usr_id = $userInfo['user_id'];
        $module_type = config('xiance_module_type');
        Log::record("------before-------get_xiance_tagCode");

        $user_service = new UserService();
//        $user_last_exam_info =  $user_service->getUserLastExamInfo('',$topicId,$module_type);
//        var_dump($user_last_exam_info);
//        exit;


        $user_ability_status = $this->algoStorage->getUserAbilityStatus($usr_id, $topicId, $module_type);
        Log::record("------after-------getUserAbilityStatus");
        //为空的情况下,表示用户第一次做.
        if (empty($user_ability_status)) {
            $pre_knode = "";
            $usr_ans = 0;
        } else {
            if ($last_answer_is_right === null) {
                //打错误日志,此处应该执行不到的.
                Log::record('---err:---last_answer_is_right-----====null');
            }
            $pre_knode = $user_ability_status['last_tag_code'];
            $usr_ans = $last_answer_is_right;
        }


        $algo_service = new AlgoService();
        Log::record("------before-------call_algo_kstime");
        $startTime=microtime(true);//开始时间

        $user_last_exam_info =  $user_service->getUserLastExamInfo('',$topicId,$module_type);
        if(!empty($user_last_exam_info))
        {
            $need_time =  $user_last_exam_info['question_list']['estimates_time'];
            $take_time = $user_last_exam_info['ctime'] - $user_last_exam_info['stime'];
        }else{
            $need_time =  0;
            $take_time = 0;
        }
        $request_data['init_kstatus'] = $init_kstatus;
        $request_data['kmap_code'] = $kmap_code;
        $request_data['pre_knode'] = $pre_knode;
        $request_data['usr_ans'] = $usr_ans;
        $request_data['level_mode'] = $level_mode;
        $request_data['need_time'] = $need_time;

        $sys_code = config('sys_code');

        //发送给日志的额外数据。
        $log_option = array(
            "user_id"=>$usr_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );


//        $response_data = $algo_service->call_algo_kstmode($usr_id, $init_kstatus, $kmap_code, $pre_knode, $usr_ans, $level_mode);
        $response_data = $algo_service->call_algo_kstime($usr_id, $init_kstatus, $kmap_code, $pre_knode, $usr_ans, $level_mode ,$need_time,$take_time,$sys_code,$log_option);
        if(empty($pre_knode))
        {
            $pre_knode = "";
        }

//        Log::record("------before-------saveXianceLog");
//        $this->algoStorage->saveXianceLog($topicId, $module_type, $kmap_code, $request_data, $response_data);
//        Log::record("------after-------saveXianceLog");
//        Log::record("------before-------saveAlgoGetTagCodeLog");

//        if(!empty($response_data))
//        {
//            $this->algoStorage->saveAlgoGetTagCodeLog($usr_id, $topicId, $pre_knode, $module_type, $response_data);
//        }

//        Log::record("------after-------saveAlgoGetTagCodeLog");
        /**日志记录*/
//        Log::record("------after-------call_algo_kstime");
        if (!$response_data) {
            $return_data = array(
                'tag_code' => $response_data['knode_toaskq'],
                'error' => "算法返回有问题"
            );

        } else {

            $weak_elems = $response_data['weak_elems'];
            $last_tag_code = $response_data['knode_toaskq'];
            Log::record("------before-------updateUserAbilityStatus");


            $this->algoStorage->updateUserAbilityStatus($usr_id, $topicId, $weak_elems, $module_type, $last_tag_code, $usr_ans);
            Log::record("------after-------updateUserAbilityStatus");
            $return_data = array(
                'tag_code' => $response_data['knode_toaskq'],
                'error' => $response_data['error']
            );
        }
        return $return_data;
    }


    /**
     * @api {api} ------  知识图谱计算法接口
     * @apiVersion 0.0.2
     * @apiName  get_xiance_tag_code  对接算法知识图谱计算法接口,先行测试调用
     * @apiGroup Algo/algoLogic
     * @apiParam {String} init_kstatus  学生所选自评水平.
     * @apiParam {String} kmap_code  知识图谱编码.
     * @apiParam {String} pre_knode  前一个知识点编码.
     * @apiParam {String} usr_ans  学生测试问题的答案编号.  有三种情况: 第一次的用户还没做题,直接取题的时候,传 ""  (即空), 已做题的话,传 "0"或"1" .
     * @apiParam {Number} level_mode  知识点难度级别
     * @apiSuccess {Number} usr_id   用户ID.
     * @apiSuccess {String} init_kstatus   用户对应的专题掌握的程度.
     * @apiSuccess {String} kmap_code   知识图谱编号.
     * @apiSuccess {Number} knode_toaskq 所得知识点
     * @apiSuccess {Number}  weak_elems  薄弱知识点列表.
     * @apiSuccess {Number}  sErrors    错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *
     *     }
     */
    public function get_xiance_tagCode($topicId, $init_kstatus, $kmap_code, $level_mode = 1, $last_answer_is_right = 0)
    {
        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
        $module_type = config('xiance_module_type');
        Log::record("------before-------get_xiance_tagCode");
        $user_answered_question_info = array();
        $user_service = new UserService();

        $user_ability_status = $this->algoStorage->getUserAbilityStatus($user_id, $topicId, $module_type);
        Log::record("------after-------getUserAbilityStatus");
        $difficulty= array();
        $score = array();

        //为空的情况下,表示用户第一次做.
        if (empty($user_ability_status)) {
            $pre_knode = "";
            $usr_ans = "";
            $likelihood = array();
        } else {
            if ($last_answer_is_right === null) {
                //打错误日志,此处应该执行不到的.
                Log::record('---err:---last_answer_is_right-----====null');
            }

            $question_service = new QuestionService();
            $user_exam_detail = $question_service->getUserXianceLastExamDetail($user_id, $topicId, $module_type);


            $likelihood = $user_ability_status['likelihood'];

            $question_service = new QuestionService();
            
            if(empty($user_exam_detail))
            {
                $pre_knode = "";   //调用了算法接口,但是用户在没有做题之前又调用接口的话pre_knode要设置为空。
                $usr_ans = "";
                $likelihood = array();

            }else{
                $user_last_answered_question_id = $user_exam_detail['question_id'];
                $api_gate_service = new ApiGateService();
                $question_info = $api_gate_service->getQuestionById($user_last_answered_question_id,$topicId);

                //如果内容没有难度的话,暂时默认设成1.
                if(!$question_info['difficulty'])
                {
                    $question_info['difficulty']=1;
                }
                $difficulty[]=$question_info['difficulty'];
                $score[] = $user_exam_detail['is_right'];
                $pre_knode = $user_exam_detail['tag_code'];
                $usr_ans = $user_exam_detail['is_right'];
                 //第一次的时候,如果用户取完数据,没有做题的话,需要单独处理下。
                $user_answered_question_info =  $question_service->getUserHasAnsweredQuestionsByTagCode($user_id, $topicId, $module_type,$pre_knode);

            }
            
        }
        $algo_service = new AlgoService();
        Log::record("------before-------call_algo_kstability");
        $type = array(1);
        $request_data['usr_id'] = $user_id;
        $request_data['init_kstatus'] = $init_kstatus;
        $request_data['kmap_code'] = $kmap_code;
        $request_data['pre_knode'] = $pre_knode;
        $request_data['usr_ans'] = $usr_ans;
        $request_data['level_mode'] = $level_mode;
        $request_data['difficulty'] = $difficulty;
        $request_data['score'] = $score;
        $request_data['likelihood'] = $likelihood;
        $request_data['type'] = json_encode($type);

        //发送给日志系统的额外数据。
        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );

//        $response_data = $algo_service->call_algo_kstime($user_id, $init_kstatus, $kmap_code, $pre_knode, $usr_ans, $level_mode ,$need_time,$take_time,$sys_code);
        $response_data = $algo_service->call_algo_kstability($user_id,$init_kstatus,$kmap_code,$pre_knode,$usr_ans,$level_mode,$difficulty,$score,$likelihood,$type,$log_option );
        if(empty($pre_knode))
        {
            $pre_knode = "";
        }

        if (!$response_data) {
            $return_data = array(
                'tag_code' => $response_data['knode_toaskq'],
                'error' => "算法返回有问题"
            );

        } else {
            $weak_elems = $response_data['weak_elems'];
            $last_tag_code = $response_data['knode_toaskq'];
            $likelihood = $response_data['likelihood'];
            Log::record("------before-------updateUserAbilityStatus");
//            if($pre_knode !="" )
//            {
            // 此处是因为，ksability每次返回的都是上次做的知识点的能力情况和likehood，所以需要将这些信息于上一个知识点绑定后，同时再插入一条下一个知识点
                $this->algoStorage->updateUserAbilityStatus($user_id, $topicId, $weak_elems, $module_type, $pre_knode, $usr_ans,$likelihood);

//            }
            ///更新能力值的最新的结果状态

            if($pre_knode!=$last_tag_code)
            {
                if($last_tag_code!=-1&&$last_tag_code!="")
                {
                    $this->algoStorage->updateUserAbilityStatus($user_id, $topicId, "", $module_type, $last_tag_code, 0,"");
                }
            }

            Log::record("------after-------updateUserAbilityStatus");

            Log::record("------before-------updateAbility");

            //记录能力值的变化过程。
            $this->algoStorage->updateAbility($user_id, $topicId, $module_type, $pre_knode, $response_data);
            Log::record("------after-------updateAbility");
            Log::record("-------------response_data------".json_encode($response_data)."----");
            //在ct_user_exam_detail表中,跟试题ID绑定能力值变化。kstability
            if(!empty($user_answered_question_info))
            {
                if($response_data['knode_toaskq']!="")
                {
                    $user_exam_detail_id = $user_answered_question_info[0]['id'];
                    $question_service->updateUserExamDetail($user_exam_detail_id,$response_data['ability']);
                }
            }

            Log::record("------after-------updateUserAbilityStatus");
            $return_data = array(
                'tag_code' => $response_data['knode_toaskq'],
                'error' => $response_data['error']
            );
        }
        return $return_data;
    }


    /**
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
    public function updateAbility($user_id, $tag_code, $diffculty, $is_right, $used_type, $topicId, $module_type,$submodule_type=0)
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
        $topic_service = new TopicService();
        $kmap_code = $topic_service->getKmapCodeByTopicId($topicId);
        $request_api = "call_algo_abilityx";
        $algo_storage = new AlgoStorage();
//        $algo_storage->saveAlgoLog($request_api,$topicId, $module_type, $kmap_code, $request_data, $algo_abilityx_return_data);
//        Log::write('-------algo------abilityx----return_data--------' . json_encode($algo_abilityx_return_data));
        $this->algoStorage->updateAbility($user_id, $topicId, $module_type, $tag_code, $algo_abilityx_return_data,$submodule_type);

        return $algo_abilityx_return_data;
    }


    /**
     * @api {api} ------ 边学边练获取下一个知识点的方法
     * @apiVersion 0.0.1
     * @apiName  get_bxbl_tagCode  对接算法知识图谱计算法接口,先行测试调用
     * @apiGroup Algo/algo
     * @apiParam {String} kmap_code   知识图谱编码 .
     * @apiParam {String} elements_codes  所有知识点编码.
     * @apiParam {String} elements_abilities  学生对所有知识点掌握的能力.
     * @apiParam {String} learning_counts  每个知识点,学生已经学习的题数.
     * @apiParam {String} weak_elements  所有的薄弱知识点.
     * @apiParam {Number} learned_elements   已经学过的知识点.
     * @apiSuccess {String} next_element   下一个题目的编号.
     * @apiSuccess {String} error   错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "next_element" : "zk_20",
     *          "error": "错误信息"
     *
     *     }
     */
    public function get_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements = array())
    {
//        $sKMapCode          = getKmapByTopicId($topicId)['kmapname'];        // 知识图谱code
//        $aElementsCodes     = array();           // 该知识图谱中所有知识点, array of codes
//        $aElementsAbilities = array();       // 所有知识点对应的该学生能力值, array of ability value， 默认值为-1
//        $aLearningCounts    = array();          // 所有知识点在该边学边练中学过的次数, array of int， 默认值为0
//        $aWeakElements      = array();            // 所有薄弱知识点code, array of codes
//        $aLearnedElements   = array();         // 所有已经学过的知识点code，按照学习的先后顺序排列， array of codes
        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
//        $elements_codes_str = config("elements_codes");
//        $elements_codes_arr = explode(",", $elements_codes_str);
        $knowledge_service = new KnowledgeService();
        $api_gate_service = new ApiGateService();
        $knowledge_list  =  $api_gate_service->getKnowledgeListByTopicId($topicId);
        foreach ($knowledge_list as $k=>$v)
        {
            $elements_codes_arr[] = $v['tag_code'];
        }
//        $elements_codes = json_encode($elements_codes_arr);
        $module_type = config("gaoxiao_module_type");
        $weak_elements = $this->algoStorage->getWeakElements($user_id, $topicId);
        $elements_abilities = array();
        $learning_counts = array();
        foreach ($elements_codes_arr as $tag_code) {
            $user = new UserService();
            $num = $user->getUserHasAnsweredNumForTagCode($user_id, $topicId, $module_type, $tag_code);
            $learning_counts[] = $num;
            $user_ability = $this->algoStorage->getUserAbility($user_id, $topicId, $module_type, $tag_code);
            $elements_abilities[] = $user_ability;
        }
        if (empty($learned_elements)) {
            $learned_elements = array();
        } else {
            $learned_elements = $learned_elements;
        }
        $algo_service = new AlgoService();

        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );
        $return_data = $algo_service->call_algo_nlix($kmap_code, $elements_codes_arr, $elements_abilities, $learning_counts, $weak_elements, $learned_elements,$log_option);

        return $return_data;
    }


//    /**
//     * 自己写的一个模拟算法取下一个知识点的方法.以后不用的.
//     */
//    public function getNextTagCode($topicId)
//    {
//        $userInfo = session('userInfo');
//        $user_id = $userInfo['user_id'];
//        $module_type = config("gaoxiao_module_type");
//        $has_learned_tag_code = session('has_learned_tag_code');
//        if (empty($has_learned_tag_code)) {
//            $has_learned_tag_code = array();
//        }
//        $weak_elements = $this->algoStorage->getWeakElements($user_id, $topicId, $module_type);
//        $arr = json_decode($weak_elements);
//        $diff_arr = array_diff($arr, $has_learned_tag_code);
//        if (empty($has_learned_tag_code)) {
//            $next_tag_code = $diff_arr[0];
//        } else {
//            $length = count($has_learned_tag_code);
//            $now_tag_code = $has_learned_tag_code[$length - 1];
//            $user = new UserService();
//            $has_learned_num = $user->getUserHasAnsweredNumForTagCode($user_id, $topicId, $module_type, $now_tag_code);
//
//            if ($has_learned_num >= 3) {
//                $new_arr = array_merge($diff_arr, array());
//                $next_tag_code = $new_arr[0];
//            } else {
//                $next_tag_code = $now_tag_code;
//            }
//        }
//        return $next_tag_code;
//    }


    /**
     * 判断是否应该进去堂堂清.
     * @param $user_id
     * @param $topicId
     * @param $module_type
     */
//    public function isEnterToTtq($user_id, $topicId, $module_type)
//    {
//        if (!$user_id) {
//            $userInfo = session('userInfo');
//            $user_id = $userInfo['user_id'];
//        }
//        $where['user_id'] = $user_id;
//        $where['topicId'] = $topicId;
//        $where['module_type'] = $module_type;
//        $answeredInfo = Db::name('user_exam')->where($where)->select();
//
//        $is_enter = 0;
//        if (!empty($answeredInfo)) {
//            $num = count($answeredInfo);
//            //已经做3个知识点,并且单个知识点已经做了3道题了.
//            if ($num % 3 == 0) {
//                $last_tag_code = $answeredInfo[$num - 1]['tag_code'];
//                $where_detail['user_id'] = $user_id;
//                $where_detail['topicId'] = $topicId;
//                $where_detail['module_type'] = $module_type;
//                $where_detail['tag_code'] = $last_tag_code;
//                $answer_detail = Db::name('user_exam_detail')->where($where_detail)->select();
//
//                $answer_num = count($answer_detail);
//                if ($answer_num < 3) {
//                    $is_enter = 0;
//                } else {
//                    $is_enter = 1;
//                }
//            } else {
//                $is_enter = 0;
//            }
//        } else {
//            $is_enter = 0;
//        }
//        return $is_enter;
//    }


    /**
     * 获取用户的薄弱知识点.
     * @param $user_id
     * @param $topicId
     */
    public function getWeakElements($user_id, $topicId)
    {
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
        }

        $weakElements = $this->algoStorage->getWeakElements($user_id, $topicId);
        $weakElements_arr = json_decode($weakElements);

        return $weakElements_arr;
    }


    /**
     * 调用算法,通过试题的难易度,来选择试题.
     * question_list  = array({"id":"14","difficulty":1},{"id":"12","difficulty":2});
     */
    public function chooseQuestionsByAlgo($user_id, $tag_code, $question_list = array(), $topicId,$module_type)
    {
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
        }

        $question_ids = array();
        $question_difficultys = array();
        $algo_service = new AlgoService();
        $question_ids = array();
        $question_difficultys = array();
        foreach ($question_list as $key => $val) {
            $question_ids[] = $val['id'];
            $question_difficultys[] = $val['difficulty'];
        }
        $ability = $this->algoStorage->getUserAbility($user_id, $topicId, $module_type, $tag_code);
        $assessment_size = config("assessment_size");

        //发送给日志的额外数据。
        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>"",//知识图谱
        );
        $return_data = $algo_service->call_algo_assessmentn($ability, $question_ids, $question_difficultys, $assessment_size,$log_option);
        return $return_data;

    }



    /**
     * 获取前置和后置知识点
     * @param $kmap_code
     * @param string $kmap_type （PREREQ：前置，POSTREQ：后置）
     * @return mixed
     */
    function getKnoledgeNode($kmap_code, $kmap_type = "PREREQ")
    {
        $key = "knowledgenode_" . $kmap_code . "_" . $kmap_type;
        $return_data = Cache::get($key);
        if (!$return_data) {
            $algo_service = new AlgoService();
            //发送给日志的额外数据。
            $log_option = array(
                "user_id"=>"",//用户id
                "topicId"=>"",//专题id
                "module_type"=>"",//模块id
                "kmap_code"=>$kmap_code,//知识图谱
            );

            $return_data = $algo_service->call_algo_knowledgenode($kmap_code, $kmap_type,$log_option);
            Cache::set($key, $return_data);
        }
        return $return_data;
    }


    public function getUserAbilityForTagCode($user_id,$topicId,$module_type,$tag_code)
    {
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
        }
        $ability = $this->algoStorage->getUserAbility($user_id, $topicId, $module_type, $tag_code);
        return $ability;
    }

    /**
     * 获取用户的 L2 薄弱知识点.
     * @param $user_id
     * @param $topicId
     */
    public function getL2WeakElements($user_id, $topicId)
    {
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
        }

        $weakElements = $this->algoStorage->getL2WeakElements($user_id, $topicId);

        if($weakElements)
        {
            $weakElements_arr = json_decode($weakElements);
        }else{
            $weakElements_arr = array();
        }

        return $weakElements_arr;
    }

        /**
     * @api {api} ------  知识图谱计算法接口
     * @apiVersion 0.0.2
     * @apiName  get_xiance_tag_code  对接算法知识图谱计算法接口,先行测试调用
     * @apiGroup Algo/algoLogic
     * @apiParam {String} init_kstatus  学生所选自评水平.
     * @apiParam {String} kmap_code  知识图谱编码.
     * @apiParam {String} pre_knode  前一个知识点编码.
     * @apiParam {String} usr_ans  学生测试问题的答案编号.  有三种情况: 第一次的用户还没做题,直接取题的时候,传 ""  (即空), 已做题的话,传 "0"或"1" .
     * @apiParam {Number} level_mode  知识点难度级别
     * @apiSuccess {Number} usr_id   用户ID.
     * @apiSuccess {String} init_kstatus   用户对应的专题掌握的程度.
     * @apiSuccess {String} kmap_code   知识图谱编号.
     * @apiSuccess {Number} knode_toaskq 所得知识点
     * @apiSuccess {Number}  weak_elems  薄弱知识点列表.
     * @apiSuccess {Number}  sErrors    错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *
     *     }
     */
    public function get_l2_xiance_tagCode($topicId, $init_kstatus, $kmap_code, $level_mode = 1, $last_answer_is_right = 0,$module_type)
    {
        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
        Log::record("------before-------get_xiance_tagCode");
        $user_answered_question_info = array();

        $user_ability_status = $this->algoStorage->getUserAbilityStatus($user_id, $topicId, $module_type);
        Log::record("------after-------getUserAbilityStatus");
        $difficulty= array();
        $score = array();

        //为空的情况下,表示用户第一次做.
        if (empty($user_ability_status)) {
            $pre_knode = "";
            $usr_ans = "";
            $likelihood = array();
        } else {
            if ($last_answer_is_right === null) {
                //打错误日志,此处应该执行不到的.
                Log::record('---err:---last_answer_is_right-----====null');
            }
            $question_service = new QuestionService();
            $user_exam_detail = $question_service->getUserXianceLastExamDetail($user_id, $topicId, $module_type);

            $likelihood = $user_ability_status['likelihood'];
//            //第一次的时候,如果用户取完数据,没有做题的话,需要单独处理下。
//            $user_answered_question_info =  $question_service->getUserHasAnsweredQuestionsByTagCode($user_id, $topicId, $module_type,$pre_knode);
            if(empty($user_exam_detail))
            {
                $pre_knode = "";   //调用了算法接口,但是用户在没有做题之前又调用接口的话pre_knode要设置为空。
                $usr_ans = "";
                $likelihood = array();

            }else{
                $user_last_answered_question_id = $user_exam_detail['question_id'];
                $question_v2_service  = new BaseQuestionV2Service();
                $question_info = $question_v2_service->getQuestionById($user_last_answered_question_id);
                //如果内容没有难度的话,暂时默认设成1.
                if(!$question_info['difficulty'])
                {
                    $question_info['difficulty']=1;
                }
                $difficulty[]=$question_info['difficulty'];

                $score[] = $user_exam_detail['is_right'];
                $pre_knode = $user_exam_detail['tag_code'];
                $usr_ans = $user_exam_detail['is_right'];
            }
        }
        $algo_service = new AlgoService();
        Log::record("------before-------call_algo_kstability");
        $type = array(1);
        $request_data['usr_id'] = $user_id;
        $request_data['init_kstatus'] = $init_kstatus;
        $request_data['kmap_code'] = $kmap_code;
        $request_data['pre_knode'] = $pre_knode;
        $request_data['usr_ans'] = $usr_ans;
        $request_data['level_mode'] = $level_mode;
        $request_data['difficulty'] = $difficulty;
        $request_data['score'] = $score;
        $request_data['likelihood'] = $likelihood;
        $request_data['type'] = json_encode($type);

        //发送给日志系统的额外数据。
        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );


//        $response_data = $algo_service->call_algo_kstime($user_id, $init_kstatus, $kmap_code, $pre_knode, $usr_ans, $level_mode ,$need_time,$take_time,$sys_code);
        $response_data = $algo_service->call_algo_kstability($user_id,$init_kstatus,$kmap_code,$pre_knode,$usr_ans,$level_mode,$difficulty,$score,$likelihood,$type,$log_option );
        if(empty($pre_knode))
        {
            $pre_knode = "";
        }

        if (!$response_data) {
            $return_data = array(
                'tag_code' => $response_data['knode_toaskq'],
                'error' => "算法返回有问题"
            );

        } else {
            $weak_elems = $response_data['weak_elems'];
            $last_tag_code = $response_data['knode_toaskq'];
            $likelihood = $response_data['likelihood'];
            Log::record("------before-------updateUserAbilityStatus");
//            if($pre_knode !="" )
//            {
            // 此处是因为，ksability每次返回的都是上次做的知识点的能力情况和likehood，所以需要将这些信息于上一个知识点绑定后，同时再插入一条下一个知识点
                $this->algoStorage->updateUserAbilityStatus($user_id, $topicId, $weak_elems, $module_type, $pre_knode, $usr_ans,$likelihood);

//            }
            ///更新能力值的最新的结果状态

            if($pre_knode!=$last_tag_code)
            {
                if($last_tag_code!=-1&&$last_tag_code!="")
                {
                    $this->algoStorage->updateUserAbilityStatus($user_id, $topicId, "", $module_type, $last_tag_code, 0,"");
                }
            }

            Log::record("------after-------updateUserAbilityStatus");

            Log::record("------before-------updateAbility");

            //记录能力值的变化过程。
            $this->algoStorage->updateAbility($user_id, $topicId, $module_type, $pre_knode, $response_data);
            Log::record("------after-------updateAbility");
            Log::record("-------------response_data------".json_encode($response_data)."----");

            //在ct_user_exam_detail表中,跟试题ID绑定能力值变化。
            if(!empty($user_answered_question_info))
            {
                if($response_data['knode_toaskq']!=""&&$response_data['knode_toaskq']!=-1)
                {
                    $user_exam_detail_id = $user_answered_question_info[0]['id'];
                    $question_service->updateUserExamDetail($user_exam_detail_id,$response_data['ability']);
                }
            }

            Log::record("------after-------updateUserAbilityStatus");
            $return_data = array(
                'tag_code' => $response_data['knode_toaskq'],
                'error' => $response_data['error']
            );
        }
        return $return_data;
    }

    /**
     * @api {api} ------ 边学边练获取下一个知识点的方法
     * @apiVersion 0.0.1
     * @apiName  get_bxbl_tagCode  对接算法知识图谱计算法接口,先行测试调用
     * @apiGroup Algo/algo
     * @apiParam {String} kmap_code   知识图谱编码 .
     * @apiParam {String} elements_codes  所有知识点编码.
     * @apiParam {String} elements_abilities  学生对所有知识点掌握的能力.
     * @apiParam {String} learning_counts  每个知识点,学生已经学习的题数.
     * @apiParam {String} weak_elements  所有的薄弱知识点.
     * @apiParam {Number} learned_elements   已经学过的知识点.
     * @apiSuccess {String} next_element   下一个题目的编号.
     * @apiSuccess {String} error   错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "next_element" : "zk_20",
     *          "error": "错误信息"
     *
     *     }
     */
    public function get_l2_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements = array(),$module_type)
    {
//        $sKMapCode          = getKmapByTopicId($topicId)['kmapname'];        // 知识图谱code
//        $aElementsCodes     = array();           // 该知识图谱中所有知识点, array of codes
//        $aElementsAbilities = array();       // 所有知识点对应的该学生能力值, array of ability value， 默认值为-1
//        $aLearningCounts    = array();          // 所有知识点在该边学边练中学过的次数, array of int， 默认值为0
//        $aWeakElements      = array();            // 所有薄弱知识点code, array of codes
//        $aLearnedElements   = array();         // 所有已经学过的知识点code，按照学习的先后顺序排列， array of codes
        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
//        $elements_codes_str = config("elements_codes");
//        $elements_codes_arr = explode(",", $elements_codes_str);
//        $knowledge_service = new KnowledgeService();
//        $knowledge_list  =  $knowledge_service->getKnowledgeListByTopicId($topicId);
        $topic_v2_service = new  \service\services\TopicV2Service();

        $knowledge_list = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);


        foreach ($knowledge_list as $k=>$v)
        {
            $elements_codes_arr[] = $v['tag_code'];
        }
//        $elements_codes = json_encode($elements_codes_arr);
        $weak_elements = $this->algoStorage->getL2WeakElements($user_id, $topicId);
        $elements_abilities = array();
        $learning_counts = array();
        foreach ($elements_codes_arr as $tag_code) {
            $user = new UserService();
            $num = $user->getUserHasAnsweredNumForTagCode($user_id, $topicId, $module_type, $tag_code);
            $learning_counts[] = $num;
            $user_ability = $this->algoStorage->getUserAbility($user_id, $topicId, $module_type, $tag_code);
            $elements_abilities[] = $user_ability;
        }
        if (empty($learned_elements)) {
            $learned_elements = array();
        } else {
            $learned_elements = $learned_elements;
        }
        $algo_service = new AlgoService();

        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );
        $return_data = $algo_service->call_algo_nlix($kmap_code, $elements_codes_arr, $elements_abilities, $learning_counts, $weak_elements, $learned_elements,$log_option);

        return $return_data;
    }


    /**
     * @api {api} ------ 边学边练获取下一个知识点的方法
     * @apiVersion 0.0.1
     * @apiName  get_bxbl_tagCode  对接算法知识图谱计算法接口,先行测试调用
     * @apiGroup Algo/algo
     * @apiParam {String} kmap_code   知识图谱编码 .
     * @apiParam {String} elements_codes  所有知识点编码.
     * @apiParam {String} elements_abilities  学生对所有知识点掌握的能力.
     * @apiParam {String} learning_counts  每个知识点,学生已经学习的题数.
     * @apiParam {String} weak_elements  所有的薄弱知识点.
     * @apiParam {Number} learned_elements   已经学过的知识点.
     * @apiSuccess {String} next_element   下一个题目的编号.
     * @apiSuccess {String} error   错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "next_element" : "zk_20",
     *          "error": "错误信息"
     *
     *     }
     */
    public function get_preview_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements = array(),$module_type)
    {
//        $sKMapCode          = getKmapByTopicId($topicId)['kmapname'];        // 知识图谱code
//        $aElementsCodes     = array();           // 该知识图谱中所有知识点, array of codes
//        $aElementsAbilities = array();       // 所有知识点对应的该学生能力值, array of ability value， 默认值为-1
//        $aLearningCounts    = array();          // 所有知识点在该边学边练中学过的次数, array of int， 默认值为0
//        $aWeakElements      = array();            // 所有薄弱知识点code, array of codes
//        $aLearnedElements   = array();         // 所有已经学过的知识点code，按照学习的先后顺序排列， array of codes
        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
        $topic_v2_service = new  \service\services\TopicV2Service();
        $knowledge_list = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);

        foreach ($knowledge_list as $k=>$v)
        {
            $elements_codes_arr[] = $v['tag_code'];
        }
//        $elements_codes = json_encode($elements_codes_arr);
        $weak_elements = $this->algoStorage->getL2WeakElements($user_id, $topicId);


        $knowledges_arr = array();
        $summer_cindex_service = new  \service\services\summer\SummerCindexService();
//        $kmap_code = $summer_cindex_service->getPreviewKmapCode($topicId);
        $topic_v2_service = new TopicV2Service();
        $kmap_code = $topic_v2_service->getMainKmapCode($topicId);

        $topic_v2_service = new  \service\services\TopicV2Service();
        $knowledgeList = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);
        foreach ($knowledgeList as $k=>$v) {
            $knowledges_arr[] = $v['tag_code'];
        }

        $weak_elements = json_encode($knowledges_arr);



        $elements_abilities = array();
        $learning_counts = array();
        foreach ($elements_codes_arr as $tag_code) {
            $user = new UserService();
            $num = $user->getUserHasAnsweredNumForTagCode($user_id, $topicId, $module_type, $tag_code);
            $learning_counts[] = $num;
            $user_ability = $this->algoStorage->getUserAbility($user_id, $topicId, $module_type, $tag_code);
            $elements_abilities[] = $user_ability;
        }


        if (empty($learned_elements)) {
            $learned_elements = array();
        } else {
            $learned_elements = $learned_elements;
        }
        $algo_service = new AlgoService();

        $log_option = array(
            "user_id"=>$user_id,//用户id
            "topicId"=>$topicId,//专题id
            "module_type"=>$module_type,//模块id
            "kmap_code"=>$kmap_code,//知识图谱
        );
        $return_data = $algo_service->call_algo_nlix($kmap_code, $elements_codes_arr, $elements_abilities, $learning_counts, $weak_elements, $learned_elements,$log_option);

        return $return_data;
    }




}