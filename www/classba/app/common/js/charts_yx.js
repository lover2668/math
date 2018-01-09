/**
 * Created by nana on 2017/3/2.
 */
$(document).ready(function() {
//加盟校数量折线图
    var line_chart = document.getElementById('s1_line');
    var line_data = echarts.init(line_chart);
    line_data.setOption({
        title: {
            text: '加盟校数量趋势',
            textStyle:{
                fontSize:"14"
            }
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {},
        toolbox: {
            show: true,
            feature: {}
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: ['上上上周', '上上周', '上周', '本周']
        },
        yAxis: {
            type: 'value',
        },
        series: [
            {
                name: '加盟校数量',
                type: 'line',
                data: [],
                markPoint: {},
            }

        ]
    });
    /*折线图请求数据*/
    $.get('/static/app/common/js/chartdata.json').done(function (data) {
        line_data.setOption({
            series: [{
                name: '加盟校数量',
                data: data.number
            }],
        });
    });
//加盟校数量折线图end


//散点图
    var spot = echarts.init(document.getElementById('s2_line_spot'));
    var markLineOpt = {
        animation: false,
        lineStyle: {
            normal: {
                type: 'solid'
            }
        },
        /*--------------趋势线---------------*/
        data: [[{
            coord: [2, 2],
            symbol: 'none'
        }, {
            coord: [8, 8],
            symbol: 'none'
        }]]
        /*--------------趋势线end---------------*/
    };
    option = {
        title: {
            text: '加盟校增长',
            textStyle:{
                fontSize:"14"
            }
        },
        grid: {
            left: '3%',
            right: '7%',
            bottom: '3%',
            containLabel: true
        },
        tooltip: {
            showDelay: 0,
        },
        toolbox: {},
        grid: {
            left: '3%',
            right: '7%',
            bottom: '3%',
            top:'15%',
            containLabel: true
        },
        xAxis: [
            {
                type: 'value',
                scale: true,
                axisLabel: {
                    formatter: '{value}月'
                },
                splitLine: {
                    show: true
                }
            }
        ],
        yAxis: [
            {
                type: 'value',
                scale: true,
                axisLabel: {
                    formatter: '{value} '
                },
                splitLine: {
                    show: true
                }
            }
        ],
        series: [
            {
                markLine: markLineOpt,
                name: '加盟校',
                type: 'scatter',
                data: [],
                markArea: {
                    silent: true,
                    itemStyle: {
                        normal: {
                            color: 'transparent',
                            borderWidth: 1,
                            borderType: 'dashed'
                        }
                    },

                },

            },

        ]
    };
    spot.setOption(option);

    /*散点图请求数据*/
    $.get('/static/app/common/js/chartdata.json').done(function (go) {
        spot.setOption({
            series: [{
                name: '加盟校',
                data: go.spot
            }],
        });
    });
//散点图end

//柱形图
    var column = echarts.init(document.getElementById('s2_column'));
    column.setOption({
        title: {
            text: '加盟校人数TOP3',
            textStyle:{
                fontSize:"14"
            }
        },
        tooltip: {},
        legend: {
            data: ['智适应人数', '在读人数', '退课人数'],
            top:'10%'
        },
        grid: {
            left: '3%',
            right: '7%',
            bottom: '3%',
            top:'20%',
            containLabel: true
        },
        xAxis: {
            data: []
        },
        yAxis: {},
        series: [
            {
                name: '智适应人数',
                type: 'bar',
                stack: 'i',
                //itemStyle: {
                //    normal: {
                //        color:'#009688'
                //    }
                //},
                data: []
            },
            {
                name: '在读人数',
                type: 'bar',
                stack: 'i',
                //itemStyle: {
                //    normal: {
                //        color:'rgb(25, 183, 207)'
                //    }
                //},
                data: []
            },
            {
                name: '退课人数',
                type: 'bar',
                stack: 'i',
                //itemStyle: {
                //    normal: {
                //        color:'rgb(129, 227, 238)'
                //    }
                //},
                data: []
            }
        ]
    });

    /*柱状图请求数据*/

    $.get('/static/app/common/js/chartdata.json').done(function (col) {
        column.setOption({
            xAxis: {
                data: col.cata
            },
            series: [{
                name: '智适应人数',
                data: col.all
            },
                {
                    name: '在读人数',
                    data: col.count
                },
                {
                    name: '退课人数',
                    data: col.quit
                }]
        });


//    柱形图 end

//      饼图 start
        var pie_pie = echarts.init(document.getElementById('s2_pie'));
        pie_pie.setOption({
            title: {
                text: '在读学生城市分布',
                textStyle:{
                    fontSize:"14"
                }
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data:['直接访问','邮件营销','联盟广告','视频广告','搜索引擎']
            },
            series: [
                {
                    name:'城市分布',
                    type:'pie',
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: false,
                            textStyle: {
                                fontSize: '30',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data:[
                        335,225,345,789,456,989
                    ]
                }
            ]
        });
//饼图end
    /*图表end*/
    });
});