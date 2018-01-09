<?php
namespace app\teacher\controller;
use think\Controller;
use service\services\QuestionService;
/**
 * Description of Useranswer
 *
 * @author zhangqiquan
 */
class Useranswer extends Controller{
    public function examDetail($user_id=0,$module_type=1,$topicId=9,$limit=15){
        $question_service=new QuestionService();
        if(input('export')){
            $dataExport=$question_service->getUseranswerExamDetailExport($user_id,$module_type,$topicId);
            $download=$this->fetch('export',$dataExport);
//            $file_name=date('YmdHis').'.xls';
//            header("Content-type:application/vnd.ms-excel");
//            header("Content-Disposition:filename={$file_name}");
            $file_name=date('YmdHis').'.html';
            header("Content-type: text/plain");
            header("Accept-Ranges: bytes");
            header("Content-Disposition: attachment; filename=".$file_name);
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header("Pragma: no-cache" );
            header("Expires: 0" ); 
            echo $download;
            die;
        }
        $data=$question_service->getUseranswerExamDetail($user_id,$module_type,$topicId,$limit);
        return $this->fetch('',$data);
    }
    
}
