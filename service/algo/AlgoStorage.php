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

class AlgoStorage implements StorageiTemplate
{
    private $call_algo_kstmode = "call_algo_kstmode";
    private $call_algo_nlix = "call_algo_nlix";
    private $call_algo_abilityx = "call_algo_abilityx";
    private $call_algo_kstability = "call_algo_kstability";

    /**
     * 记录先行测试的响应数据
     * @param $topicId
     * @param $request_data
     * @param $response_data
     */
    public function saveXianceLog($topicId, $module_type, $kmap_code, $request_data, $response_data)
    {
        $request_api = $this->call_algo_kstability;

        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
        $data = array(
            'user_id' => $user_id,
            'request_api' => $request_api,
            'topicId' => $topicId,
            'module_type' => $module_type,
            'kmap_code' => $kmap_code,
            'request_data' => json_encode($request_data),
            'response_data' => json_encode($response_data),
            'ctime' => time()
        );

        Db::name('user_algo_request_log')->insert($data);
    }


    public function saveAlgoLog($request_api,$topicId, $module_type, $kmap_code, $request_data, $response_data)
    {
        $userInfo = session('userInfo');
        $user_id = $userInfo['user_id'];
        $data = array(
            'user_id' => $user_id,
            'request_api' => $request_api,
            'topicId' => $topicId,
            'module_type' => $module_type,
            'kmap_code' => $kmap_code,
            'request_data' => json_encode($request_data),
            'response_data' => json_encode($response_data),
            'ctime' => time()
        );

        Db::name('user_algo_request_log')->insert($data);
    }




    /**
     * 更新用户的做题过程中算法调用结果状态.
     */
    public function updateUserAbilityStatus($user_id, $topicId, $weak_elems, $module_type, $last_tag_code, $usr_ans = 0,$likelihood)
    {
        //插入数据.
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['weak_elements'] = $weak_elems;
        $data['module_type'] = $module_type;
        $data['etime'] = time();
        $data['likelihood'] = $likelihood;
        if($last_tag_code!="-1")
        {
            $data['last_tag_code'] = $last_tag_code;
        }

        if (empty($usr_ans)) {
            $data['last_answer_isRight'] = 0;
        } else {
            $data['last_answer_isRight'] = (int)$usr_ans;
        }
        // 查询条件.
        $where['topicId'] = $topicId;
        $where['user_id'] = $user_id;
        $where['module_type'] = $module_type;
        $where['last_tag_code'] = $last_tag_code;

        Log::record("------where------".json_encode($where)."------where");


        $isHas = Db::name('user_ability_status')->where($where)->find();
        Log::record("------where------111111------where");

        if ($isHas) {
            Log::record("------where------2222------where");

            Db::name('user_ability_status')->where($where)->update($data);
        } else {
            Log::record("------where------3333------where");

            Db::name('user_ability_status')->insert($data);
        }
    }


    /**
     * 更新用户的能力值.
     */
    public function updateAbility($user_id, $topicId, $module_type, $tag_code, $algo_abilityx_return_data,$submodule_type=0)
    {

        if ($algo_abilityx_return_data['ability'] === null) {
            Log::record("------error-------算法返回能力值为NULL");
            $algo_abilityx_return_data['ability'] = 0;
        }
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['tag_code'] = $tag_code;
        $data['module_type'] = $module_type;
//        $data['submodule_type'] = $submodule_type;
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
     * 或得用户算法测试过程中的状态值.
     */
    public function getUserAbilityStatus($user_id, $topicId,$module_type)
    {
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] =$module_type;
        $user_ability_status = Db::name("user_ability_status")->order('id desc')->where($where)->find();
        return $user_ability_status;
    }


    /**
     * 或得用户算法测试过程中的状态值.
     */
    public function getUserAbilityStatusResult($user_id, $topicIds,$module_type=null)
    {
        $where['user_id'] = $user_id;
        $where['topicId'] = array("in",$topicIds);
        if($module_type)
        {
            $where['module_type'] =$module_type;
        }
        $user_ability_status = Db::name("user_ability_status")->where($where)->select();
        return $user_ability_status;
    }


    /**
     *
     * @param $usr_id
     * @param $topicId
     * @param $pre_knode
     * @param $module_type
     * @param $response_data
     */
    public function saveAlgoGetTagCodeLog($usr_id, $topicId, $pre_knode, $module_type, $response_data)
    {
        if (empty($pre_knode)) {
            $pre_knode = "";
        }

        $data['user_id'] = $usr_id;
        $data['topicId'] = $topicId;
        $data['pre_knode'] = $pre_knode;
        $data['module_type'] = $module_type;
        $data['init_kstatus'] = $response_data['init_kstatus'];
        $data['kmap_code'] = $response_data['kmap_code'];
        $data['knode_toaskq'] = $response_data['knode_toaskq'];
        $data['weak_elems'] = $response_data['weak_elems'];

        Db::name('user_algo_tagcode_log')->insert($data);

    }

    /**
     * 获取薄弱知识点.
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @return mixed
     */
    public function getWeakElements($user_id, $topicId)
    {
        $module_type = config('xiance_module_type');
        $user_ability_status = $this->getUserAbilityStatus($user_id, $topicId,$module_type);
        $weak_elements = $user_ability_status['weak_elements'];
        return $weak_elements;
    }

    /**
     * 获取用户的能力值.
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $tag_code
     */
    public function getUserAbility($user_id, $topicId, $module_type, $tag_code)
    {
        $userAbitlityInfo = $this->getUserAbilityInfoByTagCode($user_id, $topicId, $module_type, $tag_code);

        if (empty($userAbitlityInfo)) {
            $user_ability = 0.5;   //  知识点没有能力值的话,默认给0.5,这种情况下,一般都是切换知识点的时候,知识点还没有能力值。
        } else {
            $user_ability = $userAbitlityInfo['ability'];
        }
        return $user_ability;
    }

    /**
     * 获取 L2 薄弱知识点.
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @return mixed
     */
    public function getL2WeakElements($user_id, $topicId)
    {
        $module_type = config('l2_xiance_module_type');
        $user_ability_status = $this->getUserAbilityStatus($user_id, $topicId,$module_type);

        $weak_elements = $user_ability_status['weak_elements'];

        return $weak_elements;
    }

}
