<?php
namespace service\services;

use service\org\util\TpString;
use service\algo\AlgoLogic;
use  think\Db;
use  think\Log;
use think\Cache;
use service\log\LogService;
use service\lib\RabbitClientService;
use YXLog\YXLog;

class BaseQuestionV2Service extends CommonService
{
    protected static $question_server_host;
    public function __construct()
    {
        //初始化并执行curl请求
        self::$question_server_host = config("v2_question_server_host");
    }


    /**
     * @api {get} getQuestionById   根据试题ID获取试题
     * @apiVersion 0.0.1
     * @apiName  getQuestionById
     * @apiGroup   question_service
     * @apiParam {String} question_id 试题ID.
     * @apiSuccess {String} id  试题ID
     * @apiSuccess {Number} q_type  试题类型
     * @apiSuccess {String} content  试题内容
     * @apiSuccess {String} options  试题选项
     * @apiSuccess {String} answer  试题答案
     * @apiSuccess {String} analyze  试题解析
     * @apiSuccess {String} video_url  知识点链接
     * @apiSuccess {String} used_type  试题用户:测试题或学习题
     * @apiSuccess {Number} module  模块.   1: 先行测试   2:  边学边练  3: 综合测试
     * @apiSuccess {Number} difficulty  难度, 1-5 ,5个级别. 易--难
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *          "id":10,
     *          "content":"\u6d4b\u8bd5\u8bd5\u9898",
     *          "q_type":1,
     *          "options":[
     *              {
     *                  "key":"A",
     *                  "answer":" this is A"
     *              },
     *              {
     *                  "key":"B",
     *                  "answer":"this is B"
     *              },
     *              {
     *                  "key":"C",
     *                  "answer":"this is C"
     *              }
     *              ],
     *          "answer":[
     *              [
     *                  [
     *                      "is not"
     *                  ],
     *                  [
     *                          "Is NOt"
     *                  ],
     *                  [
     *                      "IS NOT"
     *                  ]
     *                  ],
     *                  [
     *                  [
     *                      "NOT"
     *                  ]
     *                  ],
     *              [
     *                  [
     *                      "Her"
     *                  ],
     *                  [
     *                      "her"
     *                  ]
     *              ]
     *          ],
     *          "analyze":[
     *              {
     *                  "title":"\u89e3\u9898\u65b9\u6cd5\u4e00",
     *                  "content":[
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u4e00\u6b65",
     *                          "is_has_answer":0
     *                      },
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                          "is_has_answer":1
     *                      },
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                          "is_has_answer":1
     *                      },
     *                      {
     *                          "content":"\u7b2c\u4e00\u79cd\u65b9\u6848\u7b2c\u56db\u6b65",
     *                          "is_has_answer":0
     *                      }
     *                  ]
     *              },
     *              {
     *                  "title":"\u89e3\u9898\u601d\u8def\u4e8c",
     *                  "content":[
     *                      [
     *                           {
     *                              "content":"\u7b2c\u4e8c\u79cd\u65b9\u6848\u7b2c\u4e00\u6b65",
     *                              "is_has_answer":0
     *                            },
     *                          {
     *                              "content":"\u7b2c\u4e8c\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                              "is_has_answer":1
     *                          },
     *                          {
     *                              "content":"\u7b2c\u4e8c\u79cd\u65b9\u6848\u7b2c\u4e8c\u6b65",
     *                              "is_has_answer":1
     *                          }
     *                      ]
     *                  ]
     *              }
     *          ],
     *      "video_url":"http:\/\/media1.classba.cn\/math_qm_05.mp4",
     *      "used_type":1,
     *      "module":"1"
     * }
     * @apiError UserNotFound The id of the User was not found.
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UserNotFound"
     *     }
     */
    public function getQuestionById($question_id)
    {
        $start_time=microtime(true);
        // $question_id = "57cecc81a66d2002513da717";
        // $question_id="57de608c14fef93f24446e04";
        // $question_id="57ddd85e14fef93f3b763623";
        session("getQuestionTime", time());//获取取题时间
        $key = "questionInfo:" . $question_id;
        $data = Cache::get($key);
        $data = array();   //暂时不走缓存。
        if (!$data) {
            $param['question_id'] = $question_id;
            $url = self::$question_server_host . "getQuestionById";
            $return_data = rpc_request($url, $param);
            $data = $return_data["data"];
            /*****日志埋点****/
            $log_service = new  LogService("tichi");
            $topic= "info";
            $msg = array(
                'request_api'=>"getQuestionById",
                'user_id'=> $this->getUserId(),
                'request_data'=>$param,   //请求数据,
                'response_data'=>$return_data,  //响应数据
                'stime'=> $start_time,     // 接口开始时间
                'etime'=>microtime(true),     // 接口结束时间
                'ctime'=> time()      // 创建时间。
            );
            $log_service::sendMessage($topic,$msg,'getQuestionById');
            /*****日志埋点****/

            //不是200表示返回错误。
            if($return_data['code']!=200)
            {
                $this->sendErrorCodeLog($url,$param);
            }

            if(empty($data))
            {
                $log_service = new logService();
                $log_service::sendMessage("error",__METHOD__."题库getQuestionById-----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
            } else {
                Cache::set($key, $data, 3600);
            }

            
        }
        $is_open_testInfo_for_question_content = config("is_open_testInfo_for_question_content");
        if($is_open_testInfo_for_question_content)
        {
            if(array_key_exists('content',$data))
            {
                $data['content'] = $data['content']."<br>----question_id--".$question_id;
                if(session('tag_code'))
                {
                    $data['content'] .= "---tag_code-----".session('tag_code');
                }
            }
        }
        if(!isset($data['estimates_time'])||!$data['estimates_time']){
            $data['estimates_time']= config('estimates_time');
        }
        $this->checkQuestionInfo($question_id,$data);
        return $data;
    }

    /**
     * 检测试题内容的问题
     * @param $return_data
     * @return string
     */
    public function checkQuestionInfo($question_id,$return_data)
    {
        $msg='';
        $url = self::$question_server_host . "getQuestionById";
        if($return_data==false){
            $msg.="错误=---url: $url,接口返回数据为null<br />";
        }else{
            if(isset($return_data['id'])==false||$return_data['id']==false){
                $msg.="错误==试题id为null<br />";
            }
            if(isset($return_data['content'])==false||$return_data['content']==false){
                $msg.="错误==试题内容为null<br />";
            }
            if(isset($return_data['q_type'])==false||$return_data['q_type']==false){
                $msg.="错误==试题类型null或为空<br />";
            }
            if(isset($return_data['content'])&&$return_data['q_type']==2&&!is_numeric(strpos(htmlspecialchars_decode($return_data['content']),'##$$##')))
            {

                $preg = "/[_]+[1-9]*[_]+/";
                preg_match_all($preg,$return_data['content'],$result);
                $num1= count($result[0]);
                $preg = "/##\\$\\$##/";
                preg_match_all($preg,$return_data['content'],$result);
                $num2 = count($result[0]);
                $num = $num1+$num2;
                if($num!=0)
                {
                    if(isset($return_data['answer']))
                    {
                        $answer_num = count($return_data['answer']);
                        if($num !=$answer_num)
                        {
                            $msg.="错误==填空题题目的答案数和题干中的特殊替换符号数量不符合,答案是: $answer_num  个,但题干中的替换符号数为: $num<br />";
                        }
                    }else{
                        $msg.="错误==题目没有正确答案<br />";
                    }
                }else{
                    $msg.="错误==填空题题目中没有包含填空符号 ##$$## 或者 ___*___<br />";
                }
            }
            if(isset($return_data['options'])&&$return_data['options']==false&&($return_data['q_type']==1||$return_data['q_type']==3)){
                $msg.="错误==选择题目没有选项<br />";
            }

            if(isset($return_data['answer'])&& (count($return_data['answer'])==0)){
                $msg.="错误==题目没有正确答案<br />";
            }
            if(isset($return_data['analyze']))
            {
                if(empty($return_data['analyze']))
                {
                    $msg.="分布解析为null<br />";
                }else{

                    foreach ($return_data['analyze'] as $k=>$v)
                    {
                        if(isset($v['content'])&&empty($v['content']))
                        {
                            $msg.="分布解析为null<br />";
                        }else{

                        }

                    }
                }



            }

            if(isset($return_data['analyze'])&& count($return_data['analyze'])<=0){
                $msg.="错误==分布解析数据类型为空<br />";
            }
        }
        if($msg)
        {
            $log_service = new logService();
            $log_service::sendMessage('error',__METHOD__.",试题ID为: $question_id -------".$msg);
            Log::info(__METHOD__.",试题ID为: $question_id -------".$msg);

        }
        return  $msg;

    }





    /**
     * @api {get} getQuestionsByKnowledge   根据知识点获取试题
     * @apiVersion 0.0.1
     * @apiName  getQuestionsByKnowledge
     * @apiGroup question_service
     *
     * @apiParam {String} knowledge  知识点.
     * @apiParam {Number} module  模块.
     * @apiParam {Number} used_type  用途.
     *
     * @apiSuccess {String} id  试题ID
     * @apiSuccess {Number} difficulty  难度, 1-5 ,5个级别. 易--难
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *  {
     *         {
     *            "id":10,
     *           "difficulty":1
     *          },
     *          {
     *             "id":11,
     *             "difficulty":2
     *          }
     * }
     * @apiError UserNotFound The id of the User was not found.
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "error": "UserNotFound"
     *     }
     */
    public function getQuestionsByKnowledge($knowledge, $module, $used_type="",$difficulty="")
    {
        $start_time=microtime(true);
        $key = "questionsByKnowledge:" . $knowledge . ":" . $module . ":" . $used_type;
        $return_data = Cache::get($key);
        $return_data  =array();
        if (!$return_data) {
            $param['knowledge'] = $knowledge;
            $param['module'] = $module;
            if($used_type)
            {
                $param['used_type'] = $used_type;
            }
            if($difficulty)
            {
                $param['difficulty'] =$difficulty;
            }
            //根据知识点获取试题.
            $url = self::$question_server_host . "getQuestionsByKnowledge";

            Log::info("---getQuestionsByKnowledge------api----url----".$url);
            Log::info("---getQuestionsByKnowledge------api----param----".json_encode($param));

            $return_data = rpc_request($url, $param);
            /*****日志埋点****/
            $log_service = new  logService("tichi");
            $topic= "info";
            $msg = array(
                'request_api'=>"getQuestionsByKnowledge",
                'user_id'=> $this->getUserId(),
                'request_data'=>$param,   //请求数据,
                'response_data'=>$return_data,  //响应数据
                'stime'=> $start_time,     // 接口开始时间
                'etime'=>microtime(true),     // 接口结束时间
                'ctime'=> time()      // 创建时间。
            );

            $return_data['code'] =200;
            //不是200表示返回错误。
            if($return_data['code']!=200)
            {
                $this->sendErrorCodeLog($url,$param);
            }
            $log_service::sendMessage($topic,$msg,'getQuestionsByKnowledge');
            /*****日志埋点****/

            Cache::set($key, $return_data, 3600 * 24);
        }

        if(empty($return_data['data']))
        {
            $log_service = new logService();
            $error_msg = "此----$knowledge ---知识点,调用知识点取题接口getQuestionsByKnowledge ,没有返回值 ,url:$url,参数为: ".json_encode($param);
            $log_service::sendMessage('error',__METHOD__.$error_msg);
        }

        return $return_data['data'];
    }



    /*
    * 临时需要的,后期要删除的
    */
    public function getQuestionInfo()
    {
        $id = rand(1, 100);
        $question_list = array(
            "id" => $id,
            "content" => "测试试题",
            "q_type" => 2,    // 1 ,单选,  2 : 填空  3: 多选
            "options" => array(
                array(
                    "key" => "A",
                    "answer" => " this is A"
                ),
                array(
                    "key" => "B",
                    "answer" => "this  is  B"
                ),
                array(
                    "key" => "C",
                    "answer" => "this  is C"
                )
            ),
            "answer" => "A|B",
            "analyze" => array(
                array(
                    "title" => "解题方法一",
                    "content" => array(
                        array(
                            "content" => "第一种方案第一步", "is_has_answer" => 0,
                        ),
                        array(
                            "content" => "第一种方案第二步", "is_has_answer" => 1,
                        ),
                        array(
                            "content" => "第一种方案第二步", "is_has_answer" => 1,
                        ),
                        array(
                            "content" => "第一种方案第四步", "is_has_answer" => 0
                        )
                    )
                ),
                array(
                    "title" => "解题思路二",
                    "content" => array(
                        array(
                            array(
                                "content" => "第二种方案第一步", "is_has_answer" => 0,
                            ),
                            array(
                                "content" => "第二种方案第二步", "is_has_answer" => 1,
                            ),
                            array(
                                "content" => "第二种方案第二步", "is_has_answer" => 1,
                            )
                        )
                    )
                )
            ),
            "video_url" => "http://media1.classba.cn/math_qm_05.mp4",
            "used_type" => 1,
            "module" => "1",
            "difficulty" => 1
        );
        return $question_list;

    }


    /**
     * @api {get} getQuestionIdsByModule   根据模块获取试题信息.
     * @apiVersion 0.0.1
     * @apiName  getQuestionIdsByModule
     * @apiGroup question_service
     *
     * @apiParam {Number} module  模块.
     *
     * @apiSuccess {String} id  试题ID
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       [
     *          {
     *             "id":"9",
     *          },
     *          {
     *              "id":"10"
     *          }
     *       ]
     */
    public function getQuestionIdsByModule($module,$used_type,$knowledge)
    {
        $start_time=microtime(true);
        $key = "questionIdsByModule:" . $module;
        $return_data = Cache::get($key);
        $return_data = array(); //暂时不走缓存
        if (!$return_data) {
            $param['module'] = $module;
            $param['used_type'] = $used_type;
            $param['knowledge'] = $knowledge;
            //根据知识点获取试题.
            $url = self::$question_server_host . "getQuestionIdsByModule";
            $return_data = rpc_request($url, $param);

            /*****日志埋点****/
            $log_service = new  logService("tichi");
            $topic= "info";
            $msg = array(
                'request_api'=>"getQuestionIdsByModule",
                'user_id'=> $this->getUserId(),
                'request_data'=>$param,   //请求数据,
                'response_data'=>$return_data,  //响应数据
                'stime'=> $start_time,     // 接口开始时间
                'etime'=>microtime(true),     // 接口结束时间
                'ctime'=> time()      // 创建时间。
            );
            $log_service::sendMessage($topic,$msg,'getQuestionIdsByModule');
            /*****日志埋点****/
            if(empty($result))
            {
                $log_service = new  logService();
                $log_service::sendMessage("error",__METHOD__."题库接口getKnowledgeList-----返回值为空,");
            }

            //不是200表示返回错误。
            if($return_data['code']!=200)
            {
                $this->sendErrorCodeLog($url,$param);
            }


            Cache::set($key, $return_data);
        }
        return $return_data['data'];
    }


    /**
     * @api {get} getZhlxQuestionIds   获取综合练习的试题.
     * @apiVersion 0.0.1
     * @apiName  getZhlxQuestionIds
     * @apiGroup question_service
     *
     *
     * @apiSuccess {String} id  试题ID
     * @apiSuccess {String} tag_code 知识点编码
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *       [
     *          {
     *             {
     *                "id": 9,
     *             },
     *             {
     *                "id":10,
     *             },
     *             {
     *                "id":11,
     *             }
     *         },
     *         {
     *             {
     *                "id": 9,
     *             },
     *             {
     *                "id":10,
     *              },
     *             {
     *                "id":11,
     *             }
     *         }
     *
     *     ]
     */
    public function getZhlxQuestionIds($topicId='')
    {
        $start_time=microtime(true);
//        return [
//            [
//                ['id'=>'57cec120a66d20026339c2b5'],
//                ['id'=>'57ceb9b6a66d2002513da716'],
//            ],
//            [
//                ['id'=>'57cec206a66d201aa50eb65b'],
//            ]
//        ];
        $key = "zhlxQuestionIds";
        $return_data = Cache::get($key);
        if (true) {
            $param = array();
            //根据知识点获取试题.
            $url = self::$question_server_host . 'getZhlxQuestionIds/tid/'.$topicId;//"v2/api/getZhlxQuestionIds";
            ///v2/api/getZhlxQuestionIds/textbook/r/period/c/grade/8/lesson/01
            $return_data = rpc_request($url, $param);
            /*****日志埋点****/
            if($return_data==false){
                $log_service = new  LogService();
                $topic= "error";
                $message  = "调用试题 getZhlxQuestionIds接口,返回值为空,地址为: $url".'parm:'. json_encode($param);
                $log_service::sendMessage($topic,__METHOD__.$message);
            }

            $log_service = new  logService("tichi");
            $topic= "info";
            $msg = array(
                'request_api'=>"getZhlxQuestionIds",
                'user_id'=> $this->getUserId(),
                'request_data'=>$param,   //请求数据,
                'response_data'=>$return_data,  //响应数据
                'stime'=> $start_time,     // 接口开始时间
                'etime'=>microtime(true),     // 接口结束时间
                'ctime'=> time()      // 创建时间。
            );
            $log_service::sendMessage($topic,$msg,'getZhlxQuestionIds');
            /*****日志埋点****/
            if(empty($return_data))
            {
                $log_service = new  logService();
                $log_service::sendMessage("error",__METHOD__."调用试题 getZhlxQuestionIds接口,返回值为空,地址为: $url");
            }

            //不是200表示返回错误。
            if($return_data['code']!=200)
            {
                $this->sendErrorCodeLog($url,$param);
            }

            Cache::set($key, $return_data);
        }
        return $return_data['data'];
    }


    /**
     * @api {get} getKnowledgeByCode   获取知识点信息.
     * @apiVersion 0.0.1
     * @apiName  getKnowledgeByCode
     * @apiGroup question_service
     *
     * @apiParam {String} knowledge  知识点编码.
     * @apiSuccess {Array} video     知识点视频信息 array("description":"描述","video_url":"abc.mp4","image_url":"abc_jpg"  )
     * @apiSuccess {String} description  视频属性
     * @apiSuccess {String} video_url  视频链接
     * @apiSuccess {String} image_url  图片地址
     * @apiSuccess {String} tag_name   知识点名称
     * @apiSuccess {String} tag_code   知识点编码
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *      'video'=>{
     *            {
     *                 ‘description’ => 描述,
     *                 ‘video_url’ => abc.mp4,
     *                 ‘image_url’ => abc.jpg
     *            },
     *            {
     *                 ‘description’ => 描述,
     *                 ‘video_url’ => abc.mp4,
     *                 ‘image_url’ => abc.jpg
     *            },
     *
     *      },
     *      'tag_name': "因式分解",
     *      'tag_code': "cz1401"
     *
     *     ]
     */
    public function getKnowledgeByCode($knowledge)
    {
        $start_time=microtime(true);
        $key = "knowledgeInfo:" . $knowledge;
        $data = Cache::get($key);
        $data = array(); //暂时不走缓存
        if (!$data) {
            $param = array();
            $param['knowledge'] = $knowledge;
            //根据知识点获取试题.
            $url = self::$question_server_host . "getKnowledgeByCode";
            $return_data = rpc_request($url, $param);
            $data = $return_data["data"];
            /*****日志埋点****/
            $log_service = new  logService("tichi");
            $topic= "info";
            $msg = array(
                'request_api'=>"getKnowledgeByCode",
                'user_id'=> $this->getUserId(),
                'request_data'=>$param,   //请求数据,
                'response_data'=>$return_data,  //响应数据
                'stime'=> $start_time,     // 接口开始时间
                'etime'=>microtime(true),     // 接口结束时间
                'ctime'=> time()      // 创建时间。
            );
            $log_service::sendMessage($topic,$msg,'getKnowledgeByCode');
            /*****日志埋点****/
            if(empty($data))
            {
                $log_service = new  logService();
                $log_service::sendMessage("error",__METHOD__."题库接口getKnowledgeByCode----返回值为空, 传参数为:".json_encode($param));
            }
            if($return_data['code']==200)
            {
                Cache::set($key, $data,3600);
            } else {
                $this->sendErrorCodeLog($url,$param);
            }           
        }
        return $data;
    }


    private function sendErrorCodeLog($url,$param)
    {
        $str_param = json_encode($param);
        $log_service = new LogService();
        $log_service::sendMessage("error",__METHOD__."题库接口返回code不是200, 链接地址: $url,  传递参数为: ".$str_param);

    }


    /**
     * 次接口临时调用老系统。
     * @return mixed
     */
    public function getTestQuestionsList()
    {

        $start_time=microtime(true);
        $key = "testQuestionsList";
        $return_data = Cache::get($key);
        if (!$return_data) {
            $param = array();
            //根据知识点获取试题.
            $url = "http://input-math.classba.cn/v2/api/questionIdList";
            $return_data = rpc_request($url, $param);

            /*****日志埋点****/
            $log_service = new  logService("tichi");
            $topic= "info";
            $msg = array(
                'request_api'=>"questionIdList",
                'user_id'=> $this->getUserId(),
                'request_data'=>$param,   //请求数据,
                'response_data'=>$return_data,  //响应数据
                'stime'=> $start_time,     // 接口开始时间
                'etime'=>microtime(true),     // 接口结束时间
                'ctime'=> time()      // 创建时间。
            );
//            $log_service::sendMessage($topic,$msg,'questionIdList');
            /*****日志埋点****/
//            if(empty($return_data))
//            {
//                $log_service = new  logService();
//                $log_service::sendMessage("error",__METHOD__."questionIdList----返回值为空, 传参数为:".json_encode($param));
//            }

            Cache::set($key, $return_data);
        }
        return $return_data;

    }


//    /**
//     * 获取综合题的新接口。
//     * @param $topic_code
//     * @return mixed
//     */
//    public function getQuestionsByTopicCode($topic_code)
//    {
//
//        $start_time=microtime(true);
//        $key = "topic_code:" . $topic_code;
//        $return_data = Cache::get($key);
//        if (!$return_data) {
//            $param = array();
//            $param['topic_code'] = $topic_code;
//            //根据知识点获取试题.
//            $url = self::$question_server_host . "getQuestionsByTopicCode";
//            $return_data = rpc_request($url, $param);
//            /*****日志埋点****/
//            $log_service = new  logService("tichi");
//            $topic= "info";
//            $msg = array(
//                'request_api'=>"getQuestionsByTopicCode",
//                'user_id'=> $this->getUserId(),
//                'request_data'=>$param,   //请求数据,
//                'response_data'=>$return_data,  //响应数据
//                'stime'=> $start_time,     // 接口开始时间
//                'etime'=>microtime(true),     // 接口结束时间
//                'ctime'=> time()      // 创建时间。
//            );
//            $log_service::sendMessage($topic,$msg,'getQuestionsByTopicCode');
//            /*****日志埋点****/
//            if(empty($return_data))
//            {
//                $log_service = new  logService();
//                $log_service::sendMessage("error",__METHOD__."题库接口getQuestionsByTopicCode----返回值为空, 传参数为:".json_encode($param));
//            }
//            //不是200表示返回错误。
//            if($return_data['code']!=200)
//            {
//                $this->sendErrorCodeLog($url,$param);
//            }
//
//
//            Cache::set($key, $return_data);
//        }
//        return $return_data['data'];
//
//
//
//    }

    /**
     * 批量取题的接口
     * @param Array $ids   例如 ['97179897083986938','589980b2f4aeb569992f06a1','5ef9d5b4-50e0-11e7-8c70-00163e1004d0' ]    
     * @return type
     */
    public function getQuestionListByIdes($question_ids) {
        $request_num = 20;  // 定义一次传递的code数量
        $return_data = [];
        $need_request_ids = []; // 需要请求的code

        if (!is_array($question_ids) || empty($question_ids)) {
            return $return_data;
        }

        foreach ($question_ids as $question_id) {
            $key = "questionInfo:" . $question_id;
            $questionInfo = Cache::get($key);
            $questionInfo = array();  // 暂时不走缓存
            if (!$questionInfo) {
                $need_request_ids[] = $question_id;
            } else {
                $return_data[$question_id] = $questionInfo;
            }
        }

        if (empty($need_request_ids)) {
            return $return_data;
        }

        $need_request_ids_arrays = array_chunk($need_request_ids, $request_num);  //把数组转化为小数组

        foreach ($need_request_ids_arrays as $value) {
            $ids = implode(",", $value);
            $questionInfo = $this->getQuestionByIds($ids);
//            var_dump($codeinfo);die;
            foreach ($value as $question_id) {
                $key = "questionInfo:" . $question_id;
                if (!empty($questionInfo[$question_id]) && isset($questionInfo[$question_id])) {
                    $return_data[$question_id] = $questionInfo[$question_id];
                    $is_open_testInfo_for_question_content = config("is_open_testInfo_for_question_content");
                    if($is_open_testInfo_for_question_content)
                    {
                        if(array_key_exists('content',$questionInfo[$question_id]))
                        {
                            $return_data[$question_id]['content'] = $questionInfo[$question_id]['content']."<br>----question_id--".$question_id;
//                            if(session('tag_code'))
//                            {
//                                $return_data[$question_id]['content'] .= "---tag_code-----".session('tag_code');
//                            }
                        }
                    }
                    $data = $questionInfo[$question_id];

                    if(!isset($data['estimates_time'])||!$data['estimates_time']){
                        $return_data[$question_id]['estimates_time']= config('estimates_time');
                    }
                    $this->checkQuestionInfo($question_id,$data);
                    Cache::set($key, $return_data[$question_id], 3600);
                } else {
                    $log_service = new LogService();
                    $log_service::sendMessage("error", "题库接口getQuestionByIdes----返回值错误,没有查到试题:".$question_id."的信息--" );
                    YXLog::error("题库接口getQuestionByIdes----返回值错误,没有查到试题:".$question_id."的信息--" );
                }
            }
        }
        return $return_data;
    }
    
    public function getQuestionByIds($ids)
    {
        $start_time=microtime(true);
        session("getQuestionTime", time());//获取取题时间
        $param['question_ids'] = $ids;
        $url = self::$question_server_host . "getQuestionByIds";
        $return_data = rpc_request($url, $param);
        //不是200表示返回错误。
        if($return_data['code']!=200)
        {
            $this->sendErrorCodeLog($url,$param);
        }

        if(empty($return_data['data']))
        {
            $log_service = new logService();
            $log_service::sendMessage("error",__METHOD__."题库getQuestionById-----返回值为空, 链接地址: $url,  传递参数为: ".json_encode($param));
        }            
        return $return_data['data'];
    }

}