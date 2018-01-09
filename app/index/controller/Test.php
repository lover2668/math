<?php
namespace app\index\controller;
use service\services\QuestionService;
use think\Db;
use service\services\CommonService;
use think\Cache;
use think\Request;
use service\services\TopicService;
use service\log\logService;
use service\lib\RabbitClientService;
use service\services\PathManageService;
use service\services\ApiGateService;
use service\services\TopicV2Service;
use service\services\BaseQuestionV2Service;
use service\lib\xhprof\xhprof;

class Test extends \think\Controller{
    private static $question_server_host;

    public function __construct()
    {
        parent::__construct();
        self::$question_server_host = config("question_server_host");
        session('');
    }
    public function query_check_question() {
        $request = Request::instance();
        $question_id = $request->param("question_id");
        if(!$question_id){
            return "question_id 参数缺失";
        }
        $param["question_id"] = $question_id;
        $url_171 = config("171_url")."/index/test/check_question";
        $return_data_171 = rpc_request($url_171, $param);
        $url_demo = config("demo_url")."/index/test/check_question";
        $return_data_demo = rpc_request($url_demo, $param);
        if ($return_data_demo["is_exist"] == 1 || $return_data_171["is_exist"] == 1) {
            return "true";
        } elseif ($return_data_demo["is_exist"] === 0 && $return_data_171["is_exist"] === 0) {
             return "false";
        } 
    }

    public function check_question() {
        $return_data = [
            "is_exist" => "",
        ];
        $request = Request::instance();
        $question_id = $request->param("question_id");
        if ($question_id) {
            $where['question_id'] = $question_id;
            $is_exist = Db::name('user_exam_detail')->where($where)->find();
            if (is_array($is_exist) && count($is_exist)) {
                $return_data["is_exist"] = 1;
            } else {
                $return_data["is_exist"] = 0;
            }
        }
        return json_encode($return_data);
    }



    public function getQuestionByIdAnalyse1($question_id){
        $QuestionService=new \service\services\QuestionService();
        $return_data = $QuestionService->getQuestionById($question_id);
        $mag='';
        if($return_data==false){
            $mag.="错误==接口返回数据为null<br />";
        }
        if(isset($return_data['id'])==false||$return_data['id']==false){
            $mag.="错误==题目id为null<br />";
        }
        if(isset($return_data['content'])==false||$return_data['content']==false){
            $mag.="错误==题目为null<br />";
        }
        if(isset($return_data['q_type'])==false||$return_data['q_type']==false){
            $mag.="错误==题目类型null<br />";
        }
        if(isset($return_data['content'])&&$return_data['q_type']==2&&!is_numeric(strpos(htmlspecialchars_decode($return_data['content']),'##$$##'))){
            $mag.="错误==填空题题目中没有包含填空符号 ##$$##<br />";
        }
        if(isset($return_data['content'])&&$return_data['q_type']==1){
            //&& (count($return_data['answer'])==0||$return_data['answer'][0][0]==false)
            $mag.="错误==选择题目没有选项<br />";
        }

        if(isset($return_data['answer'])&& (count($return_data['answer'])==0)){
            $mag.="错误==题目没有正确答案<br />";
        }
        if(isset($return_data['analyze'])==false||$return_data['analyze']==false)$mag.="分布解析为null<br />";
        if(isset($return_data['analyze'])&& count($return_data['analyze'])<=0){
            $mag.="错误==分布解析数据类型为空<br />";
        }
        $data=[
            'data'=>$return_data,
            'msg'=>$mag
        ];
        return $data;
        //return $this->fetch('getquestionbyidanalyse',$data);
    }

    /**
     * 错误列表
     */
  /**
     * 错误列表
     */
    public function errList(){
        $obj=Db::name('user_questions_request_log')->order('id desc')->paginate(60,false, input());
        $data=[
            'list'=>$obj->toarray(),
            'page'=>$obj->render()
        ];
        return $this->fetch('',$data);
    }

