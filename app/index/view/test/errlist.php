<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>错误日记列表</title>
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>

<body>
<table width="100%" border="1" class="table table-bordered">
  <tbody>
    <tr>
      <td>id</td>
      <td>user_id</td>
      <td>request_api</td>
      <td>topicId</td>
      <td>module_type</td>
      <td>kmap_code</td>
      <td>request_data</td>
      <td>response_data</td>
      <td>ctime</td>
    </tr>
    {foreach name="list.data" item="vo"}
    <tr>
      <td>{$vo.id}</td>
      <td>{$vo.user_id}</td>
      <td>{$vo.request_api}</td>
      <td>{$vo.topicId}</td>
      <td>{$vo.module_type}</td>
      <td>{$vo.kmap_code}</td>
      <td>{$vo.request_data}</td>
      <td>{$vo.response_data}</td>
      <td>{$vo.ctime|date="Y-m-d H:i:s",###}</td>
    </tr>
    {/foreach}
    
  </tbody>
</table>
{$page}
</body>
</html>
