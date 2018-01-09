<!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script type="text/javascript" src="__PUBLIC__/static/lib/mathjax/MathJax.js?config=TeX-MML-AM_CHTML"></script>
<div class="am-g">
    <div class="am-u-sm-12">
        <div style="text-align: right;"><input type="button" value="刷新" onclick="javascript:window.location.reload();" /></div>
        <div style="overflow:auto;">
            <h3>&nbsp;当前学习阶段:  {$module_type_title}</h3>
            
            <div>&nbsp;累计完成<?php echo think\Db::name('user_exam_action_log')->where(['user_id'=> input('user_id', 0),'topicId'=> input('topic_id', 0),'module_type'=>$find['module_type'],'is_submit'=>'1'])->order('id  DESC')->count(); ?>道题目<br /></div>
            <div>用户名:<?php echo input('username', ''); ?></div>
            <table  class="table table-bordered">
                <thead>
              <tr>

                  <th class="table-id">序号</th>
<!--                  <th class="table-title">ID</th>-->
                  <th class="table-type">题目</th>
                  <th class="table-date am-hide-sm-only">学生答案</th>
                  <th class="table-date am-hide-sm-only">正确答案</th>
                  <th class="table-set">答题时间</th>
                  <th class="table-set">答题状态</th>
                  <th class="table-set">操作</th>
              </tr>
              </thead>
                <tbody>
                    {foreach name="user_exam_action_log" item="vv33" key="k"}
                    <?php $vo=think\Db::name('user_exam_detail')->where(['user_id'=>$vv33['user_id'],'topicId'=>$vv33['topicId'],'module_type'=>$vv33['module_type'],'question_id'=>$vv33['question_id']])->find(); ?>
                    <tr>
                    <td class="table-id">{$k+1}</td>
<!--                    <td class="table-title">{$vv33.id}</td>-->
                    <td class="table-type">
                         <?php
                $QuestionService=new service\services\QuestionService();
                $QuestionService=$QuestionService->getQuestionById($vv33['question_id']);//$data['question_id']
                if(isset($QuestionService['content']))echo str_replace('##$$##', '_______',htmlspecialchars_decode($QuestionService['content']));
            ?>
                    </td>
                    <td class="table-date am-hide-sm-only">
                    <?php
                    if($vo['user_answer_base64']&&$vo['user_answer']!=';'
&&$vo['user_answer']!=';;'
&&$vo['user_answer']!=';;;'
&&$vo['user_answer']!=';;;;'
&&$vo['user_answer']!=';;;;'
&&$vo['user_answer']!=';;;;;'){
                                           if(is_array($vo['user_answer_base64'])){
                                                   foreach($vo['user_answer_base64'] as $kk=>$vv){
                                                           if(!is_array($vv))echo '<img src="'.$vv.'" />';
                                                   }
                                           }else{
                                                   $image=explode('@@@', $vo['user_answer_base64']);
                                                   if(count($image)>1){
                                                       foreach ($image as $key => $value) {
                                                           if($value)echo '<img src="'.$value.'" /><br/>';
                                                       }
                                                   }else{
                                                       echo '<img src="'.$vo['user_answer_base64'].'" /><br/>';
                                                   }

                                           }    

                                           }else{
                                              if($vo['user_answer']&&$vo['user_answer']!=';'
&&$vo['user_answer']!=';;'
&&$vo['user_answer']!=';;;'
&&$vo['user_answer']!=';;;;'
&&$vo['user_answer']!=';;;;'
&&$vo['user_answer']!=';;;;;'){
                                                   if(is_array($vo['user_answer'])){
                                                       foreach($vo['user_answer'] as $vvv){
                                                           if(!is_array($vvv))echo $vvv;
                                                       }
                                                   }else{
                                                       echo $vo['user_answer'];
                                                   }
                                               }else{
                                                   echo "未作答";
                                               }
                                           }
                    ?>
                    </td>
                    <td class="table-date am-hide-sm-only">
                    <?php
                if($vo['right_answer_base64']){
                    $right_answer=json_decode($vo['right_answer_base64'], true);
                    if(is_array($right_answer)){
                        foreach($right_answer as $kk=>$vv){
                            foreach ($vv as $key => $value) {
                                echo '<img src="'.$value.'" /><br/>';
                            }
                                
                        }
                    }
                }else{
                    $right_answer=json_decode($vo['right_answer'], true);
                        if(is_array($right_answer)){
                            foreach($right_answer as $kk=>$vv){
                                if(is_array($vv)){
                                    echo str_replace('"', ' ', $vv[0]);
                                }else{
                                    echo str_replace('"', ' ', $vv);
                                }
                            }
                        }else{
                            echo str_replace('"', "", $vo['right_answer']);
                        }
                }
                
                 if($vo==false){
                            $right_answer=$QuestionService['answer_base64'];
                            if($QuestionService['answer_base64']){
                                if(is_array($right_answer)){
                                    foreach($right_answer as $kk=>$vv){
                                        foreach ($vv as $key => $value) {
                                            echo '<img src="'.$value.'" /><br/>';
                                        }

                                    }
                                }
                            }else{
                                    if(is_array($right_answer)&&$right_answer){
                                        foreach($right_answer as $kk=>$vv){
                                            if(is_array($vv)){
                                                echo str_replace('"', ' ', $vv[0]);
                                            }else{
                                                echo str_replace('"', ' ', $vv);
                                            }
                                        }
                                    }else{
                                        echo str_replace('"', "", $QuestionService['answer']);
                                    }
                            }
                        }     
                ?>
                    </td>
                    <td class="table-set">{if $vo.spent_time}{$vo.spent_time/1000|ceil}秒{else}{/if}</td>

                    <td class="table-set">
                  {if condition="$vv33.is_submit eq 0"}正在作答{else}
                  <?php
                  if(ceil($vo['spent_time']/1000)>$QuestionService['estimates_time']){
                      echo "超时";
                  }else{
                      echo "正常";
                  }
                  ?>
                  {/if}
                    </td>
                    <td class="table-set">
                        {if condition="$vv33.is_submit eq 0"}
                        <a href="{url link='errQuestionxx1'  vars='id=$vv33[question_id]' suffix='true' domain='true'}" target="_blank"  >查看</a>
                        {else}<a href="{url link='errQuestionxx'  vars='id=$vo[id]' suffix='true' domain='true'}"  target="_blank" >查看</a>
                        {/if}
                        
                    </td>
                    </tr>
                    {/foreach}
                  
                </tbody>
            </table>
            
            
        </div>
    </div>

</div>
