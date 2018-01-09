<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/8/17
 * Time: 上午10:12
 */
namespace service\algo;

use think\Log;
use service\log\LogService;

/**
 * Class AlgoService
 * @package service\services
 * 算法调用类.
 */
class  AlgoService
{

    private static $API_URL;


    public function __construct()
    {
        switch ($_SERVER['HTTP_HOST']) {
            case 'math.classba.cn':
                self::$API_URL = config("math_classba_cn_api_url");
                break;
            case 'math.171xue.com':
                self::$API_URL = config("math_171xue_com_api_url");
                break;
            default:
                self::$API_URL = config("api_url");
                break;
        }
        
    }

    public static function getAPIURL()
    {
        return self::$API_URL;
    }


    public static function setAPIURL($API_URL)
    {
        self::$API_URL = $API_URL;
    }

    /**
     * @api {api} /AlgoService/algo/kstmode  知识图谱计算法接口
     * @apiVersion 0.0.1
     * @apiName  call_algo_kstmode  对接算法知识图谱计算法接口,先行测试调用
     * @apiGroup Algo/algo
     * @apiParam {Number} usr_id  用户ID.
     * @apiParam {String} init_kstatus  学生所选自评水平.
     * @apiParam {String} kmap_code  知识图谱编码.
     * @apiParam {String} pre_knode  前一个知识点编码.
     * @apiParam {String} usr_ans   学生测试问题的答案编号.  有三种情况: 第一次的用户还没做题,直接取题的时候,传 ""  (即空), 已做题的话,传 "0"或"1" .
     * @apiParam {Number} level_mode  知识点难度级别
     * @apiSuccess {Number} usr_id   用户ID.
     * @apiSuccess {String} init_kstatus   用户对应的专题掌握的程度.
     * @apiSuccess {String} kmap_code   知识图谱编号.
     * @apiSuccess {Number} knode_toaskq 所得知识点
     * @apiSuccess {Number}  weak_elems  薄弱知识点列表.
     * @apiSuccess {Number}  sErrors    错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *
     *     }
     */
    public function call_algo_kstmode($usr_id, $init_kstatus, $kmap_code, $pre_knode, $usr_ans, $level_mode = 1)
    {
        //kst new algo
        // 算法服务API参数
        if(empty($pre_knode))
        {
            $pre_knode = "";
        }
        $param = array();
        $param['usr_id'] = $usr_id;
        $param['kmap_code'] = $kmap_code;;
        $param['usr_ans'] = $usr_ans;
        $param['init_kstatus'] = (int)$init_kstatus;
        $param['pre_knode'] = $pre_knode;
        $param['level_mode'] = $level_mode;
        Log::write(json_encode($param));
        $url = self::$API_URL . '/AlgoService/algo/kstmode';
        $return_data = self::callAlgoService($url, 'post', json_encode($param), true);
        Log::write(json_encode($return_data));
        return $return_data;
    }




    /**
     * @api {api} /AlgoService/algo/kstmode  知识图谱计算法接口
     * @apiVersion 0.0.1
     * @apiName  call_algo_kstmode  对接算法知识图谱计算法接口,先行测试调用
     * @apiGroup Algo/algo
     * @apiParam {Number} usr_id  用户ID.
     * @apiParam {String} init_kstatus  学生所选自评水平.
     * @apiParam {String} kmap_code  知识图谱编码.
     * @apiParam {String} pre_knode  前一个知识点编码.
     * @apiParam {String} usr_ans   学生测试问题的答案编号.  有三种情况: 第一次的用户还没做题,直接取题的时候,传 ""  (即空), 已做题的话,传 "0"或"1" .
     * @apiParam {Number} level_mode  知识点难度级别
     * @apiSuccess {Number} usr_id   用户ID.
     * @apiSuccess {String} init_kstatus   用户对应的专题掌握的程度.
     * @apiSuccess {String} kmap_code   知识图谱编号.
     * @apiSuccess {Number} knode_toaskq 所得知识点
     * @apiSuccess {Number}  weak_elems  薄弱知识点列表.
     * @apiSuccess {Number}  sErrors    错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *
     *     }
     */
    public function call_algo_kstime($usr_id, $init_kstatus, $kmap_code, $pre_knode, $usr_ans, $level_mode = 1,$need_time,$take_time,$sys_code=1,$log_option=array())
    {
        //kst new algo
        // 算法服务API参数
        if(empty($pre_knode))
        {
            $pre_knode = "";
        }
        $param = array();
        $param['usr_id'] = $usr_id;
        $param['kmap_code'] = $kmap_code;;
        $param['usr_ans'] = $usr_ans;
        $param['init_kstatus'] = (int)$init_kstatus;
        $param['pre_knode'] = $pre_knode;
        $param['level_mode'] = $level_mode;
        $param['need_time'] = $need_time;
        $param['take_time'] = $take_time;
        $param['sys_code']  =  $sys_code;
        Log::write(json_encode($param));

        $log_service = new logService("algo");
        $topic= "info";
        $startTime=microtime(true);//开始时间
        $url = self::$API_URL . '/AlgoService/algo/kstime';
        $return_data = self::callAlgoService($url, 'post', json_encode($param), true);
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间

        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;

        $log_service::sendMessage($topic,$log_option,'kstime');


        if (!$return_data) {
            $topic = "error";
            $methodName = __METHOD__;
            $callParams = json_encode(func_get_args());
            $message = "算法返回空数据：调用的方法是：{$methodName},传入的数数是：{$callParams}";
            $log_service::sendMessage($topic, $message);
        }
        return $return_data;
    }


