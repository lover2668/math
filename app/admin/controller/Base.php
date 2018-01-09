<?php
namespace app\admin\controller;

use think\Controller;

class Base extends Controller
{
    protected $userInfo = array();  //用户全局基本信息，包括已经学过的状态

    protected function _initialize()
    {

        //判断是否登录
        if (session('admin')) {
//            echo "登录成功";

        } else {
            /* echo "登录失败,跳转到登录页面.";*/

             $this->redirect('Login/login');

        }


    }

    /**
     * 获取用户SESSION信息
     *
     * @return mixed
     */
    public function getUserInfo()
    {
        $userInfo = session('userInfo');
        return $userInfo;
    }

    /**
     * 获取用户的user_id
     *
     * @return user_id
     */
    public function getUserId()
    {
        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
        return $user_id;
    }

    /**
     * 404跳转页面
     */
    protected function error404()
    {

    }

    /**
     * 403跳转页面
     */
    protected function error403()
    {


    }


}