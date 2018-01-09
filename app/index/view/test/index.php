<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>针对三千接口部分的测试类说明</title>
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
</head>

<body>
<h1>语文demo  测试接口说明:(测试三千那边的接口数据)</h1>
<table width="100%" border="1" class="table table-bordered">
  <tbody>
  <tr>
	  <td width="15%" align="right"><h3>接口名称</h3></td>
      <td><h3>关于三千的错误日记列表</h3></td>
    </tr>
    <tr>
      <td width="15%" align="right">例子</td>
      <td>errList()
      <br />实例 <a href="/index.php/index/Test/errList" target="_blank">{url link=""  vars="" suffix='true' domain='true'}/index.php/index/Test/errList</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
   <tr>
	  <td width="15%" align="right"><h3>接口名称</h3></td>
      <td><h3>清空缓存</h3></td>
    </tr>
    <tr>
      <td width="15%" align="right">例子</td>
      <td>cacheClear()根据试题ID获取试题 
      <br />实例 <a href="/index.php/index/Test/cacheClear" target="_blank">{url link=""  vars="" suffix='true' domain='true'}/index.php/index/Test/cacheClear</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
	  <td width="15%" align="right"><h3>接口名称</h3></td>
      <td><h3>根据试题ID获取试题并有题目错误分析</h3></td>
    </tr>
    <tr>
      <td width="15%" align="right">例子</td>
      <td>getQuestionByIdAnalyse($question_id)根据试题ID获取试题并有题目错误分析 
      <br />实例 <a href="/index.php/index/Test/getQuestionByIdAnalyse/question_id/380" target="_blank">{url link=""  vars="" suffix='true' domain='true'}index.php/index/Test/getQuestionByIdAnalyse/question_id/380</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
	  <td width="15%" align="right"><h3>接口名称</h3></td>
      <td><h3>根据试题ID获取试题</h3></td>
    </tr>
    <tr>
      <td width="15%" align="right">例子</td>
      <td>getQuestionById($question_id)根据试题ID获取试题 
      <br />实例 <a href="/index.php/index/Test/getQuestionById/question_id/380" target="_blank">{url link=""  vars="" suffix='true' domain='true'}index.php/index/Test/getQuestionById/question_id/380</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
	  <td width="15%" align="right"><h3>接口名称</h3></td>
      <td><h3>根据知识点获取试题 </h3></td>
    </tr>
    <tr>
      <td width="15%" align="right">例子</td>
      <td>getQuestionsByKnowledge($knowledge, $module, $used_type)根据知识点获取试题 <br /> <a href="/index.php/index/Test/getQuestionsByKnowledge/knowledge/cz1401/module/2/used_type/2" target="_blank">{url link=""  vars="" suffix='true' domain='true'}/index.php/index/Test/getQuestionsByKnowledge/knowledge/cz1401/module/2/used_type/2</a><br /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
	  <td width="15%" align="right"><h3>接口名称</h3></td>
      <td><h3>获取用户已经做过的试题</h3></td>
    </tr>
    <tr>
      <td width="15%" align="right">例子</td>
      <td>getUserHasAnsweredQuestions($user_id, $topicId, $module_type, $submodule_type = 1)获取用户已经做过的试题 <br /> <a href="/index.php/index/Test/getUserHasAnsweredQuestions/user_id/140/topicId/1/module_type/1/submodule_type/1" target="_blank">{url link=""  vars="" suffix='true' domain='true'}/index.php/index/Test/getUserHasAnsweredQuestions/user_id/140/topicId/1/module_type/1/submodule_type/1</a><br /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
	  <td width="15%" align="right"><h3>接口名称</h3></td>
      <td><h3>获取知识图谱信息</h3></td>
    </tr>
    <tr>
      <td width="15%" align="right">例子</td>
      <td>getKnowledgeList()获取知识图谱信息 <br /> <a href="/index.php/index/Test/getKnowledgeList" target="_blank">{url link=""  vars="" suffix='true' domain='true'}/index.php/index/Test/getKnowledgeList</a><br /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
	  <td width="15%" align="right"><h3>接口名称</h3></td>
      <td><h3>获取知识相关信息</h3></td>
    </tr>
    <tr>
      <td width="15%" align="right">例子</td>
      <td>getKnowlegeCode($knowledge_map, $tag_code)
      <br />实例 <a href="/index.php/index/Test/getKnowlegeCode/knowledge_map/cz1401,cz1402,cz1403,cz1404/tag_code/cz1404" target="_blank">{url link=""  vars="" suffix='true' domain='true'}/index.php/index/Test/getKnowlegeCode/knowledge_map/cz1401,cz1402,cz1403,cz1404/tag_code/cz1404</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    
    <tr>
	  <td width="15%" align="right"><h3>接口名称</h3></td>
      <td><h3>根据模块获取试题信息</h3></td>
    </tr>
    <tr>
      <td width="15%" align="right">例子</td>
      <td>getQuestionIdsByModule($module_id,$tag_code)
      <br />实例 <a href="/index.php/index/Test/getQuestionIdsByModule/module_id/1/tag_code/cz1401" target="_blank">{url link=""  vars="" suffix='true' domain='true'}/index.php/index/Test/getQuestionIdsByModule/module_id/1/tag_code/cz1401</a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>

</body>
</html>
