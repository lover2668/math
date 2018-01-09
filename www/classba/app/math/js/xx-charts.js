/**
 * Created by linxiao on 2017/5/16.
 */
//绘制蓝色外圈
function blueCircle(canvas,context,centerX,centerY,rad,n){
    if(n<=75){
        context.save();
        context.strokeStyle = "#16CC6C"; //设置描边样式
        context.lineWidth = 5; //设置线宽
        context.beginPath(); //路径开始
        context.arc(centerX, centerY, 40 , Math.PI*(3/4), Math.PI*(3/4) +n*rad, false); //用于绘制圆弧context.arc(x坐标，y坐标，半径，起始角度，终止角度，顺时针/逆时针)
        context.stroke(); //绘制
        context.restore();
    }
}
function innerCircle(canvas,context,centerX,centerY,startRad,endRad,rad,n){
    context.save();
    context.strokeStyle = "#16CC6C"; //设置描边样式
    context.lineWidth = 5; //设置线宽
    context.beginPath(); //路径开始
    console.log(startRad+"-------------"+ n*rad)
    context.arc(centerX, centerY, 40 , startRad, startRad+n*rad, false); //用于绘制圆弧context.arc(x坐标，y坐标，半径，起始角度，终止角度，顺时针/逆时针)
    context.stroke(); //绘制
    context.restore();
}
//绘制白色外圈
function whiteCircle(canvas,context,centerX,centerY,startRad,endRad){
    context.save();
    context.beginPath();
    context.strokeStyle = "#eaeaea";
    context.lineWidth = 5; //设置线宽
    context.arc(centerX, centerY, 40 , startRad, endRad, false);
    context.stroke();
    context.restore();
}

//动画循环
(function drawFrame(){
    var canvas = document.getElementById('xx-my-time'),  //获取canvas元素
        context = canvas.getContext('2d'),  //获取画图环境，指明为2d
        centerX = canvas.width/2,   //Canvas中心点x轴坐标
        centerY = canvas.height/2,  //Canvas中心点y轴坐标
        rad = Math.PI*2/100, //将360度分成100份，那么每一份就是rad度
        speed = 0.1; //加载的快慢就靠它了
    //window.requestAnimationFrame(drawFrame, canvas);
    context.clearRect(0, 0, canvas.width, canvas.height);
    var startRad = Math.PI*(3/4);
    var endRad = Math.PI*(1/4);
    whiteCircle(canvas,context,centerX,centerY,startRad,endRad);
    var timeIndex=0;
    function setTime(){
        var hour = parseInt(timeIndex / 3600);    // 计算时
        var minutes = parseInt((timeIndex % 3600) / 60);    // 计算分
        var seconds = parseInt(timeIndex % 60);    // 计算秒
        hour = hour < 10 ? "0" + hour : hour;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        $(".times").html(hour + ":" + minutes + ":" + seconds);
        var n = hour + ":" + minutes + ":" + seconds;
        blueCircle(canvas,context,centerX,centerY,rad,timeIndex);
        timeIndex++;
    }
    setTime();
    var times = setInterval(setTime, 1000);
    var canvas2 = document.getElementById('xx-my-right'),  //获取canvas元素
        context2 = canvas2.getContext('2d'),  //获取画图环境，指明为2d
        centerX2 = canvas2.width/2,   //Canvas中心点x轴坐标
        centerY2 = canvas2.height/2;  //Canvas中心点y轴坐标
    startRad = 0;
    endRad = Math.PI*2;
    whiteCircle(canvas2,context2,centerX2,centerY2,startRad,endRad);
    innerCircle(canvas2,context2,centerX2,centerY2,Math.PI*(-1/2),endRad,rad,10);
    $(".xx-right").html("当前正答率<br/>"+45+"%");

    var canvas3 = document.getElementById('xx-my-tested'),  //获取canvas元素
        context3 = canvas3.getContext('2d'),  //获取画图环境，指明为2d
        centerX3 = canvas3.width/2,   //Canvas中心点x轴坐标
        centerY3 = canvas3.height/2;  //Canvas中心点y轴坐标
    whiteCircle(canvas3,context3,centerX3,centerY3,startRad,endRad);
    innerCircle(canvas3,context3,centerX3,centerY3,Math.PI*(-1/2),endRad,rad,10);
    $(".tested").html("已学知识点<br/>"+45+"%");
}());