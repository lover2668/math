<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//注册命名空间.
\think\Loader::addNamespace('service', '../service/');

function rpc_request($url, $param, $method = "post", $ret_json = true)
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
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // code 码
    if ($httpCode > 399) {
        $log_service = new  service\log\LogService();
        $msg = 'HTTP--EERROR:  url:' . $url . ",参数:" . json_encode($param) . "  返回状态码： $httpCode,时间是：" . date('Y-m-d H:i:s');
        $log_service::sendMessage('error', $msg);
    }

    curl_close($ch);
    if ($ret_json) {
        $return_data = json_decode($data, true);
    }
    return $return_data;
}

function arr_foreach($arr)
{
    static $tmp = array();
    if (!is_array($arr)) {
        return false;
    }
    foreach ($arr as $val) {
        $tmp[$val['id']] = $val['knowledge_code'];

    }
    return $tmp;
}

function arr_foreachzh($arr)
{
    static $tmp = array();
    if (!is_array($arr)) {
        return false;
    }
    foreach ($arr as $val) {
        foreach ($val as $kk => $vv) {
            $tmp[$vv['id']] = isset($vv['knowledge_code']) ? $vv['knowledge_code'] : 'cz1014' . rand(0, 9);
        }

    }
    return $tmp;
}

function dateof($time)
{
    return date('Y年m月d日 H时i分s秒');
}

function htmlspecialchars_decode_and_replace($data)
{
    return htmlspecialchars_decode(str_replace('##$$##', '___________', $data));
}

function replace_and_htmlspecialchars_decode($data)
{
    $str = str_replace('&lt;b', '&lt; b', $data);  //å°a<b è¿ç§æ°æ®æ¿æ¢æa< b
    $str = str_replace('&lt;x', '&lt; x', $str);
    $str = str_replace('&lt;1', '&lt; 1', $str);
    $str = str_replace('&lt;4', '&lt; 4', $str);
    $str = str_replace('&lt;6', '&lt; 6', $str);
    $str = str_replace('&lt;3', '&lt; 3', $str);
    $str = str_replace('&lt;d', '&lt;  d', $str); //å°a<d è¿ç§æ°æ®æ¿æ¢æa< d
    $str = str_replace('&lt; br', '&lt;br', $str);//ç±äºç¬¬ä¸æ­¥æ¿æ¢ä¼å°<br> <br/>æ¿æ¢æ< br>
    return htmlspecialchars_decode($str);
}


/**
 * 简单对称加密算法之加密
 * @param String $string 需要加密的字串
 * @param String $skey 加密EKY
 * @return String
 */
function token_encode($string = '', $skey = '')
{
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key] .= $value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}

/**
 * 简单对称加密算法之解密
 * @param String $string 需要解密的字串
 * @param String $skey 解密KEY
 * @return String
 */
function token_decode($string = '', $skey = '')
{
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return json_decode(base64_decode(join('', $strArr)), true);
}

/*
 * 验证token
 * @param string $token_str 待验证token字符串
 * @param string $token_key token解码公钥
 * @param string $token_verify token验证公钥
 * @return array 解码后信息,返回false标识验证失败
 */
function token_verify($token_str, $token_key = null, $token_verify = null)
{
    if (is_null($token_key)) {
        $token_key = config('token_key') !== null ? config('token_key') : '';
    }

    if (is_null($token_verify)) {
        $token_verify = config('token_verify') !== null ? config('token_verify') : '';
    }


    $decode_ret = token_decode($token_str, $token_key);

    // || !isset($decode_ret['verify']) || $decode_ret['verify'] !== $token_verify
    if (empty($decode_ret)) {
        return false;
    }

    return $decode_ret;
}


/**
 * 获取2个时间差
 * @param $startDate
 * @param $endDate
 *
 * @return string
 */
function dateFormat($startDate, $endDate)
{
    $time = $endDate - $startDate;
    $year = floor($time / 60 / 60 / 24 / 365);
    $time -= $year * 60 * 60 * 24 * 365;
    $month = floor($time / 60 / 60 / 24 / 30);
    $time -= $month * 60 * 60 * 24 * 30;
    $week = floor($time / 60 / 60 / 24 / 7);
    $time -= $week * 60 * 60 * 24 * 7;
    $day = floor($time / 60 / 60 / 24);
    $time -= $day * 60 * 60 * 24;
    $hour = floor($time / 60 / 60);
    $time -= $hour * 60 * 60;
    $minute = floor($time / 60);
    $time -= $minute * 60;
    $second = $time;

    $str = "";
    if ($hour) {
        $str .= ($hour >= 10 ? $hour : "0" . $hour) . ":";
    } else {
        $str .= "00:";
    }
    if ($minute) {
        $str .= ($minute >= 10 ? $minute : "0" . $minute) . ":";
    } else {
        $str .= "00:";
    }
    if ($second) {
        $str .= ($second >= 10 ? $second : "0" . $second);
    } else {
        $str .= "00";
    }
    return $str;


}

