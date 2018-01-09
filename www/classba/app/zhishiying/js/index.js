/**
 *
 */
/*❀----导----航----❀*/
$(document).ready(function(){
    var $height = $(".star-sky img").height(),$width = $(".star-sky img").width();
    $(".star-sky-background").height($height).width($width);
    particlesJS('particles-js',

        {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 700
                    }
                },
                "color": {
                    "value": "#18D3B4"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    },
                    "image": {
                        "src": "img/github.svg",
                        "width": 100,
                        "height": 100
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 5,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#18D3B4",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 6,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 400,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true,
            "config_demo": {
                "hide_card": false,
                "background_color": "red",
                "background_image": "",
                "background_position": "50% 50%",
                "background_repeat": "no-repeat",
                "background_size": "contain"
            }
        }
    );
    var myPlayer = videojs("example_video_1");
    var howWideIsIt = $(".maxwidth-v4").width()*0.78;
    myPlayer.width(howWideIsIt);
    var howTallIsIt = howWideIsIt*.63;
    console.log(howTallIsIt)
    myPlayer.height(howTallIsIt);
    $(window).resize(function(){
        var $height = $(".star-sky img").height(),$width = $(".star-sky img").width();
        $(".star-sky-background").height($height).width($width);
        var howWideIsIt = $(".maxwidth-v4").width()*0.78;
        myPlayer.width(howWideIsIt);
        var howTallIsIt = howWideIsIt*.63;
        console.log(howTallIsIt)
        myPlayer.height(howTallIsIt);
    });


    var news_list = news;
    var news_html = "";
    for(var i = 0;i<news_list.length;i++){
        if(news_list[i].model=="lg"){
            news_html += '<div class="col-md-6 news-cell">'
                +'<a href="'+news_list[i].link+'" target="_blank"><img src="'+news_list[i].img+'"/><div class="txt">'
                +'<p>'+news_list[i].title+'</p></div></a></div>'
        }else{
            news_html += '<div class="col-md-3 news-cell">'
                +'<a href="'+news_list[i].link+'" target="_blank"><img src="'+news_list[i].img+'"/><div class="txt">'
                +'<p>'+news_list[i].title+'</p></div></a></div>'
        }

    }
    $("#wrapper").html(news_html);
    $(".txt").each(function (i,o){
        var $this = $(o),
            width = $this.siblings("img").width();// 或者js的offsetHeight获取
        $(this).css("width",width);
    });
    $("#wrapper .news-cell").hover(function(){

        $(this).find(".txt").stop().animate({height:"198px"},400);

        $(this).find(".txt p").stop().animate({paddingTop:"60px"},400);

    },function(){

        $(this).find(".txt").stop().animate({height:"45px"},400);

        $(this).find(".txt p").stop().animate({padding:"9px",paddingTop:"0px"},400);

    });
})