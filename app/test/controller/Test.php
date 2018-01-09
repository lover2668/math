<?php

namespace app\test\controller;

use think\Db;
use service\lib\CheckAnswer;
use service\services\KnowledgeV2Service;
use service\services\BaseQuestionV2Service;
class Test {

    function select_db() {
        $where = [];
        $where["is_right"] = 1;
        $questiones = Db::name('user_exam_detail')->field("question_id,right_answer,user_answer")->where($where)->select();
        foreach ($questiones as $key => $value) {
            $this->check_question($value);
        }
        echo '<br><hr>over';
    }

    function check_question($param) {
        $question_id= $param["question_id"];
        $right_answer = json_decode($param["right_answer"]);
        $user_answer_arr = explode(";", $param['user_answer']);
        $is_right = 0;
        $is_right_arr = array();
        $user_answer_num = count($user_answer_arr);
        $right_answer_num = count($right_answer);
        $check_tiku_answer[] = "";
        $check_user_answer[] = "";

        if ($user_answer_num == $right_answer_num) {
            foreach ($user_answer_arr as $key => $val) {
                if (is_array($right_answer)) {
                    $answer_html = $right_answer[$key];
                    foreach ($answer_html as $kk => $v) {
                        $answer[$kk] = htmlspecialchars_decode($v);
                    }
                    $is_one_answer_right = 0;
                    foreach ($answer as $k => $v) {
                        //大于小于做的特殊处理，并且把样式过滤掉了
                        $txt1 = $v;
                        $txt1 = preg_replace('/(style=.+?[\'|"])|((width)=[\'"]+[0-9]+[\'"]+)|((height)=[\'"]+[0-9]+[\'"]+)/i', '', $txt1);
                        $v = html_entity_decode($txt1);
                        $v = htmlspecialchars_decode($v, ENT_QUOTES); //解析单引号
                        //把空格全替换掉。
                        $val = str_replace(' ', '', $val);
                        $v = str_replace(' ', '', $v);

                        $val = str_replace('，', ',', $val);
                        $v = str_replace('，', ',', $v);

                        $val = trim($val);
                        $v = trim($v);

                        //将题库和前段的数据都做了 全角转半角的转化。已解决应半角全角问题，导致的答案判断错误问题。
//                        $val = Unicode::sbc2Dbc($val);
//                        $v = Unicode::sbc2Dbc($v);
                        $val = CheckAnswer::trim_tiankong_answer($val);
                        $v = CheckAnswer::trim_tiankong_answer($v);
                        $check_user_answer [] = $val;
                        $check_tiku_answer [] = $v;
                        if ($val === $v) {
                            $is_one_answer_right = 1;
                            break;
                        } else {
                            $is_one_answer_right = 0;
                        }
                    }
                    
                } elseif ($right_answer=$user_answer_arr) {
                    $is_one_answer_right = 1;
                }else {
                    var_dump("此题： $question_id  ，是填空题，返回的答案不是array.");
                    var_dump($right_answer,$user_answer_arr);
                    echo '<hr>';
                }
                echo $is_one_answer_right.'<hr>';
            }
            if (!in_array(0, $is_right_arr)) {
                $is_right = 1;
            }
        } else {
            var_dump("试题ID: $question_id 内容不对,缺少答案。前段展示需要输入" . $user_answer_num . "个答案,而内容只有" . $right_answer_num . "个答案");
            echo '<hr>';    
            
        }
    }

    function checkcodes($param=[]) {
        $param=['c310202','c210201','c200202','c200306' ];
        $knowledgeV2Service = new KnowledgeV2Service();
        $tag_info = $knowledgeV2Service->getKnowledgeListByCodes($param);
        var_dump($tag_info);
    }

    function checkQuestionIds($param=[]) {
        $param=['97179897083986938','589980b2f4aeb569992f06a1','5ef9d5b4-50e0-11e7-8c70-00163e1004d0'];
        $baseQuestionV2Service = new BaseQuestionV2Service();
        $info = $baseQuestionV2Service->getQuestionListByIdes($param);
        var_dump($info);
    }
}
