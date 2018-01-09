<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>导出列表</title>
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>

<body>
    <div class="example" id="examplew100" style=" OVERFLOW-X: scroll; scrollbar-face-color:#B3DDF7;scrollbar-shadow-color:#B3DDF7;scrollbar-highlight-color:#B3DDF7;scrollbar-3dlight-color:#EBEBE4;scrollbar-darkshadow-color:#EBEBE4;scrollbar-track-color:#F4F4F0;scrollbar-arrow-color:#000000; width:100%;HEIGHT: 600px;">
                                                                <div class="field_num_width" id="field_num_width" style="width:2000px;">
    <table width="2000px" border="1" class="table table-bordered">
      <tr>
        {foreach name="structure" item="stru"}  
        <td width="280px">{$stru}</td>
        {/foreach}
      </tr>
      {foreach name="list" item="vo"}
      <tr>
        <td>{$vo.username}</td>
        <td>{if condition="isset($question_content[$vo['question_id']]['content'])"}{$question_content[$vo['question_id']]['content']|htmlspecialchars_decode_and_replace}{/if}</td>
        <td>{$vo['question_id']}</td>
        <td><img src="{$vo.user_answer_base64|htmlspecialchars_decode_and_replace}" /></td>
        <td>
            {php}
                    $right_answer_base64=json_decode($vo['right_answer_base64'], true);
                    {/php}
                    {foreach name="right_answer_base64" item="rvo"}
                        {foreach name="rvo" item="rrvo"}
                            <img src="{$rrvo}" />
                        {/foreach} &nbsp;
                    {/foreach}
            </td>
        <td>{if $vo.is_right eq 1}对{else}错{/if}</td>
        <td>{$vo.ctime|date="Y-m-d H:i:s",###}</td>
      </tr>
      {/foreach}

    </table>
                                                                </div></div>
 </body>
</html>