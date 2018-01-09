//课程分类一级tab切换
        $(".titleClass>ul>li").click(function(e){
           
            e.preventDefault();
             alert(1);
            var index=$(this).index();
            //根据学科换背景
            if(index==0){
                $(".courseClass").css("background-image","url(assets/img/part2_bg_english.png)");
            }else if(index==1){
                $(".courseClass").css("background-image","url(assets/img/part2_bg_math.png)");
            }else if(index==2){
                $(".courseClass").css("background-image","url(assets/img/part2_bg_chinese.png)");
            }else if(index==3){
                $(".courseClass").css("background-image","url(assets/img/part2_bg_physics.png)");
            }else if(index==4){
                 $(".courseClass").css("background-image","url(assets/img/part2_bg_chemistry.png)");
            }
            $(this).addClass("tabbg").siblings("li").removeClass("tabbg");
            $('.titleClass-cont').find("section").
            eq(index).css('display','block').siblings("section").css("display","none");
        })
        //课程分类二级tab切换-划过切换-英语
		$(".english>ul>li").click(function(){
            var ind=$(this).index();
            $(this).addClass("fontbg").siblings("li").removeClass("fontbg");
            $(".english-content").children("li").
            eq(ind).css('display','block').siblings("li").css("display","none");
        })
         //课程分类二级tab切换-划过切换-数学
		$(".math>ul>li").click(function(){
            var ind=$(this).index();
            $(this).addClass("fontbg").siblings("li").removeClass("fontbg");
            $(".math-content").children("li").
            eq(ind).css('display','block').siblings("li").css("display","none");
        })
          //课程分类二级tab切换-划过切换-语文
		$(".chinese>ul>li").click(function(){
            var ind=$(this).index();
            $(this).addClass("fontbg").siblings("li").removeClass("fontbg");
            $(".chinese-content").children("li").
            eq(ind).css('display','block').siblings("li").css("display","none");
        })