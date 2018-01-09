<?php
namespace service\services;

use service\algo\AlgoStorage;
use service\entity\User;
use service\org\util\TpString;
use think\Cache;
use  think\Db;
use think\Log;
use  service\algo\AlgoLogic;
use think\Request;
use service\log\LogService;
use service\services\ApiGateService;

class UserService extends BaseUserService
{

    private $api_server_user;
    public function __construct()
    {
        //$this->api_server_user= config("api_server_user");
        $this->api_server_user=get_api_server_user();
    }


    /**
     * @api {api} /UserService/call_loginAuthorize 用户统一登陆api
     * @apiVersion 0.0.1
     * @apiName  call_loginAuthorize  用户登录
     * @apiGroup UserService
     * @apiParam {String} user_name   用户名 .
     * @apiParam {String} password  密码.
     * @apiParam {String} user_type  用户类型  5：学生 2：课程设计师 3：加盟校管理员 4：教师
     * @apiSuccess {String} code   状态值.
     * @apiSuccess {Array} data   会员数据的数组.
     * @apiSuccess {String} msg   消息类型.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *           'code' => 1
     *           'data' => Array
     *              ｛
     *                'user_id' => 4334
     *                'user_name' => yixue123
     *                'real_name' => yixue123
     *                 'school_id' => 1
     *                 'school_sn' => sh1
     *                 'school_name' => 智适应教育上海总部
     *                 'last_login_time' => 1477474158
     *                  'last_login_ip' => 116.226.37.252
     *               ｝
     *          'msg' => success
     *
     *     }
     */
    private static function call_loginAuthorize($user_name, $password, $user_type = 5)
    {
        $param['user_name'] = $user_name;
        $param['password'] = $password;
        $param['user_type'] = $user_type;
        ksort($param);
        $key = implode(";", $param);
        $key = $key . self::$api_auth_key;
        $key = md5($key);
        $param['key'] = $key;
        $url = self::$api_auth_url;
        $return_data = rpc_request($url, $param);
        return $return_data;
    }

    public function loginIn($username, $password)
    {
        $return_data = array(
            'isSuccess' => '',
            'err_code' => '',
            'err_info' => ''
        );
        $user = self::call_loginAuthorize($username, $password);
        if ($user['data']['user_id'] && $user['data']['user_name']) {
            $return_data['isSuccess'] = 1;
            $return_data['err_info'] = "登陆成功";
            $data = array(
                "user_id" => $user['data']['user_id'],
                "username" => $user['data']['user_name']
            );
            $this->setUserSessionInfo($data);
        } else {
            $return_data['isSuccess'] = 0;
            $return_data['err_info'] = "用户或者密码错误";
        }
        return $return_data;

    }

    public function loginInTest($username, $password)
    {
        $return_data = array(
            'isSuccess' => '',
            'err_code' => '',
            'err_info' => ''
        );
//        $user = User::get(['username' => $username,'code'=>$password]);
        $user = User::get(['username' => $username]);
        if ($user) {
            if ($user && $user->status == 0) {
                $return_data['isSuccess'] = 0;
                $return_data['err_info'] = "您的帐号处于冻结状态，请联系管理员解冻！";
            } elseif ($user && $user->status == 1) {
                if (md5($password . $user->code) != $user->password) {
                    $return_data['isSuccess'] = 0;
                    $return_data['err_info'] = "用户密码错误";
                } else {
                    $return_data['isSuccess'] = 1;

                    $data = array(
                        "user_id" =>$user->id,
                        "username" => $user->username,
                        "topic_id" => 9,//topicId默认为1
                        "debug"=>1
                    );
                    $this->setUserSessionInfo($data);
                }
            }
        } else {
            $return_data['isSuccess'] = 0;
            $return_data['err_info'] = "用户不存在";
        }

        return $return_data;

    }


    /**
     * 获取用户的SESSION信息
     */
    public function getUserSession()
    {

        $user_info = session('userInfo');
        return $user_info;

    }


    /**
     * 销毁用户的session信息.
     */
    public function destroyUserSession()
    {
        session('userInfo', null);
    }

    /**
     * 批量添加账号.
     */
    public function batchAddAccount()
    {
        $String = new TPString();
        for ($i = 1; $i <= 150; $i++) {
            $user = new User();
            if (strlen($i) == 1) {
                $username = 'test0' . $i;
            } else {
                $username = 'test' . $i;
            }
            $round_string = $String->randString(4, -1);
            $user->username = $username;
            $user->password = md5("123" . $round_string);
            $user->code = $round_string;
            $user->status = 1;
            $user->type = 3;
            $user->ctime = time();
            $user->remark = "测试帐号";
            $user->save();
        }
    }

