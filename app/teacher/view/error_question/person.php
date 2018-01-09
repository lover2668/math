<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
个人错题
</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="icon" type="image/png" href="/plugin/lib/i/yixue-tt-logo.png">
    <script type="text/javascript" src="/static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML"></script>
    <!--  <link rel="icon" type="image/png" href="assets/i/favicon.png">
      <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">
      <meta name="apple-mobile-web-app-title" content="Amaze UI" />-->
    <link rel="stylesheet" href="/static/lib/css/amazeui.min.css"/>
    <link rel="stylesheet" href="/static/lib/css/admin.css">
    
    <script src="/static/lib/js/jquery.min.js"></script>
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器， 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
    以获得更好的体验！</p>
<![endif]-->

    <!-- sidebar end -->
    
    <!-- content start -->
    <div class="admin-content">
        <div class="admin-content-body">
            
    <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">个人错题</strong> / <small>Person</small>
        </div>
        <a style="font-size:16px;color: #333;float: right;" target="_blank" href="/teacher/error_question/printquestion.html" id="printQuestion"><i class="am-icon-print"></i> 打印错题</a>
    </div>
<hr>
<div class="am-g">
    <div class="am-u-sm-12">
        学生账号: <button type="button" class="am-btn am-btn-warning"><?php echo input('username', '');  ?></button>
    </div>

    </div>
        <div class="am-g">
            <div class="am-u-sm-6"><div id="page_1_pie1" style="width:80%;height:300px;text-align:center;"></div></div>
            <div class="am-u-sm-6">   <div id="page_1_pie2" style="width:80%;height:300px;text-align:center;"></div></div>
        </div>

    <hr>

    <div class="am-g">
        <div class="am-u-sm-12">
            <table class="am-table am-table-bordered" style="table-layout: fixed">
                <caption style="margin-bottom: 20px;">知识点掌握情况</caption>
                <tr><td width="20%">已掌握知识点</td><td>
                        {if $weakElements}
                        {foreach name="knowledgeList_tag_name" item="vo"}
                        {if condition="isset($vo['tag_name'])"}{$vo['tag_name']}{/if}
                        {/foreach}
                        {else}
                        您还需要付出更多的努力！
                        {/if}             
                            
                    </td></tr>
                <tr><td>未掌握知识点</td><td>
                    {if $user_exam_detail eq false}
                    {foreach name="knowledgeList" item="vo"}
                    {$vo.tag_name}
                    {/foreach}
                    {else}
                    {foreach name="weakElements_tag_name" item="vo"}
                    {if condition="isset($vo['tag_name'])"}{$vo['tag_name']}{/if}
                    {/foreach}
                    {/if}
                    </td></tr>
                </table>
            </div>
        <div class="am-u-sm-12">

            <div style="overflow:auto;">
                <table class="am-table am-table-striped am-table-hover table-main" style="table-layout: fixed">
                    <thead>
                    <tr>
                       <th><label><input type="checkbox" name="checkAll"/> 全选</label></th> <th class="table-id">序号</th><th class="table-title">知识点</th><th class="table-type">题目</th><th class="table-author am-hide-sm-only">班级正答率</th><th class="table-set">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $knowledgeService=new service\services\KnowledgeService();
                        $questionService=new service\services\QuestionService;
                        $keynum=0;
                        ?>
                        {foreach name="user_exam_detail" item="vo"}
                        
                        <tr>
                        <td><input type="checkbox" name="check[]" value="{$vo.id}"/></td>
                        <td>{$vo.id}</td>
                        <td style="word-wrap:break-word;">
                            <?php 
                            $tag_namearr=$knowledgeService->getKnowledgeByCode($vo['tag_code'],$vo['topicId']);
                            if(isset($tag_namearr['tag_name']))echo $tag_namearr['tag_name'];
                            ?>
                        </td>
                        <td style="word-wrap:break-word;">
                        <?php $question=$questionService->getQuestionById($vo['question_id']); ?>
                        {if condition="isset($question['content'])"}
                        <?php echo  str_replace('##$$##', '_______',htmlspecialchars_decode($question['content']));?>
                        {else}
                        试题内容为空 试题id：{$question['question_id']}
                        {/if}
                        </td>
                        <td class="am-hide-sm-only">
                            <!--班级本题的答题数量-->
                            <?php 
                            echo think\Db::name('user_exam_detail')->where($user_where)->where(['question_id'=>$vo['question_id'],'is_right'=>1])->count();
                            ?>/
                            <?php 
                            echo think\Db::name('user_exam_detail')->where($user_where)->where(['question_id'=>$vo['question_id']])->count();
                            ?>
                            <!-----班级答对的数量---->
                        </td>
                        <td>
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <button onclick="openbatch('{url link="userDetail" vars="id=$vo[id]"}')" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><i class="am-icon-file-text-o"></i> 查看</button>

                                </div>
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
                </table>
            </div>
                <div class="am-cf">
                    共 <?php echo count($user_exam_detail); ?> 条记录
                </div>


        </div>

    </div>



        </div>

        <footer class="admin-content-footer">
            
            
        </footer>

    </div>
    <!-- content end -->


