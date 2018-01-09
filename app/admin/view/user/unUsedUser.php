{extend name="base" /}
{block name="title"}
未使用的用户
{/block}

{block name="css"}
<style type="text/css">
    .am-checkbox, .am-radio
    {
        margin-top: 0;
    }
</style>
{/block}
{block name="main"}
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">未使用的用户</strong> / <small>unUsed User</small></div>
</div>
<hr>

<div class="am-g">
    <div class="am-u-sm-12">

        <div class="am-u-sm-1">
            用户分组
        </div>
        <div class="am-u-sm-11" id="groupList">
        <label class="am-radio am-secondary am-radio-inline" >
            <input type="radio" name="type" value="0" data-am-ucheck {if condition="$type eq 0"}checked{/if}> 全部
        </label>
        {volist name="groupList" id="group"}
        <label class="am-radio am-secondary am-radio-inline" >
            <input type="radio" name="type" value="{$group.id}"  data-am-ucheck {if condition="$type eq $group.id"}checked{/if}> {$group.name}
        </label>
        {/volist}
      </div>


        <form action="{:url('freezeUser')}" method="post">

        <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
            <tr>
                <th class="table-check"><input type="checkbox"></th><th class="table-id">序号</th><th class="table-title">用户名</th><th class="table-type">添加时间</th><th class="table-type">状态</th><th class="table-set">操作</th>
            </tr>
            </thead>
            <tbody>
            {if condition="count($data)>0"}
            {volist name="data" id="vo"}
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{$vo.id}"></td>
                <td>{$vo.id}</td>
                <td>{$vo.username}</td>
                <td>{$vo.ctime|date="Y-m-d H:i:s",###}</td>
                    <td>
                        {if condition="$vo.status eq 1"}
                        <span style="color: green">正常</span>
                        {else/}
                        <span style="color: #c10000">冻结中</span>
                        {/if}
                    </td>
                <td>
                    <div class="am-btn-toolbar">
                        <div class="am-btn-group am-btn-group-xs">
                            {if condition="$vo.status eq 1"}
                            <button  class="freezeUser am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><i class="am-icon-file-text-o"></i> &nbsp;冻结</button>
                            {else/}
                            <button  class="unFreezeUser am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><i class="am-icon-file-text-o"></i> &nbsp;解冻</button>

                            {/if}
                        </div>
                    </div>
                </td>
            </tr>
            {/volist}
            <tr>
                <td colspan="6">
                    <div class="am-btn-toolbar">
                        <div class="am-btn-group am-btn-group-xs">
                    <button style="margin-right: 10px;"  class="freezeUser am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><i class="am-icon-file-text-o"></i> &nbsp;批量冻结</button>
                     <button  class="unFreezeUser am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><i class="am-icon-file-text-o"></i> &nbsp;批量解冻</button>
                </div>
                        </div>
                </td>
            </tr>
            {else/}
                <tr>
                    <td colspan="6" align="center">
                        暂无数据
                    </td>
                </tr>
            {/if}
            </tbody>
        </table>

        </form>

        <div class="am-cf">
           <!--  共 3 条记录-->
            <div class="am-fr">

                {$page}

             </div>
        </div>


    </div>

</div>


{/block}

{block name="js"}

<script type="text/javascript">
    var url="{:url('unUsedUser')}";
    var freezeUserUrl="{:url('freezeUser')}";
    var unFreezeUserUrl="{:url('unFreezeUser')}";
    $(document).ready(function () {
        $("#groupList input:radio").click(function () {
            location.href=url+"?type="+$(this).val();
        });

        $("tr button.freezeUser").click(function () {
            $(this).parents("tr").find("input:checkbox").attr("checked","checked");
            $(this).parents("form").attr("action",freezeUserUrl);

        })

        $("tr button.unFreezeUser").click(function () {
            $(this).parents("tr").find("input:checkbox").attr("checked","checked");
            $(this).parents("form").attr("action",unFreezeUserUrl);

        })

        $("thead input").click(function () {

            $("tbody input") .prop("checked", this.checked);


        })
    })
</script>
{/block}