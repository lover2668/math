<?php
namespace app\teacher\controller;
use service\services\QuestionService;
use service\services\TeacherService;
use service\services\UserService;
use service\services\TopicService;
use think\Db;
use think\Request;
use service\services\KnowledgeService;
/**
 * Description of Student
 *
 * @author zhangqiquan
 */
class Mystudents extends BaseController   {
    function __construct(Request $request = null, $login = true) {
        parent::__construct($request, false);
    }
    function index()
    {
        if($this->user_id==false||$this->username==false)return $this->redirect ('Login/index');
        $data=[];
        //分配班级
        //查询班级
        $class=$this->getClassList($this->user_id, 2);
        $data['class']=$class;
        return $this->fetch("index",$data);
    }
    
        public function getList(){
        $post= input();
        $topicService=new TopicService();
        $topicList=$topicService->getTopicList();
        $userList=$this->getCourseUser($post['class_id'],2);
        $user_id_arr=$userList['data'];
        $user_id_arr=array_column($user_id_arr,'user_name', 'user_id');
        //print_r($user_id_arr);die;
        //获取当前用户下所有做过的题目
        $questions=[];
        $where='user_id in ("'. implode('","', array_keys($user_id_arr)).'")';
        $wrong_user_num=[];
        if($user_id_arr&& is_array($user_id_arr)){
            foreach ($user_id_arr as $k=>$v){
                if(isset($post['topic_id'])&&$post['topic_id']){
                    $wrong_user_num[$k]=Db::name('user_exam_detail')->where(['user_id'=>$k,'is_right'=>'0','topicId'=>$post['topic_id']])->count();
                }else{
                    $wrong_user_num[$k]=Db::name('user_exam_detail')->where(['user_id'=>$k,'is_right'=>'0'])->count();
                }
                
            }
        }
        $data=[];
        $data['post']=$post;
        $data['user']=$userList['data'];
        $data['wrong_user_num']=$wrong_user_num;
        $data['topicList']=$topicList;
        $body=$this->fetch('',$data);
        echo $body;die;
    }

public function printTbb($user_id,$topic,$course_id){
        
        $topicId=$topic;
        $course_name= input('course_name', '');
//        foreach ($classAndCourse as $key => $value) {
//            foreach ($value as $k => $v) {
//                foreach($v as $kk=>$vv){
//                    if($vv['course_id']==$course_id)$course_name=$vv['course_name'];
//                }
//            }
//        }
        $this->assign('course_name', $course_name);
        
        ////////////////////分配现行测试掌握情况/////////////////////////////
        $algoLogic = new \service\algo\AlgoLogic();
        $weakElements = $algoLogic->getWeakElements($user_id, $topic);
        $knowledge_service = new \service\services\KnowledgeService();
        $knowledgeList =  $knowledge_service->getKnowledgeListByTopicId($topic);
        $total_knowledge_num = count($knowledgeList);  //总知识点数量.
        $weakElements_num = count($weakElements); //薄弱知识点数量.
        $this->assign('total_knowledge_num', $total_knowledge_num);
        $this->assign('weakElements_num', $weakElements_num);
        $has_learned_num = $total_knowledge_num - $weakElements_num;  //已学会知识点
        $this->assign('has_learned_num', $has_learned_num);
        ////////////////////分配现行测试掌握情况////////////////////////////
        
        //////////////////正答率//////////////////////////
        $datishu=Db::name('user_exam_detail')->where(['user_id'=>$user_id,'topicId'=>$topic])->count();
        $dacuoshu=Db::name('user_exam_detail')->where(['user_id'=>$user_id,'topicId'=>$topic,'is_right'=>'0'])->count();
        $this->assign('datishu', $datishu);
        $this->assign('dacuoshu', $dacuoshu);
        $this->assign('daduishu', $datishu-$dacuoshu);
        //////////////////正答率////////////////////////
        
         ///////////////////获取薄弱知识点的名字/////////////////////
        $weakElements_tag_name=[];
         //总的知识点 剔除为掌握的
        $new_knowledgeList=$knowledgeList;
        if($weakElements&&is_array($weakElements)){
            $knowledgeService=new \service\services\KnowledgeService();
            foreach($weakElements as $k=>$v){
                $weakElements_tag_name[]=$knowledgeService->getKnowledgeByCode($v,$topicId,false);
                foreach ($new_knowledgeList as $key => $value) {
                    if($value['tag_code']==$v)unset ($new_knowledgeList[$key]);//剔除为掌握的
                }
            }
        }
        $this->assign("knowledgeList_tag_name", $new_knowledgeList);//已经掌握的知识点
        $this->assign("weakElements_tag_name", $weakElements_tag_name);
        /////////////////获取薄弱知识点的名字end//////////////////////
        ////////////////////////////知识点列表//////////////////////////////////////
        $tag_codes=Db::name('user_exam_detail')->where(['user_id'=>$user_id,'topicId'=>$topic])->column('tag_code','tag_code');
        $knowledge_name_arr=[];
        foreach ($tag_codes as $key => $value) {
            $knowledgeService=new \service\services\KnowledgeService();
            $getKnowledgeByCode=$knowledgeService->getKnowledgeByCode($value,$topicId);
            $knowledge_name_arr[$value]=['tag_info'=>$getKnowledgeByCode,'datishu'=>Db::name('user_exam_detail')->where(['user_id'=>$user_id,'topicId'=>$topic,'tag_code'=>$value])->count(),'dacuoshu'=>Db::name('user_exam_detail')->where(['user_id'=>$user_id,'topicId'=>$topic,'tag_code'=>$value,'is_right'=>'0'])->count()];
        }
        $this->assign("knowledge_name_arr", $knowledge_name_arr);
        
        //分配已经掌握和没有掌握的
        //
        ///////////////////获取薄弱知识点的名字/////////////////////
        $weakElements_tag_name=[];
         //总的知识点 剔除为掌握的
        $new_knowledgeList=$knowledgeList;
        if($weakElements&&is_array($weakElements)){
            $knowledgeService=new KnowledgeService();
            foreach($weakElements as $k=>$v){
                $weakElements_tag_name[]=$knowledgeService->getKnowledgeByCode($v,$topicId);
                foreach ($new_knowledgeList as $key => $value) {
                    if($value['tag_code']==$v)unset ($new_knowledgeList[$key]);//剔除为掌握的
                }
            }
        }
        $this->assign("knowledgeList_tag_name", $new_knowledgeList);//已经掌握的知识点
        $this->assign("weakElements_tag_name", $weakElements_tag_name);
        
        
        /////////////////////////////知识点列表////////////////////////////////////
        $datilist=Db::name('user_exam_detail')->where(['user_id'=>$user_id,'topicId'=>$topic])->select();
        $getQuestionById=[];
        $qid_tag_name=[];
        foreach($datilist as $k=>$v){
            $QuestionService=new QuestionService();
            $getQuestionById[$v['question_id']]=$QuestionService->getQuestionById($v['question_id']);
            $knowledgeService=new \service\services\KnowledgeService();
            $qid_tag_name[$v['question_id']]=$knowledgeService->getKnowledgeByCode($v['tag_code'],$topicId);
        }
        $this->assign("qid_tag_name", $qid_tag_name);
        $this->assign("getQuestionById", $getQuestionById);
        $this->assign("datilist", $datilist);
        if($datilist==false)return $this->fetch ('tip');
        return $this->fetch();
    }
    
