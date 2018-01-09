{extend name="base" /}
{block name="title"}
巩固学习
{/block}
{block name="css"}
<link rel="stylesheet" href="{:loadResource('classba/assets/video/video-js.css')}">
<link href="{:loadResource('classba/app/math/css/math.css')}" rel="stylesheet">
<style>
</style>
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
        <div class="xx-knowledge">
            <i class="xx-icon">&#xe66f;</i>考察知识点：
            <span class="xx-tagcode"  name="xx-tagcode">一次函数</span>
            <div class="btn xx-knowledge-video-button" style="display: none" data-toggle="modal" data-target="#knowledge-video"><i class="xx-icon">&#xe665;</i>&nbsp;知识点视频</div>
        </div>
        <div class="xx-time-charts" id="xx-time-charts">

        </div>
    </div>
    <h1 name="xx-question-type">01&nbsp;填空题</h1>

    <div class="xx-question-title" name="xx-question-title">

    </div>
    <div name="xx-question-option">

    </div>
    <div class="xx-analyse" name="question-analysis">
        <h2>分析</h2>
        <ul>

        </ul>
        <div class="xx-audio">
            <i class="xx-icon">&#xe65a;</i>
            <audio id="audio" src="__PUBLIC__/classba/assets/video/source/horse.ogg" style="visibility: hidden" controls="controls">
                Your browser does not support the audio element.
            </audio>
        </div>
    </div>
    <div class="xx-analyse" name="step-wise-analysis">
        <h2>分步解析</h2>
        <ul>

        </ul>
    </div>
    <div class="xx-continue" name="xx-continue">
    </div>
</div>
<div class="xx-footer"></div>

<div class="modal fade bs-example-modal-lg" id="knowledge-video" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myLargeModalLabel">知识点讲解视频</h4>
            </div>
            <div class="modal-body" style="background: #000">
                <video style="margin: auto" id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="868" height="488" poster="" data-setup="{}">
                    <source src="" type="video/mp4">
                    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                </video>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div id="confirmTab" class="modal fade xx-confirm" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">你尚未填写答案，是否确认提交？</h4>
            </div>
            <div class="modal-footer">
                <div class="btn xx-btn-dismiss" data-dismiss="modal">取&nbsp;&nbsp;消</div>
                <div class="btn xx-btn-confirm">确&nbsp;&nbsp;定</div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<input type="hidden"  name="topicId" value="{$topicId}" />
{/block}
{block name="js"}
<script id="editor" type="text/plain" style="width:1024px;height:500px;display:none"></script>
<script type="text/javascript" src="{:loadResource('classba/assets/editor/ueditor.config.js')}"></script>
<script type="text/javascript" src="{:loadResource('classba/assets/editor/ueditor.all.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('classba/assets/editor/kityformula-plugin/addKityFormulaDialog.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('classba/assets/editor/kityformula-plugin/getKfContent.js')}"></script>
<script type="text/javascript" charset="utf-8" src="{:loadResource('classba/assets/editor/kityformula-plugin/defaultFilterFix.js')}"></script>
<script type="text/javascript" src="{:loadResource('classba/assets/video/video.js')}"></script>
<script src="{:loadResource('classba/app/math/js/class.SummerAppGgStudy.js')}"></script>
<script src="{:loadResource('classba/app/math/js/app-gg-study.js')}"></script>
{/block}
