<?php

namespace YXLog\driver;

use think\App;
use think\Config;

/**
 * 本地化调试输出到文件
 */
class YXLog
{
    protected $config       = [
        'time_format'  => 'Y-m-d H:i:s', //记录日期格式）
        'file_size'    => 2097152, //拆分日志的文件大小(默认2M)
        'path'         => LOG_PATH, //日志记录地址
        'apart_level'  => [], //独立等级记录
        'prefix'       => '', //日志前缀
        'save_tp_info' => true, //是否保存tp产生的日志info信息
        'trans_id'     => '', //trans_id设置
        'tp_tags'      => ['[ LANG ]', '[ ROUTE ]', '[ HEADER ]', '[ PARAM ]', '[ RUN ]', '[ DB ]', '[ SQL ]', '[ BEHAVIOR ]', '[ CACHE ]', '[ BIND ]', '[ SESSION ]', '[ VIEW ]'], //tp自带的log标识
    ];
    public static $trans_id = null;

    /**
     * 初始化Trans Id
     * @param type $trans_id
     * @param boolean $force 强制更新
     * @return type
     */
    public static function initTransId($trans_id = null, $force = false)
    {
        // 给trans_id赋值
        if (empty(self::$trans_id)) {
            self::$trans_id = empty($trans_id) ? \YXLog\YXLog::buildTransId() : $trans_id;
        } else {
            if ($force === true) {
                self::$trans_id = empty($trans_id) ? \YXLog\YXLog::buildTransId() : $trans_id;
            }
        }
        return self::$trans_id;
    }

    /**
     * 实例化并传入参数
     * @param type $config
     */
    public function __construct($config = [])
    {
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }
        $trans_id       = isset($this->config['trans_id']) ? $this->config['trans_id'] : null;
        self::$trans_id = self::initTransId($trans_id);
    }

    /**
     * 设置trans_id
     * @param type $trans_id
     * @return type
     */
    public static function setTransId($trans_id = '')
    {
        if (!empty($trans_id)) {
            self::$trans_id = $trans_id;
        }
        return self::$trans_id;
    }

    /**
     * 返回trans
     * @return type
     */
    public static function getTransId()
    {
        return self::$trans_id;
    }

    /**
     * 在一个字符串中查找指定内容（支持数组）
     * @param type $str     //目标字符串
     * @param type $target  //要查找的内容
     * @param type $case_sens //是否区别大小写
     */
    public static function strFind($str, $target, $case_sens = true)
    {
        if (empty($str) || empty($target)) {
            return false;
        }

        if (is_string($target)) {
            return $case_sens ? strpos($str, $target) : stripos($str, $target);
        }

        if (is_array($target)) {
            foreach ($target as $v) {
                $find_result = $case_sens ? strpos($str, $v) : stripos($str, $v);
                if ($find_result !== false) {
                    return $find_result;
                }
            }
        }

        return false;
    }

    /**
     * 日志写入接口
     * @access public
     * @param array $log 日志信息
     * @return bool
     */
    public function save(array $log = [])
    {
        $format_list = ["\t", "\n", "\r", "  "];  //注意此处不能用单引号哦
        $tp_tags     = $this->config['tp_tags'];  //tp自带的标识

        $prefix = $this->config['prefix'];
        //如果前缀为空，则默认用主机作为前缀
        if ($prefix == '') {
            $prefix = isset($_SERVER['HTTP_HOST']) ? ($_SERVER['HTTP_HOST'] . '_') : $prefix;
        }

        $cli         = IS_CLI ? '_cli' : '';
        $logFileName = $this->config['path'] . DS . $prefix . date('Ymd') . $cli;
        $destination = $logFileName . '.log';
        $path        = dirname($destination);

        !is_dir($path) && mkdir($path, 0755, true);

        $info = '';
        foreach ($log as $type => $val) {
            $level_msg = '';
            foreach ($val as $msg) {
                if (!is_string($msg)) {
                    $msg = var_export($msg, true);
                }

                if ($this->config['save_tp_info'] === true) {
                    //重新格式化tp自带log信息
                    if (self::strFind($msg, $tp_tags) !== false) {
                        $msg = str_replace($format_list, '', $msg);
                    }
                }

                $level_msg = '[' . $type . '] ' . $msg;
                if (in_array($type, $this->config['apart_level'])) {
                    // 独立记录的日志级别
                    $filename = $logFileName . '_' . $type . '.log';
                    $this->write($level_msg, $filename, true);
                } else {
                    $this->write($level_msg, $destination);
                }
            }
        }
        return true;
    }

    protected function write($message, $destination, $apart = false)
    {
        $microtime = microtime(true);
        //检测日志文件大小，超过配置大小则备份日志文件重新生成
        if (is_file($destination) && floor($this->config['file_size']) <= filesize($destination)) {
            rename($destination, dirname($destination) . DS . basename($destination, '.log') . '_' . date('His', time()) . '.log');
        }

        //日志的跟踪序列，用PHPSESSID记录
        $request_id = isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : '';
        $request_id = '[sessid:' . md5($request_id) . ']';

        //生成trans_id
        $mtime = intval($microtime * 1000);
        $randv = rand(0, 99999);
        if (!empty(Config::get('log.trans_id'))) {
            self::setTransId(Config::get('log.trans_id'));
        }
        $transid = is_null(self::$trans_id) ? '' : self::$trans_id;
        $transid = '[transid:' . $transid . ']';

        if (!IS_CLI) {
            $more_info = '';
            if (!defined('THINK_START_TIME')) {
                define('THINK_START_TIME', defined('START_TIME') ? START_TIME : time());
            }
            $runtime  = round($microtime - THINK_START_TIME, 10);
            //$reqs       = $runtime > 0 ? number_format(1 / $runtime, 2) : '∞';
            $time_str = '[runtime:' . number_format($runtime, 6) . 's]';
            //$reqs_str   = '[request：' . $reqs . 'req/s]';
            //$memory_use = number_format((memory_get_usage() - THINK_START_MEM) / 1024, 2);
            //$memory_str = '[mem:' . $memory_use . 'kb]';
            //$file_load  = '[file loaded：' . count(get_included_files()) . ']';

            $more_info = $time_str;

            //记录日志时间，至毫秒
            $now = date($this->config['time_format'], intval($microtime)) . '.' . substr(strval($mtime), -3);
            // 获取基本信息
            if (isset($_SERVER['HTTP_HOST'])) {
                $uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            } else {
                $uri = "cmd:" . implode(' ', $_SERVER['argv']);
            }
            $server  = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '0.0.0.0';
            $remote  = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
            $method  = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'CLI';
            $secheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] . '://' : '';
            //$uri     = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $message = "[{$now}]" . $message . " [{$server} {$remote} {$method} {$secheme}{$uri}]" . $more_info . $request_id . $transid . "\r\n\r\n";
        } else {
            $now     = date($this->config['time_format']);
            $message = "[{$now}]" . $message;
        }

        return error_log($message, 3, $destination);
    }

}
