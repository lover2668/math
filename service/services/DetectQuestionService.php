<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/12/1
 * Time: 下午5:32
 */

namespace service\services;

use service\entity\User;
use service\org\util\TpString;
use think\Db;
use think\Log;
use service\algo\AlgoLogic;
use service\services\QuestionService;
use service\log\LogService;

class DetectQuestionService extends CommonService
{

    public function __construct()
    {

    }


    public function getDetectNextQuestion($topicId)
    {
        $question_service = new QuestionService();
        $extraQuestions = $this->getDetectQuestions($topicId);

        $question_ids = $extraQuestions['question_ids'];
        $tag_codes  = $extraQuestions['tag_codes'];
        if(empty($question_ids))
        {
            $question_list  =array();
            $tag_code = "";
            $this->updateUserBxblDetectStatus($topicId,1);
        }else{
            $next_question_id = $question_ids[0];
//            $next_question_id = "58be4084f4aeb556245316dd";
            $tag_code = $tag_codes[0];
            $api_gate_service = new ApiGateService();

            $question_list = $api_gate_service->getQuestionById($next_question_id,$topicId);
             ///////////////////////////
            $log_service = new logService();
            $msg='会员id:'.$this->getUserId().'取题时间:'. date('Y-m-d H:i:s');
            $log_service::sendMessage('info',__METHOD__."取题###试题ID为: $next_question_id -------".$msg);
            ///////////////////////////
        }
        $return_data = array(
            "question_list"=>$question_list,
            "tag_code"=>$tag_code
        );
        return $return_data;
    }


    public function getDetectQuestions($topicId)
    {
        $user_id = $this->getUserId();
        $gaoxiao_module_type = config('gaoxiao_module_type');
        $bxbl_module_type = config('bxbl_module_type');
        $xuexi_module_type = config('xuexi_module_type');
//        $xuexi_module_type_moment = config('xuexi_module_type_moment');
        $question_service = new QuestionService();
        $batch_num = $question_service->getBatchNum($topicId,$bxbl_module_type);
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['batch_num'] = $batch_num;
        $used_type = "";
        $question_ids = array();
        $return_data = Db::name('user_bxbl_detect_question')->where($where)->find();
        //用户已经做过的题.
        $hasAnsweredQuestionsId = $question_service->getUserHasAnsweredQuestions('', $topicId, $bxbl_module_type);
        $question_service = new QuestionService();
        $question_ids = array();
        $final_tag_code_info = array();
        $final_question_ids = array();

        if (empty($return_data)) {
            $tag_code_list = $this->getHasAnsweredTagCodeByBatchNum($topicId, $batch_num,$bxbl_module_type);
            $tag_code_arr = array();
            foreach ($tag_code_list as $key => $val) {
                $tag_code_arr[] = $val["tag_code"];
            }
            $tag_code_str = join("','", $tag_code_arr);
            $sql = "select tag_code from ct_user_exam_detail where user_id=$user_id and topicId='$topicId'  and module_type=$bxbl_module_type and ( is_right=0  or  is_view_analyze = 1 )  and tag_code in ('" . $tag_code_str . "')";
            $return_question = Db::query($sql);

            //边学边练第一步的用户都对,并且也没看解析,即堂堂清不需要做题的时候.
            if(empty($return_question))
            {
                $this->insertDetectQuestion("", $topicId, $final_question_ids, $final_tag_code_info, $batch_num);
            }else{
                $total_tag_code = array();
                foreach ($return_question as $k => $v) {
                    $total_tag_code[] = $v['tag_code'];
                }
                $tag_num_info = array_count_values($total_tag_code);
                $tag_new_num_info = array();
                $i = 0;
                foreach ($tag_num_info as $kk => $vv) {
                    $tag_new_num_info[$i]['tag_code'] = $kk;
                    $tag_new_num_info[$i]['num'] = $vv;
                    $i++;
                }

                foreach ($tag_new_num_info as $k => $v) {
                    $tag_code = $v['tag_code'];
                    $api_gate_service= new ApiGateService();
                    $questions_list = $api_gate_service->getQuestionsByKnowledge($tag_code, $xuexi_module_type, $used_type,$topicId);

                    $questions_id_arr = array();
                    foreach ($questions_list as $key => $val) {
                        $questions_id_arr[] = $val['id'];
                    }
                    $not_answered_questionsId = array_diff($questions_id_arr, $hasAnsweredQuestionsId);
                    $not_answered_questionsId_arr = array_merge($not_answered_questionsId, array());
                    $count = count($not_answered_questionsId_arr);
                    if ($count < $v['num']) {
                        exit($tag_code . "题量不够");
                    }else {
                        for ($i = 0; $i < $v['num']; $i++) {
                            $tag_new_num_info[$k]['question_ids'][] = $not_answered_questionsId_arr[$i];
                        }
                    }
                }
                $j = 0;
                foreach ($tag_new_num_info as $k => $v) {
                    foreach ($v['question_ids'] as $kk => $val) {
                        $final_question_ids[] = $val;
                        $final_tag_code_info[] = $v['tag_code'];
                    }
                }
                $question_ids = $final_question_ids;
                if(!empty($question_ids)){
                    $this->insertDetectQuestion("", $topicId, $final_question_ids, $final_tag_code_info, $batch_num);
                }

            }
        } else {
            $question_total_ids = json_decode($return_data['question_ids']);
            foreach ($question_total_ids as $key => $val) {
                if (!in_array($val, $hasAnsweredQuestionsId)) {
                    $question_ids[] = $val;
                }
            }
            $tag_code_arr = json_decode($return_data['tag_codes']);
            foreach ($question_total_ids as $key =>$val)
            {
                if(in_array($val,$question_ids))
                {
                    $final_tag_code_info[] = $tag_code_arr[$key];
                }
            }
        }

        $return_arr = array(
            "question_ids"=>$question_ids,
            "tag_codes"=>$final_tag_code_info
        );
        return $return_arr;
    }