    /**
     * 先行测试+能力值接口
     * @param $usr_id
     * @param $init_kstatus
     * @param $kmap_code
     * @param $pre_knode
     * @param $usr_ans
     * @param $level_mode
     * @param array $diffculty
     * @param array $score
     * @param array $likelihood
     * @param array $type
     * @param array $option    先行测试+能力值接口
     * @return mixed
     */
    public function call_algo_kstability($usr_id,$init_kstatus,$kmap_code,$pre_knode,$usr_ans,$level_mode,$diffculty = array(),$score=array(),$likelihood =array(),$type =array(1),$log_option =array())
    {
        // 算法服务API参数
        if(empty($pre_knode))
        {
            $pre_knode = "";
        }
        $param = array();
        $param['usr_id'] = $usr_id;
        $param['kmap_code'] = $kmap_code;
        $param['usr_ans'] = $usr_ans;
        $param['init_kstatus'] = (int)$init_kstatus;
        $param['pre_knode'] = $pre_knode;
        $param['level_mode'] = $level_mode;
        foreach ($diffculty as $key=>$val)
        {
            if(is_int($val))
            {
                if(!$val)
                {
                    $diffculty[$key] = 1;
                }
            }else{
                $num =  (int)$val;
                if($num)
                {
                    $diffculty[$key] = $num;
                }else{
                    $diffculty[$key] = 1;
                }

            }
        }
        $param['difficulty'] = json_encode($diffculty);
        $param['score'] = json_encode($score);
        if(is_array($likelihood))
        {
            $param['likelihood'] = json_encode($likelihood);
        }else{
            $param['likelihood'] = $likelihood;
        }
        $param['type'] = json_encode($type);

        $startTime=microtime(true);//开始时间


        $url = self::$API_URL . '/AlgoService/algo/kstability';
        $return_data = self::callAlgoService($url, 'post', json_encode($param), true);
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间

        $log_service = new LogService("algo");
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;

        $log_service::sendMessage($topic,$log_option,'kstability');

        if (!$return_data) {
            $topic = "error";
            $methodName = __METHOD__;
            $callParams = json_encode(func_get_args());
            $message = "算法返回空数据：调用的方法是：{$methodName},传入的数数是：{$callParams}";
            $log_service::sendMessage($topic, $message);
        }
        if($return_data['knode_toaskq']=="")
        {
            $methodName=__METHOD__;
            $message  = $methodName."-----算法返回知识点又出现为空的情况了。用户是, user_id:$usr_id";
            $log_service::sendMessage($topic,$message);
        }

        return $return_data;
    }



