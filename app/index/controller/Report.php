<?php

namespace app\index\controller;

use think\Request;
use think\Log;
use service\services\KnowledgeService;
use service\services\UserService;
use service\services\KnowledgeV2Service;
use service\services\TopicV2Service;
use service\algo\SummerAlgoLogic;
use service\services\summer\SummerUserService;
use service\services\QuestionService;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use service\org\util\Page;
use app\index\controller\Base;
use service\algo\AlgoLogic;
use service\services\summer\SummerCindexService;
use service\services\ZhlxService;
use service\services\TopicService;
use service\services\ApiGateService;

class Report extends Common {

    public function test() {
        $request = Request::instance();
        $summerUserService = new SummerUserService();
        $topicId = $request->param("topicId");
        $user_info = $this->getUserInfo();
        $user_name = $user_info["username"];
        $user_id = $user_info["user_id"];
        $module_type = config("l1_module_type");
        $hasAnswerQuestions = $summerUserService->getUserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type);
        $this->assign("hasAnswerQuestions", $hasAnswerQuestions);
//        foreach ($hasAnswerQuestions as $key => $value) {
////            var_dump($value);
//            foreach ($value["analyze"] as $k => $val) {
//                 var_dump($val);
//            }
//           
//        }
//        die;
        return $this->fetch("preReport");
    }

    function reportCenter() {
        $request = Request::instance();
        $topic_id = $request->param("topicId");
        $user_info = $this->getUserInfo();
        $user_id = $user_info["user_id"];
        $xiance_module_type = config('xiance_module_type');
        $bxbl_module_type = config('bxbl_module_type');
        $zhonghe_module_type = config('zonghe_module_type');
        $mncs_module_type = config('mncs_module_type');
        $xiance_report_info = [];
        $bxbl_report_info = [];
        $jingsai_report_info = [];
        $mncs_report_info = [];

        $summer_user_service = new SummerUserService();
        $topicV2Service = new TopicV2Service();
        $topic_info = $topicV2Service->getTopicByTopicId($topic_id);
        $flow_id = $topic_info["flow_id"];
        //先测模块
        $is_end_xiance = $summer_user_service->getUserStep($topic_id, $user_id, $xiance_module_type);
        if ($is_end_xiance) {
            $xiance_report_info["url"] = url("preReport", ["topicId" => $topic_id, "user_id" => $user_id]);
            $xiance_report_info["time"] = date("Y-m-d H:i", $is_end_xiance["etime"]);
            $is_end_xiance = 1;
        } else {
            $is_end_xiance = 0;
        }
        // 边学边练模块
        $is_end_bxbl = $summer_user_service->getUserStep($topic_id, $user_id, $bxbl_module_type);
        if ($is_end_bxbl) {
            $bxbl_report_info["url"] = url("studyReport", ["topicId" => $topic_id, "user_id" => $user_id]);
            $bxbl_report_info["time"] = date("Y-m-d H:i", $is_end_bxbl["etime"]);
            $is_end_bxbl = 1;
        } else {
            $is_end_bxbl = 0;
        }
        // 竞赛拓展模块
        $is_end_jingsai = $summer_user_service->getUserStep($topic_id, $user_id, $zhonghe_module_type);
        if ($is_end_jingsai) {
            $jingsai_report_info["url"] = url("zhlxReport", ["topicId" => $topic_id, "user_id" => $user_id]);
            $jingsai_report_info["time"] = date("Y-m-d H:i", $is_end_jingsai["etime"]);
            $is_end_jingsai = 1;
        } else {
            $is_end_jingsai = 0;
        }
        // 模拟测试模块
        $is_end_mncs = $summer_user_service->getUserStep($topic_id, $user_id, $mncs_module_type);
        if ($is_end_mncs) {
            $mncs_report_info["url"] = url("mncsReport", ["topicId" => $topic_id, "user_id" => $user_id]);
            $mncs_report_info["time"] = date("Y-m-d H:i", $is_end_mncs["etime"]);
            $is_end_mncs = 1;
        } else {
            $is_end_mncs = 0;
        }
        $this->assign("flow_id", $flow_id);

        $this->assign("is_end_xiance", $is_end_xiance);
        $this->assign("xiance_report_info", $xiance_report_info);

        $this->assign("is_end_bxbl", $is_end_bxbl);
        $this->assign("bxbl_report_info", $bxbl_report_info);

        $this->assign("is_end_jingsai", $is_end_jingsai);
         $this->assign("jingsai_report_info", $jingsai_report_info);
        

        $this->assign("is_end_mncs", $is_end_mncs);
        $this->assign("mncs_report_info", $mncs_report_info);


        return $this->fetch("reportCenter");
    }

    /**
     * 生成二维码
     * @param $url
     */
    function qrCode() {
        $request = Request::instance();
        $url = $request->param("url");
        $url = urlsafe_b64decode($url);
        $qrCode = new QrCode($url);
        $qrCode->setSize(300);

// Set advanced options
        $qrCode->setWriterByName('png')
                ->setMargin(10)
                ->setEncoding('UTF-8')
                ->setErrorCorrectionLevel(ErrorCorrectionLevel::LOW)
                ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
                ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])
                //->setLabel('Scan the code', 16, __DIR__.'/../assets/noto_sans.otf', LabelAlignment::CENTER)
                // ->setLogoPath(__DIR__.'/../assets/symfony.png')
                ->setLogoWidth(150)
                ->setValidateResult(false);
        header('Content-Type: ' . $qrCode->getContentType());
        echo $qrCode->writeString();
    }

    public function spentTime($time) {
        $year = floor($time / 60 / 60 / 24 / 365);
        $time -= $year * 60 * 60 * 24 * 365;
        $month = floor($time / 60 / 60 / 24 / 30);
        $time -= $month * 60 * 60 * 24 * 30;
        $week = floor($time / 60 / 60 / 24 / 7);
        $time -= $week * 60 * 60 * 24 * 7;
        $day = floor($time / 60 / 60 / 24);
        $time -= $day * 60 * 60 * 24;
        $hour = floor($time / 60 / 60);
        $time -= $hour * 60 * 60;
        $minute = floor($time / 60);
        $time -= $minute * 60;
        $second = $time;

        $str = "";
        if ($hour) {
            $str .= ($hour >= 10 ? $hour : "0" . $hour) . "时";
        }
        if ($minute) {
            $str .= ($minute >= 10 ? $minute : "0" . $minute) . "分";
        }
        if ($second) {
            $str .= ($second >= 10 ? $second : "0" . $second) . "秒";
        } else {
            $str .= "00秒";
        }
        return $str;
    }

    /**
     * 先行测试报告页
     * @return mixed
     */
    public function preReport() {
//        error_reporting(E_ALL ^ E_NOTICE);

        $request = Request::instance();
        $algoLogic = new AlgoLogic();
        //$topicId=$this->getTopicId();
        $request = Request::instance();
        $topicId = $request->param("topicId");
        $user_id = $request->param("user_id");

        $topicService = new TopicService();
        $topic = $topicService->getTopicByTopicId($topicId);
        $topicNmae = $topic["topic_name"];
        $user_service = new UserService();
        //$question_service = new QuestionService();
//        $all_knowledgeList = $question_service->getKnowledgeList();
//        $kmap_code = config("kmap_code");
        $module_type = config('xiance_module_type');
//        $topic_service = new TopicService();
        $knowledge_service = new KnowledgeService();
//        $kmap_code = $topic_service->getKmapCodeByTopicId($topicId);
        $is_show_report = 0;
        $is_show_nextstep = 0;
        $api_gate_service = new ApiGateService();
        $knowledgeList = $api_gate_service->getKnowledgeListByTopicId($topicId);
        $weakElements = $algoLogic->getWeakElements($user_id, $topicId);
        $knowledge_v2_service = new KnowledgeV2Service();

        foreach ($knowledgeList as $key => $knowledge) {
            $tag_code = $knowledge['tag_code'];
            $tag_info = $knowledge_v2_service->getKnowledgeByCode($tag_code);
            $knowledgeList[$key]['tag_name'] = $tag_info['tag_name'];
            if (empty($weakElements)) {
                $knowledgeList[$key]['is_weak'] = 0;
            } else {
                if (in_array($tag_code, $weakElements)) {
                    $knowledgeList[$key]['is_weak'] = 1;
                } else {
                    $knowledgeList[$key]['is_weak'] = 0;
                }
            }
        }
        $need_toLearn_next_tag_name = "";
        if (empty($weakElements)) {
            $need_toLearn_next_tag_name = "";
        } else {
            $need_toLearn_next_tag_code = $weakElements[0];
            $knowledge_v2_service = new KnowledgeV2Service();
            foreach ($knowledgeList as $key => $val) {
                if ($val['tag_code'] == $need_toLearn_next_tag_code) {
                    $tag_info = $knowledge_v2_service->getKnowledgeByCode($val['tag_code']);
                    $need_toLearn_next_tag_name = $tag_info['tag_name'];
                }
            }
        }
        $total_knowledge_num = count($knowledgeList);  //总知识点数量.
        $weakElements_num = count($weakElements); //薄弱知识点数量.
        ///////////////////获取薄弱知识点的名字/////////////////////
        $weakElements_tag_name = [];
        //总的知识点 剔除为掌握的
        $new_knowledgeList = $knowledgeList;
        if ($weakElements && is_array($weakElements)) {
            $knowledgeService = new KnowledgeService();
            foreach ($weakElements as $k => $v) {
                $weakElements_tag_name[] = $knowledge_v2_service->getKnowledgeByCode($v);
                foreach ($new_knowledgeList as $key => $value) {
                    if ($value['tag_code'] == $v)
                        unset($new_knowledgeList[$key]); //剔除为掌握的
                }
            }
        }

        $this->assign("knowledgeList_tag_name", $new_knowledgeList); //已经掌握的知识点
        $this->assign("weakElements_tag_name", $weakElements_tag_name);
        /////////////////获取薄弱知识点的名字end//////////////////////
        $has_learned_num = $total_knowledge_num - $weakElements_num;  //已学会知识点

        //获取用户所有知识点的平均掌握情况.
        $average_ability = $user_service->getUserAverageAbility($user_id, $topicId, $module_type);
        //用户做过的试题信息.
        $has_answered_questions = $user_service->getUserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type);
