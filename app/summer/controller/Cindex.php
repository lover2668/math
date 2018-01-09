<?php
namespace app\summer\controller;

use service\services\BaseQuestionV2Service;
use service\services\KnowledgeV2Service;
use service\services\summer\SummerCindexService;
use service\services\summer\SummerUserService;
use service\services\TopicV2Service;
use service\services\UserService;
use think\Request;
use  service\services\QuestionService;
use  service\services\AlgoService;
use  service\algo\AlgoLogic;
use  think\Log;
use app\index\controller\Base;
class Cindex extends Base
{



    function startIndex()
    {

        $request = Request::instance();
        $topicId = $request->param('topicId');
        $userService=new UserService();
        $user_id=$this->getUserId();

        $xiance_module_type = config('l2_xiance_module_type');
        $bxbl_module_type = config('l2_bxbl_module_type');

        $xxcsStep=$userService->getUserStep($topicId,$user_id,$xiance_module_type);//先行测试
        $bxblStep=$userService->getUserStep($topicId,$user_id,$bxbl_module_type);//边学边练
        $questionService=new QuestionService();
        $url="";
        if($xxcsStep)
        {

            if($bxblStep&&$bxblStep['is_end']==1)
            {
                if($xxcsStep['is_all_right']==1){
                    $url=url("preReport",array("topicId"=>$topicId));
                }else{
                    $url=url("Bxbl/studyReport",array("topicId"=>$topicId));
                }

            }else
            {
                $isDoBxbl=$questionService->getLastUserExamActionLog($topicId,$bxbl_module_type,$user_id);
                if($isDoBxbl)
                {
                    $url=url("Bxbl/bIndex",array("topicId"=>$topicId));
                }else
                {
                    $url=url("preReport",array("topicId"=>$topicId));
                }

            }
        }

        if($url)
        {
            $this->redirect($url);
        }else
        {

            $topic_v2_service=new TopicV2Service();
            $topic=$topic_v2_service->getTopicByTopicId($topicId);
            $topicNmae=$topic["topic_name"];
            $this->assign("topicId", $topicId);
            $this->assign("topicName", $topicNmae);
            return $this->fetch("startIndex");
        }


    }


    function index()
    {
        $topic_v2_service = new TopicV2Service();
        $topicList =  $topic_v2_service->getTopicList();
        $this->assign("result",$topicList);
        return $this->fetch("index");
    }

    /**
     *
     * @param int $topicId
     * @return mixed
     */
    public function preIndex()
    {

        $request=Request::instance();
        $topicId=$request->param("topicId");
        $userService=new UserService();
        $user_id=$this->getUserId();

        $xiance_module_type = config('xiance_module_type');
        $bxbl_module_type = config('bxbl_module_type');

        $xxcsStep=$userService->getUserStep($topicId,$user_id,$xiance_module_type);//先行测试
        $bxblStep=$userService->getUserStep($topicId,$user_id,$bxbl_module_type);//边学边练
        $questionService=new QuestionService();
        $url="";
        if($xxcsStep)
        {
            if($bxblStep&&$bxblStep['is_end']==1)
            {
                if($xxcsStep['is_all_right']==1){
                    $url=url("preReport",array("topicId"=>$topicId));
                }else{
                    $url=url("Bxbl/studyReport",array("topicId"=>$topicId));
                }

            }else
            {
                $isDoBxbl=$questionService->getLastUserExamActionLog($topicId,$bxbl_module_type,$user_id);
                if($isDoBxbl)
                {
                    $url=url("Bxbl/bIndex",array("topicId"=>$topicId));
                }else
                {
                    $url=url("preReport",array("topicId"=>$topicId));
                }

            }
        }
        if($url)
        {
            $this->redirect($url);
        }else{
            $topic_v2_service = new TopicV2Service();
            $topic=$topic_v2_service->getTopicByTopicId($topicId);
            $topicNmae=$topic["topic_name"];

            $this->assign("topicId", $topicId);
            $this->assign("topic_name", $topicNmae);
            return $this->fetch("preIndex");
        }
    }


