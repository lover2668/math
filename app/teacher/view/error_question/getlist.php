<style>
    .userClass{
        border: 1px solid #daeef1;
    margin-right: 5px;
    }
    .userClass:hover{
        background-color: rgb(91, 221, 191);
    cursor: pointer;
    }
</style>
<script type="text/javascript" src="__PUBLIC__/static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<?php
$zhengdashu=0;
$datishu=0;
foreach($questions_answer_num as $v){
    $datishu+=$v;
}
foreach($questions_answer_right_num as $v){
    $zhengdashu+=$v;
}
$zhengdalv=0;
if($zhengdashu!=0)$zhengdalv=ceil($zhengdashu/$datishu)*100;
$dacuoshuliang=$datishu-$zhengdashu;
?>
<div class="am-g">
    <div class="am-u-sm-3">
        <div id="page_1_pie1" style="width:90%;height:300px;text-align:center;"></div>
        </div>

    <div class="am-u-sm-9">

        <ul class="am-avg-sm-2 am-avg-md-3 am-avg-lg-6 am-thumbnails" id="studentsList" style="height: 250px;overflow: auto">
            <?php  $keynum=0;?>
            {foreach name="user" item="vo" key="k"}
            <!--查看学生错题正答率-->
             <li onclick="lareropend('{url link="person" vars="user_id=$vo[user_id]&class_id=$post[class_id]&username=$vo[user_name]&right_n=$zhengdashu&"}?wrong_n={$datishu-$zhengdashu}&topic_id={$post['topic_id']}');">
                 <div class="userClass">
                <span>NO.{$keynum++}</span>
                <p style="word-wrap:break-word;padding:3px;">{$vo.user_name}</p>
                <span>
                    {if condition="isset($user_answer_right_num[$vo['user_id']])"}
                    {$user_answer_right_num[$vo['user_id']]}
                    {else}0
                    {/if}
                    /
                    {if condition="isset($user_answer_num[$vo['user_id']])"}
                    {$user_answer_num[$vo['user_id']]}
                    {else}0
                    {/if}</span>
                </div>
            </li>
            {/foreach}
         </ul>
    </div>

    </div>
<div class="am-g">
    <div class="am-u-sm-12">

        <div style="overflow:auto;">
            
        
            <table class="am-table am-table-striped am-table-hover table-main" style="table-layout: fixed">
                <thead>
                <tr>
                    <th class="table-id">序号</th><th class="table-title">知识点</th><th class="table-type">题目</th><th class="table-set">操作</th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                    $knowledgeService=new service\services\KnowledgeService();
                    $questionService=new service\services\QuestionService;
                    $keynum=0;
                    ?>
                    
                    {foreach name="questions" item="vo"}
                    <tr>
                    <td class="table-id">{$keynum++}</td>
                    <td class="table-title">
                        <?php 
                            $tag_namearr=$knowledgeService->getKnowledgeByCode($vo['tag_code'],$vo['topicId']);
                            if(isset($tag_namearr['tag_name']))echo $tag_namearr['tag_name'];
                        ?>
                    </td>
                    <td class="table-type">
                        <?php $question=$questionService->getQuestionById($vo['question_id']); ?>
                        {if condition="isset($question['content'])"}
                        <?php echo  str_replace('##$$##', '_______',htmlspecialchars_decode($question['content']));?>
                        {else}
                        试题内容为空 试题id：{$vo['question_id']}
                        {/if}
                    </td>
                    <td class="table-set"><button onclick="openbatch('{url link="detail" vars="question_id=$vo[question_id]&class_id=$post[class_id]&topic_id=$post[topic_id]"}')" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><i class="am-icon-file-text-o"></i> 查看</button></td>
                    </tr>
                    {/foreach}                    
                 </tbody>
            </table>
        </div>
            <div class="am-cf">
                共 <?php echo count($questions); ?> 条记录
            </div>
    </div>

</div>

<script>
    var zhengdalv={$zhengdalv};
    // 基于准备好的dom，初始化echarts实例
    var page_1_pie1 = echarts.init(document.getElementById('page_1_pie1'));
    var page_1_pie1_value = [{
        name: '班级正答率',
        value: {$zhengdashu}    }, {
        name: '',
        value: {if $zhengdashu eq 0 && $dacuoshuliang eq 0}100{else}{$dacuoshuliang}{/if}    }];
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
    function lareropend(url){
        layer.open({
        type: 2,
        title: '个人错题',
        shadeClose: true,
        shade: 0.8,
        area: ['100%', '100%'],
        content: url //iframe的url
      }); 
    }
</script>