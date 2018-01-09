<?php

namespace app\summer\controller;

use service\services\BaseQuestionV2Service;
use service\services\TopicV2Service;
use think\Cache;
use think\Request;
use service\services\summer\SummerUserService;
use service\services\KnowledgeV2Service;
use service\services\summer\SummerQuestionService;
use service\services\QuestionService;
use think\Log;
use app\index\controller\Base;
use service\org\util\Unicode;

class Test extends \think\Controller {

    protected function _initialize()
    {
        session('');
        // session_start();
    }

    public function getV2GetTopicList() {

        $topicV2_service = new TopicV2Service();
        $topic_list = $topicV2_service->getTopicList();

        var_dump($topic_list);
    }

    public function getV2GetTopicByTopicId() {
        $topicV2_service = new TopicV2Service();
        $topic_id = 9002;
        $topic_info = $topicV2_service->getTopicByTopicId($topic_id);

        var_dump($topic_info);
    }

    public function testChapter() {
        $topicV2_service = new TopicV2Service();
        $topicId = 9016;
        $tag_code = "c040202";
        $chapter_key = $topicV2_service->getChapterForTagCode($topicId, $tag_code);

        var_dump($chapter_key);
    }

    public function getQuestionByIdAnalyse1($question_id) {
        $return_data = $this->getQuestionById($question_id);
//        var_dump($return_data);
//        die;
////        if(isset()){
////            
////        }
        $return_data = $return_data['data'];
        $msg = '';
        if ($return_data == false) {
            $msg .= "接口返回数据为null<br />";
        } else {
            if (isset($return_data['id']) == false || $return_data['id'] == false) {
                $msg .= "错误==试题id为null<br />";
            }
            if (isset($return_data['content']) == false || $return_data['content'] == false) {
                $msg .= "错误==试题内容为null<br />";
            }
            if (isset($return_data['q_type']) == false || $return_data['q_type'] == false) {
                $msg .= "错误==试题类型null或为空<br />";
            }
            if (isset($return_data['answer']) == false || $return_data['answer'] == false) {
                $msg .= "错误==试题没有正确答案<br />";
            }
            if (isset($return_data['content']) && $return_data['q_type'] == 2 && !is_numeric(strpos(htmlspecialchars_decode($return_data['content']), '##$$##'))) {

                $preg = "/[_]+[1-9]*[_]+/";
                preg_match_all($preg, $return_data['content'], $result);
                $num1 = count($result[0]);
                $preg = "/##\\$\\$##/";
                preg_match_all($preg, $return_data['content'], $result);
                $num2 = count($result[0]);
                $num = $num1 + $num2;
                if ($num != 0) {
                    if (isset($return_data['answer'])) {
                        $answer_num = count($return_data['answer']);
                        if ($num != $answer_num) {
                            $msg .= "错误==填空题题目的答案数和题干中的特殊替换符号数量不符合,答案是: $answer_num  个,但题干中的替换符号数为: $num<br />";
                        }
                    } else {
                        $msg .= "错误==题目没有正确答案<br />";
                    }
                } else {
                    $msg .= "错误==填空题题目中没有包含填空符号 ##$$## 或者 ___*___<br />";
                }
            }
            if ((!isset($return_data['options']) || $return_data['options'] == null) && ($return_data['q_type'] == 1 || $return_data['q_type'] == 3)) {
                $msg .= "错误==选择题目没有选项<br />";
            } elseif (!is_array($return_data['options'])) {
                $msg .= "错误==选择题目选项格式不正确<br />";
            }

            if (isset($return_data['answer']) && (count($return_data['answer']) == 0)) {
                $msg .= "错误==题目没有正确答案<br />";
            }
            if (isset($return_data['analyze'])) {
                if (empty($return_data['analyze'])) {
                    $msg .= "分布解析为null<br />";
                } else {

                    foreach ($return_data['analyze'] as $k => $v) {
                        if (isset($v['content']) && empty($v['content'])) {
                            $msg .= "分布解析为null<br />";
                        } else {
                            
                        }
                    }
                }
            }

            if (isset($return_data['analyze']) && count($return_data['analyze']) <= 0) {
                $msg .= "错误==分布解析数据类型为空<br />";
            }
        }
        return $msg;
        //return $this->fetch('getquestionbyidanalyse',$data);
    }

