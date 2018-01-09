<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 17/3/30
 * Time: 下午3:48
 */


namespace service\services;

use service\entity\UserExamStepLog;
use service\log\LogService;
use think\Cache;
use service\api\PathManage;

class PathManageService  extends  CommonService
{

    private $path_module_map;
    private $domain;

    function __construct()
    {
        $this->domain = $_SERVER['SERVER_NAME'];
        $this->path_module_map =  [
            '001' =>array(
                'learn_code'=>'001',
                'url'=> "index/Index/preIndex",
                'module_type' => config('xiance_module_type'),
                'name'=>"先行测试"
            ),//先行测试
            '002' => array(
                'learn_code'=>'002',
                'url'=>"index/Bxbl/bIndex",
                'module_type'=>config('bxbl_module_type'),
                'name'=>"边学边练"
            ),//边学边练
            '003' => array(
                'learn_code'=>'003',
                'url'=>"index/Zhlx/zhlxquestion",
                'module_type'=>config('zonghe_module_type'),
                'name'=>"竞赛拓展"
            ),//综合测试
            '5' => array(
                'learn_code'=>'5',
                'url'=>"index/Mncs/mncsquestion",  
                'module_type'=>config('mncs_module_type'),
                'name'=>"模拟测试"
            ),//模拟测试
            '1' =>array(
                'learn_code'=>'001',
                'url'=> "index/Index/preIndex",
                'module_type' => config('xiance_module_type'),
                'name'=>"先行测试"
            ),//先行测试
            '2' => array(
                'learn_code'=>'002',
                'url'=>"index/Bxbl/bIndex",
                'module_type'=>config('bxbl_module_type'),
                'name'=>"边学边练"
            ),//边学边练
            '3' => array(
                'learn_code'=>'003',
                'url'=>"index/Zhlx/zhlxquestion",
                'module_type'=>config('zonghe_module_type'),
                'name'=>"竞赛拓展"
            ),//综合测试
            '5' => array(
                'learn_code'=>'5',
                'url'=>"index/Mncs/mncsquestion",  
                'module_type'=>config('mncs_module_type'),
                'name'=>"模拟测试"
            ),//模拟测试
            '8'=>array(
                'learn_code'=>'8',
                'url'=>"summer/Index/index",   //L1基础学习测试
                'module_type'=>8,
                'name'=>"L1基础学习测试"
            ),
            '9'=>array(
                'learn_code'=>'9',
                'url'=>"summer/Cindex/preIndex",   //L2学习测试的先行测试
                'module_type'=>9,
                'name'=>"L2学习测试的先行测试"
            ),
            '10'=>array(
                'learn_code'=>'10',
                'url'=>"summer/Cbxbl/bIndex",   //L2学习测试的边学边练（没有学习检测）
                'module_type'=>10,
                'name'=>"L2学习测试的边学边练（没有学习检测"
            ),
            '11'=>array(
                'learn_code'=>'11',
                'url'=>"summer/Czhlx/zhlxquestion",   //L2学习测试的竞赛拓展
                'module_type'=>11,
                'name'=>"L2学习测试的竞赛拓展"
            ),
            "12"=>array(
                'learn_code'=>'12',
                'url'=>"summer/Preview/bIndex",   //L2学习测试的竞赛拓展
                'module_type'=>12,
                'name'=>"暑期预习课"

            )

        ];

        $this->moduleType_relation_to_LearnCode = [
            config('xiance_module_type')=>"001",
            config('bxbl_module_type')=>"002",
            config('zonghe_module_type')=>"003",
            config('mncs_module_type')=>"5",
            '8'=>8,
            "9"=>9,
            "10"=>10,
            "11"=>11,
            "12"=>12
        ];
    }

    /**
     * 获取学习路径
     * @param $flow_id
     * @return mixed
     */
    public  function getPath($flow_id)
    {
        $path_manage_api = new PathManage();
        $data = $path_manage_api->getStudyPath($flow_id);
        return $data;
    }


