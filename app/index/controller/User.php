<?php
namespace app\index\controller;
use service\services\UserService;
use service\services\PathManageService;
use think\Request;
use  think\Log;
class User extends  Base
{

    public function index()
    {
//        $test = new questionService();
//        $test->index();
        echo "fsdfsadfasdfs";
//        return $this->fetch();
    }

    public function test()
    {


    }

    public function addUserAccount()
    {

        $user =  new UserService();
        $user->batchAddAccount();



    }


    /**
     * 获取用户下一步要做的模块
     */
    public function getUserNextModule()
    {
        $request = Request::instance();
        $pre_module_type = $request->param('pre_module_type');
        $topicId = $request->param('topicId');
        $path_manager = new PathManageService();
        $return_data=  $path_manager->getUserNextModule('',$topicId,$pre_module_type);
        echo json_encode($return_data);

    }




}
