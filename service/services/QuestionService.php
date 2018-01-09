<?php
namespace service\services;

use service\algo\SummerAlgoLogic;
use service\entity\User;
use service\org\util\TpString;
use service\algo\AlgoLogic;
use service\services\summer\SummerQuestionService;
use service\services\summer\SummerUserService;
use think\console\command\make\Model;
use think\Db;
use think\Log;
use think\Cache;
use service\log\LogService;
use service\services\KnowledgeV2Service;
use service\org\util\Unicode;
use service\lib\CheckAnswer;
use YXLog\YXLog;
class QuestionService extends BaseQuestionService
{
    /**
     * 获取用户已经做过的试题.
     */
    public function getUserHasAnsweredQuestions($user_id, $topicId, $module_type,$tag_code=null)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        if($tag_code)
        {
            $where['tag_code'] = $tag_code;
        }
        $userAnsweredQuestions = Db::name('user_exam_detail')->where($where)->select();
        $userAnsweredQuestionIds = array();
        foreach ($userAnsweredQuestions as $userAnsweredQuestion) {
            $userAnsweredQuestionIds[] = $userAnsweredQuestion['question_id'];
        }
        return $userAnsweredQuestionIds;
    }

    /**
     * 根据根据知识点用户做题信息。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $tag_code
     * @return false|mixed|\PDOStatement|string|\think\Collection
     */
    public function getUserHasAnsweredQuestionsByTagCode($user_id, $topicId, $module_type,$tag_code)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        if($tag_code)
        {
            $where['tag_code'] = $tag_code;
        }
        $userAnsweredQuestions = Db::name('user_exam_detail')->where($where)->order("id desc")->select();
//        $userAnsweredQuestionIds = array();
//        foreach ($userAnsweredQuestions as $userAnsweredQuestion) {
//            $userAnsweredQuestionIds[] = $userAnsweredQuestion['question_id'];
//        }
        return $userAnsweredQuestions;
    }



    /**
     * 获取先行测试的下一个试题.
     * @param $topicId
     * @param $knowledge
     * @param $module
     * @param $used_type
     */
    public function getXianceNextQuestion($topicId, $tag_code, $module_type, $used_type)
    {
        Log::info("---00000000000------getXianceNextQuestion---------getQuestionsByKnowledge-----");
        $api_gate_service  = new ApiGateService();
        $questions_list = $api_gate_service->getQuestionsByKnowledge($tag_code, $module_type, $used_type,$topicId);
        Log::info("---00000000000------getXianceNextQuestion---question_list--".json_encode($questions_list));
        $questions_id_arr = array();
        foreach ($questions_list as $key => $val) {
            $questions_id_arr[] = $val['id'];
        }
        $hasAnsweredQuestionsId = $this->getUserHasAnsweredQuestions('', $topicId, $module_type,$tag_code);
        $not_answered_questionsId = array_diff($questions_id_arr, $hasAnsweredQuestionsId);
        $not_answered_questionsId_arr = array_merge($not_answered_questionsId, array());
        if (empty($not_answered_questionsId_arr)) {
            $num = count($questions_id_arr);
            $return_data['error'] = "题量不够出问题了,算法已推出,并且用户已做" . $num . "道题";
            Log::info("---111111------");
            Log::info($return_data['error']);
            Log::info("---111111------");
        } else {
            //老的执行代码
            $next_question_id = $not_answered_questionsId_arr[0];

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
//            $next_question_id = "589980b2f4aeb569992f0431";
            Log::info("---00000000000------getXianceNextQuestion----");
            Log::info("---next_question_id------".$next_question_id);
            Log::info("---00000000000-----getXianceNextQuestion-");
            //根据ID获取试题.
            Log::record("------before-------getQuestionById");
            $return_data = $api_gate_service->getQuestionById($next_question_id,$topicId);
            Log::record("------after-------getQuestionById");
            $return_data['error'] = "";
            ///////////////////////////
            Log::record("------after-------999999");

            Log::record("------after-------7777");

            $log_service = new logService();
            $msg='会员id:'.$this->getUserId().'取题时间:'. date('Y-m-d H:i:s');
            $log_service::sendMessage('info',__METHOD__."取题###试题ID为: $next_question_id -------".$msg);
            ///////////////////////////
        }
        return $return_data;
    }


    /**
     * 提交试题
     */
    public function submitQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type, $submodule_type = 0, $isViewAnalyze = 0)
    {
        $user_id = $this->getUserId();
        $return_data = array(
            'isSuccess' => 1,
            'is_right' => 0
        );
        $ability = null;
        $getQuestionTime = session("getQuestionTime");//获取取题时间
        foreach ($answer_content as $key => $q_info) {
            $question_id = $q_info['question_id'];
            try{
//                if($topicId>=9000)
//                {
                    $return_info = $this->compare_question($question_id, $q_info,"v2");
//                }else{
//                    $return_info = $this->compare_question($question_id, $q_info,"v1");
//                }
            }catch (Exception $e)
            {
                log::record( $e->getMessage());
                $return_info['is_right'] = 0;
                $return_data['right_answer'] = "";
                $return_data['user_answer'] ="";
            }



            $is_right = $return_info['is_right'];
            $right_answer = $return_info['right_answer'];
            $user_answer = $return_info['user_answer'];
//            $isViewAnswer = empty($q_info['is_view_answer']) ? 0 : 1;
            $isViewAnswer = 0;
            if(isset($q_info['is_view_answer']))
            {
                $isViewAnswer = $q_info['is_view_answer'];
                if($isViewAnswer==1)
                {
                    $is_right  = 0;
                }
            }

            $isViewAnalyze = 0;
            if (isset($q_info['is_view_analyze'])) $isViewAnalyze = $q_info['is_view_analyze'];
            
            //根据试题信息取出试题的难易度.
            $api_gate_service = new ApiGateService();
            $question_info = $api_gate_service->getQuestionById($question_id,$topicId);
            $diffculty = $question_info['difficulty'];
            $estimates_time = $question_info['estimates_time'];
            if(!$diffculty)
            {
                $diffculty = config('default_diffculty');
            }
            $algoLogic = new AlgoLogic();
            // 只有边学边练的时候,会更新能力值.
            Log::record("------before-------updateAbility");
            //调用算法的现行测试新接口后,都不用更新能力值了。都是取知识点的时候更新。
            if ($module_type == 2) {
                $algo_abilityx_return_data = $algoLogic->updateAbility($user_id, $tag_code, $diffculty, $is_right, $used_type, $topicId, $module_type,$submodule_type);
                $ability = $algo_abilityx_return_data["ability"];
            }
            Log::record("------after-------updateAbility");

            //记录进入数据库,并记录入缓存中.
            Log::record("------before-------insertUserExamInfo");
            $this->insertUserExamInfo($topicId, $question_id, $return_info, $module_type, $used_type, $tag_code, $submodule_type, $isViewAnswer, $isViewAnalyze, $getQuestionTime,$ability,$diffculty,$estimates_time);
            Log::record("------after-------insertUserExamInfo");
            //更新用户的LOG.
            $this->updateUserExamActionLog("", $topicId, $module_type, $question_id);
            $return_data['is_right'] = $is_right;
            ///////////////////////////
            $log_service = new logService();
            $msg='会员id:'.$this->getUserId().'答题时间:'. date('Y-m-d H:i:s');
            $log_service::sendMessage('info',__METHOD__."答题###试题ID为: $question_id -------".$msg);
            ///////////////////////////
        }
        return $return_data;
    }


    /**
     * 提交试题
     */
    public function redoSubmitQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type, $submodule_type = 0, $isViewAnalyze = 0)
    {
        $user_id = $this->getUserId();
        $return_data = array(
            'isSuccess' => 1,
            'is_right' => 0
        );
        $getQuestionTime = session("getQuestionTime");//获取取题时间
        foreach ($answer_content as $key => $q_info) {
            $question_id = $q_info['question_id'];
//            if($topicId>=9000)
//            {
                $return_info = $this->compare_question($question_id, $q_info,"v2");
//            }else{
//                $return_info = $this->compare_question($question_id, $q_info,"v1");
//            }
            $is_right = $return_info['is_right'];
            $right_answer = $return_info['right_answer'];
            $user_answer = $return_info['user_answer'];
            $isViewAnswer = empty($q_info['is_view_answer']) ? 0 : 1;
            $isViewAnalyze = 0;
            if (isset($q_info['is_view_analyze'])) $isViewAnalyze = $q_info['is_view_analyze'];
            //记录进入数据库,并记录入缓存中.
            Log::record("------before-------insertUserExamInfo");
            $this->insertUserRedoExamInfo($topicId, $question_id, $return_info, $module_type, $used_type, $tag_code, $submodule_type, $isViewAnswer, $isViewAnalyze, $getQuestionTime);
            $return_data['is_right'] = $is_right;
        }
        return $return_data;
    }


    /**
     * 判断试题的正确与否,并进行入库操作.
     * @param $question_id
     * @param array $user_answer = array(type=>1,answer=>"A");
     *
     */
    private function compare_question($question_id, $q_info,$version="v1")
    {
        $q_type = $q_info['type'];
        //q_type=1 表示单选题.q_type=2 ,表示是填空题.
        switch ($q_type) {
            case 1:
                $return_info = $this->compare_single_choice_question($question_id, $q_info,$version);
                break;
            case 2:
                $return_info = $this->compare_blank_question($question_id, $q_info,$version);
                break;
            case 3:
                $return_info = $this->compare_multiple_choice_question($question_id, $q_info,$version);
                break;
            default:
                $return_info = array();
        }
        return $return_info;
    }


    /**
     * 单选题答案对比.
     */
    private function compare_single_choice_question($question_id, $q_info,$version)
    {
        if($version=='v2')
        {
            $question_v2_service = new BaseQuestionV2Service();
            $question_info = $question_v2_service->getQuestionById($question_id);
        }else{
            $question_info = $this->getQuestionById($question_id);
        }
        $right_answer = $question_info['answer'];
//        $right_answer_base64 = $question_info['answer_base64'];
        $user_answer = $q_info['answer'];
        if (trim($right_answer) == trim($user_answer)) {
            $is_right = 1;
        } else {
            $is_right = 0;
        }
        $return_info['is_right'] = $is_right;
        $return_info['right_answer'] = json_encode($right_answer);
//        $return_info['right_answer_base64'] = json_encode($right_answer_base64);
        $return_info['user_answer'] = $user_answer;
//        $return_info['user_answer_base64'] = $q_info['answer_base64'];
        return $return_info;
    }

    /**
     * 暂时没有先不考虑
     * 多选题答案对比
     */
    private function compare_multiple_choice_question($question_id, $q_info)
    {
//        $question_info = $this->getQuestionById($question_id);
//        $right_answer  = $question_info['answer'];
//        $user_answer =  $q_info['user_answer'];
//        $return_info['is_right'] = 1;
//        $return_info['right_answer'] = "A";
//        $return_info['user_answer'] = "B";
    }

    /**
     * 填空题答案判断.
     */
    private function compare_blank_question($question_id, $q_info,$version)
    {
        if($version=='v2')
        {
            $question_v2_service = new BaseQuestionV2Service();
            $question_info = $question_v2_service->getQuestionById($question_id);
        }else{
            $question_info = $this->getQuestionById($question_id);
        }
        //容错处理，如果填空题的答案给的不是数组格式的，则强制转成数组，并记下日志。
        if(is_array($question_info['answer']))
        {
            $right_answer = $question_info['answer'];
        }else{
            Log::error("此题： $question_id  ，是填空题，返回的答案不是array.");
            $log_service = new logService();
            $log_service::sendMessage('error',__METHOD__."此题： $question_id  ，是填空题，返回的答案不是array.");

            $right_answer = array();
        }

        $right_answer_base64 = $question_info['answer_base64'];
        $user_answer_arr = explode(";", $q_info['answer']);
        $is_right = 0;
        $is_right_arr = array();
        $user_answer_num = count($user_answer_arr);
        $right_answer_num = count($right_answer);
        Log::record("-------user_answer_num".$user_answer_num."-------right_answer_num----".$right_answer_num."------");
        $check_tiku_answer[] ="";
        $check_user_answer[] = "";
        if($user_answer_num==$right_answer_num)
        {
            Log::record("-------user_answer_num---right_answer_num---相等-----");
            foreach ($user_answer_arr as $key => $val) {
//                $val = str_replace("\, ", "", $val);
                if(is_array($right_answer))
                {
                    $answer_html = $right_answer[$key];
                    foreach ($answer_html as $kk => $v) {
                        $answer[$kk] = htmlspecialchars_decode($v);
                    }
                    $is_one_answer_right = 0;
                    foreach ( $answer as $k =>$v)
                    {
                        //大于小于做的特殊处理，并且把样式过滤掉了
                        $txt1 = $v;
                        $txt1 = preg_replace( '/(style=.+?[\'|"])|((width)=[\'"]+[0-9]+[\'"]+)|((height)=[\'"]+[0-9]+[\'"]+)/i', '' , $txt1);
                        $v = html_entity_decode($txt1);
                        $v = htmlspecialchars_decode($v,ENT_QUOTES); //解析单引号

                        //把空格全替换掉。
                        $val = str_replace(' ', '', $val);
                        $v = str_replace(' ', '', $v);

                        $val = str_replace('，', ',', $val);
                        $v = str_replace('，', ',', $v);

                        //将题库和前段的数据都做了 全角转半角的转化。已解决应半角全角问题，导致的答案判断错误问题。
//                        $val = Unicode::sbc2Dbc($val);
//                        $v = Unicode::sbc2Dbc($v);

                        $val = trim($val);
                        $v = trim($v);
                        $val = CheckAnswer::trim_tiankong_answer($val);
                        $v = CheckAnswer::trim_tiankong_answer($v);
                        $check_user_answer []= $val;
                        $check_tiku_answer [] =$v;


//                        var_dump(htmlspecialchars_decode($v,ENT_QUOTES));die;
                        if($val===$v)
                        {
//                            $is_right_arr[] = 1;
                            $is_one_answer_right = 1;
                            break;
                        }else{
//                            $is_right_arr[] = 0;
                            $is_one_answer_right = 0;
                        }
                    }
                    $is_right_arr[] = $is_one_answer_right;
                }else{
                    Log::error("此题： $question_id  ，是填空题，返回的答案不是array.");
                    $log_service = new logService();
                    $log_service::sendMessage('error',__METHOD__."此题： $question_id  ，是填空题，返回的答案不是array.");
                    $is_right_arr[] = 0;
                }
            }
            if (!in_array(0, $is_right_arr)) {
                $is_right = 1;
            }
        }else{
            Log::record("-------user_answer_num---right_answer_num---不等-----");

            $is_right = 0;
            $log_service = new logService();
            $log_service::sendMessage('error',__METHOD__."试题ID: $question_id  内容不对,缺少答案。前段展示需要输入".$user_answer_num."个答案,而内容只有".$right_answer_num."个答案");
            Log::error("试题ID: $question_id 内容不对,缺少答案。前段展示需要输入".$user_answer_num."个答案,而内容只有".$right_answer_num."个答案");
        }


        $return_info['is_right'] = $is_right;
        $return_info['right_answer'] = json_encode($right_answer);
        $return_info['right_answer_base64'] = json_encode($right_answer_base64);
        $return_info['user_answer'] = $q_info['answer'];
        $return_info['user_answer_base64'] = $q_info['answer_base64'];
        $return_info['check_tiku_answer'] = $check_tiku_answer;
        $return_info['check_user_answer'] = $check_user_answer;
        return $return_info;
    }


    /**
     * 插入试题数据.
     */
    private function insertUserExamInfo($topicId, $question_id, $return_info, $module_type, $used_type, $tag_code, $submodule_type, $isViewAnswer = 0, $isViewAnalyze = 0, $getQuestionTime = 0,$ability=null,$diffculty=0,$estimates_time=0)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['question_id'] = $question_id;
        $where['module_type'] = $module_type;
        $where['tag_code'] = $tag_code;
        $isHasAnswered = Db::name('user_exam_detail')->where($where)->find();

