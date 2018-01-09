<?php
namespace app\teacher\controller;
use think\Controller;
/**
 * Description of BaseController
 *
 * @author zhangqiquan
 */
class BaseController extends Controller{
    protected $username='';
    protected $user_id='';
    function __construct(\think\Request $request = null,$login=true) {
        parent::__construct($request);
        $this->user_id= session('teacher.user_id');
        $this->username= session('teacher.username');
        if($login){
            if($this->user_id==false||$this->username==false)return $this->redirect ('Login/index');
        }        
    }
    //获取对应的接口地址
    private function configGetClass(){
        $url='';
        switch ($_SERVER['HTTP_HOST']) {
            case 'math.classba.cn':
                $url = config("api_server_user");
                break;
            case 'math.171xue.com':
                $url = config("api_server_user_171xue");
                break;
            default:
                $url = config("api_server_user_test");
                break;
        }
        return $url;
    }
    //通过uid cid 获取班级
    public function getClassList($user_id,$cid=2){
        $param=[];
        $param['user_id']=$user_id;
        $param['cid']=$cid;
        $return_data = rpc_request($this->configGetClass().'/classs/getClassList/', $param);
        return $return_data;
    }
    //根据班级获取课程
    public function getCourseList($class_id,$cid=2){
        $param=[];
        $param['class_id']=$class_id;
        $param['cid']=$cid;
        $return_data = rpc_request($this->configGetClass().'/course/lists/', $param);
        return $return_data;
    }
    //根据课程获取课次
    public function getCourseModules($course_id,$cid=2){
        $param=[];
        $param['course_id']=$course_id;
        $return_data = rpc_request($this->configGetClass().'/Course/modules/', $param);
        return $return_data;
    }
    //根据课程获取专题
    public function getTopicList($module_id){
        $param=[];
        $param['module_id']=$module_id;
        $return_data = rpc_request($this->configGetClass().'/course/topicList/', $param);
        return $return_data;
    }
    //通过班级获取学生列表
    public function getCourseUser($class_id,$cid=2){
        $param=[];
        $param['class_id']=$class_id;
        $param['cid']=$cid;
        $return_data = rpc_request($this->configGetClass().'/classs/users/', $param);
        return $return_data;
    }
}
