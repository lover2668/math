<?php

namespace app\summer\controller;

use service\services\summer\SummerQuestionService;
use service\services\UserService;
use service\services\PathManageService;
use think\Request;
use think\Log;
use service\services\TopicV2Service;
use service\services\KnowledgeV2Service;
use service\algo\SummerAlgoLogic;
use service\services\summer\SummerUserService;
use service\services\QuestionService;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use service\org\util\Page;
use app\index\controller\Base;
use  service\algo\AlgoLogic;
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

    public function stageReport() {
        error_reporting(E_ALL ^ E_NOTICE);
//        var_dump($_SERVER['HTTP_HOST']);die;
        $request = Request::instance();
//        $url = $request->url(TRUE);
//        if(!$_SERVER["QUERY_STRING"]){
//             $url.="?a=1";
//        }
        $topicId = $request->param("topicId", 9016);
        $user_id = $request->param("user_id");
        $batch_num = $request->param("batch_num");
        $submodule_type = $request->param("submodule_type", 1);
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];

//            $url.="&user_id=".$user_id;
        }
        $topicService = new TopicV2Service();
        $knowledgeService = new KnowledgeV2Service();
        $userService = new UserService();
        $summerUserService = new SummerUserService();
        $questionService = new QuestionService();
        $summer_question_service = new SummerQuestionService();

        $module_type = config("l1_module_type");
        if (!$batch_num) {
//            $batch_num=$questionService->checkBatchNum($user_id ,$topicId, $module_type,1);
            $batch_num = $summer_question_service->checkBatchNum($user_id ,$topicId, $module_type,1);
//            $url.="&batch_num=".$batch_num;
        }

        $user_info = $userService->getUserName($user_id);
        if(isset($user_info["real_name"]) && !empty($user_info["real_name"])){
            $user_name = $user_info["real_name"];
        } else {
            $user_name = $user_info["user_name"];
        }
        $topic = $topicService->getTopicByTopicId($topicId);
//        $kmap_code = $topicService->getMainKmapCode($topicId);
        $kmap_code_all = $topicService->getKmapCodeAll($topicId);
        $topic_name = $topic["topic_name"];
        $knowledge_list_all = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        $err_tag_code = [];
        foreach ($knowledge_list_all as $key => $value) {
            $tag_code = $value["tag_code"];
            $tag_info = $knowledgeService->getKnowledgeByCode($tag_code);
            if (!isset($tag_info["tag_name"])) {
                $err_tag_code = $tag_code;
                $tag_names[$tag_code] = "未定义" . $tag_code;
            } else {
                $tag_names[$tag_code] = $tag_info["tag_name"];
            }
        }
//        $tag_names["c020206"] = "已删除知识点c020206";
        $hasAnswerQuestions = $summerUserService->getL2UserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type, $submodule_type, $batch_num);
        if ($hasAnswerQuestions) {
            $lastQuestion = end($hasAnswerQuestions);
            $date = date("Y/m/d", $lastQuestion["ctime"]);
        } else {
            $date = date("Y/m/d");
        }
        $sum_num = 0;
        $right_num = 0;
        $spent_time = 0;
        $estimates_time = 0;
        foreach ($hasAnswerQuestions as $key => $value) {
            $sum_num++;
            $spent_time += $value["spent_time"];
            $estimates_time += $value["estimates_time"];
            if ($value["is_right"] == 1) {
                $right_num++;
            }
        }
        if ($sum_num) {
            $accuracy = ceil($right_num / $sum_num * 100);
        } else {
            $accuracy = 0;
        }
        if (!$estimates_time) {
            $xiaolv = 0;
        } else {
            $xiaolv = sprintf("%.2f", (ceil($spent_time / 1000) / $estimates_time));
        }
        $spent_time = ceil($spent_time / 1000);
        $spent_time = $this->spentTime($spent_time);

        $estimates_time = dateFormatForMicroTime($estimates_time);
//        $knowledge_list = $topicService->getKmapInfoByKmapCode($kmap_code_all);
//        foreach ($knowledge_list as $key => $value) {
//            $knowledge_list[$key] = $value["tag_code"];
//        }
//        $algoLogic = new SummerAlgoLogic();
//        $weakElements = $algoLogic->getWeakElements($user_id, $topicId, $module_type, 1, $batch_num);  //正常的薄弱知识点
//        if(!$weakElements){
//            $right_list =$knowledge_list;
//        } else {
//             $right_list = array_diff($knowledge_list, $weakElements);
//        }
//        $zhangwolv = ceil((count($right_list) / count($knowledge_list)) * 100);

        $url = "http://" . $_SERVER['HTTP_HOST'] . "/summer/report/stageReportMobile.html?topicId=" . $topicId . "&user_id=" . $user_id . "&batch_num=" . $batch_num . "&module_type=" . $module_type . "&submodule_type=" . $submodule_type;
        $this->assign("url_code", urlsafe_b64encode($url));
        $this->assign("url", $url);
        $this->assign("topic_name", $topic_name);
        $this->assign("topicId", $topicId);
        $this->assign("user_name", $user_name);
        $this->assign("user_id", $user_id);
        $this->assign("submodule_type", $submodule_type);
        $this->assign("batch_num", $batch_num);
        $this->assign("date_time", $date);
        $this->assign("spent_time", $spent_time);
        $this->assign("estimates_time", $estimates_time);
        $this->assign("xiaolv", $xiaolv);
        $this->assign("tag_names", $tag_names);
//        $this->assign("zhangwolv", $zhangwolv);
        $this->assign("hasAnswerQuestions", $hasAnswerQuestions);
//        $this->assign("weakElements", $weakElements);
//        $this->assign("weakElements_num", count($weakElements));
//        $this->assign("knowledge_list_num", count($knowledge_list));
//        $this->assign("new_knowledgeList_num", count($right_list));
        $this->assign("sum_num", $sum_num);
        $this->assign("right_num", $right_num);
//        $this->assign("right_list", $right_list);
        $this->assign("accuracy", $accuracy);
        return $this->fetch("stagereport");
    }

    /**
     * 基础先行测试报告。
     */
    public function preReport() {
        error_reporting(E_ALL ^ E_NOTICE);
//        echo config("estimates_time");die;
        $request = Request::instance();
        $topicId = $request->param("topicId", 9016);
        $user_id = $request->param("user_id");
        $is_all = $request->param("is_all", 0);
        $batch_num = $request->param("batch_num", 0);
        $submodule_type = $request->param("submodule_type", 1);
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
        }
        $topicService = new TopicV2Service();
        $knowledgeService = new KnowledgeV2Service();
        $userService = new UserService();
        $summerUserService = new SummerUserService();
        $questionService = new QuestionService();
        $summer_question_service = new SummerQuestionService();
 
        $module_type = config("l1_module_type");
        if (!$is_all) {
            if (!$batch_num) {
//            $batch_num=$questionService->checkBatchNum($user_id ,$topicId, $module_type,1);
                $batch_num = $summer_question_service->checkBatchNum($user_id ,$topicId, $module_type,1);
            }
        }
        $user_info = $userService->getUserName($user_id);
        if(isset($user_info["real_name"]) && !empty($user_info["real_name"])){
            $user_name = $user_info["real_name"];
        } else {
            $user_name = $user_info["user_name"];
        }
        $topic = $topicService->getTopicByTopicId($topicId);
