<?php

/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 17/5/31
 * Time: 22:58
 */

namespace service\services\summer;

use service\services\BaseQuestionV2Service;
use service\services\CommonService;
use service\services\KnowledgeV2Service;
use service\services\TopicV2Service;
use service\services\UserService;
use think\Db;
use think\Log;
use think\Cache;
use service\log\LogService;
use service\services\QuestionService;
use service\services\KnowledgeService;
use service\algo\AlgoLogic;
use service\algo\SummerAlgoLogic;

class SummerUserService extends CommonService
{

    private $domain;

    function __construct()
    {
        $this->domain = $_SERVER['SERVER_NAME'];
    }

    private function generateUrl($url)
    {
        $topicId = session('topicId');
        $url = "http://" . $this->domain . url($url, ['topicId' => $topicId]);
        return $url;
    }

    /**
     * 获取过度页信息
     */
    public function getMiddleSetInfo($topicId)
    {
        $middle_num = session($topicId.":middle_num");
        $return_data = array(
            "10" => array(    //每15到题出一个报告页。
                'is_end' => 0,
                'report_url' => $this->generateUrl("summer/Report/stageReport"),
                'next_url' => $this->generateUrl("summer/Index/index"),
                'next_module_name' => "下15题的学习",
                'next_module_type' => 8,
                'pre_module_name' => 15 * session('jump_num') . "道题",
                'pre_module_type' => 8
            ),
            "11" => array(
                'is_end' => 0,  //先行测试做完，并且还有薄弱知识点。
                'report_url' => $this->generateUrl("summer/Report/preReport"),
                'next_url' => $this->generateUrl("summer/Index/studyGate"),
                'next_module_name' => "L1基础学习阶段",
                'next_module_type' => 8,
                'pre_module_name' => "基础先行测试",
                'pre_module_type' => 8
            ),
            "12" => array(
                'is_end' => 0,    //L1学习模块完成一个知识点的学习。
//                'report_url' => $this->generateUrl("summer/Index/learningReport"),
                'report_url' => $this->generateUrl("summer/Report/learningReport"),
                'next_url' => $this->generateUrl("summer/Index/studyGate"),
                'next_module_name' => "下一个知识点",
                'next_module_type' => 8,
                'pre_module_name' => "一个知识点的学习",
                'pre_module_type' => 8
            ),
            "13" => array(
                'is_end' => 0,    //L1学习模块完成1轮12个知识点的学习。
//                'report_url' => $this->generateUrl("summer/Index/learningReport"),
                'report_url' => $this->generateUrl("summer/Report/learningReport"),
                'next_url' => $this->generateUrl("summer/Index/backTestIndex"),
                'next_module_name' => "后置知识点测试",
                'next_module_type' => 8,
                'pre_module_name' => "L1基础学习模块",
                'pre_module_type' => 8
            ),
            "14" => array(
                'is_end' => 0,    //先行测试做完，并且没有薄弱知识点。
                'report_url' => "http://" . $this->domain .url("summer/Report/learningReport", ['topicId' => $topicId,"is_all"=>1]),
//                'report_url' => $this->generateUrl("summer/Report/preReport"),
                'next_url' => $this->generateUrl("summer/Cindex/startIndex"),
                'next_module_name' => "L2综合学习",
                'next_module_type' => 9,
                'pre_module_name' => "L1学习",
                'pre_module_type' => 8
            ),

        );

        return $return_data[$middle_num];
    }

    /**
     * 获得先测第一次的大知识图谱测试后的总成绩。
     */
    public function getXianceLearnedAlgoScale($userId, $topicId)
    {
        if (!$userId) {
            $userId = $this->getUserId();
        }
        $module_type = config('l1_module_type');
        $where['user_id'] = $userId;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $userAnsweredQuestions = Db::name('user_algo_status')->where($where)->find();
        $topic_v2_service = new TopicV2Service();
        $bigMap_code_list = $topic_v2_service->getBigKmapTagCodeList($topicId);
        $orign_weaks = json_decode($userAnsweredQuestions['orign_weaks']);
        $measure_num = count($bigMap_code_list);
        $orign_num = count($orign_weaks);
        Log::info(__METHOD__ . "---getXianceFirstLearnedScale------measure_num: $measure_num , ------orign_weaks: $orign_num");
        $scale = 1 - ($orign_num / $measure_num);
        if($scale<0)
        {
            $scale = 0;
        }elseif ($scale>1)
        {
            $scale = 1;
        }
        return $scale;
    }

    /**
     * 获得用户第一次做完先测返回的新构建的图谱。
     */
    public function getXianceLearnedAlgoInfo($user_id, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['batch_num'] = 1;   // 一直都是第一次算法的图谱编码，做为最终编码。
        $userAnsweredQuestions = Db::name('user_algo_status')->order('id ASC')->where($where)->find();
        return $userAnsweredQuestions;
    }

