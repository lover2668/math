<?php
/**
 * 王业坤
 */
namespace app\admin\controller;

use service\services\GroupService;
use service\services\UserService;
use think\Exception;
use think\Request;

class User extends Base
{
    /**
     * 添加用户
     *
     * @return mixed
     */
    public function addUser()
    {
        if (Request::instance()->isPost()) {
            $userName = input("userName");
            $password = input("password");
            $type=input("type");
            $start=intval(input("start"));
            $end=intval(input("end"));

            if($start>$end)
            {
                $this->error("开始数值要小于或等于结束数值！");
            }

            if (empty($userName) || empty($password)) {
                $this->error("请输入用户名或密码！");
            }

            $userService = new UserService();
            $user = $userService->getUserByName($userName);
            if ($user) {
                $this->error("该用户已经存在");
            }

            try
            {
                $result = $userService->addUser($userName, $password,$start,$end,$type);
            }catch (Exception $ex)
            {

                $this->error($ex->getMessage());
            }



            if ($result) {
                $this->success("添加成功！");
            } else {
                $this->error("网络繁忙，请稍后重试！");
            }
        } else {
            $groupService=new GroupService();
            $groupList=$groupService->getGroupList();
            $this->assign("groupList",$groupList);
            return $this->fetch("addUser");
        }


    }

    /**
     * 获取未使用过的用户
     *
     * @return mixed
     */
    public function unUsedUser()
    {

        if (Request::instance()->isPost()) {

        } else {

            $groupService=new GroupService();
            $groupList=$groupService->getGroupList();


            $type=input("type");
            $request = Request::instance();
            $param=$request->param();

            $userService = new UserService();
            $result = $userService->unUsedUser($type,$param);
            $page=$result->render();
            // 模板变量赋值
            $this->assign("groupList",$groupList);
            $this->assign("type",$type);
            $this->assign('page', $page);
            $this->assign("data", $result);
            return $this->fetch("unUsedUser");
        }
    }

    /**
     * 冻结账号
     */
    function freezeUser()
    {
        $request=Request::instance();
        $ids=$request->param("ids/a");
        if($ids==null)
        {
            $this->error("请选择要冻结的账号");
        }
        $userService = new UserService();
        $result = $userService->freezeUser($ids);
        if($result)
        {
            $this->success("冻结成功！");
        }else
        {
            $this->success("冻结失败！");
        }
    }

    /**
     * 解冻账号
     */
    function unfreezeUser()
    {
        $request=Request::instance();
        $ids=$request->param("ids/a");
        if($ids==null)
        {
            $this->error("请选择要解冻的账号");
        }
        $userService = new UserService();
        $result = $userService->unFreezeUser($ids);
        if($result)
        {
            $this->success("解冻成功！");
        }else
        {
            $this->success("解冻失败！");
        }
    }
}
