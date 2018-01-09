<?php
namespace service\services;
use  think\Db;
/**
 * Description of SteplogService
 *
 * @author Administrator
 */
class SteplogService {
    /**
     * 插入user_exam_step_log 记录
     * @param type $get $_GET的数据
     * @param type $module_type 专题类型
     * @return type
     */
    public function insert($get,$module_type){
        $id=\think\Db::name('user_exam_step_log')->insert(['user_id'=>\think\Session::get('userInfo.user_id'),'topicId'=>$get['topicId'],'module_type'=>$module_type,'is_end'=>'1','etime'=>  time()]);
        return $id;
    }
    /**
     * 查询一行user_exam_step_log 数据
     * @param type $where 查询条件
     * @return type  
     */
    public function getRow($where){
        $row=\think\Db::name('user_exam_step_log')->where($where)->field('is_end,module_type')->find();
        return $row;
    }
}
