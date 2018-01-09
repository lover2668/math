<?php
namespace app\index\controller;

use service\services\ApiGateService;
use think\Request;

use  service\services\QuestionService;

use  service\services\AlgoService;

use  service\lib\RabbitClientService;

use  service\algo\AlgoLogic;

use service\services\UserService;

use service\services\ZhlxService;
use service\services\KnowledgeService;
use service\services\TopicService;
use service\services\KnowledgeV2Service;
class Mncs extends Base
{
    
    function checkScale($topicId) {
        $module_type = config('bxbl_module_type');
        $user_service = new UserService();
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
            
        
        return $scale;
    }
    
    function index()
    {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $scale = $this->checkScale($topicId);
        $question_service=new QuestionService();
        $api_gate_service = new ApiGateService();
        $getZhlxQuestionIds=$api_gate_service->getZhlxQuestionIds($topicId);
        if($getZhlxQuestionIds==false)return $this->error("题库没topicId为".input('topicId', '').'的试题内容', url("bxbl/studyReport",['topicId'=>$topicId]));
        $this->assign('topicId', $topicId);

        return $this->fetch("index");
    }

//    public function index()
//    {
//        echo "fsdfsadfasdfs";
//    }

    /**
     * 答题页面
     * @return mixed
     */
    public function mncsQuestion()
    {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $api_gate_service = new ApiGateService();
        $getZhlxQuestionIds=$api_gate_service->getZhlxQuestionIds($topicId);
        if($getZhlxQuestionIds==false)return $this->error("题库没topicId为".$topicId.'的试题内容', url("bxbl/studyReport",['topicId'=>$topicId]));

        $this->assign("topicId", $topicId);
        $topic_service = new TopicService();
        $topic_list = $topic_service->getTopicByTopicId($topicId);
        $this->assign("topic_name", $topic_list['topic_name']);
        return $this->fetch("mncsQuestion");
    }

