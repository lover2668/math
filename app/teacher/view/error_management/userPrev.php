{extend name="base" /}
{block name="title"}
请输入用户名前缀
{/block}

{block name="css"}
<link rel="stylesheet" href="/static/math/css/classes_error.css"/>
{/block}

{block name="main"}
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">请输入用户名前缀</strong> / <small>input UserPrev</small></div>
</div>

<hr>

<div class="am-g">

    <form method="get">


    <div class="am-u-sm-12 am-u-md-12">
        <input type="text" name="user_prev" placeholder="请输入用户名前缀"/>
        <input type="text" name="username" placeholder="请输入用户名"/>
        <button type="submit" class="am-btn am-btn-primary">提交</button>
    </div>
    </form>
</div>

{/block}

{block name="js"}

{/block}