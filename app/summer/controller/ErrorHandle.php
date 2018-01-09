<?php
namespace app\summer\controller;
class ErrorHandle extends  \think\Controller
{

    public function newError($msg,$url)
    {
        $this->error($msg,$url);
    }




}
