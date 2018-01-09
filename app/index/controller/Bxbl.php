<?php

namespace app\index\controller;

use service\entity\User;
use service\services\ApiGateService;
use service\services\KnowledgeService;
use service\services\KnowledgeV2Service;
use service\services\TopicService;
use service\services\UserService;
use think\Request;
use service\services\QuestionService;
use service\services\AlgoService;
use service\lib\RabbitClientService;
use service\algo\AlgoLogic;
use service\services\TtqQuestionService;
use service\services\DetectQuestionService;
use service\log\LogService;
use think\Log;
use service\services\PathManageService;

class Bxbl extends Base {

    public function index() {
        echo "fsdfsadfasdfs";
    }

    /**
     * 高效学习答题页面
     * @return mixed
     */
    public function bIndex() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $module_type = config('bxbl_module_type');
        //判断用户是否有薄弱知识点，没有薄弱知识点的话，是不需要进入边学边练的。
        $algoLogic = new AlgoLogic();
        $weakElements = $algoLogic->getWeakElements("", $topicId);
        $weak_num = count($weakElements);
        $domain =  $_SERVER['SERVER_NAME'];
        if($weak_num==0)
        {
            //如果没有薄弱知识点直接进去下一个模块。
            $user_service = new UserService();
            $has_learned_all_tag_code =1;  //此次因为用户不需要做，就默认全对了
            $is_end = 1;
            $user_service->insertUserStep($topicId, $module_type, $has_learned_all_tag_code, $is_end);
            $path_manager = new PathManageService();
            $return_data=  $path_manager->getUserNextModule('',$topicId,"");
            //如果已经做完了，提示用户跳转回去。
            if($return_data['is_end']==1)
            {
                //恭喜您全答对，可以完成本课程了。
                $url = "http://".$domain.url('index/Index/prereport',['topicId' => $topicId]);
                $this->success('恭喜您，由于您成绩太优异，不需要继续做练习了。', $url);
            }else{
                //用户直接跳转到下一个模块。
                $this->redirect($return_data['url']);
                exit;
            }
        }




        $topic_service = new TopicService();
        $topicInfo = $topic_service->getTopicByTopicId($topicId);