    public function index(){
        return $this->fetch();
    }
    /**
     * 清空缓存 
     */
    public function cacheClear(){
        echo Cache::clear();
    }
    public function getQuestionByIdAnalyse($question_id){
        $QuestionService=new \service\services\QuestionService();
        $return_data = $QuestionService->getQuestionById($question_id);
        $mag='';
        if($return_data==false){
            $mag.="错误==接口返回数据为null<br />";
        }
        if(isset($return_data['id'])==false||$return_data['id']==false){
            $mag.="错误==题目id为null<br />";
        }
        if(isset($return_data['content'])==false||$return_data['content']==false){
            $mag.="错误==题目为null<br />";
        }
        if(isset($return_data['q_type'])==false||$return_data['q_type']==false){
            $mag.="错误==题目类型null<br />";
        }
        if(isset($return_data['content'])&&$return_data['q_type']==2&&!is_numeric(strpos(htmlspecialchars_decode($return_data['content']),'##$$##'))){
            $mag.="错误==填空题题目中没有包含填空符号 ##$$##<br />";
        }
        if(isset($return_data['content'])&&$return_data['q_type']==1){
            //&& (count($return_data['answer'])==0||$return_data['answer'][0][0]==false)
            $mag.="错误==选择题目没有选项<br />";
        }

        if(isset($return_data['answer'])&& (count($return_data['answer'])==0)){
            $mag.="错误==题目没有正确答案<br />";
        }
        if(isset($return_data['analyze'])==false||$return_data['analyze']==false)$mag.="分布解析为null<br />";
        if(isset($return_data['analyze'])&& count($return_data['analyze'])<=0){
            $mag.="错误==分布解析数据类型为空<br />";
        }
        $data=[
            'data'=>$return_data,
            'msg'=>$mag
        ];
        return $this->fetch('',$data);
    }

