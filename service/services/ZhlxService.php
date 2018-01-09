<?php
namespace service\services;

use service\entity\User;
use service\org\util\TpString;
use  think\Db;
use think\Log;
use  service\algo\AlgoLogic;

use  service\services\QuestionService;


class ZhlxService extends CommonService
{

    public function __construct()
    {

    }


    /**
     * 获取综合练习的平均能力值.
     */
    public function  getZhlxAverageNum($topicId)
    {
        $user_id =$this->getUserId();
        $where['user_id']  = $user_id;
        $where['module_type'] =config("zhlx_module_type");
        $where['topicId'] = $topicId;
        $return_data = Db::name('user_ability')->field('ability')->where($where)->select();
        $num = count($return_data);
        $total_ability = 0;
        foreach ($return_data   as  $key=>$val) {
            $total_ability += $val['ability'];
        }
        if($total_ability==0)return 0;
        $averageNum = $total_ability/$num;
        return $averageNum;
    }
    /**
     * 获取综合练习的平均能力值.
     */
    public function  getL2ZhlxAverageNum($user_id,$topicId,$jingsai_module_type)
    {
        if(!$user_id)
        {
            $user_id =$this->getUserId();
        }
        $where['user_id']  = $user_id;
        $where['module_type'] =$jingsai_module_type;
        $where['topicId'] = $topicId;
        $return_data = Db::name('user_ability')->field('ability')->where($where)->select();
        $num = count($return_data);
        $total_ability = 0;
        foreach ($return_data   as  $key=>$val) {
            $total_ability += $val['ability'];
        }
        if($total_ability==0)return 0;
        $averageNum = $total_ability/$num;
        return $averageNum;
    }




}