    /**
     * 获取用户要做的下一个模块。
     * @param null $user_id
     * @param $topicId
     * @param $pre_module_type  ,如果有传递pre_module_type,那就获取这个模块后的,下一个模块。
     */
    public  function getUserNextModule($user_id=null, $topicId,$pre_module_type=null)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }

        $topic_service = new TopicService();
        $topicInfo = $topic_service->getTopicByTopicId($topicId);
        $path_module_map = $this->path_module_map;
        if(isset($topicInfo['flow_id']))
        {
            $flow_id = $topicInfo['flow_id'];
        }else{
            $flow_id =1;
        }
        $path_info =  $this->getPath($flow_id);
        $path_relation_module= $path_info['path_relation_module'];
        $return_data=[];//变量初始化
        //如果传pre_module
        if($pre_module_type)
        {
            $path_info =  $this->getPath($flow_id);
            $path_relation_module= $path_info['path_relation_module'];
            $moduleType_relation_to_LearnCode = $this->moduleType_relation_to_LearnCode;
            $now_learning_code = $moduleType_relation_to_LearnCode[$pre_module_type];
            $next_module_key=0;
            $total_path_module_num = count($path_relation_module);
            foreach ($path_relation_module as $key=>$val)
            {
                if($val['learning_code_num'] ==$now_learning_code)
                {
                    if($key==($total_path_module_num-1))
                    {
                        $next_module_key=$key;
                    }else{
                        $next_module_key =$key+1;
                    }
                    break;
                }
            }
            $next_path_code = $path_relation_module[$next_module_key]['learning_code_num'];
            $return_data = $path_module_map[$next_path_code];
            $return_data['url'] = "http://".$this->domain.url($return_data['url'],['topicId' => $topicId]);
        }else{    //如果没传,系统来判断用户要做那个知识点。
            $model = new UserExamStepLog();
            $condition["user_id"] = $user_id;
            $condition["topicId"] = $topicId;
            $condition["is_end"] = 1;
            $order = "etime desc";
            //查询最后做的记录
            $result = $model->where($condition)->order($order)->column('*');
            if ($result) {
                $step= array_values($result);
                $next_map=$this->getPath_next_module_map($path_info,$step);
                $next_path_code=$next_map;
            } else {
                $next_path_code = $path_relation_module[0]['learning_code_num'];
            }
            //判断下一个路径代号
            if(isset($path_module_map[$next_path_code])){
                $return_data = $path_module_map[$next_path_code];
                //判断url  module_type 是否为真 如果配置false 的情况下 视为代号结束
                if($return_data['url']&&$return_data['module_type']){
                    $return_data['url'] = "http://".$this->domain.url($return_data['url'],['topicId' => $topicId]);
                    $return_data['is_end'] = 0;
                }else{
                    $return_data['url']='';
                    $return_data['is_end'] = 1;
                }
            }else{
                $return_data['url'] = '';
                $return_data['is_end'] = 1;
            }
        }
        return  $return_data;
    }
    /**
     * 获取刚才做过的模块类型的路径下一个路径代号
     * @param type $module_type
     */
    protected function getPath_next_module_map($path_info,$step){
        $return_data=[];
        $path_module_map=$this->path_module_map;
        $module_type_arr = array();
        $new_step = array();
        foreach ($step as $key=>$val)
        {
            $module_type=$val['module_type'];
            if(!in_array($module_type,$module_type_arr))
            {
                $new_step[] = $val;
            }
            $module_type_arr[]=$module_type;
        }

        $nextk=count($new_step);//下一个索引
        $next_map=0;
        if(isset($path_info['path_relation_module'][$nextk]['learning_code_num']))$next_map=$path_info['path_relation_module'][$nextk]['learning_code_num'];
        return $next_map;
    }




    /**
     * 获取用户要做的下一个模块。
     * @param null $user_id
     * @param $topicId
     * @param $pre_module_type  ,如果有传递pre_module_type,那就获取这个模块后的,下一个模块。
     */
    public  function getUserSummerNextModule($user_id=null, $topicId,$pre_module_type=null)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }

        $topic_service = new TopicService();
