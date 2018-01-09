/**
 * Created by linxiao on 17/4/14.
 */
$(function(){
    $("#subjects").height($("#subjects").width());
    var subjectChart = echarts.init(document.getElementById('subjects'));
    var option = {
        title: {
            text: '',
            subtext: ''
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c}%"
        },
        toolbox: {
            feature: {
                dataView: {
                    readOnly: false
                },
                restore: {},
                saveAsImage: {}
            }
        },
        legend: {
            data: ['展现', '点击', '访问', '咨询', '订单']
        },
        calculable: true,
        series: [{
            name: '漏斗图',
            type: 'funnel',
            left: '10%',
            top: 60,
            //x2: 80,
            bottom: 60,
            width: '80%',
            //funnelAlign: 'left',
            // height: {totalHeight} - y - y2,
            min: 25,
            max: 100,
            minSize: '0%',
            maxSize: '100%',
            sort: 'descending',
            gap: 2,
            label: {
                normal: {
                    show: true,
                    position: 'inside'
                },
                emphasis: {
                    textStyle: {
                        fontSize: 20
                    }
                }
            },
            labelLine: {
                normal: {
                    length: 10,
                    lineStyle: {
                        width: 1,
                        type: 'solid'
                    }
                }
            },
            itemStyle: {
                normal: {
                    label: {
                        show: true,
                        formatter: function(val) { //让series 中的文字进行换行
                            return val.name.split("-").join("\n");

                        }
                    },
                    color: function (params) {
                        // build a color map as your need.
                        var colorList = [
                            '#C1232B', '#B5C334', '#FCCE10', '#E87C25', '#27727B',
                            '#FE8463', '#9BCA63', '#FAD860', '#F3A43B', '#60C0DD',
                            '#D7504B', '#C6E579', '#F4E001', '#F0805A', '#26C0C0'
                        ];
                        return colorList[params.dataIndex]
                    },
                    labelLine: {
                        show: true
                    }
                },
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            },
            data: [{
                value: 70,
                name: '第3层筛选-THE THIRD LAYER'
            }, {
                value: 55,
                name: '第4层筛选-THE FOURTH LAYER'
            }, {
                value: 40,
                name: '第5层筛选-THE FIFTH LAYER'
            }, {
                value: 25,
                name: '第6层筛选-THE SIXTH LAYER'
            },{
                value: 10,
                name: ''
            }, {
                value: 85,
                name: '第2层筛选-THE SECOND LAYER'
            }, {
                value: 100,
                name: '第1层筛选-THE FIRST LAYER'
            }]
        }]
    };
    subjectChart.setOption(option);
    window.addEventListener("resize", function () {
        $("#subjects").height($("#subjects").width());
        subjectChart.resize();
    });
})