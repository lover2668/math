<select name="class_id" data-am-selected="{btnSize: 'sm'}">
    <option value="-1">请选择班级</option>
    {volist name="classList" id="item"}
    <option value="{$item.id}" {eq name="item.id" value="$class_id"} selected {/eq}>{$item.name}</option>
    {/volist}
</select>
<select name="course_id" data-am-selected="{btnSize: 'sm'}">
    <option value="-1">请选择课程</option>
    {volist name="selectedCourseList" id="item"}
    <option value="{$item.course_id}" {eq name="item.course_id" value="$course_id"} selected {/eq}>{$item.course_name}</option>
    {/volist}

</select>

<select name="charpter_id" data-am-selected="{btnSize: 'sm'}">
    <option value="-1">请选择课次</option>
    {foreach name="selectedCharptericList" item="item"}
    <option value="{$item.charpter_id}" {eq name="item.charpter_id" value="$charpter_id"} selected {/eq} >{$item.charpter_name}</option>
    {/foreach}
</select>

<script type="text/javascript">
    /**
     * 加载第几次课
     * @param charpterList
     */
    function loadCharpter(charpterList) {
        var html="";
            for(var item in charpterList)
            {
                html+="<option value='"+charpterList[item].charpter_id+"'>"+charpterList[item].charpter_name+"</option>";
            }
            $("select[name=charpter_id]").find("option:gt(0)").remove().end().append(html)

    }
    var totalCourseList={$courseList};
    $(document).ready(function () {

        $("form").submit(function () {
            var class_id=$("select[name=class_id]").val();
            var course_id=$("select[name=course_id]").val();
            var charpter_id=$("select[name=charpter_id]").val();
            if(class_id==-1)
            {
                layer.alert('请选择班级！', {icon: 5});
                return false;
            }

            if(course_id==-1)
            {
                layer.alert('请选择课程！', {icon: 5});
                return false;
            }

            if(charpter_id==-1)
            {
                layer.alert('请选择课次！', {icon: 5});
                return false;
            }


        })

        $("select[name=class_id]").change(function () {
            var index=$(this).val();
            var html="";
            var courseList=totalCourseList[index];
            for(var item in courseList)
            {
                html+="<option value='"+courseList[item].course_id+"'>"+courseList[item].course_name+"</option>";
            }
            $("select[name=course_id]").find("option:gt(0)").remove().end().append(html);
            $("select[name=charpter_id]").find("option:gt(0)").remove().end().append(html);
        })

        $("select[name=course_id]").change(function () {
            if($(this).val()==-1)
            {
                return;
            }
            var class_id=$("select[name=class_id]").val();
            var course_id=$(this).val();
            var url="{:url('Mystudents/getChapter')}";
            var data={"class_id":class_id,"course_id":course_id};
            $.post(url,data,function (result) {

                if(result.data)
                {
                    loadCharpter(result.data);
                }

            })

        });



    })
</script>