//        $user_id = $this->getUserId();
        $xxcsIsAllRight = $user_service->getUserIsAllRight($topicId, $module_type, $user_id);
        $has_learned_percent = round(($has_learned_num / ($has_learned_num + $weakElements_num)) * 100); //掌握的百分比
        $this->assign("is_all_right", $xxcsIsAllRight);
        $accuracy = $user_service->getUserExamDetail($user_id, $topicId, $module_type);
        $this->assign("accuracy", $accuracy); //正确率
        $this->assign("has_learned_percent", $has_learned_percent);
//        $analyze = $has_answered_questions[0]['analyze'];
        $this->assign("total_knowledge_num", $total_knowledge_num);
        $this->assign("weakElements_num", $weakElements_num);
        $this->assign("has_learned_num", $has_learned_num);
        $this->assign("has_answered_questions", $has_answered_questions);
        $this->assign("tagInfo", json_encode($knowledgeList));
        $this->assign("need_toLearn_next_tag_name", $need_toLearn_next_tag_name);
        $this->assign("average_ability", $average_ability);
        $this->assign("topicId", $topicId);
        $this->assign("topic_name", $topicNmae);
        $this->assign('module_type', $module_type);
        $this->assign('is_show_report', $is_show_report);
        $this->assign('is_show_nextstep', $is_show_nextstep);


        //判断现行测试边学边练和竞赛扩展是否做完
        $userService = new UserService();
        $xianceStep = $userService->getUserStep($topicId, $user_id, config('xiance_module_type')); //边学边练
        $xiance_is_end = 0;
        $this->assign('xiance_is_end', 0);
        if (isset($xianceStep['is_end']) && $xianceStep['is_end'] == 1)
            $xiance_is_end = 1;
        $this->assign('xiance_is_end', $xiance_is_end);

        $bxblStep = $userService->getUserStep($topicId, $user_id, config('bxbl_module_type')); //边学边练
        $bxbl_is_end = 0;
        if (isset($bxblStep['is_end']) && $bxblStep['is_end'] == 1)
            $bxbl_is_end = 1;;
        $this->assign('bxbl_is_end', $bxbl_is_end);
        $zhlxStep = $userService->getUserStep($topicId, $user_id, config('zonghe_module_type')); //综合练习
        $zhlx_is_end = 0;
        if (isset($zhlxStep['is_end']) && $zhlxStep['is_end'] == 1)
            $zhlx_is_end = 1;;
        $this->assign('zhlx_is_end', $zhlx_is_end);
        return $this->fetch("index/preReport");
    }

    /**
     * 学情报告
     * @return mixed
     */
    public function studyReport() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $user_id = $request->param('user_id');
        $module_type = config('bxbl_module_type');
        $user_service = new UserService();
        $has_answered_questions = $user_service->getUserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type);
        $user_ability = $user_service->getUserAbility($user_id, $topicId, $module_type);
        $algoLogic = new AlgoLogic();
        $knowledge_service = new KnowledgeService();
        $api_gate_service = new ApiGateService();
        $knowledgeList = $api_gate_service->getKnowledgeListByTopicId($topicId);
        $weakElements = $algoLogic->getWeakElements($user_id, $topicId);
        $total_knowledge_num = count($knowledgeList);  //总知识点数量.
        $weakElements_num = count($weakElements); //薄弱知识点数量.
        $frist_has_learned_num = $total_knowledge_num - $weakElements_num;  //原本已掌握的知识点数量。
        $has_learned_weakElements = array();
        foreach ($user_ability as $key => $ability_num) {
            $ability_standard = config('ability_standard');
            if ($ability_num >= $ability_standard) {
                if (in_array($key, $weakElements)) {
                    $has_learned_weakElements[] = $key;
                }
            }
        }
        $has_learned_weakElements_num = count($has_learned_weakElements);   //通过学习,已经掌握的薄弱知识点。
        $not_learned_weakElements_num = $weakElements_num - $has_learned_weakElements_num;  //通过学习还未掌握的知识点。
        $scale = round(($total_knowledge_num - $not_learned_weakElements_num) / $total_knowledge_num, 2) * 100;  //知识点掌握率
        $tag_info = array();
        $tag_ability_report = array();
        $knowledge_v2_service = new KnowledgeV2Service();
        foreach ($user_ability as $key => $ability_num) {
            foreach ($knowledgeList as $k => $knonwledge) {
                if ($knonwledge["tag_code"] === $key) {
                    $tag_namearr = $knowledge_v2_service->getKnowledgeByCode($knonwledge["tag_code"]);
                    $tag_video_list = $tag_namearr['video'];
                    $tag_info['tag_name'] = $tag_namearr['tag_name'];
                    $tag_info['tag_code'] = $knonwledge['tag_code'];
                    $tag_info['ability'] = $ability_num;
                    $tag_ability_report[] = $tag_info;
                    break;
                }
            }
        }

        $is_show_report = 0;
        $is_show_nextstep = 0;

        $topic_service = new TopicService();
        $topicInfo = $topic_service->getTopicByTopicId($topicId);
        $flow_id = $topicInfo["flow_id"];
        $url = "";

        if ($flow_id == 1) {
            $url = url("Index/zhlx/index", ["topicId" => $topicId]);
        } elseif ($flow_id == 2) {
            $url = url("Index/mncs/index", ["topicId" => $topicId]);
        }

        $topic_name = $topicInfo['topic_name'];
        $this->assign("topic_name", $topic_name);
        $this->assign("flow_id", $flow_id);
        $this->assign("url", $url);
        $accuracy = $user_service->getUserExamDetail($user_id, $topicId, $module_type);
        $this->assign("accuracy", $accuracy); //正确率
        $this->assign("has_answered_questions", $has_answered_questions);
        $this->assign("frist_has_learned_num", $frist_has_learned_num);
        $this->assign("has_learned_weakElements_num", $has_learned_weakElements_num);
        $this->assign("not_learned_weakElements_num", $not_learned_weakElements_num);
        $this->assign("scale", $scale);
        $this->assign("tag_ability_report", json_encode($tag_ability_report));
        $this->assign("topicId", $topicId);
        $this->assign('module_type', $module_type);
        $this->assign('is_show_report', $is_show_report);
        $this->assign('is_show_nextstep', $is_show_nextstep);

        //判断现行测试边学边练和竞赛扩展是否做完
        $user_id = $this->getUserId();
        $userService = new UserService();
        $xianceStep = $userService->getUserStep($topicId, $user_id, config('xiance_module_type')); //边学边练
        $xiance_is_end = 0;
        $this->assign('xiance_is_end', 0);
        if (isset($xianceStep['is_end']) && $xianceStep['is_end'] == 1)
            $xiance_is_end = 1;
        $this->assign('xiance_is_end', $xiance_is_end);

        $bxblStep = $userService->getUserStep($topicId, $user_id, config('bxbl_module_type')); //边学边练
        $bxbl_is_end = 0;
        if (isset($bxblStep['is_end']) && $bxblStep['is_end'] == 1)
            $bxbl_is_end = 1;;
        $this->assign('bxbl_is_end', $bxbl_is_end);
        $zhlxStep = $userService->getUserStep($topicId, $user_id, config('zonghe_module_type')); //综合练习
        $zhlx_is_end = 0;
        if (isset($zhlxStep['is_end']) && $zhlxStep['is_end'] == 1)
            $zhlx_is_end = 1;;
        $this->assign('zhlx_is_end', $zhlx_is_end);

        return $this->fetch("bxbl/studyReport");
    }

    /**
     * 竞赛拓展报告页
     * @return mixed
     */
    public function zhlxReport() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $user_id = $request->param('user_id');
        $algoLogic = new AlgoLogic();
        $user_service = new UserService();
        $question_service = new QuestionService();