    public function checkQuestion() {
        $topicService = new TopicV2Service();
        $request = Request::instance();
        $topic_id = $request->param("topic_id", 9002);
//        $key = "my_question:tag_codes";
//        $tag_listes = Cache::get($key);
//        if (!$tag_listes) {
//        $topicList = $topicService->getTopicList();
//        var_dump($topicList);die;
//        foreach ($topicList as $k => $v) {
//                $topic_info = $topicService->getKmapCodeList($v['id']);
        $topic_info = $topicService->getTopicByTopicId($topic_id);
//        var_dump($topic_info);
        foreach ($topic_info['kmap_code_list'] as $k => $val) {
            if ($k == 200) {
                $tag_codes = $topicService->getKmapInfoByKmapCode($val['kmap_code']);
//                var_dump($tag_codes);die;
                foreach ($tag_codes as $key => $value) {
                    $tag_listes[] = $value["tag_code"];
                }
            }
        }
//        }
//            Cache::set($key, $tag_listes, 3600 * 24);
//        }
        echo "tag_codes的数量 : " . count(array_unique($tag_listes)) . "<hr>";
//        var_dump(array_unique($tag_listes));die;
        $err_tag_code = [];
        foreach (array_unique($tag_listes) as $key => $value) {
//            var_dump($key);
            $tag_code = $value;
            $knowledge = $this->getQuestionsByKnowledge($tag_code);
            if ($knowledge["code"] == 200) {
                foreach ($knowledge["data"] as $k => $val) {
                    $question_ids[] = $val["id"];
                }
            } else {
                $err_tag_code[] = $tag_code;
            }
        }
        echo "err_tag_codes的数量 : " . count($err_tag_code) . "<br>";
        echo "err_tag_codes的错误原因 : 知识点没题<hr>";
        echo "tag_code分别是:<br>";
        foreach ($err_tag_code as $k) {
            echo $k . "<br>";
        }
        echo "<hr>";
        echo "question_ids的数量 : " . count(array_unique($question_ids)) . "<hr>";
        $ki = 0;
        $err_questions = [];
        foreach (array_unique($question_ids) as $key => $value) {
//            var_dump($value);die;
            $msg = $this->getQuestionByIdAnalyse1($value);
//            var_dump($question_msg);die;
            if ($msg) {
                $err_questions[$ki][] = $value;
                $err_questions[$ki][] = $msg;
            }
            $ki++;
        }
        echo "question_ids错误的数量 : " . count($err_questions) . "<hr>";
        echo "question_id和错误原因分别是:<br>";
        foreach ($err_questions as $k => $val) {
            var_dump($val);
            echo"<br>";
        }
    }

    public function getQuestionsByKnowledge($knowledge) {
        $key = "questionsByKnowledge:" . $knowledge;
        $return_data = Cache::get($key);
        $return_data = array();
        if (!$return_data) {
            $param['knowledge'] = $knowledge;
            //根据知识点获取试题.
//            $url = config("question_server_host") . "index/api/getQuestionsByKnowledge";
//            var_dump($url);
            $url = "http://input-math-t.51xonline.com/v2/api/getQuestionsByKnowledge";
            $return_data = rpc_request($url, $param);
            Cache::set($key, $return_data, 3600 * 24);
        }
        return $return_data;
    }

    public function getQuestionById($question_id) {
        $key = "question:" . $question_id;
//        $return_data = Cache::get($key);
//        $return_data = array();   //暂时不走缓存。
//        if (!$return_data) {
        $param['question_id'] = $question_id;
        $url = "http://input-math-t.51xonline.com/v2/api/getQuestionById";
        $return_data = rpc_request($url, $param);
//        var_dump($return_data);die;
//            Cache::set($key, $return_data, 3600 * 24);
//        }
        return $return_data;
    }

    public function test1() {
        $topicId = 9002;
        $module_type = 8;
        $submodule_type = 2;


        //获取用户已经掌握的知识点。
        $summer_user_service = new SummerUserService();
        $nodes = $summer_user_service->getUserMasteryTagCode("", $topicId, $module_type, $submodule_type);


        exit;
    }


