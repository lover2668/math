{extend name="base" /}
{block name="title"}
添加用户组
{/block}
{block name="main"}
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">添加用户组</strong> / <small>add Group</small></div>
</div>

<hr>

<div class="am-g">
    <div class="am-u-sm-12">
        <form class="am-form am-form-horizontal" method="post">
            <div class="am-form-group">
                <label for="user-name" class="am-u-sm-3 am-form-label">请输入分组名称</label>
                <div class="am-u-sm-9">
                    <input id="user-name" name="name" placeholder="请输入组名称" type="text">
                </div>
            </div>
            <div class="am-form-group">
                <div class="am-u-sm-9 am-u-sm-push-3">
                    <button type="submit" class="am-btn am-btn-primary">提交</button>
                </div>
            </div>


        </form>

    </div>

</div>


{/block}

{block name="js"}

{/block}