<?php
/**
 * 日志记录方法，引用think\log
 */

namespace YXLog;

use think\Log;

class YXLog extends Log
{
    //版本号
    const VERSION = '1.0.5';

    /**
     * 写入日志公共方法
     * @param type $msg
     * @param type $level
     * @param type $tag
     */
    public static function writeLog($msg, $level, $tag = '')
    {
        $trace_str = '';
        $trace     = debug_backtrace();
        if (is_array($trace)) {
            $trace_info = $trace[1];
            $trace_str  .= '[';
            $trace_str  .= isset($trace_info['file']) ? ($trace_info['file']) : '';
            $trace_str  .= isset($trace_info['line']) ? ('(' . $trace_info['line'] . ')') : '';
            $trace_str  .= isset($trace_info['class']) ? (': ' . $trace_info['class']) : '';
            $trace_str  .= isset($trace_info['type']) ? ($trace_info['type']) : '';
            $trace_str  .= isset($trace_info['function']) ? ($trace_info['function'] . '()') : '';
            $trace_str  .= ']';
        }

        $log_str = $msg;
        if (!empty($tag)) {
            $log_str .= " [tag:{$tag}]";
        }
        if ($trace_str) {
            $log_str .= " " . $trace_str;
        }
        self::write($log_str, $level);
    }

    /**
     * 生成一个trans_id
     * @return type
     */
    public static function buildTransId()
    {
        $mtime = intval(microtime(true) * 1000);
        $randv = rand(0, 99999);
        return($mtime . $randv);
    }

    /**
     * 获取日志trans_id
     * @return string
     */
    public static function getTransId()
    {
        $driver = parent::$driver;
        if ($driver instanceof \YXLog\driver\YXLog) {
            return $driver::getTransId();
        }

        return false;
    }

    /**
     * 设置日志trans_id
     * @param type $trans_id
     * @return type
     */
    public static function setTransId($trans_id)
    {
        $driver = parent::$driver;
        if ($driver instanceof \YXLog\driver\YXLog) {
            return $driver::setTransId($trans_id);
        }
        return false;
    }

    /**
     * 重新初始化trans_id
     * @param type $trans_id
     * @param type $force true为强制重置，false则如果没有才生成
     * @return boolean
     */
    public static function initTransId($trans_id = null, $force = true)
    {
        $driver = parent::$driver;
        if ($driver instanceof \YXLog\driver\YXLog) {
            return $driver::initTransId($trans_id, $force);
        }
        return false;
    }

    /**
     * 获取调用信息
     * @return type
     */
    public static function getCallerInfo()
    {
        $trace  = debug_backtrace();
        $caller = array_shift($trace);
        return $trace;
    }

    //---用户方法---
    public static function log($msg, $tag = '')
    {
        self::writeLog($msg, 'info', $tag);
    }

    public static function logError($msg, $tag = '')
    {
        self::writeLog($msg, 'error', $tag);
    }

    public static function error($msg, $tag = '')
    {
        self::writeLog($msg, 'error', $tag);
    }

    public static function logInfo($msg, $tag = '')
    {
        self::writeLog($msg, 'info', $tag);
    }

    public static function info($msg, $tag = '')
    {
        self::writeLog($msg, 'info', $tag);
    }

    public static function logDebug($msg, $tag = '')
    {
        self::writeLog($msg, 'debug', $tag);
    }

    public static function debug($msg, $tag = '')
    {
        self::writeLog($msg, 'debug', $tag);
    }

    public static function logNotice($msg, $tag = '')
    {
        self::writeLog($msg, 'notice', $tag);
    }

    public static function notice($msg, $tag = '')
    {
        self::writeLog($msg, 'notice', $tag);
    }

    public static function logAlert($msg, $tag = '')
    {
        self::writeLog($msg, 'alert', $tag);
    }

    public static function alert($msg, $tag = '')
    {
        self::writeLog($msg, 'alert', $tag);
    }

    public static function logFatal($msg, $tag = '')
    {
        self::writeLog($msg, 'fatal', $tag);
    }

    public static function fatal($msg, $tag = '')
    {
        self::writeLog($msg, 'fatal', $tag);
    }
}