        $topic_name = $topicInfo['topic_name'];
        $this->assign("topic_name", $topic_name);
        $this->assign("topicId", $topicId);
        return $this->fetch("bIndex");
    }

    /**
     * 学习检测答题页面
     * @return mixed
     */
    public function bTest() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $topic_service = new TopicService();
        $topicInfo = $topic_service->getTopicByTopicId($topicId);
        $topic_name = $topicInfo['topic_name'];
        $this->assign("topic_name", $topic_name);
        $this->assign("topicId", $topicId);
        return $this->fetch("bTest");
    }

    /**
     * 学情报告
     * @return mixed
     */
    public function studyReport() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $module_type = config('bxbl_module_type');
        $user_service = new UserService();
        $has_answered_questions = $user_service->getUserHasAnsweredQuestionsByModule("", $topicId, $module_type);
        $user_ability = $user_service->getUserAbility("", $topicId, $module_type);
        $algoLogic = new AlgoLogic();
        $knowledge_service = new KnowledgeService();
        $api_gate_service = new ApiGateService();
        $knowledgeList = $api_gate_service->getKnowledgeListByTopicId($topicId);
        $weakElements = $algoLogic->getWeakElements("", $topicId);
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
        $knowledge_v2_service = new KnowledgeV2Service();
        foreach ($user_ability as $key => $ability_num) {
            foreach ($knowledgeList as $k => $knonwledge) {
                if ($knonwledge["tag_code"] === $key) {
                    $tag_namearr=$knowledge_v2_service->getKnowledgeByCode($knonwledge["tag_code"]);
                    $tag_video_list = $tag_namearr['video'];
                    $tag_info['tag_name'] = $tag_namearr['tag_name'];
                    $tag_info['tag_code'] = $knonwledge['tag_code'];
                    $tag_info['ability'] = $ability_num;
                    $tag_ability_report[] = $tag_info;
                    break;
                }
            }
        }

        $is_show_report=$request->param("is_show_report");
        $is_show_nextstep=$request->param("is_show_nextstep");

        if(!isset($is_show_report))
        {
            $is_show_report =  1;
        }
        if(!isset($is_show_nextstep))
        {
            $is_show_nextstep=1;
        }
        
        $topic_service = new TopicService();
        $topicInfo = $topic_service->getTopicByTopicId($topicId);
        $flow_id = $topicInfo["flow_id"];
        $url="";
        
        if($flow_id==1){
            $url= url("Index/zhlx/btips", ["topicId"=>$topicId]);
        }elseif ($flow_id==2) {
            $url= url("Index/mncs/index", ["topicId"=>$topicId]);
        }

        $topic_name = $topicInfo['topic_name'];
        $this->assign("topic_name", $topic_name);
        $this->assign("flow_id", $flow_id);
        $this->assign("url", $url);
        $accuracy = $user_service->getUserExamDetail("", $topicId, $module_type);
        $this->assign("accuracy", $accuracy); //正确率
        $this->assign("has_answered_questions", $has_answered_questions);
        $this->assign("frist_has_learned_num", $frist_has_learned_num);
        $this->assign("has_learned_weakElements_num", $has_learned_weakElements_num);
        $this->assign("not_learned_weakElements_num", $not_learned_weakElements_num);
        $this->assign("scale", $scale);
        $this->assign("tag_ability_report", json_encode($tag_ability_report));
        $this->assign("topicId", $topicId);
        $this->assign('module_type',$module_type);
        $this->assign('is_show_nextstep',$is_show_nextstep);
        $this->assign('is_show_report',$is_show_report);

        //判断现行测试边学边练和竞赛扩展是否做完
        $user_id= $this->getUserId();
        $userService=new UserService();
         $xianceStep=$userService->getUserStep($topicId,$user_id,config('xiance_module_type'));//边学边练
         $xiance_is_end=0;
        $this->assign('xiance_is_end',0);
        if(isset($xianceStep['is_end'])&&$xianceStep['is_end']==1)$xiance_is_end=1;
        $this->assign('xiance_is_end',$xiance_is_end);
        
        $bxblStep=$userService->getUserStep($topicId,$user_id,config('bxbl_module_type'));//边学边练
        $bxbl_is_end=0;
        if(isset($bxblStep['is_end'])&&$bxblStep['is_end']==1)$bxbl_is_end=1;;
        $this->assign('bxbl_is_end',$bxbl_is_end);
        $zhlxStep=$userService->getUserStep($topicId,$user_id,config('zonghe_module_type'));//综合练习
        $zhlx_is_end=0;
        if(isset($zhlxStep['is_end'])&&$zhlxStep['is_end']==1)$zhlx_is_end=1;;
        $this->assign('zhlx_is_end',$zhlx_is_end);
        $mncsStep=$userService->getUserStep($topicId,$user_id,config('mncs_module_type'));//综合练习
        $mncs_is_end=0;
        if(isset($mncsStep['is_end'])&&$mncsStep['is_end']==1)$mncs_is_end=1;;
        $this->assign('mncs_is_end',$mncs_is_end);
        
        return $this->fetch("studyReport");
    }

    public function getUserTagAbilityReport() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $module_type = config('bxbl_module_type');
        $user_service = new UserService();

        $knowledge_service = new KnowledgeService();

        $user_ability = $user_service->getUserAbility("", $topicId, $module_type);

        $knowledgeList = $knowledge_service->getKnowledgeListByTopicId($topicId);

        $knowledge_v2_service = new KnowledgeV2Service();
        $tag_ability_report = array();
        foreach ($user_ability as $key => $ability_num) {
            foreach ($knowledgeList as $k => $knonwledge) {
                if ($knonwledge["tag_code"] == $key) {
                    $tag_namearr=$knowledge_v2_service->getKnowledgeByCode($knonwledge["tag_code"]);
                    $tag_video_list = $tag_namearr['video'];
                    $tag_info['tag_name'] = $tag_namearr['tag_name'];
                    $tag_info['tag_code'] = $knonwledge['tag_code'];
                    $tag_info['ability'] = $ability_num;
                    $tag_ability_report[] = $tag_info;
                    break;
                }
            }
        }

        var_dump($tag_ability_report);
    }

    /**
     * 堂堂清
     * @return mixed
     */
    public function ttqQuestion() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $this->assign("topicId", $topicId);

        $questionService = new QuestionService();
        $knowledgeQuestion = $questionService->getTtqKnowledgeQuestionArr($topicId);
        $this->assign('knowledgeQuestion', json_encode($knowledgeQuestion));
        return $this->fetch("ttqQuestion");
    }

    /**
     * 堂堂清报告页
     * @return mixed
     */
    public function ttqReport() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $user_id = $this->getUserId();
        $ttqQuestion_service = new TtqQuestionService();
        $batch_num = $ttqQuestion_service->getNowBatchNumForTtqReport($topicId);
        $tag_code_question = $ttqQuestion_service->getUserTtqAnsweredQuestions($topicId, $batch_num);
        $is_do_ttq = $ttqQuestion_service->isDoTtq($topicId, $batch_num); //是否做了堂堂清
        $tag_code_report = $ttqQuestion_service->getUserTtqAnswerReport($topicId, $batch_num);
        $user_service = new UserService();
        $module_type = config('gaoxiao_module_type');
        $user_step = $user_service->getUserStep($topicId, $user_id, $module_type);
        $is_all_end = $user_step['is_end'];
        $question_service = new QuestionService();
