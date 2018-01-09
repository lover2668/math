<?php
namespace service\services;
use service\log\LogService;
use think\Log;

class BaseUserService extends CommonService
{
    /**
     * @api {loginFromTianWangXing} /loginFromTianWangXing/:token 从天王星登录
     * @apiGroup login
     * @apiParam {String} token 从天王星传过来的token
     * @apiSuccessExample 成功:
     *   *     HTTP/1.1 200 OK
     *  {
     *         {
     *            "id":10,
     *           "difficulty":1
     *          },
     *          {
     *             "id":11,
     *             "difficulty":2
     *          }
     * }
     * @param $token
     * @return mixed
     */
    function loginFromTianWangXing($token)
    {
        $user = token_verify($token);
        Log::record("token------:".$token."-------decode_resut_user-----:".json_encode($user));

        $log_service = new logService();
        $log_service::sendMessage("info","token------:".$token."-------decode_resut_user-----:".json_encode($user));


        if(isset($user["time"]))
        {
            $tokenTime=$user["time"];
            $currentTime=time();
            if($currentTime-$tokenTime>5)
            {
                $log_service::sendMessage("info","超时5秒，token值失效！".json_encode($user));
                die("超时5秒，token值失效！");
            }
        }


        if ($user['uid'] && $user['uname']) {
            $return_data['isSuccess'] = 1;
            $return_data['err_info'] = "登陆成功";
            $data = array(
                "user_id" => $user['uid'],
                "username" => $user['uname'],
                "topic_id" => $user["topic_id"],
                'course_id' => isset($user["course_id"]) ? $user["course_id"] : 0,
                'course_name' => isset($user["course_name"]) ? $user["course_name"] : 0,
                'section_id' => isset($user["section_id"]) ? $user["section_id"] : 0,
                'section_name' => isset($user["section_name"]) ? $user["section_name"] : 0,
                'class_id' => isset($user["class_id"]) ? $user["class_id"] : 0,
                'class_name' => isset($user["class_name"]) ? $user["class_name"] : 0,
            );
            $this->setUserSessionInfo($data);
        }else {
            $return_data['isSuccess'] = 0;
            $return_data['err_info'] = "天王星接口出错";
        }
        return $return_data;
    }

    /**
     * 获取用户做的专题ID
     * @return mixed
     */
    function getTopicId()
    {
        $user=$this->getUserSession();
        return $user["topic_id"];
    }


    function teacherLoginFromTianWangXing($token)
    {
        $user = token_verify($token);
        if ($user['uid'] && $user['uname']) {
            $return_data['isSuccess'] = 1;
            $return_data['err_info'] = "登陆成功";
            $data = array(
                "user_id" => $user['uid'],
                "username" => $user['uname']
            );
            session("teacher",$data);
        } else {
            $return_data['isSuccess'] = 0;
            $return_data['err_info'] = "天王星接口出错";
        }
        return $return_data;
    }


}
