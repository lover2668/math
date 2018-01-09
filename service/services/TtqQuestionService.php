<?php
namespace service\services;

use service\entity\User;
use service\org\util\TpString;
use think\Db;
use think\Log;
use service\algo\AlgoLogic;
use service\services\QuestionService;

class TtqQuestionService extends CommonService
{

    public function __construct()
    {

    }


    public function getTtqQuestions($topicId)
    {
        $user_id = $this->getUserId();
        $module_type = config('gaoxiao_module_type');
//        $batch_num = 1;
        $question_service = new QuestionService();
        $batch_num = $question_service->getTtqBatchNum($topicId,$module_type);

        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['batch_num'] = $batch_num;
        $used_type = 2;
        $question_ids = array();
        $return_data = Db::name('user_bxbl_ttq_question')->where($where)->find();
        //用户已经做过的题.
//        $hasAnsweredQuestionsId = $this->getUserHasAnsweredQuestions($user_id, $topicId, $module_type);
        $hasAnsweredQuestionsId = $question_service->getUserHasAnsweredQuestions('', $topicId, $module_type);
        $question_service = new QuestionService();
        $question_ids = array();
        $final_tag_code_info = array();
        $final_question_ids = array();

        if (empty($return_data)) {
            $tag_code_list = $this->getHasAnsweredTagCodeByBatchNum($topicId, $batch_num);

            $tag_code_arr = array();
            foreach ($tag_code_list as $key => $val) {
                $tag_code_arr[] = $val["tag_code"];
            }
            $tag_code_str = join("','", $tag_code_arr);
            $sql = "select tag_code from ct_user_exam_detail where user_id=$user_id and topicId='$topicId'  and module_type=$module_type and (is_right=0 or is_view_analyze = 1 )  and tag_code in ('" . $tag_code_str . "')";
            $return_question = Db::query($sql);
            //边学边练第一步的用户都对,并且也没看解析,即堂堂清不需要做题的时候.
            if(empty($return_question))
            {
                $this->insertBxblTtqQuestion("", $topicId, $final_question_ids, $final_tag_code_info, $batch_num);
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
                    $questions_list = $question_service->getQuestionsByKnowledge($tag_code, $module_type, $used_type);
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
                    $this->insertBxblTtqQuestion("", $topicId, $final_question_ids, $final_tag_code_info, $batch_num);
                }

            }



//            $question_ids = $this->getTtqQuestionByAlgo($topicId, $tag_new_num_info, $batch_num);
        } else {
            $question_total_ids = json_decode($return_data['question_ids']);
//            $return_question_ids = json_decode($return_data['question_ids']);
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






    public function getTtqQuestions_new($topicId)
    {
        $user_id = $this->getUserId();
        $module_type = config('gaoxiao_module_type');
//        $batch_num = 1;
        $question_service = new QuestionService();
        $batch_num = $question_service->getTtqBatchNum($topicId,$module_type);

        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['batch_num'] = $batch_num;
        $used_type = 2;
        $question_ids = array();
        $return_data = Db::name('user_bxbl_ttq_question')->where($where)->find();
        //用户已经做过的题.
//        $hasAnsweredQuestionsId = $this->getUserHasAnsweredQuestions($user_id, $topicId, $module_type);
        $hasAnsweredQuestionsId = $question_service->getUserHasAnsweredQuestions('', $topicId, $module_type);
        $question_service = new QuestionService();
        $question_ids = array();
        $final_tag_code_info = array();
        $final_question_ids = array();

        if (empty($return_data)) {

            $algoLogic = new AlgoLogic();


            $algoLogic->getTtqQuestion();
            $tag_code_list = $this->getHasAnsweredTagCodeByBatchNum($topicId, $batch_num);

            $tag_code_arr = array();
            foreach ($tag_code_list as $key => $val) {
                $tag_code_arr[] = $val["tag_code"];
            }
            $tag_code_str = join("','", $tag_code_arr);
            $sql = "select tag_code from ct_user_exam_detail where user_id=$user_id and topicId='$topicId'  and module_type=$module_type and (is_right=0 or is_view_analyze = 1 )  and tag_code in ('" . $tag_code_str . "')";
            $return_question = Db::query($sql);
            //边学边练第一步的用户都对,并且也没看解析,即堂堂清不需要做题的时候.
            if(empty($return_question))
            {
                $this->insertBxblTtqQuestion("", $topicId, $final_question_ids, $final_tag_code_info, $batch_num);
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
                    $questions_list = $question_service->getQuestionsByKnowledge($tag_code, $module_type, $used_type);
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
                    $this->insertBxblTtqQuestion("", $topicId, $final_question_ids, $final_tag_code_info, $batch_num);
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
     * 通过算法获取堂堂清要做的试题.
     * $tag_arr = array('tag_code':"cz1401","num":1);
     */
    public function getTtqQuestionByAlgo($topicId, $tag_arr, $batch_num)
    {
        $user_id = $this->getUserId();
        $question_service = new QuestionService();
        $module_type = config('gaoxiao_module_type');
        $used_type = 2;
        $hasAnsweredQuestionsId = $question_service->getUserHasAnsweredQuestions('', $topicId, $module_type);
        foreach ($tag_arr as $k => $v) {
            $tag_code = $v['tag_code'];
            $questions_list = $question_service->getQuestionsByKnowledge($tag_code, $module_type, $used_type);
            $questions_id_arr = array();
            foreach ($questions_list as $key => $val) {
                $questions_id_arr[] = $val['id'];
            }
            $not_answered_questionsId = array_diff($questions_id_arr, $hasAnsweredQuestionsId);
            $not_answered_questionsId_arr = array_merge($not_answered_questionsId, array());
            $count = count($not_answered_questionsId_arr);
            if ($count < $v['num']) {
                exit($tag_code . "题量不够");
            } else {
                for ($i = 0; $i < $v['num']; $i++) {
                    $tag_arr[$k]['question_ids'][] = $not_answered_questionsId_arr[$i];
                }
            }
        }

        $final_tag_codes = array();
        $final_question_ids = array();
        foreach ($tag_arr as $k => $v) {
            foreach ($v['question_ids'] as $kk => $val) {
                $final_question_ids[] = $val;
                $final_tag_codes[] = $v['tag_code'];
            }
        }

        $this->insertBxblTtqQuestion("", $topicId, $final_question_ids, $final_tag_codes, $batch_num);
        return $final_question_ids;
    }

    /**
     * 记录下堂堂清要做的题.
     * @param null $user_id
     * @param $topicId
     * @param array $question_ids
     * @param array $tag_codes
     */
    private function insertBxblTtqQuestion($user_id = null, $topicId, $question_ids = array(), $tag_codes = array(), $batch_num)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['question_ids'] = json_encode($question_ids);
        $data['tag_codes'] = json_encode($tag_codes);
        $data['batch_num'] = $batch_num;
        Db::name('user_bxbl_ttq_question')->insert($data);
    }


    /**
     * 获取一个某一个循环中的用户做过的知识点.
     */
    public function getHasAnsweredTagCodeByBatchNum($topicId, $batch_num)
    {
        $user_id = $this->getUserId();
        $where_user_bxbl_question['user_id'] = $user_id;
        $where_user_bxbl_question['topicId'] = $topicId;
        $where_user_bxbl_question['batch_num'] = $batch_num;
        $return_data = Db::name('user_bxbl_question')->where($where_user_bxbl_question)->field("tag_code")->select();
        return $return_data;

    }


    public function getTtqNextQuestion($topicId)
    {
        $question_service = new QuestionService();
        $extraQuestions = $this->getTtqQuestions($topicId);
        $question_ids = $extraQuestions['question_ids'];
        $tag_codes  = $extraQuestions['tag_codes'];
        if(empty($question_ids))
        {
            $question_list  =array();
            $tag_code = "";
            $this->updateUserBxblTtqStatus($topicId,1);
        }else{
            $next_question_id = $question_ids[0];
            $tag_code = $tag_codes[0];
            $question_list = $question_service->getQuestionById($next_question_id);
        }
        $return_data = array(
            "question_list"=>$question_list,
            "tag_code"=>$tag_code
        );
        return $return_data;
    }

    /**
     * 更新没个批次的循环的堂堂清的结束状态.
     * @param $topicId
     * @param $is_end
     * @throws \think\Exception
     */
    public function updateUserBxblTtqStatus($topicId,$is_end)
    {
        $question_service = new QuestionService();
        $module_type = config('gaoxiao_module_type');
        $user_id = $this->getUserId();
        $batch_num = $question_service->getBatchNum($topicId,$module_type);
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['batch_num'] = $batch_num;
        $data['is_end'] = $is_end;
        $data['etime'] = time();
        Db::name('user_bxbl_ttq_question')->where($where)->update($data);
    }


    /**
     * 堂堂清报告页的batch_num.
     * 获取当前的批量值,
     */
    public function getNowBatchNumForTtqReport($topicId)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] =$topicId;
        $return_info = Db::name('user_bxbl_ttq_question')->where($where)->order("id desc ")->find();
        $batch_num = $return_info['batch_num'];
        return  $batch_num;
    }



    /**
     * 获取用户堂堂清做的题.
     */
    public function getUserTtqAnsweredQuestions($topicId,$batch_num)
    {
        $module_type = config('gaoxiao_module_type');
        $question_service  = new QuestionService();
        $submodule_type = 2;
        $question_service = new QuestionService();
//        $knowledgeList = $this->getKnowledgeListByKmap();
        $user_id = $this->getUserId();
        $where_user_bxbl_question['user_id'] = $user_id;
        $where_user_bxbl_question['topicId'] = $topicId;
        $where_user_bxbl_question['batch_num'] = $batch_num;
        $return_user_bxbl_question = Db::name('user_bxbl_question')->where($where_user_bxbl_question)->select();
        $tag_code_arr  = array();
        foreach ($return_user_bxbl_question as $k=>$v) {
            $tag_code_arr[] = $v['tag_code'];
        }
        $tag_code_str = join("','", $tag_code_arr);
        $sql = "select * from ct_user_exam_detail where user_id=$user_id and topicId='$topicId'  and module_type=$module_type  and submodule_type=$submodule_type and tag_code in ('" . $tag_code_str . "')";
//        $sql = "select tag_code,question_id,is_right from ct_user_exam_detail where user_id=$user_id and topicId='$topicId'  and module_type=$module_type  and submodule_type=$submodule_type and tag_code in ('" . $tag_code_str . "')";

        $return_question = Db::query($sql);
        $tag_code_question = array();
        foreach ($return_question as $k => $val)
        {
            $tag_code = $val['tag_code'];
            $question['question_id'] = $val['question_id'];
            $question['is_right'] = $val['is_right'];
            $question['tag_code'] = $val['tag_code'];
            $return_info = array();
            $return_info = $question_service->getQuestionById($val['question_id']);
            $return_info['tag_code'] = $val['tag_code'];
            $return_info['module_type'] = $val['module_type'];
            $return_info['right_answer'] = $val['right_answer'];
            $return_info['user_answer'] = $val['user_answer'];
            $return_info['right_answer_base64'] = $val['right_answer_base64'];
            $userAnswerBase64Arr = [];
            if ($val['user_answer_base64']) {
                $userAnswerBase64Arr = explode("@@@", $val['user_answer_base64']);
            }
            $return_info['user_answer_base64'] = $userAnswerBase64Arr;
            $return_info['is_right'] = $val['is_right'];
            $return_arr[] = $return_info;
            $tag_code_question[$tag_code][] = $return_info;
        }
        return $tag_code_question;
    }

    /**
     * 获取堂堂清的知识点对错报告.
     * @param $topicId
     * @param $batch_num
     * @return array
     */
    public function getUserTtqAnswerReport($topicId,$batch_num)
    {
        $user_id = $this->getUserId();
        $where_user_bxbl_question['user_id'] = $user_id;
        $where_user_bxbl_question['topicId'] = $topicId;
        $where_user_bxbl_question['batch_num'] = $batch_num;
        $return_user_bxbl_question = Db::name('user_bxbl_question')->where($where_user_bxbl_question)->select();
        $tag_code_report = array();
        $question_service =  new QuestionService();
        $all_knowledgeList = $question_service->getKnowledgeList();
        $kmap_code = config("kmap_code");
        $module_type = config('xiance_module_type');
        $knowledgeList = $all_knowledgeList[$kmap_code];
        foreach ($return_user_bxbl_question as $k =>$v) {
            $tag_code = $v['tag_code'];
            $isAllRight=$question_service->isAllRight($topicId,$tag_code);
            if($isAllRight)
            {
                $arr['scale'] = 1;
                $arr['err_scale'] = 0;
            }else
            {
                $return_report   =  $this->getUserTtqAnsweredQuestion($topicId,$tag_code);
                $total_num = count($return_report);
                $right_num = 0;
                $error_num = 0;
                foreach ($return_report as $kk=>$vv) {
                    if($vv['is_right'])
                    {
                        $right_num++;
                    }else{
                        $error_num++;
                    }
                }

                $arr['scale'] = round($right_num/$total_num,2);
                $arr['err_scale'] = round($error_num/$total_num,2);
            }



            foreach ($knowledgeList as $key=> $knonwledge ) {
                if($knonwledge['code'] == $tag_code)
                {
                    $tag_name = $knonwledge['name'];
                }
            }
            $arr['tag_code'] = $tag_code;
            $arr['tag_name'] = $tag_name;
            $tag_code_report[] = $arr;
        }


        return $tag_code_report;
    }


    /**
     * 获得用户每个知识点的答题情况.
     */
    public function getUserTtqAnsweredQuestion($topicId,$tag_code)
    {
        $user_id  = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['tag_code'] = $tag_code;
        $where['module_type'] = 2;
        $where['submodule_type'] = 2;//堂堂清
        $return_user_bxbl_question = Db::name('user_exam_detail')->where($where)->field('tag_code,is_right')->select();
        return $return_user_bxbl_question;
    }

    /**
     * 是否做了堂堂清
     * @param $topicId
     * @param $batch_num
     *
     * @return bool
     */
   public function isDoTtq($topicId,$batch_num)
   {
        $isDoTtq=true;
        $user_id  = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['batch_num'] = $batch_num;
        $result=Db::name('user_bxbl_ttq_question')->where($where)->find();
       if($result&&$result["tag_codes"]=="[]"&&$result["question_ids"]=="[]"&&$result["is_end"]==1)
       {
           $isDoTtq=false;
       }

       return $isDoTtq;
   }






}
