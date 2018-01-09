<?php

/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 17/5/31
 * Time: 22:58
 */

namespace service\services\summer;

use service\services\CommonService;
use service\services\TopicV2Service;
use think\Db;
use think\Log;
use think\Cache;
use service\log\LogService;
use service\services\QuestionService;
use service\services\KnowledgeService;
use service\services\summer\SummerUserService;

class SummerCindexService extends CommonService {

    /**
     * 获取用户L2部分先行测试图谱。
     * @param $user_id
     * @param $topicId
     * @return string
     */
    public function getCxianceKmapCode($user_id,$topicId)
    {
        if(!$user_id)
        {
            $user_id = $this->getUserId();
        }
        $summer_user_service = new SummerUserService();
        $module_type = config('l2_xiance_module_type');
        $learned_code_info  =  $summer_user_service->getUserL2XianceLearnedKmapCodeByModuleType($user_id,$topicId,$module_type);
        if(empty($learned_code_info))
        {
            Log::record(__METHOD__." UserL2XianceLearnedKmapCode  is null ");
            $scale = $summer_user_service->getXianceLearnedAlgoScale($user_id,$topicId);
            $topic_v2_service =  new TopicV2Service();
            $topic_info= $topic_v2_service->getTopicByTopicId($topicId);
            $kmap_code_list = $topic_info['kmap_code_list'];
            $small_kmap_code =  $kmap_code_list['220']['kmap_code'];
            $middle_kmap_code = $kmap_code_list['230']['kmap_code'];
            $big_kmap_code = $kmap_code_list['240']['kmap_code'];
            $kmap_code = "";
            if($scale>=0&&$scale<0.6)
            {
                $kmap_code = $small_kmap_code;

            }elseif($scale>=0.6&&$scale<0.8)
            {
                $kmap_code = $middle_kmap_code;

            }elseif($scale>=0.8)
            {
                $kmap_code = $big_kmap_code;
            }else{
                $kmap_code = $small_kmap_code;
            }
            if($kmap_code)
            {
                $summer_user_service->insertUserLearnedKmapCode($user_id,$topicId,$module_type,$kmap_code);
            }
        }else{
            Log::record(__METHOD__." UserL2XianceLearnedKmapCode  is not null ");
            $kmap_code = $learned_code_info['kmap_code'];
            Log::record(__METHOD__." UserL2XianceLearnedKmapCode is  kmap_code ");
        }
        return  $kmap_code;
    }


    public function  getPreviewKmapCode($topicId)
    {
        $topic_v2_service =  new TopicV2Service();
        $topic_info= $topic_v2_service->getTopicByTopicId($topicId);
        $kmap_code_list = $topic_info['kmap_code_list'];
        $kmap_code =  $kmap_code_list['210']['kmap_code'];
        return  $kmap_code;
    }






}
