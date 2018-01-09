<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/8/17
 * Time: 上午10:12
 */
namespace service\algo;

use service\log\LogService;
use think\Log;
use app\summer\controller\ErrorHandle;


/**
 * Class AlgoService
 * @package service\services
 * 算法调用类.
 */
class  AlgoV2Service
{

    private static $API_URL;


    public function __construct()
    {
        self::$API_URL = config("new_algo_api_url");
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
        curl_close($ch);


        if ($ret_json) {
            $data = json_decode($data, true);
        }
        return $data;
    }

    /**
     * 根据学生信息、专题信息及课次信息生成会话编号;同一个usser_id,同一个课次ID，
     * 同一专题及学习次数四个维度信息组合只能生产一个唯一的会话编号
     * @param $user_id 用户id
     * @param $kmap_code 知识图谱编号（专题编号）
     * @param $curriculum_id 课次ID
     * @param $sys_code 系统编码
     * @param $level_mode 测评标准难度级别，详见:测评难度级别表
     * @param $init_kstatus 掌握程度自评级别, 详见:自评程度级别表
     * @param $learn_times 学习当前专题次数
     * @param $total_level 题目难度级别总数,详见: 题目难度表
     * @return string
     */
    public function call_algo_getSessionId($user_id,$kmap_code,$curriculum_id,$sys_code,$level_mode,$init_kstatus,$learn_times,$total_level,$log_option)
    {
        $param['user_id']=$user_id;
        $param['kmap_code']=$kmap_code;
        $param['curriculum_id']=$curriculum_id;
        $param['sys_code']=$sys_code;
        $param['level_mode']=$level_mode;
        $param['init_kstatus']=$init_kstatus;
        $param['learn_times']=$learn_times;
        $param['user_id']=$user_id;
        $param['total_level']=$total_level;
        $url  = self::$API_URL.'/XAlgo/algo/getSessionId';   // 算法API地址
        $startTime=microtime(true);//开始时间

        Log::record(__METHOD__."-- user_id:".$user_id."---传递参数－－－－－".json_encode($param));
        $return_data = $this->callAlgoService($url, 'post', json_encode($param), true);
        Log::record(__METHOD__."---user_id:".$user_id."--算法返回参数－－－－－".json_encode($return_data));


        $log_service = new logService("algo");
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;
        $log_service::sendMessage($topic,$log_option,'getSessionId');
        $session_id="";
        if($return_data["code"]==0)
        {
            $session_id= $return_data["session_id"];
        }else
        {
            $log_service = new LogService();
            $log_service::sendMessage("error",__METHOD__."获取算法接口getSessionId----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param))."  返回值为：".json_encode($return_data);
//            $this->error($return_data);  //暂时先不加，需要彪彪把所有图谱在新的算法服务器上部署一份才可以。
        }
        return $session_id;

    }


    /**
     * L1先行测试取题接口。
     * @param $session_id
     * @param $answeer
     */
    public function call_algo_revassess($session_id,$map_code,$answer,$log_option)
    {
        $param['session_id']=$session_id;
        $param['answer'] =(int) $answer;    //用户第一次做的时候传0，不是传空。
        $param['map_code'] = $map_code;
        $url  = self::$API_URL.'/XAlgo/algo/revassess';   // 算法API地址

        Log::record(__METHOD__."------call_algo_revassess-----URL地址为-000000----： ".$url);

        $startTime=microtime(true);//开始时间
        $return_data = $this->callAlgoService($url, 'post', json_encode($param), true);
        $log_service = new logService("algo");
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;

        $log_service::sendMessage($topic,$log_option,'revassess');


        if($return_data["code"]!=0)
        {
            $log_service = new LogService();
            $log_service::sendMessage("error",__METHOD__."获取算法接口getSessionId----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
            $this->error($return_data);
        }
        return  $return_data;
    }


    /**
     * 生成能力值
     * @param $session_id
     * @param $stage_code
     * @param $node_code
     * @param $questions
     * @param $type
     */
    public function call_algo_ability($session_id,$map_code,$stage_code,$node_code,$questions,$used_type,$log_option)
    {
        $param['session_id'] = $session_id;
        $param['stage_code'] = $stage_code;
        $param['node_code']  = $node_code;
        $param['questions'] = $questions;
        $param['type'] = $used_type;
        $param['map_code'] =$map_code;
        $startTime=microtime(true);//开始时间

        $url  = self::$API_URL.'/XAlgo/algo/ability';   // 算法API地址
        $return_data = $this->callAlgoService($url, 'post', json_encode($param), true);

        $log_service = new logService("algo");
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;

        $log_service::sendMessage($topic,$log_option,'ability');


        if($return_data["code"]!=0)
        {
            $log_service = new LogService();
            $log_service::sendMessage("error",__METHOD__."获取算法接口ability-----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
            $this->error($return_data);
        }
        return  $return_data;

    }

//
//    /**
//     *  l1学习阶段 获取下一个知识点接口  （已删除）
//     */
//    public function call_algo_nlixplus($session_id,$map_code,$nodes,$weak_nodes,$learned_nodes,$log_option)
//    {
//        $param['session_id'] = $session_id;
//        $param['map_code'] = $map_code;
//        $param['nodes']  = json_encode($nodes);
//        $param['weak_nodes'] = json_encode($weak_nodes);
//        $param['learned_nodes'] = json_encode($learned_nodes);
//        $url  = self::$API_URL.'/XAlgo/algo/nlixplus';   // 算法API地址
//        $startTime=microtime(true);//开始时间
//
//        $return_data = $this->callAlgoService($url, 'post', json_encode($param), true);
//
//        $log_service = new logService("algo");
//        $endTime=microtime(true);//结束时间
//        $createTime=time();//创建时间
//        $topic= "info";
//        $log_option['request_data'] =  $param;
//        $log_option['response_data'] = $return_data;
//        $log_option['stime'] = $startTime;
//        $log_option['etime'] = $endTime;
//        $log_option['ctime'] = $createTime;
//
//        $log_service::sendMessage($topic,$log_option,'nlixplus');
//
//
//
//        if($return_data["code"]!=0)
//        {
//            $log_service = new LogService();
//            $log_service::sendMessage("error",__METHOD__."获取算法接口nlixplus-----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
//        }
//        return  $return_data;
//    }





    /**
     *  l1学习阶段 获取下一个知识点接口
     */
    public function call_algo_learnrecomp($session_id,$map_code,$nodes,$weak_nodes,$learned_nodes,$log_option)
    {
//        $session_id = "3398104297704448";
        $param['session_id'] = $session_id;
        $param['map_code'] = $map_code;
        $param['nodes']  = $nodes;
        $param['weak_nodes'] = $weak_nodes;
        $param['learned_nodes'] = $learned_nodes;


//        $api_url = "http://192.168.100.185:8080";
//        $url  = $api_url.'/XAlgo/algo/learnrecomp';   // 算法API地址

        $url  = self::$API_URL.'/XAlgo/algo/learnrecomp';   // 算法API地址
        $param1=  json_encode($param);
        $startTime=microtime(true);//开始时间
        $return_data = $this->callAlgoService($url, 'post', $param1, true);

        $log_service = new logService("algo");
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;

        $log_service::sendMessage($topic,$log_option,'learnrecomp');
        if($return_data["code"]!=0)
        {
            $log_service = new LogService();
            $log_service::sendMessage("error",__METHOD__."获取算法接口learnrecomp-----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
            $this->error($return_data);
        }
        return  $return_data;
    }


    /**
     * 构建新知识图谱
     * @param $session_id
     * @param $map_code
     * @param $mode
     * @param $is_direct
     * @param $nodes
     */
    public function call_algo_constructmap($session_id,$map_code,$mode="POSTREQ",$bdirect=true,$included=false,$nodes,$bcur_scope=false,$log_option)
    {
        $param['session_id'] = $session_id;
        $param['map_code'] = $map_code;
        $param['mode']  = $mode;
        $param['bdirect'] = true;
        $param['included'] = false;
        $param['nodes'] = $nodes;
        $param['bcur_scope'] = $bcur_scope;
        $url  = self::$API_URL.'/XAlgo/algo/constructmap';   // 算法API地址
        $param1=  json_encode($param);
        $startTime=microtime(true);//开始时间
        $return_data = $this->callAlgoService($url, 'post', $param1, true);
        $log_service = new logService("algo");
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;
        $log_service::sendMessage($topic,$log_option,'constructmap');

        if($return_data["code"]!=0)
        {
            $log_service = new LogService();
            $log_service::sendMessage("error",__METHOD__."获取算法接口constructmap-----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
            $this->error($return_data);
        }
        return  $return_data;


    }

    /**
     * 后测取知识点接口。
     * @param $session_id
     * @param $map_code
     * @param $answer
     * @param $log_option
     * @return mixed
     */
    public function call_algo_learnassess($session_id,$map_code,$answer,$log_option)
    {

        $param['session_id']=$session_id;
        $param['answer'] =(int) $answer;    //用户第一次做的时候传0，不是传空。
        $param['map_code'] = $map_code;
        $url  = self::$API_URL.'/XAlgo/algo/learnassess';   // 算法API地址
        $startTime=microtime(true);//开始时间
        $return_data = $this->callAlgoService($url, 'post', json_encode($param), true);
        $log_service = new logService("algo");
        $endTime=microtime(true);//结束时间
        $createTime=time();//创建时间
        $topic= "info";
        $log_option['request_data'] =  $param;
        $log_option['response_data'] = $return_data;
        $log_option['stime'] = $startTime;
        $log_option['etime'] = $endTime;
        $log_option['ctime'] = $createTime;
        $log_service::sendMessage($topic,$log_option,'learnassess');
        if($return_data["code"]!=0)
        {
            $log_service = new LogService();
            $log_service::sendMessage("error",__METHOD__."获取算法接口getSessionId----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
            $this->error($return_data);
        }
        return  $return_data;
    }

    /**
     * 强制错误输出。
     * @param $data
     * @return array
     */
    public function error($data)
    {
        $router = new ErrorHandle();

        $router->newError('算法异常.', '');
        exit;
    }

}