//        $kmap_code = $topicService->getMainKmapCode($topicId);
        $kmap_code_all = $topicService->getKmapCodeAll($topicId);
        $topic_name = $topic["topic_name"];
        $knowledge_list_all = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        $err_tag_code = [];
        foreach ($knowledge_list_all as $key => $value) {
            $tag_code = $value["tag_code"];
            $tag_info = $knowledgeService->getKnowledgeByCode($tag_code);
//            var_dump($tag_info);die;
            if (!isset($tag_info["tag_name"])) {
                $err_tag_code = $tag_code;
                $tag_names[$tag_code] = "未定义" . $tag_code;
            } else {
                $tag_names[$tag_code] = $tag_info["tag_name"];
            }
        }
//        $tag_names["c020206"] = "已删除知识点c020206";

        $hasAnswerQuestions = $summerUserService->getL2UserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type, $submodule_type, $batch_num);
        $user_ability_info = $summerUserService->getUserTagCodeAbility($user_id, $topicId, "", $module_type, $submodule_type);
        if ($hasAnswerQuestions) {
            $lastQuestion = end($hasAnswerQuestions);
            $date = date("Y/m/d", $lastQuestion["ctime"]);
        } else {
            $date = date("Y/m/d");
        }
//        var_dump($hasAnswerQuestions);die;
        $sum_num = 0;
        $right_num = 0;
        $spent_time = 0;
        $estimates_time = 0;
        foreach ($hasAnswerQuestions as $key => $value) {
            $sum_num++;
//            var_dump($value["spent_time"]);
            $spent_time += $value["spent_time"];
            $estimates_time += $value["estimates_time"];
            if ($value["is_right"] == 1) {
                $right_num++;
            }
        }
        if ($sum_num) {
            $accuracy = ceil($right_num / $sum_num * 100);
        } else {
            $accuracy = 0;
        }
//        var_dump($spent_time);
//        var_dump($estimates_time);die;
        if (!$estimates_time) {
            $xiaolv = 0;
        } else {
            $xiaolv = sprintf("%.2f", (ceil($spent_time / 1000) / $estimates_time));
        }

        $spent_time = ceil($spent_time / 1000);
        $spent_time = $this->spentTime($spent_time);

        $estimates_time = dateFormatForMicroTime($estimates_time);
        $knowledge_list = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        foreach ($knowledge_list as $key => $value) {
            $knowledge_list[$key] = $value["tag_code"];
        }
//        $algoLogic = new SummerAlgoLogic();
        $weakElements = $summerUserService->getUserLastWeakElements($user_id, $topicId, $module_type, 1, $batch_num,$knowledge_list);  //正常的薄弱知识点
        if (!$weakElements) {
            $right_list = $knowledge_list;
        } else {
            $right_list = array_diff($knowledge_list, $weakElements);
        }
        $zhangwolv = ceil((count($right_list) / count($knowledge_list)) * 100);
        $url = "http://" . $_SERVER['HTTP_HOST'] . "/summer/report/preReportMobile.html?topicId=" . $topicId . "&user_id=" . $user_id;
        if ($is_all) {
            $url .= "&is_all=1";
        } else {
            $url .= "&batch_num=" . $batch_num;
        }
        $url .= "&module_type=" . $module_type . "&submodule_type=" . $submodule_type;
        //获取章节
        $chapter_data =  $this->getChapter($tag_names,$topicId,$weakElements,$right_list);

        $this->assign("url_code", urlsafe_b64encode($url));
        $this->assign("url", $url);
        $this->assign("is_all", $is_all);
        $this->assign("topic_name", $topic_name);
        $this->assign("topicId", $topicId);
        $this->assign("user_name", $user_name);
        $this->assign("user_id", $user_id);
        $this->assign("submodule_type", $submodule_type);
        $this->assign("batch_num", $batch_num);
        $this->assign("date_time", $date);
        $this->assign("spent_time", $spent_time);
        $this->assign("estimates_time", $estimates_time);
        $this->assign("xiaolv", $xiaolv);
        $this->assign("tag_names", $tag_names);
        $this->assign("user_ability_info", $user_ability_info);
        $this->assign("zhangwolv", $zhangwolv);
        $this->assign("hasAnswerQuestions", $hasAnswerQuestions);
        $this->assign("weakElements", $weakElements);
        $this->assign("weakElements_num", count($weakElements));
        $this->assign("knowledge_list_num", count($knowledge_list));
        $this->assign("new_knowledgeList_num", count($right_list));
        $this->assign("sum_num", $sum_num);
        $this->assign("right_num", $right_num);
        $this->assign("right_list", $right_list);
        $this->assign("accuracy", $accuracy);
        $this->assign("chapter_data",$chapter_data);
        return $this->fetch("preReport");
    }

    //获取章节
    public function getChapter($tag_names, $topicId="9016", $weak, $right){

        //获取接口数据
        $topicV2Service = new TopicV2Service();
        $getChapterData = $topicV2Service->getChapterListByTopicId($topicId);
        $childrens = [];

        if($getChapterData){
            foreach ($getChapterData as $k => $v) {
                $items = [];
                //获取知识点
                $tag_list = $v['tag_list'];
                $tag_list_arr= explode(',', $tag_list);
                if($tag_list_arr && is_array($tag_list_arr)){
                    foreach ($tag_list_arr as $k1 => $v1) {
                        //1未掌握 2已掌握 3没学
                        $status = 3;
                        if(in_array($v1, $weak)){
                            $status = 1;
                        }else if(in_array($v1, $right)){
                            $status = 2;
                        }
                        $items[] = ["name"=> $tag_names[$v1],"value"=> $tag_names[$v1],"status"=>$status];
                    }
                }
                $childrens[] = ['name'=>$v['name'],'value'=>$v['name'],'children'=>$items];
            }
        }
        $o['name'] = '章节';//主节点
        $o['value'] = '章节';
        $o['children'] = $childrens;
        $o['num'] = count($tag_names);
        return json_encode($o);
    }


    /**
     * 知识点学习报告。
     */
    public function learningReport() {
        error_reporting(E_ALL ^ E_NOTICE);
        $request = Request::instance();
        $topicId = $request->param("topicId", 9016);
        $user_id = $request->param("user_id");
        $batch_num = $request->param("batch_num", 0);
        $is_all = $request->param("is_all", 0);
        $submodule_type = $request->param("submodule_type", 2);
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
        }
        $topicService = new TopicV2Service();
        $knowledgeService = new KnowledgeV2Service();
        $userService = new UserService();
        $summerUserService = new SummerUserService();
        $questionService = new QuestionService();
        $summer_question_service = new SummerQuestionService();

        $module_type = config("l1_module_type");
        if (!$is_all) {
            if (!$batch_num) {
                $batch_num = $summer_question_service->checkBatchNum($user_id ,$topicId, $module_type,1);
            }
        }
        $user_info = $userService->getUserName($user_id);
         if(isset($user_info["real_name"]) && !empty($user_info["real_name"])){
            $user_name = $user_info["real_name"];
        } else {
            $user_name = $user_info["user_name"];
        }
        $topic = $topicService->getTopicByTopicId($topicId);
//        $kmap_code = $topicService->getMainKmapCode($topicId);
        $kmap_code_all = $topicService->getKmapCodeAll($topicId);
        $topic_name = $topic["topic_name"];
        $knowledge_list_all = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        $err_tag_code = [];
        foreach ($knowledge_list_all as $key => $value) {
            $tag_code = $value["tag_code"];
            $tag_info = $knowledgeService->getKnowledgeByCode($tag_code);
            if (!isset($tag_info["tag_name"])) {
                $err_tag_code = $tag_code;
                $tag_names[$tag_code] = "未定义" . $tag_code;
            } else {
                $tag_names[$tag_code] = $tag_info["tag_name"];
            }
        }
