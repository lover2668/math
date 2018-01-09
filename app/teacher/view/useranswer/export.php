<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>导出列表</title>
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>

<body>
    <table width="100%" border="1" class="table table-bordered">
      <tr>
        {foreach name="structure" item="stru"}  
        <td>{$stru}</td>
        {/foreach}
      </tr>
      {foreach name="list" item="vo"}
      <tr>
        <td>{if condition="isset($question_content[$vo['question_id']])"}{$question_content[$vo['question_id']]|htmlspecialchars_decode_and_replace}{/if}</td>
        <td><img src="{$vo.user_answer_base64|htmlspecialchars_decode_and_replace}" /></td>
        <td>{if $vo.is_right eq 1}对{else}错{/if}</td>
        <td>{$vo.ctime|dateof}</td>
      </tr>
      {/foreach}

    </table>
 </body>
</html>