//        $topic_v2_service = new TopicV2Service();
//        $topicInfo = $topic_v2_service->getTopicByTopicId($topicId);
        $topicInfo = $topic_service->getTopicByTopicId($topicId);

        $path_module_map = $this->path_module_map;
        if(isset($topicInfo['flow_id']))
        {
            $flow_id = $topicInfo['flow_id'];
        }else{
            $flow_id =1;
        }
        $path_info =  $this->getPath($flow_id);
        $path_relation_module= $path_info['path_relation_module'];
        $return_data=[];//变量初始化
        //如果传pre_module
        if($pre_module_type)
        {
            $path_info =  $this->getPath($flow_id);
            $path_relation_module= $path_info['path_relation_module'];
            $moduleType_relation_to_LearnCode = $this->moduleType_relation_to_LearnCode;
            $now_learning_code = $moduleType_relation_to_LearnCode[$pre_module_type];
            $next_module_key=0;
            $total_path_module_num = count($path_relation_module);
            foreach ($path_relation_module as $key=>$val)
            {
                if($val['learning_code_num'] ==$now_learning_code)
                {
                    if($key==($total_path_module_num-1))
                    {
                        $next_module_key=$key;
                    }else{
                        $next_module_key =$key+1;
                    }
                    break;
                }
            }
            $next_path_code = $path_relation_module[$next_module_key]['learning_code_num'];
            $return_data = $path_module_map[$next_path_code];
            $return_data['url'] = "http://".$this->domain.url($return_data['url'],['topicId' => $topicId]);
        }else{    //如果没传,系统来判断用户要做那个知识点。
            $model = new UserExamStepLog();
            $condition["user_id"] = $user_id;
            $condition["topicId"] = $topicId;
            $condition["is_end"] = 1;
            $order = "etime desc";
            //查询最后做的记录
            $result = $model->where($condition)->order($order)->column('*');
            if ($result) {
                $step= array_values($result);
                $next_map=$this->getPath_next_module_map($path_info,$step);
                $next_path_code=$next_map;
            } else {
                $next_path_code = $path_relation_module[0]['learning_code_num'];
            }
            $path_relation_module_num = count($path_relation_module);
            $user_learned_num= count($result);
            $result_arr = array_merge($result,array());

            if(empty($result_arr))
            {
                $is_end =  0;
            }else{
                $is_end = $result_arr[0]['is_end'];
            }
            if($path_relation_module_num==$user_learned_num && $is_end)
            {
                //如果做完了，先跳转到最后的模块地址。
                $pre_module_type = $result_arr[0]['module_type'];
                $moduleType_relation_to_LearnCode = $this->moduleType_relation_to_LearnCode;
                $now_learning_code = $moduleType_relation_to_LearnCode[$pre_module_type];
                $return_data = $path_module_map[$now_learning_code];
                $return_data['url'] = "http://".$this->domain.url($return_data['url'],['topicId' => $topicId]);
                $return_data['is_end'] = 0;

            }else{
                //判断下一个路径代号
                if(isset($path_module_map[$next_path_code])){
                    $return_data = $path_module_map[$next_path_code];
                    //判断url  module_type 是否为真 如果配置false 的情况下 视为代号结束
                    if($return_data['url']&&$return_data['module_type']){
                        $return_data['url'] = "http://".$this->domain.url($return_data['url'],['topicId' => $topicId]);
                        $return_data['is_end'] = 0;
                    }else{
                        $return_data['url']='';
                        $return_data['is_end'] = 1;
                    }
                }else{
//                $return_data['url'] = '';
//                $return_data['is_end'] = 1;
                    $result = array_merge($result,array());
                    $last_path_code = $result[0]['module_type'];
                    $return_data = $path_module_map[$last_path_code];
                    $return_data['url'] = "http://".$this->domain.url($return_data['url'],['topicId' => $topicId]);
                    $return_data['is_end'] = 0;
                }
            }
        }
        return  $return_data;
    }


}
