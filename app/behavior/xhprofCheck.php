<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 17/8/21
 * Time: 11:22
 */


namespace app\behavior;
use service\lib\xhprof\xhprof;


class xhprofCheck
{
    var $is_epen_xhprof;
    public function  __construct()
    {
        $this->is_epen_xhprof = config('is_open_xhprof')?config('is_open_xhprof'):false;
    }

    public function app_init(&$params)
    {
        if($this->is_epen_xhprof)
        {
            xhprof::s();
        }
    }

    public function app_end(&$params)
    {
        if($this->is_epen_xhprof)
        {
            xhprof::e();
        }
    }
}