//        $tag_names["c020206"] = "已删除知识点c020206";
        $hasAnswerQuestions = $summerUserService->getL2UserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type, $submodule_type, $batch_num);
        $user_ability_info = $summerUserService->getUserTagCodeAbility($user_id, $topicId, "", $module_type, $submodule_type);
        if ($hasAnswerQuestions) {
            $lastQuestion = end($hasAnswerQuestions);
            $date = date("Y/m/d", $lastQuestion["ctime"]);
        } else {
            echo '未做知识点学习报告,暂无数据';die;
            $date = date("Y/m/d");
        }
//        var_dump($hasAnswerQuestions);die;
        $sum_num = 0;
        $right_num = 0;
        $spent_time = 0;
        $estimates_time = 0;
        foreach ($hasAnswerQuestions as $key => $value) {
            $sum_num++;
            $spent_time += $value["spent_time"];
            $estimates_time += $value["estimates_time"];
            if ($value["is_right"] == 1) {
                $right_num++;
            }
        }
        if ($sum_num) {
            $accuracy = ceil($right_num / $sum_num * 100);
        } else {
            $accuracy = 0;
        }
        if (!$estimates_time) {
            $xiaolv = 0;
        } else {
            $xiaolv = sprintf("%.2f", (ceil($spent_time / 1000) / $estimates_time));
        }
        $spent_time = ceil($spent_time / 1000);
        $spent_time = $this->spentTime($spent_time);

        $estimates_time = dateFormatForMicroTime($estimates_time);
        $knowledge_list = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        foreach ($knowledge_list as $key => $value) {
            $knowledge_list[$key] = $value["tag_code"];
        }
//        $algoLogic = new SummerAlgoLogic();
        $weakElements = $summerUserService->getUserLastWeakElements($user_id, $topicId, $module_type, 1, $batch_num,$knowledge_list);
        if (!$weakElements) {
            $right_list = $knowledge_list;
        } else {
            $right_list = array_diff($knowledge_list, $weakElements);
        }
        $zhangwolv = ceil((count($right_list) / count($knowledge_list)) * 100);
        $url = "http://" . $_SERVER['HTTP_HOST'] . "/summer/report/learningReportMobile.html?topicId=" . $topicId . "&user_id=" . $user_id;
        if ($is_all) {
            $url .= "&is_all=1";
        } else {
            $url .= "&batch_num=" . $batch_num;
        }
        $url .= "&module_type=" . $module_type . "&submodule_type=" . $submodule_type;


        //定义维度
        $blm_wd_key = [];//维度名
        $blm_wd_config = nengLiConfig();//布鲁姆键值配置
        $blm_wd_value = $questionService->getBlmValue($blm_wd_key,$topicId);//维度值
        $this->assign("blm_wd_key", json_encode($blm_wd_key));
        $this->assign("blm_wd_config", json_encode($blm_wd_config));
        $this->assign("blm_wd_value", json_encode($blm_wd_value));

        $this->assign("url_code", urlsafe_b64encode($url));
        $this->assign("url", $url);
        $this->assign("topic_name", $topic_name);
        $this->assign("topicId", $topicId);
        $this->assign("user_name", $user_name);
        $this->assign("user_id", $user_id);
        $this->assign("submodule_type", $submodule_type);
        $this->assign("batch_num", $batch_num);
        $this->assign("date_time", $date);
        $this->assign("spent_time", $spent_time);
        $this->assign("estimates_time", $estimates_time);
        $this->assign("xiaolv", $xiaolv);
        $this->assign("tag_names", $tag_names);
        $this->assign("user_ability_info", $user_ability_info);
        $this->assign("zhangwolv", $zhangwolv);
        $this->assign("hasAnswerQuestions", $hasAnswerQuestions);
        $this->assign("weakElements", $weakElements);
        $this->assign("weakElements_num", count($weakElements));
        $this->assign("knowledge_list_num", count($knowledge_list));
        $this->assign("new_knowledgeList_num", count($right_list));
        $this->assign("sum_num", $sum_num);
        $this->assign("right_num", $right_num);
        $this->assign("right_list", $right_list);
        $this->assign("accuracy", $accuracy);
        return $this->fetch("learningReport");
    }


    public function reportMobile() {
        error_reporting(E_ALL ^ E_NOTICE);
        $request = Request::instance();
        $topicId = $request->param("topicId");
        return $this->fetch("reportMobile");
    }

    public function reportDetail() {
        error_reporting(E_ALL ^ E_NOTICE);
        $request = Request::instance();
        $url = $request->url(TRUE);
        $topicId = $request->param('topicId', 9016);
        $user_id = $request->param('user_id', 1);
        $module_type = $request->param('module_type', config("l1_module_type"));
        $submodule_type = $request->param('submodule_type', 1);
        $batch_num = $request->param("batch_num", 0);
        $report_num = $request->param("report_num");
        $is_error = $request->param('is_error', null);
        $page_num = $request->param('page', 1);
        $pageSize = $request->param('page_size', 5); //默认5条   

        if ($is_error) {
            $is_right = 0;
        } else {
            $is_right = null;
        }

        if ($report_num == 1) {
            $report_name = "阶段测试报告";
        } elseif ($report_num == 2) {
            $report_name = "基础先行测试报告";
        } elseif ($report_num == 3) {
            $report_name = "知识点学习报告";
        } else {
            $report_name = "当堂报告";
        }
        $startIndex = ($page_num - 1) * $pageSize + 1;

        $summerUserService = new SummerUserService();
        $topicService = new TopicV2Service();
        $knowledgeService = new KnowledgeV2Service();
        $kmap_code_all = $topicService->getKmapCodeAll($topicId);
        $knowledge_list_all = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        $err_tag_code = [];
//        config("next", ">");
//        $pageService = new Page(21);
//        $pageService->Config("next", ">");
//        $pageService->Config("prev", "<");
        foreach ($knowledge_list_all as $key => $value) {
            $tag_code = $value["tag_code"];
            $tag_info = $knowledgeService->getKnowledgeByCode($tag_code);
//            var_dump($tag_info);die;
            if (!isset($tag_info["tag_name"])) {
                $err_tag_code = $tag_code;
                $tag_names[$tag_code] = "未定义" . $tag_code;
            } else {
                $tag_names[$tag_code] = $tag_info["tag_name"];
            }
        }
//        $tag_names["c020206"] = "已删除知识点c020206";
        $hasAnswerQuestions = $summerUserService->getUserAnsweredQuestionsByModule($user_id, $topicId, $batch_num, $module_type, $submodule_type);
//        var_dump($hasAnswerQuestions);die;
        $sum_num = 0;
        $right_num = 0;
        foreach ($hasAnswerQuestions as $key => $value) {
            $sum_num++;
            if ($value["is_right"] == 1) {
                $right_num++;
            }
        }
        if ($sum_num) {
            $accuracy = ceil($right_num / $sum_num * 100);
        } else {
            $accuracy = 0;
        }
        $pageParams = $request->param();
        $pageParams = [
            "query" => $pageParams,
        ];
        $hasAnswerQuestionsWithDetail = $summerUserService->getUserHasAnsweredQuestionsWithDetail($user_id, $topicId, $module_type, $submodule_type, $is_right, $page_num, $pageSize, $pageParams, 'asc');
        $this->assign("startIndex", $startIndex);
        $this->assign("topicId", $topicId);
        $this->assign("report_name", $report_name);
        $this->assign("report_num", $report_num);
        $this->assign("user_id", $user_id);
        $this->assign("module_type", $module_type);
        $this->assign("submodule_type", $submodule_type);
        $this->assign("page_num", $page_num);
        $this->assign("url", $url);
        $this->assign("is_error", $is_error);
        $this->assign("page_size", $pageSize);
        $this->assign("batch_num", $batch_num);
        $this->assign("hasAnswerQuestions", $hasAnswerQuestionsWithDetail["data"]);
        $this->assign("page", $hasAnswerQuestionsWithDetail["page"]);
        $this->assign("err_num", $sum_num - $right_num);
        $this->assign("sum_num", $sum_num);
        $this->assign("tag_names", $tag_names);
        $this->assign("right_num", $right_num);
        $this->assign("accuracy", $accuracy);
        return $this->fetch("reportDetail");
    }

    public function stageReportMobile() {
        error_reporting(E_ALL ^ E_NOTICE);
        $request = Request::instance();
        $url = $request->url(TRUE);
        if (!$_SERVER["QUERY_STRING"]) {
            $url .= "?a=1";
        }
        $topicId = $request->param("topicId", 9016);
        $user_id = $request->param("user_id");
        $batch_num = $request->param("batch_num");
        $submodule_type = $request->param("submodule_type", 1);
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
            $url .= "&user_id=" . $user_id;
        }
        $topicService = new TopicV2Service();
        $knowledgeService = new KnowledgeV2Service();
        $userService = new UserService();
        $summerUserService = new SummerUserService();
        $questionService = new QuestionService();
        $summer_question_service = new SummerQuestionService();

        $module_type = config("l1_module_type");
        if (!$batch_num) {
//            $batch_num=$questionService->checkBatchNum($user_id ,$topicId, $module_type,1);
            $batch_num = $summer_question_service->checkBatchNum($user_id ,$topicId, $module_type,1); 
        }
        $url .= "&batch_num=" . $batch_num;
        $user_info = $userService->getUserName($user_id);
