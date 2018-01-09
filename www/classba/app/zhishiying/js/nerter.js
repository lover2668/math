/**
 * Created by linxiao on 17/4/13.
 */
$(function(){
    $(".box").on('mouseenter mouseleave',function(e){
        var fx = getDir(e,$(this));
        if(e.type == "mouseout" || e.type == "mouseleave"){
            iconLeave($(this).find(".box-mask-detail"),$(this).find(".box-mask"),fx);
        }else{
            iconEnter($(this).find(".box-mask-detail"),$(this).find(".box-mask"),fx);
        }

    })
    function getDir(e, data) {
        var size = {};
        size.w = data.innerWidth();
        size.h = data.innerHeight();
        var pos = data.offset();
        var x = (e.pageX - pos.left - (size.w / 2)) * (size.w > size.h ? (size.h / size.w) : 1);
        var y = (e.pageY - pos.top - (size.h / 2)) * (size.h > size.w ? (size.w / size.h) : 1);
        var direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;
        return direction;
    }

    function iconEnter(i,b,f){
        switch(f){
            case 0:
                b.css({top:"-100%",left:0}).stop(true,true).animate({top: 0}, 500);
                break;
            case 1:
                b.css({top:0,left:"100%"}).stop(true,true).animate({left: 0}, 500);
                break;
            case 2:
                b.css({top:"100%",left:0}).stop(true,true).animate({top: 0}, 500);
                break;
            case 3:
                b.css({top:0,left:"-100%"}).stop(true,true).animate({left: 0}, 500);
                break;
        }

    }

    function iconLeave(i,b,f){
        switch(f){
            case 0:
                b.stop(true,true).animate({top: "-100%"}, 500);
                break;
            case 1:
                b.stop(true,true).animate({left: "100%"}, 500);
                break;
            case 2:
                b.stop(true,true).animate({top: "100%"}, 500);
                break;
            case 3:
                b.stop(true,true).animate({left: "-100%"}, 500);
                break;
        }

    }

});