    /**
     * 插入user_exam_step_log 记录
     * @param type $topicId
     * @param type $module_type 专题类型
     * @return type
     */
    public function insertUserTinyStep($topicId, $module_type, $submodule_type, $batch_num, $is_end = 1)
    {
        $user_id = $this->getUserId();
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['module_type'] = $module_type;
        $data['submodule_type'] = $submodule_type;
        $data['batch_num'] = $batch_num;
        $data['is_end'] = $is_end;
        $data['ctime'] = time();

        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['is_end'] = $is_end;
        $where['submodule_type'] = $submodule_type;
        $where['batch_num'] = $batch_num;

        $find_data = Db::name('user_exam_tiny_step_log')->where($where)->field('id')->find();
        if (empty($find_data)) {
            $id = Db::name('user_exam_tiny_step_log')->insert($data);
        } else {
            $id = $find_data['id'];
        }
        return $id;
    }

    /**
     * 获取用户详细的步骤。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $submodule_type
     * @param $batch_num
     * @return int|mixed
     */
    public function getUserTinyStep($user_id, $topicId, $module_type, $submodule_type, $batch_num)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }

        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $where['batch_num'] = $batch_num;
        $return_data = Db::name('user_exam_tiny_step_log')->where($where)->find();
        if (empty($return_data)) {
            $is_end = 0;
        } else {
            $is_end = $return_data['is_end'];
        }
        return $is_end;
    }

    /**
     * 获取用户的章节掌握率
     * @param $user_id
     * @param $topicId
     * @param $chapter_code
     */
    public function getUserChapterScale($user_id, $topicId, $chapter_code)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['chapter_code'] = $chapter_code;
        $user_chapter_list = Db::name('user_question_relation_chapter')->where(['user_id' => $user_id, 'topicId' => $topicId, 'chapter_code' => $chapter_code])->select();
        $total_num = count($user_chapter_list);
        $wrong_num = 0;
        $right_num = 0;
        foreach ($user_chapter_list as $k => $v) {
            if ($v['is_right'] == 1) {
                $right_num++;
            }
        }
        if ($total_num != 0) {
            $scale = $right_num / $total_num;
        } else {
            $scale = 0;
        }
        return $scale;
    }

    /**
     * 根据模块获取用户在某模块下已做题数.
     * @param null $user_id
     * @param $topicId
     * @param $module_type
     * @return array
     */
    public function getUserHasAnsweredQuestionsByModule($user_id = null, $topicId, $module_type = null)
    {

        $return_arr = array();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if ($topicId) {
            if (is_array($topicId)) {
                $where['topicId'] = array("in", $topicId);
            } else {
                $where['topicId'] = $topicId;
            }
        }

        if ($module_type) {
            $where['module_type'] = $module_type;
        }

        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();
        $question_service = new QuestionService();

        $knowledge_v2_service = new KnowledgeV2Service();
        //$kmap_code = config("kmap_code");
        foreach ($answeredInfo as $key => $val) {
//            $return_arr[$key]['question_id'] = $val['question_id'];
            $return_info = array();
            $question_v2_service = new BaseQuestionV2Service();
            $return_info = $question_v2_service->getQuestionById($val['question_id']);
            $return_info['tag_code'] = $val['tag_code'];
            $return_info['module_type'] = $val['module_type'];
            $return_info['right_answer'] = $val['right_answer'];
            $return_info['user_answer'] = $val['user_answer'];
            $return_info['stime'] = $val['stime'];
            $return_info['ctime'] = $val['ctime'];
            $return_info['spent_time'] = $val['spent_time'];
            $return_info['is_view_analyze'] = $val["is_view_analyze"];
            $return_info['is_view_answer'] = $val["is_view_answer"];
            $return_info['exam_detail_id'] = $val["id"];
            $return_info['topicId'] = $val["topicId"];
            $return_info['right_answer_base64'] = $val['right_answer_base64'];
            // $return_info['user_answer_base64'] = $val['user_answer_base64'];

            $tag = $knowledge_v2_service->getKnowledgeByCode($val["tag_code"]);
            $tag_name = "";
            if ($tag) {
                $tag_name = $tag["tag_name"];
            }
            $return_info['tag_name'] = $tag_name;


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
     * 根据模块获取用户在某模块下已做题数.
     * @param null $user_id
     * @param $topicId
     * @param $module_type
     * @return array
     */
    public function getUserHasAnsweredQuestionNumByModule($user_id = null, $topicId, $module_type = null)
    {

        $return_arr = array();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if ($topicId) {
            if (is_array($topicId)) {
                $where['topicId'] = array("in", $topicId);
            } else {
                $where['topicId'] = $topicId;
            }
        }

        if ($module_type) {
            $where['module_type'] = $module_type;
        }

        $answeredInfo = Db::name('user_exam_detail')->where($where)->field('id')->select();
        return $answeredInfo;
    }


    /**
     * 查询一行user_exam_step_log 数据
     * @param type $where 查询条件
     * @return type
     */
    public function getUserStep($topicId, $user_id, $module_type = 1)
    {
        $where['topicId'] = $topicId;
        $where['user_id'] = $user_id;
        $where['module_type'] = $module_type;
        $row = Db::name('user_exam_step_log')->where($where)->find();
        return $row;
    }

    /**
     * 判断用户已经达标。
     * @param $user_id
     * @param $topicId
     * @param $tag_code
     * @param $module_type
     * @param $submodule_type
     * @param $grandson_module_type
     */
    public function getUserBaseStudyIsLearned($user_id, $topicId, $batch_num, $tag_code, $module_type, $submodule_type, $grandson_module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $where['grandson_module_type'] = $grandson_module_type;
        $where['tag_code'] = $tag_code;
        $where['batch_num'] = $batch_num;
        $user_exam_detail = Db::name('user_exam_detail')->where($where)->select();
        $total_num = count($user_exam_detail);
        $num = 0;
        foreach ($user_exam_detail as $k => $v) {
            if ($v['used_type'] == 2) {
                $num++;
            }
        }
        $is_end = 0;
        if ($num >= 2) {
            $is_arrived = $this->getUserBaseStudyAbilityIsArrived($user_id, $topicId, $tag_code);
            if ($is_arrived) {
                $is_end = 1;
            } else {
                if ($total_num >= 4) {
                    $is_end = 1;
                }
            }
        } else {
            if ($total_num >= 4) {
                $is_end = 1;
            } else {
                $is_end = 0;
            }
        }
        return $is_end;
    }

    /**
     * 获取用户L1基础过程能力值是否达标。
     * @param $user_id
     * @param $tag_code
     */
    public function getUserBaseStudyAbilityIsArrived($user_id, $topicId, $tag_code)
    {
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['tag_code'] = $tag_code;
        $user_ability_info = Db::name('user_ability')->order("id desc ")->find();
        $ability = (int)$user_ability_info['ability'];
        if ($ability > 0.6) {
            $is_arrived = 1;
        } else {
            $is_arrived = 0;
        }
        return $is_arrived;
    }


    /**
     * 判断用户已经达标。
     * @param $user_id
     * @param $topicId
     * @param $tag_code
     * @param $module_type
     * @param $submodule_type
     * @param $grandson_module_type
     */
    public function getUserGgStudyIsLearned($user_id, $topicId, $batch_num, $tag_code, $module_type, $submodule_type, $grandson_module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $summer_question_service = new SummerQuestionService();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['batch_num'] = $batch_num;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $where['grandson_module_type'] = $grandson_module_type;
        $where['tag_code'] = $tag_code;
        $where['batch_num'] = $batch_num;
        $user_exam_detail = Db::name('user_exam_detail')->where($where)->select();

        $total_num = count($user_exam_detail);
        $num = 0;
        foreach ($user_exam_detail as $k => $v) {
            if ($v['used_type'] == 2) {
                $num++;
            }
        }
        $is_end = 0;
        if ($num >= 2) {

            $is_arrived = $this->getUserBaseStudyAbilityIsArrived($user_id, $topicId, $tag_code);
            if ($is_arrived) {
                $is_end = 1;
            } else {
                if ($total_num >= 4) {
                    $is_end = 1;
                }
            }
        } else {
            //此处本不用判断总数是否大于4，为了做容错，故此处也加上了。
            if ($total_num >= 4) {
                $is_end = 1;
            } else {
                $is_end = 0;
            }
        }
        return $is_end;
    }

    /**
     * 获取用户L1基础过程能力值是否达标。
     * @param $user_id
     * @param $tag_code
     */
    public function getUserGgStudyAbilityIsArrived($user_id, $topicId, $tag_code)
    {
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['tag_code'] = $tag_code;
        $user_ability_info = Db::name('user_ability')->order("id desc ")->find();
        $ability = (int)$user_ability_info['ability'];
        if ($ability > 0.8) {
            $is_arrived = 1;
        } else {
            $is_arrived = 0;
        }
        return $is_arrived;
    }


    /**
     * 根据特定条件获取用户已经学习的知识点.
     */
    public function getUserHasLearnedTagCode($user_id = null, $topicId, $module_type, $submodule_type, $batch_num)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        if ($batch_num) {
            $where['batch_num'] = $batch_num;
        }
        $where['submodule_type'] = $submodule_type;
        $return_data = Db::name('user_exam')->where($where)->field("tag_code")->select();

        return $return_data;
    }


    /**
     * 用户在单独的模块下,某知识点做的题数.
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $tag_code
     * @return mixed
     */
    public function getUserHasAnsweredNumForTagCode($user_id, $topicId, $module_type, $tag_code, $submodule_type)
    {
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
        }
        $summer_question_service = new SummerQuestionService();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $where['tag_code'] = $tag_code;
        $answeredInfo = Db::name('user_exam')->where($where)->select();
//        echo Db::name('user_exam_detail')->getLastSql();
        $num = count($answeredInfo);
        return $num;
    }


    /**
     * 根据特定条件获取用户已经学习的知识点.
     */
    public function getUserMasteryTagCode($user_id = null, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        //-----------------------------------判断已掌握知识点的第一种方式。-----------------------------------------------------------------------------------

        Log::record("------" . __FUNCTION__ . "---判断已掌握知识点的第一种方式---");

        //先找出学过两遍的知识点，学过两边的表示已掌握的。
        $summer_question_service = new SummerQuestionService();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $user_exam = Db::name('user_exam')->where($where)->field("batch_num,tag_code")->select();
        $final_inters_code1 = array();
        $user_answered_code_arr = array();
        $user_answered_code = array();
        //此处没有直接取batch_num==1和batch_num==2的数据。直接数组的前两次，做了个容错处理。
        foreach ($user_exam as $k => $v) {
            $batch_num = $v['batch_num'];
            $user_answered_code[$batch_num][] = $v['tag_code'];
        }
        $user_answered_code_arr = array_merge($user_answered_code, array());
        $user_answered_code_num = count($user_answered_code_arr);


        if ($user_answered_code_num >= 2) {
            $final_inters_code1 = array_intersect($user_answered_code_arr[0], $user_answered_code_arr[1]);
        }
        Log::record("------" . __FUNCTION__ . "---第一种方式结束---");

        //-----------------------------------------第一种方式结束----------------------------------------
        Log::record("------" . __FUNCTION__ . "---第二种方式开始---");
        //-----------------------------------------第二种方式开始----------------------------------------
        //再找出用户在基础和巩固环节都掌握的。
        $batch_num = $summer_question_service->getBatchNum($topicId, $module_type);
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['batch_num'] = $batch_num;
        $user_mastery_info = Db::name('user_mastery_tag_code')->where($where)->field("batch_num,tag_code,submodule_type")->select();

        $gg_arr = array();
        $base_arr = array();
        foreach ($user_mastery_info as $k => $v) {
            if ($v['submodule_type'] == 1) {
                $base_arr[] = $v['tag_code'];
            }
            if ($v['submodule_type'] == 2) {
                $gg_arr[] = $v['tag_code'];
            }
        }
        //两种情况，如果用户基础和巩固都做过了，那么要对两个孙子模块掌握的知识点求并运算。如果只做过一个，那么只取其中一个已经掌握的知识点就可以了。
        if (empty($base_arr) || empty($gg_arr)) {
            if (empty($base_arr)) {
                $final_inters_code2 = $gg_arr;
            } else {
                $final_inters_code2 = $base_arr;
            }
        } else {

            $final_inters_code2 = array_intersect($base_arr, $gg_arr);
        }

        Log::record("------" . __FUNCTION__ . "---第二种方式结束---");

        //-----------------------------------------第二种方式结束----------------------------------------

        $final_inters_code = array_merge($final_inters_code1, $final_inters_code2);
        $final_inters_code = array_unique($final_inters_code);
        $final_inters_code = array_merge($final_inters_code, array());

        return $final_inters_code;
    }


    public function getUserBtKmapCode($user_id, $topicId, $module_type, $batch_num)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['batch_num'] = $batch_num;
        $user_algo_bt_status_info = Db::name('user_algo_bt_status')->where($where)->find();

        if (empty($user_algo_bt_status_info)) {
            $kmap_code = "";
        } else {
            $kmap_code = $user_algo_bt_status_info['kmap_code'];
        }
        return $kmap_code;
    }


    /**
     *
     */
    public function updateUserMasteryTagCode($topicId, $batch_num, $module_type, $submodule_type, $tag_code, $ability, $grandson_module_type)
    {
        $l1_module_type = config('l1_module_type');
        $arrived_ability_standard = false;
        $arrived_max_questionNum = false;
        Log::record("------" . __FUNCTION__ . "---1111---");

        if ($l1_module_type == $module_type && $submodule_type == 2) {
            Log::record("------" . __FUNCTION__ . "---2222---");

            $user_id = $this->getUserId();
            //1  基础学习，2 巩固学习
            if ($grandson_module_type == 1) {
                $standard_ability = config('l1_base_ability_standard');
            } elseif ($grandson_module_type == 2) {
                $standard_ability = config('l1_gg_ability_standard');
            }

            Log::record("------" . __FUNCTION__ . "------ability---" . $ability . "--------standard_ability" . $standard_ability);

            //先判断能力值是否达标。
            if ($ability >= $standard_ability) {
                $arrived_ability_standard = true;
            }

            //如果次知识点学习过两边，也标记已掌握。
            $where_user_exam['user_id'] = $user_id;
            $where_user_exam['topicId'] = $topicId;
            $where_user_exam['tag_code'] = $tag_code;
            $where_user_exam['module_type'] = $module_type;
            $where_user_exam['submodule_type'] = $submodule_type;
            $user_exam_info = Db::name('user_exam')->where($where_user_exam)->select();
            Log::record("------" . __FUNCTION__ . "---3333---");

            // 如果已经做过两边的知识点则判断未已掌握。
            if (!empty($user_exam_info)) {
                $num = count($user_exam_info);
                if ($num >= 2) {
                    Log::record("------" . __FUNCTION__ . "---4444---");

                    $arrived_max_questionNum = true;
                }

            }

            if ($arrived_ability_standard || $arrived_max_questionNum) {
                Log::record("------" . __FUNCTION__ . "---55555-----arrived_ability_standard" . $arrived_ability_standard . "------arrived_max_questionNum------" . $arrived_max_questionNum);

                $where['user_id'] = $user_id;
                $where['batch_num'] = $batch_num;
                $where['topicId'] = $topicId;
                $where['module_type'] = $module_type;
                $where['submodule_type'] = $submodule_type;
                $where['tag_code'] = $tag_code;
                $user_mastery_info = Db::name('user_mastery_tag_code')->where($where)->find();
                if (empty($user_mastery_info)) {
                    Log::record("------" . __FUNCTION__ . "---666---");

                    $data['user_id'] = $user_id;
                    $data['batch_num'] = $batch_num;
                    $data['topicId'] = $topicId;
                    $data['module_type'] = $module_type;
                    $data['submodule_type'] = $submodule_type;
                    $data['grandson_module_type'] = $grandson_module_type;
                    $data['tag_code'] = $tag_code;
                    $data['ability'] = "$ability";
                    //mastery_type ，1表示能力值达标，2 ：表示能力值达标。
                    if ($arrived_max_questionNum) {
                        $data['mastery_type'] = 2;
                    } elseif ($arrived_ability_standard) {
                        $data['mastery_type'] = 1;
                    }
                    Log::record("------" . __FUNCTION__ . "---888---");


                    $data['ctime'] = time();
                    Db::name('user_mastery_tag_code')->insert($data);
                }
            }


        }
    }

    /**
     * 根据模块获取用户在某模块下已做题.
     * @param null $user_id
     * @param $topicId
     * @param $module_type
     * @return array
     */
    public function getL2UserHasAnsweredQuestionsByModule($user_id = null, $topicId, $module_type = null, $submodule_type = 0, $batch_num = 0)
    {

        $question_v2_service = new BaseQuestionV2Service();
        $return_arr = array();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if ($topicId) {
            if (is_array($topicId)) {
                $where['topicId'] = array("in", $topicId);
            } else {
                $where['topicId'] = $topicId;
            }
        }

        if ($module_type) {
            $where['module_type'] = $module_type;
        }
        if ($submodule_type) {
            $where['submodule_type'] = $submodule_type;
        }
        if ($batch_num) {
            $where['batch_num'] = $batch_num;
        }

        $answeredInfo = Db::name('user_exam_detail')->where($where)->order("id desc")->select();
        $question_service = new QuestionService();

        //$kmap_code = config("kmap_code");
        $knowledgeService = new KnowledgeService();
        foreach ($answeredInfo as $key => $val) {
//            $return_arr[$key]['question_id'] = $val['question_id'];
            $return_info = array();
            $return_info = $question_v2_service->getQuestionById($val['question_id']);
            $return_info['tag_code'] = $val['tag_code'];
            $return_info['module_type'] = $val['module_type'];
            $return_info['right_answer'] = $val['right_answer'];
            $return_info['user_answer'] = $val['user_answer'];
            $return_info['stime'] = $val['stime'];
            $return_info['ctime'] = $val['ctime'];
            $return_info['spent_time'] = $val['spent_time'];
            $return_info['is_view_analyze'] = $val["is_view_analyze"];
            $return_info['is_view_answer'] = $val["is_view_answer"];
            $return_info['exam_detail_id'] = $val["id"];
            $return_info['topicId'] = $val["topicId"];
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
     * 获取用户L1基础过程知识点能力值。
     * @param $user_id
     * @param $tag_code
     */
    public function getUserTagCodeAbility($user_id, $topicId, $tag_code = 0, $module_type = 0, $submodule_type = 0)
    {
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        if ($tag_code) {
            $where['tag_code'] = $tag_code;
        }
        if ($module_type) {
            $where['module_type'] = $module_type;
        }
        if ($module_type) {
            $where['submodule_type'] = $submodule_type;
        }
//        var_dump($where);
        $user_ability = [];
        $user_ability_info=[];
        $ability_info = Db::name('user_ability')->where($where)->field("tag_code,ability")->select();
//        var_dump($user_ability_info);
        if ($ability_info) {
            foreach ($ability_info as $k => $val) {
                $user_ability[$val["tag_code"]] = $val["ability"];
            }
            $i = 0;
            foreach ($user_ability as $key => $value) {
                $user_ability_info[$i]['ability'] = ceil($value * 100);
                $user_ability_info[$i]['tag_code'] = $key;
                $i++;
            }
        }
        return $user_ability_info;
    }


    public function getUserAnsweredQuestionsByModule($user_id = null, $topicId, $batch_num, $module_type, $submodule_type, $grandson_module_type = 0)
    {
        $return_arr = array();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        if ($batch_num) {
            $where['batch_num'] = $batch_num;
        }

        if ($module_type) {
            $where['module_type'] = $module_type;
        }
        $where['submodule_type'] = $submodule_type;
        if ($grandson_module_type) {
            $where['grandson_module_type'] = $grandson_module_type;
        }

        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();
        return $answeredInfo;
    }

    /**
     *
     * @param type $user_id
     * @param type $topicId
     * @param type $module_type
     * @param type $submodule_type
     * @param type $is_right
     * @param type $page
     * @param type $pageSize
     * @param type $pageParams
     * @return type
     */

    public function getUserHasAnsweredQuestionsWithDetail($user_id, $topicId, $module_type, $submodule_type, $is_right = null, $page = null, $pageSize = null, $pageParams = [], $order='asc')
    {

        $return_arr = array();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if ($topicId) {
            if (is_array($topicId)) {
                $where['topicId'] = array("in", $topicId);
            } else {
                $where['topicId'] = $topicId;
            }
        }
        if ($module_type !== null) {
            $where['module_type'] = $module_type;
        }

        if ($submodule_type) {
            $where['submodule_type'] = $submodule_type;
        }

        if ($is_right !== null) {
            $where["is_right"] = $is_right;
        }
        $data = [];
//        $pageHtml = "";
        $result = Db::name('user_exam_detail')->where($where)->order(['id'=>$order])->paginate($pageSize, false, $pageParams);
        if ($result) {
            $page = $result->render();
//            if (empty($page)) {
//                $pageHtml = "";
//            }
            $arrayResult = $result->toArray();
            $data = $arrayResult["data"];
        }

        $question_service = new BaseQuestionV2Service();

        //$kmap_code = config("kmap_code");
        $knowledgeService = new KnowledgeService();
        foreach ($data as $key => $val) {
//            $return_arr[$key]['question_id'] = $val['question_id'];
            $return_info = array();
            $return_info = $question_service->getQuestionById($val['question_id']);
            $return_info['tag_code'] = $val['tag_code'];
            $return_info['module_type'] = $val['module_type'];
            $return_info['right_answer'] = $val['right_answer'];
            $return_info['user_answer'] = $val['user_answer'];
            $return_info['stime'] = $val['stime'];
            $return_info['ctime'] = $val['ctime'];
            $return_info['spent_time'] = $val['spent_time'];
            $return_info['is_view_analyze'] = $val["is_view_analyze"];
            $return_info['is_view_answer'] = $val["is_view_answer"];
            $return_info['exam_detail_id'] = $val["id"];
            $return_info['topicId'] = $val["topicId"];
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

        $returnData = [
            "data" => $return_arr,
            "page" => $page,
        ];
        return $returnData;
    }


    /**
     * 获取用户的  L2  下一个边学边练的知识点.
     */
    public function getUserPreviewNextTagCode($kmap_code, $topicId, $used_type, $module_type)
    {
        //如果已做此知识点的题,已经学完应学的数量,即开始推下一个知识点.没有学完的话,即还是推此知识点.
        //获取用户做的最后一个知识点.
        Log::record("------" . __FUNCTION__ . "---topicId---" . $topicId);
        $tag_code = $this->getUserLastTagCodeByModule($topicId, $module_type);
        Log::record("------" . __FUNCTION__ . "---tag_code---" . $tag_code);
        $user_service = new UserService();
        $hasLeardTagCode = $user_service->getUserHasLearnedTagCode("", $topicId, $module_type);
        $learned_elements = array();
        foreach ($hasLeardTagCode as $key => $val) {
            $learned_elements[] = $val['tag_code'];
        }
        $algologic = new AlgoLogic();

        //如果没有知识点,说明用户第一次做.故直接从算法获取知识点.
        if ($tag_code == "") {
            $return_tag_code = $algologic->get_preview_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements, $module_type);
            $tag_code = $return_tag_code['next_element'];
        } else {
            $return_data = $user_service->getUserHasAnsweredQuestions("", $tag_code, $topicId, $module_type);
            $num = count($return_data);
            Log::record("------" . __FUNCTION__ . "---num---" . $num);
            $need_num = config("to_learn_num");
            Log::record("------" . __FUNCTION__ . "---need_num---" . $need_num);
            if ($num >= $need_num) {
                $return_tag_code = $algologic->get_preview_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements, $module_type);
                $tag_code = $return_tag_code['next_element'];
            }
        }

        return $tag_code;
    }


    /**
     * 获取用户边学边练的最后一个知识点.
     */
    public function getUserLastTagCodeByModule($topicId, $module_type)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where["topicId"] = $topicId;
        $where['module_type'] = $module_type;
        Log::record("------" . __FUNCTION__ . "---topicId---" . $topicId);
//        $return_data = Db::name('user_bxbl_question')->where($where)->field('tag_code')->order("id desc")->find();
        $return_data = Db::name('user_exam_detail')->where($where)->field('tag_code')->order("id desc")->find();
        if (empty($return_data)) {
            $tag_code = "";
        } else {
            $tag_code = $return_data['tag_code'];
        }
        Log::record("------" . __FUNCTION__ . "---tag_code---" . $tag_code);
        return $tag_code;
    }


    /**
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getUserLearnedL1XianceInfo($user_id, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = 1;
        $return_data = Db::name('user_algo_status')->where($where)->field('id')->select();
        return $return_data;
    }


    /**
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getUserHasLearnedCodeScale($user_id, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $learned_code_list = Db::name('user_exam')->where($where)->field('distinct  tag_code')->select();
        $learned_code_num = count($learned_code_list);
        //获取大知识图谱的知识点。
        $topic_v2_service = new TopicV2Service();
        $big_map_code_list = $topic_v2_service->getBigKmapTagCodeList($topicId);
        $big_map_code_num = count($big_map_code_list);
        $scale = $learned_code_num / $big_map_code_num;
        if($scale>1)
        {
            $scale =1;
        }
        return $scale;
    }


    /**
     * 获取用户答题的最后一条记录的信息。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     */
    public function getUserLastExamInfo($user_id = null, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $question_v2_service = new BaseQuestionV2Service();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $user_last_exam_detail = Db::name('user_exam_detail')->where($where)->order('id desc')->find();
        if (!empty($user_last_exam_detail)) {
            $question_id = $user_last_exam_detail['question_id'];
            $user_last_exam_detail['question_list'] = $question_v2_service->getQuestionById($question_id);
        }
        return $user_last_exam_detail;
    }


    /**
     * 获取用户已经做过的试题.
     */
    public function getUserHasAnsweredQuestions($user_id, $topicId, $module_type, $submodule_type, $tag_code = "")
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $summer_question_service = new SummerQuestionService();
        $batch_num = $summer_question_service->getBatchNum($topicId, $module_type);
        $where['batch_num'] = $batch_num;
        $userAnsweredQuestions = Db::name('user_exam_detail')->where($where)->select();
        $userAnsweredQuestionIds = array();
        foreach ($userAnsweredQuestions as $userAnsweredQuestion) {
            $userAnsweredQuestionIds[] = $userAnsweredQuestion['question_id'];
        }
        return $userAnsweredQuestionIds;
    }


    /**
     * 更新用户学习模块已经掌握的知识点
     * @param $user_id
     * @param $topicId
     * @param $nodes
     */
    public function updateUserHasMasteryTagCode($user_id, $topicId, $nodes)
    {

        if (!$user_id) {
            $user_id = $this->getUserId();
        }



        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $user_has_mastery_tag_code_info = Db::name('user_has_mastery_tag_code')->where($where)->select();
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['masteryed_code'] = json_encode($nodes);
        $data['ctime'] = time();
        if (empty($user_has_mastery_tag_code_info)) {
            $userAnsweredQuestions = Db::name('user_has_mastery_tag_code')->insert($data);
        } else {
            $userAnsweredQuestions = Db::name('user_has_mastery_tag_code')->where($where)->update($data);
        }
    }


    /**
     * 获取用户学习模块已经掌握的知识点
     * @param $user_id
     * @param $topicId
     * @return mixed
     */
    public function getUserHasMasteryTagCode($user_id, $topicId)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $user_has_mastery_tag_code_info = Db::name('user_has_mastery_tag_code')->where($where)->find();
        if(empty($user_has_mastery_tag_code_info))
        {
            $nodes = array();
        }else{
            $nodes = json_decode($user_has_mastery_tag_code_info['masteryed_code']);
        }
        return  $nodes;
    }





    /**
     * 获取用户先行测试最后一道题的记录。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     */
    public function getUserL1XianceLastExamDetail($user_id,$topicId,$module_type,$submodule_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $user_last_exam_detail = Db::name('user_exam_detail')->where($where)->field('ctime')->order('id desc')->find();
        return $user_last_exam_detail;

    }



    public function getUserL1StudyModuleLastExamDetail($user_id,$topicId,$module_type,$submodule_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $user_last_exam_detail = Db::name('user_exam_detail')->where($where)->where(" grandson_module_type!=0 ")->field('ctime')->order('id desc')->find();
        return $user_last_exam_detail;



    }
    /**
     * 获取能力值大于0.8的知识点
     * @param type $user_id
     * @param type $topicId
     * @param type $module_type
     * @return type
     */
    public function getKnowledgesByAbility($user_id, $topicId, $module_type) {
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $userCatchKnoeledges = Db::name('user_ability')->where($where)->where(" ability >= 0.8 ")->field('tag_code')->select();
        $tag_codes =[];
        foreach ($userCatchKnoeledges as $key => $value) {
            $tag_codes []= $value["tag_code"];
        }
        return $tag_codes;
    }
    /**
     * 获取用户掌握的知识点
     * @param type $user_id
     * @param type $topicId
     * @return type
     */
    public function getKnowledgesByMasteryTagCode($user_id, $topicId) {
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $userCatchKnoeledges = array();
        $userCatchKnoeledgesInfo = Db::name('user_has_mastery_tag_code')->where($where)->field('masteryed_code')->find();
        if(!empty($userCatchKnoeledgesInfo))
        {
            $userCatchKnoeledgesStr = $userCatchKnoeledgesInfo["masteryed_code"];
            $userCatchKnoeledges = json_decode($userCatchKnoeledgesStr);
        }
        return $userCatchKnoeledges;
    }

    /**
     * 用户最终的薄弱知识点
     * @param type $user_id
     * @param type $topicId
     * @param type $module_type
     * @param type $sub_module_type
     * @param type $batch_num
     * @param type $knowledge_list
     * @return type
     */
    public function getUserLastWeakElements($user_id, $topicId, $module_type,$sub_module_type,$batch_num,$knowledge_list) {
        $summerAlgoLogic = new SummerAlgoLogic();
        $weakElements = [];
//        var_dump($user_id, $topicId, $module_type,$sub_module_type,$batch_num);die;
        $orignWeakElements = $summerAlgoLogic->getOrignWeakElements($user_id, $topicId, $module_type, $sub_module_type, $batch_num);
//        var_dump($orignWeakElements);
        if (!$orignWeakElements) {
            $right_list = $knowledge_list;
        } else {
            $right_list = array_diff($knowledge_list, $orignWeakElements);
        }
        
        $userCatchKnowledgesAbility = $this->getKnowledgesByAbility($user_id, $topicId, $module_type);
//        var_dump($userCatchKnowledgesAbility);
        $userCatchKnowledgesMasteryTagCode = $this->getKnowledgesByMasteryTagCode($user_id, $topicId);
//        var_dump($userCatchKnowledgesMasteryTagCode);
        $catchKnowledges = array_merge($userCatchKnowledgesAbility, $userCatchKnowledgesMasteryTagCode,$right_list);
//        var_dump($catchKnowledges);
        $weakElements = array_diff($knowledge_list, $catchKnowledges);
//        var_dump($weakElements);die;
        return $weakElements;
    }

 /**
     * 获取用户详细的步骤。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $submodule_type
     * @param $batch_num
     * @return int|mixed
     */
    public function getUserTinyStepLog($user_id, $topicId, $module_type, $submodule_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = 1;
        $return_data = Db::name('user_exam_tiny_step_log')->where($where)->find();
        if (empty($return_data)) {
            $is_end = 0;
        } else {
            $is_end = $return_data['is_end'];
        }
        return $is_end;
    }



    /**
     * 根据模块获取用户在某模块下已做题数.
     * @param null $user_id
     * @param $topicId
     * @param $module_type
     * @return array
     */
    public function getUserHasAnsweredQuestionsByModuleType($user_id = null, $topicId, $module_type = null)
    {

        $return_arr = array();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if ($topicId) {
            if (is_array($topicId)) {
                $where['topicId'] = array("in", $topicId);
            } else {
                $where['topicId'] = $topicId;
            }
        }

        if ($module_type) {
            $where['module_type'] = $module_type;
        }
        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();
        return $answeredInfo;
    }


    /**
     * 获取用户某模块下做的全对的试题。
     * @param null $user_id
     * @param $topicId
     * @param null $module_type
     * @return false|mixed|\PDOStatement|string|\think\Collection
     */
    public function getUserAnsweredAllRightQuestionsByModuleType($user_id = null, $topicId, $module_type = null)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if ($topicId) {
            if (is_array($topicId)) {
                $where['topicId'] = array("in", $topicId);
            } else {
                $where['topicId'] = $topicId;
            }
        }
        $where['is_right'] = 1;

        if ($module_type) {
            $where['module_type'] = $module_type;
        }
        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();
        return $answeredInfo;

    }


    /**
     * 获取用户在L2先行测试部分的知识图谱编码。
     * @param null $user_id
     * @param $topicId
     * @param $module_type
     */
    public function getUserL2XianceLearnedKmapCodeByModuleType($user_id=null,$topicId,$module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $answeredInfo = Db::name('user_learned_kmap_code')->where($where)->find();
        return $answeredInfo;
    }


    /**
     * 记录用户某专题下在特定模块做过的知识点图谱。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $kmap_code
     */
    public function insertUserLearnedKmapCode($user_id,$topicId,$module_type,$kmap_code)
    {

        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $data['user_id'] =$user_id;
        $data['topicId'] = $topicId;
        $data['module_type'] = $module_type;
        $data['kmap_code'] = $kmap_code;
        Db::name('user_learned_kmap_code')->insert($data);
    }

    /**
     * 根据模块获取用户在某模块下已做题数 批量接口.
     * @param null $user_id
     * @param $topicId
     * @param $module_type
     * @return array
     */
    public function getUserHasAnsweredQuestionListByModule($user_id = null, $topicId, $module_type = null)
    {

        $return_arr = array();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if ($topicId) {
            if (is_array($topicId)) {
                $where['topicId'] = array("in", $topicId);
            } else {
                $where['topicId'] = $topicId;
            }
        }

        if ($module_type) {
            $where['module_type'] = $module_type;
        }

        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();
        $question_ids = [];
        $tag_codes = [];
        foreach ($answeredInfo as $key => $value) {
            $question_ids[]=$value["question_id"];
            $tag_codes[]=$value["tag_code"];
        }
        $question_service = new QuestionService();
        $baseQuestionV2Service = new BaseQuestionV2Service();
        $knowledge_v2_service = new KnowledgeV2Service();
        
        $tag_code_info = $knowledge_v2_service->getKnowledgeListByCodes($tag_codes);
        $question_id_info = $baseQuestionV2Service->getQuestionListByIdes($question_ids);
//        var_dump($question_id_info);die;
        foreach ($answeredInfo as $key => $val) {
            $return_info = array();
            $return_info = $question_id_info[$val['question_id']];
            $return_info['tag_code'] = $val['tag_code'];
            $return_info['module_type'] = $val['module_type'];
            $return_info['right_answer'] = $val['right_answer'];
            $return_info['user_answer'] = $val['user_answer'];
            $return_info['stime'] = $val['stime'];
            $return_info['ctime'] = $val['ctime'];
            $return_info['spent_time'] = $val['spent_time'];
            $return_info['is_view_analyze'] = $val["is_view_analyze"];
            $return_info['is_view_answer'] = $val["is_view_answer"];
            $return_info['exam_detail_id'] = $val["id"];
            $return_info['topicId'] = $val["topicId"];
            $return_info['right_answer_base64'] = $val['right_answer_base64'];
            $return_info['tag_name'] = $tag_code_info[$val["tag_code"]]["tag_name"];
            $userAnswerBase64Arr = [];
            if ($val['user_answer_base64']) {
                $userAnswerBase64Arr = explode("@@@", $val['user_answer_base64']);
            }
            $return_info['user_answer_base64'] = $userAnswerBase64Arr;
            $return_info['is_right'] = $val['is_right'];
            $return_arr[] = $return_info;
        }

        return $return_arr;
    }

}
