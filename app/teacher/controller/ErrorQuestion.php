<?php
namespace app\teacher\controller;
use service\services\UserService;
use think\Db;
use think\Request;
use service\services\QuestionService;
use service\algo\AlgoLogic;
use service\services\KnowledgeService;
class ErrorQuestion extends BaseController
{
    public function show(){
        $data=[];
        //分配班级
        //查询班级
        $class=$this->getClassList($this->user_id, 2);
        $data['class']=$class;
        return $this->fetch('classes', $data);
    }

    //班级错题显示页面
    public function classes(){
        return $this->fetch('index');
    }
    public function getList(){
        $post= input();
        if($data=cache(md5(json_encode(input())))){
            $body=$data;
            echo $body;die;
        }
        $userList=$this->getCourseUser($post['class_id'],2);
        $user_id_arr=$userList['data'];
        $user_id_arr=array_column($user_id_arr,'user_name', 'user_id');
        //print_r($user_id_arr);die;
        //获取当前用户下所有做过的题目
        $questions=[];
        $uids=array_keys($user_id_arr);
        $where='user_id in ("'. implode('","', array_keys($user_id_arr)).'")';
        //拿到所有的试题列表了
        $questions_answer_num=[];
        $questions_answer_right_num=[];
        $user_answer_num=[];
        $user_answer_right_num=[];
        if($user_id_arr){
            if($topic_id=input('topic_id','')){
                $questions=Db::name('user_exam_detail')->where(['topicId'=>$topic_id])->column('*');
                $new_questions=[];
                if($questions){
                    foreach ($questions as $key => $value) {
                        if(in_array($value['user_id'], $uids)){
                            if($value['is_right']==0)$new_questions[$key]=$value;
                            //大题数 打错数 用户的
                            if(isset($user_answer_num[$value['user_id']])==false)$user_answer_num[$value['user_id']]=0;
                            $user_answer_num[$value['user_id']]+=1;
                            if(isset($user_answer_right_num[$value['user_id']])==false)$user_answer_right_num[$value['user_id']]=0;
                            if($value['is_right'])$user_answer_right_num[$value['user_id']]+=1;
                            //大题数 打错数
                            
                            //这个题目总的答对数量 打错数量
                            if(isset($questions_answer_num[$key])==false)$questions_answer_num[$key]=0;
                            $questions_answer_num[$key]+=1;
                            if(isset($questions_answer_right_num[$key])==false)$questions_answer_right_num[$key]=0;
                            if($value['is_right'])$questions_answer_right_num[$key]+=1;
                            //这个题目总的答对数量
                        }
                    }
                }
                $questions=$new_questions;
            }else{
                //不带专题的查询
                $getTopicList=$this->getTopicList(input('module_id'));
                if(isset($getTopicList['data'])&& is_array($getTopicList['data'])){
                    $topic_ids=array_column($getTopicList['data'],'topic_id');
                    $where='topicId in ("'. implode('","', array_keys($topic_ids)).'")';
                    $questions=Db::name('user_exam_detail')->where($where)->column('*');
                    $new_questions=[];
                    if($questions){
                        foreach ($questions as $key => $value) {
                            if(in_array($value['user_id'], $uids)){
                                if($value['is_right']==0)$new_questions[$key]=$value;
                                //大题数 打错数 用户的
                                if(isset($user_answer_num[$value['user_id']])==false)$user_answer_num[$value['user_id']]=0;
                                $user_answer_num[$value['user_id']]+=1;
                                if(isset($user_answer_right_num[$value['user_id']])==false)$user_answer_right_num[$value['user_id']]=0;
                                if($value['is_right'])$user_answer_right_num[$value['user_id']]+=1;
                                //大题数 打错数

                                //这个题目总的答对数量 打错数量
                                if(isset($questions_answer_num[$key])==false)$questions_answer_num[$key]=0;
                                $questions_answer_num[$key]+=1;
                                if(isset($questions_answer_right_num[$key])==false)$questions_answer_right_num[$key]=0;
                                if($value['is_right'])$questions_answer_right_num[$key]+=1;
                                //这个题目总的答对数量
                            }
                        }
                    }
                    $questions=$new_questions;
                }
            }
            
            
//            if($questions&& is_array($questions)){
//                foreach ($questions as $key => $value) {
//                    $questions_answer_num[$key]=Db::name('user_exam_detail')->where($where)->where(['question_id'=>$key])->count();//答题数量
//                    $questions_answer_right_num[$key]=Db::name('user_exam_detail')->where($where)->where(['question_id'=>$key,'is_right'=>'1'])->count();//答对数量
//                }
//            }
//            //学生的答题数和答对数量
//            foreach ($user_id_arr as $k => $v) {
//                $user_answer_num[$k]=Db::name('user_exam_detail')->where(['user_id'=>$k])->count();//答题数量
//                $user_answer_right_num[$k]=Db::name('user_exam_detail')->where(['user_id'=>$k,'is_right'=>'1'])->count();//答对数量
//            }
        }
        $data=[];
        $data['post']=$post;
        $data['user']=$userList['data'];
        //拿到这个题目并且in学生列表的总题数量 再统计正确数量
        $data['questions']=$questions;
        $data['questions_answer_num']=$questions_answer_num;
        $data['questions_answer_right_num']=$questions_answer_right_num;
        //分配学生答题数和正答率
        $data['user_answer_num']=$user_answer_num;
        $data['user_answer_right_num']=$user_answer_right_num;
        echo '<div style="display:none">';
        print_r($data);
        echo '</div>';
        //print_r($data);die;
        $body=$this->fetch('',$data);
        cache(md5(json_encode(input())), $body, 5);
        echo $body;die;
    }
    //查看明细
    public function detail($question_id,$class_id,$topic_id=''){
        $post= input();
        $question=[];
        $user_answer_wrong_user=[];
        $user_answer_right_user=[];
        $user_exam_detail=[];
        if($topic_id){
            $user_answer_wrong_user=Db::name('user_exam_detail')->where(['question_id'=>$question_id,'topicId'=>$topic_id,'is_right'=>'0'])->column('is_right','user_id');
            $user_answer_right_user=Db::name('user_exam_detail')->where(['question_id'=>$question_id,'topicId'=>$topic_id,'is_right'=>'1'])->column('is_right','user_id');
            $user_exam_detail=Db::name('user_exam_detail')->where(['question_id'=>$question_id,'topicId'=>$topic_id])->order('id desc')->find();
        }else{
            //查询所有学生
            $userList=$this->getCourseUser($post['class_id'],2);
            $user_id_arr=$userList['data'];
            $user_id_arr=array_column($user_id_arr,'user_name', 'user_id');
            $where='user_id in ("'. implode('","', array_keys($user_id_arr)).'")';
            $user_answer_wrong_user=Db::name('user_exam_detail')->where($where)->where(['question_id'=>$question_id,'is_right'=>'0'])->column('is_right','user_id');
            $user_answer_right_user=Db::name('user_exam_detail')->where($where)->where(['question_id'=>$question_id,'is_right'=>'1'])->column('is_right','user_id');
            $user_exam_detail=Db::name('user_exam_detail')->where($where)->where(['question_id'=>$question_id])->order('id desc')->find();
        }
        $data['post']= $post;
        $data['user_answer_wrong_user']=$user_answer_wrong_user;
        $data['user_answer_right_user']=$user_answer_right_user;
        $data['user_exam_detail']=$user_exam_detail;
        $userList=$this->getCourseUser($post['class_id'],2);
        $user_id_arr=$userList['data'];
        $user_data=array_column($user_id_arr,'user_name', 'user_id');
        $data['user_data']=$user_data;
        return $this->fetch('', $data);
    }
    public function userDetail($id){
        $post= input();
        $question=[];
        $user_exam_detail=[];
        $data['post']= $post;
        $user_exam_detail=Db::name('user_exam_detail')->where(['id'=>$id])->find();
        $data['user_exam_detail']=$user_exam_detail;
        return $this->fetch('', $data);
    }