//        var_dump($user_info);die;
         if(isset($user_info["real_name"]) && !empty($user_info["real_name"])){
            $user_name = $user_info["real_name"];
        } else {
            $user_name = $user_info["user_name"];
        }
        $topic = $topicService->getTopicByTopicId($topicId);
//        $kmap_code = $topicService->getMainKmapCode($topicId);
        $kmap_code_all = $topicService->getKmapCodeAll($topicId);
        $topic_name = $topic["topic_name"];
        $knowledge_list_all = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        $err_tag_code = [];
        foreach ($knowledge_list_all as $key => $value) {
            $tag_code = $value["tag_code"];
            $tag_info = $knowledgeService->getKnowledgeByCode($tag_code);
            if (!isset($tag_info["tag_name"])) {
                $err_tag_code = $tag_code;
                $tag_names[$tag_code] = "未定义" . $tag_code;
            } else {
                $tag_names[$tag_code] = $tag_info["tag_name"];
            }
        }
//        $tag_names["c020206"] = "已删除知识点c020206";
        $hasAnswerQuestions = $summerUserService->getL2UserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type, $submodule_type, $batch_num);
        if ($hasAnswerQuestions) {
            $lastQuestion = end($hasAnswerQuestions);
            $date = date("Y/m/d", $lastQuestion["ctime"]);
        } else {
            $date = date("Y/m/d");
        }
        $sum_num = 0;
        $right_num = 0;
        $spent_time = 0;
        $estimates_time = 0;
        foreach ($hasAnswerQuestions as $key => $value) {
            $sum_num++;
            $spent_time += $value["spent_time"];
            $estimates_time += $value["estimates_time"];
            if ($value["is_right"] == 1) {
                $right_num++;
            }
        }
        if ($sum_num) {
            $accuracy = ceil($right_num / $sum_num * 100);
        } else {
            $accuracy = 0;
        }
        if (!$estimates_time) {
            $xiaolv = 0;
        } else {
            $xiaolv = sprintf("%.2f", (ceil($spent_time / 1000) / $estimates_time));
        }
        $spent_time = ceil($spent_time / 1000);
        $spent_time = $this->spentTime($spent_time);

        $estimates_time = dateFormatForMicroTime($estimates_time);
//        $knowledge_list = $topicService->getKmapInfoByKmapCode($kmap_code_all);
//        foreach ($knowledge_list as $key => $value) {
//            $knowledge_list[$key] = $value["tag_code"];
//        }
//        $algoLogic = new SummerAlgoLogic();
//        $weakElements = $algoLogic->getWeakElements($user_id, $topicId, $module_type, 1, $batch_num);  //正常的薄弱知识点
//        if(!$weakElements){
//            $right_list =$knowledge_list;
//        } else {
//             $right_list = array_diff($knowledge_list, $weakElements);
//        }
//        $zhangwolv = ceil((count($right_list) / count($knowledge_list)) * 100);
        $this->assign("url", $url);
        $this->assign("topic_name", $topic_name);
        $this->assign("topicId", $topicId);
        $this->assign("user_name", $user_name);
        $this->assign("user_id", $user_id);
        $this->assign("submodule_type", $submodule_type);
        $this->assign("batch_num", $batch_num);
        $this->assign("date_time", $date);
        $this->assign("spent_time", $spent_time);
        $this->assign("estimates_time", $estimates_time);
        $this->assign("xiaolv", $xiaolv);
        $this->assign("tag_names", $tag_names);
//        $this->assign("zhangwolv", $zhangwolv);
        $this->assign("hasAnswerQuestions", $hasAnswerQuestions);
//        $this->assign("weakElements", $weakElements);
//        $this->assign("weakElements_num", count($weakElements));
//        $this->assign("knowledge_list_num", count($knowledge_list));
//        $this->assign("new_knowledgeList_num", count($right_list));
        $this->assign("sum_num", $sum_num);
        $this->assign("right_num", $right_num);
//        $this->assign("right_list", $right_list);
        $this->assign("accuracy", $accuracy);
        return $this->fetch("stageReportMobile");
    }

    /**
     * 基础先行测试报告。
     */
    public function preReportMobile() {
        error_reporting(E_ALL ^ E_NOTICE);
        $request = Request::instance();
        $url = $request->url(TRUE);
        if (!$_SERVER["QUERY_STRING"]) {
            $url .= "?a=1";
        }
        $topicId = $request->param("topicId", 9016);
        $user_id = $request->param("user_id");
        $is_all = $request->param("is_all", 0);
        $batch_num = $request->param("batch_num", 0);
        $submodule_type = $request->param("submodule_type", 1);
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
            $url .= "&user_id=" . $user_id;
        }
        $topicService = new TopicV2Service();
        $knowledgeService = new KnowledgeV2Service();
        $userService = new UserService();
        $summerUserService = new SummerUserService();
        $questionService = new QuestionService();
        $summer_question_service = new SummerQuestionService();

        $module_type = config("l1_module_type");
        if (!$is_all) {
            if (!$batch_num) {
//            $batch_num=$questionService->checkBatchNum($user_id ,$topicId, $module_type,1);
                $batch_num = $summer_question_service->checkBatchNum($user_id ,$topicId, $module_type,1);
            }
            $url .= "&batch_num=" . $batch_num;
        }
        $user_info = $userService->getUserName($user_id);
         if(isset($user_info["real_name"]) && !empty($user_info["real_name"])){
            $user_name = $user_info["real_name"];
        } else {
            $user_name = $user_info["user_name"];
        }
        $topic = $topicService->getTopicByTopicId($topicId);
