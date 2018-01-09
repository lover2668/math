<?php

/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/12/21
 * Time: 下午7:29
 */

namespace service\services;

use service\org\util\TpString;
use think\Log;
use think\Cache;
use service\log\LogService;
use YXLog\YXLog;

class KnowledgeV2Service {

    protected static $question_server_host;

    public function __construct() {
        self::$question_server_host = config("v2_question_server_host");
    }

    public static function getQuestionServerHost() {
        return self::$question_server_host;
    }

    /**
     * @api {get} getKnowledgeByCode   获取知识点信息.
     */
    public function getKnowledgeByCode($knowledge, $log_option = array()) {
        $start_time = microtime(true);
        $key = "knowledgeInfo:" . $knowledge;
        $data = Cache::get($key);
        $data = array(); //暂时不走缓存
        if (!$data) {
            $param = array();
            $param["knowledge"] = $knowledge;
            //根据知识点获取试题.
            $url = self::$question_server_host . "getKnowledgeByCode";
            $return_data = rpc_request($url, $param);
            $data = $return_data["data"];
            /*             * ***日志埋点*** */
            $log_service = new logService("tichi");
            $topic = "info";
            $msg = array(
                'request_api' => "getKnowledgeByCode",
                'user_id' => "test----要改的",
                'request_data' => $param, //请求数据,
                'response_data' => $return_data, //响应数据
                'stime' => $start_time, // 接口开始时间
                'etime' => microtime(true), // 接口结束时间
                'ctime' => time()      // 创建时间。
            );
            $log_service::sendMessage($topic, $msg, 'getKnowledgeByCode');
            /*             * ***日志埋点*** */
            if (empty($data)) {
                $data['tag_name'] = "接口未定义";
                $data['tag_code'] = $knowledge;
                $data['video'] = [];
                $log_service = new logService();
                $log_service::sendMessage("error", __CLASS__ . "-----" . __METHOD__ . "题库接口getKnowledgeByCode----返回值为空, 传参数为:" . json_encode($param));
                YXLog::error( __CLASS__ . "-----" . __METHOD__ . "题库接口getKnowledgeByCode- 传参数为:" . json_encode($param)."返回值为". json_encode($return_data));
            } else {
                Cache::set($key, $data, 3600);
            }
            
        }
        return $data;
    }

    /**
     * @api {get} getKnowledgeByCodes   获取知识点信息.
     */
    public function getKnowledgeByCodes($codes) {
        $start_time = microtime(true);
        $param = array();
        $param["codes"] = $codes;
        //根据知识点获取试题.
        $url = self::$question_server_host . "getKnowledgeByCodes";
        $return_data = rpc_request($url, $param);
        /*         * ***日志埋点**** */
        if ($return_data['code'] != 200) {
            $log_service = new logService();
            $log_service::sendMessage("error", __CLASS__ . "-----" . __METHOD__ . "题库接口
getKnowledgeByCodes----返回值不是200, 传参数为:" . json_encode($param) . "返回值为:" . json_encode($return_data));
            YXLog::error( __CLASS__ . "-----" . __METHOD__ . "题库接口getKnowledgeByCodes----返回值为空, 传参数为:" . json_encode($param)."返回值为". json_encode($return_data));
        }
        return $return_data['data'];
    }

    /**
     * 获取专题的学习图谱的 知识点。
     * @param $topicId
     * @return mixed
     */
    public function getKnowledgeListByTopicId($topicId) {
        $topic_service = new TopicService();
        $kmap_code = $topic_service->getKmapCodeByTopicId($topicId);
        $topic_v2_service = new TopicV2Service();
        $knowledgeList = $topic_v2_service->getKmapInfoByKmapCode($kmap_code);
        return $knowledgeList;
    }

    /**
     * 批量取知识点的接口
     * @param Array $knowledges   例如 ['c200201','q0401','q0405','q0404' ]    
     * @return type
     */
    public function getKnowledgeListByCodes($knowledges) {
        $request_num = 20;  // 定义一次传递的code数量
        $return_data = [];
        $need_request_code = []; // 需要请求的code

        if (!is_array($knowledges) || empty($knowledges)) {
            return $return_data;
        }


        foreach ($knowledges as $knowledge) {
            $key = "knowledgeInfo:" . $knowledge;
            $knowledgeInfo = Cache::get($key);
            $knowledgeInfo = array();
            if (!$knowledgeInfo) {
                $need_request_code[] = $knowledge;
            } else {
                $return_data[$knowledge] = $knowledgeInfo;
            }
        }

        if (empty($need_request_code)) {
            return $return_data;
        }

        $need_request_code_arrays = array_chunk($need_request_code, $request_num);  //把数组转化为小数组

        foreach ($need_request_code_arrays as $value) {
            $codes = implode(",", $value);
            $codeinfo = $this->getKnowledgeByCodes($codes);
//            var_dump($codeinfo);die;
            foreach ($value as $tag_code) {
                $key = "knowledgeInfo:" . $tag_code;
                if (!empty($codeinfo[$tag_code]) && isset($codeinfo[$tag_code])) {
                    $return_data[$tag_code] = $codeinfo[$tag_code];
                    Cache::set($key, $codeinfo[$tag_code], 3600);
                } else {
                    $return_data[$tag_code] = [
                        "tag_name" => "未定义code",
                        "video" => [],
                        "tag_code" => $tag_code,
                        "contet" => ""
                    ];
                    $log_service = new LogService();
                    $log_service::sendMessage("error", "题库接口getKnowledgeByCodes----返回值错误,没有查到知识点:".$tag_code."的信息--" );
                    YXLog::error("题库接口getKnowledgeByCodes----返回值错误,没有查到知识点:".$tag_code."的信息--");
                }
            }
        }
        return $return_data;
    }

}
