/**
 * Created by linxiao on 16/6/12.
 */
//判断用户答案是否正确
function checkUserAnswerIsRight(userAnswer,trueAnswer) {

    is_right   = true;
    userAnswer = String(userAnswer);
    trueAnswer = String(trueAnswer);
    userAnswer = userAnswer.replace(/^(\s)*|(\s)*$/g,"");
    userAnswerArr = new Array();
    trueAnswerArr = new Array();
    if(userAnswer!=trueAnswer) {

        if(userAnswer.indexOf(";")>-1) {
            trueAnswerArr = trueAnswer.split(";");
            userAnswerArr = userAnswer.split(";");
            for(i in userAnswerArr) {
                if(trueAnswerArr[i].indexOf("|")>-1) {
                    t_in_arr = trueAnswerArr[i].split("|");
                    userAnswerArr[i] = userAnswerArr[i].replace(/^(\s)+|(\s)+$/g,"").replace(/(\s)+/g," ");
                    if(!in_array(userAnswerArr[i],t_in_arr)) {
                        return false;
                    }
                }else{
                    if(userAnswerArr[i]!=trueAnswerArr[i]) {
                        return false;
                    }
                }
            }
        }else{
            if(trueAnswer.indexOf("|")>-1) {
                trueAnswerArr = trueAnswer.split("|");
                userAnswer = userAnswer.replace(/(\s)+/g," ");
                return in_array(userAnswer,trueAnswerArr);
            }else{
                userAnswer = userAnswer.replace(/(\s)+/g," ");
                return userAnswer==trueAnswer ? true : false;
            }
        }
    }
    return is_right;
}
function in_array(search,array){
    for(var i in array){
        if(array[i]==search){
            return true;
        }
    }
    return false;
}