//        $all_knowledgeList = $question_service->getKnowledgeList();
        $kmap_code = config("kmap_code");
        $module_type = config('zonghe_module_type');
//        $knowledgeList = $all_knowledgeList[$kmap_code];


        $is_show_report = 0;
        $is_show_nextstep = 0;


        //用户做过的试题信息.
        $has_answered_questions = $user_service->getUserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type);
        $api_gate_service = new ApiGateService();
        $getZhlxQuestionIds = $api_gate_service->getZhlxQuestionIds($topicId); //获取当前知识点下有没有做错的 如果有就是继续做

        $zongtishuliang = $user_service->getUserHasAnsweredQuestionsByModuleType($user_id, $topicId, $module_type);
        $daduitishuliang = $user_service->getUserAnsweredAllRightQuestionsByModuleType($user_id, $topicId, $module_type);
        if (count($zongtishuliang)) {
            $daduibi = ceil((count($daduitishuliang) / count($zongtishuliang)) * 100);
        } else {
            $daduibi = 0;
        }

        $topicService = new TopicService();
        $topic = $topicService->getTopicByTopicId($topicId);
        $topicNmae = $topic["topic_name"];
        $this->assign("zongtishuliang", count($zongtishuliang));
        $this->assign("daduitishuliang", count($daduitishuliang));
        $this->assign("daduibi", $daduibi);
       
        $this->assign("has_answered_questions", $has_answered_questions);
        $this->assign("topicId", $topicId);
        $this->assign("topic_name", $topicNmae);
        $this->assign("getZhlxQuestionIds", $getZhlxQuestionIds);
        $this->assign("is_show_report", $is_show_report);
        $this->assign("is_show_nextstep", $is_show_nextstep);

        $xxcsIsAllRight = $user_service->getUserIsAllRight($topicId, $module_type, $user_id);
        if (count($has_answered_questions) == 0) {
            $this->assign("has_learned_percent", 0);
        } else {
            $this->assign("has_learned_percent", ceil($xxcsIsAllRight / count($has_answered_questions) * 100));
        }
        $this->assign("xiance_count", count($user_service->getUserHasAnsweredQuestionsByModule($user_id, $topicId, config('xiance_module_type'))));

        return $this->fetch("zhlx/zhlxReport");
    }

    /**
     * 模拟测试报告页
     * @return mixed
     */
    public function mncsReport() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $user_id = $request->param('user_id');
        $algoLogic = new AlgoLogic();
        $user_service = new UserService();
        $question_service = new QuestionService();
