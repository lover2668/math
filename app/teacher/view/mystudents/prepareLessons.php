{extend name="base" /}
{block name="title"}
备课
{/block}
{block name="css"}
<style>
    @media screen and (min-width: 769px) {
        .am-table > thead > tr > th:nth-of-type(1) {
            min-width: 80px;
        }
        .am-table > thead > tr > th:nth-of-type(2) {
            min-width: 200px;
        }
        .am-table > thead > tr > th:nth-of-type(3) {
            min-width: 200px;
        }
        .am-table > thead > tr > th:nth-of-type(4) {
            min-width: 200px;
        }
        .am-table > thead > tr > th:nth-of-type(5) {
            min-width: 100px;
        }
        .am-btn-primary i {
            display: inline-block;
        }
    }
    @media screen and (max-width: 768px) {
        .am-input-group {
            width: 100%;
        }
        .am-input-group .am-input-group-btn {
            width: 100%;
        }
        .am-input-group.am-input-group-btn form {
            width: 100%;
        }
        .am-input-group .am-form-group {
            margin-bottom: 0;
        }
        .am-input-group .am-selected.am-dropdown {
            display: block;
            margin-bottom: 5px;
            width: 100%;
        }
        .am-input-group .am-btn-primary {
            float: right;
        }
        .am-table {
            margin-top: 10px;
        }
        .am-table > thead > tr > th:nth-of-type(1) {
            width: 15%;
        }
        .am-table > thead > tr > th:nth-of-type(2) {
            width: 25%;
        }
        .am-table > thead > tr > th:nth-of-type(3) {
            width: 22%;
        }
        .am-table > thead > tr > th:nth-of-type(4) {
            width: 22%;
        }
        .am-table > thead > tr > th:nth-of-type(5) {
            width: 16%;
        }
        .am-btn-primary i {
            display: none;
        }
    }
    .am-table tbody img {
        max-width: 100%;
    }
</style>
{/block}
{block name="main"}
<script src="/static/js/jquery-1.11.3.js"></script>
<script src="/static/layer/layer.js"></script>

      <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">试题</strong> / <small>列表/{$title}</small></div>
      </div>

      <hr>

<div class="am-g">
    <div class="am-u-sm-12 am-u-md-12">
        <div class="am-input-group am-input-group-sm">

            <div class="am-form-group">

            </div>


            <span class="am-input-group-btn">
                <form method="get">
                    {include file="common/filter"/}
                    <button class="am-btn am-btn-primary " type="submit">查询</button>
                </form>
          </span>

        </div>
    </div>
</div>

      <div class="am-g">
        <div class="am-u-sm-12">
                    <table class="am-table table-main">
                  <thead>
                  <tr   class="info">
                    <th class="table-title"  align="center">ID<!--(question_id)--></th>
                    <th class="table-title"  align="center">题目<!--(question)--></th>
                    <th class="table-title"  align="center">正确答案<!--(right_answer)--></th>
                    <th class="table-title"  align="center">用户答案<!--(user_answer)--></th>
                    <th class="table-title">操作</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  if(input("class_id")) {
                      ?>
                      {if condition="$data"}
                      {volist name="data" id="item"}
                      <tr>
                          <td>
                              {$item.id}
                          </td>
                          <td>
                              {$item.question.content|htmlspecialchars_decode_and_replace|strip_tags|str_replace="&nbsp;","
                              ",###|cut_str=###,23}
                          </td>
                          <td>
                              {if condition="$item.question.q_forms eq 1 "}
                              {$item.question.analyze_text|html_entity_decode}
                              {else/}
                              {if condition="$item.question.type eq 2 or $item.question.type eq 3"} <!--填空题或多选题-->
                              {php}
                              $answers=json_decode($item["right_answer"],true);
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
                              {$item.right_answer}
                              {/if}

                              {/if}

                              {if condition="$item.question.type eq 1"} <!--单选题-->
                              {$item.right_answer}
                              {/if}
                              {/if}
                          </td>
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
                          <td >

                              <a href="javascript:void(0)" class="am-btn am-btn-primary am-btn-xs" onclick="showQuestion({$item.id});"><i class="am-icon-file-excel-o"></i> 批改</a>

                          </td>
                      </tr>

                      {/volist}
                      {else/}
                      <tr><td colspan="5" class="am-text-center">暂无数据！</td> </tr>
                      {/if}
                      <?php
                  }else
                  {
                      ?>
                      <tr><td colspan="5" class="am-text-center" style="font-size:18px;color: #c10000">请选择班级和课程！</td> </tr>
                      <?php
                  }
                  ?>
                  </tbody>
                </table>
            <div class="am-cf">
              <div class="am-fr" id="am-fr">
                 {$page}
              </div>
            </div>
              <hr />
        

      </div>
    </div>


    {/block}

{block name="js"}
<script type="text/javascript">
    function showQuestion(id) {
        var param="?id="+id;
        layer.open({
            type: 2,
            title: false,
            closeBtn: 0, //不显示关闭按钮
            shade: [0],
            offset: 'rb', //右下角弹出
            time: 100, //2秒后自动关闭
            shift: 2,
            end: function(){ //此处用于演示
                layer.open({
                    type: 2,
                    title: '错题详情',
                    shadeClose: true,
                    shade: false,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['90%', '80%'],
                    content: '{url link="readingEvent" vars="" suffix="true" domain="true"}'+param
                });
            }
        });
    }
</script>
{/block}