    public function errQuestion($user_id){

        $q_forms='2';
        $request = Request::instance();
        $username=$request->param("username");
        $topic=$request->param("topic");
        $topicList=[];
        if($topic)
        {
            $topicList=explode("|",$topic);
        }
        if($request->param('q_forms'))$q_forms=$request->param('q_forms');
        $map=['is_right'=>'0','user_id'=>$user_id];
        if($topicList)
        {
            $map["topicId"]=array("in",$topicList);
        }
        if(input('topic_id', ''))$map["topicId"]=input('topic_id', '');
        if(input('topic_id', '')){
            $map['topicId']= input('topic_id');
            $data=Db::name('user_exam_detail')->where($map)->paginate(60,false, ['query'=>input()]);
        }else{
            $data=Db::name('user_exam_detail')->where($map)->paginate(60,false, ['query'=>input()]);
        }
        
        $data=[
            'list'=>$data->toarray(),
            'page'=>$data->render(),
        ];
        $user=Db::name('user')->where(['id'=>$user_id])->find();
        $this->assign('user', $user);
        $this->assign('data', $data);
        $this->assign('username', $username);
        return $this->fetch("errquestion");
    }
    public function errQuestionxx($id){
        $data=Db::name('user_exam_detail')->where(['id'=>$id])->find();
        $this->assign('data', $data);
        return $this->fetch("errquestionxx");
    }
    
    public function detection($user_id,$topic_id){
        //user_exam_action_log
        $find=Db::name('user_exam_action_log')->where(['user_id'=>$user_id,'topicId'=>$topic_id])->order('id  DESC')->find();
        if($find==false)return $this->fetch ('no_detection');
        $module_type_title="";
        $action_log=Db::name('user_exam_action_log')->where(['user_id'=>$user_id,'topicId'=>$topic_id,'module_type'=>$find['module_type']])->order('id  DESC')->column('*');
        switch ($find['module_type']) {
            case 1:
                $module_type_title='先行测试';
                break;
            case 2:
                $module_type_title='边学边练';
                break;
            case 3:
                $module_type_title='竞赛拓展';
                break;
        }
        $this->assign('find', $find);
        $this->assign('module_type_title', $module_type_title);
        $this->assign('user_exam_action_log', array_values($action_log));
        //print_r($action_log);die;
        //判断3 
        //$exam_detail=Db::name('user_exam_detail')->where(['user_id'=>$user_id,'topicId'=>$topic_id,'module_type'=>3])->order('id DESC')->select();
//        if($exam_detail&&$find['module_type']!=3){
//            $this->assign('module_type_title', '竞赛拓展');
//        }else{
//            $exam_detail=Db::name('user_exam_detail')->where(['user_id'=>$user_id,'topicId'=>$topic_id,'module_type'=>$find['module_type']])->where('question_id in("'. implode('","', $action_log).'")')->column('*','question_id');
//        }
        //$this->assign('exam_detail', array_values($exam_detail));
        return $this->fetch ('');
    }
    public function errQuestionxx1($id){
        $this->assign('id', $id);
        return $this->fetch("errquestionxx1");
    }
}
