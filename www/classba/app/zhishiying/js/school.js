/**
 * Created by linxiao on 17/4/14.
 */
$(document).ready(function(){
    //var school_list = school_list;
    console.info(school_list)
    var schoolHtml = "";
    for(var i = 0;i<school_list.length;i++){
        schoolHtml += '<div class="row" style="margin-top: 50px;margin-left: 5px;margin-right: 5px;">';
        var city = school_list[i].city,schools = school_list[i].school_list;
        schoolHtml += '<div class="school-title">' +
            '<span class="school-title-area">'+city+'</span>' +
            '<div class="col-md-3 school-title-line"></div>' +
            '</div><div class="row school-list">';
        for (var j = 0;j<schools.length;j++){
            schoolHtml += '<div class="col-md-6 media">' +
                '<div class="media-body">' +
                '<div class="col-md-8"><span class="media-heading" style="font-size: 18px">'+schools[j].school_name+'</span></div>' +
                '<div class="col-md-4" style="font-size: 12px">' +
                '<i class="glyphicon glyphicon-earphone" style="padding-top:6px;"></i>' +
                '&nbsp;&nbsp;&nbsp;'+schools[j].contact+'</div>' +
                '<div class="col-md-12" style="font-size: 10px">' +
                '<i class="glyphicon glyphicon-map-marker"></i>' +
                '&nbsp;'+schools[j].address+'</div></div></div>'
        }
        schoolHtml +="</div>"
    }
    $("#school-wrapper").html(schoolHtml);
})