//        $all_knowledgeList = $question_service->getKnowledgeList();
//        $kmap_code = config("kmap_code");
//        $knowledgeList = $all_knowledgeList[$kmap_code];
        $knowledge_service = new KnowledgeService();
        $knowledgeList = $knowledge_service->getKnowledgeListByTopicId($topicId);
        $tagKey = array_keys($tag_code_question);
        $tagList = array();
        foreach ($tagKey as $tagItem) {
            foreach ($knowledgeList as $key => $knonwledge) {
                if ($knonwledge["tag_code"] == $tagItem) {
                    $tagList[] = $knonwledge['tag_name'];
                    break;
                }
            }
        }
        if ($is_all_end) {
            $is_all_right = $user_service->getUserXianceIsAllRight($topicId);
            if (!$is_all_right) {
                $scale = $user_service->getUserLearnedKnowledgeScaleForBxbl($topicId);
                $ability_scale_standard = config("ability_scale_standard");
                if ($scale > $ability_scale_standard) {
                    $learnStatus = 1;
                } else {
                    $learnStatus = 0;
                }
            } else {
                $learnStatus = 1;
            }
        } else {
            $learnStatus = 0;
        }
//        $tag_code_report[0]['scale'] = 1;
        $this->assign("tagList", $tagList);
        $this->assign("is_do_ttq", $is_do_ttq);
        $this->assign("topicId", $topicId);
        $this->assign("isAllEnd", $is_all_end);
        $this->assign("learnStatus", $learnStatus);
        $this->assign("tag_code_question", $tag_code_question);
        $this->assign("tag_code_report", json_encode($tag_code_report));
        $this->assign("tag_code_report_arr", $tag_code_report);
        return $this->fetch("ttqReport");
    }

    /**
     * @api {get} /index/Bxbl/getExamQuestions   获取试题
     * @apiVersion 0.0.1
     * @apiName  getExamQuestions
     * @apiGroup  Bxbl
     *
     * @apiParam {Number} topicId  用户topicId.
     * @apiSuccess {Number} is_enter  是否进入堂堂清,0 ,不进入,1 进入..
     * @apiSuccess {Array} question_list  试题信息.
     * @apiSuccess {String} error  错误信息.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "is_enter":0,
     *      "question_list":{
     *          "id":10,
     *          "content":"\u6d4b\u8bd5\u8bd5\u9898",
     *          "q_type":1,
     *          "options":[
     *              {
     *                  "key":"A",
     *                  "answer":" this is A"
     *              },
     *              {
     *                  "key":"B",
     *                  "answer":"this is B"
     *              },
     *              {
     *                  "key":"C",
     *                  "answer":"this is C"
     *              }
     *              ],
     *          "answer":[
     *              [
     *                  [
     *                      "is not"
     *                  ],
     *                  [
     *                          "Is NOt"
     *                  ],
     *                  [
     *                      "IS NOT"
     *                  ]
     *                  ],
     *                  [
     *                  [
     *                      "NOT"
     *                  ]
     *                  ],
     *              [
     *                  [
     *                      "Her"
     *                  ],
     *                  [
     *                      "her"
     *                  ]
     *              ]
     *          ],
     *          "answer_base64":[
     *              ["data:image/png;base64,iVBO"],
     *              ["data:image/png;base64,iVBO"],
     *              ["data:image/png;base64,iVBO"],
     *          ],
     *          "analyze":[
     *              {
     *                  "title":"\u89e3\u9898\u65b9\u6cd5\u4e00",
     *                  "content":[
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u4e00\u6b65",
     *                          "is_has_answer":0
     *                      },
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                          "is_has_answer":1
     *                      },
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                          "is_has_answer":1
     *                      },
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u56db\u6b65",
     *                          "is_has_answer":0
     *                      }
     *                  ]
     *              },
     *              {
     *                  "title":"\u89e3\u9898\u601d\u8def\u4e8c",
     *                  "content":[
     *                      [
     *                           {
     *                              "content":"\u7b2c\u4e8c\u79cd\u65b9\u6848\u7b2c\u4e00\u6b65",
     *                              "is_has_answer":0
     *                            },
     *                          {
     *                              "content":"\u7b2c\u4e8c\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                              "is_has_answer":1
     *                          },
     *                          {
     *                              "content":"\u7b2c\u4e8c\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                              "is_has_answer":1
     *                          }
     *                      ]
     *                  ]
     *              }
     *          ],
     *      "video_url":"http:\/\/media1.classba.cn\/math_qm_05.mp4",
     *      "used_type":1,
     *      "module":"1",
     *      "tag_code":"ct_12",
     *      "tag_code_timeline":[
     *              {
     *                  "tag_code":"zk_1",
     *                  "img_url":"http://f.hiphotos.baidu.com/image/h%3D360/sign=6b518201f8faaf519be387b9bc5494ed/738b4710b912c8fc6684dceaf9039245d68821a5.jpg"
     *              }.
     *              {
     *                  "tag_code":"zk_2",
     *                  "img_url":"http://g.hiphotos.baidu.com/image/pic/item/30adcbef76094b362ae9c3e9a1cc7cd98d109dbf.jpg"
     *              }
     *              {
     *                  "tag_code":"zk_3",
     *                  "img_url":"http://g.hiphotos.baidu.com/image/pic/item/aa18972bd40735fa109f91bd9c510fb30f240843.jpg"
     *              }
     *
     *          ]
     *      }
     * }
     * @apiError UserNotFound The id of the User was not found.
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UserNotFound"
     *     }
     */
    public function getExamQuestions() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $module_type = config('bxbl_module_type');
