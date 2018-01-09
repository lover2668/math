<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/12
 * Time: 11:39
 */
namespace app\admin\controller;
use think\Controller;
use think\Request;

class Login extends Controller
{

    function login()
    {
        if (Request::instance()->isPost())
        {

            $userName=input("userName");
            $password=input("password");
            if($userName=="admin" && $password=="admin@2016")
            {
                session("admin",$userName);
                $this->redirect("Index/index");
            }else
            {
                $this->error("用户名密码错误!");
            }

        }else
        {
            return $this->fetch("");
        }

    }

    /**
     * 退出登录
     */
    function logout()
    {
        session("admin",null);
        $this->redirect("login");
    }
}