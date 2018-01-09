<?php
/**
 * Created by PhpStorm.
 * User: yuan
 * Date: 16/5/12
 * Time: 下午3:09
 */
namespace service\lib;
use think\Request;
use think\log;
use service\log\LogService;


class CheckAnswer
{

    function compare_tiankong_answer($old, $new)
    {
        $old = json_decode($old,true);
        $new = json_decode($new,true);

        for($i = 0; $i<count($old); $i++)
        {
            for($j = 0; $j<count($new[$i]); $j++) {
                $b = $this->trim_tiankong_answer($new[$i][$j]);

                $res = false;
                for($k = 0; $k<count($old[$i]); $k++)
                {
                    $a = $this->trim_tiankong_answer($old[$i][$k]);
//                    echo "新答案（单个）：".$b.",题库中的答案（单个）：".$a."<br>";
                    $log_service = new logService();
                    $topic = "info";
                    echo  "fsdf";
                    exit;
                    $log_service::sendMessage($topic,"新答案（单个）：".$b.",题库中的答案（单个）：".$a."<br>");
                    if($a == $b){
                        $res = true;
                        break;
                    }
                }
                if(!$res)
                {
                    return false;
                }
            }
        }
        return true;
    }

//    public static  function trim_tiankong_answer($answer)
//    {
//        //ascii不可见字符
//        $answer=preg_replace('/[\xc2\xa0]/','',$answer);
//
//        //latex里的字体
//        $answer = str_replace('\rm','',$answer);
//
//
//        //left和right
//        $answer = str_replace('\left','',$answer);
//        $answer = str_replace('\right','',$answer);
//        $answer = str_replace('或',',',$answer);
//        $answer = str_replace(' ','',$answer);
//        $answer = str_replace('\,','',$answer);
//        $answer = str_replace('，',',',$answer);
//        $answer = str_replace('{','',$answer);
//        $answer = str_replace('}','',$answer);
//        $answer = str_replace('\(','',$answer);
//        $answer = str_replace('\)','',$answer);
//        $answer = str_replace('\[','',$answer);
//        $answer = str_replace('\]','',$answer);
//        return $answer;
//    }


    public static function trim_tiankong_answer($answer)
    {
        //ascii不可见字符
//        $answer=preg_replace('/[\xc2\xa0]/','',$answer);//解决的问题是空格
        $answer=preg_replace('/\xc2\xa0/','',$answer);

        //latex里的字体
        $answer = str_replace('\rm','',$answer);

        //left和right
        $answer = str_replace('\left','',$answer);
        $answer = str_replace('\right','',$answer);
        $answer = str_replace('或',',',$answer);
        $answer = str_replace(' ','',$answer);
        $answer = str_replace('\,','',$answer);
        $answer = str_replace('，',',',$answer);
        $answer = str_replace('{','',$answer);
        $answer = str_replace('}','',$answer);
        $answer = str_replace('\(','',$answer);
        $answer = str_replace('\)','',$answer);
        $answer = str_replace('\[','',$answer);
        $answer = str_replace('\]','',$answer);
        $answer = str_replace('\times','×',$answer);//公式的乘号改成
        return $answer;
    }



    public static function checkLatexEqual($formulaA,$formulaB)
    {

        if(empty($formulaB)||empty($formulaA))
        {
            return  $is_right = 0;
        }

        $latexPresetVar = '$';
        foreach(range('A','Z') as $v)
        {
            $presetVal = rand(100,200);
            $latexPresetVar .= ' \varcalc{'.$v.'}{'.$presetVal.'}';
        }

        foreach(range('a','z') as $v)
        {
            if($v == 'e')
            {
                $httpStatusCode = 401;
                $message = '试题中包含e字母,所以无法计算';
                continue;
            }
            $presetVal = rand(100,200);
            $latexPresetVar .= ' \varcalc{'.$v.'}{'.$presetVal.'}';
        }
        $latexPresetVar .= '$';
//        $latexPresetVar = '$\varcalc{x}{100}$';
        //    $formulaA = '\frac {1-b} {2}';
        //    $formulaB = '-\frac {b-1} {2}';
        $tex = '\documentclass[12pt]{article}
            \begin{document}
            \begin{enumerate}
            \item set '.$latexPresetVar.' 
            \item formulaA is $\solver{'.$formulaA.'} answer_formulaA====\answer----answer_formulaA$
            \item formulaB is $\solver{'.$formulaB.'} answer_formulaB====\answer----answer_formulaB$
            \end{enumerate}
            \end{document}';


        log::record(__METHOD__."----params: -----1:answer-----".$formulaA."------2:answer----".$formulaB);
        log::record(__METHOD__."-----tex----".$tex);

        $tex_file_name = 'latex'.microtime(true).'_'.rand(1,10000).'.tex';
        log::record(__METHOD__."-----tex_file_name-----".$tex_file_name);
//        $dir =  APP_PATH."../www/uploads/";//记录路径
        file_put_contents('/tmp/'.$tex_file_name, $tex);

        if(!file_exists("/usr/local/bin/latexcalc"))
        {
            $server_ip = $_SERVER['SERVER_ADDR'];
            log::error("服务器--".$server_ip."-----usr/local/bin/latexcalc  flie  not  exist");
            return  $is_right = false;
        }

        $tex_calc_res = $tex_file_name.'.res';
        $calc_res = exec('/usr/local/bin/latexcalc /tmp/'.$tex_file_name.' > /tmp/'.$tex_calc_res);
        $calc_res = file_get_contents('/tmp/'.$tex_calc_res);
        $preg_res = array();
        preg_match('/answer_formulaA====(.*?)----answer_formulaA/', $calc_res, $preg_res);
//        var_dump($preg_res);
        log::record(__METHOD__."----formulaA-----preg_res:---".json_encode($preg_res));

        if(!isset($preg_res[1]))
        {
            return $is_right = false;
        }
        $answerA = $preg_res[1];
        preg_match('/answer_formulaB====(.*?)----answer_formulaB/', $calc_res, $preg_res2);
        if(!isset($preg_res2[1]))
        {
            return $is_right = false;
        }
        $answerB = $preg_res2[1];
        log::record(__METHOD__."-----formulaA------preg_res:----".json_encode($preg_res));
        if($answerA == $answerB)
        {
            $is_right=true;
        }
        else
        {
            $is_right = false;
        }
        return  $is_right;
    }


    public function testCheckLatexEqual()
    {
        $formulaA = '\frac {1-b} {2}';
        $formulaB = '-\frac {b-1} {2}';
        $formulaB = "";
        $formulaA = "";
        $is_right =  testLatexEqual($formulaA,$formulaB);
        var_dump($is_right);
    }






}


?>
