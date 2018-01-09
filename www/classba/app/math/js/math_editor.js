/**
 * Created by linxiao on 17/2/28.
 */
var KeleFE;
!function (e) {
    var t = function () {
        function t(t, n) {
            this.container = $(t).addClass("fe-container"), e.UI.render(this.container, n)
        }

        return t
    }();
    e.Base = t
}(KeleFE || (KeleFE = {}));
var KeleFE;
!function (e) {
    var t = function () {
        function e() {
        }

        return e.toolbar = [{
            className: "根号&分数",
            img: "001.png",
            symbol: [{symbolName: "分数", img: "002.png", latex: "\\frac{}{}"}, {
                symbolName: "根号",
                img: "003.png",
                latex: "\\sqrt{}"
            }, {symbolName: "n次根号", img: "004.png", latex: "\\sqrt[n]{}"}]
        }, {
            className: "上下标",
            img: "006.png",
            symbol: [{symbolName: "右上标", img: "007.png", latex: "^{}"}, {
                symbolName: "右下标",
                img: "008.png",
                latex: "_{}"
            }, {symbolName: "右上下标", img: "009.png", latex: "_{}^{}"}]
        }, {
            className: "不等号",
            img: "016.png",
            symbol: [{symbolName: "大于等于", img: "017.png", latex: "\\geq"}, {
                symbolName: "小于等于",
                img: "018.png",
                latex: "\\leq"
            }, {symbolName: "不等", img: "019.png", latex: "\\neq"}, {
                symbolName: "恒等",
                img: "020.png",
                latex: "\\equiv"
            }, {symbolName: "约等", img: "022.png", latex: "\\approx"}, {
                symbolName: "远大于",
                img: "023.png",
                latex: "\\gg"
            }, {symbolName: "远小于", img: "024.png", latex: "ll"}, {
                symbolName: "正比于",
                img: "025.png",
                latex: "\\propto"
            }, {symbolName: "相似", img: "026.png", latex: "\\sim"}, {
                symbolName: "相似2",
                img: "027.png",
                latex: "\\simeq"
            }, {symbolName: "全等", img: "028.png", latex: "\\cong"}]
        }, {
            className: "希腊字母",
            img: "029.png",
            symbol: [{symbolName: "α", img: "030.png", latex: "\\alpha"}, {
                symbolName: "β",
                img: "031.png",
                latex: "\\beta"
            }, {symbolName: "γ", img: "032.png", latex: "\\gamma"}, {
                symbolName: "δ",
                img: "033.png",
                latex: "\\delta"
            }, {symbolName: "ε", img: "034.png", latex: "\\epsilon"}, {
                symbolName: "ζ",
                img: "035.png",
                latex: "\\zeta"
            }, {symbolName: "θ", img: "036.png", latex: "\\theta"}, {
                symbolName: "ι",
                img: "037.png",
                latex: "\\iota"
            }, {symbolName: "κ", img: "038.png", latex: "\\kappa"}, {
                symbolName: "λ",
                img: "039.png",
                latex: "\\lambda"
            }, {symbolName: "μ", img: "040.png", latex: "\\mu"}, {
                symbolName: "η",
                img: "041.png",
                latex: "\\eta"
            }, {symbolName: "ν", img: "042.png", latex: "\\nu"}, {
                symbolName: "ξ",
                img: "043.png",
                latex: "\\xi"
            }, {symbolName: "ο", img: "044.png", latex: "o"}, {
                symbolName: "π",
                img: "045.png",
                latex: "\\pi"
            }, {symbolName: "ρ", img: "046.png", latex: "\rho"}, {
                symbolName: "σ",
                img: "047.png",
                latex: "\\sigma"
            }, {symbolName: "τ", img: "048.png", latex: "\\tau"}, {
                symbolName: "υ",
                img: "049.png",
                latex: "\\upsilon"
            }, {symbolName: "φ", img: "050.png", latex: "\\phi"}, {
                symbolName: "χ",
                img: "051.png",
                latex: "\\chi"
            }, {symbolName: "ψ", img: "052.png", latex: "\\psi"}, {
                symbolName: "ω",
                img: "053.png",
                latex: "\\omega"
            }, {symbolName: "Γ", img: "054.png", latex: "\\Gamma"}, {
                symbolName: "Δ",
                img: "055.png",
                latex: "\\Delta"
            }, {symbolName: "Θ", img: "056.png", latex: "\\Theta"}, {
                symbolName: "Λ",
                img: "057.png",
                latex: "\\Lambda"
            }, {symbolName: "Ξ", img: "058.png", latex: "\\Xi"}, {
                symbolName: "Π",
                img: "059.png",
                latex: "\\Pi"
            }, {symbolName: "Σ", img: "060.png", latex: "\\Sigma"}, {
                symbolName: "Φ",
                img: "061.png",
                latex: "\\Phi"
            }, {symbolName: "Ψ", img: "062.png", latex: "\\Psi"}, {symbolName: "Ω", img: "063.png", latex: "\\Omega"}]
        }, {
            className: "其他符号",
            img: "064.png",
            symbol: [{symbolName: "偏微分", img: "065.png", latex: "\\partial"}, {
                symbolName: "角",
                img: "066.png",
                latex: "\\angle"
            }, {symbolName: "°", img: "067.png", latex: "\\circ"}, {
                symbolName: "三角形",
                img: "068.png",
                latex: "\\triangle"
            }, {symbolName: "平行", img: "069.png", latex: "\\parallel"}, {
                symbolName: "垂直",
                img: "070.png",
                latex: "\\perp"
            }, {symbolName: "正方形", img: "071.png", latex: "\\square"}, {
                symbolName: "无穷",
                img: "175.png",
                latex: "\\infty"
            }]
        }, {
            className: "集合运算",
            img: "072.png",
            symbol: [{symbolName: "并集大写", img: "073.png", latex: "\\cup"}, {
                symbolName: "交集大写",
                img: "074.png",
                latex: "\\cap"
            }, {symbolName: "属于", img: "075.png", latex: "\\in"}, {
                symbolName: "不属于",
                img: "076.png",
                latex: "\\notin"
            }, {symbolName: "包含", img: "077.png", latex: "\\supseteq"}, {
                symbolName: "真包含",
                img: "078.png",
                latex: "\\supset"
            }, {symbolName: "被包含", img: "079.png", latex: "\\subseteq"}, {
                symbolName: "被真包含",
                img: "080.png",
                latex: "\\subset"
            }, {symbolName: "不被包含", img: "081.png", latex: "\\nsubseteq"}, {
                symbolName: "空集",
                img: "082.png",
                latex: "\\varnothing"
            }]
        }, {
            className: "逻辑符号",
            img: "083.png",
            symbol: [{symbolName: "因为", img: "084.png", latex: "\\because"}, {
                symbolName: "所以",
                img: "085.png",
                latex: "\\therefore"
            }, {symbolName: "且", img: "086.png", latex: "\\vee"}, {
                symbolName: "或",
                img: "087.png",
                latex: "\\wedge"
            }, {symbolName: "非", img: "088.png", latex: "\\neg"}, {
                symbolName: "任取",
                img: "089.png",
                latex: "\\forall"
            }, {symbolName: "存在", img: "090.png", latex: "\\exists"}]
        }, {
            className: "积分符号",
            img: "091.png",
            symbol: [{symbolName: "积分", img: "092.png", latex: "\\int"}, {
                symbolName: "曲线积分",
                img: "093.png",
                latex: "\\oint"
            }, {symbolName: "积分上下标", img: "094.png", latex: "\\int_{}^{}"}]
        }, {
            className: "求和符号",
            img: "174.png",
            symbol: [{symbolName: "求和符号右上下标", img: "097.png", latex: "\\sum_{}^{}"}, {
                symbolName: "大写交",
                img: "100.png",
                latex: "\\bigcap"
            }, {symbolName: "大写交下标", img: "101.png", latex: "\\bigcap_{}"}, {
                symbolName: "大写交上下标",
                img: "102.png",
                latex: "\\bigcap_{}^{}"
            }, {symbolName: "大写并", img: "105.png", latex: "\\bigcup"}, {
                symbolName: "大写并下标",
                img: "106.png",
                latex: "\\bigcup_{}"
            }, {symbolName: "大写并上下标", img: "107.png", latex: "\\bigcup_{}^{}"}]
        }, {className: "矩阵行列式", img: "110.png", symbol: []}, {
            className: "运算箭头",
            img: "111.png",
            symbol: [{symbolName: "左右单箭头", img: "112.png", latex: "\\leftrightarrow"}, {
                symbolName: "右单箭头",
                img: "113.png",
                latex: "\\rightarrow"
            }, {symbolName: "左单箭头", img: "114.png", latex: "\\leftarrow"}, {
                symbolName: "上下单箭头",
                img: "115.png",
                latex: "\\updownarrow"
            }, {symbolName: "上单箭头", img: "116.png", latex: "\\uparrow"}, {
                symbolName: "下单箭头",
                img: "117.png",
                latex: "\\downarrow"
            }, {symbolName: "左右双箭头", img: "118.png", latex: "\\Leftrightarrow"}, {
                symbolName: "右双箭头",
                img: "119.png",
                latex: "\\Rightarrow"
            }, {symbolName: "左双箭头", img: "120.png", latex: "\\Leftarrow"}, {
                symbolName: "上下双箭头",
                img: "121.png",
                latex: "\\Updownarrow"
            }, {symbolName: "上双箭头", img: "122.png", latex: "\\Uparrow"}, {
                symbolName: "下双箭头",
                img: "123.png",
                latex: "\\Downarrow"
            }, {symbolName: "右上单箭头", img: "124.png", latex: "\\nearrow"}, {
                symbolName: "左下单箭头",
                img: "125.png",
                latex: "\\swarrow"
            }, {symbolName: "右下单箭头", img: "126.png", latex: "\\searrow"}, {
                symbolName: "左上单箭头",
                img: "127.png",
                latex: "\\nwarrow"
            }, {symbolName: "带尾箭头", img: "130.png", latex: "\\mapsto"}]
        }, {
            className: "上下标箭头",
            img: "131.png",
            symbol: [{symbolName: "上标右箭头", img: "133.png", latex: "\\overrightarrow{}"}, {
                symbolName: "上标左箭头",
                img: "134.png",
                latex: "\\overleftarrow{}"
            }, {symbolName: "共轭", img: "135.png", latex: "\\bar{}"}, {
                symbolName: "双共轭",
                img: "136.png",
                latex: "\\bar{\\bar{}}"
            }, {symbolName: "下划线", img: "137.png", latex: "\\underline{}"}, {
                symbolName: "双下划线",
                img: "138.png",
                latex: "\\underline{\\underline{}}"
            }, {symbolName: "划掉", img: "147.png", latex: "\\not{}"}]
        }, {className: "带标注箭头", img: "148.png", symbol: []}, {
            className: "普通运算符号",
            img: "164.png",
            symbol: [{symbolName: "正负", img: "165.png", latex: "\\pm"}, {
                symbolName: "负正",
                img: "166.png",
                latex: "\\mp"
            }, {symbolName: "叉乘", img: "167.png", latex: "\\times"}, {
                symbolName: "除以",
                img: "168.png",
                latex: "\\div"
            }, {symbolName: "点", img: "169.png", latex: "\\cdot"}, {
                symbolName: "大点",
                img: "170.png",
                latex: "\\bullet"
            }, {symbolName: "圈叉乘", img: "171.png", latex: "\\otimes"}, {
                symbolName: "圈叉加",
                img: "172.png",
                latex: "\\oplus"
            }, {symbolName: "圈叉点", img: "173.png", latex: "\\odot"}]
        }], e
    }();
    e.Data = t
}(KeleFE || (KeleFE = {}));
var KeleFE;
!function (e) {
    var t = function () {
        function t() {
        }

        return t.render = function (t, n) {
            var i = $("<div class='fe-body'></div>").appendTo(t), r = MathQuill.getInterface(2).MathField(i.get(0), {
                spaceBehavesLikeTab: !0,
                handlers: {
                    edit: function () {
                        n.handlers.change(r.latex())
                    }
                }
            }), s = $("<div class='fe-toolbar'></div>").prependTo(t);
            $(e.Data.toolbar).each(function () {
                if (this.symbol.length) {
                    var e = $("<div class='fe-symbol-layer'></div>");
                    $(this.symbol).each(function () {
                        var t = this;
                        $("<img src='/static/assets/fomula-editor/images/formula/" + this.img + "'>").appendTo(e).click(function () {
                            r.write(t.latex)
                        })
                    }), $("<div class='fe-symbol'><img src='/static/assets/fomula-editor/images/formula/" + this.img + "'><br>" + this.className + "</div>").appendTo(s).append(e)
                }
            });
            var o = $("<div class='fe-footer'></div>").appendTo(t);
            return $('<button class="btn btn-submit">提交</button>').appendTo(o).click(function () {
                n.handlers.completed && n.handlers.completed(r.latex())
            }), $('<button class="btn btn-cancel">取消</button>').appendTo(o).click(function () {
                n.handlers.cancel && n.handlers.cancel()
            }), {mq: r}
        }, t
    }();
    e.UI = t
}(KeleFE || (KeleFE = {}));