    /**
     * 获取一个某一个循环中的用户做过的知识点.
     * @param $topicId  专题ID
     * @param $batch_num    边学边学的批次数
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getHasAnsweredTagCodeByBatchNum($topicId, $batch_num,$module_type)
    {
        $user_id = $this->getUserId();
        $user_exam['user_id'] = $user_id;
        $user_exam['topicId'] = $topicId;
        $user_exam['batch_num'] = $batch_num;
        $user_exam['module_type'] = $module_type;
        $return_data = Db::name('user_exam')->where($user_exam)->field("tag_code")->select();
        return $return_data;

    }
    /**
     * 记录下堂堂清要做的题.
     * @param null $user_id
     * @param $topicId
     * @param array $question_ids
     * @param array $tag_codes
     */
    private function insertDetectQuestion($user_id = null, $topicId, $question_ids = array(), $tag_codes = array(), $batch_num)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['question_ids'] = json_encode($question_ids);
        $data['tag_codes'] = json_encode($tag_codes);
        $data['batch_num'] = $batch_num;
        Db::name('user_bxbl_detect_question')->insert($data);
    }


    /**
     * 更新每个批次的循环的学习监测的结束状态.
     * @param $topicId
     * @param $is_end
     * @throws \think\Exception
     */
    public function updateUserBxblDetectStatus($topicId,$is_end)
    {
        $question_service = new QuestionService();
        $gaoxiao_module_type = config('gaoxiao_module_type');
        $user_id = $this->getUserId();
        $batch_num = $question_service->getBatchNum($topicId,$gaoxiao_module_type);
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['batch_num'] = $batch_num;
        $data['is_end'] = $is_end;
        $data['etime'] = time();
        Db::name('user_bxbl_detect_question')->where($where)->update($data);
    }



}