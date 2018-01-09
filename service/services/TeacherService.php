<?php
namespace service\services;

class TeacherService
{
    /**
     * 获取课次
     * @param $cid 课程类别id 2:数学 3：语文
     * @param $class_id 班级id
     * @param $course_id 课程id
     * @return array
     */
    function getCharpter($cid,$class_id,$course_id)
    {
        $returnData=[];
        $service=new RemoteService();
        $data=$service->getUserAndTopics($cid,$class_id,$course_id);
        $modules=[];
        if(isset($data["data"]["modules"])&&$data["data"]["modules"])
        {
            $modules=$data["data"]["modules"];
        }
        foreach ($modules as $item)
        {
            $topicList=[];
            foreach ($item["topicList"] as $topicItem)
            {
                $topicList[$topicItem["topic_id"]]=["topic_id"=>$topicItem["topic_id"],"topic_name"=>$topicItem["name"]];
            }
            $returnData[$item["module_id"]]=[
                "charpter_id"=>$item["module_id"],
                "charpter_name"=>$item["name"],
                "topicList"=>$topicList,
                ];;
        }
        return $returnData;
    }

    /**
     * 获取老师的班级和课程
     * @param $user_id
     * @return array|mixed
     */
    function getClassAndCourse($user_id)
    {
        $service=new RemoteService();
        return $service->getClassAndCourse($user_id);
    }


    function getUsersFromRemote($cid,$class_id,$course_id)
    {

        $returnData=[];
        $service=new RemoteService();
        $data=$service->getUserAndTopics($cid,$class_id,$course_id);
        if(isset($data["data"]["user_list"])&&$data["data"]["user_list"])
        {
            $returnData=$data["data"]["user_list"];
        }
       return $returnData;
    }


}