    /**
     * 用户在单独的模块下,某知识点做的题数.
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @param $tag_code
     * @return mixed
     */
    public function getUserHasAnsweredNumForTagCode($user_id, $topicId, $module_type, $tag_code)
    {
        if (!$user_id) {
            $userInfo = session('userInfo');
            $user_id = $userInfo['user_id'];
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['tag_code'] = $tag_code;
        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();

        $num = count($answeredInfo);
        return $num;
    }


    /**
     * 根据模块获取用户在某模块下已做题数.
     * @param null $user_id
     * @param $topicId
     * @param $module_type
     * @return array
     */
    public function getUserHasAnsweredQuestionsByModule($user_id = null, $topicId, $module_type=null)
    {

        $return_arr = array();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if($topicId)
        {
            if(is_array($topicId))
            {
                $where['topicId'] = array("in",$topicId);
            }else
            {
                $where['topicId'] = $topicId;
            }

        }

        if($module_type)
        {
            $where['module_type'] = $module_type;
        }

        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();
        $question_service = new QuestionService();
        $api_gate_service  = new ApiGateService();
        //$kmap_code = config("kmap_code");
        $knowledgeService=new KnowledgeService();
        foreach ($answeredInfo as $key => $val) {
//            $return_arr[$key]['question_id'] = $val['question_id'];
            $return_info = array();
            $return_info = $api_gate_service->getQuestionById($val['question_id'],$topicId);
            $return_info['tag_code'] = $val['tag_code'];
            $return_info['module_type'] = $val['module_type'];
            $return_info['right_answer'] = $val['right_answer'];
            $return_info['user_answer'] = $val['user_answer'];
            $return_info['stime'] = $val['stime'];
            $return_info['ctime'] = $val['ctime'];
            // $tag = $question_service->getKnowlegeCode($kmap_code, $val['tag_code']);

            $knowledge_v2_service = new KnowledgeV2Service();
            $tag=$knowledge_v2_service->getKnowledgeByCode($val["tag_code"]);
            $tag_name="";
            if($tag)
            {
                $tag_name=$tag["tag_name"];
            }
            $return_info['tag_name'] = $tag_name;

            //$return_info['tag_name'] = $tag["name"];
            $return_info['is_view_analyze'] = $val["is_view_analyze"];
            $return_info['is_view_answer'] = $val["is_view_answer"];
            $return_info['exam_detail_id'] = $val["id"];
            $return_info['topicId'] = $val["topicId"];


            $return_info['right_answer_base64'] = $val['right_answer_base64'];

            // $return_info['user_answer_base64'] = $val['user_answer_base64'];

            $userAnswerBase64Arr = [];
            if ($val['user_answer_base64']) {
                $userAnswerBase64Arr = explode("@@@", $val['user_answer_base64']);

            }

            $return_info['user_answer_base64'] = $userAnswerBase64Arr;


            $return_info['is_right'] = $val['is_right'];
            $return_arr[] = $return_info;
//            $return_arr[$key]['is_right'] = $val['is_right'];
        }

        return $return_arr;
    }

    public function getUserHasAnsweredErrorQuestions($user_id = null, $topicId, $module_type=null,$isAll=true,$pageSize=null,$param=null)
    {

        $return_arr = array();
        $question_service = new QuestionService();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if($topicId)
        {
            if(is_array($topicId))
            {
                $where['topicId'] = array("in",$topicId);
            }else
            {
                $where['topicId'] = $topicId;
            }
        }



        if($module_type)
        {
            $where['module_type'] = $module_type;
        }

        if($isAll)
        {
            $answeredInfo = Db::name('user_exam_detail')->where($where)->where("is_right=0 or is_view_answer=1")->select();
            $page="";
            $total=count($answeredInfo);
        }else
        {
            $answeredInfo=Db::name('user_exam_detail')->where($where)->where("is_right=0 or is_view_answer=1")->fetchSql(false)->paginate($pageSize,false,$param);
            $page=$answeredInfo->render();
            $total=$answeredInfo->total();
        }
        $knowledgeService=new KnowledgeService();
        foreach ($answeredInfo as $key => $val) {
//            $return_arr[$key]['question_id'] = $val['question_id'];
            $return_info = array();
            $return_info = $question_service->getQuestionById($val['question_id']);
            $return_info['tag_code'] = $val['tag_code'];
            $return_info['module_type'] = $val['module_type'];
            $return_info['right_answer'] = $val['right_answer'];
            $return_info['user_answer'] = $val['user_answer'];
            $return_info['stime'] = $val['stime'];
            $return_info['ctime'] = $val['ctime'];
            /* $tag = $question_service->getKnowlegeCode($kmap_code, $val['tag_code']);
             $return_info['tag_name'] = $tag["name"];*/
            $tag_code = $val['tag_code'];
            $knowledge_v2_service = new KnowledgeV2Service();
            $tag=$knowledge_v2_service->getKnowledgeByCode($tag_code,$topicId);
            $tag_name="";
            if($tag)
            {
                $tag_name=$tag["tag_name"];
            }
            $return_info['tag_name'] = $tag_name;
            $return_info['is_view_analyze'] = $val["is_view_analyze"];
            $return_info['is_view_answer'] = $val["is_view_answer"];
            $return_info['exam_detail_id'] = $val["id"];
            $return_info['topicId'] = $val["topicId"];
            $return_info['user_id'] = $val["user_id"];


            $return_info['right_answer_base64'] = $val['right_answer_base64'];

            // $return_info['user_answer_base64'] = $val['user_answer_base64'];

            $userAnswerBase64Arr = [];
            if ($val['user_answer_base64']) {
                $userAnswerBase64Arr = explode("@@@", $val['user_answer_base64']);

            }

            $return_info['user_answer_base64'] = $userAnswerBase64Arr;


            $return_info['is_right'] = $val['is_right'];
            $return_arr[] = $return_info;
//            $return_arr[$key]['is_right'] = $val['is_right'];
        }

        $returnData=["data"=>$return_arr,"page"=>$page,"total"=>$total];
        return $returnData;
    }





    /**
     * 获取用户最后一个试题的答的对错.
     */
    public function getUserLastAnswerIsRight($user_id = null, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['user_id'] = $user_id;
        $return_data = Db::name('user_exam_detail')->field('is_right')->where($where)->order("id desc")->find();
        if (empty($return_data)) {
            $is_right = 0;
        } else {
            $is_right = $return_data['is_right'];
        }
        return $is_right;
    }



    public function getUserExamDetail($user_id=null,$topicId,$module_type){
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $data=Db::name('user_exam_detail')->where(['user_id'=>$user_id,'topicId'=>$topicId,'module_type'=>$module_type])->select();
        $return=['sum_num'=>0,'right_num'=>0];//正确和总数量
        foreach($data as $k=>$v){
            $return['sum_num']+=1;
            if($v['is_right']){
                $return['right_num']+=1;
            }
        }
        if($return['sum_num']==0)
        {
            $accuracy = 0;
        }else{
            $accuracy = $return['right_num']/$return['sum_num']*100;

        }
        return round($accuracy);
    }

    public function getUserAverageAbility($user_id = null, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $return_data = Db::name('user_ability')->field('ability')->where($where)->select();
        $num = count($return_data);
        $total_ability_num = 0;
        foreach ($return_data as $key => $val) {
            $total_ability_num += $val['ability'];
        }
        if($total_ability_num==0)return 0;
        $average_ability = $total_ability_num / $num;
        return $average_ability;
    }

    /**
     * 获取用户相关知识点的能力值
     * @param $user_id 用户id
     * @param $topicId 专题id
     * @param $module_type 模块类型
     * @return array
     */
    function getUserAbility($user_id, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $return_data = Db::name('user_ability')->where($where)->column("ability", "tag_code");
        return $return_data;
    }

    /**
     * 插入user_exam_step_log 记录
     * @param type $topicId
     * @param type $module_type 专题类型
     * @return type
     */
    public function insertUserStep($topicId, $module_type, $has_learned_all_tag_code, $is_end = 1)
    {
        $user_id = $this->getUserId();
        $data['user_id'] = $user_id;
        $data['topicId'] = $topicId;
        $data['module_type'] = $module_type;
        $data['is_end'] = $is_end;
        $data['etime'] = time();
        $data['has_learned_all_tag_code'] = $has_learned_all_tag_code;
        $find_data = Db::name('user_exam_step_log')->where(['user_id' => $user_id, 'topicId' => $topicId, 'module_type' => $module_type, 'is_end' => $is_end])->field('id')->find();

        if (empty($find_data)) {
            $id = Db::name('user_exam_step_log')->insert($data);
        } else {
            $id = $find_data['id'];
        }
        return $id;
    }

    /**
     * 查询一行user_exam_step_log 数据
     * @param type $where 查询条件
     * @return type
     */
    public function getUserStep($topicId, $user_id, $module_type = 1)
    {
        $where['topicId'] = $topicId;
        $where['user_id'] = $user_id;
        $where['module_type'] = $module_type;
        $row = Db::name('user_exam_step_log')->where($where)->find();
        $where['is_right']=0;
        $wrong_num=Db::name('user_exam_detail')->where($where)->count();
        if($row)$row['is_all_right']=!$wrong_num;
        return $row;
    }

    /**
     * 查询多行user_exam_step_log 数据
     * @param type $where 查询条件
     * @return type
     */
    public function getListUserStep($topicId, $user_id, $module_type = 1)
    {
        $where['topicId'] = $topicId;
        $where['user_id'] = $user_id;
        $where['module_type'] = $module_type;
        $list = Db::name('user_exam_step_log')->where($where)->column('is_end,module_type,id,is_all_right,user_id,topicId', 'module_type');
        if ($list) {
            foreach ($list as $k => $v) {
                if ($v['is_end'] == '1' && $v['is_all_right'] == '0') {
                    $wrong_count = Db::name('user_exam_detail')->where(['topicId' => $v['topicId'], 'module_type' => $v['module_type'], 'is_right' => '0', 'user_id' => $v['user_id']])->count();
                    if ($wrong_count == 0) {
                        //错误数量=0 全对了更新
                        Db::name('user_exam_step_log')->where($v)->update(['is_all_right' => '1']);
                        $list[$k]['is_all_right'] = 1;
                    } else {
                        Db::name('user_exam_step_log')->where($v)->update(['is_all_right' => '2']);
                        $list[$k]['is_all_right'] = 2;
                    }
                    //查询是否全对  如果全对 更新当前字段is_all_right =1 否则更新当前字段为2
                }
            }
        }
        return $list;
    }

    /**
     * 获取用户的下一个边学边练的知识点.
     */
    public function getUserBxblNextTagCode($kmap_code, $topicId, $used_type)
    {
        //如果已做此知识点的题,已经学完应学的数量,即开始推下一个知识点.没有学完的话,即还是推此知识点.
        //获取用户做的最后一个知识点.
        Log::record("------" . __FUNCTION__ . "---topicId---" . $topicId);
        $tag_code = $this->getUserLastTagCodeOfBxbl($topicId);
        Log::record("------" . __FUNCTION__ . "---tag_code---" . $tag_code);
        $module_type = config('gaoxiao_module_type');
        $hasLeardTagCode = $this->getUserHasLearnedTagCode("", $topicId, $module_type);
        $learned_elements = array();
        foreach ($hasLeardTagCode as $key => $val) {
            $learned_elements[] = $val['tag_code'];
        }
        $algologic = new AlgoLogic();

        //如果没有知识点,说明用户第一次做.故直接从算法获取知识点.
        if ($tag_code=="") {
            $return_tag_code = $algologic->get_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements);
            $tag_code = $return_tag_code['next_element'];
        } else {
            $return_data = $this->getUserHasAnsweredQuestions("", $tag_code, $topicId, $module_type);
            $num = count($return_data);
            Log::record("------" . __FUNCTION__ . "---num---" . $num);
            $need_num = config("to_learn_num");
            Log::record("------" . __FUNCTION__ . "---need_num---" . $need_num);
            if ($num >= $need_num) {
                $return_tag_code = $algologic->get_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements);
                $tag_code = $return_tag_code['next_element'];
            }
        }
        return $tag_code;
    }





    /**
     * 获取用户的下一个边学边练的知识点.
     */
    public function getUserBxblNextOnlyOneTagCode($kmap_code, $topicId, $used_type)
    {
        //如果已做此知识点的题,已经学完应学的数量,即开始推下一个知识点.没有学完的话,即还是推此知识点.
        //获取用户做的最后一个知识点.
        Log::record("------" . __FUNCTION__ . "---topicId---" . $topicId);
        $tag_code = $this->getUserLastTagCodeOfBxbl($topicId);
        Log::record("------" . __FUNCTION__ . "---tag_code---" . $tag_code);
        $module_type = config('gaoxiao_module_type');
        $hasLeardTagCode = $this->getUserHasLearnedTagCode("", $topicId, $module_type);
        $learned_elements = array();
        foreach ($hasLeardTagCode as $key => $val) {
            $learned_elements[] = $val['tag_code'];
        }
        $algologic = new AlgoLogic();

        $question_service = new QuestionService();
        //如果没有知识点,说明用户第一次做.故直接从算法获取知识点.
        if ($tag_code=="") {
            $return_tag_code = $algologic->get_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements);
            $tag_code = $return_tag_code['next_element'];
        } else {
            $user_has_learned_exam = $question_service->getUserHasAchieveTagCodeOrderByAsc($topicId, $module_type);
            $user_has_learned_first_tag_code = $user_has_learned_exam[0]['tag_code'];

            $return_data = $this->getUserHasAnsweredQuestions("", $user_has_learned_first_tag_code, $topicId, $module_type);
            $num = count($return_data);
            Log::record("------" . __FUNCTION__ . "---num---" . $num);
            $need_num = config("to_learn_num");
            Log::record("------" . __FUNCTION__ . "---need_num---" . $need_num);
            if ($num >= $need_num) {
//                $return_tag_code = $algologic->get_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements);
//                $tag_code = $return_tag_code['next_element'];

                $tag_code = -1;
            }
        }
        return $tag_code;
    }


    /**
     * 获取用户已答试题.
     * @param $user_id
     * @param $tag_code
     * @param $topicId
     * @param $module_type
     */
    public function getUserHasAnsweredQuestions($user_id, $tag_code, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['tag_code'] = $tag_code;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['user_id'] = $user_id;
        $return_data = Db::name('user_exam_detail')->where($where)->select();
        return $return_data;
    }

    /**
     * 根据特定条件获取用户已经学习的知识点.
     */
    public function getUserHasLearnedTagCode($user_id = null, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $question_service = new QuestionService();
        $batch_num  = $question_service->getBatchNum($topicId, $module_type);
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $where['batch_num'] = $batch_num;
        $return_data = Db::name('user_exam')->where($where)->field("tag_code")->select();
        return $return_data;
    }


    /**
     * 获取用户边学边练的最后一个知识点.
     */
    public function getUserLastTagCodeOfBxbl($topicId)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where["topicId"] = $topicId;
        $where['module_type'] = config('gaoxiao_module_type');
        Log::record("------" . __FUNCTION__ . "---topicId---" . $topicId);