<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu"
   data-am-offcanvas="{target: '#admin-offcanvas'}"></a>


<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/static/lib/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->

<!--<![endif]-->
<script src="/static/lib/js/amazeui.min.js"></script>

<script src="/static/lib/js/app.js"></script>

<script type="text/javascript" src="/plugin/lib/echarts/echarts.min.js"></script>
<script src="/static/lib/layer/layer.js"></script>
<?php
$ggggg=count($user_exam_detail1)-$user_answer_wrong_num;
if($ggggg<=0)$ggggg=0;
?>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var page_1_pie1 = echarts.init(document.getElementById('page_1_pie1'));
    var page_1_pie1_value = [{
        name: '个人正答率',
        value:{$ggggg}    }, {
        name: '',
        value: {if $ggggg eq 0&& $user_answer_wrong_num eq 0}100{else}{$user_answer_wrong_num}{/if}    }];
    var option1 = {
        title: {
            show: false
        },
        tooltip: {
            show: false
        },
        legend: {
            show: true,
            left: 'center',
            bottom: 0,
            data: [{
                name: page_1_pie1_value[0]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }, {
                name: page_1_pie1_value[1]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }]
        },
        series: [{
            type: 'pie',
            radius: ['60%', '80%'],
            label: {
                normal: {
                    position: 'center'
                }
            },
            data: [{
                value: page_1_pie1_value[0]['value'],
                name: page_1_pie1_value[0]['name'],
                itemStyle: {
                    normal: {
                        color: '#00B0A6'
                    }
                },
                label: {
                    normal: {
                        formatter: function (v) {
                            return Math.round(v.percent) + '%';
                        },
                        textStyle: {
                            fontSize: 30,
                            fontWeight: 'bold',
                            color: '#00B0A6'
                        }
                    }
                }
            }, {
                value: page_1_pie1_value[1]['value'],
                name: page_1_pie1_value[1]['name'],
                itemStyle: {
                    normal: {
                        color: '#C2E4E3'
                    }
                },
                label: {
                    show: false,
                    normal: {
                        formatter: ''
                    }
                }

            }]
        }]
    };
    page_1_pie1.setOption(option1);


    //page pie 2
    var page_1_pie2 = echarts.init(document.getElementById('page_1_pie2'));
    var page_1_pie2_value = [{
        name: '班级正答率',
        value:{$post.right_n}   }, {
        name: '',
        value: {if $post.wrong_n eq 0 && $post.right_n eq 0}100{else}{$post.wrong_n}{/if}    }];
    var option2 = {
        title: {
            show: false
        },
        tooltip: {
            show: false
        },
        legend: {
            show: true,
            left: 'center',
            bottom: 0,
            data: [{
                name: page_1_pie2_value[0]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }, {
                name: page_1_pie2_value[1]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }]
        },
        series: [{
            type: 'pie',
            radius: ['60%', '80%'],
            label: {
                normal: {
                    position: 'center'
                }
            },
            data: [{
                value: page_1_pie2_value[0]['value'],
                name: page_1_pie2_value[0]['name'],
                itemStyle: {
                    normal: {
                        color: '#00B0A6'
                    }
                },
                label: {
                    normal: {
                        formatter: function (v) {
                            return Math.round(v.percent) + '%';
                        },
                        textStyle: {
                            fontSize: 30,
                            fontWeight: 'bold',
                            color: '#00B0A6'
                        }
                    }
                }
            }, {
                value: page_1_pie2_value[1]['value'],
                name: page_1_pie2_value[1]['name'],
                itemStyle: {
                    normal: {
                        color: '#C2E4E3'
                    }
                },
                label: {
                    show: false,
                    normal: {
                        formatter: ''
                    }
                }

            }]
        }]
    };
    page_1_pie2.setOption(option2);

function openbatch(url){
        layer.open({
        type: 2,
        title: '查看',
        shadeClose: true,
        shade: 0.8,
        area: ['80%', '80%'],
        content: url //iframe的url
      }); 
    }
    
 $(document).ready(function () {
        $("input[name=checkAll]").click(function () {
            if(this.checked)
            {
                $("tr td input").prop("checked", true);
            }else
            {
                $("tr td input").prop("checked", false);
            }
        })

        $("input[name='check[]']").click(function () {
            var checkedLength=$("input[name='check[]']:checked").length;
            var length=$("input[name='check[]']").length;
            if(checkedLength==length)
            {
                $("input[name=checkAll]").prop("checked", true);
            }else
            {
                $("input[name=checkAll]").prop("checked", false);
            }
        })

        $("#printQuestion").click(function () {
            var selectCheckBox=$("input[name='check[]']:checked");
            var ids=[];
            var ids_str='';
            if(selectCheckBox.length>0)
            {
                $.each(selectCheckBox,function (index,item) {
                    ids.push($(item).val());
                    ids_str+='id['+index+']='+$(item).val()+'&';
                });
                //var href=$(this).attr("href")+"?ids="+ids.join("|");
                var href=$(this).attr("href")+"?"+ids_str;
                $(this).attr("href",href);
            }else
            {
                layer.alert('请在下方选择要打印的错题！', {icon: 6});
                return false;
            }

        })

    })
    
</script>

</body>
</html>