/**
 * 根据用户的能力获取他的星级
 * @param $ability
 */
function getAbilityRating($ability)
{
    if ($ability >= 0.8) {
        $rating = 1;
    } else if ($ability >= 0.7) {
        $rating = 2;
    } else if ($ability >= 60) {
        $rating = 1;
    } else {
        $rating = 0;
    }
    return $rating;

}


/**
 * 知识点掌握率的等级
 * @param $percent
 * @return int
 */
function getHasLearnedPercentLevel($percent)
{


    if ($percent > 86) {
        $level = 1;
    } else if ($percent > 70) {
        $level = 2;
    } else if ($percent > 60) {
        $level = 3;
    } else if ($percent > 50) {
        $level = 4;
    } else {
        $level = 5;
    }
    return $level;
}

/**
 * 获取答对率的等级
 * @param $percent
 * @return int
 */
function getRightAnswerPercentLevel($percent)
{


    if ($percent > 86) {
        $level = 1;
    } else if ($percent > 70) {
        $level = 2;
    } else if ($percent > 60) {
        $level = 3;
    } else if ($percent > 50) {
        $level = 4;
    } else {
        $level = 5;
    }
    return $level;
}


/**
 * 获取简评
 * @param $score
 */
function getJianPing($weakElements, $knowledgeList, $rightNumber, $wrongNumber)
{
    $allKnowledge = count($knowledgeList);
    $hasLearnedKnowledge = count($knowledgeList) - count($weakElements);

    $percent_A = (count($weakElements) / count($knowledgeList)) * 100;
    $percent_B = ($rightNumber / ($rightNumber + $wrongNumber)) * 100;

    $pingYu_A = [
        1 => "这次测试中的知识点掌握得非常好。",
        2 => "这次测试中的知识点掌握程度较好。",
        3 => "这次测试中的知识点掌握程度中等。",
        4 => "这次测试中的知识点掌握程度一般。",
        5 => "这次测试中的知识点掌握程度较低。",
    ];
    $pingYu_B = [
        1 => "正答率在同年级学生中处于较高的位置。",
        2 => "正答率在同年级学生中处于中上的位置。",
        3 => "正答率在同年级学生中处于中等的位置。",
        4 => "正答率在同年级学生中处于中下的位置。",
        5 => "正答率在同年级学生中处于较后的位置。",
    ];

    $level_A = getHasLearnedPercentLevel($percent_A);
    $level_B = getRightAnswerPercentLevel($percent_B);


    if ($hasLearnedKnowledge > 0 && $hasLearnedKnowledge < $allKnowledge) {
        $notHasLearnedKnowledge = $allKnowledge - $hasLearnedKnowledge;
        $pingYu_C = "已掌握{$hasLearnedKnowledge}个知识点，未掌握{$notHasLearnedKnowledge}个知识点。";
    }
    if ($hasLearnedKnowledge == $allKnowledge) {
        $pingYu_C = "掌握了所有知识点。";
    }

    if ($hasLearnedKnowledge == 0) {
        $pingYu_C = "所有知识点暂未掌握。";
    }

    if ($rightNumber == 0) {
        $pingYu_C .= "还需要努力提升自己的答题正确率。";
    } else {


        if ($wrongNumber > 0) {
            $pingYu_C .= "做对{$rightNumber}题，做错{$wrongNumber}题。";
        } else {
            $pingYu_C .= "所有题目都回答正确。";
        }
    }


    $pingYu = [$pingYu_C, $pingYu_A[$level_A], $pingYu_B[$level_B]];
    return $pingYu;


}

/**
 * 获取总结分析
 * @param $score
 */