    /**
     * 测试L1先行测试不嗯的题的数量问题。
     */
    public function checkQuestionNum()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");

        $topic_v2_service = new TopicV2Service();
        $kmap_code_list = $topic_v2_service->getBigKmapTagCodeList($topicId);

        $question_v2_service = new BaseQuestionV2Service();
        foreach ($kmap_code_list as $k => $v) {

            $xiance_module_type = config('xiance_module_type');

            $tag_code = $v['tag_code'];
            //先行测试必须有3道题。
            $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $xiance_module_type, 1);
            $question_ids = array();
            foreach ($questions_list as $k => $v) {

                $question_ids[] = $v['id'];
            }

            $num = count($questions_list);
            if ($num < 3) {
                echo "--- L1先行测试模块－－－－-知识点 $tag_code------－－－－－－小于3道，已有 $num 道,已有的试题ID为：" . json_encode($question_ids) . " <br>";
            }
        }
    }



    /**
     * 测试L1学习模块的题的数量问题。
     */
    public function checkOldStudyQuestionNum()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");

        $topic_v2_service = new TopicV2Service();
        $kmap_code_list = $topic_v2_service->getBigKmapTagCodeList($topicId);

        $question_v2_service = new BaseQuestionV2Service();
        foreach ($kmap_code_list as $k => $v) {


            $tag_code = $v['tag_code'];

            $base_module_type = 6;
            $gg_module_type = 7;
            //先行测试必须有3道题。
            // 先两道学习题，再2道练习题。
            $base_xuexi_questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $base_module_type, 2);
            $base_xuexi_question_ids = array();
            foreach ($base_xuexi_questions_list as $k => $v) {

                $base_xuexi_question_ids[] = $v['id'];
            }

            $num = count($base_xuexi_questions_list);
            if ($num < 4) {
                echo "---－－－－－基础模块－－－－－知识点 $tag_code------－学习题少于4道－－现有 $num 个 已有的试题ID为：" . json_encode($base_xuexi_question_ids) . " <br>";
            }

            $base_lianxi_questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $base_module_type, 1);
            $base_lianxi_questions_ids = array();
            foreach ($base_lianxi_questions_list as $k => $v) {

                $base_lianxi_questions_ids[] = $v['id'];
            }
            $num = count($base_lianxi_questions_list);
            if ($num < 4) {
                echo "---－－－－－基础模块－－－－－知识点 $tag_code------－测试题少于4道－－现有 $num 个 已有的试题ID为：" . json_encode($base_lianxi_questions_ids) . " <br>";
            }


            $gg_xuexi_questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $gg_module_type, 2);


            $gg_xuexi_questions_ids = array();
            foreach ($gg_xuexi_questions_list as $k => $v) {

                $gg_xuexi_questions_ids[] = $v['id'];
            }

            $num = count($gg_xuexi_questions_list);
            if ($num < 4) {
                echo "---－－－－－巩固模块－－－－－知识点 $tag_code------－学习题少于4道－－现有 $num  个 已有的试题ID为：" . json_encode($gg_xuexi_questions_ids) . " <br>";
            }

            $gg_lianxi_questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $gg_module_type, 1);

            $gg_lianxi_questions_ids = array();
            foreach ($gg_xuexi_questions_list as $k => $v) {
                $gg_lianxi_questions_ids[] = $v['id'];
            }


            $num = count($gg_lianxi_questions_list);
            if ($num < 4) {
                echo "---－－－－－巩固模块－－－－－知识点 $tag_code------－测试题少于4道－－现有 $num  个    已有的试题ID为：" . json_encode($gg_lianxi_questions_ids) . " <br>";
            }
        }
    }

    public function checkStudyQuestionNum()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");

        $topic_v2_service = new TopicV2Service();
        $kmap_code_list  =  $topic_v2_service->getBigKmapTagCodeList($topicId);

        $question_v2_service = new BaseQuestionV2Service();
        foreach ($kmap_code_list as $k=>$v) {


            $tag_code= $v['tag_code'];

            $base_module_type= 6;
            $gg_module_type = 7;
            //先行测试必须有3道题。
            // 先两道学习题，再2道练习题。
            $base_xuexi_questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $base_module_type);
            $base_xuexi_question_ids = array();
            foreach ($base_xuexi_questions_list as $k=>$v)
            {

                $base_xuexi_question_ids[] = $v['id'];
            }

            $num = count($base_xuexi_questions_list);
            if($num<8)
            {
                echo "---－－－－－基础模块－－－－－知识点 $tag_code------－学习题少于8道－－现有 $num 个 已有的试题ID为：".json_encode($base_xuexi_question_ids)." <br>";
            }

            $gg_xuexi_questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $gg_module_type);

            $gg_xuexi_questions_ids =array();
            foreach ($gg_xuexi_questions_list as $k=>$v)
            {

                $gg_xuexi_questions_ids[] = $v['id'];
            }

            $num = count($gg_xuexi_questions_list);
            if($num<8)
            {
                echo "---－－－－－巩固模块－－－－－知识点 $tag_code------－学习题少于8道－－现有 $num  个 已有的试题ID为：".json_encode($gg_xuexi_questions_ids)." <br>";
            }


        }





    }






    /**
     * 暂时不需要了。
     */
    public function checkStudyQuestionNumForDiffculty()
    {

        $request = Request::instance();
        $topicId = $request->param("topicId");

        $topic_v2_service = new TopicV2Service();
        $kmap_code_list = $topic_v2_service->getBigKmapTagCodeList($topicId);

        $question_v2_service = new BaseQuestionV2Service();
        foreach ($kmap_code_list as $k => $v) {
            $tag_code = $v['tag_code'];
            $base_module_type = 6;
            $gg_module_type = 7;
            $base_lianxi_questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $base_module_type, 1, 1);

            $base_lianxi_questions_ids = array();
            foreach ($base_lianxi_questions_list as $k => $v) {
                $base_lianxi_questions_ids[] = $v['id'];
            }
            $num = count($base_lianxi_questions_list);
            if ($num < 4) {
                echo "---－－－－－基础模块－－－－难度为1 的知识点 $tag_code---- --－测试题少于4道－－现有 $num 个 已有的试题ID为：" . json_encode($base_lianxi_questions_ids) . " <br>";
            }

            $base_lianxi_questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $base_module_type, 1, 2);

            $base_lianxi_questions_ids = array();
            foreach ($base_lianxi_questions_list as $k => $v) {
                $base_lianxi_questions_ids[] = $v['id'];
            }

            $num = count($base_lianxi_questions_list);
            if ($num < 4) {
                echo "---－－－－－基础模块－－－－难度为2 的知识点 $tag_code---- --－测试题少于4道－－现有 $num 个 已有的试题ID为：" . json_encode($base_lianxi_questions_ids) . " <br>";
            }


            $gg_lianxi_questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $gg_module_type, 2);

            $gg_lianxi_questions_ids = array();
            foreach ($base_lianxi_questions_list as $k => $v) {
                $gg_lianxi_questions_ids[] = $v['id'];
            }

            $num = count($gg_lianxi_questions_list);
            if ($num < 4) {
                echo "---－－－－－巩固模块－－－－－难度为2 的知识点 $tag_code------－测试题少于4道－－现有 $num 个 已有的试题ID为：" . json_encode($gg_lianxi_questions_ids) . " <br>";
            }

            $gg_lianxi_questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $gg_module_type, 3);
            $gg_lianxi_questions_ids = array();
            foreach ($base_lianxi_questions_list as $k => $v) {
                $gg_lianxi_questions_ids[] = $v['id'];
            }

            $num = count($gg_lianxi_questions_list);
            if ($num < 4) {
                echo "---－－－－－巩固模块－－－－－难度为3 的知识点 $tag_code------－测试题少于4道－－现有 $num 个  已有的试题ID为：" . json_encode($gg_lianxi_questions_ids) . " <br>";
            }
        }
    }






    /**
     * 检测预习课题量问题。
     */
    public function checkPreviewQuestionNum()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");

        $topic_v2_service = new TopicV2Service();
        $kmap_code = $topic_v2_service->getMainKmapCode($topicId);
        $tag_code_list = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);

        $question_total_id_list = array();
        $question_v2_service = new BaseQuestionV2Service();
        foreach ($tag_code_list as $k => $v) {
            $tag_code = $v['tag_code'];
            $module_type = 2;
            $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $module_type);
