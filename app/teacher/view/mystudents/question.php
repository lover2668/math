<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="/plugin/lib/css/amazeui.min.css"/>
</head>
<body>
<table class="am-table am-table-bordered" style="margin-top: 10px;">
    <caption style="font-size: 16px;margin-bottom: 10px;">
        <button type="button" class="am-btn am-btn-success">试题详情</button>
    </caption>
    <tbody>
    <tr>
        <td style="width:10em;">题目</td>
        <td>
           {$question.content|htmlspecialchars_decode_and_replace}
            <div>
                {if condition="$question.type eq 1 or $question.type eq 3"}
                <!--选择题 或多选题-->
                {if condition="$question.options"}
                {volist name="$question.options" id="option"}
                {$option.key}.{$option.answer|html_entity_decode|strip_tags }<br/>
                {/volist}
                {/if}
                {/if}
            </div>
        </td>
    </tr>
    <tr>
        <td> {if condition="$question.q_forms eq 1 "}
            解析
            {else/}
            正确答案
            {/if}</td>
        <td><p><span>
            {if condition="$question.q_forms eq 1 "}
                                                        {$question.analyze_text|html_entity_decode}
                                                    {else/}
                                                            {if condition="$question.type eq 2 or $question.type eq 3"} <!--填空题或多选题-->
                                                            {php}
                                                            $answers=$question["answer"];
                                                            {/php}
                                                            {if condition="is_array($answers)"}
                                                            {php}$count=count($answers);{/php}
                                                            {volist name="answers" id="answer"}
                                                            {$answer|implode=",",###}
                                                            {if condition="($key+1)<$count"}
                                                            ,
                                                            {/if}

                                                            {/volist}
                                                            {else/}
                                                            {$question.answer}
                                                            {/if}

                                                            {/if}

                                                            {if condition="$question.type eq 1"} <!--单选题-->
                                                            {$question.answer}
                                                            {/if}
                                                    {/if}
        </span></p></td>
    </tr>
    </tbody>
</table>

<table class="am-table am-table-bordered am-table-striped am-table-compact">
    <caption style="font-size: 16px;margin-bottom: 10px;">
        <button type="button" class="am-btn am-btn-danger">答错的学生</button>
    </caption>
    <thead>
    <tr>
        <th style="width:10em;">学生名称</th>
        <th>答案</th>
    </tr>
    </thead>
    <tbody>


        {volist name="errStudentsArr" id="item"}
        <tr>
            <td>{$item.username}</td>
            <td>
                {if condition="empty($item.user_answer)"}
                <p> 未做答</p>
                {else/}
                {if condition="$item.q_forms eq 1"}
                <p style="color: green">{:str_replace("###",",",$item.user_answer)}</p>
                {else/}
                {if condition="$item.is_right eq 1"}
                <p style="color: green;font-weight: ">{:str_replace("###",",",$item.user_answer)}</p>
                {else/}
                <p style="color: red">{:str_replace("###",",",$item.user_answer)}</p>
                {/if}
                {/if}
                {/if}
            </td>
        </tr>
        {/volist}

    </tbody>
</table>
<table>
    <tr></tr>
</table>
</body>
</html>