function getSummary($weakElements, $knowledgeList, $rightNumber, $wrongNumber)
{

    $percent_A = (count($weakElements) / count($knowledgeList)) * 100;
    $percent_B = ($rightNumber / ($rightNumber + $wrongNumber)) * 100;

    $pingYu_A = [
        1 => "在刚刚完成的测试中，显示数学核心知识点已基本掌握，已攻克本学期学习知识点中的“硬骨头”。",
        2 => "在刚刚完成的测试中，显示已攻克本学期学习知识点中的2/3的内容。",
        3 => "在刚刚完成的测试中，显示已突破本学期基础知识点大关！但作为强大潜力股仍有进步空间。",
        4 => "在刚刚完成的测试中，显示已基本掌握本学期基础知识！但在知识点的熟练运用及突破领域还有新大陆等待去开发哦！",
        5 => "在刚刚完成的测试中，显示已掌握本学期部分知识点，但仍有部分知识点的理解有些偏差，需要继续努力，发挥不怕苦不怕累的战斗精神，将其一举拿下！",
    ];

    $pingYu_B = [
        1 => "对数学概念的理解清晰透彻，解题方法完整。",
        2 => "对数学概念的理解基本到位，需要优化解题方法。",
        3 => "对数学概念的理解基本到位，需要掌握基本解题方法。",
        4 => "对数学概念的理解需要加强，需要掌握基本解题方法。",
        5 => "对数学概念的理解急需加强，急需掌握基本解题方法。",
    ];

    $level_A = getHasLearnedPercentLevel($percent_A);

    $level_B = getRightAnswerPercentLevel($percent_B);
    if ($weakElements) {
        $tagName = [];
        foreach ($weakElements as $item) {
            $tagName[] = getTagName($item, $knowledgeList);
        }
        $pingYu_C = "系统判断出薄弱知识点是" . implode("、", $tagName) . "。";
    } else {
        $pingYu_C = "系统判断出暂无薄弱知识点，知识点掌握的很扎实。";
    }


    $pingYu = [$pingYu_C, $pingYu_A[$level_A], $pingYu_B[$level_B]];
    return $pingYu;
}


/**
 * 获取指导
 * @param $score
 */
function getGuidance($weakElements, $knowledgeList, $rightNumber, $wrongNumber)
{

    $percent_A = (count($weakElements) / count($knowledgeList)) * 100;
    $percent_B = ($rightNumber / ($rightNumber + $wrongNumber)) * 100;
    $pingYu_A = [
        1 => "已对数学知识有较为高度的理解，继续坚持，一定能有理想的收获！",
        2 => "已对数学知识有一定高度的理解，再继续前进，拔高对概念和知识的深化理解，更上一层楼！",
        3 => "建议在接下来的学习中认真观看名师视频，仔细记笔记并养成课后复习的好习惯，把知识盲区全部扫清！",
        4 => "建议需加强对基础知识的巩固，加深理解，多做练习，攻克难关，提高对数学学习的热情！",
        5 => "对知识点和概念的认识有所欠缺，但是只要坚定学习信念，加强基础概念吸收，只要下功夫，一定有所突破！",
    ];
    $pingYu_B = [
        1 => "正答率排在同年级学生中较高的位置，建议在接下来的学习中认真观看名师视频，把为数不多的知识小漏洞消灭。",
        2 => "正答率排在同年级学生中的中上位置，接下来要认真学习名师视频，梳理笔记，从细节做起，把知识的掌握更加细致系统化。",
        3 => "正答率排在同年级学生的中等位置，坚持是最好的方法，把名师视频，精编讲义，巩固练习结合，不断进行操练，相信不久就可以更上一层楼啦。",
        4 => "正答率排在同年级学生中的中下位置，接下来要严格按照学习流程，不能有任何偷懒，把名师视频钻研透彻，认真观看例题精讲，提高解题技巧，相信可以事半功倍哦",
        5 => "正答率排在同年级学生中的较后位置，在今后的学习中，希望能踏踏实实，一步一个脚印地走下去，除了熟悉基础知识点外，还需要将基本的题目做到深入了解，稳步提升。",
    ];

    $level_A = getHasLearnedPercentLevel($percent_A);

    $level_B = getRightAnswerPercentLevel($percent_B);


    if ($weakElements) {

        $tagName = [];
        foreach ($weakElements as $item) {
            $tagName[] = getTagName($item, $knowledgeList);
        }
        $pingYu_C = "应该着重巩固" . implode("、", $tagName) . "这些薄弱知识点，建议借助智适应学习快速提升这些知识点的掌握程度。";
    } else {
        $pingYu_C = "知识点掌握程度比较扎实，建议借助智适应学习提升整体学习能力。";
    }

    $pingYu = [$pingYu_C, $pingYu_A[$level_A], $pingYu_B[$level_B]];
    return $pingYu;
}

/**
 * 获取知识点的名称
 * @param $tagCode
 * @param $knowledgeList
 * @return string
 */
