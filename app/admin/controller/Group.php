<?php
/**
 * 王业坤
 */
namespace app\admin\controller;

use service\services\GroupService;
use think\Request;

class Group extends Base
{
    /**
     * 添加用户
     *
     * @return mixed
     */
    public function addGroup()
    {
        if (Request::instance()->isPost()) {
            $name=input("name");
            if(trim($name)=="")
            {
                $this->error("请输入分组名称!");
            }
            $data["name"]=$name;
            $groupService=new GroupService();
            $maxSort=$groupService->maxSort();
            $data["sort"]=$maxSort+1;
            $result=$groupService->addGroup($data);
            if($result)
            {
                $this->success("添加成功!");
            }else
            {
                $this->error("添加失败");
            }


        } else {
            return $this->fetch("addGroup");
        }


    }

    /**
     * 获取未使用过的用户
     *
     * @return mixed
     */
    public function listGroup()
    {

        if (Request::instance()->isPost()) {

        } else {

            $groupService=new GroupService();
            $data=$groupService->getGroupList();
            $this->assign("data",$data);
            return $this->fetch("listGroup");
        }
    }


    public function updateGroup()
    {
        if (Request::instance()->isPost()) {

            $name=input("name");
            $sort=input("sort");
            $id=input("id");
            $data[]=["id"=>$id,"name"=>$name,"sort"=>$sort];
            $groupService=new GroupService();
            $result=$groupService->updateGroup($data);
            if($result)
            {
                $this->success("更新成功!");
            }else
            {
                $this->error("更新失败");
            }

        }
    }

}
