<?php
namespace service\services;

/**
 * 备课管理 of Preparelessons
 *
 * @author zhangqiquan
 */
class Preparelessons {
    public static function getPrepareContent($tid,$page=1,$module_tyle_id=0,$pageRows=20){
        $url= config('question_server_host');
        //index/api/getQuestionByTid/tid/32/page/1/pageRows/20
        $url.='index/api/getQuestionByTid';
        $param=[];
        $param['tid']=$tid;
        $param['page']=$page;
        if($module_tyle_id)$param['module_id']=$module_tyle_id;
        $param['pageRows']=$pageRows;
        $param['type']=3;
        $return_data = rpc_request($url, $param);
        return $return_data;
    }
}