//            var_dump($questions_list);die;
            $questions_ids = array();
            foreach ($questions_list as $k => $v) {
                $questions_ids[] = $v['id'];
                $question_total_id_list[] =$v['id'];
            }
            $num = count($questions_list);
            if ($num < 3) {
                echo "---－－－－－预习模块－－－－知识点 $tag_code---- --－试题少于3道－－现有 $num 个 已有的试题ID为：" . json_encode($questions_ids) . " <br>";
            }else{
                echo "---－－－－－预习模块－－－－知识点 $tag_code---- --－试题大于3道－－现有 $num 个 已有的试题ID为：" . json_encode($questions_ids) . " <br>";

            }
        }
        echo count($question_total_id_list);

    }

    //L2题量
    public function checkL2QuestionNum() {

        $request = Request::instance();
        $topicId = $request->param("topicId");

        $topic_v2_service = new TopicV2Service();
        $topic_info = $topic_v2_service->getTopicByTopicId($topicId);
        $kamp_code_list = $topic_info["kmap_code_list"];
        $kmap_code = $kamp_code_list["220"]["kmap_code"];
        $tag_code_xuemiao = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);
        $question_v2_service = new BaseQuestionV2Service();
        $xiance_module_type = 1;
        $gaoxiao_module_type = 2;
        foreach ($tag_code_xuemiao as $k => $v) {
            $tag_code = $v['tag_code'];

            $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $xiance_module_type);
            $num = count($questions_list);
            if ($num < 1) {
                echo "---－－－－－L2先测模块---学苗线路图谱－－－－知识点 $tag_code---- --－试题少于1道 <br>";
            }
            $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $gaoxiao_module_type);
            $questions_ids = array();
            foreach ($questions_list as $k => $v) {
                $questions_ids[] = $v['id'];
            }
            $num = count($questions_list);
            if ($num < 3) {
                echo "---－－－－－L2高效模块---学苗线路图谱－－－－知识点 $tag_code---- --－试题少于3道－－现有 $num 个 已有的试题ID为：" . json_encode($questions_ids) . " <br>";
            }
        }
        $kmap_code = $kamp_code_list["230"]["kmap_code"];
        $tag_code_xuezhong = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);
        $question_v2_service = new BaseQuestionV2Service();
        foreach ($tag_code_xuezhong as $k => $v) {
            $tag_code = $v['tag_code'];
            $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $xiance_module_type);
            $num = count($questions_list);
            if ($num < 1) {
                echo "---－－－－－L2先测模块---学中线路图谱－－－－知识点 $tag_code---- --－试题少于1道 <br>";
            }
            $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $gaoxiao_module_type);
            $questions_ids = array();
            foreach ($questions_list as $k => $v) {
                $questions_ids[] = $v['id'];
            }
            $num = count($questions_list);
            if ($num < 3) {
                echo "---－－－－－L2高效模块---学中线路图谱－－－－知识点 $tag_code---- --－试题少于3道－－现有 $num 个 已有的试题ID为：" . json_encode($questions_ids) . " <br>";
            }
        }
        $kmap_code = $kamp_code_list["240"]["kmap_code"];
        $tag_code_xueba = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);
        $question_v2_service = new BaseQuestionV2Service();
        foreach ($tag_code_xueba as $k => $v) {
            $tag_code = $v['tag_code'];
            $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $xiance_module_type);
            $num = count($questions_list);
            if ($num < 1) {
                echo "---－－－－－L2先测模块---学霸线路图谱－－－－知识点 $tag_code---- --－试题少于1道 <br>";
            }
            $questions_list = $question_v2_service->getQuestionsByKnowledge($tag_code, $gaoxiao_module_type);
            $questions_ids = array();
            foreach ($questions_list as $k => $v) {
                $questions_ids[] = $v['id'];
            }
            $num = count($questions_list);
            if ($num < 3) {
                echo "---－－－－－L2高效模块---学霸线路图谱－－－－知识点 $tag_code---- --－试题少于3道－－现有 $num 个 已有的试题ID为：" . json_encode($questions_ids) . " <br>";
            }
        }
    }


    /**
     *  检测L2部分题库的问题。
     */
    public function checkL2question()
    {




    }



    public function  checkZhlxQuestionNum()
    {

        $request = Request::instance();
        $topicId = $request->param("topicId");

        $getZhlxQuestionIds =array();
        $topic_v2_service = new  TopicV2Service();
        $zhlx_kmap_code_list = $topic_v2_service->getZhlxKmapCodeList($topicId);
        $module_type = config('zonghe_module_type');
        $question_v2_service = new BaseQuestionV2Service();
        foreach ($zhlx_kmap_code_list as $k=>$v) {
            $getZhlxQuestionIds[] =  $question_v2_service->getQuestionsByKnowledge($v['tag_code'],$module_type);
        }

        var_dump($getZhlxQuestionIds);


    }


    public function checkQuestionInfo()
    {

        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
        $is_test = $request->param("is_test",0);
//        $user_id = $this->getUserId();

        $quesiton_id = $request->param("question_id");

        $type = 1;
        $this->assign("question_id",$quesiton_id);
        $this->assign('type',$type);
        $this->assign('is_test',$is_test);
        return $this->fetch("test/index");


    }



    public function checkLatexEqual()
    {
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $module_type = config('l1_module_type');
//        $user_id = $this->getUserId();

        $type=2;
        $quesiton_id = $request->param("question_id");
        $this->assign("question_id",$quesiton_id);
        $this->assign('type',$type);
        return $this->fetch("test/index");
    }




    public function getExamQuestions()
    {
        $request = Request::instance();
        $question_id = $request->param("question_id");
        $is_test = $request->param('is_test');
        if($is_test)
        {
            config("v2_question_server_host","http://test-input-math.51xonline.com/");
        }

//        $tag_code=session('tag_code');
//        $knowledge_v2_service =  new KnowledgeV2Service();
//        $tag_info =$knowledge_v2_service->getKnowledgeByCode($tag_code);
//        $tag_name  = $tag_info['tag_name'];
        $question_v2_service = new BaseQuestionV2Service();

        $question_list=$question_v2_service->getQuestionById($question_id);
        $return_data = array(
            "is_end" => 0,
            "question_list" => $question_list,
            "has_answered_questions" => array(),
            "tag_code" => "test",
            "tag_name"=>"接口未定义"
        );
        $return_data['right_scale'] = 0.6;
        $return_data['has_learedCode_scale'] = 0.6;

        echo json_encode($return_data);
    }




    /**
     * L1先行测试提交
     */
    public function submitQuestion()
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
        $used_type = 1;   //1 表示测试题,  2 表示练习题
        $question_service = new QuestionService();
        $submodule_type = 1;
        try {
            $isSuccess = $question_service->submitQuestionForTest($topicId, $answer_content, $module_type, $tag_code, $used_type,$submodule_type);

//            $isSuccess = $question_service->submitQuestionForNewTest($topicId, $answer_content, $module_type, $tag_code, $used_type,$submodule_type);

        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }
        echo json_encode($isSuccess);
    }



    /**
     * L1先行测试提交
     */
    public function submitLatexEqualQuestion()
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
        $used_type = 1;   //1 表示测试题,  2 表示练习题
        $question_service = new QuestionService();
        $submodule_type = 1;
        try {
//            $isSuccess = $question_service->submitQuestionForTest($topicId, $answer_content, $module_type, $tag_code, $used_type,$submodule_type);

            $isSuccess = $question_service->submitQuestionForNewTest($topicId, $answer_content, $module_type, $tag_code, $used_type,$submodule_type);

        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }
        echo json_encode($isSuccess);
    }





    public function test()
    {
        $a = "12,，，，：31，343,44,  ｛｛｛｛｛｛ {{{";
        $b = "12,31,343,44,。。。";
        $b = "12,，，，：31，343,44,［［［］］］］   [[[]]]]";

        var_dump($a);
        $a = Unicode::sbc2Dbc($a);

        echo "<br>";
        var_dump($a);
        exit;
        if($a===$b)
        {
            echo "<br>===<br>";
        }else{
            echo "<br> bu  deng yu  <br>";
        }

        var_dump($a);

        exit;


        $b = Unicode::dbc2Sbc($b);
        var_dump($b);


    }


}
