/**
 * Created by linxiao on 17/3/14.
 */
(function(){
    var player = videojs('example_video_1', {'playbackRates': [0.5, 1, 1.5, 2,2.5,3] }, function() {
        console.log('Good to go!');
        //this.play();
        var player = this;
        //this.volume(0.5);
        this.on('timeupdate', function() {
            var howLongIsThis = player.duration();
            var whatHasBeenBuffered = player.buffered();
            var howMuchIsDownloaded = player.bufferedPercent();
            var howLoudIsIt = player.volume();
            var currentTime = player.currentTime();
            $("#played_time").html(playedTime(this)+"秒");
            $("#video_time").html(howLongIsThis+"秒");
            $("#current_time").html(currentTime+"秒")
//                    console.log(howLongIsThis+"----"+howMuchIsDownloaded+"----"+whatHasBeenBuffered+"---"+howLoudIsIt+"---"+currentTime)
        });
        pauseTimes(player);
        var play_times = $("#play_times").val();
        playTimes(this,play_times,true);
        this.on('seeking', function() {
            forbiddenControl(this,false);
        });
        $('#play_speed').change(function(){
            var speed = $(this).children('option:selected').val();//这就是selected的值
            player.playbackRate(speed);
        });


    });
    $("#reset").on("click",function(){
        localStorage.clear();
        player.load(player.children_[0].currentSrc);
        playTimes(player,2,true);
        $("#played_time").html(0+"秒");
        $("#video_time").html(0+"秒");
        $("#current_time").html(0+"秒")
        if(document.getElementById("shade"))
        {
            document.getElementById(player.id_).removeChild(document.getElementById("shade"));
        }
    });
    function pauseTimes(elem){
        //统计暂停次数;
        var pauseTimes  = 0;
        elem.on('pause', function() {
            if(elem.currentTime()<elem.duration())
                pauseTimes++;
            $("#pause_times").html(pauseTimes);
        });
    }
    //禁止拖动
    function forbiddenControl(elem,boolean){
        console.log(elem.currentTime()+"======"+elem.played().end(0))
        if(!boolean){
            if(elem.currentTime()>elem.played().end(0)){
                player.currentTime(elem.played().end(0));
            }
        }
    }
    //控制播放次数;
    function playTimes(elem, times,autoplay) {
        console.log(localStorage.getItem("playTimes")<times)
        var start = (localStorage.getItem("playTimes")<times)?0:localStorage.getItem("playTimes");
        if(start >= times){
            elem.pause();
            videoShade(elem,times,"播放结束");
        }else{
            elem.on("ended",function() {
                start++;
                localStorage.setItem("playTimes",start);
                if(start >= times){
                    videoShade(elem,times,"播放结束");
                }else{
                    //执行showTime()
                    var i = 3;
                    var intervalid;
                    intervalid = setInterval(function(){
                        videoShade(elem,times,i+"秒后自动播放");
                        if (i == 0) {
                            elem.play();
                            clearInterval(intervalid);
                            if(document.getElementById("shade"))
                            {
                                document.getElementById(elem.id_).removeChild(document.getElementById("shade"));
                            }
                        }
                        i--;
                    }, 1000);
                }
            });
        }
    }

    //获取视频已经播放的时间
    function playedTime(elem){
        return elem.played().end(0)
    }
    //播放结束先是浮层
    function videoShade(elem,times,text){
        var shade_height = elem.height(),shade_width = elem.width();
        console.log(shade_height);
        if(document.getElementById("shade"))
        {
            document.getElementById(elem.id_).removeChild(document.getElementById("shade"));
        }
        var $shade_div = document.createElement("div");
        $shade_div.id = "shade";
        $shade_div.innerHTML= text;
        $shade_div.style.height = shade_height+"px";
        $shade_div.style.position = "absolute";
        $shade_div.style.width = shade_width+"px";
        $shade_div.style.textAlign = "center";
        $shade_div.style.lineHeight = shade_height+"px";
        $shade_div.style.fontSize = "28px";
        $shade_div.style.background = "#000";
        $shade_div.style.opacity = 0.4;
        document.getElementById(elem.id_).appendChild($shade_div);
    }
    //记录播放时间;
    function playTime(elem, times) {
        var start = 0;
        elem.on("ended",function() {
            start++;
            start == times && elem.pause();
            if(start == times && elem.pause()){
                alert("播放结束")
            }
        });
    }
})()