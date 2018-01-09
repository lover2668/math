<?php
/**
 * 王业坤
 */
namespace app\admin\controller;

use service\services\GroupService;
use think\Request;

class Index extends Base
{
  function index()
  {
      $currentDate=date("Y-m-d H:i:s");
      $ip=Request::instance()->ip();
      $this->assign("currentDate",$currentDate);
      $this->assign("ip",$ip);

      return $this->fetch();
  }

}
