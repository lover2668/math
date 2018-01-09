<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>

<body>
    <a href="{url link="xiance_prereport"   vars='user_id=$user_id&tid=$tid'}?module_type=<?php echo config('xiance_module_type') ?>">现行测试</a>
    <a href="{url link="bxbl_prereport"   vars='user_id=$user_id&tid=$tid'}?module_type=<?php echo config('gaoxiao_module_type') ?>">高效学习</a>
    <a href="{url link="zhlx_prereport"   vars='user_id=$user_id&tid=$tid'}?module_type=<?php echo config('zonghe_module_type') ?>">竞赛拓展</a>
</body>
</html>
