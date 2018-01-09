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
<div class="am-g">
    <div class="am-u-sm-12">

        <div style="overflow:auto;">
            
            <table class="am-table am-table-striped am-table-hover table-main" style="table-layout: fixed">
                <thead>
              <tr>

                  <th class="table-id">ID</th><th class="table-title">登录名</th>
                  <th class="table-type">姓名</th><th class="table-author am-hide-sm-only">班级</th>
                  <th class="table-date am-hide-sm-only">上次学习时间</th>
<!--                  <th class="table-date am-hide-sm-only">学习进度</th>-->
                  <th class="table-date am-hide-sm-only">错题数</th>
                  <th class="table-set">操作</th>
              </tr>
              </thead>
                <tbody>
                        {foreach name="user" item="vo"}
                            <tr>
                            <td>{$vo.user_id}</td>
                            <td><a href="#">{$vo.user_name}</a></td>
                            <td>{$vo.real_name}</td>
                            <td class="am-hide-sm-only">{$vo.class_name}</td>
                            <td class="am-hide-sm-only">
                                <?php
                                $user_exam_step_log=think\Db::name('user_exam_step_log')->where(['user_id'=>$vo['user_id']])->order('id desc')->find();
                                if(isset($user_exam_step_log['etime']))echo date('Y-m-d H:i:s',$user_exam_step_log['etime']);
                                ?>
                            </td>
<!--                            <td class="am-hide-sm-only">
                                <?php
                                $jingdu=think\Db::name('user_exam_step_log')->where(['user_id'=>$vo['user_id']])->count();
                                if($jingdu==0){
                                    echo "0%";
                                }else{
                                    echo ceil($jingdu/count($topicList))*100;echo "%";
                                }
                                
                                ?>
                            </td>-->
                            <td class="am-hide-sm-only">{if condition="isset($wrong_user_num[$vo['user_id']])"}{$wrong_user_num[$vo['user_id']]}{else}0{/if}个</td>
                            <td>
                                                                
                                <a href="{:url('errQuestion',array('user_id'=>$vo['user_id'],'username'=>$vo['user_name']))}?topic_id=<?php echo input('topic_id','') ?>">查看错题</a>
                                | <a target="_blank" href="{:url('printTbb',array('user_id'=>$vo['user_id'],'username'=>$vo['user_name']))}?course_id=<?php echo input('course_id','0') ?>&topic=<?php echo  input('topic_id', '1')?>&course_name={$vo.class_name}">打印报告</a>
                                |
                                <a href="{:url('detection',array('user_id'=>$vo['user_id'],'username'=>$vo['user_name']))}?topic_id=<?php echo input('topic_id','') ?>"  target="_blank"  >学习监测</a>
                            </td>
                        </tr>
                        {/foreach}
                </tbody>
            </table>
            
            
        </div>
            <div class="am-cf">
                共<?php echo count($user); ?>条记录
            </div>
    </div>

</div>

<script>

    function openbatch(url){
        parent.layer.open({
        type: 2,
        title: '查看',
        shadeClose: true,
        shade: 0.8,
        area: ['100%', '100%'],
        content: url //iframe的url
      }); 
    }
</script>