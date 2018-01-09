<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>乂学-数学</title>
        <meta name="renderer" content="webkit">
        <!-- No Baidu Siteapp-->
        <meta http-equiv="Cache-Control" content="no-siteapp"/>
        <link rel="icon" type="image/png" href="__PUBLIC__/plugin/lib/i/yixue-tt-logo.png">
        <!-- Add to homescreen for Chrome on Android -->
        <meta name="mobile-web-app-capable" content="yes">
        <!-- Add to homescreen for Safari on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="乂学教育-数学产品"/>
        <meta name="msapplication-TileColor" content="#0e90d2">
        <link rel="stylesheet" href="{:loadResource('static/lib/css/amazeui.min.css')}">

        <!-- 选项按钮样式 -->
        <link rel="stylesheet" href="{:loadResource('static/math/css/xc_style.css')}">
        <link rel="stylesheet" href="{:loadResource('static/math/css/report_center.css')}">
        <style>
            .line-right>a.disabled{
                border:1px solid #ccc;
                box-shadow: none;
                color: #000;
                cursor: not-allowed;
            }

        </style>
    </head>
    <body>
        <header class="am-topbar xx-topbar-math">
            <div class="am-u-lg-9">
                <h1 class="am-topbar-brand xx-brand" style="margin-left: 40px;">
                    <a href="<?php echo config('logo_url'); ?>" class="am-text-ir"></a>
                </h1>
                <div class="xx-logo-badge">
                    <p class="xx-badge-title">课次</p>
                    <p class="xx-badge-title-num">1</p>
                </div>
                <div class="xx-subject"></div>
                <ul class="xx-subject-module">
        <!--            <li class="xx-subject-module-unit active"style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>1</span>&nbsp;&nbsp;&nbsp;先行测试</li>-->
                    <!--            <li class="xx-subject-module-unit unlearned" style="border-top-left-radius: 16px;border-bottom-left-radius: 16px;"><span>2</span>&nbsp;&nbsp;&nbsp;测试报告</li>-->
                    <!--            <li class="xx-subject-module-unit unlearned"><span>3</span>&nbsp;&nbsp;&nbsp;高效学习</li>-->
                    <!--            <li class="xx-subject-module-unit unlearned"><span>4</span>&nbsp;&nbsp;&nbsp;学习检测</li>-->
                    <!--            <li class="xx-subject-module-unit unlearned" style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;"><span>5</span>&nbsp;&nbsp;&nbsp;学情报告</li>-->
                </ul>
            </div>
            <div class="am-u-lg-3">
                <ul class="am-topbar-right" id="collapse-head">
                    <li class="am-dropdown" data-am-dropdown style="height:80px;">
                        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;" style="line-height: 80px;color:#fff;">
                            <span class="am-icon-user"></span>  <?php echo session("real_name") ? session("real_name") : session("username") ?><span class="am-icon-caret-down"></span>
                        </a>
                        <ul class="am-dropdown-content">
                            <li id="logout">&nbsp;&nbsp;&nbsp;<i class="am-icon-power-off"></i><a href="#">退出系统</a></li>
                        </ul>
                    </li>
                </ul>
            </div>


        </header>

        <div class="content">
            <div class="fc-practice">
                <h1>我的报告中心</h1>
                <div id="timeline">

                    <div class="timeline-item">
                        <div class="timeline-content line-left">
                            <p> 
                                {if condition="$is_end_xiance eq 1"}
                                {$xiance_report_info.time}
                                {else/}
                                暂无报告
                                {/if}
                            </p>
                        </div>
                        <div class="timeline-icon">

                        </div>
                        <div class="timeline-content line-right">
                            <h3>
                                先行测试报告
                            </h3>
                            {if condition="$is_end_xiance eq 1"}
                            <a href="{$xiance_report_info.url}" target="_blank">查看报告</a>
                            {else/}
                            <a class="disabled" href="javascript:;">查看报告</a>
                            {/if}

                            <!--                    <a href="#" class="fc-no-data">查看报告</a>-->

                        </div>
                    </div>


                    <div class="timeline-item">
                        <div class="timeline-item">
                            <div class="timeline-content line-left">
                                <p>
                                    {if condition="$is_end_bxbl eq 1"}
                                    {$bxbl_report_info.time}
                                    {else/}
                                    暂无报告
                                    {/if}
                                </p>
                            </div>
                            <div class="timeline-icon">

                            </div>
                            <div class="timeline-content line-right">
                                <h3>
                                    学情报告
                                </h3>
                                {if condition="$is_end_bxbl eq 1"}
                                <a href="{$bxbl_report_info.url}" target="_blank">查看报告</a>
                                {else/}
                                <a class="disabled">查看报告</a>
                                {/if}


                                <!--                        <a href="#" class="fc-no-data">查看报告</a>-->
                            </div>
                        </div>
                    </div>
                    {if condition="$flow_id eq 1"}
                    <div class="timeline-item">
                        <div class="timeline-item">
                            <div class="timeline-content line-left">
                                <p>
                                    {if condition="$is_end_jingsai eq 1"}
                                    {$jingsai_report_info.time}
                                    {else/}
                                    暂无报告
                                    {/if}
                                    </p>
                            </div>
                            <div class="timeline-icon">

                            </div>
                            <div class="timeline-content line-right">
                                <h3>
                                    竞赛拓展报告
                                </h3>
                                {if condition="$is_end_jingsai eq 1"}
                                <a href="{$jingsai_report_info.url}" target="_blank">查看报告</a>
                                {else/}
                                <a class="disabled" href="javascript:;">查看报告</a>
                                {/if}
                                

                                <!--                        <a href="#" class="fc-no-data">查看报告</a>-->
                            </div>
                        </div>
                    </div>
                    
                    {else/}
                    <div class="timeline-item">
                        <div class="timeline-item">
                            <div class="timeline-content line-left">
                                <p>
                                    {if condition="$is_end_mncs eq 1"}
                                    {$mncs_report_info.time}
                                    {else/}
                                    暂无报告
                                    {/if}
                                    </p>
                            </div>
                            <div class="timeline-icon">

                            </div>
                            <div class="timeline-content line-right">
                                <h3>
                                    模拟测试报告
                                </h3>
                                {if condition="$is_end_mncs eq 1"}
                                <a href="{$mncs_report_info.url}" target="_blank">查看报告</a>
                                {else/}
                                <a class="disabled" href="javascript:;">查看报告</a>
                                {/if}
                                

                                <!--                        <a href="#" class="fc-no-data">查看报告</a>-->
                            </div>
                        </div>
                    </div>
     {/if}
                </div>
            </div>
        </div>

        <script src="{:loadResource('static/lib/js/jquery.min.js')}"></script>
        <script src="{:loadResource('static/lib/js/amazeui.min.js')}"></script>
        <script>
            var $_CONFIG = {

            };
            $_CONFIG.uid = "<?php echo session("user_id") ?>"
        </script>

        <script>

        </script>
        <script src="{:loadResource('classba/assets/tools/xa.js')}"></script>
    </body>
</html>