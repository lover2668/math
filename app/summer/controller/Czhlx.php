<?php
namespace app\summer\controller;

use service\services\BaseQuestionV2Service;
use service\services\KnowledgeV2Service;
use service\services\summer\SummerCindexService;
use service\services\summer\SummerQuestionService;
use service\services\summer\SummerUserService;
use service\services\TopicV2Service;
use Symfony\Component\PropertyAccess\StringUtil;
use think\Request;

use  service\services\QuestionService;

use  service\services\AlgoService;

use  service\lib\RabbitClientService;

use  service\algo\AlgoLogic;

use service\services\UserService;

use service\services\ZhlxService;
use service\services\KnowledgeService;
use service\services\TopicService;
use service\services\ApiGateService;
use app\index\controller\Base;
//error_reporting(0);
class Czhlx extends Base
{
    function index()
    {
        $topicId = input('topicId');
        $this->assign('topicId',$topicId );

        $getZhlxQuestionIds =array();
        $topic_v2_service = new  TopicV2Service();
        $zhlx_kmap_code_list = $topic_v2_service->getZhlxKmapCodeList($topicId);

        $question_v2_service = new BaseQuestionV2Service();
        $zonghe_module_type = config('zonghe_module_type');
        foreach ($zhlx_kmap_code_list as $k=>$v) {
            $getZhlxQuestionIds[] =  $question_v2_service->getQuestionsByKnowledge($v['tag_code'],$zonghe_module_type);
        }

        if($getZhlxQuestionIds==false)return $this->error("题库没topicId为".input('topicId', '').'的试题内容');

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
    public function zhlxQuestion()
    {
        $request = Request::instance();
        $topicId = $request->param('topicId');
        
//        $question_service=new QuestionService();
//        $question_v2_service  = new BaseQuestionV2Service();
//        $getZhlxQuestionIds=$question_v2_service->getZhlxQuestionIds($topicId);

        $getZhlxQuestionIds =array();
        $topic_v2_service = new  TopicV2Service();
        $zhlx_kmap_code_list = $topic_v2_service->getZhlxKmapCodeList($topicId);

        $zonghe_module_type = config('zonghe_module_type');
        $question_v2_service = new BaseQuestionV2Service();
        foreach ($zhlx_kmap_code_list as $k=>$v) {
            $getZhlxQuestionIds[] =  $question_v2_service->getQuestionsByKnowledge($v['tag_code'],$zonghe_module_type);
        }

        if($getZhlxQuestionIds==false)return $this->error("题库没topicId为".$topicId.'的试题内容');
        $topicId = $request->param('topicId');
        $this->assign("topicId", $topicId);
        $topic_v2_service = new TopicV2Service();

        $topic_list = $topic_v2_service->getTopicByTopicId(input('topicId',1));
        $this->assign("topic_name", $topic_list['topic_name']);
        return $this->fetch("zhlxQuestion");
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
        $module_type = config('zonghe_module_type');
        $jingsai_module_type = config('l2_jingsai_module_type');
        $kmap_code = config('kmap_code');  //知识图谱
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
        $question_v2_service = new BaseQuestionV2Service();
        $knowledge_v2_service = new KnowledgeV2Service();
        $knowledgeList = $knowledge_v2_service->getKnowledgeListByTopicId($topicId);

        $weakElements = $algoLogic->getL2WeakElements("",$topicId);
        $total_knowledge_num = count($knowledgeList);  //总知识点数量.
        $weakElements_num =  count($weakElements); //薄弱知识点数量.
        $has_learned_num = $total_knowledge_num-$weakElements_num;  //已学会知识点
        $summer_question_service  = new SummerQuestionService();

        $getZhlxNextQuestion=$summer_question_service->getZhlxNextQuestion($jingsai_module_type, 2, $topicId);

        $tag_code=$getZhlxNextQuestion['tag_code'];
        if($getZhlxNextQuestion['question_id']==false){
            $num=10;//答题结束
        }else{

//            $getZhlxNextQuestion['question_id'] = "58d2315cf4aeb57fc94f4b93";
            $question_list =$question_v2_service->getQuestionById($getZhlxNextQuestion['question_id'],$topicId);
            $question_service->insertUserExamActionLog('', $topicId, $jingsai_module_type, $getZhlxNextQuestion['question_id'], $question_list, $tag_code);
        }
        $user_service = new UserService();
        $has_answered_questions= $user_service->getUserHasAnsweredQuestionsByModuleType("",$topicId,$jingsai_module_type);
        if (!empty($num) && $num >= 10) {
            $return_array = array(
                "question_list" => "",
                "tag_code_timeline" => "",
                "tag_code" => "",
                "has_answered_questions"=>$has_answered_questions,
                "is_end" => 1
            );
            $has_learned_all_tag_code=1;
            $SteplogService_id=$user_service->insertUserStep($topicId, $jingsai_module_type,$has_learned_all_tag_code,1);
        } else {
            $return_array = array(
                "question_list" => $question_list,
                "tag_code_timeline" => $tag_code_timeline,
                "tag_code" => $tag_code,
                "has_answered_questions"=>$has_answered_questions,
                "is_end" => 0
            );
            
        }
        if(isset($tag_code)&&$tag_code){
            $knowledge_v2_service = new  KnowledgeV2Service();
            $tag_namearr = $knowledge_v2_service->getKnowledgeByCode($tag_code, $topicId);
            $return_array['tag_name'] = $tag_namearr['tag_name'];
        }
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
        $tag_code = 'zhlx';
        $jingsai_module_type = config('l2_jingsai_module_type');

        $used_type = 2;   //1 表示测试题,  2 表示练习题
        $question_service = new QuestionService();
        $isSuccess = $question_service->submitSpingQuestion($topicId, $answer_content, $jingsai_module_type, $tag_code, $used_type);
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
    public function zhlxReport()
    {
        $request = Request::instance();
        $topicId = $request->param('topicId');

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

        $algoLogic = new AlgoLogic();
        $user_service = new UserService();

        $knowledge_v2_service = new KnowledgeV2Service();
        $knowledgeList = $knowledge_v2_service->getKnowledgeListByTopicId($topicId);
        $kmap_code = config("kmap_code");
        $module_type = config('zonghe_module_type');
        $jingsai_module_type = config('l2_jingsai_module_type');

//        $weakElements = $algoLogic->getL2WeakElements("",$topicId);
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
//        $total_knowledge_num = count($knowledgeList);  //总知识点数量.
//        $weakElements_num =  count($weakElements); //薄弱知识点数量.
//        $has_learned_num = $total_knowledge_num-$weakElements_num;  //已学会知识点
//        $zhlx_service = new ZhlxService();
//        $tag_info =  $zhlx_service->getL2UserZhlxTagReport("",$topicId);
//        
//
//        //或得此专题所有用户的平均攻克能力值.
//        $average_num = $zhlx_service->getL2ZhlxAverageNum("",$topicId,$jingsai_module_type);
//        if($has_learned_num>$average_num)
//        {
//            $result = 1;
//        }else{
//            $result = 0;
//        }
//        $bxbl_zhlx_Learned_nums=$user_service->getL2Learned_nums($this->getUserId(), $topicId,$knowledgeList);//获取综合练习和边学边练攻克值>=0.8
//        //获取用户所有知识点的平均掌握情况.
//        $average_ability =  $user_service->getUserAverageAbility("",$topicId,$jingsai_module_type);
        //用户做过的试题信息.
        $has_answered_questions= $user_service->getUserHasAnsweredQuestionsByModule("",$topicId,$jingsai_module_type);

        $summer_user_service = new SummerUserService();
        $user_hasAnswered_question =  $summer_user_service->getUserHasAnsweredQuestionsByModuleType("",$topicId,$jingsai_module_type);
        $zongtishuliang = count($user_hasAnswered_question);
        $user_hasAnswered_all_right_question =  $summer_user_service->getUserAnsweredAllRightQuestionsByModuleType("",$topicId,$jingsai_module_type);
        $daduitishuliang = count($user_hasAnswered_all_right_question);
        if($zongtishuliang){
            $daduibi=ceil($daduitishuliang/$zongtishuliang*100);
        } else {
            $daduibi=0;
        }
        


        $this->assign('is_show_report',$is_show_report);
        $this->assign('is_show_nextstep',$is_show_nextstep);

        $this->assign("zongtishuliang",$zongtishuliang);
        $this->assign("daduitishuliang",$daduitishuliang);
        $this->assign("daduibi",$daduibi);
//        $zhlx=$user_service->getL2ZhlxUserChartData("",$topicId);
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
        $topicService=new TopicService();
        $topic=$topicService->getTopicByTopicId($topicId);
        $topicNmae=$topic["topic_name"];
        $this->assign("topic_name", $topicNmae);
        $api_gate_service = new ApiGateService();
        $getZhlxQuestionIds = $api_gate_service->getZhlxQuestionIds($topicId);//获取当前知识点下有没有做错的 如果有就是继续做



        $this->assign("getZhlxQuestionIds", $getZhlxQuestionIds);
        
        $xxcsIsAllRight = $user_service->getUserIsAllRight($topicId, $jingsai_module_type, $this->getUserId());
        if(count($has_answered_questions)==0){
            $this->assign("has_learned_percent",  0);
        }else{
            $this->assign("has_learned_percent",  ceil($xxcsIsAllRight/count($has_answered_questions)*100));
        }
        $this->assign("xiance_count",  count($user_service->getUserHasAnsweredQuestionsByModule("", $topicId, config('l2_xiance_module_type'))));
        
//        //判断现行测试边学边练和竞赛扩展是否做完
//        $user_id= $this->getUserId();
//        $userService=new UserService();
//         $xianceStep=$userService->getUserStep($topicId,$user_id,config('l2_xiance_module_type'));//边学边练
//         $xiance_is_end=0;
//        $this->assign('xiance_is_end',0);
//        if(isset($xianceStep['is_end'])&&$xianceStep['is_end']==1)$xiance_is_end=1;
//        $this->assign('xiance_is_end',$xiance_is_end);
//        $bxblStep=$userService->getUserStep($topicId,$user_id,config('l2_bxbl_module_type'));//边学边练
//        $bxbl_is_end=0;
//        if(isset($bxblStep['is_end'])&&$bxblStep['is_end']==1)$bxbl_is_end=1;;
//        $this->assign('bxbl_is_end',$bxbl_is_end);
//        $zhlxStep=$userService->getUserStep($topicId,$user_id,config('l2_jingsai_module_type'));//综合练习
//        $zhlx_is_end=0;
//        if(isset($zhlxStep['is_end'])&&$zhlxStep['is_end']==1)$zhlx_is_end=1;
//        $this->assign('zhlx_is_end',$zhlx_is_end);
        return $this->fetch("zhlxReport");

    }

    /**
     * 获取堂堂清回答错误的问题
     * @return mixed
     */
    public function getTTQError()
    {
        $topicid=input("topicId");
        $questionService=new QuestionService();
        $result=$questionService->getTTQError($topicid);
        $return_arr=array();
        $answeredInfo=$result["data"];
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


        $this->assign("list",$return_arr);
        $this->assign("page",$result["page"]);

       return $this->fetch("getTTQError");
    }
    public function getKnowledgeByCode($tag_code,$topicId)
    {
        $knowledgeService=new KnowledgeService();
        $tag=$knowledgeService->getKnowledgeByCode($tag_code,$topicId);
        echo json_encode($tag);
    }

}