//        $gaoxiao_module_type = config("gaoxiao_module_type");
//        $xuexi_module_type = config("xuexi_module_type");
        $bxbl_module_type = config('bxbl_module_type');

        if ($isHasAnswered) {
            return;
        } else {
            if ($module_type == $bxbl_module_type) {
                $batch_num = $this->getNowBatchNum($topicId, $module_type);
            } else {
                $batch_num = 1;
            }

            $is_right = $return_info['is_right'];
            //如果查看过答案，也是错的
            if ($isViewAnswer == 1) {
                $is_right = 0;
            }
            YXLog::error("-----insertUserExamInfo-------前端传递的spent_time为:". input('spent_time')."-直接获取的input接受值------insertUserExamInfo----");
            $data = array(
                'user_id' => $user_id,
                'topicId' => $topicId,
                'batch_num' => $batch_num,
                'tag_code' => $tag_code,
                'module_type' => $module_type,
                'submodule_type' => $submodule_type,
                'used_type' => $used_type,
                'question_id' => $question_id,
                'right_answer' => $return_info['right_answer'],
                'user_answer' => $return_info['user_answer'],
                'right_answer_base64' => empty($return_info['right_answer_base64']) ? "" : $return_info['right_answer_base64'],
                'user_answer_base64' => empty($return_info['user_answer_base64']) ? "" : $return_info['user_answer_base64'],
                'is_right' => $is_right,
                'is_view_answer' => $isViewAnswer,
                'is_view_analyze' => $isViewAnalyze,
                'ctime' => time(),
                'estimates_time' => $estimates_time,
                'difficulty' => $diffculty,
                'stime' => $getQuestionTime,//获取试题时间
                'course_id'=> session('userInfo.course_id'),
                'course_name'=>session('userInfo.course_name'),
                'section_id'=>session('userInfo.section_id'),
                'section_name'=>session('userInfo.section_name'),
                'class_id'=>session('userInfo.class_id'),
                'class_name'=>session('userInfo.class_name'),
                'spent_time'=> input('spent_time', 5)
            );
            if(!empty($ability)){
                $data["ability"] = $ability;
            }

            Db::name('user_exam_detail')->insert($data);
            $where_user_exam['user_id'] = $user_id;
            $where_user_exam['topicId'] = $topicId;
            $where_user_exam['module_type'] = $module_type;
            $where_user_exam['tag_code'] = $tag_code;

            $user_exam_info = Db::name('user_exam')->where($where_user_exam)->find();

            if ($user_exam_info) {
                //已有此数据,则进行错题喝和对题的数,还有时间的更新
                if ($is_right) {
                    $user_exam_data['right_num'] = $user_exam_info['right_num'] + 1;
                    $user_exam_data['utime'] = time();
                    Db::name('user_exam')->where($where_user_exam)->update($user_exam_data);
                } else {
                    $user_exam_data['wrong_num'] = $user_exam_info['wrong_num'] + 1;
                    $user_exam_data['utime'] = time();
                    Db::name('user_exam')->where($where_user_exam)->update($user_exam_data);
                }
            } else {
                $user_exam_data['user_id'] = $user_id;
                $user_exam_data['topicId'] = $topicId;
                $user_exam_data['module_type'] = $module_type;
                $user_exam_data['tag_code'] = $tag_code;
                $user_exam_data['ctime'] = time();
                $user_exam_data['batch_num'] = $batch_num;
                //如果没有,则插入此数据.如果有自动加一
                if ($is_right) {
                    $user_exam_data['right_num'] = 1;
                } else {
                    $user_exam_data['wrong_num'] = 1;
                }
                Db::name('user_exam')->insert($user_exam_data);
            }
        }
    }

    /**
     * 插入试题数据.
     */
    private function insertUserRedoExamInfo($topicId, $question_id, $return_info, $module_type, $used_type, $tag_code, $submodule_type, $isViewAnswer = 0, $isViewAnalyze = 0, $getQuestionTime = 0)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['question_id'] = $question_id;
        $where['module_type'] = $module_type;
        $isHasAnswered = Db::name('user_exam_redo_detail')->where($where)->find();