    /**
     * @api {get} /index/index/getExamQuestions   获取试题
     * @apiVersion 0.0.1
     * @apiName  getExamQuestions
     * @apiGroup  xiance/Index
     *
     * @apiParam {Number} initKStatus  用户自评值.
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
     *              ["data:image/png;base64,iVBO"]
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
    public function getExamQuestions()
    {
        //获取用户自评的掌握程度
        $request = Request::instance();
        $initKStatus = $request->param('initKStatus');
        $initKStatus=3;//默认传3，原哥讲的
        // $topicId=$this->getTopicId();
        $request=Request::instance();
        $topicId=$request->param("topicId");
//        $topicId = $this->getTestTopicId();
        session('initKStatus', $initKStatus);
        $module_type = config('l2_xiance_module_type');
        $used_type = config('test_question');
        $user_id = $this->getUserId();
        $user_service = new UserService();

        $row = $user_service->getUserStep($topicId, $user_id, $module_type);
        if ($row) {
            //如果找到并且已经结束
            if ($row["is_end"] == 1) {
                $returnData = array(
                    "is_end" => 1,
                    "question_list" => "",
                    "error" => "你已经做过先行测试了"
                );
                echo json_encode($returnData);
                return;
            }
        }
        $summer_user_service = new SummerUserService();
        $summer_cindex_service = new SummerCindexService();
        $kmap_code = $summer_cindex_service->getCxianceKmapCode("",$topicId);

//        $kmap_code  = $this->getTestKmapCode();

        //首先判断用户是否已经读取过此知识点的题,如果读过,并且没做,那么还是显示出此题.如果做过的话,重新读新的知识点.
        $question_service = new QuestionService();
        Log::record("------before-------getLastUserExamActionLog");

        $lastUserLog = $question_service->getLastUserExamActionLog($topicId, $module_type);
        Log::record("------after-------getLastUserExamActionLog");
        $user_service = new UserService();
        Log::record("------before-------getUserHasAnsweredQuestionsByModuleType");
        $has_answered_questions = $user_service->getUserHasAnsweredQuestionsByModuleType("", $topicId, $module_type);
        Log::record("------after-------getUserHasAnsweredQuestionsByModuleType");
        $algoLogic = new AlgoLogic();
        $level_mode = config("level_mode_1");

        if (!empty($lastUserLog) && $lastUserLog['is_submit'] != 1) {
            session('tag_code', $lastUserLog['tag_code']);
            $tag_code=session('tag_code');
            //$question_info=unserialize($lastUserLog['question_info']);
            $question_v2_service = new BaseQuestionV2Service();
            $question_info=$question_v2_service->getQuestionById($lastUserLog['question_id']);
            $return_array = array(
                "is_end" => 0,
                "question_list" => $question_info,
                "has_answered_questions" => $has_answered_questions,
                "tag_code" => $lastUserLog['tag_code']
            );
        } else {


            //获取用户最后一道题做的对错.
            Log::record("------before-------getUserLastAnswerIsRight");
            $last_answer_is_right = $user_service->getUserLastAnswerIsRight("", $topicId, $module_type);
            Log::record("------after-------getUserLastAnswerIsRight");
            try {
                Log::record("------before-------get_xiance_tagCode");
                $return_tag_code = $algoLogic->get_l2_xiance_tagCode($topicId, $initKStatus, $kmap_code, $level_mode, $last_answer_is_right,$module_type);
//                if(session('userInfo.user_id')=='6480')error_log(print_r($return_tag_code,1).'专题id'.$topicId."下一个知识点\n", 3, APP_PATH.'lllll.txt');
                Log::record("------after-------get_xiance_tagCode");
            } catch (Exception $e) {
                print $e->getMessage();
                exit();
            }

            if ($return_tag_code['error']) {
                $return_array = array(
                    "is_end" => 0,
                    "question_list" => "",
                    "error" => $return_tag_code['error']
                );
            } else {
                if ($return_tag_code['tag_code'] == -1) {
                    //题目做完了埋点  这里需要插入数据库 end 1 代表结束了
                    Log::record("------before-------insertUserStep");
                    $has_learned_all_tag_code = 1;
                    $is_end = 1;
                    $SteplogService_id = $user_service->insertUserStep($topicId, $module_type, $has_learned_all_tag_code, $is_end);
                    Log::record("------after-------insertUserStep");
                    //题目做完了埋点  这里需要插入数据库 end 1 代表结束了 end
                    $return_array = array(
                        'is_end' => 1,
                        'question_list' => "",
                        "has_answered_questions" => "",
                        "tag_code" => "",
                        "error" => ""
                    );
                } else {
                    $tag_code = $return_tag_code['tag_code'];
                    session('tag_code', $tag_code);
                    Log::record("------before-------getXianceNextQuestion");
                    $question_list = $question_service->getL2XianceNextQuestion($topicId, $tag_code, $module_type, $used_type);
                    Log::record("------after-------getXianceNextQuestion");

                    if (empty($question_list)) {
                        $return_array = array(
                            'is_end' => 1,
                            'question_list' => "",
                            "has_answered_questions" => "",
                            "tag_code" => "",
                            "error" => ""
                        );

                    } else {
                        if (!$question_list["error"]) {
                            $question_id = $question_list["id"];


                            $question_service->insertUserExamActionLog('', $topicId, $module_type, $question_id, $question_list, $tag_code);
                            $return_array = array(
                                "is_end" => 0,
                                "question_list" => $question_list,
                                "has_answered_questions" => $has_answered_questions,
                                "tag_code" => $tag_code,
                                "error" => ""
                            );
                        } else {
                            $return_array = array(
                                "is_end" => 0,
                                "question_list" => "",
                                "has_answered_questions" => $has_answered_questions,
                                "tag_code" => $tag_code,
                                "error" => $question_list["error"]
                            );
                        }

                    }


                }
            }
        }
        if(isset($tag_code)&&$tag_code){
            $knowledge_v2_service =  new KnowledgeV2Service();
            $tag_namearr = $knowledge_v2_service->getKnowledgeByCode($tag_code);
            $return_array['tag_name']=$tag_namearr['tag_name'];
        }
//        $return_array['question_list']="";
        echo json_encode($return_array);
    }


    /**
     * @api {post} index/index/submitQuestion  试题提交
     * @apiVersion 0.0.1
     * @apiName  submitQuestion  试题提交
     * @apiGroup xiance/Index
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
    public function submitQuestion()
    {
        $request = Request::instance();
        //$topicId=$this->getTopicId();
        $request=Request::instance();
        $topicId=$request->param("topicId");
        $answer_content = input("answer_content/a");
        $tag_code = session('tag_code');
        $module_type = config('l2_xiance_module_type');

        $used_type = 1;   //1 表示测试题,  2 表示练习题
        $question_service = new QuestionService();
        $submodule_type = 0;
        $isViewAnalyze = 0;
        $is_update_ability = 0;

        try {
            $isSuccess = $question_service->submitSpingQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type,$submodule_type,$isViewAnalyze,$is_update_ability);
        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }


        echo json_encode($isSuccess);
    }


    /**
     * @api {get} index/index/topicSelect  专题列表
     * @apiVersion 0.0.1
     * @apiName  topicSelect  专题列表
     * @apiGroup xiance/Index
     *
     * @apiSuccess {Number} id  专题id.
     * @apiSuccess {String} topic  专题内容信息.
     *
     * @apiSuccess {String} pic_url  专题图片地址.
     * @apiSuccess {String} video_url 专题视频地址.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         {
     *             "id":"9",
     *             "topic":"\u671f\u672b\u51b2\u523a\u4e94\u5e74\u7ea7",
     *             "pic_url":"http:\/\/img.classba.com.cn\/2016-05-02_57271c340b378.jpg",
     *             "video_url":"http:\/\/media1.classba.cn\/math_qm_05.mp4",
     *         },
     *          {
     *              "id":"9",
     *              "topic":"\u671f\u672b\u51b2\u523a\u4e94\u5e74\u7ea7",
     *              "pic_url":"http:\/\/img.classba.com.cn\/2016-05-02_57271c340b378.jpg",
     *              "video_url":"http:\/\/media1.classba.cn\/math_qm_05.mp4",
     *          }
     *     }
     */