//        $kmap_code = $topicService->getMainKmapCode($topicId);
        $kmap_code_all = $topicService->getKmapCodeAll($topicId);
        $topic_name = $topic["topic_name"];
        $knowledge_list_all = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        $err_tag_code = [];
        foreach ($knowledge_list_all as $key => $value) {
            $tag_code = $value["tag_code"];
            $tag_info = $knowledgeService->getKnowledgeByCode($tag_code);
//            var_dump($tag_info);die;
            if (!isset($tag_info["tag_name"])) {
                $err_tag_code = $tag_code;
                $tag_names[$tag_code] = "未定义" . $tag_code;
            } else {
                $tag_names[$tag_code] = $tag_info["tag_name"];
            }
        }
//        $tag_names["c020206"] = "已删除知识点c020206";

        $hasAnswerQuestions = $summerUserService->getL2UserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type, $submodule_type, $batch_num);
        $user_ability_info = $summerUserService->getUserTagCodeAbility($user_id, $topicId, "", $module_type, $submodule_type);
        if ($hasAnswerQuestions) {
            $lastQuestion = end($hasAnswerQuestions);
            $date = date("Y/m/d", $lastQuestion["ctime"]);
        } else {
            $date = date("Y/m/d");
        }
//        var_dump($hasAnswerQuestions);die;
        $sum_num = 0;
        $right_num = 0;
        $spent_time = 0;
        $estimates_time = 0;
        foreach ($hasAnswerQuestions as $key => $value) {
            $sum_num++;
//            var_dump($value["spent_time"]);
            $spent_time += $value["spent_time"];
            $estimates_time += $value["estimates_time"];
            if ($value["is_right"] == 1) {
                $right_num++;
            }
        }
        if ($sum_num) {
            $accuracy = ceil($right_num / $sum_num * 100);
        } else {
            $accuracy = 0;
        }
        if (!$estimates_time) {
            $xiaolv = 0;
        } else {
            $xiaolv = sprintf("%.2f", (ceil($spent_time / 1000) / $estimates_time));
        }

        $spent_time = ceil($spent_time / 1000);
        $spent_time = $this->spentTime($spent_time);

        $estimates_time = dateFormatForMicroTime($estimates_time);
        $knowledge_list = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        foreach ($knowledge_list as $key => $value) {
            $knowledge_list[$key] = $value["tag_code"];
        }
//        $algoLogic = new SummerAlgoLogic();
        $weakElements = $summerUserService->getUserLastWeakElements($user_id, $topicId, $module_type, 1, $batch_num,$knowledge_list);
        if (!$weakElements) {
            $right_list = $knowledge_list;
        } else {
            $right_list = array_diff($knowledge_list, $weakElements);
        }
        $zhangwolv = ceil((count($right_list) / count($knowledge_list)) * 100);
        $this->assign("url", $url);
        $this->assign("topic_name", $topic_name);
        $this->assign("topicId", $topicId);
        $this->assign("user_name", $user_name);
        $this->assign("user_id", $user_id);
        $this->assign("submodule_type", $submodule_type);
        $this->assign("batch_num", $batch_num);
        $this->assign("date_time", $date);
        $this->assign("spent_time", $spent_time);
        $this->assign("estimates_time", $estimates_time);
        $this->assign("xiaolv", $xiaolv);
        $this->assign("tag_names", $tag_names);
        $this->assign("user_ability_info", $user_ability_info);
        $this->assign("zhangwolv", $zhangwolv);
        $this->assign("hasAnswerQuestions", $hasAnswerQuestions);
        $this->assign("weakElements", $weakElements);
        $this->assign("weakElements_num", count($weakElements));
        $this->assign("knowledge_list_num", count($knowledge_list));
        $this->assign("new_knowledgeList_num", count($right_list));
        $this->assign("sum_num", $sum_num);
        $this->assign("right_num", $right_num);
        $this->assign("right_list", $right_list);
        $this->assign("accuracy", $accuracy);
        return $this->fetch("preReportMobile");
    }

    /**
     * 知识点学习报告手机端。
     */
    public function learningReportMobile() {
        error_reporting(E_ALL ^ E_NOTICE);
        $request = Request::instance();
        $url = $request->url(TRUE);
        if (!$_SERVER["QUERY_STRING"]) {
            $url .= "?a=1";
        }
        $topicId = $request->param("topicId", 9016);
        $user_id = $request->param("user_id");
        $is_all = $request->param("is_all", 0);
        $batch_num = $request->param("batch_num", 0);
        $submodule_type = $request->param("submodule_type", 1);
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
            $url .= "&user_id=" . $user_id;
        }
        $topicService = new TopicV2Service();
        $knowledgeService = new KnowledgeV2Service();
        $userService = new UserService();
        $summerUserService = new SummerUserService();
        $questionService = new QuestionService();
        $summer_question_service = new SummerQuestionService();

        $module_type = config("l1_module_type");
        if (!$is_all) {
            if (!$batch_num) {
//            $batch_num=$questionService->checkBatchNum($user_id ,$topicId, $module_type,1);
                $batch_num = $summer_question_service->checkBatchNum($user_id ,$topicId, $module_type,1);
            }
            $url .= "&batch_num=" . $batch_num;
        }
        $user_info = $userService->getUserName($user_id);
         if(isset($user_info["real_name"]) && !empty($user_info["real_name"])){
            $user_name = $user_info["real_name"];
        } else {
            $user_name = $user_info["user_name"];
        }
        $topic = $topicService->getTopicByTopicId($topicId);
//        $kmap_code = $topicService->getMainKmapCode($topicId);
        $kmap_code_all = $topicService->getKmapCodeAll($topicId);
        $topic_name = $topic["topic_name"];
        $knowledge_list_all = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        $err_tag_code = [];
        foreach ($knowledge_list_all as $key => $value) {
            $tag_code = $value["tag_code"];
            $tag_info = $knowledgeService->getKnowledgeByCode($tag_code);
            if (!isset($tag_info["tag_name"])) {
                $err_tag_code = $tag_code;
                $tag_names[$tag_code] = "未定义" . $tag_code;
            } else {
                $tag_names[$tag_code] = $tag_info["tag_name"];
            }
        }
//        $tag_names["c020206"] = "已删除知识点c020206";
        $hasAnswerQuestions = $summerUserService->getL2UserHasAnsweredQuestionsByModule($user_id, $topicId, $module_type, $submodule_type, $batch_num);
        $user_ability_info = $summerUserService->getUserTagCodeAbility($user_id, $topicId, "", $module_type, $submodule_type);
        if ($hasAnswerQuestions) {
            $lastQuestion = end($hasAnswerQuestions);
            $date = date("Y/m/d", $lastQuestion["ctime"]);
        } else {
            $date = date("Y/m/d");
        }
//        var_dump($hasAnswerQuestions);die;
        $sum_num = 0;
        $right_num = 0;
        $spent_time = 0;
        $estimates_time = 0;
        foreach ($hasAnswerQuestions as $key => $value) {
            $sum_num++;
            $spent_time += $value["spent_time"];
            $estimates_time += $value["estimates_time"];
            if ($value["is_right"] == 1) {
                $right_num++;
            }
        }
        if ($sum_num) {
            $accuracy = ceil($right_num / $sum_num * 100);
        } else {
            $accuracy = 0;
        }
        if (!$estimates_time) {
            $xiaolv = 0;
        } else {
            $xiaolv = sprintf("%.2f", (ceil($spent_time / 1000) / $estimates_time));
        }
        $spent_time = ceil($spent_time / 1000);
        $spent_time = $this->spentTime($spent_time);

        $estimates_time = dateFormatForMicroTime($estimates_time);
        $knowledge_list = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        foreach ($knowledge_list as $key => $value) {
            $knowledge_list[$key] = $value["tag_code"];
        }