//        $gaoxiao_module_type = config("gaoxiao_module_type");
//        $xuexi_module_type = config("xuexi_module_type");
        $bxbl_module_type = config('bxbl_module_type');

        if ($isHasAnswered) {
            return;
        } else {
            if ($module_type == $bxbl_module_type) {
                $batch_num = $this->getNowBatchNum($topicId, $module_type);
            } else {
                $batch_num = 1;
            }

            $is_right = $return_info['is_right'];
            //如果查看过答案，也是错的
            if ($isViewAnswer == 1) {
                $is_right = 0;
            }
            $data = array(
                'user_id' => $user_id,
                'topicId' => $topicId,
                'batch_num' => $batch_num,
                'tag_code' => $tag_code,
                'module_type' => $module_type,
                'submodule_type' => $submodule_type,
                'used_type' => $used_type,
                'question_id' => $question_id,
                'right_answer' => $return_info['right_answer'],
                'user_answer' => $return_info['user_answer'],
                'right_answer_base64' => empty($return_info['right_answer_base64']) ? "" : $return_info['right_answer_base64'],
                'user_answer_base64' => empty($return_info['user_answer_base64']) ? "" : $return_info['user_answer_base64'],
                'is_right' => $is_right,
                'is_view_answer' => $isViewAnswer,
                'is_view_analyze' => $isViewAnalyze,
                'ctime' => time(),
                'stime' => $getQuestionTime,//获取试题时间

            );


            Db::name('user_exam_redo_detail')->insert($data);
            $where_user_exam['user_id'] = $user_id;
            $where_user_exam['topicId'] = $topicId;
            $where_user_exam['module_type'] = $module_type;
            $where_user_exam['tag_code'] = $tag_code;

            $user_exam_info = Db::name('user_exam')->where($where_user_exam)->find();

            if ($user_exam_info) {
                //已有此数据,则进行错题喝和对题的数,还有时间的更新
                if ($is_right) {
                    $user_exam_data['right_num'] = $user_exam_info['right_num'] + 1;
                    $user_exam_data['utime'] = time();
                    Db::name('user_exam')->where($where_user_exam)->update($user_exam_data);
                } else {
                    $user_exam_data['wrong_num'] = $user_exam_info['wrong_num'] + 1;
                    $user_exam_data['utime'] = time();
                    Db::name('user_exam')->where($where_user_exam)->update($user_exam_data);
                }
            } else {
                $user_exam_data['user_id'] = $user_id;
                $user_exam_data['topicId'] = $topicId;
                $user_exam_data['module_type'] = $module_type;
                $user_exam_data['tag_code'] = $tag_code;
                $user_exam_data['ctime'] = time();
                $user_exam_data['batch_num'] = $batch_num;
                //如果没有,则插入此数据.如果有自动加一
                if ($is_right) {
                    $user_exam_data['right_num'] = 1;
                } else {
                    $user_exam_data['wrong_num'] = 1;
                }
                Db::name('user_exam')->insert($user_exam_data);
            }
        }
    }








    /**
     * 获取用户最后一条没做过的题信息.
     * @param $topicId
     * @param $module_type
     * @param null $userId
     */
    public function getLastUserExamActionLog($topicId, $module_type, $user_id = null)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['is_submit'] = 0;  //表示用户没有做.
        $return_data = Db::name('user_exam_action_log')->where($where)->order("id desc")->find();
        return $return_data;
    }

    /**
     * 插入用户的组题记录
     * @param $topicId
     * @param $module_type
     * @param $question_id
     * @param $return_data
     * @param $tag_code
     * @param null $userId
     */
    public function insertUserExamActionLog($user_id = null, $topicId, $module_type, $question_id, $question_info, $tag_code, $is_submit = 0)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $serialize_questionInfo_data = serialize($question_info);
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['question_id'] = $question_id;
        $where['is_submit'] = $is_submit;
        $return_data = Db::name('user_exam_action_log')->where($where)->order("id desc")->find();
        if (!$return_data) {
            $user_exam_action_log_data['user_id'] = $user_id;
            $user_exam_action_log_data['module_type'] = $module_type;
            $user_exam_action_log_data['question_id'] = $question_id;
            $user_exam_action_log_data['topicId'] = $topicId;
            $user_exam_action_log_data['tag_code'] = $tag_code;
            $user_exam_action_log_data['question_info'] = $serialize_questionInfo_data;
            Db::name('user_exam_action_log')->insert($user_exam_action_log_data);
        }
    }


    /**
     * 更新用户的做题的试题状态.
     */
    public function updateUserExamActionLog($user_id = null, $topicId, $module_type, $question_id)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['question_id'] = $question_id;
        $user_exam_action_log_data['is_submit'] = 1;
        Db::name('user_exam_action_log')->where($where)->update($user_exam_action_log_data);
    }


    /**
     * @param $tag_code
     * @param $module_type
     * @param $used_type
     */
    public function getBxblNextQuestion($user_id, $tag_code, $module_type, $used_type, $topicId)
    {
        $extraQuestions = $this->getBxblQuestions($user_id, $tag_code, $module_type, $used_type, $topicId);
        $next_question_id = $extraQuestions[0];
//        $next_question_id = "5820cfd114fef96fe66d7ee2";
//        $next_question_id ="5820cfd114fef96fe66d7ee2";
        Log::record("------before----getBxblNextQuestion---getQuestionById");
        $api_gate_service  = new  ApiGateService();
        $return_data = $api_gate_service->getQuestionById($next_question_id,$topicId);
        ///////////////////////////
        $log_service = new logService();
        $msg='会员id:'.$this->getUserId().'取题时间:'. date('Y-m-d H:i:s');
        $log_service::sendMessage('info',__METHOD__."取题###试题ID为: $next_question_id -------".$msg);
        ///////////////////////////
        return $return_data;
    }

    /**
     * 获取用户边学边练要做的题.
     * @param $user_id
     * @param $tag_code
     * @param $module_type
     * @param $used_type
     * @param $topicId
     * @return mixed
     */
    public function getBxblQuestions($user_id, $tag_code, $module_type, $used_type, $topicId)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        //用户已经做过的题.
        $hasAnsweredQuestionsId = $this->getUserHasAnsweredQuestions($user_id, $topicId, $module_type);
        $api_gate_service = new ApiGateService();
        $used_type ="";
        $question_list = $api_gate_service->getQuestionsByKnowledge($tag_code, $module_type, $used_type,$topicId);
//        $question_list = $this->getQuestionsByKnowledge($tag_code, $module_type, $used_type);
        $extraQuestions = array();
        $i = 0;
        foreach ($question_list as $key => $val) {
            if (!in_array($val['id'], $hasAnsweredQuestionsId)) {
                $extraQuestions[$i]["id"] = $val['id'];
                $extraQuestions[$i]["difficulty"] = $val['difficulty'];
                $i++;
            }
        }
        if (!empty($extraQuestions)) {
            //调用算法筛题.
            $module_type = config("gaoxiao_module_type");

            $algoLogic = new AlgoLogic();
            $question_ids = $algoLogic->chooseQuestionsByAlgo("", $tag_code, $extraQuestions, $topicId,$module_type);
            if (empty($question_ids)) {
                exit("算法错误---------算法未返回知识点.");
            }
        } else {
            exit("知识点取不到题,有BUG");
        }
        return $question_ids;
    }


    /**
     * 是否已经调用算法,获取到某个知识点边学边练下要做的题.
     */
    public function getQuestionsForBxblByTagCode($tag_code)
    {
        $user_id = $this->getUserId();
        $type = 1;  //边学边练的练习过程是1,堂堂清是2.
        $where['user_id'] = $user_id;
        $where['tag_code'] = $tag_code;
        $where['type'] = $type;
        $return_info = Db::name('user_bxbl_question')->where($where)->find();
        return $return_info;
    }


    /**
     * 插入用户边学边练的要做的试题.
     */
    public function insertUserBxblQuestion($user_id = null, $tag_code, $question_ids = array(), $topicId, $type = 1)
    {
        if ($user_id) {
            $user_id = $this->getUserId();
        }
        $return_data = $this->getQuestionsForBxblByTagCode($tag_code);

        $module_type = config('gaoxiao_module_type');
        $batch_num = $this->getBatchNum($topicId, $module_type);

        if (empty($return_data)) {

            Log::record("------batch_num-----" . $batch_num . "-----batch_num");
            $data['user_id'] = $user_id;
            $data['tag_code'] = $tag_code;
            $data['topicId'] = $topicId;
            $data['type'] = $type;
            $data['question_ids'] = json_encode($question_ids);
            $data['batch_num'] = $batch_num;
            Db::name('user_bxbl_question')->insert($data);
        }
    }


    /**
     * 获取当前应该的批次。主要用在提交时候。
     */
    public function getNowBatchNum($topicId,$module_type)
    {
        $user_id = $this->getUserId();
        $batch_num = $this->getBatchNum($topicId,$module_type);
        $detect_is_end = $this->getUserDetectIsEnd($user_id, $topicId, $batch_num);

        if($detect_is_end)
        {
            $batch_num = $batch_num +1;
        }
        return $batch_num;
    }

    /**
     * 获取用户取题
     *
     */
    public function getBatchNum($topicId, $module_type)
    {
        $user_id = $this->getUserId();
        $getUserHasAchieveTagCode = $this->getUserHasAchieveTagCode($topicId, $module_type);
        $has_get_num = count($getUserHasAchieveTagCode);
        if ($has_get_num != 0) {
            Log::record("------has_get_num-------" . $has_get_num . "-----has_get_num");
            $batch_num = ceil($has_get_num / 3);
        } else {
            $batch_num = 1;
        }
        return $batch_num;
    }

    /**
     * 获取用户已经获取过知识点的边学边练的知识点.
     * @param $topicId
     * @param $module_type
     */
    public function getUserHasAchieveTagCode($topicId, $module_type)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $answeredInfo = Db::name('user_exam')->where($where)->order("id desc")->select();
        return $answeredInfo;
    }


    public function getUserHasAchieveTagCodeOrderByAsc($topicId, $module_type)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $answeredInfo = Db::name('user_exam')->where($where)->order("id asc")->select();
        return $answeredInfo;

    }


    public function isEnterToTtq($user_id = null, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $batch_num = $this->getBatchNum($topicId, $module_type);
        //先计算本批次的边学边练前部分有没有做完.
        $bxbl_front_is_end = $this->getUserBxblFrontIsEnd($user_id, $topicId, $batch_num, $module_type);
        //计算堂堂清此部分有没有做完.
        $bxbl_ttq_is_end = $this->getUserBxblTtqIsEnd($user_id, $topicId, $batch_num, $module_type);
        if ($bxbl_ttq_is_end && $bxbl_front_is_end) {
            $is_enter = 0;
        } elseif (!$bxbl_ttq_is_end && !$bxbl_front_is_end) {
            $is_enter = 0;
        } elseif ($bxbl_front_is_end && !$bxbl_ttq_is_end) {
            $is_enter = 1;
        } else {
            $is_enter = 0;
        }
        return $is_enter;
    }


    /**
     * 判断是否进入学习检测。
     * @param null $user_id
     * @param $topicId
     * @param $module_type
     */
    public function isEnterToDetect($user_id = null, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $batch_num = $this->getBatchNum($topicId, $module_type);
        //判断用户本批次的高效学习是否完成。
        $gaoxiao_is_end = $this->getUserGaoxiaoIsEnd('', $topicId, $batch_num, $module_type);

        $detect_is_end = $this->getUserDetectIsEnd($user_id, $topicId, $batch_num);

        if ($detect_is_end && $gaoxiao_is_end) {
            $is_enter = 0;
        } elseif (!$detect_is_end && !$gaoxiao_is_end) {
            $is_enter = 0;
        } elseif ($gaoxiao_is_end && !$detect_is_end) {
            $is_enter = 1;
        } else {
            $is_enter = 0;
        }
        return $is_enter;
    }


    /**
     * 获取用户高效学习的知识点。
     * @param $user_id
     * @param $topicId
     * @param $batch_num
     * @param $module_type
     */
    private function getUserGaoxiaoIsEnd($user_id, $topicId, $batch_num, $module_type)
    {

        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['batch_num'] = $batch_num;
        $where['module_type'] = $module_type;
        $user_exam_info = Db::name('user_exam')->where($where)->order("id desc")->select();


        if (!empty($user_exam_info)) {
            $num = count($user_exam_info);
            if ($num % 3 == 0) {
                $user_answered_list = Db::name('user_exam_detail')->where($where)->order("id desc")->select();
                $has_answered_num = count($user_answered_list);
                if ($has_answered_num >= 9) {
                    $is_end = 1;
                } else {
                    $is_end = 0;
                }
            } else {
                $is_end = 0;
            }
        } else {
            $is_end = 0;
        }
        return $is_end;
    }


