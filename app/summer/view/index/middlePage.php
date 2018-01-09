{extend name="base" /}
{block name="title"}
{/block}
{block name="css"}
<link href="{:loadResource('classba/app/math/css/math.css')}" rel="stylesheet">
{/block}
{block name="mainContent"}
<div class="xx-container"style="position: relative;width: 100%;">
    <div class="xx-middlepage-top" id="particles-js" >
        <p class="middlepage-title">恭喜你，完成{$middleInfo.pre_module_name}</p>
        <p class="middlepage-next">接下来即将开始</p>
        <p class="middlepage-next-title">— {$middleInfo.next_module_name} —</p>
    </div>
    <div style="margin-top: 160px;" name="find-report" class="xx-default-button xx-true-button actived">
        查看报告
    </div>
    <div style="margin-top: 24px;" name="next-stage" class="xx-default-button">
        下一步
    </div>
</div>
<input type="hidden"  name="topicId" value="{$topicId}" />
<input type="hidden"  name="nextUrl" value="{$middleInfo.next_url}" />
<input type="hidden"  name="report_url" value="{$middleInfo.report_url}" />
{/block}
{block name="js"}
<script type="text/javascript" src="{:loadResource('classba/assets/particles/particles.js')}"></script>
<script>
    particlesJS.load('particles-js', "__PUBLIC__/classba/assets/particles/particlesjs-config.json");
    var topicId = $("[name=topicId]").val();
    var nextUrl = $("[name=nextUrl]").val();
    var report_url = $("[name=report_url]").val();
    console.log(nextUrl);
    $("[name=find-report]").on("click",function(){
        $(this).addClass("actived");
        window.open(report_url,"_blank")
        $("[name=next-stage]").addClass("xx-true-button").on("click",function(){
            console.log(new Date().getTime())
            location.href = nextUrl;
        });
    });

</script>
{/block}
