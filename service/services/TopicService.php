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
use Symfony\Component\PropertyAccess\Tests\Fixtures\TraversableArrayObject;
use  think\Db;
use  think\Log;
use think\Cache;
use service\log\LogService;



class TopicService extends \think\Controller
{
    protected static $question_server_host;

    public function __construct()
    {
        parent::__construct();
        self::$question_server_host = config("question_server_host");
    }

    public static function getQuestionServerHost ()
    {
        return  self::$question_server_host;
    }

    public function getTopicInfoByTopicId($topicId)
    {


    }

    /**
     * @api {get}  getTopicList  专题列表
     * @apiVersion 0.0.1
     * @apiName  getTopicList  专题列表
     * @apiGroup  topic_service
     *
     * @apiSuccess {String} id  专题id.
     * @apiSuccess {String} topic_name  专题内容信息.
     *
     * @apiSuccess {String} pic_url  专题图片地址.
     * @apiSuccess {String} video_url 专题视频地址.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         {
     *             "id":"9",
     *             "topic_name":"\u671f\u672b\u51b2\u523a\u4e94\u5e74\u7ea7",
     *             "pic_url":"http:\/\/img.classba.com.cn\/2016-05-02_57271c340b378.jpg",
     *             "video_url":"http:\/\/media1.classba.cn\/math_qm_05.mp4",
     *         },
     *          {
     *              "id":"9",
     *              "topic_name":"\u671f\u672b\u51b2\u523a\u4e94\u5e74\u7ea7",
     *              "pic_url":"http:\/\/img.classba.com.cn\/2016-05-02_57271c340b378.jpg",
     *              "video_url":"http:\/\/media1.classba.cn\/math_qm_05.mp4",
     *          }
     *     }
     */
    public function getTopicList()
    {

        $key = "topicList:";
        $return_data = Cache::get($key);
        if (!$return_data) {
            $param = array();
            //根据知识点获取试题.
            $url = self::$question_server_host . "index/api/getTopicList/type/3";
            $return_data = rpc_request($url, $param);

            if(empty($return_data))
            {
                $log_service = new  logService();
                $log_service::sendMessage("error",__METHOD__."题库接口getKnowledgeByCode----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
            }


            foreach ($return_data as $key => $value)
            {
                $return_data[$key]['topic_name'] = $value['topic'];

            }
            Cache::set($key, $return_data);
        }
        return $return_data;
    }


    /**
     * 获取单个专题的信息。
     * @param $topicId
     * @return mixed
     */
    public function getTopicByTopicId($topicId)
    {
//        if($topicId>=9000)
//        {
            $topic_v2_service = new  TopicV2Service();
            $return_data =  $topic_v2_service->getTopicByTopicId($topicId);
//        }else{
//            //if($topicId>200)exit ("请正确选择专题");
//            $key = "topicList:" . $topicId;
//            $return_data = Cache::get($key);
//            $return_data = array();
//            if (!$return_data) {
//                $param = array();
//                $param['tid'] = $topicId;
//                //根据知识点获取试题.
//                $url = self::$question_server_host . "index/api/getTopicByTopicId";
//                $return_data = rpc_request($url, $param);
//                if(empty($return_data))
//                {
//                    $log_service = new  logService();
//                    $log_service::sendMessage("error",__METHOD__."题库接口getTopicByTopicId----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
//                }
//
//                Cache::set($key, $return_data);
//            }
//
//        }
        return $return_data;
    }

    /**
     * 根据topicId获取kmap_code.
     * @param $topicId
     */
    public function getKmapCodeByTopicId($topicId)
    {
        //大于等于9000，走小胡新接口，小于9000，走三千老接口。
//        if($topicId>=9000)
//        {
            $topic_v2_service = new TopicV2Service();
            $kmap_code = $topic_v2_service->getMainKmapCode($topicId);
//        }else{
//            $topic_list = $this->getTopicByTopicId($topicId);
//            $kmap_code =  $topic_list['kmap_code'];
//        }
        return $kmap_code;
    }

}