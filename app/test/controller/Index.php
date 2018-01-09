<?php

namespace app\test\controller;
use service\services\TopicService;
use service\algo\AlgoLogic;
use service\services\QuestionService;
//ini_set("max_execution_time",120);

/**
 * Description of Index
 *
 * @author zhangqiquan
 */
class Index {
    public function xiance(){
        //修改最大执行时间
        ini_set('max_execution_time', 0);
//修改此次最大运行内存
        ini_set('memory_limit','128M');
        $topicService=new TopicService();
        $topicList=$topicService->getTopicList();

        echo "<pre>";
        $level_mode = config("level_mode");
        $initKStatus=3;//默认传3
        $last_answer_is_right=0;
        $algoLogic = new AlgoLogic();
        $question_service = new QuestionService();
        $module_type = config('xiance_module_type');
        $used_type = config('test_question');
        $tag_code_arr = array();
     //   session('tag_code_arr',null);
        $code_arr = session('tag_code_arr');

 //       var_dump($code_arr);
   //     exit;
        if(empty($code_arr))
        {
            session('tag_code_arr','');

            foreach($topicList as $k=>$v){
                $tag_code_arr=$this->topicList($v['tid'],$tag_code_arr);
            }
            session('tag_code_arr',$tag_code_arr);
            $code_arr = $tag_code_arr;
        }else{
//            echo "2222";
        }
        foreach ($code_arr as $key=>$tag_code)
        {
       //     echo "------key-----".$key."----<br>";
            $question_service = new QuestionService();

            $module = 1 ;
            $used_type = 1;
            $question_arr = $question_service->getQuestionsByKnowledge($tag_code, $module, $used_type);
            if(empty($question_arr))
            {
                echo   "知识点:----".$tag_code."----在先行测试中,used_type=1的情况下,没有试题<br>";
            }
            $module = 2 ;
            $used_type = 2;
            $question_arr_2 = $question_service->getQuestionsByKnowledge($tag_code, $module, $used_type);
            if(empty($question_arr_2))
            {
                echo   "知识点:----".$tag_code."----在边学边练中,used_type=2的情况下,没有试题<br>";
            }
        }

    }
    public function topicList($tid,$tag_code_arr){
        $data=[];
        $url = config("question_server_host"). "index/api/getTopicByTopicId";
        $return_data = rpc_request($url, ['tid'=>$tid]);
        foreach($return_data['knowledge_map'] as $k=>$v){
//            echo '通过列表获取tag_code...'.$tid.'===='.$v['tag_code'].'&&&&&<br />';
//            $data[]=$v['tag_code'];
            $tag_code_arr[] = $v['tag_code'];
        }
        return $tag_code_arr;
    }
    public function bIndex(){
        $topicService=new TopicService();
        $topicList=$topicService->getTopicList();
        echo "<pre>";
        $level_mode = config("level_mode");
        $initKStatus=3;//默认传3
        $last_answer_is_right=0;
        $algoLogic = new AlgoLogic();
        $question_service = new QuestionService();
        $module_type = config('xiance_module_type');
        $used_type = config('test_question');
        foreach($topicList as $k=>$v){
            echo $v['tid'].'.........<br />';
            $kmap_code = $topicService->getKmapCodeByTopicId($v['tid']);
            $return_tag_code = $algoLogic->get_xiance_tagCode($v['tid'], $initKStatus, $kmap_code, $level_mode, $last_answer_is_right);
            $question_list = $question_service->getXianceNextQuestion($v['tid'], $return_tag_code['tag_code'], $module_type, $used_type);
            echo "=====题目内容=====";
            print_r($question_list);
            if($question_list==false) echo "没有题目";
            echo "=========<br />";
        }
    }
    
    public function bxbl(){
        $topicService=new TopicService();
        $topicList=$topicService->getTopicList();
        echo "<pre>";
        $level_mode = config("level_mode");
        $initKStatus=3;//默认传3
        $last_answer_is_right=0;
        $algoLogic = new AlgoLogic();
        $question_service = new QuestionService();
        $module_type = config('xiance_module_type');
        $used_type = config('test_question');
        foreach($topicList as $k=>$v){
            $topicList=$this->topicList($v['tid']);
            foreach($topicList as $k1=>$v1){
                echo "=====题目内容=====";
                echo "tid=".$v['tid'].'   tag_code='.$v1;
                $question_list = $question_service->getXianceNextQuestion($v['tid'], $v1, $module_type, $used_type);
                print_r($question_list);        
            }
            echo "=========<br />";
        }
    }
}
