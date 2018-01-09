{extend name="base" /}
{block name="title"}
所有测试
{/block}
{block name="main"}
<div class="am-cf am-padding am-padding-bottom-0">
    <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">学生报告</strong> /
        <small>All information</small>
    </div>
</div>

<hr/>
<div class="am-u-sm-12">

    <table class="am-table am-table-striped am-table-hover table-main">
        <thead>
        <tr>
            <th class="table-id" style="width:80px">试题ID</th>
            <th class="table-author am-hide-sm-only" style="width:100px">试题</th>
            <th class="table-author am-hide-sm-only" style="width: 150px;">试题类型</th>
            <th class="table-type" style="width: 150px;">知识点</th>
            <th class="table-type" style="width: 150px;">正确答案</th>
            <th class="table-type" style="width: 150px;">学生答案</th>
             <th class="table-date am-hide-sm-only" >是否正确</th>
            <th class="table-date am-hide-sm-only" >是否查看过答案</th>
            <th class="table-date am-hide-sm-only" >是否查看过分析</th>
            <th class="table-date am-hide-sm-only" style="width: 200px;">提交时间</th>
        </tr>
        </thead>
        <tbody>
        {volist name="result" id="item"}
        <tr>

            <td>{$item.id}</td>
            <td >{$item.question["content"]|htmlspecialchars_decode|str_replace="##$$##","_______",###}</td>
            <td class="am-hide-sm-only" >
                {switch name="item.module_type"}
                {case value="1"}先行测试{/case}
                {case value="2"}边学边练->
                    {if condition="$item.submodule_type eq 1"}
                    边学边练
                {/if}
                {if condition="$item.submodule_type eq 2"}
                堂堂清
                {/if}
                {/case}
                {case value="3"}综合练习{/case}
                {/switch}
            </td>

            <td class="am-hide-sm-only" >{$item.tag_name}</td>

            <td class="am-hide-sm-only">

                {if condition="$item.q_type eq 1"}
                {$item.answer}
                {/if}

                {if condition="$item.q_type eq 2"}

                {assign name="m" value="1" /}
                {assign name="k" value="1" /}
                {volist name="item.answer_base64" key="blank_num" id="ans"  }
                {volist name="ans" key="answer_num" id="an"  }
                {if condition="strstr($an,'png;base64')"}
                <img  src="{$an}" />
                {else/}
                {$an}
                {/if}
                {if condition="$m neq  $answer_num"}
                <!--|-->
                {/if}
                {/volist}
                {if condition="$k neq  $answer_num"}
                <!--,-->
                {/if}

                {/volist}
                {/if}

            </td>
            <td class="am-hide-sm-only">

                {if condition="$item.q_type eq 1"}
                {$item.user_answer}
                {/if}
                {if condition="$item.q_type eq 2"}
                {if condition="$item.user_answer_base64 neq  '' "}
                {volist name="item.user_answer_base64" id="user_answer_base64_item"}
                <img src="{$user_answer_base64_item}"/>
                {/volist}
                {/if}
                {/if}

            </td>

            <td class="am-hide-sm-only">
                {if condition="$item['is_right'] eq 1"}
                <span class="am-icon-check" style="color: green;"></span>
                {else/}

                <i class="am-icon-times" style="color: #c10000;"></i>
                {/if}
            </td>
            <td class="am-hide-sm-only">
                {if condition="$item['is_view_answer'] eq 1"}
                <span class="am-icon-check"></span>
                {else/}

                <i class="am-icon-times"></i>
                {/if}
            </td>

            <td class="am-hide-sm-only">
                {if condition="$item['is_view_analyze'] eq 1"}
                <span class="am-icon-check"></span>
                {else/}

                <i class="am-icon-times"></i>
                {/if}
            </td>

            <td class="am-hide-sm-only">
                {$item.ctime|date="Y-m-d H:i:s",###}
            </td>

        </tr>
        {/volist}

        </tbody>
    </table>

    <div class="am-fr">
        {$page}
    </div>


</div>

{/block}