    /**
     * @api {get} /index/Zhlx/getExamQuestions   获取试题
     * @apiVersion 0.0.1
     * @apiName  getExamQuestions  获取试题
     * @apiGroup  Zhlx
     *
     * @apiParam {Number} topicId  用户topicId.
     * @apiSuccess {Number} is_end  是否进入堂堂清,0 ,不进入,1 进入..
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
    public function getExamQuestions()
    {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $module_type = config('mncs_module_type');
        $kmap_code = config('kmap_code');  //知识图谱
        $api_gate_service = new ApiGateService();
        $tag_code_timeline = array(
            array(
                'tag_code' => "zk_1",
                'img_url' => "http://f.hiphotos.baidu.com/image/h%3D360/sign=6b518201f8faaf519be387b9bc5494ed/738b4710b912c8fc6684dceaf9039245d68821a5.jpg"
            ),
            array(
                'tag_code' => "zk_2",
                'img_url' => "http://g.hiphotos.baidu.com/image/pic/item/30adcbef76094b362ae9c3e9a1cc7cd98d109dbf.jpg"
            ),
            array(
                'tag_code' => "zk_3",
                'img_url' => "http://g.hiphotos.baidu.com/image/pic/item/aa18972bd40735fa109f91bd9c510fb30f240843.jpg"
            )
        );
        $question_service=new QuestionService();
        $algoLogic = new AlgoLogic();
//        $all_knowledgeList = $question_service->getKnowledgeList();
//        $knowledgeList = $all_knowledgeList[$kmap_code];

        $api_gate_service = new ApiGateService();
        $knowledgeList = $api_gate_service->getKnowledgeListByTopicId($topicId);


        $weakElements = $algoLogic->getWeakElements("",$topicId);
        $total_knowledge_num = count($knowledgeList);  //总知识点数量.
        $weakElements_num =  count($weakElements); //薄弱知识点数量.
        $has_learned_num = $total_knowledge_num-$weakElements_num;  //已学会知识点

        $getZhlxNextQuestion=$question_service->getZhlxNextQuestion($module_type, 2, $topicId);
        $tag_code="mncs";
//        $tag_code=$getZhlxNextQuestion['tag_code'];
        if($getZhlxNextQuestion['question_id']==false){
            $num=10;//答题结束
        }else{

            $question_list =$api_gate_service->getQuestionById($getZhlxNextQuestion['question_id'],$topicId);
            $question_service->insertUserExamActionLog('', $topicId, $module_type, $getZhlxNextQuestion['question_id'], $question_list, $tag_code);
        }
        $user_service = new UserService();
        $has_answered_questions= $user_service->getUserHasAnsweredQuestionsByModuleType("",$topicId,$module_type);
        if (!empty($num) && $num >= 10) {
            $return_array = array(
                "question_list" => "",
                "tag_code_timeline" => "",
                "tag_code" => "",
                "has_answered_questions"=>$has_answered_questions,
                "is_end" => 1
            );
            $has_learned_all_tag_code=1;
            $SteplogService_id=$user_service->insertUserStep($topicId, $module_type,$has_learned_all_tag_code,1);
        } else {
            $return_array = array(
                "question_list" => $question_list,
                "tag_code_timeline" => $tag_code_timeline,
                "tag_code" => $tag_code,
                "has_answered_questions"=>$has_answered_questions,
                "is_end" => 0
            );
            
        }
//        if(isset($tag_code)&&$tag_code){
//            $knowledge_v2_service = new  KnowledgeV2Service();
//            $tag_namearr = $knowledge_v2_service->getKnowledgeByCode($tag_code, $topicId);
//            $return_array['tag_name'] = $tag_namearr['tag_name'];
            $return_array['tag_name']="竞赛拓展";

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
     * @api {post} index/Zhlx/submitQuestion  试题提交
     * @apiVersion 0.0.1
     * @apiName  submitQuestion  试题提交
     * @apiGroup Zhlx
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
        $topicId = $request->param('topicId');
        $answer_content = input("answer_content/a");

//        $answer_content = array(
//            array(
//                'question_id' => 12,
//                'type' => 1,
//                'answer' => "A"
//            ),
//            array(
//                'question_id' => 13,
//                'type' => 2,
//                'answer' => "B"
//            )
//        );
        //$tag_code = session('tag_code');
        $tag_code = 'mncs';
        $module_type = config('mncs_module_type');

        $used_type = 2;   //1 表示测试题,  2 表示练习题
        $question_service = new questionService();
        $isSuccess = $question_service->submitQuestion($topicId, $answer_content, $module_type, $tag_code, $used_type);
        echo json_encode($isSuccess);
//        $userInfo = session('userInfo');
//        $user_id = $userInfo['user_id'];
//        $ttq_session_key = $user_id . "zhlx_num";
//        $num = session($ttq_session_key);
//        if (empty($num)) {
//            session($ttq_session_key, 1);
//        } else {
//            session($ttq_session_key, $num + 1);
//        }
//        $return_data = array(
//            'isSuccess' => 1,
//            'error' => ""
//        );
//        echo json_encode($return_data);
    }

    /**
     * 报告页
     * @return mixed
     */
    public function mncsReport()
    {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        $algoLogic = new AlgoLogic();
        $user_service = new UserService();
        $question_service =  new QuestionService();
//        $all_knowledgeList = $question_service->getKnowledgeList();
        $kmap_code = config("kmap_code");
        $module_type = config('mncs_module_type');
//        $knowledgeList = $all_knowledgeList[$kmap_code];

        
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
        //用户做过的试题信息.
        $has_answered_questions= $user_service->getUserHasAnsweredQuestionsByModule("",$topicId,$module_type);
        $api_gate_service = new ApiGateService();
        $getZhlxQuestionIds = $api_gate_service->getZhlxQuestionIds($topicId);//获取当前知识点下有没有做错的 如果有就是继续做

        $topicService=new TopicService();
        $topic=$topicService->getTopicByTopicId($topicId);
        $topicNmae=$topic["topic_name"];

        $zongtishuliang=$user_service->getUserHasAnsweredQuestionsByModuleType("", $topicId, $module_type);
        $daduitishuliang=$user_service->getUserAnsweredAllRightQuestionsByModuleType("", $topicId, $module_type);
       
        if(count($zongtishuliang)){
            $daduibi=ceil((count($daduitishuliang)/ count($zongtishuliang))*100);
        } else {
            $daduibi = 0;
        }
        $this->assign("zongtishuliang", count($zongtishuliang));
        $this->assign("daduitishuliang", count($daduitishuliang));
        $this->assign("daduibi",  $daduibi);

        $this->assign("has_answered_questions",$has_answered_questions);
        $this->assign("topicId", $topicId);
        $this->assign("topic_name", $topicNmae);
        $this->assign("getZhlxQuestionIds", $getZhlxQuestionIds);
        $this->assign("is_show_report", $is_show_report);
        $this->assign("is_show_nextstep", $is_show_nextstep);
        
        $xxcsIsAllRight = $user_service->getUserIsAllRight($topicId, $module_type, $this->getUserId());
        if(count($has_answered_questions)==0){
            $this->assign("has_learned_percent",  0);
        }else{
            $this->assign("has_learned_percent",  ceil($xxcsIsAllRight/count($has_answered_questions)*100));
        }
        $this->assign("xiance_count",  count($user_service->getUserHasAnsweredQuestionsByModule("", $topicId, config('xiance_module_type'))));

        return $this->fetch("mncsReport");

    }


    public function getKnowledgeByCode($tag_code,$topicId)
    {
        $knowledgeService=new KnowledgeService();
        $tag=$knowledgeService->getKnowledgeByCode($tag_code,$topicId);
        echo json_encode($tag);
    }

}