    /**
     * 根据试题ID获取试题
     * @param type $question_id
     */
    public function getQuestionById($question_id){
        $QuestionService=new \service\services\QuestionService();
        $return_data = $QuestionService->getQuestionById($question_id);
        var_dump($return_data);
    }
    /**
     * 根据知识点获取试题
     * @param type $knowledge
     * @param type $module
     * @param type $used_type
     * @return type
     */
    public function getQuestionsByKnowledge($knowledge, $module, $used_type)
    {
        $key = "questionsByKnowledge:" . $knowledge . ":" . $module . ":" . $used_type;
        $param['knowledge'] = $knowledge;
        $param['module'] = $module;
        $param['used_type'] = $used_type;
        //根据知识点获取试题.
        $url = self::$question_server_host . "api/index/getQuestionsByKnowledge";
        $return_data = rpc_request($url, $param);
        var_dump($return_data);
    }
    /**
     * 获取用户已经做过的试题.
     * @param type $user_id
     * @param type $topicId
     * @param type $module_type
     * @param type $submodule_type
     * @return type
     */
    public function getUserHasAnsweredQuestions($user_id, $topicId, $module_type, $submodule_type = 1)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $userAnsweredQuestions = Db::name('user_exam_detail')->where($where)->select();
        $userAnsweredQuestionIds = array();
        foreach ($userAnsweredQuestions as $userAnsweredQuestion) {
            $userAnsweredQuestionIds[] = $userAnsweredQuestion['question_id'];
        }
        var_dump($userAnsweredQuestionIds);
    }
    /**
     * 获取知识图谱信息
     * @return type
     */
    public function getKnowledgeList()
    {

        $key = "knowledgeList";
        $url = self::$question_server_host . "api/index/getKnowledgeList";
        $return_data = rpc_request($url, array());
        var_dump($return_data);
    }
    /**
     * 获取知识相关信息
     * @param type $knowledge_map
     * @param type $tag_code
     * @return type
     */
    public function getKnowlegeCode($knowledge_map, $tag_code)
    {
        $cacheKey = $knowledge_map . ":" . $tag_code;
        $url = self::$question_server_host . "api/index/getKnowledgeList";
        $result = rpc_request($url, array());
        $retunData='';
        foreach ($result as $key => $item) {
            foreach ($item as $child) {
                $itemKey = $key . ":" . $child["code"];
                $cacheData = json_encode($child);
                Cache::set($itemKey, $cacheData, 3600 * 24);
            }
            $retunData = Cache::get($cacheKey);
        }
        if (!is_array($retunData)) $retunData = json_decode($retunData, true);
        var_dump($retunData);
    }
    /**
     * 根据模块获取试题信息.
     * @param type $module_id
     * @param type $tag_code
     * @return type
     */
    public function getQuestionIdsByModule($module_id,$tag_code)
    {
        $key = "questionIdsByModule:" . $module_id;
        $param['module_id'] = $module_id;
        $param['tag_code'] = $tag_code;
        //根据知识点获取试题.
        $url = self::$question_server_host . "api/index/getQuestionIdsByModule";
        $return_data = rpc_request($url, $param);
        var_dump($return_data);
    }
    /**
     * 根据难度的获取对应模块的试题.
     * @param type $module_id
     * @param type $difficulty_arr
     * @param type $tag_code
     * @return type
     */
    public function getQuestionsByAttribute($module_id, $difficulty_arr,$tag_code)
    {
        $param['module_id'] = $module_id;
        $param['tag_code'] = $tag_code;
        $param['difficulty'] = json_encode($difficulty_arr);
        $url = self::$question_server_host . "api/index/getQuestionsByAttribute";
        $return_data = rpc_request($url, $param);
        var_dump($return_data);
    }



    public function  getTopicByTopicId()
    {
        $topicId = 1;
        $topic_service = new TopicService();
        $topic_list = $topic_service->getTopicByTopicId($topicId);
        dump($topic_list);
    }

    public function sendMessage()
    {
        $rabbit_server = new  RabbitClientService();
        $topic= "info";
        $message  = "ni嘿嘿和嘿嘿fasdfasdfsdf";
        $rabbit_server->publish($topic,$message);
        echo "11122333";
    }

    public function sendAlgoMsg()
    {
        $log_service = new  LogService("algo");
        $topic= "info";
        $message  = "-----algo--------";
        $message  = array(
            'key'=>"algo"
        );
        $log_service::sendMessage($topic,$message,"kstmode");
    }


    public function sendTichiMsg()
    {
        $log_service = new  LogService("tichi");
        $topic= "info";
        $message  = array(
            'key'=>"base"
        );
        $log_service::sendMessage($topic,$message,"getQuestionById");
    }

    public function sendBaseMsg()
    {
        $log_service = new  LogService();
        $topic= "info";
        $message  = "------base--------";
        $log_service::sendMessage($topic,$message);

    }

    function test()
    {
        $message = array(
            'topicId' => 1,    //专题ID
            'module_type' => 2,   //版块
            'kmap_code' => "",   //知识图谱编码
            'request_data' => "",   //请求数据
            'response_data' => "",  //响应数据
            'stime' => "",     // 接口开始时间
            'etime' => "",     // 接口结束时间
            'ctime' => ""      // 创建时间。
        );

        $log_service = new  LogService("algo");
        $topic= "info";
        $log_service::sendMessage($topic,$message,"kstmode");

    }
    public function getQuestionsByKnowledge1($tag_code, $module=1, $used_type=1){
        $return_data  =array();
        $param['knowledge'] = $tag_code;
        $param['module'] = $module;
        $param['used_type'] = $used_type;
        //根据知识点获取试题.
        $url = self::$question_server_host . "index/api/getQuestionsByKnowledge";

        $return_data = rpc_request($url, $param);
        print_r($return_data);
    }



    public function testQuestionId()
    {

        $request=Request::instance();
        $question_id=$request->param("question_id");
        $question_service = new QuestionService();
        $return_data =  $question_service->getQuestionById($question_id);

        $msg  = $question_service->checkQuestionInfo($question_id,$return_data);
        var_dump($msg);


    }


    public function testUser()
    {
//        $token = "ecyxJp1hapWQiOjI4NDM2LCJ0b3BpY19pZCI6OTQxNCwidmVyaWZ5IjoieXgiLCJ1bmFtZSI6Inl4NTIxMXN0dWRlbnQxMDAiLCJjb3Vyc2VfbmFtZSI6IjIwMTdcdTY2OTFcdTY3MWZcdTY1NzBcdTViNjZcdTViNjZcdTRlNjBcdWZmMDhcdTZkNGJcdThiZDVcdTc1MjhcdWZmMDkiLCJjb3Vyc2VfaWQiOiIyMDEiLCJzZWN0aW9uX2lkIjoiNzY0NyIsInNlY3Rpb25fbmFtZSI6Ilx1NGViYVx1NjU1OVx1NzI0OFx1NGUwM1x1NWU3NFx1N2VhN1x1NjY5MVx1NTA0N1x1N2IyYzFcdTZiMjFcdThiZmUiLCJyZWdpb25faWQiOjB9";

        $token="ecyxJp1hapWQiOjI4NzIxLCJ0b3BpY19pZCI6OTQxNCwidmVyaWZ5IjoieXgiLCJ1bmFtZSI6Inl4NTIxMXN0dWRlbnQzNzQiLCJjb3Vyc2VfbmFtZSI6IjIwMTdcdTY2OTFcdTY3MWZcdTY1NzBcdTViNjZcdWZmMDhcdTZkNGJcdThiZDVcdTdlYzRcdTRlMTNcdTc1Mjg5MDE2XHVmZjA5IiwiY291cnNlX2lkIjoiMjA0Iiwic2VjdGlvbl9pZCI6Ijc2NjQiLCJzZWN0aW9uX25hbWUiOiJcdTdiMmNcdTRlMDBcdTZiMjFcdThiZmUiLCJyZWdpb25faWQiOjB9";
        $user =  token_verify($token);

        var_dump($user);

    }


    public function testPath()
    {
        $topicId = 9016;
        $path_manager = new PathManageService();
        $return_data=  $path_manager->getUserSummerNextModule('',$topicId,"");
        $url = $return_data['url'];
        echo $url;
    }



    public function testZhlx()
    {
        $topicId =  input('topicId');
        $question_service=new QuestionService();
        $api_gate_service = new ApiGateService();
        $return_data = array();
        if($topicId>=9000)
        {
            $topic_v2_service = new  TopicV2Service();
            $zhlx_kmap_code_list = $topic_v2_service->getZhlxKmapCodeList($topicId);
            $question_v2_service = new BaseQuestionV2Service();
            $module_type = config('zonghe_module_type');
            foreach ($zhlx_kmap_code_list as $k=>$v) {
                $return_data[$v['tag_code']] =  $question_v2_service->getQuestionsByKnowledge($v['tag_code'],$module_type);
//                $return_data[]
            }

        }else{
            $question_service  = new QuestionService();
            $return_data = $question_service->getZhlxQuestionIds($topicId);
        }



        var_dump($return_data);




    }


    public function test2()
    {
        xhprof::s();
        dump(true);
        xhprof::e();
    }

    public function test4()
    {
        $token = "ecyxJp1hapWQiOjMzNTgwLCJ0b3BpY19pZCI6OTUzNCwidmVyaWZ5IjoieXgiLCJ1bmFtZSI6Inl4NTIxMXN0dWRlbnQxMjgxIiwiY291cnNlX25hbWUiOiJcdTUzNGVcdTVlMDhcdTU5MjdcdTcyNDhcdTRlMDNcdTVlNzRcdTdlYTdcdTc5Y2JcdTViNjNcdThiZmUiLCJjb3Vyc2VfaWQiOiIzMDkiLCJzZWN0aW9uX2lkIjoiOTc1NCIsInNlY3Rpb25fbmFtZSI6Ilx1N2IyY1x1NGUwMFx1NmIyMVx1OGJmZSIsInJlZ2lvbl9pZCI6NTIwMTAwLCJ0aW1lIjoxNTEwODM4NTAxfQO0O0OO0O0O";
        $user = token_verify($token);

        var_dump($user);exit;
    }


}
