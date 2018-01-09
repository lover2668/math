<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>暑期课数学</title>
    <meta name="keywords" content="上海乂学教育科技有限公司-暑期课数学"/>
    <meta name="description" content="上海乂学教育科技有限公司-暑期课程系列（数学）"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" type="text/css" href="{:loadResource('classba/assets/bootstrap/css/bootstrap.min.css')}">
    <!--<link rel="stylesheet" type="text/css" href="../../static/app/common/yx_font/yx_iconfont.css">-->
    <link rel="stylesheet" type="text/css" href="{:loadResource('classba/app/physical/css/style.css')}">
    <link rel="stylesheet" type="text/css" href="{:loadResource('classba/app/physical/css/base.css')}">
    <link href="{:loadResource('classba/app/math/css/math.css')}" rel="stylesheet">
    <style>

        .line-left{
            float: left;
            width:128px;

        }
        .line-left>p{
            font-size:14px;
            color:#999999;
            letter-spacing:0;
            text-align:right;
        }
        .line-right{
            float: right;
            width:255px;
        }
        .line-right>h3,.line-right>a{
            display: inline-block;
            margin:0;
        }
        .line-right>h3{
            font-size:20px;
            color:#333333;
            letter-spacing:0;
            line-height:24px;
            text-align:left;
        }
        .line-right>a{
            background:#ffffff;
            border: 1px solid #16CC6C;
            box-shadow:0 8px 8px 0 rgba(18,181,191,0.16);
            border-radius:16px;
            width:87px;
            height:30px;
            font-size:14px;
            color:#16CC6C;;
            letter-spacing:0;
            line-height:30px;
            text-align:center;
            text-decoration: none;
            float: right;
        }
        #timeline .timeline-item{
            height:30px;
            line-height: 30px;
        }
        #timeline .timeline-item:first-child{
            margin:0;
        }
        #timeline>.timeline-item:not(:first-child){
            top:55px;
        }
        /*#timeline>.timeline-item:nth-last-child(2),#timeline>.timeline-item:nth-last-child(3),#timeline>.timeline-item:nth-last-child(4){*/
            /*top:45px;*/
        /*}*/
        /*#timeline>.timeline-item:nth-last-child(2)>.timeline-icon,#timeline>.timeline-item:nth-last-child(3)>.timeline-icon,#timeline>.timeline-item:nth-last-child(4)>.timeline-icon{*/
            /*top:4px;*/
        /*}*/
    </style>
</head>
<body>
<div class="xx-header">
    <div class="xx-navbar">
        <div class="xx-navbar-left xx-navbar-logo">
        </div>
        <div class="xx-navbar-left xx-nav">
            {block name="title"}

            {/block}
        </div>
        <ul class="xx-navbar-right xx-navbar-menu">
            <li class="xx-navbar-left xx-report">
                <i class="xx-icon">&#xe656;</i>&nbsp;我的报告
            </li>
            <div class="hover-li">点击此处可查看所有本课程学习报告<i class="yxiconfont">&#xe65f;</i></div>
            <li class="xx-navbar-left xx-account"><i class="xx-icon">&#xe65b;</i>&nbsp;<?php echo session("real_name")?session("real_name"):session("username") ?></li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="fc-practice">
        <h1>我的报告中心</h1>
        <div id="timeline">
            <div class="timeline-item">
                <div class="timeline-icon">

                </div>
                <div class="timeline-content line-left">

                    <p>
                        {$l1_xiance_time}
                    </p>

                </div>
                <div class="timeline-content line-right">

                    <h3>
                        L1先行测试报告
                    </h3>
                    <a href="{:url('summer/report/prereport',array('topicId'=>$topicId,'is_all'=>1))}">查看报告</a>
                </div>
            </div>
            {if condition='$l1_studyModule_time'}
                <div class="timeline-item">
                    <div class="timeline-icon">

                    </div>
                    <div class="timeline-content line-left">

                        <p>
                            {$l1_studyModule_time}
                        </p>

                    </div>
                    <div class="timeline-content line-right">

                        <h3>
                            L1知识点学习报告
                        </h3>
                        <a href="{:url('summer/report/learningReport',array('topicId'=>$topicId,'is_all'=>1))}">查看报告</a>
                    </div>
                </div>
            {/if}
            {if condition="$l2_xiance_is_end eq  1"}
                <div class="timeline-item">
                    <div class="timeline-icon">

                    </div>
                    <div class="timeline-content line-left">

                        <p>
                            {$l2_xiance_time}
                        </p>

                    </div>
                    <div class="timeline-content line-right">

                        <h3>
                            L2先行测试报告
                        </h3>
                        <a href="{:url('summer/cindex/preReport',array('topicId'=>$topicId,'is_show_nextstep'=>0))}">查看报告</a>
                    </div>
                </div>
            {/if}


            {if condition="$l2_bxbl_is_end eq  1 &&  $is_show_l2_bxbl_report"}
            <div class="timeline-item">
                <div class="timeline-icon">

                </div>
                <div class="timeline-content line-left">

                    <p>
                        {$l2_bxbl_time}
                    </p>

                </div>
                <div class="timeline-content line-right">

                    <h3>
                        L2学情报告
                    </h3>
                    <a href="{:url('summer/cbxbl/studyReport',array('topicId'=>$topicId,'is_show_nextstep'=>0))}">查看报告</a>


                </div>
            </div>
            {/if}




            {if condition="$l2_jingsai_is_end eq  1"}
            <div class="timeline-item">
                <div class="timeline-icon">

                </div>
                <div class="timeline-content line-left">

                    <p>
                        {$l2_jingsai_time}
                    </p>

                </div>
                <div class="timeline-content line-right">

                    <h3>
                        L2竞赛拓展报告
                    </h3>
                    <a href="{:url('summer/czhlx/zhlxReport',array('topicId'=>$topicId,'is_show_nextstep'=>0))}">查看报告</a>
                </div>
            </div>
            {/if}


        </div>
    </div>
</div>
<script src="{:loadResource('classba/assets/jquery/jquery.js')}" type="text/javascript"></script>
<script src="{:loadResource('classba/assets/bootstrap/js/bootstrap.min.js')}" type="text/javascript"></script>
<script>
    $(document).ready(function(){

    });
</script>
</body>
</html>