//        $kmap_code = config('kmap_code');  //知识图谱
        $topic_service = new TopicService();
        $knowledge_service = new KnowledgeService();
        $kmap_code = $topic_service->getKmapCodeByTopicId($topicId);
        $used_type = config('learn_question');
        $xiance_module_type = config('xiance_module_type');
        $user_id = $this->getUserId();
        //先不走算法,自己模拟先走一遍.
        $algologic = new AlgoLogic();
        $user_service = new UserService();
        $question_service = new QuestionService();
        $is_enter_Detect = $question_service->isEnterToDetect("", $topicId, $module_type);
        $tag_name = "";
        $has_answered_questions = $user_service->getUserHasAnsweredQuestionsByModuleType("", $topicId, $module_type);
        $api_gate_service = new ApiGateService();
        if ($is_enter_Detect) {
            $return_array = array(
                "question_list" => "",
                "tag_code_timeline" => "",
                "tag_code" => "",
//                "has_answered_questions"=>$has_answered_questions,
                "is_enter" => $is_enter_Detect
            );
        } else {
            $lastUserLog = $question_service->getLastUserExamActionLog($topicId, $module_type);
            //先不用缓存。
            //$lastUserLog = array();
            if (!empty($lastUserLog) && $lastUserLog['is_submit'] != 1) {
                session('tag_code', $lastUserLog['tag_code']);
                $tag_code = session('tag_code');
                $api_gate_service = new ApiGateService();
                $knowledgeList = $api_gate_service->getKnowledgeListByTopicId($topicId);

                $ability_num = $algologic->getUserAbilityForTagCode("", $topicId, $xiance_module_type, $tag_code);
                $knowledge_v2_service = new KnowledgeV2Service();
                $tag_video_list = array();
                foreach ($knowledgeList as $knowledgeListItem) {
                    if ($knowledgeListItem["tag_code"] == $tag_code) {
                        $tag_namearr=$knowledge_v2_service->getKnowledgeByCode($knowledgeListItem["tag_code"]);
                        $tag_video_list = $tag_namearr['video'];
                        break;
                    }
                }
                $knowledge_video_num = count($tag_video_list);
                $tag_video = "";
                if (!empty($tag_video_list)) {
                    if ($knowledge_video_num == 1) {
                        $tag_video = $tag_video_list[0]['video_url'];
                    } else {
                        if ($ability_num <= 0.4) {
                            foreach ($tag_video_list as $key => $val) {
                                $tag_video_list[$key]['is_selected'] = 0;
                                if (trim($val['description']) === "基础") {
                                    $tag_video = $val['video_url'];
                                    $tag_video_list[$key]['is_selected'] = 1;
                                }
                            }
                        } else {
                            foreach ($tag_video_list as $key => $val) {
                                $tag_video_list[$key]['is_selected'] = 0;
                                if (trim($val['description']) === "巩固") {
                                    $tag_video = $val['video_url'];
                                    $tag_video_list[$key]['is_selected'] = 1;
                                }
                            }
                        }
                    }
                } else {
                    Log::record("------试题内容问题,没有知识点视频。------------");
                    $log_service = new logService();
                    $log_service::sendMessage('error', __METHOD__ . "此 $tag_code 知识点,没有返回知识点视频");
                }

                //$question_info=unserialize($lastUserLog['question_info']);
                $api_gate_service = new ApiGateService();
                $question_info = $api_gate_service->getQuestionById($lastUserLog['question_id'],$topicId);
                $return_array = array(
                    "is_enter" => 0,
                    "question_list" => $question_info,
                    "has_answered_questions" => $has_answered_questions,
                    "tag_code" => $lastUserLog['tag_code'],
                    "tag_name" => $tag_name,
                    "tag_video" => $tag_video,
                    "tag_video_list" => $tag_video_list
                );
            } else {


                $topicInfo = $topic_service->getTopicByTopicId($topicId);
                if(isset($topicInfo['flow_id'])) {
                    $flow_id = $topicInfo['flow_id'];
                }else{
                    exit("未设置flow_id");
                }
//                $flow_id =7;
                if($flow_id==7)
                {
                    $tag_code = $user_service->getUserBxblNextOnlyOneTagCode($kmap_code, $topicId, $used_type);
                    Log::record("-------getUserBxblNextTagCode----getExamQuestions-----------tag_code--" . $tag_code);
                }else{
                    $tag_code = $user_service->getUserBxblNextTagCode($kmap_code, $topicId, $used_type);
                    Log::record("-------getUserBxblNextTagCode----getExamQuestions-----------tag_code--" . $tag_code);
                }


                if ($tag_code == -1 || $tag_code == "") {
                    //记录下来用户知识点已经学完.
                    $has_learned_all_tag_code = 1;
                    $is_end = 0;
                    $SteplogService_id = $user_service->insertUserStep($topicId, $module_type, $has_learned_all_tag_code, $is_end);

                    $is_enter_ttq = 1;
                    $return_array = array(
                        "question_list" => "",
                        "tag_code_timeline" => "",
                        "tag_code" => "",
                        "has_answered_questions" => $has_answered_questions,
                        "is_enter" => $is_enter_ttq
                    );
                } else {
                    session('tag_code', $tag_code);

                    $knowledgeList =  $api_gate_service->getKnowledgeListByTopicId($topicId);

                    $user_has_Learned_tag_code = $user_service->getUserHasLearnedTagCode("", $topicId, $module_type);
                    $new_tag_arr = array();
                    foreach ($user_has_Learned_tag_code as $k => $v) {
                        $new_tag_arr[] = $v['tag_code'];
                    }
                    if (!in_array($tag_code, $new_tag_arr)) {
                        $user_has_Learned_tag_code[]['tag_code'] = $tag_code;
                    }

                    $tag_code_timeline = array();
                    $i = 0;
                    foreach ($user_has_Learned_tag_code as $kk => $val) {
                        foreach ($knowledgeList as $k => $v) {
                            if ($v['tag_code'] == $val['tag_code']) {
                                $tag_code_timeline[$i]['tag_code'] = $v['tag_code'];

                                $knowledge_v2_service = new KnowledgeV2Service();
                                $tag_namearr=$knowledge_v2_service->getKnowledgeByCode($v['tag_code']);

                                $tag_code_timeline[$i]['tag_name'] = $tag_namearr['tag_name'];
                                if ($v['tag_code'] == $tag_code) {
                                    $tag_name = $tag_namearr['tag_name'];
                                }
                                $i++;
                            }
                        }
                    }
                    $question_service = new QuestionService();
                    $question_list = $question_service->getBxblNextQuestion("", $tag_code, $module_type, $used_type, $topicId);
                    if (!empty($question_list)) {
                        $question_id = $question_list["id"];
                        $question_service->insertUserExamActionLog('', $topicId, $module_type, $question_id, $question_list, $tag_code);
                    }

                    //获取用户的某一个知识点的在先行测试的module_type。
                    $xiance_module_type = config('xiance_module_type');
                    $ability_num = $algologic->getUserAbilityForTagCode("", $topicId, $xiance_module_type, $tag_code);
                    $tag_video_list = array();
                    foreach ($knowledgeList as $knowledgeListItem) {
                        if ($knowledgeListItem["tag_code"] == $tag_code) {
                            $tag_namearr=$knowledge_v2_service->getKnowledgeByCode($knowledgeListItem["tag_code"]);
                            $tag_video_list = $tag_namearr['video'];
                            break;
                        }
                    }
                    $knowledge_video_num = count($tag_video_list);
                    $tag_video = "";
                    if (!empty($tag_video_list)) {
                        if ($knowledge_video_num == 1) {
                            $tag_video = $tag_video_list[0]['video_url'];
                        } else {
                            if ($ability_num <= 0.4) {
                                foreach ($tag_video_list as $key => $val) {
                                    $tag_video_list[$key]['is_selected'] = 0;
                                    if (trim($val['description']) === "基础") {
                                        $tag_video = $val['video_url'];
                                        $tag_video_list[$key]['is_selected'] = 1;
                                    }
                                }
                            } else {
                                foreach ($tag_video_list as $key => $val) {
                                    $tag_video_list[$key]['is_selected'] = 0;
                                    if (trim($val['description']) === "巩固") {
                                        $tag_video = $val['video_url'];
                                        $tag_video_list[$key]['is_selected'] = 1;
                                    }
                                }
                            }
                        }
                    } else {
                        Log::record("------试题内容问题,没有知识点视频。------------");
                        $log_service = new logService();
                        $log_service::sendMessage('error', __METHOD__ . "此 $tag_code 知识点,没有返回知识点视频");
                    }


                    $weakElements = $algologic->getWeakElements($user_id, $topicId);
                    $haveLearnedWekkElements = $user_service->getHaveLeardTagCode($user_id, $topicId, $module_type);
                    $have_learned = 0;
                    if ($haveLearnedWekkElements) {
                        $have_learned = count($haveLearnedWekkElements) . "/" . count($weakElements);
                    }
                    $return_array = array(
                        "question_list" => $question_list,
                        "tag_code_timeline" => $tag_code_timeline,
                        "tag_code" => $tag_code,
                        "tag_name" => $tag_name,
                        "has_answered_questions" => $has_answered_questions,
                        "tag_video" => $tag_video,
                        "tag_video_list" => $tag_video_list,
                        "is_enter" => 0,
                        "have_learned" => $have_learned,
                    );
                }
            }
        }
        if (isset($tag_code) && $tag_code) {
            $knowledge_v2_service = new  KnowledgeV2Service();
            $tag_namearr = $knowledge_v2_service->getKnowledgeByCode($tag_code, $topicId);
            $return_array['tag_name'] = $tag_namearr['tag_name'];
        }
        
         $total_num=$user_service->getUserHasAnsweredQuestionsByModuleType("", $topicId, $module_type);
        if(!empty($total_num)){
            $right_num=$user_service->getUserAnsweredAllRightQuestionsByModuleType("", $topicId, $module_type);
            $right_scale=count($right_num)/ count($total_num);
        } else {
            $right_scale=0;
        }
        $has_learedCode_scale = $user_service->getUserHasLearnedCodeScale("",$topicId,$module_type);
        
        $return_array['right_scale'] = $right_scale;
        $return_array['has_learedCode_scale'] =$has_learedCode_scale;
        
        
        echo json_encode($return_array);
    }

    /**
     * @api {post} index/Bxbl/submitQuestion  试题提交
     * @apiVersion 0.0.1
     * @apiName  submitQuestion  试题提交
     * @apiGroup Bxbl
     *
     * @apiParam {Number} topicId  用户topicId.
     * @apiParam {array}  answer_content  用户提交答案,结构如下: array({"question_id"=>12,"answer"=>"A",'type'=>3},{"question_id"=>12,"answer"=>"B",'type'=>1});
     * @apiSuccess {Number} isSuccess  是否提交成功.
     * @apiSuccess {String} error  错误信息.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "isSuccess":1,
     *         "error": ""
     *     }
     */
    public function submitQuestion() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $answer_content = input("answer_content/a");

