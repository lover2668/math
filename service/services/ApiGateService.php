<?php
namespace service\services;

use service\org\util\TpString;
use service\algo\AlgoLogic;
use  think\Db;
use  think\Log;
use think\Cache;
use service\log\LogService;
use service\lib\RabbitClientService;

class ApiGateService extends CommonService
{
    protected static $question_server_host;
    public function __construct()
    {
        //初始化并执行curl请求
        self::$question_server_host = config("question_server_host");
    }


    public function getQuestionById($question_id,$topicId)
    {
//        if($topicId>=9000)
//        {
            $question_v2_service =new BaseQuestionV2Service();
            $return_data = $question_v2_service->getQuestionById($question_id);
//        }else{
//            $question_service  = new QuestionService();
//            $return_data = $question_service->getQuestionById($question_id);
//        }
        return $return_data;
    }







    public function getQuestionsByKnowledge($knowledge, $module, $used_type,$topicId)
    {
//        if($topicId>=9000)
//        {
            $question_v2_service =new BaseQuestionV2Service();
            $return_data = $question_v2_service->getQuestionsByKnowledge($knowledge,$module,$used_type,"");
//        }else{
//            $question_service  = new QuestionService();
//            $return_data = $question_service->getQuestionsByKnowledge($knowledge, $module, $used_type);
//        }
        return $return_data;
    }



    public function getKnowledgeList()
    {
        $start_time=microtime(true);
        $key = "knowledgeList";
        $return_data = Cache::get($key);
        if (!$return_data) {
            $url = self::$question_server_host . "index/api/getKnowledgeList";
            $return_data = rpc_request($url, array());

            /*****日志埋点****/
            $log_service = new  logService("tichi");
            $topic= "info";
            $msg = array(
                'request_api'=>"getKnowledgeList",
                'user_id'=> $this->getUserId(),
                'request_data'=>array(),   //请求数据,
                'response_data'=>$return_data,  //响应数据
                'stime'=> $start_time,     // 接口开始时间
                'etime'=>microtime(true),     // 接口结束时间
                'ctime'=> time()      // 创建时间。
            );
            $log_service::sendMessage($topic,$msg,'getKnowledgeList');
            /*****日志埋点****/
            if(empty($return_data))
            {
                $log_service = new  logService();
                $log_service::sendMessage("error",__METHOD__."题库接口getKnowledgeList-----返回值为空,");
            }

            Cache::set($key, $return_data, 3600 * 24);
        }
        return $return_data;
    }

    /**
     * 获取知识相关信息
     * @param $knowledge_map 知识图谱
     * @param $tag_code 知识点code码
     *
     * @return mixed
     */
    public function getKnowlegeCode($knowledge_map, $tag_code,$topicId)
    {

//        if($topicId>=9000)
//        {
            $question_v2_service =new BaseQuestionV2Service();
            $return_data = $question_v2_service->getKnowledgeByCode($tag_code);
//        }else{
//            $question_service  = new QuestionService();
//            $return_data = $question_service->getKnowlegeCode($knowledge_map, $tag_code);
//        }

        return $return_data;
    }






    public function getQuestionIdsByModule($module,$used_type,$knowledge,$topicId)
    {
//        if($topicId>=9000)
//        {
            $question_v2_service =new BaseQuestionV2Service();
            $return_data = $question_v2_service->getQuestionIdsByModule($module,$used_type,$knowledge);
//        }else{
//            $question_service  = new QuestionService();
//            $return_data = $question_service->getQuestionIdsByModule($module,$used_type,$knowledge);
//        }
        return $return_data;
    }



    public function getZhlxQuestionIds($topicId)
    {
        $return_data = array();
//        if($topicId>=9000)
//        {
            $topic_v2_service = new  TopicV2Service();
            $zhlx_kmap_code_list = $topic_v2_service->getZhlxKmapCodeList($topicId);
            $question_v2_service = new BaseQuestionV2Service();
            $module_type = config('zonghe_module_type');
            foreach ($zhlx_kmap_code_list as $k=>$v) {
                $return_data[] =  $question_v2_service->getQuestionsByKnowledge($v['tag_code'],$module_type);
            }

//        }else{
//            $question_service  = new QuestionService();
//            $return_data = $question_service->getZhlxQuestionIds($topicId);
//        }


        return $return_data;
    }




    public function getTestQuestionsList()
    {

        $start_time=microtime(true);
        $key = "testQuestionsList";
        $return_data = Cache::get($key);
        if (!$return_data) {
            $param = array();
            //根据知识点获取试题.
            $url = self::$question_server_host . "index/api/questionIdList";
            $return_data = rpc_request($url, $param);

            /*****日志埋点****/
            $log_service = new  logService("tichi");
            $topic= "info";
            $msg = array(
                'request_api'=>"questionIdList",
                'user_id'=> $this->getUserId(),
                'request_data'=>$param,   //请求数据,
                'response_data'=>$return_data,  //响应数据
                'stime'=> $start_time,     // 接口开始时间
                'etime'=>microtime(true),     // 接口结束时间
                'ctime'=> time()      // 创建时间。
            );
//            $log_service::sendMessage($topic,$msg,'questionIdList');
            /*****日志埋点****/
//            if(empty($return_data))
//            {
//                $log_service = new  logService();
//                $log_service::sendMessage("error",__METHOD__."questionIdList----返回值为空, 传参数为:".json_encode($param));
//            }

            Cache::set($key, $return_data);
        }
        return $return_data;
    }



    public function getKnowledgeListByTopicId($topicId)
    {
//        if($topicId>=9000)
//        {
            $topic_service =  new TopicService();
            $kmap_code = $topic_service->getKmapCodeByTopicId($topicId);
           
            $topic_v2_service = new TopicV2Service();
            $knowledgeList =  $topic_v2_service->getKmapInfoByKmapCode($kmap_code);
//        }else{
//            $knowledge_service = new KnowledgeService();
//            $knowledgeList =  $knowledge_service->getKnowledgeListByTopicId($topicId);
//        }
        return $knowledgeList;
    }



}