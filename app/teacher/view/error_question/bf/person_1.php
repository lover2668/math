{extend name="error_question/base" /}
{block name="title"}
个人错题
{/block}
{block name="main"}
    <div class="am-cf am-padding am-padding-bottom-0">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">个人错题</strong> / <small>Person</small>
        </div>
        <a style="font-size:16px;color: #333;float: right;" target="_blank" href="{:url('printQuestion')}" id="printQuestion"><i class="am-icon-print"></i> 打印错题</a>
    </div>
<hr>
<div class="am-g">
    <div class="am-u-sm-12">
        学生账号: <button type="button" class="am-btn am-btn-warning">{$username}</button>
    </div>

    </div>
        <div class="am-g">
            <div class="am-u-sm-6"><div id="page_1_pie1" style="width:80%;height:300px;text-align:center;"></div></div>
            <div class="am-u-sm-6">   <div id="page_1_pie2" style="width:80%;height:300px;text-align:center;"></div></div>
        </div>

    <hr>

    <div class="am-g">
        <div class="am-u-sm-12">
            <table class="am-table am-table-bordered" style="table-layout: fixed">
                <caption style="margin-bottom: 20px;">知识点掌握情况</caption>
                <tr><td width="20%">已掌握知识点</td><td>

                        {if condition="$haveAnsweredQuestion"}
                            {if condition="$strongElementsList"}
                            {$strongElementsList|implode='、',###}
                            {else /}
                            您还需要付出更多的努力！
                            {/if}
                        {else/}
                                该学生还未做过试题，无法判断掌握的知识点！
                        {/if}

                    </td></tr>
                <tr><td>未掌握知识点</td><td>

                        {if condition="$haveAnsweredQuestion"}
                            {if condition="$weakElementsList"}
                                {$weakElementsList|implode='、',###}
                                {else/}
                                太棒了，知识点您都掌握了！
                            {/if}
                        {else/}
                        该学生还未做过试题，无法判断未掌握的知识点！
                        {/if}
                    </td></tr>
                </table>
            </div>
        <div class="am-u-sm-12">

            <div style="overflow:auto;">
                <table class="am-table am-table-striped am-table-hover table-main" style="table-layout: fixed">
                    <thead>
                    <tr>
                       <th><label><input type="checkbox" name="checkAll"/> 全选</label></th> <th class="table-id">序号</th><th class="table-title">知识点</th><th class="table-type">题目</th><th class="table-author am-hide-sm-only">班级正答率</th><th class="table-set">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {volist name="data" id="vo"}
                    <tr>
                        <td><input type="checkbox" name="check[]" value="{$vo.exam_detail_id}"/></td>
                        <td>{$orderNumberStart++}</td>
                        <td style="word-wrap:break-word;">{$vo.tag_name}</td>
                        <td style="word-wrap:break-word;">{$vo.content|htmlspecialchars_decode_and_replace}</td>
                        <td class="am-hide-sm-only">{$vo.rightNum}/{$vo.totalNum}</td>
                        <td>
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <button onclick="openbatch({$vo.exam_detail_id})" class="am-btn am-btn-default am-btn-xs am-text-danger am-hide-sm-only"><i class="am-icon-file-text-o"></i> 查看</button>

                                </div>
                            </div>
                        </td>
                    </tr>
                    {/volist}
                    </tbody>
                </table>
            </div>
                <div class="am-cf">
                    共 {$total} 条记录
                    {$page}
                </div>


        </div>

    </div>


{/block}

{block name="js"}
<script type="text/javascript" src="/plugin/lib/echarts/echarts.min.js"></script>
<script src="/static/lib/layer/layer.js"></script>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var page_1_pie1 = echarts.init(document.getElementById('page_1_pie1'));
    var page_1_pie1_value = [{
        name: '个人正答率',
        value: {$rightNum}    }, {
        name: '',
        value: {$wrongNum}    }];
    var option1 = {
        title: {
            show: false
        },
        tooltip: {
            show: false
        },
        legend: {
            show: true,
            left: 'center',
            bottom: 0,
            data: [{
                name: page_1_pie1_value[0]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }, {
                name: page_1_pie1_value[1]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }]
        },
        series: [{
            type: 'pie',
            radius: ['60%', '80%'],
            label: {
                normal: {
                    position: 'center'
                }
            },
            data: [{
                value: page_1_pie1_value[0]['value'],
                name: page_1_pie1_value[0]['name'],
                itemStyle: {
                    normal: {
                        color: '#00B0A6'
                    }
                },
                label: {
                    normal: {
                        formatter: function (v) {
                            return Math.round(v.percent) + '%';
                        },
                        textStyle: {
                            fontSize: 30,
                            fontWeight: 'bold',
                            color: '#00B0A6'
                        }
                    }
                }
            }, {
                value: page_1_pie1_value[1]['value'],
                name: page_1_pie1_value[1]['name'],
                itemStyle: {
                    normal: {
                        color: '#C2E4E3'
                    }
                },
                label: {
                    show: false,
                    normal: {
                        formatter: ''
                    }
                }

            }]
        }]
    };
    page_1_pie1.setOption(option1);


    //page pie 2
    var page_1_pie2 = echarts.init(document.getElementById('page_1_pie2'));
    var page_1_pie2_value = [{
        name: '班级正答率',
        value:{$rightNumOfClass}   }, {
        name: '',
        value: {$wrongNumOfClass}    }];
    var option2 = {
        title: {
            show: false
        },
        tooltip: {
            show: false
        },
        legend: {
            show: true,
            left: 'center',
            bottom: 0,
            data: [{
                name: page_1_pie2_value[0]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }, {
                name: page_1_pie2_value[1]['name'],
                icon: 'circle',
                textStyle: {
                    color: '#00B0A6'
                }
            }]
        },
        series: [{
            type: 'pie',
            radius: ['60%', '80%'],
            label: {
                normal: {
                    position: 'center'
                }
            },
            data: [{
                value: page_1_pie2_value[0]['value'],
                name: page_1_pie2_value[0]['name'],
                itemStyle: {
                    normal: {
                        color: '#00B0A6'
                    }
                },
                label: {
                    normal: {
                        formatter: function (v) {
                            return Math.round(v.percent) + '%';
                        },
                        textStyle: {
                            fontSize: 30,
                            fontWeight: 'bold',
                            color: '#00B0A6'
                        }
                    }
                }
            }, {
                value: page_1_pie2_value[1]['value'],
                name: page_1_pie2_value[1]['name'],
                itemStyle: {
                    normal: {
                        color: '#C2E4E3'
                    }
                },
                label: {
                    show: false,
                    normal: {
                        formatter: ''
                    }
                }

            }]
        }]
    };
    page_1_pie2.setOption(option2);



    function openbatch(id){
        //iframe窗
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
                    title: '试题详情',
                    shadeClose: true,
                    shade: false,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['80%', '80%'],
                    content: '{url link="question" vars="" suffix="true" domain="true"}?id='+id
                });
            }
        });
    }

    $(document).ready(function () {
        $("input[name=checkAll]").click(function () {
            if(this.checked)
            {
                $("tr td input").prop("checked", true);
            }else
            {
                $("tr td input").prop("checked", false);
            }
        })

        $("input[name='check[]']").click(function () {
            var checkedLength=$("input[name='check[]']:checked").length;
            var length=$("input[name='check[]']").length;
            if(checkedLength==length)
            {
                $("input[name=checkAll]").prop("checked", true);
            }else
            {
                $("input[name=checkAll]").prop("checked", false);
            }
        })

        $("#printQuestion").click(function () {
            var selectCheckBox=$("input[name='check[]']:checked");
            var ids=[];
            var ids_str='';
            if(selectCheckBox.length>0)
            {
                $.each(selectCheckBox,function (index,item) {
                    ids.push($(item).val());
                    ids_str+='id['+index+']='+$(item).val()+'&';
                });
                //var href=$(this).attr("href")+"?ids="+ids.join("|");
                var href=$(this).attr("href")+"?"+ids_str;
                $(this).attr("href",href);
            }else
            {
                layer.alert('请在下方选择要打印的错题！', {icon: 6});
                return false;
            }

        })

    })

</script>
{/block}