//        $all_knowledgeList = $question_service->getKnowledgeList();
        $kmap_code = config("kmap_code");
        $module_type = config('mncs_module_type');
//        $knowledgeList = $all_knowledgeList[$kmap_code];
        //用户做过的试题信息.
        $has_answered_questions = $user_service->getUserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type);
        $api_gate_service = new ApiGateService();
        $getZhlxQuestionIds = $api_gate_service->getZhlxQuestionIds($topicId); //获取当前知识点下有没有做错的 如果有就是继续做

        $zongtishuliang = $user_service->getUserHasAnsweredQuestionsByModuleType($user_id, $topicId, $module_type);
        $daduitishuliang = $user_service->getUserAnsweredAllRightQuestionsByModuleType($user_id, $topicId, $module_type);
        if (count($zongtishuliang)) {
            $daduibi = ceil((count($daduitishuliang) / count($zongtishuliang)) * 100);
        } else {
            $daduibi = 0;
        }
        $topicService = new TopicService();
        $topic = $topicService->getTopicByTopicId($topicId);
        $flow_id = $topic["flow_id"];
        $is_show_report = 0;
        $is_show_nextstep = 0;
        $this->assign("has_answered_questions", $has_answered_questions);
        $this->assign("zongtishuliang", count($zongtishuliang));
        $this->assign("daduitishuliang", count($daduitishuliang));
        $this->assign("daduibi", $daduibi);
        $this->assign("topicId", $topicId);

        $topicName = $topic["topic_name"];
        $this->assign("topic_name", $topicName);
        $this->assign("flow_id", $flow_id);
        $this->assign("getZhlxQuestionIds", $getZhlxQuestionIds);
        $this->assign("is_show_report", $is_show_report);
        $this->assign("is_show_nextstep", $is_show_nextstep);

        $xxcsIsAllRight = $user_service->getUserIsAllRight($topicId, $module_type, $user_id);
        if (count($has_answered_questions) == 0) {
            $this->assign("has_learned_percent", 0);
        } else {
            $this->assign("has_learned_percent", ceil($xxcsIsAllRight / count($has_answered_questions) * 100));
        }
        $this->assign("xiance_count", count($user_service->getUserHasAnsweredQuestionsByModule($user_id, $topicId, config('xiance_module_type'))));
        return $this->fetch("mncs/mncsReport");
    }

}
