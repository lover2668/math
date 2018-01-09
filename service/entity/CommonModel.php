<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/8/9
 * Time: 下午3:12
 */

namespace service\entity;

use think\Model;

class CommonModel extends Model
{
    function addData($data)
    {
        return $this->save($data);
    }

    function updateData($data,$condition)
    {
        return $this->save($data,$condition);
    }

    function deleteData($condition)
    {
        $returnData=0;
        if($condition)
        {
            $returnData=$this->where($condition)->delete();
        }
        return $returnData;

    }

    public function listData($fields,$condition = array(),$order=null,$isAll=true,$pageSize=15,$config=null)
    {
        if($isAll) {
            $data = $this->where($condition)->field($fields)->order($order)->select();
            $total=count($data);
            $page="";
        }else
        {
            $result = $this->where($condition)->field($fields)->order($order)->paginate($pageSize,false,$config);
            if($result)
            {
                $resultArray=$result->toArray();
                $data=$resultArray["data"];
                $total=$resultArray["total"];
                $page=$result->render();
            }else
            {
                $data=[];
                $total=0;
                $page="";
            }


        }
        $returnData=["data"=>$data,"total"=>$total,"page"=>$page];
        return $returnData;
    }
}