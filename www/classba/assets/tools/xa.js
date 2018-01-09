/**
 * Created by linxiao on 17/8/8.
 */
host = window.location.host;
var reportjs_id = "";
switch (host){
    case "math2.classba.cn" :
        reportjs_id = "1263344959";
        break;
    case "math2.171xue.com" :
        reportjs_id = "1263344959";
        break;
    case "en2.171xue.com" :
        reportjs_id = "1263344995";
        break;
    case "en1.171xue.com" :
        reportjs_id = "1263344995";
        break;
    case "en1.classba.cn" :
        reportjs_id = "1263344995";
        break;
    case "en-reading.classba.cn" :
        reportjs_id = "1263344995";
        break;
    case "cn2.171xue.com" :
        reportjs_id = "1263345020";
        break;
    case "cn2.classba.cn" :
        reportjs_id = "1263345020";
        break;
    case "phy.171xue.com" :
        reportjs_id = "1263345031";
        break;
    case "phy.classba.cn" :
        reportjs_id = "1263345031";
        break;
    case "localhost.math" :
        reportjs_id = "1263351340";
        break;
    case "math.local":
        reportjs_id = "1263358369";
        break;
    default :
        reportjs_id = "1263345112";
        break;
}
var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cspan id='cnzz_stat_icon_"+reportjs_id+"'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D"+reportjs_id+"%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));