<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/8/9
 * Time: 下午3:12
 */

namespace service\entity;

use think\Model;

class User extends Model
{


    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TO
    }




}