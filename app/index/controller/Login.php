<?php
namespace app\index\controller;

use service\services\UserService;
use think\Controller;
use think\Request;
use think\Session;
use service\services\TopicV2Service;
use service\services\PathManageService;


class Login extends Controller
{

    protected function _initialize()
    {
        session('');
    }
    /**
     *
     * @return mixed
     */
    public function login()
    {
        $request  = Request::instance();
        $debug =  $request->param('debug');
        if(!$debug)
        {
            $debug = 0;
        }

//        $path_manager = new PathManageService();
//        $return_data=  $path_manager->getUserSummerNextModule('',$topicId,"");
//        $url = $return_data['url'];
//        $this->redirect($url);
//
        $this->assign('debug',$debug);
        return   $this->fetch();
    }


    /**
     * @api {post} index/login/loginIn/pwd/:pwd/username/:username  用户登录
     * @apiVersion 0.0.1
     * @apiName  loginIn
     * @apiGroup Login
     *
     * @apiParam {String} pwd 用户密码.
     * @apiParam {String} username 用户昵称.
     * @apiSuccess {Number} isSuccess  是否登录成功.
     * @apiSuccess {Number} err_code  错误编码.
     * @apiSuccess {String} err_info 错误说明.
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "isSuccess": 0,
     *       "err_code": 1,
     *       "err_info":"密码错误"
     *     }
     * @apiError UserNotFound The id of the User was not found.
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UserNotFound"
     *     }
     */
    public function loginIn($token = "")
    {
       // $token="ecyxJp1hapWQiOjEyMiwidG9waWNfaWQiOjM5LCJ1bmFtZSI6InhpYW9sYW4xMjIiLCJ2ZXJpZnkiOiJrdW5zaGFuMTQifQO0O0OO0O0O";
        //$token="ecyxJp1hapWQiOjEsInRvcGljX2lkIjoxMzgsInZlcmlmeSI6Inl4IiwidW5hbWUiOiJzdHVkZW50MDAxIn0O0O0O";
//        $token ="ecyxJp1hapWQiOjI4NDM2LCJ0b3BpY19pZCI6OTQxNCwidmVyaWZ5IjoieXgiLCJ1bmFtZSI6Inl4NTIxMXN0dWRlbnQxMDAiLCJjb3Vyc2VfbmFtZSI6IjIwMTdcdTY2OTFcdTY3MWZcdTY1NzBcdTViNjZcdTViNjZcdTRlNjBcdWZmMDhcdTZkNGJcdThiZDVcdTc1MjhcdWZmMDkiLCJjb3Vyc2VfaWQiOiIyMDEiLCJzZWN0aW9uX2lkIjoiNzY0NyIsInNlY3Rpb25fbmFtZSI6Ilx1NGViYVx1NjU1OVx1NzI0OFx1NGUwM1x1NWU3NFx1N2VhN1x1NjY5MVx1NTA0N1x1N2IyYzFcdTZiMjFcdThiZmUiLCJyZWdpb25faWQiOjB9";
//       $token = "ecyxJp1hapWQiOjMxMDgzLCJ0b3BpY19pZCI6MzgsInZlcmlmeSI6Inl4IiwidW5hbWUiOiIxNTNzdHVkZW50MTA5IiwiY291cnNlX25hbWUiOiJcdTRlMDNcdTVlNzRcdTdlYTdcdTY2MjVcdTViNjNcdTY1NzBcdTViNjZcdThiZDVcdTU0MmNcdThiZmUiLCJjb3Vyc2VfaWQiOiIxNDYiLCJzZWN0aW9uX2lkIjoiNDE4OSIsInNlY3Rpb25fbmFtZSI6Ilx1NGUwM1x1NWU3NFx1N2VhN1x1NjYyNVx1NWI2M1x1NjU3MFx1NWI2Nlx1OGJkNVx1NTQyY1x1OGJmZSIsInJlZ2lvbl9pZCI6MH0O0O0O";
        $user_service = new  UserService();
        if ($token) {

            $token_info = token_verify($token);
//            $topicId=$user_service->getTopicId();
            $topicId = $token_info['topic_id'];
            $topic_v2_service = new  TopicV2Service();
            $topicInfo =  $topic_v2_service->getTopicByTopicId($topicId);
            $flow_id = $topicInfo['flow_id'];

            if($flow_id==8)
            {
                $url = config("demo_url").$token;
                $this->redirect($url);
            }else{
                $data = $user_service->loginFromTianWangXing($token);//正式的
                if($data["isSuccess"]==1)
                {
                    $path_manager = new PathManageService();
                    $return_data=  $path_manager->getUserSummerNextModule('',$topicId,"");
                    $url = $return_data['url'];
                    $this->redirect($url);
                }else
                {
                    $this->error($data["err_info"]);
                }
            }
        } else {
            $request = Request::instance();
            $username = $request->param('username');
            $passwd = $request->param('pwd');
            $data = $user_service->loginInTest($username, $passwd); //测试的
            echo json_encode($data);
        }
    }


    /**
     * @api {post} index/login/loginOut  用户退出登录
     * @apiVersion 0.0.1
     * @apiName  loginOut
     * @apiGroup Login
     */
    public function loginOut()
    {
        if(empty(session_id()))
        {
//            session_start();  //加上session_id后，此处必须加.否则报错。
//            Session::start();
        }
//        $user_service = new UserService();
        Session::clear();
        $this->redirect("/");

    }


}
