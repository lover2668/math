/**
 * Created by linxiao on 2017/5/14.
 */
function Index(opts) {
    this.ui = opts;
    this.domReady();
}

Index.prototype.domReady = function () {
    var thisObj = this;
    var question = thisObj.getQuestion();
    thisObj.createDomTitle(question, ".xx-container");
    thisObj.createDomOption(question);
    thisObj.createDomContinue();
};
Index.prototype.getQuestion = function () {
    var question;
    $.ajax({
        url: "../../static/app/en/js/question.json",
        data: {
        },
        type: 'POST',
        dataType: 'json',
        cache: false,
        async: false,
        success: function (response) {
            question = response;
            console.log(question)
        },
        complete: function () {
        }
    });
    return question;
}
// 提交试题
Index.prototype.initSubmit = function (topicId, answer_content, start_time) {
    var thisObj = this;
    $.ajax({
        url: "../../static/app/en/js/right.json",
        data: {
            topicId: 66,
            answer_content: [
                {question_id: "58b667ddf4aeb5432a11c665", type: "2", answer: "B"}
            ],
            spent_time: 448613
        },
        type: 'POST',
        dataType: 'json',
        success: function (response) {
            if (response.isSuccess == 1) {
                thisObj.domReady();
            } else {

            }
        },
        complete: function () {
        }
    });
}
Index.prototype.createDomTitle = function (opts, ui) {
    $("[name='xx-question-title']").html("asfdskadhfkjasdhfkjh")
}
Index.prototype.createDomContinue = function (opts, ui) {
    var thisObj = this;
    $("[name='xx-continue']").html('<button name="continue" class="btn btn-default" type="submit">Button</button>')
    $("[name='continue']").on("click", function () {

        thisObj.initSubmit();
    })
}
Index.prototype.createDomOption = function (opts) {
    var optionChild = "";
    var quetion_type = opts.question_list.q_type;
    if (quetion_type == "2") {
        optionChild += '<div class="form-group">' +
            '<label for="exampleInputEmail1"></label>' +
            '<input type="text" class="form-control" id="exampleInputEmail1" placeholder="">' +
            '</div>';
        $("[name='xx-question-option']").html(optionChild);
    } else if (quetion_type == "1") {
        var options = opts.question_list;
        var optionNum = options.options;
        for (var i = 0; i < optionNum.length; i++) {
            optionChild += '<div class="radio">' +
                '<label>' +
                '<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>' +
                '例子' + i +
                '</div>';
        }
        $("[name='xx-question-option']").html(optionChild);
    } else if (quetion_type == "3") {
        var options = opts.question_list;
        var optionNum = options.options;
        for (var i = 0; i < optionNum.length; i++) {
            optionChild += '<div class="checkbox">' +
                '<label>' +
                '<input type="checkbox" value="">' +
                '例子' + i +
                '</label>' +
                '</div>';
        }
        $("[name='xx-question-option']").html(optionChild);
    }
}



