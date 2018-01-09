<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 17/3/30
 * Time: 下午3:48
 */
namespace service\api;
use service\entity\UserExamStepLog;
use service\log\LogService;
use think\Cache;

class PathManage
{

    private $api_host;
    private $path_mapping;

    function __construct()
    {
        $this->api_host = config("api_host_config.studypath_api_server");

    }


    public function getStudyPath($flow_id)
    {

        $key = "studyPath:flow_id:" . $flow_id;
        $data = Cache::get($key);
        if (!$data) {
            $param['flow_id'] = $flow_id;
            //根据知识点获取试题.
            $url = $this->api_host . "/index/api/getStudyPathRow/flow_id/{$flow_id}";
            $start_time = microtime(true);
            $result = rpc_request($url, $param);


            /*****日志埋点****/
            $log_service = new LogService();
            $msg = json_encode(array(
                'request_api' => "getStudyPathRow",
                //'user_id' => $this->getUserId(),
                'request_data' => $param,   //请求数据,
                'response_data' => $result,  //响应数据
                'stime' => $start_time,     // 接口开始时间
                'etime' => microtime(true),     // 接口结束时间
                'ctime' => time()      // 创建时间。
            ));
            $log_service::sendMessage("info", $msg);
            /*****日志埋点****/
            if (empty($result)) {
                $log_service = new  logService();
                $log_service::sendMessage("error", __METHOD__ . "路径接口getStudyPathRow-----返回值为空,");
            } else {
                if (isset($result["data"])) {
                    $data = $result["data"];
                }
            }
            Cache::set($key, $data);
        }
        return  $data;
    }




}
