<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/8/17
 * Time: 上午10:23
 */
namespace service\algo;

use think\Db;

use think\Log;

class SummerAlgoStorage implements StorageiTemplate
{

    public function saveXianceLog($topicId, $module_type, $kmap_code, $request_data, $response_data)
    {
        // TODO: Implement saveXianceLog() method.
    }




    /**
     * 更新用户的能力值.
     */
    public function updateAbility($user_id, $topicId, $module_type,$submodule_type,$grandson_module_type, $tag_code, $algo_return_data)
    {

        if ($algo_return_data['ability'] === null) {
            Log::record("------error-------算法返回能力值为NULL");
            $algo_return_data['ability'] = 0;
        }
        $ability = round($algo_return_data['ability'], 2);

        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['tag_code'] = $tag_code;
        $data['module_type'] = $module_type;
        $data['submodule_type'] = $submodule_type;
        $data['grandson_module_type'] = $grandson_module_type;
        $data['ability'] = $ability;
        $data['likelihood'] = "";
        if( !isset($algo_return_data['abilityprob'])|| $algo_return_data['abilityprob']==null)
        {
            $algo_return_data['abilityprob'] = "";
            Log::error("abilityprob 值错误.");
        }else{
            $data['abilityprob'] = $algo_return_data['abilityprob'];
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $where['grandson_module_type'] = $grandson_module_type;
        $where['tag_code'] = $tag_code;
        $isHas = Db::name('user_ability')->where($where)->find();
        if ($isHas) {
            $data['utime'] = time();
            Db::name('user_ability')->where($where)->update($data);

        } else {
            $data['ctime'] = time();
            Db::name('user_ability')->insert($data);
        }
    }



    public function updateUserAlgoStatus($user_id,$topicId,$module_type,$submodule_type,$batch_num,$algo_return_data)
    {
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $where['batch_num'] = $batch_num;
        //插入数据.
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['module_type'] = $module_type;
        $data['submodule_type'] = $submodule_type;
        $data['batch_num'] = $batch_num;
        $data['algo_session_id'] = $algo_return_data['session_id'];
        $data['next_node'] = $algo_return_data['next_node'];
        $data['measure_code'] = isset($algo_return_data['measure_code'])?$algo_return_data['measure_code']:"";
        $data['orign_weaks'] = json_encode($algo_return_data['orign_weaks']);
        $data['nlearn_weaks'] =json_encode( $algo_return_data['nlearn_weaks']);
        $data['measure_nodes'] =json_encode( $algo_return_data['measure_nodes']);
        $data['ctime'] = time();
        $isHas = Db::name('user_algo_status')->where($where)->find();
        if (!$isHas) {
            Log::record("------where------3333------where");
            Db::name('user_algo_status')->insert($data);
        }

    }


    /**
     * 在后测阶段更新能力值。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $submodule_type
     * @param $batch_num
     * @param $algo_return_data
     */
    public function updateUserAlgoStatusForBt($user_id,$topicId,$module_type,$submodule_type,$batch_num,$algo_return_data)
    {

        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['submodule_type'] = $submodule_type;
        $where['batch_num'] = $batch_num;
        //插入数据.
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['module_type'] = $module_type;
        $data['submodule_type'] = $submodule_type;
        $data['batch_num'] = $batch_num;
        $data['algo_session_id'] = $algo_return_data['session_id'];
        $data['next_node'] = $algo_return_data['next_node'];
        $data['nlearn_weaks'] =json_encode( $algo_return_data['weak_nodes']);
        $data['ctime'] = time();
        $isHas = Db::name('user_algo_status')->where($where)->find();
        if (!$isHas) {
            Log::record("------where------3333------where");
            Db::name('user_algo_status')->insert($data);
        }


    }






    /**
     *
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $submodule_type
     * @param $algo_return_data
     */
    public function updateUserAlgoBtStatus($user_id,$topicId,$module_type,$batch_num,$algo_return_data)
    {
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['batch_num'] = $batch_num;

        //插入数据.
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['batch_num'] = $batch_num;
        $data['module_type'] = $module_type;
        $data['algo_session_id'] = $algo_return_data['session_id'];
        $data['kmap_code'] = $algo_return_data['nmap_code'];
        $data['nodes'] =json_encode( $algo_return_data['nodes']);
        $data['ctime'] = time();
        $isHas = Db::name('user_algo_bt_status')->where($where)->find();
        if (!$isHas) {
            Db::name('user_algo_bt_status')->insert($data);
        }
    }






    /**
     * 获取用户算法的特定模块的最终数据格式。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getUserAlgoStatus($user_id, $topicId, $module_type,$batch_num)
    {
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] =$module_type;
        $where['batch_num']  =$batch_num;
        $user_algo_status = Db::name("user_algo_status")->order('id desc')->where($where)->find();
        return $user_algo_status;


    }



    /**
     * 获取薄弱知识点.
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @return mixed
     */
    public function getWeakElements($user_id, $topicId,$module_type,$sub_module_type,$batch_num)
    {
        $user_algo_status = $this->getUserAlgoStatus($user_id,$topicId,$module_type,$sub_module_type,$batch_num);
        $weak_elements = $user_algo_status['nlearn_weaks'];
        return $weak_elements;
    }
    /**
     * 获取原始薄弱知识点.
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @return mixed
     */
    public function getOrignWeakElements($user_id, $topicId,$module_type,$sub_module_type,$batch_num)
    {
        $user_algo_status = $this->getUserAlgoStatus($user_id,$topicId,$module_type,$sub_module_type,$batch_num);
        $weak_elements = $user_algo_status['orign_weaks'];
        return $weak_elements;
    }


    /**
     * 暑期课算法获取能力值。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $tag_code
     * @return float
     */
    public function getUserAbility($user_id, $topicId, $module_type, $tag_code)
    {
        $userAbitlityInfo = $this->getUserFinalAbilityInfoByTagCode($user_id, $topicId, $module_type, $tag_code);
        if (empty($userAbitlityInfo)) {
            $user_ability = 0.5;   //  知识点没有能力值的话,默认给0.5,这种情况下,一般都是切换知识点的时候,知识点还没有能力值。
        } else {
            $user_ability = round($userAbitlityInfo['ability'], 2);
            if ($user_ability == 0) {
                $user_ability = 0.5;
            }
            //要做去重，只用最新的能力值。
        }
        return $user_ability;
    }


    /**
     * 获取用户的能力值信息.
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $tag_code
     */
    public function getUserFinalAbilityInfoByTagCode($user_id, $topicId, $module_type, $tag_code)
    {
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['tag_code'] = $tag_code;
        $user_ability_info = Db::name('user_ability')->order('id desc')->where($where)->find();
        return $user_ability_info;
    }


    /**
     * 获取用户的能力值信息.
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $tag_code
     */
    public function getUserAbilityInfoByTagCode($user_id, $topicId, $module_type, $tag_code)
    {

        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['tag_code'] = $tag_code;
        $user_ability_info = Db::name('user_ability')->where($where)->find();
        return $user_ability_info;
    }


    /**
     * 更新用户的能力值.
     */
    public function updateAbilityForSpring($user_id, $topicId, $module_type, $tag_code, $algo_abilityx_return_data)
    {

        if ($algo_abilityx_return_data['ability'] === null) {
            Log::record("------error-------算法返回能力值为NULL");
            $algo_abilityx_return_data['ability'] = 0;
        }
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['tag_code'] = $tag_code;
        $data['module_type'] = $module_type;
        $data['ability'] = $algo_abilityx_return_data['ability'];
        if( !isset($algo_abilityx_return_data['likelihood'])|| $algo_abilityx_return_data['likelihood']==null)
        {
            $algo_abilityx_return_data['likelihood'] = "";
            Log::error("算法返回likelihood 值错误.");
        }else{
            $data['likelihood'] = $algo_abilityx_return_data['likelihood'];
        }
        if( !isset($algo_abilityx_return_data['abilityprob'])|| $algo_abilityx_return_data['abilityprob']==null)
        {
            $algo_abilityx_return_data['abilityprob'] = "";
            Log::error("abilityprob 值错误.");
        }else{
            $data['abilityprob'] = $algo_abilityx_return_data['abilityprob'];
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['tag_code'] = $tag_code;
        $isHas = Db::name('user_ability')->where($where)->find();
        if ($isHas) {
            Db::name('user_ability')->where($where)->update($data);

        } else {
            Db::name('user_ability')->insert($data);

        }
    }


}