    /**
     * 专题导航文字
     * @return mixed
     */
    public function wordIndex()
    {
//        $test = new questionService();
//        $test->index();
        $request = Request::instance();
        //$topicId=$this->getTopicId();
        $request=Request::instance();
        $topicId=$request->param("topicId");
        $this->assign("topicId", $topicId);
        $initKStatus = $request->param('initKStatus');
        $this->assign("initKStatus", $initKStatus);
        return $this->fetch("wordIndex");
    }

    /**
     * 先行测试报告页
     * @return mixed
     */
    public function preReport()
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $request = Request::instance();
        $algoLogic = new AlgoLogic();
        //$topicId=$this->getTopicId();
        $request=Request::instance();
        $topicId=$request->param("topicId");
        $topic_v2_service = new TopicV2Service();
        $knowledge_v2_service =  new KnowledgeV2Service();
        $topic=$topic_v2_service->getTopicByTopicId($topicId);
        $topicNmae=$topic["topic_name"];

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

        $user_service=new UserService();
        $summer_user_service = new SummerUserService();
        $xiance_module_type = config('l2_xiance_module_type');
        $summer_cindex_service = new SummerCindexService();
        $kmap_code = $summer_cindex_service->getCxianceKmapCode("",$topicId);
        $knowledgeList = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);
        $weakElements = $algoLogic->getL2WeakElements("", $topicId);

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
        $average_ability = $user_service->getUserAverageAbility("", $topicId, $xiance_module_type);

        //用户做过的试题信息.
        $has_answered_questions = $summer_user_service->getUserHasAnsweredQuestionsByModule("", $topicId, $xiance_module_type);

        $user_id = $this->getUserId();
        $xxcsIsAllRight = $user_service->getUserIsAllRight($topicId, $xiance_module_type, $user_id);

        $has_learned_percent=round(($has_learned_num/($has_learned_num+$weakElements_num))*100);//掌握的百分比

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




        $this->assign('is_show_report',$is_show_report);
        $this->assign('is_show_nextstep',$is_show_nextstep);

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
        return $this->fetch("preReport");
    }


    public function submitCorrection(){
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info=true;
        if($info){
            $file_path= cookie('file_path');//isset($_REQUEST['file_path'])?$_REQUEST['file_path']:'';
            $question_service = new QuestionService();
            $data=[];
            $data['user_id']= $this->getUserId();
            $data['question_id']=input('question_id');
            $data['content']=input('content');
            $data['file_path']=$file_path;
            $ok=$question_service->addErrorCorrection($data);
            if($ok){
                cookie('file_path',null);
                return $this->success("操作成功");
            }else{
                return $this->error("操作失败");
            }
        }else{
            // 上传失败获取错误信息
            return $this->error("文件上传失败");
        }
    }
    public function submitFile(){
        $file = request()->file('myfile');
        // 移动到框架应用根目录/www/uploads/ 目录下
        $info = $file->move(ROOT_PATH . 'www' . DS . 'uploads');
        $return=['error'=>0];
        if($info){
            $return['file_path']= str_replace('\\', '/', $info->getSaveName());
            cookie('file_path',cookie('file_path').','.$return['file_path']);
            $return['msg']="上传成功";
        }else{
            // 上传失败获取错误信息
            $return['error']=1;
            $return['msg']="上传失败";
        }
        echo json_encode($return);
    }

    function choose()
    {
        $request=Request::instance();
        $topicId=$request->param("topicId");
        $this->assign("topicId",$topicId);
        return $this->fetch();
    }


}