//        $return_data = Db::name('user_bxbl_question')->where($where)->field('tag_code')->order("id desc")->find();
        $return_data = Db::name('user_exam_detail')->where($where)->field('tag_code')->order("id desc")->find();
        if (empty($return_data)) {
            $tag_code = "";
        } else {
            $tag_code = $return_data['tag_code'];
        }
        Log::record("------" . __FUNCTION__ . "---tag_code---" . $tag_code);
        return $tag_code;
    }

    /**
     * 添加用户
     * @param $userName
     * @param $password
     * @param $isBatch 是否批量生成
     * @return int
     */
    public function addUser($userName, $password, $start,$end, $type)
    {
        $String = new TpString();
        $user = new User();


        $remark = "测试账号";
        if ($type == 1) {
            $remark = "学生账号";
        }
        $data = [];

        for ($i = $start; $i <= $end; $i++) {
            $round_string = $String->randString(4, -1);
            $userPassword = md5($password . $round_string);

            if (strlen($i) == 1) {
                $username = $userName.'0' . $i;
            } else {
                $username = $userName.$i;
            }

            $data[] = array(
                "username" => $username,
                "password" => $userPassword,
                "code" => $round_string,
                "status" => 1,
                "type" => $type,
                "ctime" => time(),
                "remark" => $remark,
            );
        }
        $result = $user->saveAll($data);
        return $result;


    }

    /**
     * 未使用过的用户
     * @return mixed
     */
    public function unUsedUser($type, $param = null)
    {
        $map["user_id"] = array("exp", "is  null");
        if ($type) {
            $map["type"] = $type;
        }
        $result = Db::table('ct_user')
            ->alias('user')
            ->join(['user_exam_detail' => 'examDetail'], 'user.id = examDetail.user_id', 'Left')
            ->where($map)
            ->field("user.id,username,user.ctime,examDetail.user_id,user.status")
            ->order("user.id desc")//->fetchSql()
            ->paginate(config('list_rows'), false, array('query' => $param));
        return $result;

    }

    /**
     * 通过用户名查找用户
     * @param $userName
     *
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public function getUserByName($userName)
    {
        $user = new User();
        $map["username"] = $userName;
        $result = $user->db()->where($map)->find();
        return $result;
    }

    public function updateUserStepLog($user_id, $topicId, $module_type, $is_end)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }

        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $data['is_end'] = $is_end;
        $data['etime'] = time();
        Db::name('user_exam_step_log')->where($where)->update($data);
    }




    public function getUserIsAllRight($topicId, $module_type, $user_id)
    {
        $return = 0;
        $user_exam_step_log_end = Db::name('user_exam_step_log')->where(['topicId' => $topicId, 'module_type' => $module_type, 'user_id' => $user_id, 'is_end' => '1'])->count();//答题结束
        if ($user_exam_step_log_end > 0) {
            $is_right = Db::name('user_exam_detail')->where(['topicId' => $topicId, 'module_type' => $module_type, 'is_right' => '0', 'user_id' => $user_id])->count();//查询是否包含有错误的题 如果没有错误就是全部答对
            if ($is_right == 0) $return = 1;
        }
        return $return;
    }


    /**
     * 获取用户
     * @param null $userName
     * @param bool $isAll
     *
     * @return array
     */

    public function getUser($userName = null, $isAll = false)
    {

        $request = Request::instance();
        $param = $request->param();

        $map = [];
        if ($userName) {
            $map["username"] = array("like", "%$userName%");
        }
        if ($isAll) {
            $page=0;
            $result = DB::name("user")->where($map)->order("id desc")->select();
        } else {
            $result = DB::name("user")->where($map)->order("id desc")->paginate(config('list_rows'), false, array('query' => $param));
            $page = $result->render();
        }


        return ["data" => $result, "page" => $page];
    }

    /*
     * 判断用户先行测试是否全对.
     */
    public function getUserXianceIsAllRight($topicId)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where['module_type'] = 1;
        $where['topicId'] = $topicId;
        $return_data = Db::name('user_exam_step_log')->field('is_all_right')->where($where)->find();
        if (!empty($return_data)) {
            $is_all_right = $return_data['is_all_right'];
        } else {
            $is_all_right = 0;
        }
        return $is_all_right;

    }

    /**
     * 获取用户边学边练的知识点.
     */
    public function getUserlearnedKnowledgeNum($topicId)
    {
        $user_id = $this->getUserId();
        $ability_standard = config("ability_standard");
        $sql = "select * from ct_user_ability where user_id = $user_id  and module_type = 2 and topicId = $topicId and ability>=$ability_standard";
        $has_learned_knowledge = Db::query($sql);
        $num = count($has_learned_knowledge);
        return $num;
    }


    /**
     * 获取用户边学边练知识点掌握对比例.
     */
    public function getUserLearnedKnowledgeScaleForBxbl($topicId)
    {
        $algoLogic = new AlgoLogic();
        $weakElements = $algoLogic->getWeakElements("", $topicId);
        $weakElements_num = count($weakElements);
        $haslearnedKnowledgeNum = $this->getUserlearnedKnowledgeNum($topicId);
        $sale = $haslearnedKnowledgeNum / $weakElements_num;
        return $sale;
    }

    static public function getUid($username)
    {
        $data = Db::name('user')->where(['username' => $username])->field('id')->find();
        if ($data['id'] == false) return -1;
        return $data['id'];
    }

    /**
     * 获取用户已经学过的知识点
     * @param $user_id
     * @param $topic_id
     * @param $module_type
     * @param $submodule_type
     *
     * @return float|int
     */
    public function getHaveLeardTagCode($user_id, $topic_id, $module_type)
    {

        if ($user_id) {
            $map["user_id"] = $user_id;
        }
        if ($topic_id) {
            $map["topicId"] = $topic_id;
        }
        if ($module_type) {

            $map["module_type"] = $module_type;
        }
        $haveLearnedWeakElements = Db::name('user_exam')->distinct("tag_code")->where($map)->field('tag_code')->column('tag_code');
        return $haveLearnedWeakElements;

    }

    public function getLearned_num($user_id, $topicId, $module_type)
    {
        $where = [];
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $data = Db::name('user_ability')->where($where)->where('ability>=0.8')->column('*', 'tag_code');
        return $data;
    }

    public function getLearned_nums($user_id, $topicId, $knowledgeList = [])
    {
        $data_bxbl = $this->getLearned_num($user_id, $topicId, config('gaoxiao_module_type'));
        $bxblLearned_num = count($data_bxbl);//边学边练攻克数量
        $zhlxLearned_nums = $this->getLearned_num($user_id, $topicId, config('zonghe_module_type'));
        $key_knowledgeList = [];
        foreach ($knowledgeList as $k => $v) {
            if (isset($v['code'])) $key_knowledgeList[] = $v['code'];
        }
        foreach ($zhlxLearned_nums as $k1 => $v1) {
            if (!in_array($k1, $key_knowledgeList)) {//综合练习知识点不在总的知识点的情况下排除
                unset($zhlxLearned_nums[$k1]);
            }
        }
        $zhlxLearned_num = count($zhlxLearned_nums);//综合练习在总的知识点的攻克数量
        return $bxblLearned_num + $zhlxLearned_num;
    }


    /**
     * 批量冻结用户
     * @param $ids 用户id
     * @return int|string
     */
    public function freezeUser($ids)
    {
        $model=new User();
        $map["id"]=array("in",$ids);
        $data["status"]=0;
        $result=$model->where($map)->update($data);
        return $result;
    }

    /**
     * 批量解冻用户
     * @param $ids 用户ids
     * @return int|string
     */
    public function unFreezeUser($ids)
    {
        $model=new User();
        $map["id"]=array("in",$ids);
        $data["status"]=1;
        $result=$model->where($map)->update($data);
        return $result;
    }


    /**
     * 获取用户列表
     * @param $class_id 班级id
     * @param $isTest 是否用于（ErrorManagement)
     * @return array
     */
    function getUserList($class_id,$isTest)
    {
        if(!$isTest)
        {
            $data=[
                ["id"=>1,"name"=>"qinfangge"],
                ["id"=>2,"name"=>"qinfangge1"],
            ];
        }else
        {
            $result=$this->getUser($class_id,true);
            $data=[];
            foreach ($result["data"] as $item)
            {
                $data[]=["id"=>$item["id"],"name"=>$item["username"]];
            }
        }


        return $data;
    }

    /**
     * 获取班级列表
     * @param bool $isTest 是否用于（ErrorManagement)
     * @param null $userName 用户名
     * @return array
     */
    function getClassList($isTest=true,$userName=null)
    {
        if(!$isTest)
        {
            $data=[
                ["id"=>1,"name"=>"1班"],
                ["id"=>2,"name"=>"2班"]
            ];
        }else
        {
            $data=[
                ["id"=>$userName,"name"=>$userName],
            ];
        }

        return $data;
    }


    /**
     * 根据班级和课程获取用户和专题
     * @param $class_id 班级id
     * @param $course_id 课程id
     * @return array|mixed
     */
    private function getUserAndTopicsFromRemote($class_id,$course_id)
    {
        $cacheKey="getUserAndTopicsFromRemote:class_id:{$class_id}:course_id:{$course_id}";
        $retunData=Cache::get($cacheKey);
        if(empty($retunData)) {
            $url = $this->api_server_user . "/classs/getUserAndTopics/class_id/{$class_id}/course_id/{$course_id}";
            $return_data = rpc_request($url, null, "get");
            if ($return_data["data"]) {
                $retunData=$return_data["data"];
            } else {
                $retunData= [];
            }
            Cache::set($cacheKey,$retunData,3600);
        }
        /*$json='{"code":1,"data":{"user_list":[{"user_id":1106,"user_name":"qinfangge1","real_name":"学生001","class_id":527,"class_name":"寒假语文流程test","last_login_time":1483672824,"last_login_ip":"139.196.80.197"},{"user_id":9561,"user_name":"cnstudent01","real_name":"","class_id":527,"class_name":"寒假语文流程test","last_login_time":1483710629,"last_login_ip":"139.196.80.197"},{"user_id":1109,"user_name":"qinfangge2","real_name":"","class_id":527,"class_name":"寒假语文流程test","last_login_time":1483710825,"last_login_ip":"139.196.80.197"},{"user_id":9563,"user_name":"cnstudent03","real_name":"","class_id":527,"class_name":"寒假语文流程test","last_login_time":0,"last_login_ip":"0"}],"topics":[1,2]},"msg":"success"}';
        $retunData=json_decode($json,true);
        $retunData=$retunData["data"];*/
        return $retunData;
    }

    /**
     * 从天王星获取这个班并且学习这个课程的学生
     * @param $class_id 班级id
     * @param $course_id 课程id
     * @return array|mixed
     */
    function getUsersFromRemote($class_id,$course_id)
    {
        $data=$this->getUserAndTopicsFromRemote($class_id,$course_id);
        $users=[];
        if($data)
        {
            $users=$data["user_list"];
        }
        return $users;
    }

    /**
     * 从天王星获取专题列表
     * @param $class_id 班级id
     * @param $course_id 课程id
     * @return array|mixed
     */
    function getTopicListFromRemote($class_id,$course_id)
    {
        $data=$this->getUserAndTopicsFromRemote($class_id,$course_id);
        $topicList=[];
        if($data)
        {
            $topicList=$data["topics"];
        }
        return $topicList;
    }

    /**
     * 从天王星获取班级和课程
     * @return array|mixed
     */
    function getClassAndCourseFromRemote($user_id,$cid)
    {

        $cacheKey="getClassAndCourseFromRemote:teacher_id:{$user_id}";
        $returnData=Cache::get($cacheKey);
        $returnData=null;
        if(empty($returnData)) {
            $url = $this->api_server_user . "/course/getClassAndCourse/user_id/{$user_id}/cid/{$cid}";
            $return_data = rpc_request($url, null, "get");
            //
            $log_service = new  LogService();
            $topic= "info";
            if($return_data['data']==false){
                $topic= "error";
                $message  = "-----base---此接口没有数据--------".$url. json_encode($return_data,JSON_UNESCAPED_UNICODE).'session=='. json_encode(session(''), JSON_UNESCAPED_UNICODE);
                $log_service::sendMessage($topic,__METHOD__.$message);
            }else{
                $message  = "------base--------". json_encode($return_data,JSON_UNESCAPED_UNICODE);
                $log_service::sendMessage($topic,__METHOD__.$message);
            }
            //
            if ($return_data["data"]) {
                $returnData=$return_data["data"];

                $newReturnData=[];
                foreach ($returnData as $key=>$item)
                {

                    $course_list=[];
                    $class_info=["class_id"=>$item["class_info"]["class_id"],"class_name"=>$item["class_info"]["class_name"]];


                    foreach ($item["course_list"] as $courseListItem)
                    {
                        $course_list[]=  ["course_id"=>$courseListItem["course_id"],"course_name"=>$courseListItem["course_name"]];
                    }
                    $newReturnData[$key]=[
                        "class_info"=>$class_info,
                        "course_list"=>$course_list,
                    ];
                }
                $returnData=$newReturnData;
                Cache::set($cacheKey,$returnData,3600);
            } else {
                $returnData= [];
            }
        }
        return $returnData;
    }

    /**
     * 获取用户学习检测要学习的知识点。
     * @param  $user_id
     * @param $topicId
     * @return array
     */
    public function getUserDetectNeedToLearnTagCode($user_id = null,$topicId)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $detect_service = new DetectQuestionService();
        $detect_question_list = $detect_service->getDetectQuestions($topicId);

        return $detect_question_list;
    }


    /**
     * 获取用户答题的最后一条记录的信息。
     * @param $user_id
     * @param $topicId
     * @param $module_type
     */
    public function getUserLastExamInfo($user_id = null,$topicId,$module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $question_service = new  QuestionService();
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $user_last_exam_detail = Db::name('user_exam_detail')->where($where)->order('id desc')->find();
        if(!empty($user_last_exam_detail))
        {
            $question_id = $user_last_exam_detail['question_id'];
            $user_last_exam_detail['question_list'] = $question_service->getQuestionById($question_id);
        }
        return  $user_last_exam_detail;
    }

    public function getUserWeakElements($user_id, $topicIds,$module_type=null)
    {
        $weakElements=[];
        $model=new AlgoStorage();
        $result=$model->getUserAbilityStatusResult($user_id,$topicIds,$module_type);
        foreach ($result as $item)
        {
            $weakElementsOfTopic=json_decode($item["weak_elements"],true);
            if($weakElementsOfTopic)
            {
                foreach ($weakElementsOfTopic as $weakElementsOfTopicItem)
                {
                    if(!in_array($weakElementsOfTopicItem,$weakElements))
                    {
                        $weakElements[]=$weakElementsOfTopicItem;
                    }
                }
            }
        }
        return $weakElements;
    }

    /**
     * 获取用户的  L2  下一个边学边练的知识点.
     */
    public function getUserL2BxblNextTagCode($kmap_code, $topicId, $used_type)
    {
        //如果已做此知识点的题,已经学完应学的数量,即开始推下一个知识点.没有学完的话,即还是推此知识点.
        //获取用户做的最后一个知识点.
        Log::record("------" . __FUNCTION__ . "---topicId---" . $topicId);
        $tag_code = $this->getUserL2LastTagCodeOfBxbl($topicId);
        Log::record("------" . __FUNCTION__ . "---tag_code---" . $tag_code);
        $module_type = config('l2_bxbl_module_type');
        $hasLeardTagCode = $this->getUserHasLearnedTagCode("", $topicId, $module_type);
        $learned_elements = array();
        foreach ($hasLeardTagCode as $key => $val) {
            $learned_elements[] = $val['tag_code'];
        }
        $algologic = new AlgoLogic();

        //如果没有知识点,说明用户第一次做.故直接从算法获取知识点.
        if ($tag_code=="") {
            $return_tag_code = $algologic->get_l2_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements,$module_type);
            $tag_code = $return_tag_code['next_element'];
        } else {
            $return_data = $this->getUserHasAnsweredQuestions("", $tag_code, $topicId, $module_type);
            $num = count($return_data);
            Log::record("------" . __FUNCTION__ . "---num---" . $num);
            $need_num = config("to_learn_num");
            Log::record("------" . __FUNCTION__ . "---need_num---" . $need_num);
            if ($num >= $need_num) {
                $return_tag_code = $algologic->get_l2_bxbl_tagCode($kmap_code, $topicId, $used_type, $learned_elements,$module_type);
                $tag_code = $return_tag_code['next_element'];
            }
        }

        return $tag_code;
    }

    /**
     * 获取用户边学边练的最后一个知识点.
     */
    public function getUserL2LastTagCodeOfBxbl($topicId)
    {
        $user_id = $this->getUserId();
        $where['user_id'] = $user_id;
        $where["topicId"] = $topicId;
        $where['module_type'] = config('l2_bxbl_module_type');
        Log::record("------" . __FUNCTION__ . "---topicId---" . $topicId);
//        $return_data = Db::name('user_bxbl_question')->where($where)->field('tag_code')->order("id desc")->find();
        $return_data = Db::name('user_exam_detail')->where($where)->field('tag_code')->order("id desc")->find();
        if (empty($return_data)) {
            $tag_code = "";
        } else {
            $tag_code = $return_data['tag_code'];
        }
        Log::record("------" . __FUNCTION__ . "---tag_code---" . $tag_code);
        return $tag_code;
    }

    public function getL2Learned_nums($user_id, $topicId, $knowledgeList = [])
    {
        $data_bxbl = $this->getLearned_num($user_id, $topicId, config('l2_bxbl_module_type'));
        $bxblLearned_num = count($data_bxbl);//边学边练攻克数量
        $zhlxLearned_nums = $this->getLearned_num($user_id, $topicId, config('l2_jingsai_module_type'));
        $key_knowledgeList = [];
        foreach ($knowledgeList as $k => $v) {
            if (isset($v['code'])) $key_knowledgeList[] = $v['code'];
        }
        foreach ($zhlxLearned_nums as $k1 => $v1) {
            if (!in_array($k1, $key_knowledgeList)) {//综合练习知识点不在总的知识点的情况下排除
                unset($zhlxLearned_nums[$k1]);
            }
        }
        $zhlxLearned_num = count($zhlxLearned_nums);//综合练习在总的知识点的攻克数量
        return $bxblLearned_num + $zhlxLearned_num;
    }



    public function getUserAnsweredQuestionsByModule($user_id = null, $topicId, $module_type=null)
    {
        $return_arr = array();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if($topicId)
        {
            if(is_array($topicId))
            {
                $where['topicId'] = array("in",$topicId);
            }else
            {
                $where['topicId'] = $topicId;
            }

        }

        if($module_type)
        {
            $where['module_type'] = $module_type;
        }

        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();
        return  $answeredInfo;
    }

    /**
     * 获取用户的姓名。
     * @param $use_id
     * @return mixed
     */
    public function getUserName($use_id)
    {
        //if($topicId>200)exit ("请正确选择专题");
        $key = "userName:" . $use_id;
        $return_data = Cache::get($key);
        if (!$return_data) {
            $param = array();
            $param['user_id'] = $use_id;
            //根据知识点获取试题.
            $url = config("api_server_user") ."/User/getUserInfo";
            $return_data = rpc_request($url, $param);
            if(empty($return_data))
            {
                $log_service = new  logService();
                $log_service::sendMessage("error",__METHOD__."用户接口getUserInfo----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
            }

            Cache::set($key, $return_data);
        }
        return $return_data['data'];
    }

    public static function getUserTopicIdState($topicId,$user_id){
        $is_start=Db::name('user_exam_step_log')->where(['topicId'=>$topicId,'user_id'=>$user_id])->column('is_end,topicId','module_type');
        $data=[];
        $data['status']=2;//没有开始
        if($is_start){
            $data['status']=3;//已经开始
            if(Db::name('user_exam_step_log')->where(['topicId'=>$topicId,'user_id'=>$user_id,'module_type'=> config('zonghe_module_type'),'is_end'=>'1'])->count()){
                $data['status']=4;//已经结束
            }else{
                //如果边学边练已经做完
                if(isset($is_start[config('bxbl_module_type')]['is_end'])&&$is_start[config('bxbl_module_type')]['is_end']==1){
                    //查询是否有综合练习
                    $path_manager = new PathManageService();
                    $return_data=  $path_manager->getUserNextModule($user_id,$topicId,'');
                    if($return_data['is_end']==1)$data['status']=3;//没有综合练习结束
                }
            }
        }else{
            if(Db::name('user_exam_detail')->where(['topicId'=>$topicId,'user_id'=>$user_id])->count()){
                $data['status']=3;//已经开始
            }
        }
        return $data;
    }




    /**
     * 根据模块获取用户在某模块下已做题数.
     * @param null $user_id
     * @param $topicId
     * @param $module_type
     * @return array
     */
    public function getUserHasAnsweredQuestionsByModuleType($user_id = null, $topicId, $module_type = null)
    {

        $return_arr = array();
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if ($topicId) {
            if (is_array($topicId)) {
                $where['topicId'] = array("in", $topicId);
            } else {
                $where['topicId'] = $topicId;
            }
        }

        if ($module_type) {
            $where['module_type'] = $module_type;
        }
        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();
        return $answeredInfo;
    }
    
     /**
     * 获取用户某模块下做的全对的试题。
     * @param null $user_id
     * @param $topicId
     * @param null $module_type
     * @return false|mixed|\PDOStatement|string|\think\Collection
     */
    public function getUserAnsweredAllRightQuestionsByModuleType($user_id = null, $topicId, $module_type = null)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        if ($topicId) {
            if (is_array($topicId)) {
                $where['topicId'] = array("in", $topicId);
            } else {
                $where['topicId'] = $topicId;
            }
        }
        $where['is_right'] = 1;

        if ($module_type) {
            $where['module_type'] = $module_type;
        }
        $answeredInfo = Db::name('user_exam_detail')->where($where)->select();
        return $answeredInfo;

    }
    
     /**
     * @param $user_id
     * @param $topicId
     * @param $module_type
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getUserHasLearnedCodeScale($user_id, $topicId, $module_type)
    {
        if (!$user_id) {
            $user_id = $this->getUserId();
        }
        $where['user_id'] = $user_id;
        $where['topicId'] = $topicId;
        $where['module_type'] = $module_type;
        $learned_code_list = Db::name('user_exam')->where($where)->field('distinct  tag_code')->select();
        $learned_code_num = count($learned_code_list);
        //获取大知识图谱的知识点。
        $apiGateService = new ApiGateService();
        $big_map_code_list = $apiGateService->getKnowledgeListByTopicId($topicId);
//        var_dump($big_map_code_list);die;
        $big_map_code_num = count($big_map_code_list);
        $scale = $learned_code_num / $big_map_code_num;
        if($scale>1)
        {
            $scale =1;
        }
        return $scale;
    }


}
