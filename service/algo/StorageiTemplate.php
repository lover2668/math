<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/8/17
 * Time: 上午10:23
 */
// 声明一个'iTemplate'接口
namespace service\algo;

interface  StorageiTemplate
{
    public function saveXianceLog($topicId,$module_type,$kmap_code,$request_data,$response_data);



    

}

