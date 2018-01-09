{extend name="base" /}
{block name="title"}
添加用户
{/block}
{block name="css"}
<style type="text/css">
    .am-ucheck-icons
    {
        top:10px;
    }
</style>

{/block}
{block name="main"}
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">添加用户</strong> / <small>add User</small></div>
</div>

<hr>

<div class="am-g">
    <div class="am-u-sm-12">
        <form class="am-form am-form-horizontal" method="post">

            <div class="am-form-group">
                <label for="user-name" class="am-u-sm-3 am-form-label">选择用户分组</label>
                <div class="am-u-sm-9">
                    {volist name="groupList" id="group"}
                    <label class="am-radio am-secondary am-radio-inline" >
                        <input type="radio" name="type" value="{$group.id}" data-am-ucheck> {$group.name}
                    </label>
                    {/volist}
                </div>
            </div>



            <div class="am-form-group">
                <label for="user-name" class="am-u-sm-3 am-form-label">用户名前缀</label>
                <div class="am-u-sm-9">
                    <input id="user-name" name="userName" placeholder="请输入用户名前缀" type="text">
                </div>
            </div>

            <div class="am-form-group">
                <label for="user-email" class="am-u-sm-3 am-form-label">密码</label>
                <div class="am-u-sm-9">
                    <input id="user-email" placeholder="请输入密码" type="password" name="password">
                </div>
            </div>

            <div class="am-form-group">
                <label for="user-phone" class="am-u-sm-3 am-form-label">开始</label>
                <div class="am-u-sm-9">
                    <input id="user-phone"  name="start"  placeholder="请输入开始数字" type="text">
                </div>
            </div>

            <div class="am-form-group">
                <label for="user-phone" class="am-u-sm-3 am-form-label">结束</label>
                <div class="am-u-sm-9">
                    <input id="user-phone"  name="end"  placeholder="请输入结束数字" type="text">
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


<div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">提示信息
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            请选择用户分组！
        </div>
    </div>
</div>


{/block}

{block name="js"}
<script type="text/javascript">
    $(document).ready(function () {


        var $modal = $('#your-modal');

        $("form").submit(function () {
            var isSelected=$('input[name=type]').is(':checked');
           if(!isSelected)
           {
               $modal.modal();
               return false;
           }
        })



    })
</script>
{/block}