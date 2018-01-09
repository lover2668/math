<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
    班级错题
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
<div id="reddingload" style="position: fixed; z-index: 9999999999; width: 100%; text-align: center; top: 20%;  display: none;"><b style="border: solid 1px #f33; padding: 5px;">加载中...</b></div>
<div class="am-cf admin-main">
    
    
    <!-- content start -->
    <div class="admin-content">
        <div class="admin-content-body">
            
<script src="/static/lib/layer/layer.js"></script>
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">我的学生</strong> </div>
</div>

<hr>
<div style="width: 100%;">
            <div class="am-form-group" style="float: right;">
                            <form method="get" action="{url link="getList"}" onSubmit="return resumit(this);">
                                <select name="class_id" id="class_id"  AUTOCOMPLETE="off" >
                                        <option value="0" selected>请选择班级</option>
                                        {if condition="isset($class['data'])&&$class['data']&&is_array($class['data'])"}
                                            {foreach name="class.data" item="vo"}
                                            <option value="{$vo.class_id}"   >{$vo.class_name}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                    <select name="course_id" id="course_id" AUTOCOMPLETE="off" >
                                        <option value="0">请选择课程</option>
                                    </select>

                                    <select name="module_id" id="module_id" AUTOCOMPLETE="off" >
                                        <option value="0">请选择课次</option> 
                                    </select>

                                    <select name="topic_id" id="topic_id" AUTOCOMPLETE="off" >
                                        <option value="0">请选择专题</option> 
                                    </select>
                                    <button class="am-btn am-btn-primary " type="submit">查询</button>
                                </form>
            </div>
</div>


<div id="getListBody"></div>


        </div>

        <footer class="admin-content-footer">
            
            
        </footer>

    </div>
    <!-- content end -->

</div>

<a href="#" class="am-icon-btn am-icon-th-list am-show-sm-only admin-menu"
   data-am-offcanvas="{target: '#admin-offcanvas'}"></a>




<script type="text/javascript">


//选择班级
$('#class_id').on('change',function(e){
	$('#reddingload').css('display','');
	var class_id=$(this).val();
	$.post('{url link="getCourseList"}','class_id='+class_id,function(data){
		data=data.data;
                                var str='<option value="0">请选择</option>';
                                $('#course_id').html(str);
                                $('#topic_id').html(str);
                                $('#module_id').html(str);
		if(data&&data.length>0){
			var str1='';
			for(i in data){
				str1+='<option value="'+data[i].course_id+'">'+data[i].course_name+'</option>';
			}
			$('#course_id').html(str+str1);
			
			
		}else{alert("没有相关数据");}
                                
		$('#reddingload').css('display','none');
	});
});
//选择课程
$('#course_id').on('change',function(e){
	$('#reddingload').css('display','');
	var course_id=$(this).val();
	$.post('{url link="getCourseModules"}','course_id='+course_id,function(data){
		data=data.data;
                                var str='<option value="0">请选择</option>';
                                $('#topic_id').html(str);
                                $('#module_id').html(str);
		if(data&&data.length>0){
			var str1='';
			for(i in data){
				str1+='<option value="'+data[i].module_id+'">'+data[i].name+'</option>';
			}
			$('#module_id').html(str+str1);

		}else{alert("没有相关数据");}
		$('#reddingload').css('display','none');
	});
});
//module_id	
//选择课次
$('#module_id').on('change',function(e){
	$('#reddingload').css('display','');
	var module_id=$(this).val();
	$.post('{url link="getTopicList"}','module_id='+module_id,function(data){
		data=data.data;
                                var str='<option value="0">请选择</option>';
                                $('#topic_id').html(str);
		if(data&&data.length>0){
			var str1='';
			for(i in data){
				str1+='<option value="'+data[i].topic_id+'">'+data[i].name+'</option>';
			}
			//topic_id  module_id
			$('#topic_id').html(str1);

		}else{alert("没有相关数据");}
		$('#reddingload').css('display','none');
	});
});	
function resumit(obj){
	var data=$(obj).serialize();
	if($('#module_id').val()==0){
		alert("请选择课次");
		return false;
	}
                if($('#topic_id').val()==0||$('#topic_id').val()==false){
                    alert("请选择专题");
                    return false;
                }
                $('#reddingload').css('display','');
	$.post(obj.action,data,function(data){
                                $('#getListBody').html(data);
                                $('#reddingload').css('display','none');
		//alert(data);
	})
	return false;
}	
</script>

</body>
</html>
