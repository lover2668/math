<?php
/**
 * Created by PhpStorm.
 * User: 沁芳阁
 * Date: 2016/12/23
 * Time: 15:36
 */

namespace service\services;


use service\entity\Group;

class GroupService extends CommonService
{

    function getGroupList()
    {
        $model=new Group();
        $map["status"]=1;
        $result=$model->where($map)->order("sort asc")->select();
        return $result;
    }

    function addGroup($data)
    {
        $model=new Group();
        $result=$model->save($data);
        return $result;
    }

    function updateGroup($data)
    {
        $model=new Group();
        $result=$model->saveAll($data,true);
        return $result;
    }

    function maxSort()
    {
        $model=new Group();
        $max=$model->max("sort");
        return $max;
    }
}