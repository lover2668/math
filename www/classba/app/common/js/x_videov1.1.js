/**
 * Created by linxiao on 17/3/14.
 */
(function () {
    videojs.plugin('myPlugin', function (myPluginOptions) {
        myPluginOptions = myPluginOptions || {};

        var player = this;
        var alertText = myPluginOptions.text || 'Player is playing!'

        player.on('play', function () {
            console.log(alertText);
        });
    });
    videojs.plugin('pauseTimes', function () {
        //统计暂停次数;
        var player = this;
        var pauseTimes = 0;
        player.on('pause', function () {
            if (player.currentTime() < player.duration())
                pauseTimes++;
        });
        return pauseTimes;
    });
    videojs.plugin('playTimes', function (options) {
        //控制播放次数;
        var player = this;

        var times = options.playtimes || 2;

        var start = (localStorage.getItem("playTimes") < times) ? 0 : localStorage.getItem("playTimes");
        if (start >= times) {
            player.pause();
            videoShade(player, times, "播放结束");
        } else {
            player.on("ended", function () {
                start++;
                localStorage.setItem("playTimes", start);
                if (start >= times) {
                    videoShade(player, times, "播放结束");
                } else {
                    //执行showTime()
                    var i = 3;
                    var intervalid;
                    intervalid = setInterval(function () {
                        videoShade(player, times, i + "秒后自动播放");
                        if (i == 0) {
                            player.play();
                            clearInterval(intervalid);
                            if (document.getElementById("shade")) {
                                document.getElementById(player.id_).removeChild(document.getElementById("shade"));
                            }
                        }
                        i--;
                    }, 1000);
                }
            });
        }
    });
    videojs.plugin('playedTime', function () {
        //视频播放时间;
        var player = this;
        return player.played().end(0);
    });
    videojs.plugin('forbiddenDrag', function (options) {
        //控制拖动;
        var player = this;
        var dragflag = options.dragflag || false;
        player.on('seeking', function () {
            if (dragflag) {
                if (player.currentTime() > player.played().end(0)) {
                    player.currentTime(player.played().end(0));
                }
            }
        });
        return;
    });
    var player = videojs('example_video_1', {
        'playbackRates': [0.5, 1, 1.5, 2, 2.5, 3]
    }, function () {
        console.log('Good to go!');
        //this.play();
        var player = this;

        this.on('timeupdate', function () {
            var howLongIsThis = player.duration();
            var whatHasBeenBuffered = player.buffered();
            var howMuchIsDownloaded = player.bufferedPercent();
            var howLoudIsIt = player.volume();
            var currentTime = player.currentTime();
            $("#played_time").html(playedTime(this) + "秒");
            $("#video_time").html(howLongIsThis + "秒");
            $("#current_time").html(currentTime + "秒")
//                    console.log(howLongIsThis+"----"+howMuchIsDownloaded+"----"+whatHasBeenBuffered+"---"+howLoudIsIt+"---"+currentTime)
        });

    });
    player.myPlugin({
        text: 'Plugin added later!'
    });
    player.playTimes({
        playtimes: 2
    });
    player.forbiddenDrag({
        dragflag: true
    });
    player.playedTime();
    $("#reset").on("click", function () {
        localStorage.clear();
        player.load(player.children_[0].currentSrc);
        $("#played_time").html(0 + "秒");
        $("#video_time").html(0 + "秒");
        $("#current_time").html(0 + "秒")
        if (document.getElementById("shade")) {
            document.getElementById(player.id_).removeChild(document.getElementById("shade"));
        }
    });
    //获取视频已经播放的时间
    function playedTime(elem){
        return elem.played().end(0)
    }
    function pauseTimes(elem){
        //统计暂停次数;
        var pauseTimes  = 0;
        elem.on('pause', function() {
            if(elem.currentTime()<elem.duration())
                pauseTimes++;
            $("#pause_times").html(pauseTimes);
        });
    }
    //播放结束先是浮层
    function videoShade(elem, times, text) {
        var shade_height = elem.height(), shade_width = elem.width();
        console.log(shade_height);
        if (document.getElementById("shade")) {
            document.getElementById(elem.id_).removeChild(document.getElementById("shade"));
        }
        var $shade_div = document.createElement("div");
        $shade_div.id = "shade";
        $shade_div.innerHTML = text;
        $shade_div.style.height = shade_height + "px";
        $shade_div.style.position = "absolute";
        $shade_div.style.width = shade_width + "px";
        $shade_div.style.textAlign = "center";
        $shade_div.style.lineHeight = shade_height + "px";
        $shade_div.style.fontSize = "28px";
        $shade_div.style.background = "#000";
        $shade_div.style.opacity = 0.4;
        document.getElementById(elem.id_).appendChild($shade_div);
    }0
})()