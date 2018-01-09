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
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
    <div style="display: none;"><?php print_r($data); ?></div>
<table width="100%" border="0" class="table table-bordered">
  <tr class="active">
    <td>ID</td>
    <td>题目内容（部分）</td>
    <td>视频</td>
    <td>操作</td>
  </tr>
  {foreach name="data" item="vo"}
  <tr>
    <td>{$vo.id}</td>
    <td>
        <?php echo str_replace('##$$##','_______',htmlspecialchars_decode($vo['content']));?>
    </td>
    <td>
        {if condition="isset($vo['knowledge_video'])&&is_array($vo['knowledge_video'])"}
        {foreach name="vo.knowledge_video" item="vvv" key="kk"}
        {if condition="isset($vvv['video_url'])&&$vvv['video_url']"}
                {if condition="count($vo['knowledge_video'])>1"}
                <a href="{$vvv['video_url']}" target="_blank">查看视频{$kk+1}</a>
                {else}
                <a href="{$vvv['video_url']}" target="_blank">查看视频</a>
                {/if}
                
        {/if}
        {/foreach}
        {/if}
        
    </td>
    <td><input type="button" value="查看" class="btn btn-default" onclick="openinfo('{url link='getInfo' vars='id=$vo[id]'}');" /></td>
  </tr>
  {/foreach}
</table>
    <div style="text-align: center;">
        <input type="button"  class="btn btn-default"  onclick="window.location='{url link='getList'}?topic_id={$topic_id}&module_tyle_id={$module_tyle_id}&page={if condition='$page<=1'}1{else}{$page-1}{/if}'"  {if condition="$page<=1"}disabled {/if} value="上一页">  &nbsp;&nbsp;&nbsp;
        <input type="button"  class="btn btn-default" onclick="window.location='{url link='getList'}?topic_id={$topic_id}&module_tyle_id={$module_tyle_id}&page={$page+1}'" {if condition="count($data)<10"}disabled {/if} value="下一页"></div>
    <script>
    function openinfo(url){
        parent.layer.open({
        type: 2,
        title: '查看',
        shadeClose: true,
        shade: 0.8,
        area: ['90%', '90%'],
        content: url //iframe的url
      }); 
    }
    </script>
</body>
</html>
