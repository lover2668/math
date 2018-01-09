<?php
namespace app\index\controller;

use  service\services\QuestionService;

class Errorbook extends Base
{

    public function index()
    {
        $topicid = input("topicId");
        $questionService = new QuestionService();
        $result = $questionService->getTTQError($topicid);
        $return_arr = array();
        $answeredInfo = $result["data"];
        foreach ($answeredInfo as $key => $val) {
            $return_info = $questionService->getQuestionById($val['question_id']);
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
        }
        $this->assign("list", $return_arr);
        $this->assign("total", count($return_arr));
        $this->assign("page", $result["page"]);
        return $this->fetch("ErrorBook/index");
    }


}