function getTagName($tagCode, $knowledgeList)
{
    $tagName = "";
    foreach ($knowledgeList as $item) {
        if ($item["tag_code"] == $tagCode) {
            $tagName = $item["tag_name"];
            break;
        }

    }

    return $tagName;
}


/**
 * 获取服务器的IP。
 * @return string
 */
function get_server_ip()
{
    if (isset($_SERVER)) {
        if ($_SERVER['SERVER_ADDR']) {
            $server_ip = $_SERVER['SERVER_ADDR'];
        } else {
            $server_ip = $_SERVER['LOCAL_ADDR'];
        }
    } else {
        $server_ip = getenv('SERVER_ADDR');
    }
    return $server_ip;
}

/**
 * 获取域名
 * @return string
 */
function getDomain()
{
    $domain = request()->domain();
    return $domain;
}

/**
 * 获取Api
 * @return mixed
 */
function get_api_server_user()
{
//    $domain = getDomain();
//    if (strstr($domain, "classba.cn") !== false) {
//        $api_url = config("api_server_user");
//    } else if (strstr($domain, "171xue.com") !== false) {
//        $api_url = config("api_server_user_171xue");
//    } else {
//        $api_url = config("api_server_user_test");
//    }
    $api_url = config('api_server_user');
    return $api_url;

}

/**
 *
 * @param type $time 时间戳
 * @return string
 */
function dateFormatForMicroTime($time)
{

    $year = floor($time / 60 / 60 / 24 / 365);
    $time -= $year * 60 * 60 * 24 * 365;
    $month = floor($time / 60 / 60 / 24 / 30);
    $time -= $month * 60 * 60 * 24 * 30;
    $week = floor($time / 60 / 60 / 24 / 7);
    $time -= $week * 60 * 60 * 24 * 7;
    $day = floor($time / 60 / 60 / 24);
    $time -= $day * 60 * 60 * 24;
    $hour = floor($time / 60 / 60);
    $time -= $hour * 60 * 60;
    $minute = floor($time / 60);
    $time -= $minute * 60;
    $second = $time;

    $str = "";
    if ($hour) {
        $str .= ($hour >= 10 ? $hour : "0" . $hour) . ":";
    }
    if ($minute) {
        $str .= ($minute >= 10 ? $minute : "0" . $minute) . ":";
    } else {
        $str .= "00:";
    }
    if ($second) {
        $str .= ($second >= 10 ? $second : "0" . $second);
    } else {
        $str .= "00";
    }
    return $str;


}

/**
 * 判断是否手机访问
 */
function isMobileVisit()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;

    //此条摘自TPM智能切换模板引擎，适合TPM开发
    if (isset ($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'])
        return true;
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        );
        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

/**
 *编码
 * @param string $string
 */
function urlsafe_b64encode($string)
{
    $data = base64_encode($string);
    $data = str_replace(array('+', '/'), array('-', '_', ''), $data);
    return $data;

}

/**
 *base64解码url
 * @param string $string
 * @return 如果传的值是一个没有编码的字符串，则返回空
 */
function urlsafe_b64decode($string)
{
    $data = str_replace(array('-', '_'), array('+', '/'), $string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('===', $mod4);
    }
    return base64_decode($data);
}


/**
 * 加载静态资源。
 * @param $static_source
 */
function loadResource($static_source,$need_version=true)
{
    $prefix  = SERVER_URL;
    if($need_version)
    {
        $url ="http://".$prefix."/".$static_source."?v=".FRONT_VERSION;
    }else{
        $url ="http://".$prefix."/".$static_source;
    }
    echo $url;
}

/**
 * 替换试题中的__1__
 * @param type $data
 * @return type
 */
function html_replace($data)
{
    $data = str_replace('##$$##', '_________', $data);
    $data = preg_replace("/___\d+___/", "________", $data);;
    return $data;
}


/**
 * 生成一个日志需要的TransIdtrans_id
 * @return type
 */
function buildTransId()
{
    $mtime = intval(microtime(true) * 1000);
    $randv = rand(0, 99999);
    return($mtime . $randv);
}


//布鲁姆认知层次
function nengLiConfig()
{
    $config = [
                'sg'=>    '数感',
                'fhys'=> '符号意识',
                'kjgn'=>'空间观念',
                'jhzg'=> '几何直观',
                'sjfx'=> '数据分析观念',
                'ysnl'=> '运算能力',
                'tlnl'=>'推理能力',
                'mxsx'=> '模型思想',
                'yyys'=> '应用意识',
                'cxys'=> '创新意识',
               ];
    return $config;
}