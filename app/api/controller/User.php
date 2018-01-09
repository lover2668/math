<?php
namespace app\api\controller;
use service\services\UserService;
class User extends Common{
    public function index(){
    }
    public function getUserTopicIdState($topicId,$user_id){
        $topicIdArr=explode(',', $topicId);
        $return_data=[];
        foreach($topicIdArr as $v){
            $data=UserService::getUserTopicIdState($v, $user_id);
            $return_data[$v]=$data;
        }
        $returnData=["code"=>1,"data"=>$return_data,"message"=>'查询成功'];
        echo json_encode($returnData);die;
    }
}
