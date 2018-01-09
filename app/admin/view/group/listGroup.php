{extend name="base" /}
{block name="title"}
分组列表
{/block}

{block name="css"}
<style type="text/css">
    .am-btn-toolbar .saveBtn
    {
        display: none !important;
    }

    .editInput
    {
        padding:5px;
        font-size:14px;
    }
</style>
{/block}

{block name="main"}
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">分组列表</strong> / <small>Group List</small></div>
</div>

<hr>

<div class="am-g">
    <div class="am-u-sm-12">

        <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
            <tr>
                <th class="table-id">序号</th>
                <th class="table-title">名称</th>
                <th class="table-type">添加时间</th>
                <th class="table-type">操作</th>
            </tr>
            </thead>
            <tbody>
            {volist name="data" id="vo"}
            <tr id="{$vo.id}">
                <td>{$vo.sort}</td>
                <td>{$vo.name}</td>
                <td>{$vo.ctime}</td>
                <td>
                    <div class="am-btn-toolbar">
                        <div class="am-btn-group am-btn-group-xs">
                    <button style="margin-right: 5px;" class="editBtn am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><i class="am-icon-edit"></i> &nbsp;修改</button>
                         </div>
                        </div>
                </td>
            </tr>
            {/volist}
            </tbody>
        </table>
        <div class="am-cf">
            <!--  共 2 条记录
            <div class="am-fr">
                 <ul class="am-pagination">
                     <li class="am-disabled"><a href="#">«</a></li>
                     <li class="am-active"><a href="#">1</a></li>
                     <li><a href="#">2</a></li>
                     <li><a href="#">3</a></li>
                     <li><a href="#">4</a></li>
                     <li><a href="#">5</a></li>
                     <li><a href="#">»</a></li>
                 </ul>
             </div>-->
        </div>


    </div>

</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">提示信息
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            更新成功。
        </div>
    </div>
</div>



{/block}

{block name="js"}
<script type="text/javascript">
    $(document).ready(function () {
        $(".editBtn").click(function () {
            var firstTd=$(this).parents("tr").find("td:eq(0)");
            var secondTd=$(this).parents("tr").find("td:eq(1)");
           var order=firstTd.text();
           var name=secondTd.text();
            firstTd.empty().append("<input class='editInput' type='text' style='width:5em' value='"+order+"'/>");
            secondTd.empty().append("<input class='editInput' type='text' style='width:8em' value='"+name+"'/>   <button class='saveBtn am-btn am-btn-default'><i class='am-icon-save'></i> &nbsp;保存</button>");

        })

        $(document).on("click",".saveBtn",function () {
            var firstTd=$(this).parents("tr").find("td:eq(0)");
            var secondTd=$(this).parents("tr").find("td:eq(1)");
            var id=$(this).parents("tr").attr("id");

            var sort=firstTd.find("input").val();
            var name=secondTd.find("input").val();
            var url="{:url('updateGroup')}";
            var data={id:id,name:name,sort:sort};

            var $modal = $('#your-modal');

            $.post(url,data,function (result) {
                $modal.find(".am-modal-bd").text(result.msg);
                $modal.modal();
                if(result.code==1)
                {
                    firstTd.empty().text(sort);
                    secondTd.empty().text(name);
                }
            })

        })
    })
</script>
{/block}