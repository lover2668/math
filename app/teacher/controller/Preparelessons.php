<?php
namespace app\teacher\controller;
use service\services\Preparelessons as PreparelessonsServices;
use service\services\QuestionService;
/**
 * Description of Preparelessons
 *
 * @author zhangqiquan
 */
class Preparelessons extends BaseController{
    function __construct(\think\Request $request = null, $login = true) {
        parent::__construct($request, false);
    }
     protected  function getModuleTypeList() {
        $data = [
                ["id" => 1, "name" => "先行测试"],
                ["id" => 2, "name" => "高效学习"],
                ["id" => 5, "name" => "学习检测"],
                ["id" => 3, "name" => "竞赛拓展"], 
        ];
        return $data;
    }
    public function index(){
        $all_class=$this->getClassList($this->user_id);
        $classCourseList=[];
        foreach ($all_class['data'] as $key => $value) {
            $data=$this->getCourseList($value['class_id']);
            foreach ($data['data'] as $k=>$v){
                $classCourseList[$v['course_id']]= $v;
            }
        }
        $data['classCourseList']=$classCourseList;
        $moduleTypeList = $this->getModuleTypeList();
        $this->assign("moduleTypeList", $moduleTypeList);
        return $this->fetch("index",$data);
    }
    public function getList($topic_id='',$module_tyle_id=0, $page=1, $pageRows=10){
        if($topic_id&& $page&&$pageRows){
            $data=PreparelessonsServices::getPrepareContent($topic_id, $page,$module_tyle_id, $pageRows);
            
            if($data['error']>0){
                echo '<h3 style="padding:5px; background:#F33;">'.$data['msg'].'</h3>';die;
            }
            $this->assign('topic_id', $topic_id);
            $this->assign('page', $page);
            $this->assign('pageRows', $pageRows);
            $this->assign('module_tyle_id', $module_tyle_id);
            $this->assign('data', $data['data']);
            return $this->fetch();
        }
    }
    public function getInfo($id){
        $QuestionService=new QuestionService();
        $data=$QuestionService->getQuestionById($id);
        $this->assign('question', $data);
        return $this->fetch();
        //print_r($data);die;
    }
}