    /**
     * @api {api} /AlgoService/algo/nlix 边学边练获取下一个知识点的方法
     * @apiVersion 0.0.1
     * @apiName  call_algo_nlix  对接算法知识图谱计算法接口,先行测试调用
     * @apiGroup Algo/algo
     * @apiParam {String} kmap_code   知识图谱编码 .
     * @apiParam {String} elements_codes  所有知识点编码.
     * @apiParam {String} elements_abilities  学生对所有知识点掌握的能力.
     * @apiParam {String} learning_counts  每个知识点,学生所需学习的数量.
     * @apiParam {String} weak_elements  所有的薄弱知识点.
     * @apiParam {Number} learned_elements   已经学过的知识点.
     * @apiSuccess {String} next_element   下一个题目的编号.
     * @apiSuccess {String} error   错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "next_element" : "zk_20",
     *          "error": "错误信息"
     *
     *     }
     */
    public function call_algo_nlix($kmap_code, $elements_codes, $elements_abilities, $learning_counts, $weak_elements, $learned_elements,$log_option = array())
    {
        // 算法服务API参数
        $param['kmap_code'] = $kmap_code;
        $param['elements_codes'] = json_encode($elements_codes);
        $param['elements_abilities'] = json_encode($elements_abilities);
        $param['learning_counts'] = json_encode($learning_counts);
        $param['weak_elements'] = $weak_elements;
        $param['learned_elements'] = json_encode($learned_elements);

//        $a = '{"kmap_code":"topic_zk3","elements_codes":"[\"zk_3.1\",\"zk_3.2\",\"zk_3.3\",\"zk_3.4\",\"zk_3.5\",\"zk_3.6\",\"zk_3.7\",\"zk_3.8\",\"zk_3.9\",\"zk_3.10\",\"zk_3.11\",\"zk_3.12\",\"zk_3.13\",\"zk_3.14\",\"zk_3.15\",\"zk_3.16\"]","elements_abilities":"[-1,-1,-1,\"0.05\",-1,-1,-1,-1,-1,-1,-1,\"0.05\",-1,-1,-1,-1]","learning_counts":"[0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0]","weak_elements":"[\"zk_3.11\",\"zk_3.10\",\"zk_3.13\",\"zk_3.12\",\"zk_3.15\",\"zk_3.14\",\"zk_3.9\",\"zk_3.16\",\"zk_3.8\",\"zk_3.7\",\"zk_3.6\",\"zk_3.4\",\"zk_3.3\",\"zk_3.2\",\"zk_3.1\"]","learned_elements":"[\"zk_3.4\",\"zk_3.12\"]"}';

        $startTime=microtime(true);//开始时间
        Log::write('-----nlix----:' . json_encode($param));
        $url = self::$API_URL . '/AlgoService/algo/nlix';    //   'localhost:8080/algo/nlix';   //       // 算法API地址

        $return_data = self::callAlgoService($url, 'post', json_encode($param), true);
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间
        $log_service = new logService("algo");
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;
        $log_service::sendMessage($topic,$log_option,'nlix');

        if(!$return_data)
        {
            $topic= "error";
            $methodName=__METHOD__;
            $callParams=json_encode(func_get_args());
            $message  = "算法返回空数据：调用的方法是：{$methodName},传入的数数是：{$callParams}";
            $log_service::sendMessage($topic,$message);
        }
        return $return_data;
    }


    /**
     * @api {api} /AlgoService/algo/abilityx 能力估计算法
     * @apiVersion 0.0.1
     * @apiName  call_algo_abilityx
     * @apiGroup Algo/algo
     * @apiParam {String} difficulty   学生做过的题目的难度 .
     * @apiParam {String} score   学生做过的题目对应的得分.
     * @apiParam {String} likelihood  该学生目前的能力估计对应的最大似然函数概率值(99个).
     * @apiParam {String} type  能力估计类型 .1 : 测试题  2: 练习题.
     * @apiSuccess {String} ability   能力值.
     * @apiSuccess {String} likelihood   该学生目前的能力值估计对应的最大似然函数概率值(99个).
     * @apiSuccess {String} abilityprob   整体评估能力可能性值.
     * @apiSuccess {String} error   错误信息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "ability" : "待定",
     *          "likelihood": "待定",
     *          "abilityprob":"" 待定",
     *          "error": "待定"
     *
     *     }
     */
    public function call_algo_abilityx($difficulty, $score, $likelihood, $type,$log_option=array())
    {

        $param['difficulty'] = array($difficulty);
        $param['score']      = array($score);
        $param['likelihood'] = $likelihood;
        $param['type']       = array($type);
        $startTime=microtime(true);//开始时间
        $url = self::$API_URL . '/AlgoService/algo/abilityx';
        $return_data = self::callAlgoService($url, 'post', json_encode($param), true);
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间
        $log_service = new logService("algo");
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;
        $log_service::sendMessage($topic,$log_option,'abilityx');
        if(!$return_data)
        {
            $log_service = new  LogService();
            $topic= "error";
            $methodName=__METHOD__;
            $callParams=json_encode(func_get_args());
            $message  = "算法返回空数据：调用的方法是：{$methodName},传入的数数是：{$callParams}";
            $log_service::sendMessage($topic,$message);
        }
        return $return_data;
    }


    /**
     * 为了过渡题库换成mongo后，试题ID是字符串的问题。临时写的新接口，目前不用了。
     * 调用算法的assessmentm接口.
     */
    public function call_algo_assessmentm($ability,$question_ids = array(),$question_difficultys = array(),$assessment_size = 1)
    {
        $param = array();
        // 初始化输入参数
//        $dAbility = 0.4;         // 当前能力值， 范围为 [0.01, 0.99]
//        $aQuestionId = array(11,21,31,41,51,61,71,81,91);        // 该知识点的所有试题id，为整数值, array of int
//        $aQuestDifficulty = array(1,2,3,1,2,3,1,2,3);        // 所有试题对应的难度值，与以上变量对应
        $param['ability']               = strval($ability);
        $param['question_ids']          = json_encode( $question_ids);
        $param['question_difficulties'] = json_encode($question_difficultys);
        $param['assessment_size'] = $assessment_size;

        $url  = self::$API_URL.'/AlgoService/algo/assessmentm';   //  'localhost:8080/algo/assessment';   //   // 算法API地址
        $aRtn = $this->callAlgoService($url, 'post', json_encode($param), true);
        // 分析返回值
        $sError                  = $aRtn['error'];
        $aQuestionsForAssessment = json_decode($aRtn['questions']);

        return $aQuestionsForAssessment;
    }


