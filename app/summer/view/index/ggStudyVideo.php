{extend name="base" /}
{block name="title"}
巩固学习
{/block}
{block name="css"}
<link rel="stylesheet" href="{:loadResource('classba/assets/video/video-js.css')}">
<link href="{:loadResource('classba/app/math/css/math.css')}" rel="stylesheet">
<style>
    .xx-container{
        padding-bottom: 96px;
    }
    .xx-container .xx-video-container{
        height:auto !important;
        padding-bottom: 24px;
    }
</style>
{/block}
{block name="mainContent"}
<div class="xx-container" style="position: relative">
    <div class="alert xx-alert-default animated" role="alert">
        开始学习前，让我们先来看看知识点视频吧
    </div>
    <div class="xx-video-container">
        <p style="padding: 24px 0 16px 40px; font-size:24px;color: #333333;"><strong>巩固视频</strong> <span>知识点－{$tag_name}</span></p>
        <video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="868" height="488" poster="" data-setup="{}">
            <source src="" type="video/mp4">
            <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
        </video>
    </div>
    <div class="xx-continue" name="xx-continue" style="background-color: transparent">
        <div class="xx-continue-button" style="letter-spacing: 0;padding:0;line-height: 48px;text-align: center" onclick="_czc.push(['_trackEvent', '巩固学习视频下一步按钮', '点击', '下一步','5','']);">
            去学习
        </div>
    </div>
</div>
<input type="hidden"  name="topicId" value="{$topicId}" />
<input type="hidden"  name="video_url" value="{$video_url}" />
{/block}
{block name="js"}
<script type="text/javascript" src="{:loadResource('classba/assets/video/video.js')}"></script>
<script>
    (function($){

        var topicId = $("[name=topicId]").val();
        var video_url = $("[name=video_url]").val();
        $_CONFIG.video_url = video_url;
        $(".xx-continue-button").on("click",function(){
            window.open("http://"+window.location.host+"/index.php/summer/index/ggStudy/topicId/"+topicId,"_self")
        });
        var player = videojs("example_video_1", {}, function() {
            window.myPlayer = this;
            myPlayer.src(video_url);
            myPlayer.load(video_url);
            this.on('timeupdate', function () {
//                playedTime(this);
                $_CONFIG.video_played_time = playedTime(this);
            });
            var pauseTimes = 0;
            this.on("pause",function(){
                if (window.myPlayer.currentTime() < window.myPlayer.duration())
                    pauseTimes++;
                $_CONFIG.video_pause_times = pauseTimes;
                console.log($_CONFIG.video_pause_times)
            })
            var end_time = new Date().getTime();
            console.log(end_time)
        });
        //获取视频已经播放的时间
        function playedTime(elem){
            return elem.played().end(0)
        }

    })(jQuery)
</script>
{/block}