//        $question_id = session("question_id");
//        $topicId = 9;
//        $answer_content = array(array(
//            'question_id' => $question_id,
//            'type' => 1,
//            'answer' => "fsdfa"
//        ));

        $tag_code = session('tag_code');
        $module_type = config('bxbl_module_type');
        $used_type = 2;   //1 表示测试题,  2 表示练习题
        $submodule_type = 1; ////只有高效学习需要试题重做。
        $question_service = new QuestionService();
        $isSuccess = $question_service->submitQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type, $submodule_type);
        echo json_encode($isSuccess);
    }

    /**
     * 高效学习重做接口。
     */
    public function redoSubmitQuestion() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $answer_content = input("answer_content/a");


        $tag_code = session('tag_code');
        $module_type = config('bxbl_module_type');
        $used_type = 2;   //1 表示测试题,  2 表示练习题
        $submodule_type = 1;  //只有高效学习需要试题重做。
        $question_service = new questionService();
        $isSuccess = $question_service->redoSubmitQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type, $submodule_type);
        echo json_encode($isSuccess);


//        var_dump($answer_content);
//        var_dump($topicId);
//        $return_data = array(
//            "isSuccess" => 1,
//            "is_right" => 0
//        );
//        echo  $return_data;
    }

    /**
     * @api {get} /index/Bxbl/GetDetectQuestion   学习检测获取试题
     * @apiVersion 0.0.1
     * @apiName  ttqGetQuestion
     * @apiGroup  Bxbl~ttq
     *
     * @apiParam {Number} topicId  用户topicId.
     * @apiSuccess {Number} is_end  先行测试是否结束.
     * @apiSuccess {Array} question_list  试题信息.
     * @apiSuccess {String} error  错误信息.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *      "is_end":0,
     *      "question_list":{
     *          "id":10,
     *           "content":"\u6d4b\u8bd5\u8bd5\u9898",
     *          "q_type":1,
     *          "options":[
     *              {
     *                  "key":"A",
     *                  "answer":" this is A"
     *              },
     *              {
     *                  "key":"B",
     *                  "answer":"this is B"
     *              },
     *              {
     *                  "key":"C",
     *                  "answer":"this is C"
     *              }
     *              ],
     *          "answer":[
     *              [
     *                  [
     *                      "is not"
     *                  ],
     *                  [
     *                          "Is NOt"
     *                  ],
     *                  [
     *                      "IS NOT"
     *                  ]
     *                  ],
     *                  [
     *                  [
     *                      "NOT"
     *                  ]
     *                  ],
     *              [
     *                  [
     *                      "Her"
     *                  ],
     *                  [
     *                      "her"
     *                  ]
     *              ]
     *          ],
     *          "answer_base64":[
     *              ["data:image/png;base64,iVBO"],
     *              ["data:image/png;base64,iVBO"],
     *              ["data:image/png;base64,iVBO"],
     *          ],
     *          "analyze":[
     *              {
     *                  "title":"\u89e3\u9898\u65b9\u6cd5\u4e00",
     *                  "content":[
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u4e00\u6b65",
     *                          "is_has_answer":0
     *                      },
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                          "is_has_answer":1
     *                      },
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                          "is_has_answer":1
     *                      },
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u56db\u6b65",
     *                          "is_has_answer":0
     *                      }
     *                  ]
     *              },
     *              {
     *                  "title":"\u89e3\u9898\u601d\u8def\u4e8c",
     *                  "content":[
     *                      [
     *                           {
     *                              "content":"\u7b2c\u4e8c\u79cd\u65b9\u6848\u7b2c\u4e00\u6b65",
     *                              "is_has_answer":0
     *                            },
     *                          {
     *                              "content":"\u7b2c\u4e8c\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                              "is_has_answer":1
     *                          },
     *                          {
     *                              "content":"\u7b2c\u4e8c\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                              "is_has_answer":1
     *                          }
     *                      ]
     *                  ]
     *              }
     *          ],
     *      "video_url":"http:\/\/media1.classba.cn\/math_qm_05.mp4",
     *      "used_type":1,
     *      "module":"1",
     *      "tag_code":"ct_12"
     *      }
     * }
     * @apiError UserNotFound The id of the User was not found.
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UserNotFound"
     *     }
     */
    public function GetDetectQuestion() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $user_id = $this->getUserId();
        $user_service = new UserService();
        $module_type = config('bxbl_module_type');
        $detect_question_service = new DetectQuestionService();
        $knowledge_service = new KnowledgeService();
        $topic_service = new TopicService();
        $question_service = new questionService();
        $return_data = $detect_question_service->getDetectNextQuestion($topicId);
        if (isset($return_data['tag_code']))
            $tag_code = $return_data['tag_code'];
        $question_list = $return_data['question_list'];
        $user_service = new UserService();
        $tag_name = "";
        $has_answered_questions = $user_service->getUserHasAnsweredQuestionsByModuleType("", $topicId, $module_type);
        if (empty($question_list)) {
            //如果用户已经学完全部知识点,怎边学边练全部结束.
            $user_step = $user_service->getUserStep($topicId, $user_id, $module_type);
            $has_learned_all_tag_code = $user_step['has_learned_all_tag_code'];
            if ($has_learned_all_tag_code == 1) {
                $user_service->updateUserStepLog($user_id, $topicId, $module_type, 1);
                $return_array = array(
                    "question_list" => "",
                    "tag_code_timeline" => "",
                    "tag_code" => "",
                    "has_answered_questions" => $has_answered_questions,
                    "is_end" => 1,
                    "is_all_end" => 1
                );
            } else {
                $return_array = array(
                    "question_list" => "",
                    "tag_code_timeline" => "",
                    "tag_code" => "",
                    "is_end" => 1,
                    "has_answered_questions" => $has_answered_questions,
                    "is_all_end" => 0
                );
            }
        } else {
            $tag_code = $return_data['tag_code'];
            session('tag_code', $tag_code);
            $kmap_code = $topic_service->getKmapCodeByTopicId($topicId);
            $api_gate_service = new ApiGateService();
            $knowledgeList = $api_gate_service->getKnowledgeListByTopicId($topicId);
            $user_has_Learned_tag_code = $user_service->getUserHasLearnedTagCode("", $topicId, $module_type);

            $user_detect_need_to_learn_tag_code = $user_service->getUserDetectNeedToLearnTagCode("", $topicId);
            $tag_codes_arr = array_unique($user_detect_need_to_learn_tag_code['tag_codes']);


            $new_tag_arr = array();
            foreach ($user_has_Learned_tag_code as $k => $v) {
                $new_tag_arr[] = $v['tag_code'];
            }
            if (!in_array($tag_code, $new_tag_arr)) {
                $user_has_Learned_tag_code[]['tag_code'] = $tag_code;
            }
            $tag_code_timeline = array();
            $i = 0;
            foreach ($tag_codes_arr as $kk => $val) {
                foreach ($knowledgeList as $k => $v) {
                    if ($v['tag_code'] == $val) {
                        $tag_code_timeline[$i]['tag_code'] = $v['tag_code'];
                        $knowledge_v2_service = new KnowledgeV2Service();
                        $tag_namearr=$knowledge_v2_service->getKnowledgeByCode($v['tag_code']);
                        $tag_code_timeline[$i]['tag_name']=$tag_namearr['tag_name'];
                        if ($v['tag_code'] == $tag_code) {
                            $tag_name = $tag_namearr['tag_name'];
                        }
                        $i++;
                    }
                }
            }
            $api_gate_service = new ApiGateService();
            $knowlegeCode = $api_gate_service->getKnowlegeCode($kmap_code, $tag_code,$topicId);


            if(!empty($knowlegeCode))
            {
                $tag_video = $knowlegeCode["video"][0]['video_url'];
            }else{
                $tag_video = "";
            }
            $return_array = array(
                "question_list" => $question_list,
                "tag_code_timeline" => $tag_code_timeline,
                "tag_code" => $tag_code,
                'tag_name' => $tag_name,
                "has_answered_questions" => $has_answered_questions,
                "tag_video" => $tag_video,
                "is_end" => 0,
                "is_all_end" => 0
            );
            $question_service->insertUserExamActionLog('', $topicId, $module_type, $question_list['id'], $question_list, $tag_code);
        }
        if (isset($tag_code) && $tag_code) {
            $knowledge_v2_service =new KnowledgeV2Service();
            $tag_namearr=$knowledge_v2_service->getKnowledgeByCode($tag_code);
            $return_array['tag_name'] = $tag_namearr['tag_name'];
        }
        $total_num=$user_service->getUserHasAnsweredQuestionsByModuleType("", $topicId, $module_type);
        if(!empty($total_num)){
            $right_num=$user_service->getUserAnsweredAllRightQuestionsByModuleType("", $topicId, $module_type);
            $right_scale=count($right_num)/ count($total_num);
        } else {
            $right_scale=0;
        }
        $has_learedCode_scale = $user_service->getUserHasLearnedCodeScale("",$topicId,$module_type);
        
        $return_array['right_scale'] = $right_scale;
        $return_array['has_learedCode_scale'] =$has_learedCode_scale;
        echo json_encode($return_array);
    }

    /**
     * @api {post} index/bxbl/ttq_Report  堂堂清报告页
     * @apiVersion 0.0.1
     * @apiName  ttq_Report  试题提交
     * @apiGroup Bxbl~ttq
     * @apiSuccess {String} tag_code  知识点.
     * @apiSuccess {double} scale  掌握程度.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         {
     *              "tag_code"=>"zh_1",
     *              "scale"=>0.8
     *          },
     *          {
     * "tag_code"=>"zh_2",
     *              "scale"=>0.5
     *          }
     *
     *     }
     */
