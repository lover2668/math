{extend name="base" /}
{block name="title"}
管理系统
{/block}
{block name="css"}

{/block}
{block name="main"}
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">后台首页</strong> / <small>Index</small></div>
</div>

<hr>

<div class="am-g">
    <div class="am-u-sm-12">
        <h1>欢迎登录乂学教育 后台管理系统！</h1>
        <div class="am-g">
            <div class="am-u-sm-2">用户名：</div>
            <div class="am-u-sm-10">yxjy</div>
            <div class="am-u-sm-2">当时时间：</div>
            <div class="am-u-sm-10">{$currentDate}</div>
            <div class="am-u-sm-2">当前登录ip：</div>
            <div class="am-u-sm-10">{$ip}</div>

        </div>

    </div>

</div>


{/block}

{block name="js"}
{/block}