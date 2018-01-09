{extend name="base" /}
{block name="title"}
个人资料
{/block}
{block name="main"}
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
      <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">表格</strong> / <small>Table</small></div>
      </div>

      <hr>

      <div class="am-g">
        <div class="am-u-sm-12 am-u-md-6">
          <div class="am-btn-toolbar">
            <div class="am-btn-group am-btn-group-xs">
<!--              <button type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 新增</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-save"></span> 保存</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-archive"></span> 审核</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除</button>-->
            </div>
          </div>
        </div>
        <div class="am-u-sm-12 am-u-md-3">
          <div class="am-form-group">
              <form action="" method="get">
            <select data-am-selected="{btnSize: 'sm'}" name="user_id" id="user_id">
              {foreach name="alluserId" item="u" key="uk"}
              <option value="{$uk}">{$u}</option>
              {/foreach}
            </select>
            <button class="am-btn am-btn-default" type="submit">搜索</button>
            <button class="am-btn am-btn-default" type="button" id="list_export">导出</button>
              </form>
          </div>
        </div>
        <div class="am-u-sm-12 am-u-md-3">
          <div class="am-input-group am-input-group-sm">
            <input type="text" class="am-form-field">
          <span class="am-input-group-btn">
            <button class="am-btn am-btn-default" type="button">搜索</button>
          </span>
          </div>
        </div>
      </div>

      <div class="am-g">
        <div class="am-u-sm-12">
          <form class="am-form">
            <table class="am-table am-table-striped am-table-hover table-main">
              <thead>
              <tr>
                <th class="table-check"><input type="checkbox" /></th><th class="table-id">ID</th><th class="table-title">题目</th><th class="table-type">你的答案</th><th class="table-author am-hide-sm-only">是否答对</th><th class="table-date am-hide-sm-only">答题时间</th><th class="table-set">操作</th>
              </tr>
              </thead>
              <tbody>
              {foreach name="list.data" item="vo" }
              <tr>
                <td><input type="checkbox" /></td>
                <td>{$vo.id}</td>
                <td><a href="#">{if condition="isset($question_content[$vo['question_id']])"}{$question_content[$vo['question_id']]|htmlspecialchars_decode_and_replace} {/if}</a></td>
                <td><img src="{$vo.user_answer_base64}"</td>
                <td class="am-hide-sm-only">{if $vo.is_right eq 1}对{else}错{/if}</td>
                <td class="am-hide-sm-only">{$vo.ctime|dateof}</td>
                <td>
                  <div class="am-btn-toolbar">
                    <div class="am-btn-group am-btn-group-xs">
                      <button class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-pencil-square-o"></span> 编辑</button>
                      <button class="am-btn am-btn-default am-btn-xs am-hide-sm-only"><span class="am-icon-copy"></span> 复制</button>
                      <button class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><span class="am-icon-trash-o"></span> 删除</button>
                    </div>
                  </div>
                </td>
              </tr>
              {/foreach}
              </tbody>
            </table>
            <div class="am-cf">
              共 {$list.total} 条记录
              <div class="am-fr">
                  {$page}
<!--                <ul class="am-pagination">
                  <li class="am-disabled"><a href="#">«</a></li>
                  <li class="am-active"><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">4</a></li>
                  <li><a href="#">5</a></li>
                  <li><a href="#">»</a></li>
                </ul>-->
              </div>
            </div>
            <hr />
            <p>注：.....</p>
          </form>
        </div>

      </div>
    </div>
    <script>
    document.getElementById("list_export").onclick=function(){
        {php}
        $is_questionmark='?';
        if(strstr($_SERVER['REQUEST_URI'],'?')){
            $is_questionmark='';
        }
        {/php}
        window.open("{php}echo $_SERVER['REQUEST_URI'];{/php}"+'{$is_questionmark}&export=1');
    }
    </script>
    {/block}
