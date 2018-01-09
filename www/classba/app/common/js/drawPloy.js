/**
 * Created by linxiao on 17/3/29.
 */
(function()
{
    var Sprite = Laya.Sprite;
    var Stage  = Laya.Stage;
    var WebGL  = Laya.WebGL;
    var sp;

    (function()
    {
        //初始化舞台，不支持WebGL时自动切换至Canvas
        Laya.init(500, 300, WebGL);
        drawSomething();
    })();

    function drawSomething()
    {
        sp = new Sprite();
        Laya.stage.addChild(sp);
        //画三角形
        sp.graphics.drawPoly(30, 28, [0, 100, 50, 0, 100, 100], "#ffff00");
        sp.graphics.drawPoly(190, 128, [0, 100, 50, 0, 100, 100, 75, 150, 25, 150], "#ffff00");
        var path = [];
        path.push(0, -130);//五角星A点
        path.push(33, -33);//五角星B点
        path.push(137, -30);//五角星C点
        path.push(55, 32);//五角星D点
        path.push(85, 130);//五角星E点
        path.push(0, 73);//五角星F点
        path.push(-85, 130);//五角星G点
        path.push(-55, 32);//五角星H点
        path.push(-137, -30);//五角星I点
        path.push(-33, -33);//五角星J点

        sp.graphics.drawPoly(Laya.stage.width / 2, Laya.stage.height / 2, path, "#FF7F50");

        sp.graphics.drawCircle(80,80,50,"#ff0000");
    }
})();