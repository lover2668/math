<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/12
 * Time: 11:39
 */
namespace app\teacher\controller;
use service\services\UserService;
use think\Controller;
use think\Request;

class Login extends Controller
{

     public function index(){
        $PATH_INFO=explode('/', $_SERVER['PATH_INFO']);
        $token= isset($PATH_INFO[count($PATH_INFO)-1])?$PATH_INFO[count($PATH_INFO)-1]:'';
        header("Location: ".'/teacher.php/index/login/index/token/'.$token);die;
    }

    function index1()
    {
        $request=Request::instance();
        $token=$request->param("token");
        if($token)
        {
            $userService=new UserService();
            $returnData=$userService->teacherLoginFromTianWangXing($token);
            if($returnData["isSuccess"])
            {
                $url=url("ErrorQuestion/classes");
                $this->redirect($url);
            }else
            {
                $this->error("token 失效");
            }
        }else {
            if (Request::instance()->isPost()) {

                $userName = input("userName");
                $password = input("password");
                $tearch=[
                    "yxjy",
                    "lxteacher01",
                    "lxteacher02",
                    "lxteacher03",
                    "hdteacher01",
                    "hdteacher02",
                    "hdteacher03",
                ];
                if (in_array($userName,$tearch) && $password == "yxjy@2016") {
                    session("teacher", $userName);
                    $this->redirect("Index/index");
                } else {
                    $this->error("用户名密码错误!");
                }

            } else {
                return $this->fetch("");
            }
        }

    }

    /**
     * 退出登录
     */
    function logout()
    {
        session("teacher",null);
        $this->redirect("index");
    }
}