//        $algoLogic = new SummerAlgoLogic();
        $weakElements = $summerUserService->getUserLastWeakElements($user_id, $topicId, $module_type, 1, $batch_num,$knowledge_list);
        if (!$weakElements) {
            $right_list = $knowledge_list;
        } else {
            $right_list = array_diff($knowledge_list, $weakElements);
        }
        if(count($knowledge_list)){
            $zhangwolv = ceil((count($right_list) / count($knowledge_list)) * 100);
        } else {
            $zhangwolv=0;
        }
        $this->assign("url", $url);
        $this->assign("topic_name", $topic_name);
        $this->assign("topicId", $topicId);
        $this->assign("user_name", $user_name);
        $this->assign("user_id", $user_id);
        $this->assign("submodule_type", $submodule_type);
        $this->assign("batch_num", $batch_num);
        $this->assign("date_time", $date);
        $this->assign("spent_time", $spent_time);
        $this->assign("estimates_time", $estimates_time);
        $this->assign("xiaolv", $xiaolv);
        $this->assign("tag_names", $tag_names);
        $this->assign("user_ability_info", $user_ability_info);
        $this->assign("zhangwolv", $zhangwolv);
        $this->assign("hasAnswerQuestions", $hasAnswerQuestions);
        $this->assign("weakElements", $weakElements);
        $this->assign("weakElements_num", count($weakElements));
        $this->assign("knowledge_list_num", count($knowledge_list));
        $this->assign("new_knowledgeList_num", count($right_list));
        $this->assign("sum_num", $sum_num);
        $this->assign("right_num", $right_num);
        $this->assign("right_list", $right_list);
        $this->assign("accuracy", $accuracy);
        return $this->fetch("learningReportMobile");
    }

    public function reportMobileMathQues() {
        error_reporting(E_ALL ^ E_NOTICE);
        $request = Request::instance();
        $url = $request->url(TRUE);
        $topicId = $request->param('topicId', 9016);
        $user_id = $request->param('user_id', 1);
        $module_type = $request->param('module_type', config("l1_module_type"));
        $submodule_type = $request->param('submodule_type', 1);
        $batch_num = $request->param("batch_num", 0);
        $report_num = $request->param("report_num");
//        $is_error = $request->param('is_error', null);
        $page_num = $request->param('page', 1);
        $pageSize = $request->param('page_size', 20); //默认20条   
//        if ($is_error) {
//            $is_right = 0;
//        } else {
//            $is_right = null;
//        }
        if ($report_num == 1) {
            $report_name = "阶段测试报告";
        } elseif ($report_num == 2) {
            $report_name = "基础先行测试报告";
        } elseif ($report_num == 3) {
            $report_name = "知识点学习报告";
        } else {
            $report_name = "当堂报告";
        }
        $startIndex = ($page_num - 1) * $pageSize + 1;
        $summerUserService = new SummerUserService();
        $topicService = new TopicV2Service();
        $knowledgeService = new KnowledgeV2Service();
        $kmap_code_all = $topicService->getKmapCodeAll($topicId);
        $knowledge_list_all = $topicService->getKmapInfoByKmapCode($kmap_code_all);
        $err_tag_code = [];
        foreach ($knowledge_list_all as $key => $value) {
            $tag_code = $value["tag_code"];
            $tag_info = $knowledgeService->getKnowledgeByCode($tag_code);
//            var_dump($tag_info);die;
            if (!isset($tag_info["tag_name"])) {
                $err_tag_code = $tag_code;
                $tag_names[$tag_code] = "未定义" . $tag_code;
            } else {
                $tag_names[$tag_code] = $tag_info["tag_name"];
            }
        }
//        $tag_names["c020206"] = "已删除知识点c020206";
        $hasAnswerQuestions = $summerUserService->getUserAnsweredQuestionsByModule($user_id, $topicId, $batch_num, $module_type, $submodule_type);
//        var_dump($hasAnswerQuestions);die;
        $sum_num = 0;
        $right_num = 0;
        foreach ($hasAnswerQuestions as $key => $value) {
            $sum_num++;
            if ($value["is_right"] == 1) {
                $right_num++;
            }
        }
        $queryParam = $request->param();
        $pageParams = [
            "query" => $queryParam,
        ];
        $hasAnswerQuestionsWithDetail = $summerUserService->getUserHasAnsweredQuestionsWithDetail($user_id, $topicId, $module_type, $submodule_type, null, $page_num, $pageSize, $queryParam);
        $totalPage = ceil($sum_num / $pageSize);

        $prevPage = $page_num - 1;
        $nextPage = $page_num + 1;

        if ($prevPage < 1) {
            $prevPage = 1;
        }

        if ($nextPage > $totalPage) {
            $nextPage = $totalPage;
        }
        $queryParam["page"] = $prevPage;
        $prevPageUrl = url("reportMobileMathQues", $queryParam);

        $queryParam["page"] = $nextPage;
        $nextPageUrl = url("reportMobileMathQues", $queryParam);

        $this->assign("prevPageUrl", $prevPageUrl);
        $this->assign("nextPageUrl", $nextPageUrl);
        $this->assign("totalPage", $totalPage);
        $this->assign("prevPage", $prevPage);
        $this->assign("nextPage", $nextPage);


        $this->assign("report_name", $report_name);
        $this->assign("startIndex", $startIndex);
        $this->assign("topicId", $topicId);
        $this->assign("user_id", $user_id);
        $this->assign("module_type", $module_type);
        $this->assign("submodule_type", $submodule_type);
        $this->assign("page_num", $page_num);
        $this->assign("url", $url);
//        $this->assign("is_error", $is_error);
        $this->assign("page_size", $pageSize);
        $this->assign("batch_num", $batch_num);
        $this->assign("hasAnswerQuestions", $hasAnswerQuestionsWithDetail["data"]);
        $this->assign("page", $hasAnswerQuestionsWithDetail["page"]);
        $this->assign("err_num", $sum_num - $right_num);
        $this->assign("sum_num", $sum_num);
        $this->assign("tag_names", $tag_names);
        $this->assign("right_num", $right_num);
//        $this->assign("accuracy", $accuracy);
        return $this->fetch("reportMobileMathQues");
    }

    public function  l2XianceReport()
    {
//        error_reporting(E_ALL ^ E_NOTICE);
        $request = Request::instance();
        $algoLogic = new AlgoLogic();
        //$topicId=$this->getTopicId();
        $request=Request::instance();
        $topicId=$request->param("topicId");
        $user_id= $request->param("user_id");
        $topic_v2_service = new TopicV2Service();
        $knowledge_v2_service =  new KnowledgeV2Service();
        $topic=$topic_v2_service->getTopicByTopicId($topicId);
        $topicNmae=$topic["topic_name"];
        $user_service=new UserService();
        $summer_user_service = new SummerUserService();
        $xiance_module_type = config('l2_xiance_module_type');
        $summer_cindex_service = new SummerCindexService();
        $kmap_code = $summer_cindex_service->getCxianceKmapCode($user_id,$topicId);
        $knowledgeList = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);
        $weakElements = $algoLogic->getL2WeakElements($user_id, $topicId);

        $is_show_report =  0;
        $is_show_nextstep=0;

        foreach ($knowledgeList as $key => $knowledge) {
            $tag_code = $knowledge['tag_code'];
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
            foreach ($knowledgeList as $key => $val) {
                if ($val['tag_code'] == $need_toLearn_next_tag_code) {
                    $knowledge_info =  $knowledge_v2_service->getKnowledgeByCode($val['tag_code']);
                    $need_toLearn_next_tag_name = $knowledge_info['tag_name'];
                }
            }
        }
        $total_knowledge_num = count($knowledgeList);  //总知识点数量.
        $weakElements_num = count($weakElements); //薄弱知识点数量.

        ///////////////////获取薄弱知识点的名字/////////////////////
        $weakElements_tag_name=[];
        //总的知识点 剔除为掌握的
        $new_knowledgeList=$knowledgeList;
        if($weakElements&&is_array($weakElements)){
            $knowledge_v2_service = new KnowledgeV2Service();
            foreach($weakElements as $k=>$v){
                $weakElements_tag_name[]=$knowledge_v2_service->getKnowledgeByCode($v);
                foreach ($new_knowledgeList as $key => $value) {
                    if($value['tag_code']==$v)unset ($new_knowledgeList[$key]);//剔除为掌握的
                }
            }
        }

        foreach ($new_knowledgeList  as $k=>$v)
        {
            $tag_info = $knowledge_v2_service->getKnowledgeByCode($v['tag_code']);
            $new_knowledgeList[$k]['tag_name'] = $tag_info['tag_name'];
        }

        /////////////////获取薄弱知识点的名字end//////////////////////
        $has_learned_num = $total_knowledge_num - $weakElements_num;  //已学会知识点
        //或得此专题所有用户的平均攻克能力值.
        $summer_user_service = new SummerUserService();

        //获取用户所有知识点的平均掌握情况.
        $average_ability = $user_service->getUserAverageAbility($user_id, $topicId, $xiance_module_type);

        //用户做过的试题信息.
        $has_answered_questions = $summer_user_service->getUserHasAnsweredQuestionsByModule($user_id, $topicId, $xiance_module_type);

