<?php
/**
 * Created by PhpStorm.
 * User: 沁芳阁
 * Date: 2016/12/23
 * Time: 15:35
 */

namespace service\entity;


use think\Model;

class Group extends Model
{
    protected $auto = ['ctime'];
    protected $insert = ['status' => 1];

    protected function setCtimeAttr($value)
    {
        return date("Y-m-d H:i:s");
    }
}