    //某个学生的错题
    public function person($user_id,$class_id,$username,$right_n,$wrong_n,$topic_id=''){
        $post= input();
        $user_exam_detail=[];
        $user_answer_wrong_num=[];
        $data['post']=$post;
        if($topic_id){
            $user_exam_detail=Db::name('user_exam_detail')->where(['topicId'=>$topic_id,'user_id'=>$user_id])->order('id desc')->column('*','question_id');
            $user_exam_detail1=Db::name('user_exam_detail')->where(['topicId'=>$topic_id,'user_id'=>$user_id])->order('id desc')->column('*','question_id');
            $user_answer_wrong_num=Db::name('user_exam_detail')->where(['topicId'=>$topic_id,'user_id'=>$user_id,'is_right'=>'0'])->count();
        }else{
            $user_exam_detail=Db::name('user_exam_detail')->where(['user_id'=>$user_id])->order('id desc')->column('*','question_id');
            $user_exam_detail1=Db::name('user_exam_detail')->where(['user_id'=>$user_id])->order('id desc')->column('*','question_id');
            $user_answer_wrong_num=Db::name('user_exam_detail')->where(['user_id'=>$user_id,'is_right'=>'0'])->count();
        }
        $data['user_exam_detail']=$user_exam_detail;
        $data['user_exam_detail1']=$user_exam_detail1;
        $data['user_answer_wrong_num']=$user_answer_wrong_num;
        
        $userList=$this->getCourseUser($post['class_id'],2);
        $user_id_arr=$userList['data'];
        $user_id_arr=array_column($user_id_arr,'user_name', 'user_id');
        $where='user_id in ("'. implode('","', array_keys($user_id_arr)).'")';
        $data['user_where']=$where;
        //
        $algoLogic = new AlgoLogic();
        $weakElements = $algoLogic->getWeakElements($user_id, $topic_id);
        $knowledge_service = new KnowledgeService();
        $knowledgeList =  $knowledge_service->getKnowledgeListByTopicId($topic_id);
        $data['weakElements']=$weakElements;//薄弱知识点
        $data['knowledgeList']=$knowledgeList;//所有的知识点
        $new_knowledgeList=$knowledgeList;
        $weakElements_tag_name=[];
        if($weakElements&&is_array($weakElements)){
            $knowledgeService=new KnowledgeService();
            foreach($weakElements as $k=>$v){
                $weakElements_tag_name[]=$knowledgeService->getKnowledgeByCode($v,$topic_id,false);
                foreach ($new_knowledgeList as $key => $value) {
                    if($value['tag_code']==$v)unset ($new_knowledgeList[$key]);//剔除为掌握的
                }
            }
        }
        $this->assign("knowledgeList_tag_name", $new_knowledgeList);//已经掌握的知识点
        $data['weakElements_tag_name']=$weakElements_tag_name;
        //
        return $this->fetch('',$data);
    }
    function printQuestion()
    {
        $request=Request::instance();
        $ids=$request->param("ids");
        $ids=explode("|",$ids);
        /////
        $ids= input('id/a', NULL);
        ////
        $data=[];
        if($ids)
        {
            $questionService=new QuestionService();
            $data=$questionService->getExamQuestionDetailByIds($ids);
        }
        $this->assign("data",$data);
        return $this->fetch("printQuestion");
    }
}