//        $user_id = $this->getUserId();
        $xxcsIsAllRight = $user_service->getUserIsAllRight($topicId, $xiance_module_type, $user_id);

        if($has_learned_num+$weakElements_num){
            $has_learned_percent=round(($has_learned_num/($has_learned_num+$weakElements_num))*100);//掌握的百分比
        } else {
            $has_learned_percent=0;
        }
        

        $accuracy=$user_service->getUserExamDetail($user_id, $topicId, $xiance_module_type);


        if(isset($xianceStep['is_end'])&&$xianceStep['is_end']==1)$xiance_is_end=1;
        $userService=new UserService();

        $bxblStep=$userService->getUserStep($topicId,$user_id,config('l2_bxbl_module_type'));//边学边练
        $bxbl_is_end=0;
        if(isset($bxblStep['is_end'])&&$bxblStep['is_end']==1)$bxbl_is_end=1;

        //判断现行测试边学边练和竞赛扩展是否做完
        $xianceStep=$userService->getUserStep($topicId,$user_id,config('l2_xiance_module_type'));//边学边练
        $xiance_is_end=0;

        $zhlxStep=$userService->getUserStep($topicId,$user_id,config('l2_jingsai_module_type'));//综合练习
        $zhlx_is_end=0;
        if(isset($zhlxStep['is_end'])&&$zhlxStep['is_end']==1)$zhlx_is_end=1;;

        $this->assign('xiance_is_end',0);
        $this->assign('xiance_is_end',$xiance_is_end);

        $this->assign("knowledgeList_tag_name", $new_knowledgeList);//已经掌握的知识点
        $this->assign("weakElements_tag_name", $weakElements_tag_name);
        $this->assign("is_all_right", $xxcsIsAllRight);
        $this->assign("accuracy", $accuracy);//正确率
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
        $this->assign('module_type',$xiance_module_type);
        $this->assign('bxbl_is_end',$bxbl_is_end);
        $this->assign('zhlx_is_end',$zhlx_is_end);
        $this->assign("is_show_report",$is_show_report);
        $this->assign('is_show_nextstep',$is_show_nextstep);
        return $this->fetch("cindex/preReport");
    }


    /**
     * L2部分的边学边练的报告。
     * @return mixed
     */
    public function l2StudyReport()
    {

        $request = Request::instance();
        $topicId = $request->param('topicId');
        $user_id = $request->param("user_id");
        $module_type = config('l2_bxbl_module_type');
        $user_service = new UserService();
        $summer_user_service =new SummerUserService();
        $has_answered_questions = $summer_user_service->getUserHasAnsweredQuestionListByModule($user_id, $topicId, $module_type);
//        var_dump($has_answered_questions);die;
        $user_ability = $user_service->getUserAbility($user_id, $topicId, $module_type);
        $algoLogic = new AlgoLogic();


        $topic_v2_service = new TopicV2Service();
        $summer_cindex_service  = new SummerCindexService();
        $knowledge_v2_service = new KnowledgeV2Service();
        $kmap_code = $summer_cindex_service->getCxianceKmapCode($user_id,$topicId);
        $knowledgeList = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);

        $weakElements = $algoLogic->getL2WeakElements($user_id, $topicId);

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
        if($total_knowledge_num){
             $scale = round(($total_knowledge_num - $not_learned_weakElements_num) / $total_knowledge_num, 2) * 100;  //知识点掌握率
        } else {
            $scale=0;
        }
       

        $tag_info = array();
        $tag_ability_report = array();


        foreach ($user_ability as $key => $ability_num) {
            foreach ($knowledgeList as $k => $knonwledge) {
                if ($knonwledge["tag_code"] === $key) {
                    $knowledge_info =  $knowledge_v2_service->getKnowledgeByCode($knonwledge['tag_code']);

                    $tag_info['tag_name'] = $knowledge_info['tag_name'];
                    $tag_info['tag_code'] = $knonwledge['tag_code'];
                    $tag_info['ability'] = $ability_num;
                    $tag_ability_report[] = $tag_info;
                    break;
                }
            }
        }

        $topicInfo = $topic_v2_service->getTopicByTopicId($topicId);

        $topic_name = $topicInfo['topic_name'];

        $accuracy = $user_service->getUserExamDetail($user_id, $topicId, $module_type);


        $is_show_report =  0;
        $is_show_nextstep=0;

        //判断现行测试边学边练和竞赛扩展是否做完
        $user_id= $this->getUserId();
        $userService=new UserService();
        $xianceStep=$userService->getUserStep($topicId,$user_id,config('l2_xiance_module_type'));//边学边练
        $xiance_is_end=0;
        if(isset($xianceStep['is_end'])&&$xianceStep['is_end']==1)$xiance_is_end=1;
        $bxblStep=$userService->getUserStep($topicId,$user_id,config('l2_bxbl_module_type'));//边学边练
        $bxbl_is_end=0;
        if(isset($bxblStep['is_end'])&&$bxblStep['is_end']==1)$bxbl_is_end=1;;
        $zhlxStep=$userService->getUserStep($topicId,$user_id,config('l2_jingsai_module_type'));//综合练习
        $zhlx_is_end=0;
        if(isset($zhlxStep['is_end'])&&$zhlxStep['is_end']==1)$zhlx_is_end=1;;

        $is_show_report =0;
        $is_show_nextstep=0;

        $this->assign("topic_name", $topic_name);
        $this->assign("accuracy", $accuracy); //正确率
        $this->assign("has_answered_questions", $has_answered_questions);
        $this->assign("frist_has_learned_num", $frist_has_learned_num);
        $this->assign("has_learned_weakElements_num", $has_learned_weakElements_num);
        $this->assign("not_learned_weakElements_num", $not_learned_weakElements_num);
        $this->assign("scale", $scale);
        $this->assign("tag_ability_report", json_encode($tag_ability_report));
        $this->assign("topicId", $topicId);
        $this->assign('module_type',$module_type);
        $this->assign('xiance_is_end',0);
        $this->assign('xiance_is_end',$xiance_is_end);
        $this->assign('bxbl_is_end',$bxbl_is_end);
        $this->assign('zhlx_is_end',$zhlx_is_end);
        $this->assign('is_show_report',$is_show_report);
        $this->assign('is_show_nextstep',$is_show_nextstep);
        return $this->fetch("cbxbl/studyReport");
    }


    /**
     * 暑期L2竞赛拓展报告页。
     */
    public function  l2ZhlxReport()
    {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $user_id = $request->param("user_id");

        $algoLogic = new AlgoLogic();
        $user_service = new UserService();

        $knowledge_v2_service = new KnowledgeV2Service();
        $knowledgeList = $knowledge_v2_service->getKnowledgeListByTopicId($topicId);


        $kmap_code = config("kmap_code");
        $module_type = config('zonghe_module_type');
        $jingsai_module_type = config('l2_jingsai_module_type');

//        $weakElements = $algoLogic->getL2WeakElements($user_id,$topicId);
//
//        foreach ($knowledgeList as $key =>$knowledge )
//        {
//            $tag_code = $knowledge['tag_code'];
//            if(empty($weakElements))
//            {
//                $knowledgeList[$key]['is_weak'] = 0;
//            }else{
//                if(in_array($tag_code,$weakElements))
//                {
//                    $knowledgeList[$key]['is_weak'] = 1;
//                }else{
//                    $knowledgeList[$key]['is_weak'] = 0;
//                }
//            }
//
//        }
//
        $is_show_report =  0;
        $is_show_nextstep=0;
//        $total_knowledge_num = count($knowledgeList);  //总知识点数量.
//        $weakElements_num =  count($weakElements); //薄弱知识点数量.
//        $has_learned_num = $total_knowledge_num-$weakElements_num;  //已学会知识点
//        $zhlx_service = new ZhlxService();
//        $tag_info =  $zhlx_service->getL2UserZhlxTagReport($user_id,$topicId);
//        Log::record("----333-----");
//        //或得此专题所有用户的平均攻克能力值.
//        $average_num = $zhlx_service->getL2ZhlxAverageNum($user_id,$topicId,$jingsai_module_type);
//        Log::record("----56666-----");
//        if($has_learned_num>$average_num)
//        {
//            $result = 1;
//        }else{
//            $result = 0;
//        }
//        $bxbl_zhlx_Learned_nums=$user_service->getL2Learned_nums($user_id, $topicId,$knowledgeList);//获取综合练习和边学边练攻克值>=0.8
//        //获取用户所有知识点的平均掌握情况.
//        Log::record("----555-----");
//
//        $average_ability =  $user_service->getUserAverageAbility($user_id,$topicId,$jingsai_module_type);
//        Log::record("----666-----");

        //用户做过的试题信息.
         $summer_user_service =new SummerUserService();
        $has_answered_questions = $summer_user_service->getUserHasAnsweredQuestionListByModule($user_id, $topicId, $jingsai_module_type);
//        $has_answered_questions= $user_service->getUserHasAnsweredQuestionsByModule($user_id,$topicId,$jingsai_module_type);
        $topicService=new TopicService();
        $topic=$topicService->getTopicByTopicId($topicId);
        $topicNmae=$topic["topic_name"];
        $api_gate_service = new ApiGateService();
        $getZhlxQuestionIds = $api_gate_service->getZhlxQuestionIds($topicId);//获取当前知识点下有没有做错的 如果有就是继续做
        Log::record("----2222-----");

        $this->assign("getZhlxQuestionIds", $getZhlxQuestionIds);
        Log::record("----333-----");

        $xxcsIsAllRight = $user_service->getUserIsAllRight($topicId, $jingsai_module_type, $user_id);
//        //判断现行测试边学边练和竞赛扩展是否做完
//        $userService=new UserService();
//        $xianceStep=$userService->getUserStep($topicId,$user_id,config('l2_xiance_module_type'));//边学边练
//        $xiance_is_end=0;
//        if(isset($xianceStep['is_end'])&&$xianceStep['is_end']==1)$xiance_is_end=1;
//
//        $bxblStep=$userService->getUserStep($topicId,$user_id,config('l2_bxbl_module_type'));//边学边练
//        $bxbl_is_end=0;
//        if(isset($bxblStep['is_end'])&&$bxblStep['is_end']==1)$bxbl_is_end=1;;
//        $zhlxStep=$userService->getUserStep($topicId,$user_id,config('l2_jingsai_module_type'));//综合练习
//        $zhlx_is_end=0;
//        if(isset($zhlxStep['is_end'])&&$zhlxStep['is_end']==1)$zhlx_is_end=1;
        $user_hasAnswered_question =  $summer_user_service->getUserHasAnsweredQuestionsByModuleType($user_id,$topicId,$jingsai_module_type);
        $zongtishuliang = count($user_hasAnswered_question);
        $user_hasAnswered_all_right_question =  $summer_user_service->getUserAnsweredAllRightQuestionsByModuleType($user_id,$topicId,$jingsai_module_type);
        $daduitishuliang = count($user_hasAnswered_all_right_question);
        if($zongtishuliang){
            $daduibi=ceil($daduitishuliang/$zongtishuliang*100);
        } else {
            $daduibi=0;
        }
        

//        $zongtishuliang=Db::name('user_exam_detail')->where(['topicId'=> input('topicId', 0),'user_id'=> session('userInfo.user_id'),'module_type'=>config('l2_jingsai_module_type')])->count();
//        $daduitishuliang=think\Db::name('user_exam_detail')->where(['is_right'=>'1','topicId'=> input('topicId', 0),'user_id'=> session('userInfo.user_id'),'module_type'=>config('l2_jingsai_module_type')])->count();
//        $this->assign('zhlx_is_end',$zhlx_is_end);
//        $this->assign('bxbl_is_end',$bxbl_is_end);
        $this->assign("zongtishuliang",$zongtishuliang);
        $this->assign("daduitishuliang",$daduitishuliang);
        $this->assign("daduibi",$daduibi);

//        $this->assign('xiance_is_end',0);
//        $this->assign('xiance_is_end',$xiance_is_end);
        if(count($has_answered_questions)==0){
            $this->assign("has_learned_percent",  0);
        }else{
            $this->assign("has_learned_percent",  ceil($xxcsIsAllRight/count($has_answered_questions)*100));
        }
        $is_show = 1;
        $this->assign("xiance_count",  count($user_service->getUserHasAnsweredQuestionsByModuleType($user_id, $topicId, config('l2_xiance_module_type'))));
//
//        $this->assign("tag_info",  json_encode($zhlx));
//        $this->assign("total_knowledge_num",$total_knowledge_num);
//        $this->assign("weakElements_num",$weakElements_num);
//        $this->assign("has_learned_num",$has_learned_num+$bxbl_zhlx_Learned_nums);//先行测试的攻克数量 + 综合练习和边学边练攻克数量
        $this->assign("has_answered_questions",$has_answered_questions);
//        $this->assign("result",$result);
//        $this->assign("average_num",$average_num);
//        $this->assign("average_ability",$average_ability);
//        $this->assign("tagInfo",json_encode($tag_info));
        $this->assign("topicId", $topicId);
        $this->assign("topic_name", $topicNmae);
        $this->assign("is_show",$is_show);
        $this->assign('is_show_report',$is_show_report);
        $this->assign('is_show_nextstep',$is_show_nextstep);
        return $this->fetch("czhlx/zhlxReport");

    }


}
