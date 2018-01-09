<?php
namespace app\teacher\controller;
use think\Controller;
class Base extends Controller
{
    protected $userInfo = array();  //用户全局基本信息，包括已经学过的状态

    protected function _initialize() {


        //判断是否登录
        if(session('teacher'))
        {
            // echo "登录成功";
            $teacher=$this->getTeacherName();
            $this->assign("teacher",$teacher);

        }else{
            $this->redirect('Login/index');

        }


    }

    protected function getTeacherName()
    {
        $teacher="";
        $user=session("teacher");
        if(isset($user["username"]))
        {
            $teacher=$user["username"];
        }
        return $teacher;
    }
    /**
     * 获取用户SESSION信息
     * @return mixed
     */
    public function getUserInfo() {
        $userInfo = session('userInfo');
        return $userInfo;
    }

    /**
     * 获取用户的user_id
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