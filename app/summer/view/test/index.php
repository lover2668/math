{extend name="base" /}
{block name="title"}
<strong>基础先行测试</strong>
{/block}
{block name="css"}
<link href="__PUBLIC__/classba/app/math/css/math.css?v=1.0.000003" rel="stylesheet">
{/block}
{block name="mainContent"}
<div id="loading">
    <div id="loading-center">
        <div id="loading-center-absolute">
            <div class="object" id="object_one"></div>
            <div class="object" id="object_two"></div>
            <div class="object" id="object_three"></div>
            <div class="object" id="object_four"></div>
        </div>
    </div>
</div>
<div class="xx-container">
    <div class="xx-question-tools">
        <div class="xx-knowledge"><i class="xx-icon">&#xe649;</i>考察知识点：<span class="xx-tagcode" name="xx-tagcode"></span></div>
        <div class="xx-time-charts" id="xx-time-charts" style="">

        </div>
    </div>
    <h1 name="xx-question-type"></h1>
    <div class="xx-question-title" name="xx-question-title">
    </div>
    <div name="xx-question-option">
    </div>
    <div class="xx-continue" name="xx-continue">
    </div>
</div>
<div class="xx-footer"></div>
<div id="confirmTab" class="modal fade xx-confirm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">你尚未填写答案，是否确认提交？</h4>
            </div>
            <div class="modal-footer">
                <div class="btn xx-btn-dismiss" data-dismiss="modal">取&nbsp;&nbsp;消</div>
                <div class="btn xx-btn-confirm">确&nbsp;&nbsp;定</div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<input type="hidden"  name="question_id" value="{$question_id}" />
<input type="hidden"  name="type"  value="{$type}"  />
<input type="hidden"  name="is_test"  value="{$is_test}"  />
{/block}
{block name="js"}
<script id="editor" type="text/plain" style="width:1024px;height:500px;display:none"></script>
<script type="text/javascript" src="__PUBLIC__/classba/assets/editor/ueditor.config.js"></script>
<script type="text/javascript" src="__PUBLIC__/classba/assets/editor/ueditor.all.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/classba/assets/editor/kityformula-plugin/addKityFormulaDialog.js?v=1.3.2"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/classba/assets/editor/kityformula-plugin/getKfContent.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/classba/assets/editor/kityformula-plugin/defaultFilterFix.js"></script>
<script src="__PUBLIC__/classba/app/math/js/class.checkQuestionInfo.js?v=1.3.6"></script>
<script src="__PUBLIC__/classba/app/math/js/app-check-test.js"></script>
{/block}
