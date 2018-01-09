<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/12/21
 * Time: 下午7:29
 */

namespace service\services;

use service\org\util\TpString;
use service\algo\AlgoLogic;
use  think\Db;
use  think\Log;
use think\Cache;
use service\log\LogService;
use service\services\TopicService;


class KnowledgeService
{

    /**
     * 根据知识图谱的Kmap_code获取知识图谱的知识点信息。
     * @param $kmap_code
     */
    public function  getKnowledgeListByTopicId($topicId)
    {
        $topic_service = new TopicService();
        $topic_list = $topic_service->getTopicByTopicId($topicId);
        $knowledgeList = $topic_list['knowledge_map'];
        return $knowledgeList;
    }

    /**
     * 根据知识点编码获取知识点信息。
     */
    public function getKnowledgeByCode($tag_code,$topicId,$is_send=true)
    {
        $topic_service = new TopicService();

        $topic_list = $topic_service->getTopicByTopicId($topicId);
        $knowledgeList = $topic_list['knowledge_map'];
        $tag_info =  array();
        $error_msg = "";
        $topic_service = new TopicService();
        $question_server_host = $topic_service::getQuestionServerHost();

        $url =  $question_server_host . "/index/api/getTopicByTopicId";

        
        if($tag_code=="zhlx")
        {
            $tag_info['tag_code'] =  $tag_code;
            $tag_info['tag_name'] =  "综合练习模块";

        }else
        {


            if(is_array($knowledgeList))
            {
                foreach ($knowledgeList as $k =>$v)
                {
                    if($v['tag_code'] == $tag_code)
                    {
                        $tag_info = $knowledgeList[$k];
                        break;
                    }
                }
                if(empty($tag_info))
                {
                    $tag_info['tag_code'] =  $tag_code;
                    $tag_info['tag_name'] = "题库没返回此知识点";
                    $error_msg = "算法和接口反馈的知识点不符合,此---".$tag_code."--知识点算法返回回来了,但题库getTopicByTopicId接口中没有返回此知识点,参数:topicId=".$topicId.",题库URL:".$url."\/".$topicId;
                }
            }else
            {
                $tag_info['tag_code'] =  $tag_code;
                $tag_info['tag_name'] =  "题库没返回此知识点";
                $error_msg = "算法和接口反馈的知识点不符合,此---".$tag_code."--知识点算法返回回来了,但题库getTopicByTopicId接口中没有返回此知识点,参数:topicId=".$topicId.",题库URL:".$url."\/".$topicId;

            }
        }
        if($error_msg&&$is_send)
        {
            $log_service = new logService();
            //$log_service::sendMessage('error',__METHOD__.$error_msg);
        }

        return  $tag_info;
    }






}