    /**
     * 调用算法的assessmentn接口.
     */
    public function call_algo_assessmentn($ability,$question_ids = array(),$question_difficultys = array(),$assessment_size = 1,$log_option=array())
    {
        $param = array();
        // 初始化输入参数
//        $dAbility = 0.4;         // 当前能力值， 范围为 [0.01, 0.99]
//        $aQuestionId = array(11,21,31,41,51,61,71,81,91);        // 该知识点的所有试题id，为整数值, array of int
//        $aQuestDifficulty = array(1,2,3,1,2,3,1,2,3);        // 所有试题对应的难度值，与以上变量对应
        $param['ability']               = strval($ability);
        $param['question_ids']          = json_encode( $question_ids);

        foreach ($question_difficultys as $key=>$val)
        {
            if(is_int($val))
            {
                if(!$val)
                {
                    $diffculty[$key] = 1;
                }
            }else{
                $diffculty[$key] = 1;
            }
        }

        $param['question_difficulties'] = json_encode($question_difficultys);
        $param['assessment_size'] = $assessment_size;

        $startTime=microtime(true);//开始时间
        $url  = self::$API_URL.'/AlgoService/algo/assessmentn';   //  'localhost:8080/algo/assessment';   //   // 算法API地址
        $return_data = $this->callAlgoService($url, 'post', json_encode($param), true);
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间
        $log_service = new logService("algo");
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;
        $log_service::sendMessage($topic,$log_option,'assessmentn');
        if(!$return_data)
        {
            $log_service = new  LogService();
            $topic= "error";
            $methodName=__METHOD__;
            $callParams=json_encode(func_get_args());
            $message  = "算法返回空数据：调用的方法是：{$methodName},传入的数数是：{$callParams}";
            $log_service::sendMessage($topic,$message);
        }
        // 分析返回值
        $sError                  = $return_data['error'];
        $aQuestionsForAssessment = json_decode($return_data['questions']);
        return $aQuestionsForAssessment;
    }



    /**
     *  调用算法 similarquest 接口.
     */
    public function call_algo_similarquest($strm = array())
    {
        $param['strm'] = json_encode($strm);
        $url  = self::$API_URL.'/AlgoService/algo/similarquest';   //  'localhost:8080/algo/similarquest';   //   // 算法API地址
        $aRtn = $this->callAlgoService($url, 'post', json_encode($param), true);

        var_dump($aRtn);
        exit;




    }





    /**
     * 调用远程算法服务API
     * @author billcui
     */
    static private function callAlgoService($url = '', $method = '', $param = '', $ret_json = true)
    {
        //设置选项
        $opts = array(
            CURLOPT_TIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => $url,
            //CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'],
        );
        if ($method === 'post') {
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $param;
        }
        //初始化并执行curl请求
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        //$curlResult=curl_getinfo($ch);
        //$error = curl_error($ch);

        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE); // code 码
        if($httpCode>399)
        {
            $log_service = new LogService();
            $msg='HTTP--EERROR:  url:'.$url.",参数:".json_encode($param)."  返回状态码： $httpCode,时间是：". date('Y-m-d H:i:s');
            $log_service::sendMessage('error',$msg);
        }

        curl_close($ch);
        if ($ret_json) {
            $data = json_decode($data, true);
        }
        return $data;
    }


    /**
     * @param $kmap_code 知识图谱
     * @param string $kmap_type 类型
     * @return mixed
     */
    public function call_algo_knowledgenode($kmap_code,$kmap_type,$log_option=array())
    {
        $param['kmap_code'] = $kmap_code;
        $param['kmap_type'] = $kmap_type;
        $startTime=microtime(true);//开始时间
        $url  = self::$API_URL.'/AlgoService/algo/knowledgenode';   //  'localhost:8080/algo/similarquest';   //   // 算法API地址
        $return_data = $this->callAlgoService($url, 'post', json_encode($param), true);
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间
        $log_service = new logService("algo");
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;
        $log_service::sendMessage($topic,$log_option,'knowledgenode');
        if(!$return_data)
        {
            $log_service = new  LogService();
            $topic= "error";
            $methodName=__METHOD__;
            $callParams=json_encode(func_get_args());
            $message  = "算法返回空数据：调用的方法是：{$methodName},传入的数数是：{$callParams}";
            $log_service::sendMessage($topic,$message);
        }

        return $return_data;

    }
}