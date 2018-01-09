<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
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
    <script src="/static/lib/js/jquery.min.js"></script>
    <script src="/static/layer/layer.js"></script>
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
</head>
<body>      
<div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
          <div class="am-btn-toolbar">
            <div class="am-btn-group am-btn-group-xs">
<!--              <button type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 新增</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-save"></span> 保存</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-archive"></span> 审核</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除</button>-->
            </div>
          </div>
        </div>
        <div class="am-u-sm-12 am-u-md-3">
          <div class="am-form-group">
              用户名称:{$username}
<!--            <select data-am-selected="{btnSize: 'sm'}">
               
            </select>
              <button class="am-btn am-btn-default" type="button">确认</button>-->
          </div>
        </div>
        <div class="am-u-sm-12 am-u-md-3">
          <div class="am-input-group am-input-group-sm">
              <input type="text" class="am-form-field" style="opacity: 0;">
          <span class="am-input-group-btn">
              <button class="am-btn am-btn-default" type="button" onclick="window.location.reload();">刷新</button>
          </span>
          </div>
        </div>
      </div>

      <div class="am-g">
        <div class="am-u-sm-12">
          <form class="am-form">
            <table class="am-table am-table-striped am-table-hover table-main" style="table-layout: fixed;">
              <thead>
              <tr>
                <th class="table-check"><input type="checkbox" /></th><th class="table-id">ID</th><th class="table-title">题目内容（部分）</th><th class="table-type">正确答案</th><th class="table-author am-hide-sm-only">学生答案</th><th class="table-date am-hide-sm-only">答题时间</th><th class="table-set">操作</th>
              </tr>
              </thead>
              <tbody>
              {foreach name="data.list.data" item="vo"}
              
              <tr>
                <td><input type="checkbox" value="{$vo.id}" /></td>
                <td>
                {$vo.id}
                </td>
                <td><?php
                $obj=new service\services\QuestionService();
                $Question=$obj->getQuestionById($vo['question_id']);
                if(isset($Question['content'])){
                    echo str_replace('##$$##', '_______', strip_tags(htmlspecialchars_decode($Question['content'])));
                }
                ?></td>
                <td>
                <?php
                if($vo['right_answer_base64']){
                    $right_answer=json_decode($vo['right_answer_base64'], true);
                    if(is_array($right_answer)){
                        foreach($right_answer as $kk=>$vv){
                            foreach ($vv as $key => $value) {
                                echo '<img src="'.$value.'" />';
                            }
                                
                        }
                    }
                }else{
                    $right_answer=json_decode($vo['right_answer'], true);
                    if(is_array($right_answer)){
                        foreach($right_answer as $kk=>$vv){
                            if(is_array($vv)){
                                echo $vv[0].' ';
                            }else{
                                echo $vv.' ';
                            }
                        }
                    }else{
                        echo str_replace('"', "", $vo['right_answer']);
                    }

                    }
                
                ?>
                </td>
                <td><?php 
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
                                        if($value)echo '<img src="'.$value.'" />';
                                    }
                                }else{
                                    echo '<img src="'.$vo['user_answer_base64'].'" />';
                                }
                        }    
                    //echo '<img src="'.$vo['user_answer_base64'].'" />';
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
                <td>
                    {if condition="isset($vo['etime'])"}
                    {$vo.etime|date="Y-m-d H:i:s",###}
                    {/if}
                    
                </td>
                <td>
                    <a href="{url link='errQuestionxx'  vars='id=$vo[id]' suffix='true' domain='true'}" class="alert_href">详情</a>
                </td>
              </tr>
              {/foreach}
              </tbody>
            </table>
            <div class="am-cf">
              共  {$data.list.total}条记录
              <div class="am-fr">
                {$data.page}
              </div>
            </div>
          <!--  <hr />
            <p>注：.....</p>-->
          </form>
        </div>

      </div>

<script src="/static/js/jquery-1.11.3.js"></script>
<script src="/static/layer/layer.js"></script>
<script>
$(".alert_href").click(function(e){
    var href=this.href;

    layer.open({
        type: 2,
        title: false,
        closeBtn: 0, //不显示关闭按钮
        shade: [0],
        offset: 'rb', //右下角弹出
        time: 100, //2秒后自动关闭
        shift: 2,
        end: function(){ //此处用于演示
            layer.open({
                type: 2,
                title: '错题详情',
                shadeClose: true,
                shade: false,
                maxmin: true, //开启最大化最小化按钮
                area: ['80%', '80%'],
                content: href
            });
        }
    });
    return false;
});
</script>
</body>
</html>
