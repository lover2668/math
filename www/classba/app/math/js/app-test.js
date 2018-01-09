/**
 * Created by linxiao on 2017/5/15.
 */
!(function ($) {
    var topicId = $("input[name=topicId]").val();
    var opts = {
        topicId:topicId,
        ui:""
    }
    var index = new SummerAppPreIndex(opts);
})(jQuery)