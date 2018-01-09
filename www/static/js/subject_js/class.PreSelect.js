/**
 * Created by sks on 2016/9/21.
 */
var toCopyPage=function(topicId){
    $.ajax({
        type:"POST",
        data:{
            topicId:topicId
        },
        url:HOST+'/index/index/getUserExamStepLog',
        dataType:'json',
        success:function(data){
            console.log(data);
            //return;
            if(data.module_type==1){
                if(data.is_end==0){
                    window.open(HOST+"index/index/videoIndex/topicId/"+topicId,"_self");
                }
                else if(data.is_end==1){
                    window.open(HOST+"index/index/preReport/topicId/"+topicId,"_self");
                }
            }
            else if(data.module_type==2){
                if(data.is_end==0){
                    window.open(HOST+"index/bxbl/bxblQuestion/topicId/"+topicId,"_self");
                }
                else if(data.is_end==1){
                    window.open(HOST+"index/bxbl/ttqReport/topicId/"+topicId,"_self");
                }
            }
            else if(data.module_type==3){
                if(data.is_end==0){
                    window.open(HOST+"index/zhlx/zhlxQuestion/topicId/"+topicId,"_self");
                }
                else if(data.is_end==1){
                    window.open(HOST+"index/zhlx/zhlxReport/topicId/"+topicId,"_self");
                }
            }
        }
    })
}