//    private function getUserBxblFrontIsEnd($user_id, $topicId, $batch_num, $module_type)
//    {
//        if (!$user_id) {
//            $user_id = $this->getUserId();
//        }
//        $where['user_id'] = $user_id;
//        $where['topicId'] = $topicId;
//        $where['batch_num'] = $batch_num;
//        $user_bxbl_question_info = Db::name('user_bxbl_question')->where($where)->order("id desc")->select();
//        if (!empty($user_bxbl_question_info)) {
//            $num = count($user_bxbl_question_info);
//            if ($num % 3 == 0) {
//                $last_tag_code = $user_bxbl_question_info[0]['tag_code'];
//
//                $where_detail['user_id'] = $user_id;
//                $where_detail['topicId'] = $topicId;
//                $where_detail['module_type'] = $module_type;
//                $where_detail['tag_code'] = $last_tag_code;
//                $where_detail['submodule_type'] = 1;
//                $answer_detail = Db::name('user_exam_detail')->where($where_detail)->select();
//                $answer_num = count($answer_detail);
//                if ($answer_num < 3) {
//                    Log::record("------" . __FUNCTION__ . "-------333");
//                    $bxbl_front_is_end = 0;
//                } else {
//                    Log::record("------" . __FUNCTION__ . "-------444");
//                    $bxbl_front_is_end = 1;
//                }
//            } else {
//                Log::record("------" . __FUNCTION__ . "-------555");
//                $bxbl_front_is_end = 0;
//            }
//        } else {
//            $bxbl_front_is_end = 0;
//        }
//        return $bxbl_front_is_end;
//    }


    private function getUserBxblTtqIsEnd($user_id, $topicId, $batch_num, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['batch_num'] = $batch_num;
        $return_data = Db::name('user_bxbl_ttq_question')->where($where)->find();
        if (!empty($return_data)) {
            $bxbl_ttq_is_end = $return_data['is_end'];
        } else {
            $bxbl_ttq_is_end = 0;
        }
        return $bxbl_ttq_is_end;
    }


    private function getUserDetectIsEnd($user_id, $topicId, $batch_num)
    {

        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['batch_num'] = $batch_num;
        $return_data = Db::name('user_bxbl_detect_question')->where($where)->find();
        if (!empty($return_data)) {
            $bxbl_detect_is_end = $return_data['is_end'];
        } else {
            $bxbl_detect_is_end = 0;
        }
        return $bxbl_detect_is_end;
    }


    public function getKnowledgeListByKmap()
    {
//        $question_service = new QuestionService();
//        $all_knowledgeList = $question_service->getKnowledgeList();
//        $kmap_code = config("kmap_code");
//        $module_type = config('xiance_module_type');
//        $knowledgeList = $all_knowledgeList[$kmap_code];
//        return $knowledgeList;

    }

    /**
     * 获取综合练习的下一道题的数据
     * @param type $module_type
     * @param type $used_type
     * @param type $topicId
     * @return type
     */
    public function getZhlxNextQuestion($module_type, $used_type = 2, $topicId)
    {
        $userInfo = session('userInfo');
        $user_id = $this->getUserId();
        $ttq_session_key = $user_id . "zhlx_num";
        $num = session($ttq_session_key);
        $question_list_key_val = '';
        $question_list = '';
        $api_gate_service = new ApiGateService();
        $question_list = $api_gate_service->getZhlxQuestionIds($topicId);//获取当前知识点下有没有做错的 如果有就是继续做

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
        $getUserHasAnsweredQuestions = $this->getUserHasAnsweredQuestions($user_id, $topicId, $module_type);//获取已经做过的题目
        //拿到用户做过的题的 问题id
        /*******************埋点循环比对判断当前答题列表的知识点和题id等于第一个知识并且做对的情况下清空同知识点下的所有试题id************************/
        /*******************埋点循环比对判断当前答题列表的知识点和题id等于第一个知识并且做对的情况下清空同知识点下的所有试题id end******************/
        $question_list = array_values(array_diff($question_list, $getUserHasAnsweredQuestions));
        $tag_code = '';
        $question_id = '';
        if (isset($question_list[0]) && isset($question_list_key_val[$question_list[0]])) {
            $tag_code = $question_list_key_val[$question_list[0]];
            $question_id = $question_list[0];
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
     * 获取堂堂清回答错误的问题
     * @param     $topicId
     * @param int $module_type
     * @param int $submodule_type
     *
     * @return mixed
     */
    public function getTTQError($topicId, $module_type = 2, $submodule_type = 2)
    {
        $user_id = $this->getUserId();
        $map['user_id'] = $user_id;
        $map['topicId'] = $topicId;
        $map['module_type'] = $module_type;
        $map['submodule_type'] = $submodule_type;
        $map['is_right'] = 0;
        $result = Db::name('user_exam_detail')->where($map)->order("id desc")->paginate(1000);//可能以后会用到分页，暂时全取出来
        $page = $result->render();
        $returnData["data"] = $result;
        $returnData["page"] = $page;
        return $returnData;

    }

    /**
     * 获取用户提交的答案是否正确
     * @param type $answer_content
     * @return type
     */
    public function getAnswerRight($answer_content)
    {
        $user_id = $this->getUserId();
        $is_right = 0;
        foreach ($answer_content as $key => $q_info) {
            $question_id = $q_info['question_id'];
            $return_info = $this->compare_question($question_id, $q_info);
            $is_right = $return_info['is_right'];
            $return_data['is_right'] = $is_right;
        }
        return $is_right;
    }

    public function getQuestionsByIds($question_arr_id = [])
    {
        $return = [];
        if ($question_arr_id) {
            foreach ($question_arr_id as $v) {
                $return[$v] = $this->getQuestionById($v);
            }
        }
        return $return;
    }

    public function allUserId()
    {
        $uid = Db::name('user')->column('username', 'id');
        return $uid;
    }

    public function getUseranswerExamDetail($user_id = 0, $module_type, $topicId, $limit)
    {
        $where['topicId'] = $topicId;
        if ($user_id) $where['user_id'] = $user_id;
        if ($module_type) $where['module_type'] = $module_type;
        $join = [
            ['user user', 'detail.user_id=user.id'],
        ];
        $data = Db::name('user_exam_detail')->alias('detail')->join($join)->where($where)->field('user.username,detail.*')->paginate($limit);
        $list = $data->toArray();
        $question_arr_id = [];
        $kmap_code = config('kmap_code');  //知识图谱
        if (isset($list['data']) && is_array($list['data'])) {
            foreach ($list['data'] as $k => $v) {
                $question_arr_id[] = $v['question_id'];
                $getKnowlegeCode = $this->getKnowlegeCode($kmap_code, $v['tag_code']);
                $list['data'][$k]['tag_code_title'] = $getKnowlegeCode['name'];
            }
        }
        $questionContent = $this->getQuestionsByIds($question_arr_id);
        $allUserId = $this->allUserId();
        $data = [
            'list' => $list,
            'page' => $data->render(),
            'question_content' => $questionContent,
            'alluserId' => $allUserId
        ];
        return $data;
    }

    public function getUseranswerExamDetailExport($user_id = 0, $module_type, $topicId)
    {
        $exclude = [
            'id',
            'user_id',
            'topicId',
            'tag_code',
            'module_type',
            'submodule_type',
            'used_type',
            'user_answer_base64',
            'is_view_answer',
            'right_answer',
            'is_view_analyze'
        ];
        $arr = [
            'username' => '用户名',
            'question_content' => '题目',
        ];
        $structure = StructureExport::getStructure(config('database.prefix') . 'user_exam_detail', $exclude);
        $structure = $arr + $structure;
        $where = ['topicId' => $topicId];
        if ($user_id) $where['user_id'] = $user_id;
        if ($module_type) $where['module_type'] = $module_type;
        $join = [
            ['user user', 'detail.user_id=user.id'],
        ];
        $data = Db::name('user_exam_detail')->alias('detail')->join($join)->where($where)->field('user.username,detail.*')->select();
        $question_arr_id = [];
        if ($data) {
            foreach ($data as $k => $v) {
                $question_arr_id[] = $v['question_id'];
            }
        }
        $questionContent = $this->getQuestionsByIds($question_arr_id);
        $return = [
            'list' => $data,
            'structure' => $structure,
            'question_content' => $questionContent
        ];
        return $return;
    }

    /**
     * 获取堂堂清待回答的知识点 数组
     */
    public function getTtqKnowledgeQuestionArr($topicId)
    {
        $TtqQuestionService = new TtqQuestionService();
        $getTtqQuestions = $TtqQuestionService->getTtqQuestions($topicId);//拿到所有待做题列表
        $data = [];
        $kmap_code = config('kmap_code');  //知识图谱
        foreach ($getTtqQuestions['tag_codes'] as $k => $v) {
            $data[$v]['question_id'][] = $getTtqQuestions['question_ids'][$k];
            $data[$v]['count'] = count($data[$v]['question_id']);
            if (isset($data[$v]['knowlegecode']) == false || $data[$v]['knowlegecode'] == false) $data[$v]['knowlegecode'] = $this->getKnowlegeCode($kmap_code, $v); //拿到知识点名称
        }
        return $data;
    }


    /**
     * 边学边练全部正确
     * @param $topicId
     * @param $tag_code
     *
     * @return bool
     */
    public function isAllRight($topicId, $tag_code)
    {
        $isAllRight = true;
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['tag_code'] = $tag_code;
        $where['module_type'] = 2;
        $where['submodule_type'] = 1;

        $result = Db::name('user_exam_detail')->where($where)->where("is_right=0 or is_view_analyze=1")->fetchSql(false)->select();
        if ($result) {
            $isAllRight = false;
        }

        return $isAllRight;
    }

    /**
     * 获取正答率
     * @param $question_id 试题id
     * @param $topicIds 专题
     * @param null $user_ids 用户ids(传入数组是为一个班级的学生id）
     * @return array
     */
    function getRightAnswerPercent($question_id,$topicIds,$user_ids=null)
    {

        $where=[];
        if($question_id)
        {
            $where["question_id"]=$question_id;
        }
        if($topicIds)
        {
            $where["topicId"]=array("in",$topicIds);
        }

        if($user_ids)
        {
            $where["user_id"]=array("in",$user_ids);
        }

        $rightResult = Db::name('user_exam_detail')
            ->where($where)
            //fetchSql(true)
            ->where("is_right=1 and  is_view_answer=0")
            ->count();

        //die($rightResult);
        $totalResult = Db::name('user_exam_detail')->where($where)->count();

        return [$rightResult,$totalResult];

    }


    /**
     * 获取正答率 (禁删)
     * @param $question_id 试题id
     * @param $topicId 专题id
     * @param $class_id 班级id
     * @param null $user_id 用户id
     * @return array
     */
    function getRightAnswerPercentForTest($question_id,$topicId,$class_id,$user_id=null)
    {

        $where=[];
        if($question_id)
        {
            $where["question_id"]=$question_id;
        }
        if($topicId)
        {
            $where["topicId"]=$topicId;
        }

        if($class_id)
        {
            $userids=$this->getUserIdsOfClassForTest($class_id);
            if($userids)
            {
                $where["user_id"]=array("in",$userids);
            }else
            {
                return [0,0];
            }

        }

        if($user_id)
        {
            $where["user_id"]=$user_id;
        }

        $rightResult = Db::name('user_exam_detail')->where($where)->where("is_right=1 and  is_view_answer=0")->count();
        $totalResult = Db::name('user_exam_detail')->where($where)->count();

        return [$rightResult,$totalResult];

    }

    /**
     * 获取用户的正答率
     * @param $question_id 试题id
     * @param $topicId 专题id(可为数组)
     * @param $user_id 用户id
     * @return array
     */
    function getUserRightAnswerPercent($question_id,$topicId,$user_id)
    {

        $where=[];
        if($question_id)
        {
            $where["question_id"]=$question_id;
        }
        if($topicId)
        {
            if(is_array($topicId))
            {
                $where['topicId'] = array("in",$topicId);
            }else
            {
                $where['topicId'] = $topicId;
            }
        }

        if($user_id)
        {
            $where["user_id"]=$user_id;
        }

        $rightResult = Db::name('user_exam_detail')->where($where)->where("is_right=1 and   is_view_answer=0")->count();
        $totalResult = Db::name('user_exam_detail')->where($where)->count();

        return [$rightResult,$totalResult];

    }

    /**
     * 获取班级错题 （用于ErrorQuestion)
     * @param $user_ids 班级学生的id
     * @param $topicIds 专题id
     * @param null $module_type 模块id
     * @param bool $isAll 是否分页
     * @param int $pageSize 分页大小
     * @param null $param 附加参数
     * @return array
     */
    public function getHasAnsweredQuestions($user_ids, $topicIds,$module_type=null,$isAll=true,$pageSize=15,$param=null)
    {
        $return_arr=[];
        $where=[];
        if($user_ids)
        {
            $where['user_id'] = array("in",$user_ids);
        }else
        {
            return ["data"=>[],"page"=>"","total"=>0];
        }
        if($topicIds)
        {
            $where['topicId'] = array("in",$topicIds);
        }

        if($module_type)
        {
            $where['module_type'] = $module_type;
        }

        if($isAll)
        {
            $answeredInfo = Db::name('user_exam_detail')
                ->distinct(true)
                ->field("question_id")
                ->group("question_id")
                ->where($where)
                ->where("is_right=0 or is_view_answer=1")
                ->select();
            $page="";
            $total=count($answeredInfo);
        }else
        {
            $totalResult = Db::name('user_exam_detail')
                ->distinct(true)
                ->field("question_id")
                ->group("question_id")
                ->where($where)
                ->where("is_right=0 or is_view_answer=1")
                ->select();

            $total=count($totalResult);
            $answeredInfo=Db::name('user_exam_detail')->distinct(true)->field("question_id")
                ->where($where)
                ->where("is_right=0 or is_view_answer=1")
                //->fetchSql(true)
                ->group("question_id")
                ->paginate($pageSize,true,$param);
            $page=$answeredInfo->render();
        }


        $question_service = new QuestionService();
        //$kmap_code = config("kmap_code");
        $knowledgeService=new KnowledgeService();
        foreach ($answeredInfo as $key => $val) {
//            $return_arr[$key]['question_id'] = $val['question_id'];
            $return_info = array();
            $api_gate_service =new ApiGateService();
            $return_info = $api_gate_service->getQuestionById($val['question_id'],$val['topicId']);
            $tag_name="";
            $exam_question=$question_service->getHasAnsweredQuestionByQuestionId($val['question_id']);
            if($exam_question)
            {
                $topicId=$exam_question["topicId"];
                $tag_code=$exam_question["tag_code"];
                $knowledge_v2_service = new KnowledgeV2Service();
                $tag=$knowledge_v2_service->getKnowledgeByCode($tag_code,$topicId);
                $tag_name=$tag["tag_name"];
            }
            $return_info['tag_name'] = $tag_name;
            $return_arr[] = $return_info;
        }
        $returnData=["data"=>$return_arr,"page"=>$page,"total"=>$total];
        return $returnData;
    }


    /**
     * 获取班级错题 （用于ErrorManagement)
     * @param null $user_id 班级学生的id
     * @param $topicId 专题id
     * @param null $class_id 模块id
     * @param null $module_type
     * @param bool $isAll
     * @param int $pageSize
     * @param null $param
     * @return array
     */
    public function getClassErrorQuestion($user_id = null, $topicId, $class_id=null,$module_type=null,$isAll=true,$pageSize=15,$param=null)
    {

        $return_arr = array();
        $where=[];
        if($user_id)
        {
            $where['user_id'] = $user_id;
        }
        if($topicId)
        {
            $where['topicId'] = $topicId;
        }
        if($class_id)
        {
            $userids=$this->getUserIdsOfClassForTest($class_id);
            if($userids)
            {
                $where["user_id"]=array("in",$userids);
            }else
            {
                return ["data"=>[],"page"=>"","total"=>0];
            }
        }
        if($module_type)
        {
            $where['module_type'] = $module_type;
        }

        if($isAll)
        {
            $answeredInfo = Db::name('user_exam_detail')->distinct(true)->field("question_id")->group("question_id")->where($where)->where("is_right=0 or is_view_answer=1")->select();
            $page="";
            $total=count($answeredInfo);
        }else
        {
            $totalResult = Db::name('user_exam_detail')->distinct(true)->field("question_id")->group("question_id")->where($where)->where("is_right=0 or is_view_answer=1")->select();
            $total=count($totalResult);
            $answeredInfo=Db::name('user_exam_detail')->distinct(true)->field("question_id")
                ->where($where)
                ->where("is_right=0 or is_view_answer=1")
                // ->fetchSql(true)
                ->group("question_id")
                ->paginate($pageSize,true,$param);
            $page=$answeredInfo->render();
        }


        $question_service = new QuestionService();
        //$kmap_code = config("kmap_code");
        $knowledgeService=new KnowledgeService();
        foreach ($answeredInfo as $key => $val) {
//            $return_arr[$key]['question_id'] = $val['question_id'];
            $return_info = array();
            $api_gate_service = new ApiGateService();
            $return_info = $api_gate_service->getQuestionById($val['question_id'],$topicId);
            $tag_name="";
            $exam_question=$question_service->getHasAnsweredQuestionByQuestionId($val['question_id']);
            if($exam_question)
            {
                $topicId=$exam_question["topicId"];
                $tag_code=$exam_question["tag_code"];
                $knowledge_v2_service = new KnowledgeV2Service();
                $tag=$knowledge_v2_service->getKnowledgeByCode($tag_code,$topicId);
                $tag_name=$tag["tag_name"];
            }
            $return_info['tag_name'] = $tag_name;
            $return_arr[] = $return_info;
        }
        $returnData=["data"=>$return_arr,"page"=>$page,"total"=>$total];
        return $returnData;
    }


    /**
     * 获取用户答过的试题id
     * @param $id
     * @param $user_id
     * @return array|mixed
     */
    public function getHasAnsweredQuestionByQuestionId($id)
    {
        $map["question_id"]=$id;
        $val = Db::name('user_exam_detail')->fetchSql(false)->where($map)->order('id desc')->find();
        $question_service = new QuestionService();
        //$kmap_code = config("kmap_code");
        $knowledgeService=new KnowledgeService();
        $topicId=$val["topicId"];
        //$kmap_code=$topicService->getKmapCodeByTopicId($topicId);
        $api_gate_service =new ApiGateService();
        $return_info = $api_gate_service->getQuestionById($val['question_id'],$topicId);
        $return_info['tag_code'] = $val['tag_code'];
        $return_info['module_type'] = $val['module_type'];
        $return_info['right_answer'] = $val['right_answer'];
        $return_info['user_answer'] = $val['user_answer'];
        $return_info['stime'] = $val['stime'];
        $return_info['ctime'] = $val['ctime'];
        //$tag = $question_service->getKnowlegeCode($kmap_code, $val['tag_code']);
        $knowledge_v2_service = new KnowledgeV2Service();
        $tag=$knowledge_v2_service->getKnowledgeByCode($val["tag_code"]);
        $tag_name="";
        if($tag)
        {
            $tag_name=$tag["tag_name"];
        }
        $return_info['tag_name'] = $tag_name;
        $return_info['is_view_analyze'] = $val["is_view_analyze"];
        $return_info['is_view_answer'] = $val["is_view_answer"];
        $return_info['right_answer_base64'] = $val['right_answer_base64'];
        $return_info['topicId'] = $val['topicId'];
        // $return_info['user_answer_base64'] = $val['user_answer_base64'];
        $userAnswerBase64Arr = [];
        if ($val['user_answer_base64']) {
            $userAnswerBase64Arr = explode("@@@", $val['user_answer_base64']);
        }

        $return_info['user_answer_base64'] = $userAnswerBase64Arr;


        $return_info['is_right'] = $val['is_right'];

        return $return_info;
    }

    /**
     * 获取用户答过的试题id
     * @param $id
     * @return array|mixed
     */
    public function getHasAnsweredQuestionByExamId($id)
    {
        $val = Db::name('user_exam_detail')->find($id);
        $question_service = new QuestionService();
        //$kmap_code = config("kmap_code");
        $knowledgeService=new KnowledgeService();
        $topicId=$val["topicId"];
        //$kmap_code=$topicService->getKmapCodeByTopicId($topicId);
        $api_gate_service = new ApiGateService();
        $return_info = $api_gate_service->getQuestionById($val['question_id'],$topicId);
        $return_info['tag_code'] = $val['tag_code'];
        $return_info['module_type'] = $val['module_type'];
        $return_info['right_answer'] = $val['right_answer'];
        $return_info['user_answer'] = $val['user_answer'];
        $return_info['stime'] = $val['stime'];
        $return_info['ctime'] = $val['ctime'];
        //$tag = $question_service->getKnowlegeCode($kmap_code, $val['tag_code']);
        $knowledge_v2_service =new KnowledgeV2Service();
        $tag=$knowledge_v2_service->getKnowledgeByCode($val["tag_code"]);
        $tag_name="";
        if($tag)
        {
            $tag_name=$tag["tag_name"];
        }
        $return_info['tag_name'] = $tag_name;
        $return_info['is_view_analyze'] = $val["is_view_analyze"];
        $return_info['is_view_answer'] = $val["is_view_answer"];
        $return_info['right_answer_base64'] = $val['right_answer_base64'];
        $return_info['topicId'] = $val['topicId'];
        // $return_info['user_answer_base64'] = $val['user_answer_base64'];
        $userAnswerBase64Arr = [];
        if ($val['user_answer_base64']) {
            $userAnswerBase64Arr = explode("@@@", $val['user_answer_base64']);
        }

        $return_info['user_answer_base64'] = $userAnswerBase64Arr;


        $return_info['is_right'] = $val['is_right'];

        return $return_info;
    }


    /**
     * 获取本题对错的学生ids(用于ErrorQuestion)
     * @param $question_id 试题id
     * @param $user_ids 学生ids
     * @return array
     */
    public function getRightAndWrongUserIds($question_id,$user_ids)
    {

        if($user_ids)
        {
            $where['user_id'] = array("in",$user_ids);

        }
        if($question_id)
        {
            $where['question_id'] = $question_id;
        }
        $result = Db::name('user_exam_detail')->where($where)->select();
        $right=$wrong=[];

        foreach ($result as $item)
        {
            if($item["is_right"]==0 ||  $item["is_view_answer"]==1)
            {
                $wrong[]=$item["user_id"];
            }else
            {
                $right[]=$item["user_id"];
            }
        }

        return ["right"=>$right,"wrong"=>$wrong];
    }

    /**
     * 获取本题对错的学生ids(用于ErrorManagement)
     * @param $question_id
     * @param $topicId
     * @param $class_id
     * @return array
     */
    public function getRightAndWrongUserIdsForTest($question_id,$topicId,$class_id)
    {
        if($topicId)
        {
            $where['topicId'] = $topicId;
        }
        if($class_id)
        {
            $userids=$this->getUserIdsOfClassForTest($class_id);
            $where["user_id"]=array("in",$userids);
        }
        if($question_id)
        {
            $where['question_id'] = $question_id;
        }
        $result = Db::name('user_exam_detail')->where($where)->select();
        $right=$wrong=[];

        foreach ($result as $item)
        {
            if($item["is_right"]==0 ||  $item["is_view_answer"]==1)
            {
                $wrong[]=$item["user_id"];
            }else
            {
                $right[]=$item["user_id"];
            }
        }

        return ["right"=>$right,"wrong"=>$wrong];
    }

    /**
     * 获取班级学生的id(用于ErrorManagement)
     * @param $userName
     * @return array
     */
    function getUserIdsOfClassForTest($userName)
    {
        $userids=[];
        $userService=new UserService();
        $result=$userService->getUser($userName,true);
        foreach($result["data"] as $item)
        {
            $userids[]=$item["id"];
        }
        return $userids;
    }

    /**
     * 添加错题修正
     * @param $data
     * @return int|string
     */
    public function addErrorCorrection($data){
        return Db::name('error_correction')->insert($data);
    }
    /**
     * 获取用户的id
     * @param $ids
     * @return array
     */
    function getExamQuestionDetailByIds($ids)
    {
        $where["id"]=array("in",$ids);
        $return_arr = array();
        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();
        $question_service=new QuestionService();
        $knowledgeService=new KnowledgeService();
        foreach ($answeredInfo as $key => $val) {
//            $return_arr[$key]['question_id'] = $val['question_id'];
            $return_info = array();
            $api_gate_service = new ApiGateService();
            $return_info = $api_gate_service->getQuestionById($val['question_id'],$val['topicId']);
            $return_info['tag_code'] = $val['tag_code'];
            $return_info['module_type'] = $val['module_type'];
            $return_info['right_answer'] = $val['right_answer'];
            $return_info['user_answer'] = $val['user_answer'];
            $return_info['stime'] = $val['stime'];
            $return_info['ctime'] = $val['ctime'];
            /* $tag = $question_service->getKnowlegeCode($kmap_code, $val['tag_code']);
             $return_info['tag_name'] = $tag["name"];*/
            $knowledge_v2_service = new KnowledgeV2Service();
            $tag=$knowledge_v2_service->getKnowledgeByCode($val["tag_code"]);
            $tag_name="";
            if($tag)
            {
                $tag_name=$tag["tag_name"];
            }
            $return_info['tag_name'] = $tag_name;
            $return_info['is_view_analyze'] = $val["is_view_analyze"];
            $return_info['is_view_answer'] = $val["is_view_answer"];
            $return_info['exam_detail_id'] = $val["id"];
            $return_info['topicId'] = $val["topicId"];
            $return_info['user_id'] = $val["user_id"];


            $return_info['right_answer_base64'] = $val['right_answer_base64'];

            // $return_info['user_answer_base64'] = $val['user_answer_base64'];

            $userAnswerBase64Arr = [];
            if ($val['user_answer_base64']) {
                $userAnswerBase64Arr = explode("@@@", $val['user_answer_base64']);

            }

            $return_info['user_answer_base64'] = $userAnswerBase64Arr;


            $return_info['is_right'] = $val['is_right'];
            $return_arr[] = $return_info;
//            $return_arr[$key]['is_right'] = $val['is_right'];
        }
        return $return_arr;
    }


    /**
     * @param $id  ct_user_exam_detail主键ID。
     * @param $ability   能力值。
     */
    public function updateUserExamDetail($id,$ability)
    {
        $where['id'] = $id;
        $data['ability'] = $ability;
        Db::name('user_exam_detail')->where($where)->update($data);

    }



    /**
     * 获取 L2 先行测试的下一个试题.
     * @param $topicId
     * @param $knowledge
     * @param $module
     * @param $used_type
     */
    public function getL2XianceNextQuestion($topicId, $tag_code, $module_type, $used_type)
    {
        Log::info("---00000000000------getXianceNextQuestion---------getQuestionsByKnowledge-----");
        $xiance_module_type = config('xiance_module_type');

        $question_v2_service = new BaseQuestionV2Service();
        $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $xiance_module_type);
        Log::info("---00000000000------getXianceNextQuestion---question_list--".json_encode($questions_list));
        $questions_id_arr = array();
        foreach ($questions_list as $key => $val) {
            $questions_id_arr[] = $val['id'];
        }
        $hasAnsweredQuestionsId = $this->getUserHasAnsweredQuestions('', $topicId, $module_type,$tag_code);
        $not_answered_questionsId = array_diff($questions_id_arr, $hasAnsweredQuestionsId);
        $not_answered_questionsId_arr = array_merge($not_answered_questionsId, array());
        if (empty($not_answered_questionsId_arr)) {
            $num = count($questions_id_arr);
            $return_data['error'] = "题量不够出问题了,算法已推出,并且用户已做" . $num . "道题";
            Log::info("---111111------");
            Log::info($return_data['error']);
            Log::info("---111111------");
        } else {
            //老的执行代码
            $next_question_id = $not_answered_questionsId_arr[0];

            //新的获取试题ID方式。
            $extraQuestions = array();
            $i = 0;
            foreach ($questions_list as $key => $val) {
                if (!in_array($val['id'], $hasAnsweredQuestionsId)) {
                    $extraQuestions[$i]["id"] = $val['id'];
                    if((int) $val['difficulty'] >9)
                    {
                        $val['difficulty']  = 1;
                    }
                    $extraQuestions[$i]["difficulty"] = $val['difficulty'];
                    $i++;
                }
            }


//              正确代码
//            if (!empty($extraQuestions)) {
//                //调用算法筛题.
//                $algoLogic = new AlgoLogic();
//                $question_ids = $algoLogic->chooseQuestionsByAlgo("", $tag_code, $extraQuestions, $topicId,$module_type);
//                if (empty($question_ids)) {
//                    exit("算法错误---------算法未返回知识点.");
//                }
//            } else {
//                exit("知识点取不到题,有BUG");
//            }
//            $next_question_id = $question_ids[0];


            //临时代码：

            $next_question_id = $extraQuestions[0]['id'];

            Log::info("---00000000000------getXianceNextQuestion----");
            Log::info("---next_question_id------".$next_question_id);
            Log::info("---00000000000-----getXianceNextQuestion-");
            //根据ID获取试题.
            Log::record("------before-------getQuestionById");

            $api_gate_service = new ApiGateService();

            $return_data = $api_gate_service->getQuestionById($next_question_id,$topicId);
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
     * @param $tag_code
     * @param $module_type
     * @param $used_type
     */
    public function getL2BxblNextQuestion($user_id, $tag_code, $module_type, $used_type, $topicId)
    {
        $extraQuestions = $this->getL2BxblQuestions($user_id, $tag_code, $module_type, $used_type, $topicId);
        $next_question_id = $extraQuestions[0];
//        $next_question_id = "5820cfd114fef96fe66d7ee2";
//        $next_question_id ="5820cfd114fef96fe66d7ee2";
        Log::record("------before----getBxblNextQuestion---getQuestionById");

        $question_v2_service =new BaseQuestionV2Service();

        $api_gate_service = new ApiGateService();
        $return_data = $api_gate_service->getQuestionById($next_question_id,$topicId);
        ///////////////////////////
        $log_service = new logService();
        $msg='会员id:'.$this->getUserId().'取题时间:'. date('Y-m-d H:i:s');
        $log_service::sendMessage('info',__METHOD__."取题###试题ID为: $next_question_id -------".$msg);
        ///////////////////////////
        return $return_data;
    }

    /**
     * 获取用户 L2 边学边练要做的题.
     * @param $user_id
     * @param $tag_code
     * @param $module_type
     * @param $used_type
     * @param $topicId
     * @return mixed
     */
    public function getL2BxblQuestions($user_id, $tag_code, $module_type, $used_type, $topicId)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        //用户已经做过的题.
        $bxbl_module_type = config('l2_bxbl_module_type');
        $hasAnsweredQuestionsId = $this->getUserHasAnsweredQuestions($user_id, $topicId, $bxbl_module_type);

        $question_v2_service = new BaseQuestionV2Service();

        $gaoxiao_module_type = config('gaoxiao_module_type');

        $question_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $gaoxiao_module_type);


        $extraQuestions = array();
        $i = 0;
        foreach ($question_list as $key => $val) {
            if (!in_array($val['id'], $hasAnsweredQuestionsId)) {
                $extraQuestions[$i]["id"] = $val['id'];
                if(!$val['difficulty'])
                {
                    $val['difficulty'] =1;
                }
                $extraQuestions[$i]["difficulty"] = $val['difficulty'];
                $i++;
            }
        }
        if (!empty($extraQuestions)) {
            foreach ($extraQuestions as $k=>$v) {
                if($k<10)
                {
                    $newExtraQuestions[] = $v;
                }else{
                    break;
                }
            }

            //调用算法筛题.
            $module_type = config("gaoxiao_module_type");

            $algoLogic = new AlgoLogic();
            $question_ids = $algoLogic->chooseQuestionsByAlgo("", $tag_code, $newExtraQuestions, $topicId,$module_type);
            if (empty($question_ids)) {
                exit("算法错误---------算法未返回知识点.");
            }
        } else {
            exit("知识点取不到题,有BUG");
        }
        return $question_ids;
    }



    /**
     * 随后放到summer 的service虾面。
     * 提交试题
     */                               
    public function submitSummerQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type, $submodule_type = 0, $isViewAnalyze = 0,$grandson_module_type = 0,$stage_code=1011)
    {
        $user_id = $this->getUserId();
        $return_data = array(
            'isSuccess' => 1,
            'is_right' => 0
        );
        $getQuestionTime = session("getQuestionTime");//获取取题时间
        $summer_question_service = new SummerQuestionService();

        $question_v2_service = new BaseQuestionV2Service();
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);

        foreach ($answer_content as $key => $q_info) {
            $question_id = $q_info['question_id'];
            try{
//                if($topicId>=9000)
//                {
                    $return_info = $this->compare_question($question_id, $q_info,"v2");
//                }else{
//                    $return_info = $this->compare_question($question_id, $q_info,"v1");
//                }
            }catch (Exception $e)
            {
                log::record( $e->getMessage());
                $return_info['is_right'] = 0;
                $return_data['right_answer'] = "";
                $return_data['user_answer'] ="";
            }

            $is_right = $return_info['is_right'];
            $right_answer = $return_info['right_answer'];
            $user_answer = $return_info['user_answer'];
            $isViewAnswer = 0;
            if(isset($q_info['is_view_answer']))
            {
                $isViewAnswer = $q_info['is_view_answer'];
                if($isViewAnswer==1)
                {
                    $is_right  = 0;
                }
            }

            $isViewAnalyze = 0;
            if (isset($q_info['is_view_analyze'])) $isViewAnalyze = $q_info['is_view_analyze'];
            $api_gate_service = new ApiGateService();
            $question_info = $api_gate_service->getQuestionById($question_id,$topicId);
            
            Log::record("------after-------insertUserExamInfo");
            //根据试题信息取出试题的难易度.
            $diffculty = $question_info['difficulty'];
            $estimates_time = $question_info['estimates_time'];
            if(!$diffculty)
            {
                $diffculty = config('default_diffculty');
            }

            $algoSummerLogic = new \service\algo\SummerAlgoLogic();
            $question_arr = array(
                'diff'=>$diffculty,
                'answer'=>$is_right
            );
            $questions[] = $question_arr;
            Log::record("-----------submitSummerQuestion-------stage_code---$stage_code----");
            $return_ability_info = $algoSummerLogic->updateAbility($user_id, $tag_code, $questions, $used_type, $topicId, $module_type,$submodule_type,$grandson_module_type,$stage_code);

            $ability = $return_ability_info['ability'];
            
            //记录进入数据库,并记录入缓存中.
            Log::record("------before-------insertUserExamInfo");
            $detail_id = $this->insertSummerUserExamInfo($topicId, $question_id, $return_info, $module_type, $used_type, $tag_code, $submodule_type, $isViewAnswer, $isViewAnalyze, $getQuestionTime,$grandson_module_type,$ability,$diffculty,$estimates_time);

            $summer_user_service = new SummerUserService();

            $summer_user_service->updateUserMasteryTagCode($topicId,$batch_num,$module_type,$submodule_type,$tag_code,$ability,$grandson_module_type);


            Log::record(__METHOD__."----0000000-------$user_id,$topicId,$module_type,$submodule_type,$tag_code,$question_id,$is_right");


            $this->updateUserExamChapter($user_id,$topicId,$module_type,$submodule_type,$tag_code,$question_id,$is_right);

            $topic_v2_service = new TopicV2Service();
            $topic_v2_service->getChapterForTagCode($topicId,$tag_code);

            Log::record("------after-------updateAbility");

            //添加数据到能力明细表
            if($question_info && $question_info['factors']){
                foreach ($question_info['factors'] as $k => $v) {
                    $this->insertFactorDetail($user_id,$topicId,$module_type,$submodule_type,$tag_code,$question_id,$is_right,$v['code'],$detail_id);
                }
            }

            //更新用户的LOG.
            $this->updateUserExamActionLog("", $topicId, $module_type, $question_id);
            $return_data['is_right'] = $is_right;
            ///////////////////////////
            $log_service = new logService();
            $msg='会员id:'.$this->getUserId().'答题时间:'. date('Y-m-d H:i:s');
            $log_service::sendMessage('info',__METHOD__."答题###试题ID为: $question_id -------".$msg);
            ///////////////////////////
        }






        return $return_data;
    }

    //插入数据到能力值表
    public function insertFactorDetail($user_id,$topicId,$module_type,$submodule_type,$tag_code,$question_id,$is_right,$factor,$detail_id){
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['module_type'] = $module_type;
        $data['submodule_type'] = $submodule_type;
        $data['tag_code'] = $tag_code;
        $data['question_id'] = $question_id;
        $data['is_right'] = $is_right;
        $data['factor'] = $factor;
        $data['detail_id'] = $detail_id;
        $data['ctime'] = time();
        Db::name('user_factor')->insert($data);
    }

    /**
     * 更新用户做题章节。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $submodule_type
     * @param $tag_code
     * @param $question_id
     * @param $is_right
     */
    public function updateUserExamChapter($user_id,$topicId,$module_type,$submodule_type,$tag_code,$question_id,$is_right)
    {
        log::record("submodule_type-----".$submodule_type);

        log::record("question_id-----".$question_id);


        if($user_id)
        {
            $user_id = $this->getUserId();
        }
        log::info(__METHOD__." 传旨参数－ $user_id,$topicId,$module_type,$submodule_type,$tag_code,$question_id,$is_right");
        $topic_v2_service = new TopicV2Service();
        $chapter_list = $topic_v2_service->getChapterForTagCode( $topicId,$tag_code);

        foreach ($chapter_list as $key =>$val) {
            $where['user_id']  = $user_id;
            $where['topicId']  = $topicId;
            $where['tag_code'] = $tag_code;
            $where['module_type'] = $module_type;
            $where['submodule_type'] = $submodule_type;
            $where['question_id'] =$question_id;

            log::info(__METHOD__."-----111-------");
            $user_chapter_info = Db::name('user_question_relation_chapter')->where($where)->find();
            if(empty($user_chapter_info))
            {

                $data['user_id'] = $user_id;
                $data['topicId'] = $topicId;
                $data['tag_code'] = $tag_code;
                $data['is_right'] = $is_right;
                $data['module_type'] = $module_type;
                $data['submodule_type'] = $submodule_type;
                $data['chapter_code'] = $val;
                $data['question_id'] = $question_id;
                log::info(__METHOD__."-----2222-------");

                Db::name('user_question_relation_chapter')->insert($data);
            }
        }

    }




    /**
     * 插入试题数据.
     */
    private function insertSummerUserExamInfo($topicId, $question_id, $return_info, $module_type, $used_type, $tag_code, $submodule_type, $isViewAnswer = 0, $isViewAnalyze = 0, $getQuestionTime = 0,$grandson_module_type,$ability=-1,$diffculty=0,$estimates_time=0)
    {

        $summer_question_service = new SummerQuestionService();
        $batch_num = $summer_question_service->getBatchNum($topicId,$module_type);

        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['question_id'] = $question_id;
        $where['module_type'] = $module_type;
        $where['batch_num'] = $batch_num;
        $where['submodule_type'] = $submodule_type;
        $where['tag_code'] = $tag_code;
        $isHasAnswered = Db::name('user_exam_detail')->where($where)->find();


        Log::record(__METHOD__."-----11111-----");


        if ($isHasAnswered) {
            Log::record(__METHOD__."-----22222-----");

            return $isHasAnswered['id'];
        } else {

            $is_right = $return_info['is_right'];
            //如果查看过答案，也是错的
            if ($isViewAnswer == 1) {
                $is_right = 0;
            }
            $data = array(
                'user_id' => $user_id,
                'topicId' => $topicId,
                'batch_num' => $batch_num,
                'tag_code' => $tag_code,
                'module_type' => $module_type,
                'submodule_type' => $submodule_type,
                'grandson_module_type'=>$grandson_module_type,
                'used_type' => $used_type,
                'question_id' => $question_id,
                'right_answer' => $return_info['right_answer'],
                'user_answer' => $return_info['user_answer'],
                'right_answer_base64' => empty($return_info['right_answer_base64']) ? "" : $return_info['right_answer_base64'],
                'user_answer_base64' => empty($return_info['user_answer_base64']) ? "" : $return_info['user_answer_base64'],
                'is_right' => $is_right,
                'is_view_answer' => $isViewAnswer,
                'is_view_analyze' => $isViewAnalyze,
                'ctime' => time(),
                'ability' => $ability,
                'difficulty' => $diffculty,
                'estimates_time' => $estimates_time,
                'stime' => $getQuestionTime,//获取试题时间
                'course_id'=> session('userInfo.course_id'),
                'course_name'=>session('userInfo.course_name'),
                'section_id'=>session('userInfo.section_id'),
                'section_name'=>session('userInfo.section_name'),
                'class_id'=>session('userInfo.class_id'),
                'class_name'=>session('userInfo.class_name'),
                'spent_time'=> input('spent_time', 5)
            );

            Log::record(__METHOD__."-----3333-----");

            $insert_id = Db::name('user_exam_detail')->insert($data);
            $where_user_exam['user_id'] = $user_id;
            $where_user_exam['topicId'] = $topicId;
            $where_user_exam['batch_num'] = $batch_num;
            $where_user_exam['module_type'] = $module_type;
            $where_user_exam['tag_code'] = $tag_code;
            $where_user_exam['submodule_type'] = $submodule_type;
            $where_user_exam['grandson_module_type'] =$grandson_module_type;

            Log::record(__METHOD__."-----444-----");

            $user_exam_info = Db::name('user_exam')->where($where_user_exam)->find();


            if ($user_exam_info) {
                Log::record(__METHOD__."-----5555-----");

                //已有此数据,则进行错题喝和对题的数,还有时间的更新
                if ($is_right) {
                    $user_exam_data['right_num'] = $user_exam_info['right_num'] + 1;
                    $user_exam_data['utime'] = time();
                    Db::name('user_exam')->where($where_user_exam)->update($user_exam_data);
                } else {
                    $user_exam_data['wrong_num'] = $user_exam_info['wrong_num'] + 1;
                    $user_exam_data['utime'] = time();
                    Db::name('user_exam')->where($where_user_exam)->update($user_exam_data);
                }
            } else {
                $user_exam_data['user_id'] = $user_id;
                $user_exam_data['topicId'] = $topicId;
                $user_exam_data['module_type'] = $module_type;
                $user_exam_data['tag_code'] = $tag_code;
                $user_exam_data['ctime'] = time();
                $user_exam_data['batch_num'] = $batch_num;
                $user_exam_data['submodule_type'] = $submodule_type;
                $user_exam_data['grandson_module_type'] = $grandson_module_type;
                Log::record(__METHOD__."-----6666-----");


                //如果没有,则插入此数据.如果有自动加一
                if ($is_right) {
                    $user_exam_data['right_num'] = 1;
                } else {
                    $user_exam_data['wrong_num'] = 1;
                }
                Log::record(__METHOD__."-----7777-----");

                Db::name('user_exam')->insert($user_exam_data);
                Log::record(__METHOD__."-----88888-----");

//                echo  Db::name('user_exam')->getLastSql();
            }
            return $insert_id;
        }
    }




    /**
     * 提交试题
     */
    public function submitSpingQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type, $submodule_type = 0, $isViewAnalyze = 0,$is_update_ability=0)
    {
        $user_id = $this->getUserId();
        $return_data = array(
            'isSuccess' => 1,
            'is_right' => 0
        );
        $ability = null;
        $getQuestionTime = session("getQuestionTime");//获取取题时间
        foreach ($answer_content as $key => $q_info) {
            $question_id = $q_info['question_id'];

            $return_info = $this->compare_question($question_id, $q_info,"v2");

            $is_right = $return_info['is_right'];
            $right_answer = $return_info['right_answer'];
            $user_answer = $return_info['user_answer'];
//            $isViewAnswer = empty($q_info['is_view_answer']) ? 0 : 1;
            $isViewAnswer = 0;
            if(isset($q_info['is_view_answer']))
            {
                $isViewAnswer = $q_info['is_view_answer'];
                if($isViewAnswer==1)
                {
                    $is_right  = 0;
                }
            }
            $question_v2_service = new BaseQuestionV2Service();

            $isViewAnalyze = 0;
            if (isset($q_info['is_view_analyze'])) $isViewAnalyze = $q_info['is_view_analyze'];
          
            //根据试题信息取出试题的难易度.
            $question_info = $question_v2_service->getQuestionById($question_id);
            $diffculty = $question_info['difficulty'];
            $estimates_time = $question_info['estimates_time'];
            if(!$diffculty)
            {
                $diffculty = config('default_diffculty');
            }
            $summer_algo_logic = new SummerAlgoLogic();
            // 只有边学边练的时候,会更新能力值.
            Log::record("------before-------updateAbility");
            //调用算法的现行测试新接口后,都不用更新能力值了。都是取知识点的时候更新。
            if($is_update_ability)
            {
                $return_ability_info = $summer_algo_logic->updateAbilityForSpring($user_id, $tag_code, $diffculty, $is_right, $used_type, $topicId, $module_type);
                $ability = $return_ability_info["ability"];
            }

              //记录进入数据库,并记录入缓存中.
            Log::record("------before-------insertUserExamInfo");
            $this->insertUserExamInfo($topicId, $question_id, $return_info, $module_type, $used_type, $tag_code, $submodule_type, $isViewAnswer, $isViewAnalyze, $getQuestionTime,$ability,$diffculty,$estimates_time);
            Log::record("------after-------insertUserExamInfo");
            Log::record("------after-------updateAbility");
            
            //更新用户的LOG.
            $this->updateUserExamActionLog("", $topicId, $module_type, $question_id);
            $return_data['is_right'] = $is_right;
            ///////////////////////////
            $log_service = new logService();
            $msg='会员id:'.$this->getUserId().'答题时间:'. date('Y-m-d H:i:s');
            $log_service::sendMessage('info',__METHOD__."答题###试题ID为: $question_id -------".$msg);
            ///////////////////////////
        }
        return $return_data;
    }



    /**
    -     * @param $tag_code
    -     * @param $module_type
    -     * @param $used_type
    -     */
    public function getPreviewNextQuestion($user_id, $tag_code, $module_type, $used_type, $topicId)
    {
        $extraQuestions = $this->getPreviewQuestions($user_id, $tag_code, $module_type, $used_type, $topicId);
        $next_question_id = $extraQuestions[0];
//        $next_question_id = "5820cfd114fef96fe66d7ee2";
//        $next_question_id ="5820cfd114fef96fe66d7ee2";
        Log::record("------before----getBxblNextQuestion---getQuestionById");
        $question_v2_service = new BaseQuestionV2Service();
        $return_data = $question_v2_service->getQuestionById($next_question_id);
        ///////////////////////////
        $log_service = new logService();
        $msg='会员id:'.$this->getUserId().'取题时间:'. date('Y-m-d H:i:s');
        $log_service::sendMessage('info',__METHOD__."取题###试题ID为: $next_question_id -------".$msg);
        ///////////////////////////
        return $return_data;
    }

    /**
     * 获取用户 L2 边学边练要做的题.
     * @param $user_id
     * @param $tag_code
     * @param $module_type
     * @param $used_type
     * @param $topicId
     * @return mixed
     */
    public function getPreviewQuestions($user_id, $tag_code, $module_type, $used_type, $topicId)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        //用户已经做过的题.
        $bxbl_module_type = config('preview_class_module_type');
        $hasAnsweredQuestionsId = $this->getUserHasAnsweredQuestions($user_id, $topicId, $bxbl_module_type);

        $question_v2_service = new BaseQuestionV2Service();
        $gaoxiao_module_type= config("gaoxiao_module_type");
        $request= \think\Request::instance();
        Log::record("----调用本方法getPreviewQuestions的控制器----" . $request->controller());
        $question_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $gaoxiao_module_type,'',1);

        $extraQuestions = array();
        $i = 0;
        foreach ($question_list as $key => $val) {
            if (!in_array($val['id'], $hasAnsweredQuestionsId)) {
                $extraQuestions[$i]["id"] = $val['id'];
                if(!$val['difficulty'])
                {
                    $val['difficulty'] =1;
                }
                $extraQuestions[$i]["difficulty"] = $val['difficulty'];
                $i++;
            }
        }
        if (!empty($extraQuestions)) {
            foreach ($extraQuestions as $k=>$v) {
                if($k<10)
                {
                    $newExtraQuestions[] = $v;
                }else{
                    break;
                }
            }

            //调用算法筛题.
            $module_type = config("gaoxiao_module_type");
            $algoLogic = new AlgoLogic();
            $question_ids = $algoLogic->chooseQuestionsByAlgo("", $tag_code, $newExtraQuestions, $topicId,$module_type);
            if (empty($question_ids)) {
                exit("算法错误---------算法未返回此知识点：  $tag_code 的题。 .");
            }
        } else {
            $log_service = new logService();
            $log_service::sendMessage('error',__METHOD__."知识点：  $tag_code  知识点取不到题,有BUG");
            exit("知识点：  $tag_code  知识点取不到题,有BUG");
        }
        return $question_ids;
    }


    public function getUserXianceLastExamDetail($user_id, $topicId, $module_type)
    {

        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $user_exam_detail  = Db::name('user_exam_detail')->where($where)->order("id desc")->find();

        return $user_exam_detail;

    }




    /**
     * 随后放到summer 的service虾面。
     * 提交试题
     */
    public function submitQuestionForTest($topicId, $answer_content, $module_type, $tag_code, $used_type, $submodule_type = 0, $isViewAnalyze = 0,$grandson_module_type = 0)
    {
        $return_data = array(
            'isSuccess' => 1,
            'is_right' => 0
        );
        $summer_question_service = new SummerQuestionService();
        foreach ($answer_content as $key => $q_info) {
            $question_id = $q_info['question_id'];
            try{
//                if($topicId>=9000)
//                {
                    $return_info = $this->compare_question($question_id, $q_info,"v2");
//                }else{
//                    $return_info = $this->compare_question($question_id, $q_info,"v1");
//                }
            }catch (Exception $e)
            {
                log::record( $e->getMessage());
                $return_info['is_right'] = 0;
                $return_data['right_answer'] = "";
                $return_data['user_answer'] ="";
            }
            if (isset($return_info['check_user_answer']))
            {

                $check_user_answer = $return_info['check_user_answer'];
                $check_tiku_answer = $return_info['check_tiku_answer'];
            }


            $is_right = $return_info['is_right'];
            $right_answer = $return_info['right_answer'];
            $user_answer = $return_info['user_answer'];
            $isViewAnswer = 0;
            if(isset($q_info['is_view_answer']))
            {
                $isViewAnswer = $q_info['is_view_answer'];
                if($isViewAnswer==1)
                {
                    $is_right  = 0;
                }
            }
            $return_data['is_right'] = $is_right;
            $return_data['isSuccess'] = 1;
            if (isset($return_info['check_user_answer']))
            {
                $return_data['check_user_answer'] = $check_user_answer;
                $return_data['check_tiku_answer'] =$check_tiku_answer;
            }

            ///////////////////////////
        }
        return $return_data;
    }


    /**
     * 随后放到summer 的service虾面。
     * 提交试题
     */
    public function submitQuestionForNewTest($topicId, $answer_content, $module_type, $tag_code, $used_type, $submodule_type = 0, $isViewAnalyze = 0,$grandson_module_type = 0)
    {
        $return_data = array(
            'isSuccess' => 1,
            'is_right' => 0
        );
        $summer_question_service = new SummerQuestionService();
        foreach ($answer_content as $key => $q_info) {
            $question_id = $q_info['question_id'];
            try{
                $return_info = $this->compare_blank_question_for_test($question_id, $q_info,"v2");
            }catch (Exception $e)
            {
                log::record( $e->getMessage());
                $return_info['is_right'] = 0;
                $return_data['right_answer'] = "";
                $return_data['user_answer'] ="";
            }

            $is_right = $return_info['is_right'];
            $right_answer = $return_info['right_answer'];
            $user_answer = $return_info['user_answer'];
            $check_user_answer = $return_info['check_user_answer'];
            $check_tiku_answer = $return_info['check_tiku_answer'];
            $isViewAnswer = 0;
            if(isset($q_info['is_view_answer']))
            {
                $isViewAnswer = $q_info['is_view_answer'];
                if($isViewAnswer==1)
                {
                    $is_right  = 0;
                }
            }
            $return_data['is_right'] = $is_right;
            $return_info['data'] = $return_info;
            $return_data['isSuccess'] = 1;
            $return_data['check_user_answer'] = $check_user_answer;
            $return_data['check_tiku_answer'] =$check_tiku_answer;

            ///////////////////////////
        }
        return $return_data;
    }


    /**
     * 填空题答案判断.
     */
    private function compare_blank_question_for_test($question_id, $q_info,$version)
    {
        if($version=='v2')
        {
            $question_v2_service = new BaseQuestionV2Service();
            $question_info = $question_v2_service->getQuestionById($question_id);
        }else{
            $question_info = $this->getQuestionById($question_id);
        }
        //容错处理，如果填空题的答案给的不是数组格式的，则强制转成数组，并记下日志。
        if(is_array($question_info['answer']))
        {
            $right_answer = $question_info['answer'];
        }else{
            Log::error("此题： $question_id  ，是填空题，返回的答案不是array.");
            $log_service = new logService();
            $log_service::sendMessage('error',__METHOD__."此题： $question_id  ，是填空题，返回的答案不是array.");

            $right_answer = array();
        }

        $right_answer_base64 = $question_info['answer_base64'];
        $user_answer_arr = explode(";", $q_info['answer']);
        $is_right = 0;
        $is_right_arr = array();
        $user_answer_num = count($user_answer_arr);
        $right_answer_num = count($right_answer);
        Log::record("-------user_answer_num".$user_answer_num."-------right_answer_num----".$right_answer_num."------");

        $check_tiku_answer[] ="";
        $check_user_answer[] = "";

        if($user_answer_num==$right_answer_num)
        {
            Log::record("-------user_answer_num---right_answer_num---相等-----");
            foreach ($user_answer_arr as $key => $val) {
//                $val = str_replace("\, ", "", $val);
                if(is_array($right_answer))
                {
                    $answer_html = $right_answer[$key];
                    foreach ($answer_html as $kk => $v) {
                        $answer[$kk] = htmlspecialchars_decode($v);
                    }
                    $is_one_answer_right = 0;
                    foreach ( $answer as $k =>$v)
                    {
                        //大于小于做的特殊处理，并且把样式过滤掉了
                        $txt1 = $v;
                        $txt1 = preg_replace( '/(style=.+?[\'|"])|((width)=[\'"]+[0-9]+[\'"]+)|((height)=[\'"]+[0-9]+[\'"]+)/i', '' , $txt1);
                        $v = html_entity_decode($txt1);
                        $v = htmlspecialchars_decode($v,ENT_QUOTES); //解析单引号

                        //把空格全替换掉。
                        $val = str_replace(' ', '', $val);
                        $v = str_replace(' ', '', $v);

                        $val = str_replace('，', ',', $val);
                        $v = str_replace('，', ',', $v);

                        $origin_val = trim($val);
                        $origin_v = trim($v);

                        //将题库和前段的数据都做了 全角转半角的转化。已解决应半角全角问题，导致的答案判断错误问题。
//                        $val = Unicode::sbc2Dbc($val);
//                        $v = Unicode::sbc2Dbc($v);

                        //一定要在trim_tiankong_answer函数之前，调用checkLatexEqual方法。

                        $val = CheckAnswer::trim_tiankong_answer($origin_val);
                        $v = CheckAnswer::trim_tiankong_answer($origin_v);

                        $check_user_answer []= $val;
                        $check_tiku_answer [] =$v;

                        if($val===$v)
                        {
                            $is_one_answer_right = 1;
                            break;
                        }else{
                            $is_one_answer_right = CheckAnswer::checkLatexEqual($origin_val,$origin_v);
                            if($is_one_answer_right)
                            {
                                break;
                            }
                        }

                    }
                    $is_right_arr[] = $is_one_answer_right;
                }else{
                    Log::error("此题： $question_id  ，是填空题，返回的答案不是array.");
                    $log_service = new logService();
                    $log_service::sendMessage('error',__METHOD__."此题： $question_id  ，是填空题，返回的答案不是array.");
                    $is_right_arr[] = 0;
                }
            }
            if (!in_array(0, $is_right_arr)) {
                $is_right = 1;
            }
        }else{
            Log::record("-------user_answer_num---right_answer_num---不等-----");

            $is_right = 0;
            $log_service = new logService();
            $log_service::sendMessage('error',__METHOD__."试题ID: $question_id  内容不对,缺少答案。前段展示需要输入".$user_answer_num."个答案,而内容只有".$right_answer_num."个答案");
            Log::error("试题ID: $question_id 内容不对,缺少答案。前段展示需要输入".$user_answer_num."个答案,而内容只有".$right_answer_num."个答案");
        }


        $return_info['is_right'] = $is_right;
        $return_info['right_answer'] = json_encode($right_answer);
        $return_info['right_answer_base64'] = json_encode($right_answer_base64);
        $return_info['user_answer'] = $q_info['answer'];
        $return_info['user_answer_base64'] = $q_info['answer_base64'];
        $return_info['check_tiku_answer'] = $check_tiku_answer;
        $return_info['check_user_answer'] = $check_user_answer;
        return $return_info;
    }

    //获取布鲁姆值
    public function getBlmValue(&$blm_wd_key,$topicId='9016'){
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $factorList  = Db::name('user_factor')->where($where)->select();
        //获取维度
        $blm_wd_key = [];
        foreach ($factorList as $v) {
            if(!in_array($v['factor'], $blm_wd_key)){
                $blm_wd_key[] = $v['factor'];
            }
        }   
        //获取基础数据
        $data = [];
        if($factorList){
            foreach ($blm_wd_key as $k1 => $v1) {
                foreach ($factorList as $k2 => $v2) {
                    if($v2['factor'] == $v1){
                        $data[$v1]['total']++;
                        if($v2['is_right']==1){
                            $data[$v1]['is_right']++;
                        }
                    }   
                }
            }
        }
        //定义返回数据
        $return = [];
        if($data){
            foreach ($data as $k => $v) {
                if($v['total']){
                    $return[] = ($v['is_right']/$v['total'])*100;
                }else{
                    $return[] = 0;
                }
            }
        }
        return $return;
    }
}
