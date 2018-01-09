<?php
namespace service\services;

use service\entity\User;
use service\org\util\TpString;
use think\Cache;
use think\Db;
use think\Log;
use service\algo\AlgoLogic;
use think\Request;


class RemoteService extends BaseRemoteService
{
    /**
     * 获取用户和专题接口
     * @param $cid
     * @param $class_id
     * @param $course_id
     * @return mixed
     */
    function getUserAndTopics($cid,$class_id,$course_id)
    {
        $key="getUserAndTopics:cid:{$cid}:class_id:{$class_id}:course_id:{$course_id}";
        $return_data=Cache::get($key);
        //$return_data=null;
        if(empty($return_data))
        {
            $url = $this->api_server_user . "/classs/getUserAndTopics/class_id/{$class_id}/course_id/{$course_id}/cid/{$cid}";
            //$url="http://test-online.classba.com.cn/api.php/classs/getUserAndTopics/class_id/1126/course_id/106/cid/3";
            //echo $url;
            $return_data = rpc_request($url,null,"get");
            Cache::set($key,$return_data,3600 * 24);
        }
        return $return_data;
    }

    /**
     * 获取老师所在的班级和课程
     * @param $user_id
     * @return array|mixed
     */
    function getClassAndCourse($user_id)
    {

        $cacheKey="getClassAndCourse:$user_id:{$user_id}";
        $retunData=Cache::get($cacheKey);
        if(empty($retunData)) {
            $url = $this->api_server_user . "/course/getClassAndCourse/user_id/{$user_id}/cid/2";
            $return_data = rpc_request($url, null, "get");
            if ($return_data["data"]) {
                $retunData=$return_data["data"];
                $newReturnData=[];
                foreach ($retunData as $key=>$item)
                {

                    $course_list=[];
                    $class_info=["class_id"=>$item["class_info"]["class_id"],"class_name"=>$item["class_info"]["class_name"]];

                    foreach ($item["course_list"] as $courseListItem)
                    {
                        $course_list[]=  ["course_id"=>$courseListItem["course_id"],"course_name"=>$courseListItem["course_name"]];
                    }
                    $newReturnData[$key]=[
                        "class_info"=>$class_info,
                        "course_list"=>$course_list,
                    ];
                }
                $return_data=$newReturnData;
                Cache::set($cacheKey,$return_data,3600);
            } else {
                $retunData= [];
            }
        }
        return $retunData;
    }

}
