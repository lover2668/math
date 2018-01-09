/**
 * Created by linxiao on 17/2/23.
 */
var MY_UI = {
        trim: function (text, specText) {
            if (this.isEmpty(specText)) {
                if (typeof(text) == 'string') {
                    return text.replace(/^\s*|\s*$/g, "");
                } else {
                    return text;
                }
            } else {
                return text.replace(specText, "");
            }

        },
        isEmpty: function (val) {
            switch (typeof(val)) {
                case 'string' :
                    return MY_UI.trim(val).length == 0 ? true : false;
                    break;
                case 'number' :

                    return val == 0 ? true : false;
                    break;
                case 'object' :
                    return val == null ? true : false;
                    break;
                default:
                    return true;

            }
        },
        isEmail: function (email) {
            var reg = /([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;
            return reg.test(email);
        },
        isTel: function (tel) {
            var reg = /^[\d|\-|\s|\_]+$/; //只允许使用数字-空格等
            return reg.test(tel);
        },
        isMobile: function (mobile) {
            var reg = /(^0{0,1}[13|15|18|14]{2}[0-9]{9}$)/;
            return reg.test(mobile);
        },
        //要看数组或对象里是否有重复的值如果有返回true
        isRepeat: function (arr) {
            var temp = {};
            for (var i in arr) {
                if (temp[arr[i]])
                    return true;
                temp[arr[i]] = true;
            }
            return false;
        },

        getTruePos: function (el) {
            var parentEl = null;
            var parentEls = el.parents("li");
            var elPos = el.offset();
            for (i = 0; i < parentEls.length; i++) {
                if ($(parentEls[i]).css('position') == 'absolute' || $(parentEls[i]).css('position') == 'relative') {
                    var parentEl = $(parentEls[i]);
                    break;
                }
            }
            // 父元素存在绝对定位
            // 根据父元素确定位置
            if (parentEl !== null) {
                var pelPos = parentEl.offset();
                elPos = {'left': (elPos.left - pelPos.left), 'top': (elPos.top - pelPos.top)};
            }
            return elPos;
        },
        getAbsoultPos: function (el) {
            var elPos = el.offset();
            return elPos;
        },
        // 获取中间位置
        getCenterPos: function (el, size) {
            var fixTop = 60;
            var offset = el.offset();
            var size = arguments[1] ? size : {'h': el.height(), 'w': el.width()};
            return {
                'top': ($(window).height() - size.h) / 2 + $(window).scrollTop() - fixTop,
                'left': ($(window).width() - size.w) / 2 - offset.left
            };
        },
        // 重新定位光标位置
        setCursor: function (iItem) {
            if ($.browser.msie) {
                var range = iItem.createTextRange();
                range.collapse(false);
                range.select();
            } else {
                var ilength = $(iItem).val().length;
                $(iItem).focus();
                window.setTimeout(function () {
                    iItem.setSelectionRange(ilength, ilength);
                    iItem.focus();
                }, 0);
            }
        },
        htmlspecialcharsDecode: function (string, quote_style) {
            //       discuss at: http://phpjs.org/functions/htmlspecialchars_decode/
            //      original by: Mirek Slugen
            //      improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            //      bugfixed by: Mateusz "loonquawl" Zalega
            //      bugfixed by: Onno Marsman
            //      bugfixed by: Brett Zamir (http://brett-zamir.me)
            //      bugfixed by: Brett Zamir (http://brett-zamir.me)
            //         input by: ReverseSyntax
            //         input by: Slawomir Kaniecki
            //         input by: Scott Cariss
            //         input by: Francois
            //         input by: Ratheous
            //         input by: Mailfaker (http://www.weedem.fr/)
            //       revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            // reimplemented by: Brett Zamir (http://brett-zamir.me)
            //        example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES');
            //        returns 1: '<p>this -> &quot;</p>'
            //        example 2: htmlspecialchars_decode("&amp;quot;");
            //        returns 2: '&quot;'

            var optTemp = 0,
                i = 0,
                noquotes = false;
            if (typeof quote_style === 'undefined') {
                quote_style = 2;
            }
            string = string.toString()
                .replace(/&lt;/g, '<')
                .replace(/&gt;/g, '>');
            var OPTS = {
                'ENT_NOQUOTES': 0,
                'ENT_HTML_QUOTE_SINGLE': 1,
                'ENT_HTML_QUOTE_DOUBLE': 2,
                'ENT_COMPAT': 2,
                'ENT_QUOTES': 3,
                'ENT_IGNORE': 4
            };
            if (quote_style === 0) {
                noquotes = true;
            }
            if (typeof quote_style !== 'number') {
                // Allow for a single string or an array of string flags
                quote_style = [].concat(quote_style);
                for (i = 0; i < quote_style.length; i++) {
                    // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
                    if (OPTS[quote_style[i]] === 0) {
                        noquotes = true;
                    } else if (OPTS[quote_style[i]]) {
                        optTemp = optTemp | OPTS[quote_style[i]];
                    }
                }
                quote_style = optTemp;
            }
            if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
                string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
                // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
            }
            if (!noquotes) {
                string = string.replace(/&quot;/g, '"');
            }
            // Put this in last place to avoid escape being double-decoded
            string = string.replace(/&amp;/g, '&');

            return string;
        },
        rulesFilter: function (content, demoDom) {
            var flag = [/##\$\$##/g, /[_]+[1-9]*[_]+/];
            for (var j = 0; j < flag.length; j++) {
                for (var i = 0; i < content.length; i++) {
                    content = content.replace(flag[j], '<span class="input_editor" data-num="input' + i + '">请输入答案</span>');
                }
            }
            return content;
        },
        /**
         * 转全角字符
         */
        toDBC: function (str) {
            var result = "";
            var len = str.length;
            for (var i = 0; i < len; i++) {
                var cCode = str.charCodeAt(i);
                //全角与半角相差（除空格外）：65248(十进制)
                cCode = (cCode >= 0x0021 && cCode <= 0x007E) ? (cCode + 65248) : cCode;
                //处理空格
                cCode = (cCode == 0x0020) ? 0x03000 : cCode;
                result += String.fromCharCode(cCode);
            }
            return result;
        },
        /**
         * 转半角字符
         */
        toSBC: function (str) {
            var result = "";
            var len = str.length;
            for (var i = 0; i < len; i++) {
                var cCode = str.charCodeAt(i);
                //全角与半角相差（除空格外）：65248（十进制）
                cCode = (cCode >= 0xFF01 && cCode <= 0xFF5E) ? (cCode - 65248) : cCode;
                //处理空格
                cCode = (cCode == 0x03000) ? 0x0020 : cCode;
                result += String.fromCharCode(cCode);
            }
            result = result.replace(/。/g, '.')
            return result;
        },
//全角转换为半角函数
        ToCDB: function (str) {
            var tmp = "";
            for (var i = 0; i < str.length; i++) {
                if (str.charCodeAt(i) > 65248 && str.charCodeAt(i) < 65375) {
                    tmp += String.fromCharCode(str.charCodeAt(i) - 65248);
                }
                else {
                    tmp += String.fromCharCode(str.charCodeAt(i));
                }
            }
            return tmp
        }
        ,
//全角半角校验
        issbccase: function (strTmp) {
            for (var i = 0; i < strTmp.length; i++) {
                if (strTmp.charCodeAt(i) > 128) {
                    return true;
                    break;
                }
            }
            return false;
        }
    }
    ;