//    public function ttq_Report()
//    {
//
//        $return_array = array(
//            array(
//                'tag_code' => "ct_1",
//                'scale' => 0.8
//            ),
//            array(
//                'tag_code' => "ct_2",
//                'scale' => 0.5
//            ),
//            array(
//                'tag_code' => "ct_3",
//                'scale' => 0.2
//            )
//        );
//        return json_encode($return_array);
//    }

    /**
     * @api {post} index/bxbl/lookAnalyze  用户查看了分步骤解析
     * @apiVersion 0.0.1
     * @apiName  lookAnalyze  试题提交
     * @apiGroup Bxbl
     * @apiParam {Number} topicId  专题topicId.
     * @apiParam {Number} question_id  试题ID.
     * @apiSuccess {Number} isSuccess  成功.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         'isSuccess':1
     *     }
     */
    public function lookAnalyze() {
        $request = Request::instance();
        $question_id = $request->param('question_id');
        $tag_code = $request->param('tag_code');

        $return_data = array(
            'isSuccess' => 1
        );
        return json_encode($return_data);
    }

    /**
     * @api {post} index/Bxbl/ttqSubmitQuestion  试题提交
     * @apiVersion 0.0.1
     * @apiName  ttqSubmitQuestion  试题提交
     * @apiGroup Bxbl~ttq
     *
     * @apiParam {Number} topicId  用户topicId.
     * @apiParam {array}  answer_content  用户提交答案,结构如下: array({"question_id"=>12,"answer"=>"A",'type'=>3},{"question_id"=>12,"answer"=>"B",'type'=>1});
     * @apiSuccess {Number} isSuccess  是否提交成功.
     * @apiSuccess {String} error  错误信息.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "isSuccess":1,
     *         "error": ""
     *     }
     */
    public function detectSubmitQuestion() {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $answer_content = input("answer_content/a");
        $tag_code = session('tag_code');
//        $module_type = config('xuexi_module_type');
        $module_type = config('bxbl_module_type');
//        $question_id = session("question_id");

        $submodule_type = 2;   //数学产品现在不分子模块了。
        $used_type = 2;   //1 表示测试题,  2 表示练习题
        $question_service = new QuestionService();
        $isSuccess = $question_service->submitQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type, $submodule_type);

        echo json_encode($isSuccess);
    }

}
