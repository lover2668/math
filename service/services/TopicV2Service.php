<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/12/21
 * Time: 下午7:29
 */

namespace service\services;

use think\Cache;
use service\log\LogService;
use think\Log;


class TopicV2Service
{
    protected static $question_server_host;

    public function __construct()
    {
        self::$question_server_host = config("new_topic_service_api_url");
    }

    public static function getQuestionServerHost ()
    {
        return  self::$question_server_host;
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
            $url = self::$question_server_host . "/math/v2/getTopicList";
            $param['type']=3;
            $return_data = rpc_request($url, $param);


            if(empty($return_data))
            {
                $log_service = new  logService();
                $log_service::sendMessage("error",__METHOD__."题库接口getTopicList----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
            }


            foreach ($return_data['data'] as $key => $value)
            {
                $return_data['data'][$key]['tid'] = $value['id'];
            }
            Cache::set($key, $return_data);
        }
        return $return_data['data'];
    }


    /**
     * 获取单个专题的信息。
     * @param $topicId
     * @return mixed
     */
    public function getTopicByTopicId($topicId)
    {
        //if($topicId>200)exit ("请正确选择专题");
        if($topicId)
        {
            $key = "topicList:" . $topicId;
            $data = Cache::get($key);
//            $data = array();
            if (!$data) {
                $param = array();
                $param['tid'] = $topicId;
                //根据知识点获取试题.
                $url = self::$question_server_host . "/math/v2/getTopicByTopicId";
                $return_data = rpc_request($url, $param);
                $data = $return_data["data"];
                if(empty($return_data))
                {
                    $log_service = new  logService();
                    $log_service::sendMessage("error",__METHOD__."题库接口getTopicByTopicId----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
                }elseif ($return_data["code"] == 200) 
                {
                    Cache::set($key, $data,3600);
                }
            }
            return $data;
        }
    }

    /**
     * 根据topicId获取kmap_code.
     * @param $topicId
     */
    public function getKmapCodeByTopicId($topicId)
    {
        $topic_info = $this->getTopicByTopicId($topicId);
        $kmap_code =  $topic_info['kmap_code'];
        return $kmap_code;
    }


    /**
     *
     * @param $kmap_code
     */
    public function getKmapInfoByKmapCode($kmap_code)
    {


        $key = "kmapInfoByKmapCode:";
        $return_data = Cache::get($key);
        $return_data = array();
        if (!$return_data) {
            $param = array();
            //根据知识点获取试题.
            $url =  self::$question_server_host."/math/v2/getKmapInfoByKmapCode";
            $param['kmap_code']=$kmap_code;
            $return_data = rpc_request($url, $param);
            if(empty($return_data))
            {
                $log_service = new  logService();
                $log_service::sendMessage("error",__METHOD__."题库接口getTopicList----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
            }

            Cache::set($key, $return_data);
        }
        return $return_data['data'];

    }





    /**
     * 根据知识点获取章节
     * @param $tag_code
     */
    public function   getChapterForTagCode($topicId,$tag_code)
    {

        $chapter_arr = array();
        $topic_info = $this->getTopicByTopicId($topicId);

        $chapter_list =  $topic_info['chapter_list'];

        foreach ($chapter_list  as  $key=>$val)
        {
                $tag_list = $val['tag_list'];
                $tag_arr  = explode(",",$tag_list);

                foreach ($tag_arr as $k=>$v) {

                    $chapter_arr[$key][] = $v;
                }
        }

        $return_data = array();
        foreach ($chapter_arr  as $key=>$val)
        {
            if(in_array($tag_code,$val))
            {
                $return_data []= $key;
            }
        }

        return  $return_data;
    }


    /**
     * 获取入口图谱编码
     * @param $topicId
     */
    public function getMainKmapCode($topicId)
    {

        //获取L1的大图谱。
        $topic_info= $this->getTopicByTopicId($topicId);
        $kmap_enter_key = $topic_info['kmap_enter_key'];
        $kmap_code_list = $topic_info['kmap_code_list'];
        $kmap_enter_code_info = $kmap_code_list[$kmap_enter_key];
        if(!isset($topic_info['kmap_enter_key']) || empty($topic_info['kmap_enter_key'])){
            Log::error("专题:id".$topicId."------返回值里没有kmap_enter_key,返回内容为:". json_encode($topic_info));
        }
        //先测的入口图谱。
        $kmap_code = $kmap_enter_code_info['kmap_code'];
        return $kmap_code;
    }

     /**
     * 获取主图谱编码
     * @param $topicId
     */
    public function getKmapCodeAll($topicId)
    {

        //获取L1的大图谱。
        $topic_info= $this->getTopicByTopicId($topicId);
        $kmap_code_list = $topic_info['kmap_code_list'];
        $kmap_enter_code_info = $kmap_code_list["200"];
        if(!isset($kmap_code_list['200']) || empty($kmap_code_list['200'])){
            Log::error("专题:id".$topicId."------返回值kmap_code_list里没有主图谱编码200,返回内容为:". json_encode($topic_info));
        }
        $kmap_code = $kmap_enter_code_info['kmap_code'];
        return $kmap_code;
    }


    /**
     * 获取大知识图谱的知识点信息。
     * @param $topicId
     * @return mixed
     */
    public function getBigKmapTagCodeList($topicId)
    {

        //获取L1的大图谱。
        $topic_info= $this->getTopicByTopicId($topicId);
        $kmap_code_list = $topic_info['kmap_code_list'];
        $kmap_code_info = $kmap_code_list['200'];
        //先测的入口图谱。
        $big_kmap_code = $kmap_code_info['kmap_code'];
        $tag_code_list = $this->getKmapInfoByKmapCode($big_kmap_code);
        return $tag_code_list;
    }




    public function getZhlxKmapCodeList($topicId)
    {
        //获取L1的大图谱。
        $topic_info = $this->getTopicByTopicId($topicId);
        $kmap_code_list = $topic_info['kmap_code_list'];

        if (isset($kmap_code_list['265']))
        {
            $kmap_code_info = $kmap_code_list['265'];

            //先测的入口图谱。
            $big_kmap_code = $kmap_code_info['kmap_code'];
            $tag_code_list = $this->getKmapInfoByKmapCode($big_kmap_code);
        }else{
            $tag_code_list = array();
        }
        return $tag_code_list;




    }

    /**
     * 根据topicId获取章节。
     * @param $topicId
     * @return mixed
     */
    public function getChapterListByTopicId($topicId)
    {
        $topic_info= $this->getTopicByTopicId($topicId);
        $chapter_list = [];
        if (isset($topic_info["chapter_list"])) {
            $chapter_list = $topic_info["chapter_list"];
        }
        return $chapter_list;
    }


}