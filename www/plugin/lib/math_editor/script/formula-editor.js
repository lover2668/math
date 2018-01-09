!function (e, t) {
    function n(e) {
        return F.isWindow(e) ? e : 9 === e.nodeType && (e.defaultView || e.parentWindow)
    }

    function i(e) {
        if (!yt[e]) {
            var t = A.body, n = F("<" + e + ">").appendTo(t), i = n.css("display");
            n.remove(), "none" !== i && "" !== i || (dt || (dt = A.createElement("iframe"), dt.frameBorder = dt.width = dt.height = 0), t.appendChild(dt), mt && dt.createElement || (mt = (dt.contentWindow || dt.contentDocument).document, mt.write((F.support.boxModel ? "<!doctype html>" : "") + "<html><body>"), mt.close()), n = mt.createElement(e), mt.body.appendChild(n), i = F.css(n, "display"), t.removeChild(dt)), yt[e] = i
        }
        return yt[e]
    }

    function r(e, t) {
        var n = {};
        return F.each(wt.concat.apply([], wt.slice(0, t)), function () {
            n[this] = e
        }), n
    }

    function s() {
        vt = t
    }

    function o() {
        return setTimeout(s, 0), vt = F.now()
    }

    function a() {
        try {
            return new e.ActiveXObject("Microsoft.XMLHTTP")
        } catch (t) {
        }
    }

    function l() {
        try {
            return new e.XMLHttpRequest
        } catch (t) {
        }
    }

    function c(e, n) {
        e.dataFilter && (n = e.dataFilter(n, e.dataType));
        var i, r, s, o, a, l, c, u, f = e.dataTypes, p = {}, h = f.length, d = f[0];
        for (i = 1; i < h; i++) {
            if (1 === i)for (r in e.converters)"string" == typeof r && (p[r.toLowerCase()] = e.converters[r]);
            if (o = d, d = f[i], "*" === d)d = o; else if ("*" !== o && o !== d) {
                if (a = o + " " + d, l = p[a] || p["* " + d], !l) {
                    u = t;
                    for (c in p)if (s = c.split(" "), (s[0] === o || "*" === s[0]) && (u = p[s[1] + " " + d])) {
                        c = p[c], c === !0 ? l = u : u === !0 && (l = c);
                        break
                    }
                }
                !l && !u && F.error("No conversion from " + a.replace(" ", " to ")), l !== !0 && (n = l ? l(n) : u(c(n)))
            }
        }
        return n
    }

    function u(e, n, i) {
        var r, s, o, a, l = e.contents, c = e.dataTypes, u = e.responseFields;
        for (s in u)s in i && (n[u[s]] = i[s]);
        for (; "*" === c[0];)c.shift(), r === t && (r = e.mimeType || n.getResponseHeader("content-type"));
        if (r)for (s in l)if (l[s] && l[s].test(r)) {
            c.unshift(s);
            break
        }
        if (c[0] in i)o = c[0]; else {
            for (s in i) {
                if (!c[0] || e.converters[s + " " + c[0]]) {
                    o = s;
                    break
                }
                a || (a = s)
            }
            o = o || a
        }
        if (o)return o !== c[0] && c.unshift(o), i[o]
    }

    function f(e, t, n, i) {
        if (F.isArray(t))F.each(t, function (t, r) {
            n || ze.test(e) ? i(e, r) : f(e + "[" + ("object" == typeof r ? t : "") + "]", r, n, i)
        }); else if (n || "object" !== F.type(t))i(e, t); else for (var r in t)f(e + "[" + r + "]", t[r], n, i)
    }

    function p(e, n) {
        var i, r, s = F.ajaxSettings.flatOptions || {};
        for (i in n)n[i] !== t && ((s[i] ? e : r || (r = {}))[i] = n[i]);
        r && F.extend(!0, e, r)
    }

    function h(e, n, i, r, s, o) {
        s = s || n.dataTypes[0], o = o || {}, o[s] = !0;
        for (var a, l = e[s], c = 0, u = l ? l.length : 0, f = e === st; c < u && (f || !a); c++)a = l[c](n, i, r), "string" == typeof a && (!f || o[a] ? a = t : (n.dataTypes.unshift(a), a = h(e, n, i, r, a, o)));
        return (f || !a) && !o["*"] && (a = h(e, n, i, r, "*", o)), a
    }

    function d(e) {
        return function (t, n) {
            if ("string" != typeof t && (n = t, t = "*"), F.isFunction(n))for (var i, r, s, o = t.toLowerCase().split(tt), a = 0, l = o.length; a < l; a++)i = o[a], s = /^\+/.test(i), s && (i = i.substr(1) || "*"), r = e[i] = e[i] || [], r[s ? "unshift" : "push"](n)
        }
    }

    function m(e, t, n) {
        var i = "width" === t ? e.offsetWidth : e.offsetHeight, r = "width" === t ? 1 : 0, s = 4;
        if (i > 0) {
            if ("border" !== n)for (; r < s; r += 2)n || (i -= parseFloat(F.css(e, "padding" + Re[r])) || 0), "margin" === n ? i += parseFloat(F.css(e, n + Re[r])) || 0 : i -= parseFloat(F.css(e, "border" + Re[r] + "Width")) || 0;
            return i + "px"
        }
        if (i = Oe(e, t), (i < 0 || null == i) && (i = e.style[t]), Qe.test(i))return i;
        if (i = parseFloat(i) || 0, n)for (; r < s; r += 2)i += parseFloat(F.css(e, "padding" + Re[r])) || 0, "padding" !== n && (i += parseFloat(F.css(e, "border" + Re[r] + "Width")) || 0), "margin" === n && (i += parseFloat(F.css(e, n + Re[r])) || 0);
        return i + "px"
    }

    function g(e) {
        var t = A.createElement("div");
        return Ee.appendChild(t), t.innerHTML = e.outerHTML, t.firstChild
    }

    function v(e) {
        var t = (e.nodeName || "").toLowerCase();
        "input" === t ? y(e) : "script" !== t && "undefined" != typeof e.getElementsByTagName && F.grep(e.getElementsByTagName("input"), y)
    }

    function y(e) {
        "checkbox" !== e.type && "radio" !== e.type || (e.defaultChecked = e.checked)
    }

    function b(e) {
        return "undefined" != typeof e.getElementsByTagName ? e.getElementsByTagName("*") : "undefined" != typeof e.querySelectorAll ? e.querySelectorAll("*") : []
    }

    function x(e, t) {
        var n;
        1 === t.nodeType && (t.clearAttributes && t.clearAttributes(), t.mergeAttributes && t.mergeAttributes(e), n = t.nodeName.toLowerCase(), "object" === n ? t.outerHTML = e.outerHTML : "input" !== n || "checkbox" !== e.type && "radio" !== e.type ? "option" === n ? t.selected = e.defaultSelected : "input" === n || "textarea" === n ? t.defaultValue = e.defaultValue : "script" === n && t.text !== e.text && (t.text = e.text) : (e.checked && (t.defaultChecked = t.checked = e.checked), t.value !== e.value && (t.value = e.value)), t.removeAttribute(F.expando), t.removeAttribute("_submit_attached"), t.removeAttribute("_change_attached"))
    }

    function w(e, t) {
        if (1 === t.nodeType && F.hasData(e)) {
            var n, i, r, s = F._data(e), o = F._data(t, s), a = s.events;
            if (a) {
                delete o.handle, o.events = {};
                for (n in a)for (i = 0, r = a[n].length; i < r; i++)F.event.add(t, n, a[n][i])
            }
            o.data && (o.data = F.extend({}, o.data))
        }
    }

    function T(e, t) {
        return F.nodeName(e, "table") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }

    function N(e) {
        var t = de.split("|"), n = e.createDocumentFragment();
        if (n.createElement)for (; t.length;)n.createElement(t.pop());
        return n
    }

    function k(e, t, n) {
        if (t = t || 0, F.isFunction(t))return F.grep(e, function (e, i) {
            var r = !!t.call(e, i, e);
            return r === n
        });
        if (t.nodeType)return F.grep(e, function (e, i) {
            return e === t === n
        });
        if ("string" == typeof t) {
            var i = F.grep(e, function (e) {
                return 1 === e.nodeType
            });
            if (ue.test(t))return F.filter(t, i, !n);
            t = F.filter(t, i)
        }
        return F.grep(e, function (e, i) {
            return F.inArray(e, t) >= 0 === n
        })
    }

    function C(e) {
        return !e || !e.parentNode || 11 === e.parentNode.nodeType
    }

    function q() {
        return !0
    }

    function S() {
        return !1
    }

    function E(e, t, n) {
        var i = t + "defer", r = t + "queue", s = t + "mark", o = F._data(e, i);
        o && ("queue" === n || !F._data(e, r)) && ("mark" === n || !F._data(e, s)) && setTimeout(function () {
            !F._data(e, r) && !F._data(e, s) && (F.removeData(e, i, !0), o.fire())
        }, 0)
    }

    function O(e) {
        for (var t in e)if (("data" !== t || !F.isEmptyObject(e[t])) && "toJSON" !== t)return !1;
        return !0
    }

    function j(e, n, i) {
        if (i === t && 1 === e.nodeType) {
            var r = "data-" + n.replace(I, "-$1").toLowerCase();
            if (i = e.getAttribute(r), "string" == typeof i) {
                try {
                    i = "true" === i || "false" !== i && ("null" === i ? null : F.isNumeric(i) ? +i : B.test(i) ? F.parseJSON(i) : i)
                } catch (s) {
                }
                F.data(e, n, i)
            } else i = t
        }
        return i
    }

    function D(e) {
        var t, n, i = Q[e] = {};
        for (e = e.split(/\s+/), t = 0, n = e.length; t < n; t++)i[e[t]] = !0;
        return i
    }

    var A = e.document, L = e.navigator, _ = e.location, F = function () {
        function n() {
            if (!a.isReady) {
                try {
                    A.documentElement.doScroll("left")
                } catch (e) {
                    return void setTimeout(n, 1)
                }
                a.ready()
            }
        }

        var i, r, s, o, a = function (e, t) {
            return new a.fn.init(e, t, i)
        }, l = e.jQuery, c = e.$, u = /^(?:[^#<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/, f = /\S/, p = /^\s+/, h = /\s+$/, d = /^<(\w+)\s*\/?>(?:<\/\1>)?$/, m = /^[\],:{}\s]*$/, g = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, v = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, y = /(?:^|:|,)(?:\s*\[)+/g, b = /(webkit)[ \/]([\w.]+)/, x = /(opera)(?:.*version)?[ \/]([\w.]+)/, w = /(msie) ([\w.]+)/, T = /(mozilla)(?:.*? rv:([\w.]+))?/, N = /-([a-z]|[0-9])/gi, k = /^-ms-/, C = function (e, t) {
            return (t + "").toUpperCase()
        }, q = L.userAgent, S = Object.prototype.toString, E = Object.prototype.hasOwnProperty, O = Array.prototype.push, j = Array.prototype.slice, D = String.prototype.trim, _ = Array.prototype.indexOf, F = {};
        return a.fn = a.prototype = {
            constructor: a, init: function (e, n, i) {
                var r, s, o, l;
                if (!e)return this;
                if (e.nodeType)return this.context = this[0] = e, this.length = 1, this;
                if ("body" === e && !n && A.body)return this.context = A, this[0] = A.body, this.selector = e, this.length = 1, this;
                if ("string" == typeof e) {
                    if (r = "<" !== e.charAt(0) || ">" !== e.charAt(e.length - 1) || e.length < 3 ? u.exec(e) : [null, e, null], r && (r[1] || !n)) {
                        if (r[1])return n = n instanceof a ? n[0] : n, l = n ? n.ownerDocument || n : A, o = d.exec(e), o ? a.isPlainObject(n) ? (e = [A.createElement(o[1])], a.fn.attr.call(e, n, !0)) : e = [l.createElement(o[1])] : (o = a.buildFragment([r[1]], [l]), e = (o.cacheable ? a.clone(o.fragment) : o.fragment).childNodes), a.merge(this, e);
                        if (s = A.getElementById(r[2]), s && s.parentNode) {
                            if (s.id !== r[2])return i.find(e);
                            this.length = 1, this[0] = s
                        }
                        return this.context = A, this.selector = e, this
                    }
                    return !n || n.jquery ? (n || i).find(e) : this.constructor(n).find(e)
                }
                return a.isFunction(e) ? i.ready(e) : (e.selector !== t && (this.selector = e.selector, this.context = e.context), a.makeArray(e, this))
            }, selector: "", jquery: "1.7.2", length: 0, size: function () {
                return this.length
            }, toArray: function () {
                return j.call(this, 0)
            }, get: function (e) {
                return null == e ? this.toArray() : e < 0 ? this[this.length + e] : this[e]
            }, pushStack: function (e, t, n) {
                var i = this.constructor();
                return a.isArray(e) ? O.apply(i, e) : a.merge(i, e), i.prevObject = this, i.context = this.context, "find" === t ? i.selector = this.selector + (this.selector ? " " : "") + n : t && (i.selector = this.selector + "." + t + "(" + n + ")"), i
            }, each: function (e, t) {
                return a.each(this, e, t)
            }, ready: function (e) {
                return a.bindReady(), s.add(e), this
            }, eq: function (e) {
                return e = +e, e === -1 ? this.slice(e) : this.slice(e, e + 1)
            }, first: function () {
                return this.eq(0)
            }, last: function () {
                return this.eq(-1)
            }, slice: function () {
                return this.pushStack(j.apply(this, arguments), "slice", j.call(arguments).join(","))
            }, map: function (e) {
                return this.pushStack(a.map(this, function (t, n) {
                    return e.call(t, n, t)
                }))
            }, end: function () {
                return this.prevObject || this.constructor(null)
            }, push: O, sort: [].sort, splice: [].splice
        }, a.fn.init.prototype = a.fn, a.extend = a.fn.extend = function () {
            var e, n, i, r, s, o, l = arguments[0] || {}, c = 1, u = arguments.length, f = !1;
            for ("boolean" == typeof l && (f = l, l = arguments[1] || {}, c = 2), "object" != typeof l && !a.isFunction(l) && (l = {}), u === c && (l = this, --c); c < u; c++)if (null != (e = arguments[c]))for (n in e)i = l[n], r = e[n], l !== r && (f && r && (a.isPlainObject(r) || (s = a.isArray(r))) ? (s ? (s = !1, o = i && a.isArray(i) ? i : []) : o = i && a.isPlainObject(i) ? i : {}, l[n] = a.extend(f, o, r)) : r !== t && (l[n] = r));
            return l
        }, a.extend({
            noConflict: function (t) {
                return e.$ === a && (e.$ = c), t && e.jQuery === a && (e.jQuery = l), a
            }, isReady: !1, readyWait: 1, holdReady: function (e) {
                e ? a.readyWait++ : a.ready(!0)
            }, ready: function (e) {
                if (e === !0 && !--a.readyWait || e !== !0 && !a.isReady) {
                    if (!A.body)return setTimeout(a.ready, 1);
                    if (a.isReady = !0, e !== !0 && --a.readyWait > 0)return;
                    s.fireWith(A, [a]), a.fn.trigger && a(A).trigger("ready").off("ready")
                }
            }, bindReady: function () {
                if (!s) {
                    if (s = a.Callbacks("once memory"), "complete" === A.readyState)return setTimeout(a.ready, 1);
                    if (A.addEventListener)A.addEventListener("DOMContentLoaded", o, !1), e.addEventListener("load", a.ready, !1); else if (A.attachEvent) {
                        A.attachEvent("onreadystatechange", o), e.attachEvent("onload", a.ready);
                        var t = !1;
                        try {
                            t = null == e.frameElement
                        } catch (i) {
                        }
                        A.documentElement.doScroll && t && n()
                    }
                }
            }, isFunction: function (e) {
                return "function" === a.type(e)
            }, isArray: Array.isArray || function (e) {
                return "array" === a.type(e)
            }, isWindow: function (e) {
                return null != e && e == e.window
            }, isNumeric: function (e) {
                return !isNaN(parseFloat(e)) && isFinite(e)
            }, type: function (e) {
                return null == e ? String(e) : F[S.call(e)] || "object"
            }, isPlainObject: function (e) {
                if (!e || "object" !== a.type(e) || e.nodeType || a.isWindow(e))return !1;
                try {
                    if (e.constructor && !E.call(e, "constructor") && !E.call(e.constructor.prototype, "isPrototypeOf"))return !1
                } catch (n) {
                    return !1
                }
                var i;
                for (i in e);
                return i === t || E.call(e, i)
            }, isEmptyObject: function (e) {
                for (var t in e)return !1;
                return !0
            }, error: function (e) {
                throw new Error(e)
            }, parseJSON: function (t) {
                return "string" == typeof t && t ? (t = a.trim(t), e.JSON && e.JSON.parse ? e.JSON.parse(t) : m.test(t.replace(g, "@").replace(v, "]").replace(y, "")) ? new Function("return " + t)() : void a.error("Invalid JSON: " + t)) : null
            }, parseXML: function (n) {
                if ("string" != typeof n || !n)return null;
                var i, r;
                try {
                    e.DOMParser ? (r = new DOMParser, i = r.parseFromString(n, "text/xml")) : (i = new ActiveXObject("Microsoft.XMLDOM"), i.async = "false", i.loadXML(n))
                } catch (s) {
                    i = t
                }
                return (!i || !i.documentElement || i.getElementsByTagName("parsererror").length) && a.error("Invalid XML: " + n), i
            }, noop: function () {
            }, globalEval: function (t) {
                t && f.test(t) && (e.execScript || function (t) {
                    e.eval.call(e, t)
                })(t)
            }, camelCase: function (e) {
                return e.replace(k, "ms-").replace(N, C)
            }, nodeName: function (e, t) {
                return e.nodeName && e.nodeName.toUpperCase() === t.toUpperCase()
            }, each: function (e, n, i) {
                var r, s = 0, o = e.length, l = o === t || a.isFunction(e);
                if (i)if (l) {
                    for (r in e)if (n.apply(e[r], i) === !1)break
                } else for (; s < o && n.apply(e[s++], i) !== !1;); else if (l) {
                    for (r in e)if (n.call(e[r], r, e[r]) === !1)break
                } else for (; s < o && n.call(e[s], s, e[s++]) !== !1;);
                return e
            }, trim: D ? function (e) {
                return null == e ? "" : D.call(e)
            } : function (e) {
                return null == e ? "" : (e + "").replace(p, "").replace(h, "")
            }, makeArray: function (e, t) {
                var n = t || [];
                if (null != e) {
                    var i = a.type(e);
                    null == e.length || "string" === i || "function" === i || "regexp" === i || a.isWindow(e) ? O.call(n, e) : a.merge(n, e)
                }
                return n
            }, inArray: function (e, t, n) {
                var i;
                if (t) {
                    if (_)return _.call(t, e, n);
                    for (i = t.length, n = n ? n < 0 ? Math.max(0, i + n) : n : 0; n < i; n++)if (n in t && t[n] === e)return n
                }
                return -1
            }, merge: function (e, n) {
                var i = e.length, r = 0;
                if ("number" == typeof n.length)for (var s = n.length; r < s; r++)e[i++] = n[r]; else for (; n[r] !== t;)e[i++] = n[r++];
                return e.length = i, e
            }, grep: function (e, t, n) {
                var i, r = [];
                n = !!n;
                for (var s = 0, o = e.length; s < o; s++)i = !!t(e[s], s), n !== i && r.push(e[s]);
                return r
            }, map: function (e, n, i) {
                var r, s, o = [], l = 0, c = e.length, u = e instanceof a || c !== t && "number" == typeof c && (c > 0 && e[0] && e[c - 1] || 0 === c || a.isArray(e));
                if (u)for (; l < c; l++)r = n(e[l], l, i), null != r && (o[o.length] = r); else for (s in e)r = n(e[s], s, i), null != r && (o[o.length] = r);
                return o.concat.apply([], o)
            }, guid: 1, proxy: function (e, n) {
                if ("string" == typeof n) {
                    var i = e[n];
                    n = e, e = i
                }
                if (!a.isFunction(e))return t;
                var r = j.call(arguments, 2), s = function () {
                    return e.apply(n, r.concat(j.call(arguments)))
                };
                return s.guid = e.guid = e.guid || s.guid || a.guid++, s
            }, access: function (e, n, i, r, s, o, l) {
                var c, u = null == i, f = 0, p = e.length;
                if (i && "object" == typeof i) {
                    for (f in i)a.access(e, n, f, i[f], 1, o, r);
                    s = 1
                } else if (r !== t) {
                    if (c = l === t && a.isFunction(r), u && (c ? (c = n, n = function (e, t, n) {
                            return c.call(a(e), n)
                        }) : (n.call(e, r), n = null)), n)for (; f < p; f++)n(e[f], i, c ? r.call(e[f], f, n(e[f], i)) : r, l);
                    s = 1
                }
                return s ? e : u ? n.call(e) : p ? n(e[0], i) : o
            }, now: function () {
                return (new Date).getTime()
            }, uaMatch: function (e) {
                e = e.toLowerCase();
                var t = b.exec(e) || x.exec(e) || w.exec(e) || e.indexOf("compatible") < 0 && T.exec(e) || [];
                return {browser: t[1] || "", version: t[2] || "0"}
            }, sub: function () {
                function e(t, n) {
                    return new e.fn.init(t, n)
                }

                a.extend(!0, e, this), e.superclass = this, e.fn = e.prototype = this(), e.fn.constructor = e, e.sub = this.sub, e.fn.init = function (n, i) {
                    return i && i instanceof a && !(i instanceof e) && (i = e(i)), a.fn.init.call(this, n, i, t)
                }, e.fn.init.prototype = e.fn;
                var t = e(A);
                return e
            }, browser: {}
        }), a.each("Boolean Number String Function Array Date RegExp Object".split(" "), function (e, t) {
            F["[object " + t + "]"] = t.toLowerCase()
        }), r = a.uaMatch(q), r.browser && (a.browser[r.browser] = !0, a.browser.version = r.version), a.browser.webkit && (a.browser.safari = !0), f.test(" ") && (p = /^[\s\xA0]+/, h = /[\s\xA0]+$/), i = a(A), A.addEventListener ? o = function () {
            A.removeEventListener("DOMContentLoaded", o, !1), a.ready()
        } : A.attachEvent && (o = function () {
            "complete" === A.readyState && (A.detachEvent("onreadystatechange", o), a.ready())
        }), a
    }(), Q = {};
    F.Callbacks = function (e) {
        e = e ? Q[e] || D(e) : {};
        var n, i, r, s, o, a, l = [], c = [], u = function (t) {
            var n, i, r, s;
            for (n = 0, i = t.length; n < i; n++)r = t[n], s = F.type(r), "array" === s ? u(r) : "function" === s && (!e.unique || !p.has(r)) && l.push(r)
        }, f = function (t, u) {
            for (u = u || [], n = !e.memory || [t, u], i = !0, r = !0, a = s || 0, s = 0, o = l.length; l && a < o; a++)if (l[a].apply(t, u) === !1 && e.stopOnFalse) {
                n = !0;
                break
            }
            r = !1, l && (e.once ? n === !0 ? p.disable() : l = [] : c && c.length && (n = c.shift(), p.fireWith(n[0], n[1])))
        }, p = {
            add: function () {
                if (l) {
                    var e = l.length;
                    u(arguments), r ? o = l.length : n && n !== !0 && (s = e, f(n[0], n[1]))
                }
                return this
            }, remove: function () {
                if (l)for (var t = arguments, n = 0, i = t.length; n < i; n++)for (var s = 0; s < l.length && (t[n] !== l[s] || (r && s <= o && (o--, s <= a && a--), l.splice(s--, 1), !e.unique)); s++);
                return this
            }, has: function (e) {
                if (l)for (var t = 0, n = l.length; t < n; t++)if (e === l[t])return !0;
                return !1
            }, empty: function () {
                return l = [], this
            }, disable: function () {
                return l = c = n = t, this
            }, disabled: function () {
                return !l
            }, lock: function () {
                return c = t, (!n || n === !0) && p.disable(), this
            }, locked: function () {
                return !c
            }, fireWith: function (t, i) {
                return c && (r ? e.once || c.push([t, i]) : (!e.once || !n) && f(t, i)), this
            }, fire: function () {
                return p.fireWith(this, arguments), this
            }, fired: function () {
                return !!i
            }
        };
        return p
    };
    var M = [].slice;
    F.extend({
        Deferred: function (e) {
            var t, n = F.Callbacks("once memory"), i = F.Callbacks("once memory"), r = F.Callbacks("memory"), s = "pending", o = {
                resolve: n,
                reject: i,
                notify: r
            }, a = {
                done: n.add, fail: i.add, progress: r.add, state: function () {
                    return s
                }, isResolved: n.fired, isRejected: i.fired, then: function (e, t, n) {
                    return l.done(e).fail(t).progress(n), this
                }, always: function () {
                    return l.done.apply(l, arguments).fail.apply(l, arguments), this
                }, pipe: function (e, t, n) {
                    return F.Deferred(function (i) {
                        F.each({done: [e, "resolve"], fail: [t, "reject"], progress: [n, "notify"]}, function (e, t) {
                            var n, r = t[0], s = t[1];
                            F.isFunction(r) ? l[e](function () {
                                n = r.apply(this, arguments), n && F.isFunction(n.promise) ? n.promise().then(i.resolve, i.reject, i.notify) : i[s + "With"](this === l ? i : this, [n])
                            }) : l[e](i[s])
                        })
                    }).promise()
                }, promise: function (e) {
                    if (null == e)e = a; else for (var t in a)e[t] = a[t];
                    return e
                }
            }, l = a.promise({});
            for (t in o)l[t] = o[t].fire, l[t + "With"] = o[t].fireWith;
            return l.done(function () {
                s = "resolved"
            }, i.disable, r.lock).fail(function () {
                s = "rejected"
            }, n.disable, r.lock), e && e.call(l, l), l
        }, when: function (e) {
            function t(e) {
                return function (t) {
                    o[e] = arguments.length > 1 ? M.call(arguments, 0) : t, l.notifyWith(c, o)
                }
            }

            function n(e) {
                return function (t) {
                    i[e] = arguments.length > 1 ? M.call(arguments, 0) : t, --a || l.resolveWith(l, i)
                }
            }

            var i = M.call(arguments, 0), r = 0, s = i.length, o = Array(s), a = s, l = s <= 1 && e && F.isFunction(e.promise) ? e : F.Deferred(), c = l.promise();
            if (s > 1) {
                for (; r < s; r++)i[r] && i[r].promise && F.isFunction(i[r].promise) ? i[r].promise().then(n(r), l.reject, t(r)) : --a;
                a || l.resolveWith(l, i)
            } else l !== e && l.resolveWith(l, s ? [e] : []);
            return c
        }
    }), F.support = function () {
        var t, n, i, r, s, o, a, l, c, u, f, p = A.createElement("div");
        A.documentElement;
        if (p.setAttribute("className", "t"), p.innerHTML = "   <link/><table></table><a href='/a' style='top:1px;float:left;opacity:.55;'>a</a><input type='checkbox'/>", n = p.getElementsByTagName("*"), i = p.getElementsByTagName("a")[0], !n || !n.length || !i)return {};
        r = A.createElement("select"), s = r.appendChild(A.createElement("option")), o = p.getElementsByTagName("input")[0], t = {
            leadingWhitespace: 3 === p.firstChild.nodeType,
            tbody: !p.getElementsByTagName("tbody").length,
            htmlSerialize: !!p.getElementsByTagName("link").length,
            style: /top/.test(i.getAttribute("style")),
            hrefNormalized: "/a" === i.getAttribute("href"),
            opacity: /^0.55/.test(i.style.opacity),
            cssFloat: !!i.style.cssFloat,
            checkOn: "on" === o.value,
            optSelected: s.selected,
            getSetAttribute: "t" !== p.className,
            enctype: !!A.createElement("form").enctype,
            html5Clone: "<:nav></:nav>" !== A.createElement("nav").cloneNode(!0).outerHTML,
            submitBubbles: !0,
            changeBubbles: !0,
            focusinBubbles: !1,
            deleteExpando: !0,
            noCloneEvent: !0,
            inlineBlockNeedsLayout: !1,
            shrinkWrapBlocks: !1,
            reliableMarginRight: !0,
            pixelMargin: !0
        }, F.boxModel = t.boxModel = "CSS1Compat" === A.compatMode, o.checked = !0, t.noCloneChecked = o.cloneNode(!0).checked, r.disabled = !0, t.optDisabled = !s.disabled;
        try {
            delete p.test
        } catch (h) {
            t.deleteExpando = !1
        }
        if (!p.addEventListener && p.attachEvent && p.fireEvent && (p.attachEvent("onclick", function () {
                t.noCloneEvent = !1
            }), p.cloneNode(!0).fireEvent("onclick")), o = A.createElement("input"), o.value = "t", o.setAttribute("type", "radio"), t.radioValue = "t" === o.value, o.setAttribute("checked", "checked"), o.setAttribute("name", "t"), p.appendChild(o), a = A.createDocumentFragment(), a.appendChild(p.lastChild), t.checkClone = a.cloneNode(!0).cloneNode(!0).lastChild.checked, t.appendChecked = o.checked, a.removeChild(o), a.appendChild(p), p.attachEvent)for (u in{
            submit: 1,
            change: 1,
            focusin: 1
        })c = "on" + u, f = c in p, f || (p.setAttribute(c, "return;"), f = "function" == typeof p[c]), t[u + "Bubbles"] = f;
        return a.removeChild(p), a = r = s = p = o = null, F(function () {
            var n, i, r, s, o, a, c, u, h, d, m, g, v = A.getElementsByTagName("body")[0];
            !v || (c = 1, g = "padding:0;margin:0;border:", d = "position:absolute;top:0;left:0;width:1px;height:1px;", m = g + "0;visibility:hidden;", u = "style='" + d + g + "5px solid #000;", h = "<div " + u + "display:block;'><div style='" + g + "0;display:block;overflow:hidden;'></div></div><table " + u + "' cellpadding='0' cellspacing='0'><tr><td></td></tr></table>", n = A.createElement("div"), n.style.cssText = m + "width:0;height:0;position:static;top:0;margin-top:" + c + "px", v.insertBefore(n, v.firstChild), p = A.createElement("div"), n.appendChild(p), p.innerHTML = "<table><tr><td style='" + g + "0;display:none'></td><td>t</td></tr></table>", l = p.getElementsByTagName("td"), f = 0 === l[0].offsetHeight, l[0].style.display = "", l[1].style.display = "none", t.reliableHiddenOffsets = f && 0 === l[0].offsetHeight, e.getComputedStyle && (p.innerHTML = "", a = A.createElement("div"), a.style.width = "0", a.style.marginRight = "0", p.style.width = "2px", p.appendChild(a), t.reliableMarginRight = 0 === (parseInt((e.getComputedStyle(a, null) || {marginRight: 0}).marginRight, 10) || 0)), "undefined" != typeof p.style.zoom && (p.innerHTML = "", p.style.width = p.style.padding = "1px", p.style.border = 0, p.style.overflow = "hidden", p.style.display = "inline", p.style.zoom = 1, t.inlineBlockNeedsLayout = 3 === p.offsetWidth, p.style.display = "block", p.style.overflow = "visible", p.innerHTML = "<div style='width:5px;'></div>", t.shrinkWrapBlocks = 3 !== p.offsetWidth), p.style.cssText = d + m, p.innerHTML = h, i = p.firstChild, r = i.firstChild, s = i.nextSibling.firstChild.firstChild, o = {
                doesNotAddBorder: 5 !== r.offsetTop,
                doesAddBorderForTableAndCells: 5 === s.offsetTop
            }, r.style.position = "fixed", r.style.top = "20px", o.fixedPosition = 20 === r.offsetTop || 15 === r.offsetTop, r.style.position = r.style.top = "", i.style.overflow = "hidden", i.style.position = "relative", o.subtractsBorderForOverflowNotVisible = r.offsetTop === -5, o.doesNotIncludeMarginInBodyOffset = v.offsetTop !== c, e.getComputedStyle && (p.style.marginTop = "1%", t.pixelMargin = "1%" !== (e.getComputedStyle(p, null) || {marginTop: 0}).marginTop), "undefined" != typeof n.style.zoom && (n.style.zoom = 1), v.removeChild(n), a = p = n = null, F.extend(t, o))
        }), t
    }();
    var B = /^(?:\{.*\}|\[.*\])$/, I = /([A-Z])/g;
    F.extend({
        cache: {},
        uuid: 0,
        expando: "jQuery" + (F.fn.jquery + Math.random()).replace(/\D/g, ""),
        noData: {embed: !0, object: "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000", applet: !0},
        hasData: function (e) {
            return e = e.nodeType ? F.cache[e[F.expando]] : e[F.expando], !!e && !O(e)
        },
        data: function (e, n, i, r) {
            if (F.acceptData(e)) {
                var s, o, a, l = F.expando, c = "string" == typeof n, u = e.nodeType, f = u ? F.cache : e, p = u ? e[l] : e[l] && l, h = "events" === n;
                if ((!p || !f[p] || !h && !r && !f[p].data) && c && i === t)return;
                return p || (u ? e[l] = p = ++F.uuid : p = l), f[p] || (f[p] = {}, u || (f[p].toJSON = F.noop)), "object" != typeof n && "function" != typeof n || (r ? f[p] = F.extend(f[p], n) : f[p].data = F.extend(f[p].data, n)), s = o = f[p], r || (o.data || (o.data = {}), o = o.data), i !== t && (o[F.camelCase(n)] = i), h && !o[n] ? s.events : (c ? (a = o[n], null == a && (a = o[F.camelCase(n)])) : a = o, a)
            }
        },
        removeData: function (e, t, n) {
            if (F.acceptData(e)) {
                var i, r, s, o = F.expando, a = e.nodeType, l = a ? F.cache : e, c = a ? e[o] : o;
                if (!l[c])return;
                if (t && (i = n ? l[c] : l[c].data)) {
                    F.isArray(t) || (t in i ? t = [t] : (t = F.camelCase(t), t = t in i ? [t] : t.split(" ")));
                    for (r = 0, s = t.length; r < s; r++)delete i[t[r]];
                    if (!(n ? O : F.isEmptyObject)(i))return
                }
                if (!n && (delete l[c].data, !O(l[c])))return;
                F.support.deleteExpando || !l.setInterval ? delete l[c] : l[c] = null, a && (F.support.deleteExpando ? delete e[o] : e.removeAttribute ? e.removeAttribute(o) : e[o] = null)
            }
        },
        _data: function (e, t, n) {
            return F.data(e, t, n, !0)
        },
        acceptData: function (e) {
            if (e.nodeName) {
                var t = F.noData[e.nodeName.toLowerCase()];
                if (t)return t !== !0 && e.getAttribute("classid") === t
            }
            return !0
        }
    }), F.fn.extend({
        data: function (e, n) {
            var i, r, s, o, a, l = this[0], c = 0, u = null;
            if (e === t) {
                if (this.length && (u = F.data(l), 1 === l.nodeType && !F._data(l, "parsedAttrs"))) {
                    for (s = l.attributes, a = s.length; c < a; c++)o = s[c].name, 0 === o.indexOf("data-") && (o = F.camelCase(o.substring(5)), j(l, o, u[o]));
                    F._data(l, "parsedAttrs", !0)
                }
                return u
            }
            return "object" == typeof e ? this.each(function () {
                F.data(this, e)
            }) : (i = e.split(".", 2), i[1] = i[1] ? "." + i[1] : "", r = i[1] + "!", F.access(this, function (n) {
                return n === t ? (u = this.triggerHandler("getData" + r, [i[0]]), u === t && l && (u = F.data(l, e), u = j(l, e, u)), u === t && i[1] ? this.data(i[0]) : u) : (i[1] = n, void this.each(function () {
                    var t = F(this);
                    t.triggerHandler("setData" + r, i), F.data(this, e, n), t.triggerHandler("changeData" + r, i)
                }))
            }, null, n, arguments.length > 1, null, !1))
        }, removeData: function (e) {
            return this.each(function () {
                F.removeData(this, e)
            })
        }
    }), F.extend({
        _mark: function (e, t) {
            e && (t = (t || "fx") + "mark", F._data(e, t, (F._data(e, t) || 0) + 1))
        }, _unmark: function (e, t, n) {
            if (e !== !0 && (n = t, t = e, e = !1), t) {
                n = n || "fx";
                var i = n + "mark", r = e ? 0 : (F._data(t, i) || 1) - 1;
                r ? F._data(t, i, r) : (F.removeData(t, i, !0), E(t, n, "mark"))
            }
        }, queue: function (e, t, n) {
            var i;
            if (e)return t = (t || "fx") + "queue", i = F._data(e, t), n && (!i || F.isArray(n) ? i = F._data(e, t, F.makeArray(n)) : i.push(n)), i || []
        }, dequeue: function (e, t) {
            t = t || "fx";
            var n = F.queue(e, t), i = n.shift(), r = {};
            "inprogress" === i && (i = n.shift()), i && ("fx" === t && n.unshift("inprogress"), F._data(e, t + ".run", r), i.call(e, function () {
                F.dequeue(e, t)
            }, r)), n.length || (F.removeData(e, t + "queue " + t + ".run", !0), E(e, t, "queue"))
        }
    }), F.fn.extend({
        queue: function (e, n) {
            var i = 2;
            return "string" != typeof e && (n = e, e = "fx", i--), arguments.length < i ? F.queue(this[0], e) : n === t ? this : this.each(function () {
                var t = F.queue(this, e, n);
                "fx" === e && "inprogress" !== t[0] && F.dequeue(this, e)
            })
        }, dequeue: function (e) {
            return this.each(function () {
                F.dequeue(this, e)
            })
        }, delay: function (e, t) {
            return e = F.fx ? F.fx.speeds[e] || e : e, t = t || "fx", this.queue(t, function (t, n) {
                var i = setTimeout(t, e);
                n.stop = function () {
                    clearTimeout(i)
                }
            })
        }, clearQueue: function (e) {
            return this.queue(e || "fx", [])
        }, promise: function (e, n) {
            function i() {
                --l || s.resolveWith(o, [o])
            }

            "string" != typeof e && (n = e, e = t), e = e || "fx";
            for (var r, s = F.Deferred(), o = this, a = o.length, l = 1, c = e + "defer", u = e + "queue", f = e + "mark"; a--;)(r = F.data(o[a], c, t, !0) || (F.data(o[a], u, t, !0) || F.data(o[a], f, t, !0)) && F.data(o[a], c, F.Callbacks("once memory"), !0)) && (l++, r.add(i));
            return i(), s.promise(n)
        }
    });
    var R, H, P, $ = /[\n\t\r]/g, z = /\s+/, W = /\r/g, X = /^(?:button|input)$/i, U = /^(?:button|input|object|select|textarea)$/i, K = /^a(?:rea)?$/i, V = /^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i, G = F.support.getSetAttribute;
    F.fn.extend({
        attr: function (e, t) {
            return F.access(this, F.attr, e, t, arguments.length > 1)
        }, removeAttr: function (e) {
            return this.each(function () {
                F.removeAttr(this, e)
            })
        }, prop: function (e, t) {
            return F.access(this, F.prop, e, t, arguments.length > 1)
        }, removeProp: function (e) {
            return e = F.propFix[e] || e, this.each(function () {
                try {
                    this[e] = t, delete this[e]
                } catch (n) {
                }
            })
        }, addClass: function (e) {
            var t, n, i, r, s, o, a;
            if (F.isFunction(e))return this.each(function (t) {
                F(this).addClass(e.call(this, t, this.className))
            });
            if (e && "string" == typeof e)for (t = e.split(z), n = 0, i = this.length; n < i; n++)if (r = this[n], 1 === r.nodeType)if (r.className || 1 !== t.length) {
                for (s = " " + r.className + " ", o = 0, a = t.length; o < a; o++)~s.indexOf(" " + t[o] + " ") || (s += t[o] + " ");
                r.className = F.trim(s)
            } else r.className = e;
            return this
        }, removeClass: function (e) {
            var n, i, r, s, o, a, l;
            if (F.isFunction(e))return this.each(function (t) {
                F(this).removeClass(e.call(this, t, this.className))
            });
            if (e && "string" == typeof e || e === t)for (n = (e || "").split(z), i = 0, r = this.length; i < r; i++)if (s = this[i], 1 === s.nodeType && s.className)if (e) {
                for (o = (" " + s.className + " ").replace($, " "), a = 0, l = n.length; a < l; a++)o = o.replace(" " + n[a] + " ", " ");
                s.className = F.trim(o)
            } else s.className = "";
            return this
        }, toggleClass: function (e, t) {
            var n = typeof e, i = "boolean" == typeof t;
            return F.isFunction(e) ? this.each(function (n) {
                F(this).toggleClass(e.call(this, n, this.className, t), t)
            }) : this.each(function () {
                if ("string" === n)for (var r, s = 0, o = F(this), a = t, l = e.split(z); r = l[s++];)a = i ? a : !o.hasClass(r), o[a ? "addClass" : "removeClass"](r); else"undefined" !== n && "boolean" !== n || (this.className && F._data(this, "__className__", this.className), this.className = this.className || e === !1 ? "" : F._data(this, "__className__") || "")
            })
        }, hasClass: function (e) {
            for (var t = " " + e + " ", n = 0, i = this.length; n < i; n++)if (1 === this[n].nodeType && (" " + this[n].className + " ").replace($, " ").indexOf(t) > -1)return !0;
            return !1
        }, val: function (e) {
            var n, i, r, s = this[0];
            return arguments.length ? (r = F.isFunction(e), this.each(function (i) {
                var s, o = F(this);
                1 === this.nodeType && (s = r ? e.call(this, i, o.val()) : e, null == s ? s = "" : "number" == typeof s ? s += "" : F.isArray(s) && (s = F.map(s, function (e) {
                    return null == e ? "" : e + ""
                })), n = F.valHooks[this.type] || F.valHooks[this.nodeName.toLowerCase()], n && "set" in n && n.set(this, s, "value") !== t || (this.value = s))
            })) : s ? (n = F.valHooks[s.type] || F.valHooks[s.nodeName.toLowerCase()], n && "get" in n && (i = n.get(s, "value")) !== t ? i : (i = s.value, "string" == typeof i ? i.replace(W, "") : null == i ? "" : i)) : void 0
        }
    }), F.extend({
        valHooks: {
            option: {
                get: function (e) {
                    var t = e.attributes.value;
                    return !t || t.specified ? e.value : e.text
                }
            }, select: {
                get: function (e) {
                    var t, n, i, r, s = e.selectedIndex, o = [], a = e.options, l = "select-one" === e.type;
                    if (s < 0)return null;
                    for (n = l ? s : 0, i = l ? s + 1 : a.length; n < i; n++)if (r = a[n], r.selected && (F.support.optDisabled ? !r.disabled : null === r.getAttribute("disabled")) && (!r.parentNode.disabled || !F.nodeName(r.parentNode, "optgroup"))) {
                        if (t = F(r).val(), l)return t;
                        o.push(t)
                    }
                    return l && !o.length && a.length ? F(a[s]).val() : o
                }, set: function (e, t) {
                    var n = F.makeArray(t);
                    return F(e).find("option").each(function () {
                        this.selected = F.inArray(F(this).val(), n) >= 0
                    }), n.length || (e.selectedIndex = -1), n
                }
            }
        },
        attrFn: {val: !0, css: !0, html: !0, text: !0, data: !0, width: !0, height: !0, offset: !0},
        attr: function (e, n, i, r) {
            var s, o, a, l = e.nodeType;
            if (e && 3 !== l && 8 !== l && 2 !== l)return r && n in F.attrFn ? F(e)[n](i) : "undefined" == typeof e.getAttribute ? F.prop(e, n, i) : (a = 1 !== l || !F.isXMLDoc(e), a && (n = n.toLowerCase(), o = F.attrHooks[n] || (V.test(n) ? H : R)), i !== t ? null === i ? void F.removeAttr(e, n) : o && "set" in o && a && (s = o.set(e, i, n)) !== t ? s : (e.setAttribute(n, "" + i), i) : o && "get" in o && a && null !== (s = o.get(e, n)) ? s : (s = e.getAttribute(n), null === s ? t : s))
        },
        removeAttr: function (e, t) {
            var n, i, r, s, o, a = 0;
            if (t && 1 === e.nodeType)for (i = t.toLowerCase().split(z), s = i.length; a < s; a++)r = i[a], r && (n = F.propFix[r] || r, o = V.test(r), o || F.attr(e, r, ""), e.removeAttribute(G ? r : n), o && n in e && (e[n] = !1))
        },
        attrHooks: {
            type: {
                set: function (e, t) {
                    if (X.test(e.nodeName) && e.parentNode)F.error("type property can't be changed"); else if (!F.support.radioValue && "radio" === t && F.nodeName(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t), n && (e.value = n), t
                    }
                }
            }, value: {
                get: function (e, t) {
                    return R && F.nodeName(e, "button") ? R.get(e, t) : t in e ? e.value : null
                }, set: function (e, t, n) {
                    return R && F.nodeName(e, "button") ? R.set(e, t, n) : void(e.value = t)
                }
            }
        },
        propFix: {
            tabindex: "tabIndex",
            readonly: "readOnly",
            "for": "htmlFor",
            "class": "className",
            maxlength: "maxLength",
            cellspacing: "cellSpacing",
            cellpadding: "cellPadding",
            rowspan: "rowSpan",
            colspan: "colSpan",
            usemap: "useMap",
            frameborder: "frameBorder",
            contenteditable: "contentEditable"
        },
        prop: function (e, n, i) {
            var r, s, o, a = e.nodeType;
            if (e && 3 !== a && 8 !== a && 2 !== a)return o = 1 !== a || !F.isXMLDoc(e), o && (n = F.propFix[n] || n, s = F.propHooks[n]), i !== t ? s && "set" in s && (r = s.set(e, i, n)) !== t ? r : e[n] = i : s && "get" in s && null !== (r = s.get(e, n)) ? r : e[n]
        },
        propHooks: {
            tabIndex: {
                get: function (e) {
                    var n = e.getAttributeNode("tabindex");
                    return n && n.specified ? parseInt(n.value, 10) : U.test(e.nodeName) || K.test(e.nodeName) && e.href ? 0 : t
                }
            }
        }
    }), F.attrHooks.tabindex = F.propHooks.tabIndex, H = {
        get: function (e, n) {
            var i, r = F.prop(e, n);
            return r === !0 || "boolean" != typeof r && (i = e.getAttributeNode(n)) && i.nodeValue !== !1 ? n.toLowerCase() : t
        }, set: function (e, t, n) {
            var i;
            return t === !1 ? F.removeAttr(e, n) : (i = F.propFix[n] || n, i in e && (e[i] = !0), e.setAttribute(n, n.toLowerCase())), n
        }
    }, G || (P = {name: !0, id: !0, coords: !0}, R = F.valHooks.button = {
        get: function (e, n) {
            var i;
            return i = e.getAttributeNode(n), i && (P[n] ? "" !== i.nodeValue : i.specified) ? i.nodeValue : t
        }, set: function (e, t, n) {
            var i = e.getAttributeNode(n);
            return i || (i = A.createAttribute(n), e.setAttributeNode(i)), i.nodeValue = t + ""
        }
    }, F.attrHooks.tabindex.set = R.set, F.each(["width", "height"], function (e, t) {
        F.attrHooks[t] = F.extend(F.attrHooks[t], {
            set: function (e, n) {
                if ("" === n)return e.setAttribute(t, "auto"), n
            }
        })
    }), F.attrHooks.contenteditable = {
        get: R.get, set: function (e, t, n) {
            "" === t && (t = "false"), R.set(e, t, n)
        }
    }), F.support.hrefNormalized || F.each(["href", "src", "width", "height"], function (e, n) {
        F.attrHooks[n] = F.extend(F.attrHooks[n], {
            get: function (e) {
                var i = e.getAttribute(n, 2);
                return null === i ? t : i
            }
        })
    }), F.support.style || (F.attrHooks.style = {
        get: function (e) {
            return e.style.cssText.toLowerCase() || t
        }, set: function (e, t) {
            return e.style.cssText = "" + t
        }
    }), F.support.optSelected || (F.propHooks.selected = F.extend(F.propHooks.selected, {
        get: function (e) {
            var t = e.parentNode;
            return t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex), null
        }
    })), F.support.enctype || (F.propFix.enctype = "encoding"), F.support.checkOn || F.each(["radio", "checkbox"], function () {
        F.valHooks[this] = {
            get: function (e) {
                return null === e.getAttribute("value") ? "on" : e.value
            }
        }
    }), F.each(["radio", "checkbox"], function () {
        F.valHooks[this] = F.extend(F.valHooks[this], {
            set: function (e, t) {
                if (F.isArray(t))return e.checked = F.inArray(F(e).val(), t) >= 0
            }
        })
    });
    var Y = /^(?:textarea|input|select)$/i, J = /^([^\.]*)?(?:\.(.+))?$/, Z = /(?:^|\s)hover(\.\S+)?\b/, ee = /^key/, te = /^(?:mouse|contextmenu)|click/, ne = /^(?:focusinfocus|focusoutblur)$/, ie = /^(\w*)(?:#([\w\-]+))?(?:\.([\w\-]+))?$/, re = function (e) {
        var t = ie.exec(e);
        return t && (t[1] = (t[1] || "").toLowerCase(), t[3] = t[3] && new RegExp("(?:^|\\s)" + t[3] + "(?:\\s|$)")), t
    }, se = function (e, t) {
        var n = e.attributes || {};
        return (!t[1] || e.nodeName.toLowerCase() === t[1]) && (!t[2] || (n.id || {}).value === t[2]) && (!t[3] || t[3].test((n["class"] || {}).value))
    }, oe = function (e) {
        return F.event.special.hover ? e : e.replace(Z, "mouseenter$1 mouseleave$1")
    };
    F.event = {
        add: function (e, n, i, r, s) {
            var o, a, l, c, u, f, p, h, d, m, g;
            if (3 !== e.nodeType && 8 !== e.nodeType && n && i && (o = F._data(e))) {
                for (i.handler && (d = i, i = d.handler, s = d.selector), i.guid || (i.guid = F.guid++), l = o.events, l || (o.events = l = {}), a = o.handle, a || (o.handle = a = function (e) {
                    return "undefined" == typeof F || e && F.event.triggered === e.type ? t : F.event.dispatch.apply(a.elem, arguments)
                }, a.elem = e), n = F.trim(oe(n)).split(" "), c = 0; c < n.length; c++)u = J.exec(n[c]) || [], f = u[1], p = (u[2] || "").split(".").sort(), g = F.event.special[f] || {}, f = (s ? g.delegateType : g.bindType) || f, g = F.event.special[f] || {}, h = F.extend({
                    type: f,
                    origType: u[1],
                    data: r,
                    handler: i,
                    guid: i.guid,
                    selector: s,
                    quick: s && re(s),
                    namespace: p.join(".")
                }, d), m = l[f], m || (m = l[f] = [], m.delegateCount = 0, g.setup && g.setup.call(e, r, p, a) !== !1 || (e.addEventListener ? e.addEventListener(f, a, !1) : e.attachEvent && e.attachEvent("on" + f, a))), g.add && (g.add.call(e, h), h.handler.guid || (h.handler.guid = i.guid)), s ? m.splice(m.delegateCount++, 0, h) : m.push(h), F.event.global[f] = !0;
                e = null
            }
        },
        global: {},
        remove: function (e, t, n, i, r) {
            var s, o, a, l, c, u, f, p, h, d, m, g, v = F.hasData(e) && F._data(e);
            if (v && (p = v.events)) {
                for (t = F.trim(oe(t || "")).split(" "), s = 0; s < t.length; s++)if (o = J.exec(t[s]) || [], a = l = o[1], c = o[2], a) {
                    for (h = F.event.special[a] || {}, a = (i ? h.delegateType : h.bindType) || a, m = p[a] || [], u = m.length, c = c ? new RegExp("(^|\\.)" + c.split(".").sort().join("\\.(?:.*\\.)?") + "(\\.|$)") : null, f = 0; f < m.length; f++)g = m[f], (r || l === g.origType) && (!n || n.guid === g.guid) && (!c || c.test(g.namespace)) && (!i || i === g.selector || "**" === i && g.selector) && (m.splice(f--, 1), g.selector && m.delegateCount--, h.remove && h.remove.call(e, g));
                    0 === m.length && u !== m.length && ((!h.teardown || h.teardown.call(e, c) === !1) && F.removeEvent(e, a, v.handle), delete p[a])
                } else for (a in p)F.event.remove(e, a + t[s], n, i, !0);
                F.isEmptyObject(p) && (d = v.handle, d && (d.elem = null), F.removeData(e, ["events", "handle"], !0))
            }
        },
        customEvent: {getData: !0, setData: !0, changeData: !0},
        trigger: function (n, i, r, s) {
            if (!r || 3 !== r.nodeType && 8 !== r.nodeType) {
                var o, a, l, c, u, f, p, h, d, m, g = n.type || n, v = [];
                if (ne.test(g + F.event.triggered))return;
                if (g.indexOf("!") >= 0 && (g = g.slice(0, -1), a = !0), g.indexOf(".") >= 0 && (v = g.split("."), g = v.shift(), v.sort()), (!r || F.event.customEvent[g]) && !F.event.global[g])return;
                if (n = "object" == typeof n ? n[F.expando] ? n : new F.Event(g, n) : new F.Event(g), n.type = g, n.isTrigger = !0, n.exclusive = a, n.namespace = v.join("."), n.namespace_re = n.namespace ? new RegExp("(^|\\.)" + v.join("\\.(?:.*\\.)?") + "(\\.|$)") : null, f = g.indexOf(":") < 0 ? "on" + g : "", !r) {
                    o = F.cache;
                    for (l in o)o[l].events && o[l].events[g] && F.event.trigger(n, i, o[l].handle.elem, !0);
                    return
                }
                if (n.result = t, n.target || (n.target = r), i = null != i ? F.makeArray(i) : [], i.unshift(n), p = F.event.special[g] || {}, p.trigger && p.trigger.apply(r, i) === !1)return;
                if (d = [[r, p.bindType || g]], !s && !p.noBubble && !F.isWindow(r)) {
                    for (m = p.delegateType || g, c = ne.test(m + g) ? r : r.parentNode, u = null; c; c = c.parentNode)d.push([c, m]), u = c;
                    u && u === r.ownerDocument && d.push([u.defaultView || u.parentWindow || e, m])
                }
                for (l = 0; l < d.length && !n.isPropagationStopped(); l++)c = d[l][0], n.type = d[l][1], h = (F._data(c, "events") || {})[n.type] && F._data(c, "handle"), h && h.apply(c, i), h = f && c[f], h && F.acceptData(c) && h.apply(c, i) === !1 && n.preventDefault();
                return n.type = g, !s && !n.isDefaultPrevented() && (!p._default || p._default.apply(r.ownerDocument, i) === !1) && ("click" !== g || !F.nodeName(r, "a")) && F.acceptData(r) && f && r[g] && ("focus" !== g && "blur" !== g || 0 !== n.target.offsetWidth) && !F.isWindow(r) && (u = r[f], u && (r[f] = null), F.event.triggered = g, r[g](), F.event.triggered = t, u && (r[f] = u)), n.result
            }
        },
        dispatch: function (n) {
            n = F.event.fix(n || e.event);
            var i, r, s, o, a, l, c, u, f, p, h = (F._data(this, "events") || {})[n.type] || [], d = h.delegateCount, m = [].slice.call(arguments, 0), g = !n.exclusive && !n.namespace, v = F.event.special[n.type] || {}, y = [];
            if (m[0] = n, n.delegateTarget = this, !v.preDispatch || v.preDispatch.call(this, n) !== !1) {
                if (d && (!n.button || "click" !== n.type))for (o = F(this), o.context = this.ownerDocument || this, s = n.target; s != this; s = s.parentNode || this)if (s.disabled !== !0) {
                    for (l = {}, u = [], o[0] = s, i = 0; i < d; i++)f = h[i], p = f.selector, l[p] === t && (l[p] = f.quick ? se(s, f.quick) : o.is(p)), l[p] && u.push(f);
                    u.length && y.push({elem: s, matches: u})
                }
                for (h.length > d && y.push({
                    elem: this,
                    matches: h.slice(d)
                }), i = 0; i < y.length && !n.isPropagationStopped(); i++)for (c = y[i], n.currentTarget = c.elem, r = 0; r < c.matches.length && !n.isImmediatePropagationStopped(); r++)f = c.matches[r], (g || !n.namespace && !f.namespace || n.namespace_re && n.namespace_re.test(f.namespace)) && (n.data = f.data, n.handleObj = f, a = ((F.event.special[f.origType] || {}).handle || f.handler).apply(c.elem, m), a !== t && (n.result = a, a === !1 && (n.preventDefault(), n.stopPropagation())));
                return v.postDispatch && v.postDispatch.call(this, n), n.result
            }
        },
        props: "attrChange attrName relatedNode srcElement altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "), filter: function (e, t) {
                return null == e.which && (e.which = null != t.charCode ? t.charCode : t.keyCode), e
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function (e, n) {
                var i, r, s, o = n.button, a = n.fromElement;
                return null == e.pageX && null != n.clientX && (i = e.target.ownerDocument || A, r = i.documentElement, s = i.body, e.pageX = n.clientX + (r && r.scrollLeft || s && s.scrollLeft || 0) - (r && r.clientLeft || s && s.clientLeft || 0), e.pageY = n.clientY + (r && r.scrollTop || s && s.scrollTop || 0) - (r && r.clientTop || s && s.clientTop || 0)), !e.relatedTarget && a && (e.relatedTarget = a === e.target ? n.toElement : a), !e.which && o !== t && (e.which = 1 & o ? 1 : 2 & o ? 3 : 4 & o ? 2 : 0), e
            }
        },
        fix: function (e) {
            if (e[F.expando])return e;
            var n, i, r = e, s = F.event.fixHooks[e.type] || {}, o = s.props ? this.props.concat(s.props) : this.props;
            for (e = F.Event(r), n = o.length; n;)i = o[--n], e[i] = r[i];
            return e.target || (e.target = r.srcElement || A), 3 === e.target.nodeType && (e.target = e.target.parentNode), e.metaKey === t && (e.metaKey = e.ctrlKey), s.filter ? s.filter(e, r) : e
        },
        special: {
            ready: {setup: F.bindReady},
            load: {noBubble: !0},
            focus: {delegateType: "focusin"},
            blur: {delegateType: "focusout"},
            beforeunload: {
                setup: function (e, t, n) {
                    F.isWindow(this) && (this.onbeforeunload = n)
                }, teardown: function (e, t) {
                    this.onbeforeunload === t && (this.onbeforeunload = null)
                }
            }
        },
        simulate: function (e, t, n, i) {
            var r = F.extend(new F.Event, n, {type: e, isSimulated: !0, originalEvent: {}});
            i ? F.event.trigger(r, null, t) : F.event.dispatch.call(t, r), r.isDefaultPrevented() && n.preventDefault()
        }
    }, F.event.handle = F.event.dispatch, F.removeEvent = A.removeEventListener ? function (e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    } : function (e, t, n) {
        e.detachEvent && e.detachEvent("on" + t, n)
    }, F.Event = function (e, t) {
        return this instanceof F.Event ? (e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || e.returnValue === !1 || e.getPreventDefault && e.getPreventDefault() ? q : S) : this.type = e, t && F.extend(this, t), this.timeStamp = e && e.timeStamp || F.now(), this[F.expando] = !0, void 0) : new F.Event(e, t)
    }, F.Event.prototype = {
        preventDefault: function () {
            this.isDefaultPrevented = q;
            var e = this.originalEvent;
            !e || (e.preventDefault ? e.preventDefault() : e.returnValue = !1)
        }, stopPropagation: function () {
            this.isPropagationStopped = q;
            var e = this.originalEvent;
            !e || (e.stopPropagation && e.stopPropagation(), e.cancelBubble = !0)
        }, stopImmediatePropagation: function () {
            this.isImmediatePropagationStopped = q, this.stopPropagation()
        }, isDefaultPrevented: S, isPropagationStopped: S, isImmediatePropagationStopped: S
    }, F.each({mouseenter: "mouseover", mouseleave: "mouseout"}, function (e, t) {
        F.event.special[e] = {
            delegateType: t, bindType: t, handle: function (e) {
                var n, i = this, r = e.relatedTarget, s = e.handleObj;
                s.selector;
                return r && (r === i || F.contains(i, r)) || (e.type = s.origType, n = s.handler.apply(this, arguments), e.type = t), n
            }
        }
    }), F.support.submitBubbles || (F.event.special.submit = {
        setup: function () {
            return !F.nodeName(this, "form") && void F.event.add(this, "click._submit keypress._submit", function (e) {
                    var n = e.target, i = F.nodeName(n, "input") || F.nodeName(n, "button") ? n.form : t;
                    i && !i._submit_attached && (F.event.add(i, "submit._submit", function (e) {
                        e._submit_bubble = !0
                    }), i._submit_attached = !0)
                })
        }, postDispatch: function (e) {
            e._submit_bubble && (delete e._submit_bubble, this.parentNode && !e.isTrigger && F.event.simulate("submit", this.parentNode, e, !0))
        }, teardown: function () {
            return !F.nodeName(this, "form") && void F.event.remove(this, "._submit")
        }
    }), F.support.changeBubbles || (F.event.special.change = {
        setup: function () {
            return Y.test(this.nodeName) ? ("checkbox" !== this.type && "radio" !== this.type || (F.event.add(this, "propertychange._change", function (e) {
                "checked" === e.originalEvent.propertyName && (this._just_changed = !0)
            }), F.event.add(this, "click._change", function (e) {
                this._just_changed && !e.isTrigger && (this._just_changed = !1, F.event.simulate("change", this, e, !0))
            })), !1) : void F.event.add(this, "beforeactivate._change", function (e) {
                var t = e.target;
                Y.test(t.nodeName) && !t._change_attached && (F.event.add(t, "change._change", function (e) {
                    this.parentNode && !e.isSimulated && !e.isTrigger && F.event.simulate("change", this.parentNode, e, !0)
                }), t._change_attached = !0)
            })
        }, handle: function (e) {
            var t = e.target;
            if (this !== t || e.isSimulated || e.isTrigger || "radio" !== t.type && "checkbox" !== t.type)return e.handleObj.handler.apply(this, arguments)
        }, teardown: function () {
            return F.event.remove(this, "._change"), Y.test(this.nodeName)
        }
    }), F.support.focusinBubbles || F.each({focus: "focusin", blur: "focusout"}, function (e, t) {
        var n = 0, i = function (e) {
            F.event.simulate(t, e.target, F.event.fix(e), !0)
        };
        F.event.special[t] = {
            setup: function () {
                0 === n++ && A.addEventListener(e, i, !0)
            }, teardown: function () {
                0 === --n && A.removeEventListener(e, i, !0)
            }
        }
    }), F.fn.extend({
        on: function (e, n, i, r, s) {
            var o, a;
            if ("object" == typeof e) {
                "string" != typeof n && (i = i || n, n = t);
                for (a in e)this.on(a, n, i, e[a], s);
                return this
            }
            if (null == i && null == r ? (r = n, i = n = t) : null == r && ("string" == typeof n ? (r = i, i = t) : (r = i, i = n, n = t)), r === !1)r = S; else if (!r)return this;
            return 1 === s && (o = r, r = function (e) {
                return F().off(e), o.apply(this, arguments)
            }, r.guid = o.guid || (o.guid = F.guid++)), this.each(function () {
                F.event.add(this, e, r, i, n)
            })
        }, one: function (e, t, n, i) {
            return this.on(e, t, n, i, 1)
        }, off: function (e, n, i) {
            if (e && e.preventDefault && e.handleObj) {
                var r = e.handleObj;
                return F(e.delegateTarget).off(r.namespace ? r.origType + "." + r.namespace : r.origType, r.selector, r.handler), this
            }
            if ("object" == typeof e) {
                for (var s in e)this.off(s, n, e[s]);
                return this
            }
            return n !== !1 && "function" != typeof n || (i = n, n = t), i === !1 && (i = S), this.each(function () {
                F.event.remove(this, e, i, n)
            })
        }, bind: function (e, t, n) {
            return this.on(e, null, t, n)
        }, unbind: function (e, t) {
            return this.off(e, null, t)
        }, live: function (e, t, n) {
            return F(this.context).on(e, this.selector, t, n), this
        }, die: function (e, t) {
            return F(this.context).off(e, this.selector || "**", t), this
        }, delegate: function (e, t, n, i) {
            return this.on(t, e, n, i)
        }, undelegate: function (e, t, n) {
            return 1 == arguments.length ? this.off(e, "**") : this.off(t, e, n)
        }, trigger: function (e, t) {
            return this.each(function () {
                F.event.trigger(e, t, this)
            })
        }, triggerHandler: function (e, t) {
            if (this[0])return F.event.trigger(e, t, this[0], !0)
        }, toggle: function (e) {
            var t = arguments, n = e.guid || F.guid++, i = 0, r = function (n) {
                var r = (F._data(this, "lastToggle" + e.guid) || 0) % i;
                return F._data(this, "lastToggle" + e.guid, r + 1), n.preventDefault(), t[r].apply(this, arguments) || !1
            };
            for (r.guid = n; i < t.length;)t[i++].guid = n;
            return this.click(r)
        }, hover: function (e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        }
    }), F.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function (e, t) {
        F.fn[t] = function (e, n) {
            return null == n && (n = e, e = null), arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
        }, F.attrFn && (F.attrFn[t] = !0), ee.test(t) && (F.event.fixHooks[t] = F.event.keyHooks), te.test(t) && (F.event.fixHooks[t] = F.event.mouseHooks)
    }), function () {
        function e(e, t, n, i, s, o) {
            for (var a = 0, l = i.length; a < l; a++) {
                var c = i[a];
                if (c) {
                    var u = !1;
                    for (c = c[e]; c;) {
                        if (c[r] === n) {
                            u = i[c.sizset];
                            break
                        }
                        if (1 === c.nodeType)if (o || (c[r] = n, c.sizset = a), "string" != typeof t) {
                            if (c === t) {
                                u = !0;
                                break
                            }
                        } else if (p.filter(t, [c]).length > 0) {
                            u = c;
                            break
                        }
                        c = c[e]
                    }
                    i[a] = u
                }
            }
        }

        function n(e, t, n, i, s, o) {
            for (var a = 0, l = i.length; a < l; a++) {
                var c = i[a];
                if (c) {
                    var u = !1;
                    for (c = c[e]; c;) {
                        if (c[r] === n) {
                            u = i[c.sizset];
                            break
                        }
                        if (1 === c.nodeType && !o && (c[r] = n, c.sizset = a), c.nodeName.toLowerCase() === t) {
                            u = c;
                            break
                        }
                        c = c[e]
                    }
                    i[a] = u
                }
            }
        }

        var i = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g, r = "sizcache" + (Math.random() + "").replace(".", ""), s = 0, o = Object.prototype.toString, a = !1, l = !0, c = /\\/g, u = /\r\n/g, f = /\W/;
        [0, 0].sort(function () {
            return l = !1, 0
        });
        var p = function (e, t, n, r) {
            n = n || [], t = t || A;
            var s = t;
            if (1 !== t.nodeType && 9 !== t.nodeType)return [];
            if (!e || "string" != typeof e)return n;
            var a, l, c, u, f, h, g, v, b = !0, x = p.isXML(t), w = [], N = e;
            do if (i.exec(""), a = i.exec(N), a && (N = a[3], w.push(a[1]), a[2])) {
                u = a[3];
                break
            } while (a);
            if (w.length > 1 && m.exec(e))if (2 === w.length && d.relative[w[0]])l = T(w[0] + w[1], t, r); else for (l = d.relative[w[0]] ? [t] : p(w.shift(), t); w.length;)e = w.shift(), d.relative[e] && (e += w.shift()), l = T(e, l, r); else if (!r && w.length > 1 && 9 === t.nodeType && !x && d.match.ID.test(w[0]) && !d.match.ID.test(w[w.length - 1]) && (f = p.find(w.shift(), t, x), t = f.expr ? p.filter(f.expr, f.set)[0] : f.set[0]), t)for (f = r ? {
                expr: w.pop(),
                set: y(r)
            } : p.find(w.pop(), 1 !== w.length || "~" !== w[0] && "+" !== w[0] || !t.parentNode ? t : t.parentNode, x), l = f.expr ? p.filter(f.expr, f.set) : f.set, w.length > 0 ? c = y(l) : b = !1; w.length;)h = w.pop(), g = h, d.relative[h] ? g = w.pop() : h = "", null == g && (g = t), d.relative[h](c, g, x); else c = w = [];
            if (c || (c = l), c || p.error(h || e), "[object Array]" === o.call(c))if (b)if (t && 1 === t.nodeType)for (v = 0; null != c[v]; v++)c[v] && (c[v] === !0 || 1 === c[v].nodeType && p.contains(t, c[v])) && n.push(l[v]); else for (v = 0; null != c[v]; v++)c[v] && 1 === c[v].nodeType && n.push(l[v]); else n.push.apply(n, c); else y(c, n);
            return u && (p(u, s, n, r), p.uniqueSort(n)), n
        };
        p.uniqueSort = function (e) {
            if (x && (a = l, e.sort(x), a))for (var t = 1; t < e.length; t++)e[t] === e[t - 1] && e.splice(t--, 1);
            return e
        }, p.matches = function (e, t) {
            return p(e, null, null, t)
        }, p.matchesSelector = function (e, t) {
            return p(t, null, null, [e]).length > 0
        }, p.find = function (e, t, n) {
            var i, r, s, o, a, l;
            if (!e)return [];
            for (r = 0, s = d.order.length; r < s; r++)if (a = d.order[r], (o = d.leftMatch[a].exec(e)) && (l = o[1], o.splice(1, 1), "\\" !== l.substr(l.length - 1) && (o[1] = (o[1] || "").replace(c, ""), i = d.find[a](o, t, n), null != i))) {
                e = e.replace(d.match[a], "");
                break
            }
            return i || (i = "undefined" != typeof t.getElementsByTagName ? t.getElementsByTagName("*") : []), {
                set: i,
                expr: e
            }
        }, p.filter = function (e, n, i, r) {
            for (var s, o, a, l, c, u, f, h, m, g = e, v = [], y = n, b = n && n[0] && p.isXML(n[0]); e && n.length;) {
                for (a in d.filter)if (null != (s = d.leftMatch[a].exec(e)) && s[2]) {
                    if (u = d.filter[a], f = s[1], o = !1, s.splice(1, 1), "\\" === f.substr(f.length - 1))continue;
                    if (y === v && (v = []), d.preFilter[a])if (s = d.preFilter[a](s, y, i, v, r, b)) {
                        if (s === !0)continue
                    } else o = l = !0;
                    if (s)for (h = 0; null != (c = y[h]); h++)c && (l = u(c, s, h, y), m = r ^ l, i && null != l ? m ? o = !0 : y[h] = !1 : m && (v.push(c), o = !0));
                    if (l !== t) {
                        if (i || (y = v), e = e.replace(d.match[a], ""), !o)return [];
                        break
                    }
                }
                if (e === g) {
                    if (null != o)break;
                    p.error(e)
                }
                g = e
            }
            return y
        }, p.error = function (e) {
            throw new Error("Syntax error, unrecognized expression: " + e)
        };
        var h = p.getText = function (e) {
            var t, n, i = e.nodeType, r = "";
            if (i) {
                if (1 === i || 9 === i || 11 === i) {
                    if ("string" == typeof e.textContent)return e.textContent;
                    if ("string" == typeof e.innerText)return e.innerText.replace(u, "");
                    for (e = e.firstChild; e; e = e.nextSibling)r += h(e)
                } else if (3 === i || 4 === i)return e.nodeValue
            } else for (t = 0; n = e[t]; t++)8 !== n.nodeType && (r += h(n));
            return r
        }, d = p.selectors = {
            order: ["ID", "NAME", "TAG"],
            match: {
                ID: /#((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,
                CLASS: /\.((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,
                NAME: /\[name=['"]*((?:[\w\u00c0-\uFFFF\-]|\\.)+)['"]*\]/,
                ATTR: /\[\s*((?:[\w\u00c0-\uFFFF\-]|\\.)+)\s*(?:(\S?=)\s*(?:(['"])(.*?)\3|(#?(?:[\w\u00c0-\uFFFF\-]|\\.)*)|)|)\s*\]/,
                TAG: /^((?:[\w\u00c0-\uFFFF\*\-]|\\.)+)/,
                CHILD: /:(only|nth|last|first)-child(?:\(\s*(even|odd|(?:[+\-]?\d+|(?:[+\-]?\d*)?n\s*(?:[+\-]\s*\d+)?))\s*\))?/,
                POS: /:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^\-]|$)/,
                PSEUDO: /:((?:[\w\u00c0-\uFFFF\-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/
            },
            leftMatch: {},
            attrMap: {"class": "className", "for": "htmlFor"},
            attrHandle: {
                href: function (e) {
                    return e.getAttribute("href")
                }, type: function (e) {
                    return e.getAttribute("type")
                }
            },
            relative: {
                "+": function (e, t) {
                    var n = "string" == typeof t, i = n && !f.test(t), r = n && !i;
                    i && (t = t.toLowerCase());
                    for (var s, o = 0, a = e.length; o < a; o++)if (s = e[o]) {
                        for (; (s = s.previousSibling) && 1 !== s.nodeType;);
                        e[o] = r || s && s.nodeName.toLowerCase() === t ? s || !1 : s === t
                    }
                    r && p.filter(t, e, !0)
                }, ">": function (e, t) {
                    var n, i = "string" == typeof t, r = 0, s = e.length;
                    if (i && !f.test(t)) {
                        for (t = t.toLowerCase(); r < s; r++)if (n = e[r]) {
                            var o = n.parentNode;
                            e[r] = o.nodeName.toLowerCase() === t && o
                        }
                    } else {
                        for (; r < s; r++)n = e[r], n && (e[r] = i ? n.parentNode : n.parentNode === t);
                        i && p.filter(t, e, !0)
                    }
                }, "": function (t, i, r) {
                    var o, a = s++, l = e;
                    "string" == typeof i && !f.test(i) && (i = i.toLowerCase(), o = i, l = n), l("parentNode", i, a, t, o, r)
                }, "~": function (t, i, r) {
                    var o, a = s++, l = e;
                    "string" == typeof i && !f.test(i) && (i = i.toLowerCase(), o = i, l = n), l("previousSibling", i, a, t, o, r)
                }
            },
            find: {
                ID: function (e, t, n) {
                    if ("undefined" != typeof t.getElementById && !n) {
                        var i = t.getElementById(e[1]);
                        return i && i.parentNode ? [i] : []
                    }
                }, NAME: function (e, t) {
                    if ("undefined" != typeof t.getElementsByName) {
                        for (var n = [], i = t.getElementsByName(e[1]), r = 0, s = i.length; r < s; r++)i[r].getAttribute("name") === e[1] && n.push(i[r]);
                        return 0 === n.length ? null : n
                    }
                }, TAG: function (e, t) {
                    if ("undefined" != typeof t.getElementsByTagName)return t.getElementsByTagName(e[1])
                }
            },
            preFilter: {
                CLASS: function (e, t, n, i, r, s) {
                    if (e = " " + e[1].replace(c, "") + " ", s)return e;
                    for (var o, a = 0; null != (o = t[a]); a++)o && (r ^ (o.className && (" " + o.className + " ").replace(/[\t\n\r]/g, " ").indexOf(e) >= 0) ? n || i.push(o) : n && (t[a] = !1));
                    return !1
                }, ID: function (e) {
                    return e[1].replace(c, "")
                }, TAG: function (e, t) {
                    return e[1].replace(c, "").toLowerCase()
                }, CHILD: function (e) {
                    if ("nth" === e[1]) {
                        e[2] || p.error(e[0]), e[2] = e[2].replace(/^\+|\s*/g, "");
                        var t = /(-?)(\d*)(?:n([+\-]?\d*))?/.exec("even" === e[2] && "2n" || "odd" === e[2] && "2n+1" || !/\D/.test(e[2]) && "0n+" + e[2] || e[2]);
                        e[2] = t[1] + (t[2] || 1) - 0, e[3] = t[3] - 0
                    } else e[2] && p.error(e[0]);
                    return e[0] = s++, e
                }, ATTR: function (e, t, n, i, r, s) {
                    var o = e[1] = e[1].replace(c, "");
                    return !s && d.attrMap[o] && (e[1] = d.attrMap[o]), e[4] = (e[4] || e[5] || "").replace(c, ""), "~=" === e[2] && (e[4] = " " + e[4] + " "), e
                }, PSEUDO: function (e, t, n, r, s) {
                    if ("not" === e[1]) {
                        if (!((i.exec(e[3]) || "").length > 1 || /^\w/.test(e[3]))) {
                            var o = p.filter(e[3], t, n, !0 ^ s);
                            return n || r.push.apply(r, o), !1
                        }
                        e[3] = p(e[3], null, null, t)
                    } else if (d.match.POS.test(e[0]) || d.match.CHILD.test(e[0]))return !0;
                    return e
                }, POS: function (e) {
                    return e.unshift(!0), e
                }
            },
            filters: {
                enabled: function (e) {
                    return e.disabled === !1 && "hidden" !== e.type
                }, disabled: function (e) {
                    return e.disabled === !0
                }, checked: function (e) {
                    return e.checked === !0
                }, selected: function (e) {
                    return e.parentNode && e.parentNode.selectedIndex, e.selected === !0
                }, parent: function (e) {
                    return !!e.firstChild
                }, empty: function (e) {
                    return !e.firstChild
                }, has: function (e, t, n) {
                    return !!p(n[3], e).length
                }, header: function (e) {
                    return /h\d/i.test(e.nodeName)
                }, text: function (e) {
                    var t = e.getAttribute("type"), n = e.type;
                    return "input" === e.nodeName.toLowerCase() && "text" === n && (t === n || null === t)
                }, radio: function (e) {
                    return "input" === e.nodeName.toLowerCase() && "radio" === e.type
                }, checkbox: function (e) {
                    return "input" === e.nodeName.toLowerCase() && "checkbox" === e.type
                }, file: function (e) {
                    return "input" === e.nodeName.toLowerCase() && "file" === e.type
                }, password: function (e) {
                    return "input" === e.nodeName.toLowerCase() && "password" === e.type
                }, submit: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return ("input" === t || "button" === t) && "submit" === e.type
                }, image: function (e) {
                    return "input" === e.nodeName.toLowerCase() && "image" === e.type
                }, reset: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return ("input" === t || "button" === t) && "reset" === e.type
                }, button: function (e) {
                    var t = e.nodeName.toLowerCase();
                    return "input" === t && "button" === e.type || "button" === t
                }, input: function (e) {
                    return /input|select|textarea|button/i.test(e.nodeName)
                }, focus: function (e) {
                    return e === e.ownerDocument.activeElement
                }
            },
            setFilters: {
                first: function (e, t) {
                    return 0 === t
                }, last: function (e, t, n, i) {
                    return t === i.length - 1
                }, even: function (e, t) {
                    return t % 2 === 0
                }, odd: function (e, t) {
                    return t % 2 === 1
                }, lt: function (e, t, n) {
                    return t < n[3] - 0
                }, gt: function (e, t, n) {
                    return t > n[3] - 0
                }, nth: function (e, t, n) {
                    return n[3] - 0 === t
                }, eq: function (e, t, n) {
                    return n[3] - 0 === t
                }
            },
            filter: {
                PSEUDO: function (e, t, n, i) {
                    var r = t[1], s = d.filters[r];
                    if (s)return s(e, n, t, i);
                    if ("contains" === r)return (e.textContent || e.innerText || h([e]) || "").indexOf(t[3]) >= 0;
                    if ("not" === r) {
                        for (var o = t[3], a = 0, l = o.length; a < l; a++)if (o[a] === e)return !1;
                        return !0
                    }
                    p.error(r)
                }, CHILD: function (e, t) {
                    var n, i, s, o, a, l, c = t[1], u = e;
                    switch (c) {
                        case"only":
                        case"first":
                            for (; u = u.previousSibling;)if (1 === u.nodeType)return !1;
                            if ("first" === c)return !0;
                            u = e;
                        case"last":
                            for (; u = u.nextSibling;)if (1 === u.nodeType)return !1;
                            return !0;
                        case"nth":
                            if (n = t[2], i = t[3], 1 === n && 0 === i)return !0;
                            if (s = t[0], o = e.parentNode, o && (o[r] !== s || !e.nodeIndex)) {
                                for (a = 0, u = o.firstChild; u; u = u.nextSibling)1 === u.nodeType && (u.nodeIndex = ++a);
                                o[r] = s
                            }
                            return l = e.nodeIndex - i, 0 === n ? 0 === l : l % n === 0 && l / n >= 0
                    }
                }, ID: function (e, t) {
                    return 1 === e.nodeType && e.getAttribute("id") === t
                }, TAG: function (e, t) {
                    return "*" === t && 1 === e.nodeType || !!e.nodeName && e.nodeName.toLowerCase() === t
                }, CLASS: function (e, t) {
                    return (" " + (e.className || e.getAttribute("class")) + " ").indexOf(t) > -1
                }, ATTR: function (e, t) {
                    var n = t[1], i = p.attr ? p.attr(e, n) : d.attrHandle[n] ? d.attrHandle[n](e) : null != e[n] ? e[n] : e.getAttribute(n), r = i + "", s = t[2], o = t[4];
                    return null == i ? "!=" === s : !s && p.attr ? null != i : "=" === s ? r === o : "*=" === s ? r.indexOf(o) >= 0 : "~=" === s ? (" " + r + " ").indexOf(o) >= 0 : o ? "!=" === s ? r !== o : "^=" === s ? 0 === r.indexOf(o) : "$=" === s ? r.substr(r.length - o.length) === o : "|=" === s && (r === o || r.substr(0, o.length + 1) === o + "-") : r && i !== !1
                }, POS: function (e, t, n, i) {
                    var r = t[2], s = d.setFilters[r];
                    if (s)return s(e, n, t, i)
                }
            }
        }, m = d.match.POS, g = function (e, t) {
            return "\\" + (t - 0 + 1)
        };
        for (var v in d.match)d.match[v] = new RegExp(d.match[v].source + /(?![^\[]*\])(?![^\(]*\))/.source), d.leftMatch[v] = new RegExp(/(^(?:.|\r|\n)*?)/.source + d.match[v].source.replace(/\\(\d+)/g, g));
        d.match.globalPOS = m;
        var y = function (e, t) {
            return e = Array.prototype.slice.call(e, 0), t ? (t.push.apply(t, e), t) : e
        };
        try {
            Array.prototype.slice.call(A.documentElement.childNodes, 0)[0].nodeType
        } catch (b) {
            y = function (e, t) {
                var n = 0, i = t || [];
                if ("[object Array]" === o.call(e))Array.prototype.push.apply(i, e); else if ("number" == typeof e.length)for (var r = e.length; n < r; n++)i.push(e[n]); else for (; e[n]; n++)i.push(e[n]);
                return i
            }
        }
        var x, w;
        A.documentElement.compareDocumentPosition ? x = function (e, t) {
            return e === t ? (a = !0, 0) : e.compareDocumentPosition && t.compareDocumentPosition ? 4 & e.compareDocumentPosition(t) ? -1 : 1 : e.compareDocumentPosition ? -1 : 1
        } : (x = function (e, t) {
            if (e === t)return a = !0, 0;
            if (e.sourceIndex && t.sourceIndex)return e.sourceIndex - t.sourceIndex;
            var n, i, r = [], s = [], o = e.parentNode, l = t.parentNode, c = o;
            if (o === l)return w(e, t);
            if (!o)return -1;
            if (!l)return 1;
            for (; c;)r.unshift(c), c = c.parentNode;
            for (c = l; c;)s.unshift(c), c = c.parentNode;
            n = r.length, i = s.length;
            for (var u = 0; u < n && u < i; u++)if (r[u] !== s[u])return w(r[u], s[u]);
            return u === n ? w(e, s[u], -1) : w(r[u], t, 1)
        }, w = function (e, t, n) {
            if (e === t)return n;
            for (var i = e.nextSibling; i;) {
                if (i === t)return -1;
                i = i.nextSibling
            }
            return 1
        }), function () {
            var e = A.createElement("div"), n = "script" + (new Date).getTime(), i = A.documentElement;
            e.innerHTML = "<a name='" + n + "'/>", i.insertBefore(e, i.firstChild), A.getElementById(n) && (d.find.ID = function (e, n, i) {
                if ("undefined" != typeof n.getElementById && !i) {
                    var r = n.getElementById(e[1]);
                    return r ? r.id === e[1] || "undefined" != typeof r.getAttributeNode && r.getAttributeNode("id").nodeValue === e[1] ? [r] : t : []
                }
            }, d.filter.ID = function (e, t) {
                var n = "undefined" != typeof e.getAttributeNode && e.getAttributeNode("id");
                return 1 === e.nodeType && n && n.nodeValue === t
            }), i.removeChild(e), i = e = null
        }(), function () {
            var e = A.createElement("div");
            e.appendChild(A.createComment("")), e.getElementsByTagName("*").length > 0 && (d.find.TAG = function (e, t) {
                var n = t.getElementsByTagName(e[1]);
                if ("*" === e[1]) {
                    for (var i = [], r = 0; n[r]; r++)1 === n[r].nodeType && i.push(n[r]);
                    n = i
                }
                return n
            }), e.innerHTML = "<a href='#'></a>", e.firstChild && "undefined" != typeof e.firstChild.getAttribute && "#" !== e.firstChild.getAttribute("href") && (d.attrHandle.href = function (e) {
                return e.getAttribute("href", 2)
            }), e = null
        }(), A.querySelectorAll && function () {
            var e = p, t = A.createElement("div"), n = "__sizzle__";
            if (t.innerHTML = "<p class='TEST'></p>", !t.querySelectorAll || 0 !== t.querySelectorAll(".TEST").length) {
                p = function (t, i, r, s) {
                    if (i = i || A, !s && !p.isXML(i)) {
                        var o = /^(\w+$)|^\.([\w\-]+$)|^#([\w\-]+$)/.exec(t);
                        if (o && (1 === i.nodeType || 9 === i.nodeType)) {
                            if (o[1])return y(i.getElementsByTagName(t), r);
                            if (o[2] && d.find.CLASS && i.getElementsByClassName)return y(i.getElementsByClassName(o[2]), r)
                        }
                        if (9 === i.nodeType) {
                            if ("body" === t && i.body)return y([i.body], r);
                            if (o && o[3]) {
                                var a = i.getElementById(o[3]);
                                if (!a || !a.parentNode)return y([], r);
                                if (a.id === o[3])return y([a], r)
                            }
                            try {
                                return y(i.querySelectorAll(t), r)
                            } catch (l) {
                            }
                        } else if (1 === i.nodeType && "object" !== i.nodeName.toLowerCase()) {
                            var c = i, u = i.getAttribute("id"), f = u || n, h = i.parentNode, m = /^\s*[+~]/.test(t);
                            u ? f = f.replace(/'/g, "\\$&") : i.setAttribute("id", f), m && h && (i = i.parentNode);
                            try {
                                if (!m || h)return y(i.querySelectorAll("[id='" + f + "'] " + t), r)
                            } catch (g) {
                            } finally {
                                u || c.removeAttribute("id")
                            }
                        }
                    }
                    return e(t, i, r, s)
                };
                for (var i in e)p[i] = e[i];
                t = null
            }
        }(), function () {
            var e = A.documentElement, t = e.matchesSelector || e.mozMatchesSelector || e.webkitMatchesSelector || e.msMatchesSelector;
            if (t) {
                var n = !t.call(A.createElement("div"), "div"), i = !1;
                try {
                    t.call(A.documentElement, "[test!='']:sizzle")
                } catch (r) {
                    i = !0
                }
                p.matchesSelector = function (e, r) {
                    if (r = r.replace(/\=\s*([^'"\]]*)\s*\]/g, "='$1']"), !p.isXML(e))try {
                        if (i || !d.match.PSEUDO.test(r) && !/!=/.test(r)) {
                            var s = t.call(e, r);
                            if (s || !n || e.document && 11 !== e.document.nodeType)return s
                        }
                    } catch (o) {
                    }
                    return p(r, null, null, [e]).length > 0
                }
            }
        }(), function () {
            var e = A.createElement("div");
            if (e.innerHTML = "<div class='test e'></div><div class='test'></div>", e.getElementsByClassName && 0 !== e.getElementsByClassName("e").length) {
                if (e.lastChild.className = "e", 1 === e.getElementsByClassName("e").length)return;
                d.order.splice(1, 0, "CLASS"), d.find.CLASS = function (e, t, n) {
                    if ("undefined" != typeof t.getElementsByClassName && !n)return t.getElementsByClassName(e[1])
                }, e = null
            }
        }(), A.documentElement.contains ? p.contains = function (e, t) {
            return e !== t && (!e.contains || e.contains(t))
        } : A.documentElement.compareDocumentPosition ? p.contains = function (e, t) {
            return !!(16 & e.compareDocumentPosition(t))
        } : p.contains = function () {
            return !1
        }, p.isXML = function (e) {
            var t = (e ? e.ownerDocument || e : 0).documentElement;
            return !!t && "HTML" !== t.nodeName
        };
        var T = function (e, t, n) {
            for (var i, r = [], s = "", o = t.nodeType ? [t] : t; i = d.match.PSEUDO.exec(e);)s += i[0], e = e.replace(d.match.PSEUDO, "");
            e = d.relative[e] ? e + "*" : e;
            for (var a = 0, l = o.length; a < l; a++)p(e, o[a], r, n);
            return p.filter(s, r)
        };
        p.attr = F.attr, p.selectors.attrMap = {}, F.find = p, F.expr = p.selectors, F.expr[":"] = F.expr.filters, F.unique = p.uniqueSort, F.text = p.getText, F.isXMLDoc = p.isXML, F.contains = p.contains
    }();
    var ae = /Until$/, le = /^(?:parents|prevUntil|prevAll)/, ce = /,/, ue = /^.[^:#\[\.,]*$/, fe = Array.prototype.slice, pe = F.expr.match.globalPOS, he = {
        children: !0,
        contents: !0,
        next: !0,
        prev: !0
    };
    F.fn.extend({
        find: function (e) {
            var t, n, i = this;
            if ("string" != typeof e)return F(e).filter(function () {
                for (t = 0, n = i.length; t < n; t++)if (F.contains(i[t], this))return !0
            });
            var r, s, o, a = this.pushStack("", "find", e);
            for (t = 0, n = this.length; t < n; t++)if (r = a.length, F.find(e, this[t], a), t > 0)for (s = r; s < a.length; s++)for (o = 0; o < r; o++)if (a[o] === a[s]) {
                a.splice(s--, 1);
                break
            }
            return a
        }, has: function (e) {
            var t = F(e);
            return this.filter(function () {
                for (var e = 0, n = t.length; e < n; e++)if (F.contains(this, t[e]))return !0
            })
        }, not: function (e) {
            return this.pushStack(k(this, e, !1), "not", e)
        }, filter: function (e) {
            return this.pushStack(k(this, e, !0), "filter", e)
        }, is: function (e) {
            return !!e && ("string" == typeof e ? pe.test(e) ? F(e, this.context).index(this[0]) >= 0 : F.filter(e, this).length > 0 : this.filter(e).length > 0)
        }, closest: function (e, t) {
            var n, i, r = [], s = this[0];
            if (F.isArray(e)) {
                for (var o = 1; s && s.ownerDocument && s !== t;) {
                    for (n = 0; n < e.length; n++)F(s).is(e[n]) && r.push({selector: e[n], elem: s, level: o});
                    s = s.parentNode, o++
                }
                return r
            }
            var a = pe.test(e) || "string" != typeof e ? F(e, t || this.context) : 0;
            for (n = 0, i = this.length; n < i; n++)for (s = this[n]; s;) {
                if (a ? a.index(s) > -1 : F.find.matchesSelector(s, e)) {
                    r.push(s);
                    break
                }
                if (s = s.parentNode, !s || !s.ownerDocument || s === t || 11 === s.nodeType)break
            }
            return r = r.length > 1 ? F.unique(r) : r, this.pushStack(r, "closest", e)
        }, index: function (e) {
            return e ? "string" == typeof e ? F.inArray(this[0], F(e)) : F.inArray(e.jquery ? e[0] : e, this) : this[0] && this[0].parentNode ? this.prevAll().length : -1
        }, add: function (e, t) {
            var n = "string" == typeof e ? F(e, t) : F.makeArray(e && e.nodeType ? [e] : e), i = F.merge(this.get(), n);
            return this.pushStack(C(n[0]) || C(i[0]) ? i : F.unique(i))
        }, andSelf: function () {
            return this.add(this.prevObject)
        }
    }), F.each({
        parent: function (e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        }, parents: function (e) {
            return F.dir(e, "parentNode")
        }, parentsUntil: function (e, t, n) {
            return F.dir(e, "parentNode", n)
        }, next: function (e) {
            return F.nth(e, 2, "nextSibling")
        }, prev: function (e) {
            return F.nth(e, 2, "previousSibling")
        }, nextAll: function (e) {
            return F.dir(e, "nextSibling")
        }, prevAll: function (e) {
            return F.dir(e, "previousSibling")
        }, nextUntil: function (e, t, n) {
            return F.dir(e, "nextSibling", n)
        }, prevUntil: function (e, t, n) {
            return F.dir(e, "previousSibling", n)
        }, siblings: function (e) {
            return F.sibling((e.parentNode || {}).firstChild, e)
        }, children: function (e) {
            return F.sibling(e.firstChild)
        }, contents: function (e) {
            return F.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document : F.makeArray(e.childNodes)
        }
    }, function (e, t) {
        F.fn[e] = function (n, i) {
            var r = F.map(this, t, n);
            return ae.test(e) || (i = n), i && "string" == typeof i && (r = F.filter(i, r)), r = this.length > 1 && !he[e] ? F.unique(r) : r, (this.length > 1 || ce.test(i)) && le.test(e) && (r = r.reverse()), this.pushStack(r, e, fe.call(arguments).join(","))
        }
    }), F.extend({
        filter: function (e, t, n) {
            return n && (e = ":not(" + e + ")"), 1 === t.length ? F.find.matchesSelector(t[0], e) ? [t[0]] : [] : F.find.matches(e, t)
        }, dir: function (e, n, i) {
            for (var r = [], s = e[n]; s && 9 !== s.nodeType && (i === t || 1 !== s.nodeType || !F(s).is(i));)1 === s.nodeType && r.push(s), s = s[n];
            return r
        }, nth: function (e, t, n, i) {
            t = t || 1;
            for (var r = 0; e && (1 !== e.nodeType || ++r !== t); e = e[n]);
            return e
        }, sibling: function (e, t) {
            for (var n = []; e; e = e.nextSibling)1 === e.nodeType && e !== t && n.push(e);
            return n
        }
    });
    var de = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video", me = / jQuery\d+="(?:\d+|null)"/g, ge = /^\s+/, ve = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, ye = /<([\w:]+)/, be = /<tbody/i, xe = /<|&#?\w+;/, we = /<(?:script|style)/i, Te = /<(?:script|object|embed|option|style)/i, Ne = new RegExp("<(?:" + de + ")[\\s/>]", "i"), ke = /checked\s*(?:[^=]|=\s*.checked.)/i, Ce = /\/(java|ecma)script/i, qe = /^\s*<!(?:\[CDATA\[|\-\-)/, Se = {
        option: [1, "<select multiple='multiple'>", "</select>"],
        legend: [1, "<fieldset>", "</fieldset>"],
        thead: [1, "<table>", "</table>"],
        tr: [2, "<table><tbody>", "</tbody></table>"],
        td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
        col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
        area: [1, "<map>", "</map>"],
        _default: [0, "", ""]
    }, Ee = N(A);
    Se.optgroup = Se.option, Se.tbody = Se.tfoot = Se.colgroup = Se.caption = Se.thead, Se.th = Se.td, F.support.htmlSerialize || (Se._default = [1, "div<div>", "</div>"]), F.fn.extend({
        text: function (e) {
            return F.access(this, function (e) {
                return e === t ? F.text(this) : this.empty().append((this[0] && this[0].ownerDocument || A).createTextNode(e))
            }, null, e, arguments.length)
        }, wrapAll: function (e) {
            if (F.isFunction(e))return this.each(function (t) {
                F(this).wrapAll(e.call(this, t))
            });
            if (this[0]) {
                var t = F(e, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && t.insertBefore(this[0]), t.map(function () {
                    for (var e = this; e.firstChild && 1 === e.firstChild.nodeType;)e = e.firstChild;
                    return e
                }).append(this)
            }
            return this
        }, wrapInner: function (e) {
            return F.isFunction(e) ? this.each(function (t) {
                F(this).wrapInner(e.call(this, t))
            }) : this.each(function () {
                var t = F(this), n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e);
            })
        }, wrap: function (e) {
            var t = F.isFunction(e);
            return this.each(function (n) {
                F(this).wrapAll(t ? e.call(this, n) : e)
            })
        }, unwrap: function () {
            return this.parent().each(function () {
                F.nodeName(this, "body") || F(this).replaceWith(this.childNodes)
            }).end()
        }, append: function () {
            return this.domManip(arguments, !0, function (e) {
                1 === this.nodeType && this.appendChild(e)
            })
        }, prepend: function () {
            return this.domManip(arguments, !0, function (e) {
                1 === this.nodeType && this.insertBefore(e, this.firstChild)
            })
        }, before: function () {
            if (this[0] && this[0].parentNode)return this.domManip(arguments, !1, function (e) {
                this.parentNode.insertBefore(e, this)
            });
            if (arguments.length) {
                var e = F.clean(arguments);
                return e.push.apply(e, this.toArray()), this.pushStack(e, "before", arguments)
            }
        }, after: function () {
            if (this[0] && this[0].parentNode)return this.domManip(arguments, !1, function (e) {
                this.parentNode.insertBefore(e, this.nextSibling)
            });
            if (arguments.length) {
                var e = this.pushStack(this, "after", arguments);
                return e.push.apply(e, F.clean(arguments)), e
            }
        }, remove: function (e, t) {
            for (var n, i = 0; null != (n = this[i]); i++)e && !F.filter(e, [n]).length || (!t && 1 === n.nodeType && (F.cleanData(n.getElementsByTagName("*")), F.cleanData([n])), n.parentNode && n.parentNode.removeChild(n));
            return this
        }, empty: function () {
            for (var e, t = 0; null != (e = this[t]); t++)for (1 === e.nodeType && F.cleanData(e.getElementsByTagName("*")); e.firstChild;)e.removeChild(e.firstChild);
            return this
        }, clone: function (e, t) {
            return e = null != e && e, t = null == t ? e : t, this.map(function () {
                return F.clone(this, e, t)
            })
        }, html: function (e) {
            return F.access(this, function (e) {
                var n = this[0] || {}, i = 0, r = this.length;
                if (e === t)return 1 === n.nodeType ? n.innerHTML.replace(me, "") : null;
                if ("string" == typeof e && !we.test(e) && (F.support.leadingWhitespace || !ge.test(e)) && !Se[(ye.exec(e) || ["", ""])[1].toLowerCase()]) {
                    e = e.replace(ve, "<$1></$2>");
                    try {
                        for (; i < r; i++)n = this[i] || {}, 1 === n.nodeType && (F.cleanData(n.getElementsByTagName("*")), n.innerHTML = e);
                        n = 0
                    } catch (s) {
                    }
                }
                n && this.empty().append(e)
            }, null, e, arguments.length)
        }, replaceWith: function (e) {
            return this[0] && this[0].parentNode ? F.isFunction(e) ? this.each(function (t) {
                var n = F(this), i = n.html();
                n.replaceWith(e.call(this, t, i))
            }) : ("string" != typeof e && (e = F(e).detach()), this.each(function () {
                var t = this.nextSibling, n = this.parentNode;
                F(this).remove(), t ? F(t).before(e) : F(n).append(e)
            })) : this.length ? this.pushStack(F(F.isFunction(e) ? e() : e), "replaceWith", e) : this
        }, detach: function (e) {
            return this.remove(e, !0)
        }, domManip: function (e, n, i) {
            var r, s, o, a, l = e[0], c = [];
            if (!F.support.checkClone && 3 === arguments.length && "string" == typeof l && ke.test(l))return this.each(function () {
                F(this).domManip(e, n, i, !0)
            });
            if (F.isFunction(l))return this.each(function (r) {
                var s = F(this);
                e[0] = l.call(this, r, n ? s.html() : t), s.domManip(e, n, i)
            });
            if (this[0]) {
                if (a = l && l.parentNode, r = F.support.parentNode && a && 11 === a.nodeType && a.childNodes.length === this.length ? {fragment: a} : F.buildFragment(e, this, c), o = r.fragment, s = 1 === o.childNodes.length ? o = o.firstChild : o.firstChild, s) {
                    n = n && F.nodeName(s, "tr");
                    for (var u = 0, f = this.length, p = f - 1; u < f; u++)i.call(n ? T(this[u], s) : this[u], r.cacheable || f > 1 && u < p ? F.clone(o, !0, !0) : o)
                }
                c.length && F.each(c, function (e, t) {
                    t.src ? F.ajax({
                        type: "GET",
                        global: !1,
                        url: t.src,
                        async: !1,
                        dataType: "script"
                    }) : F.globalEval((t.text || t.textContent || t.innerHTML || "").replace(qe, "/*$0*/")), t.parentNode && t.parentNode.removeChild(t)
                })
            }
            return this
        }
    }), F.buildFragment = function (e, t, n) {
        var i, r, s, o, a = e[0];
        return t && t[0] && (o = t[0].ownerDocument || t[0]), o.createDocumentFragment || (o = A), 1 === e.length && "string" == typeof a && a.length < 512 && o === A && "<" === a.charAt(0) && !Te.test(a) && (F.support.checkClone || !ke.test(a)) && (F.support.html5Clone || !Ne.test(a)) && (r = !0, s = F.fragments[a], s && 1 !== s && (i = s)), i || (i = o.createDocumentFragment(), F.clean(e, o, i, n)), r && (F.fragments[a] = s ? i : 1), {
            fragment: i,
            cacheable: r
        }
    }, F.fragments = {}, F.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function (e, t) {
        F.fn[e] = function (n) {
            var i = [], r = F(n), s = 1 === this.length && this[0].parentNode;
            if (s && 11 === s.nodeType && 1 === s.childNodes.length && 1 === r.length)return r[t](this[0]), this;
            for (var o = 0, a = r.length; o < a; o++) {
                var l = (o > 0 ? this.clone(!0) : this).get();
                F(r[o])[t](l), i = i.concat(l)
            }
            return this.pushStack(i, e, r.selector)
        }
    }), F.extend({
        clone: function (e, t, n) {
            var i, r, s, o = F.support.html5Clone || F.isXMLDoc(e) || !Ne.test("<" + e.nodeName + ">") ? e.cloneNode(!0) : g(e);
            if (!(F.support.noCloneEvent && F.support.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || F.isXMLDoc(e)))for (x(e, o), i = b(e), r = b(o), s = 0; i[s]; ++s)r[s] && x(i[s], r[s]);
            if (t && (w(e, o), n))for (i = b(e), r = b(o), s = 0; i[s]; ++s)w(i[s], r[s]);
            return i = r = null, o
        }, clean: function (e, t, n, i) {
            var r, s, o, a = [];
            t = t || A, "undefined" == typeof t.createElement && (t = t.ownerDocument || t[0] && t[0].ownerDocument || A);
            for (var l, c = 0; null != (l = e[c]); c++)if ("number" == typeof l && (l += ""), l) {
                if ("string" == typeof l)if (xe.test(l)) {
                    l = l.replace(ve, "<$1></$2>");
                    var u, f = (ye.exec(l) || ["", ""])[1].toLowerCase(), p = Se[f] || Se._default, h = p[0], d = t.createElement("div"), m = Ee.childNodes;
                    for (t === A ? Ee.appendChild(d) : N(t).appendChild(d), d.innerHTML = p[1] + l + p[2]; h--;)d = d.lastChild;
                    if (!F.support.tbody) {
                        var g = be.test(l), y = "table" !== f || g ? "<table>" !== p[1] || g ? [] : d.childNodes : d.firstChild && d.firstChild.childNodes;
                        for (o = y.length - 1; o >= 0; --o)F.nodeName(y[o], "tbody") && !y[o].childNodes.length && y[o].parentNode.removeChild(y[o])
                    }
                    !F.support.leadingWhitespace && ge.test(l) && d.insertBefore(t.createTextNode(ge.exec(l)[0]), d.firstChild), l = d.childNodes, d && (d.parentNode.removeChild(d), m.length > 0 && (u = m[m.length - 1], u && u.parentNode && u.parentNode.removeChild(u)))
                } else l = t.createTextNode(l);
                var b;
                if (!F.support.appendChecked)if (l[0] && "number" == typeof(b = l.length))for (o = 0; o < b; o++)v(l[o]); else v(l);
                l.nodeType ? a.push(l) : a = F.merge(a, l)
            }
            if (n)for (r = function (e) {
                return !e.type || Ce.test(e.type)
            }, c = 0; a[c]; c++)if (s = a[c], i && F.nodeName(s, "script") && (!s.type || Ce.test(s.type)))i.push(s.parentNode ? s.parentNode.removeChild(s) : s); else {
                if (1 === s.nodeType) {
                    var x = F.grep(s.getElementsByTagName("script"), r);
                    a.splice.apply(a, [c + 1, 0].concat(x))
                }
                n.appendChild(s)
            }
            return a
        }, cleanData: function (e) {
            for (var t, n, i, r = F.cache, s = F.event.special, o = F.support.deleteExpando, a = 0; null != (i = e[a]); a++)if ((!i.nodeName || !F.noData[i.nodeName.toLowerCase()]) && (n = i[F.expando])) {
                if (t = r[n], t && t.events) {
                    for (var l in t.events)s[l] ? F.event.remove(i, l) : F.removeEvent(i, l, t.handle);
                    t.handle && (t.handle.elem = null)
                }
                o ? delete i[F.expando] : i.removeAttribute && i.removeAttribute(F.expando), delete r[n]
            }
        }
    });
    var Oe, je, De, Ae = /alpha\([^)]*\)/i, Le = /opacity=([^)]*)/, _e = /([A-Z]|^ms)/g, Fe = /^[\-+]?(?:\d*\.)?\d+$/i, Qe = /^-?(?:\d*\.)?\d+(?!px)[^\d\s]+$/i, Me = /^([\-+])=([\-+.\de]+)/, Be = /^margin/, Ie = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    }, Re = ["Top", "Right", "Bottom", "Left"];
    F.fn.css = function (e, n) {
        return F.access(this, function (e, n, i) {
            return i !== t ? F.style(e, n, i) : F.css(e, n)
        }, e, n, arguments.length > 1)
    }, F.extend({
        cssHooks: {
            opacity: {
                get: function (e, t) {
                    if (t) {
                        var n = Oe(e, "opacity");
                        return "" === n ? "1" : n
                    }
                    return e.style.opacity
                }
            }
        },
        cssNumber: {
            fillOpacity: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {"float": F.support.cssFloat ? "cssFloat" : "styleFloat"},
        style: function (e, n, i, r) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var s, o, a = F.camelCase(n), l = e.style, c = F.cssHooks[a];
                if (n = F.cssProps[a] || a, i === t)return c && "get" in c && (s = c.get(e, !1, r)) !== t ? s : l[n];
                if (o = typeof i, "string" === o && (s = Me.exec(i)) && (i = +(s[1] + 1) * +s[2] + parseFloat(F.css(e, n)), o = "number"), null == i || "number" === o && isNaN(i))return;
                if ("number" === o && !F.cssNumber[a] && (i += "px"), !(c && "set" in c && (i = c.set(e, i)) === t))try {
                    l[n] = i
                } catch (u) {
                }
            }
        },
        css: function (e, n, i) {
            var r, s;
            return n = F.camelCase(n), s = F.cssHooks[n], n = F.cssProps[n] || n, "cssFloat" === n && (n = "float"), s && "get" in s && (r = s.get(e, !0, i)) !== t ? r : Oe ? Oe(e, n) : void 0
        },
        swap: function (e, t, n) {
            var i, r, s = {};
            for (r in t)s[r] = e.style[r], e.style[r] = t[r];
            i = n.call(e);
            for (r in t)e.style[r] = s[r];
            return i
        }
    }), F.curCSS = F.css, A.defaultView && A.defaultView.getComputedStyle && (je = function (e, t) {
        var n, i, r, s, o = e.style;
        return t = t.replace(_e, "-$1").toLowerCase(), (i = e.ownerDocument.defaultView) && (r = i.getComputedStyle(e, null)) && (n = r.getPropertyValue(t), "" === n && !F.contains(e.ownerDocument.documentElement, e) && (n = F.style(e, t))), !F.support.pixelMargin && r && Be.test(t) && Qe.test(n) && (s = o.width, o.width = n, n = r.width, o.width = s), n
    }), A.documentElement.currentStyle && (De = function (e, t) {
        var n, i, r, s = e.currentStyle && e.currentStyle[t], o = e.style;
        return null == s && o && (r = o[t]) && (s = r), Qe.test(s) && (n = o.left, i = e.runtimeStyle && e.runtimeStyle.left, i && (e.runtimeStyle.left = e.currentStyle.left), o.left = "fontSize" === t ? "1em" : s, s = o.pixelLeft + "px", o.left = n, i && (e.runtimeStyle.left = i)), "" === s ? "auto" : s
    }), Oe = je || De, F.each(["height", "width"], function (e, t) {
        F.cssHooks[t] = {
            get: function (e, n, i) {
                if (n)return 0 !== e.offsetWidth ? m(e, t, i) : F.swap(e, Ie, function () {
                    return m(e, t, i)
                })
            }, set: function (e, t) {
                return Fe.test(t) ? t + "px" : t
            }
        }
    }), F.support.opacity || (F.cssHooks.opacity = {
        get: function (e, t) {
            return Le.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? parseFloat(RegExp.$1) / 100 + "" : t ? "1" : ""
        }, set: function (e, t) {
            var n = e.style, i = e.currentStyle, r = F.isNumeric(t) ? "alpha(opacity=" + 100 * t + ")" : "", s = i && i.filter || n.filter || "";
            n.zoom = 1, t >= 1 && "" === F.trim(s.replace(Ae, "")) && (n.removeAttribute("filter"), i && !i.filter) || (n.filter = Ae.test(s) ? s.replace(Ae, r) : s + " " + r)
        }
    }), F(function () {
        F.support.reliableMarginRight || (F.cssHooks.marginRight = {
            get: function (e, t) {
                return F.swap(e, {display: "inline-block"}, function () {
                    return t ? Oe(e, "margin-right") : e.style.marginRight
                })
            }
        })
    }), F.expr && F.expr.filters && (F.expr.filters.hidden = function (e) {
        var t = e.offsetWidth, n = e.offsetHeight;
        return 0 === t && 0 === n || !F.support.reliableHiddenOffsets && "none" === (e.style && e.style.display || F.css(e, "display"))
    }, F.expr.filters.visible = function (e) {
        return !F.expr.filters.hidden(e)
    }), F.each({margin: "", padding: "", border: "Width"}, function (e, t) {
        F.cssHooks[e + t] = {
            expand: function (n) {
                var i, r = "string" == typeof n ? n.split(" ") : [n], s = {};
                for (i = 0; i < 4; i++)s[e + Re[i] + t] = r[i] || r[i - 2] || r[0];
                return s
            }
        }
    });
    var He, Pe, $e = /%20/g, ze = /\[\]$/, We = /\r?\n/g, Xe = /#.*$/, Ue = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm, Ke = /^(?:color|date|datetime|datetime-local|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i, Ve = /^(?:about|app|app\-storage|.+\-extension|file|res|widget):$/, Ge = /^(?:GET|HEAD)$/, Ye = /^\/\//, Je = /\?/, Ze = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, et = /^(?:select|textarea)/i, tt = /\s+/, nt = /([?&])_=[^&]*/, it = /^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+))?)?/, rt = F.fn.load, st = {}, ot = {}, at = ["*/"] + ["*"];
    try {
        He = _.href
    } catch (lt) {
        He = A.createElement("a"), He.href = "", He = He.href
    }
    Pe = it.exec(He.toLowerCase()) || [], F.fn.extend({
        load: function (e, n, i) {
            if ("string" != typeof e && rt)return rt.apply(this, arguments);
            if (!this.length)return this;
            var r = e.indexOf(" ");
            if (r >= 0) {
                var s = e.slice(r, e.length);
                e = e.slice(0, r)
            }
            var o = "GET";
            n && (F.isFunction(n) ? (i = n, n = t) : "object" == typeof n && (n = F.param(n, F.ajaxSettings.traditional), o = "POST"));
            var a = this;
            return F.ajax({
                url: e, type: o, dataType: "html", data: n, complete: function (e, t, n) {
                    n = e.responseText, e.isResolved() && (e.done(function (e) {
                        n = e
                    }), a.html(s ? F("<div>").append(n.replace(Ze, "")).find(s) : n)), i && a.each(i, [n, t, e])
                }
            }), this
        }, serialize: function () {
            return F.param(this.serializeArray())
        }, serializeArray: function () {
            return this.map(function () {
                return this.elements ? F.makeArray(this.elements) : this
            }).filter(function () {
                return this.name && !this.disabled && (this.checked || et.test(this.nodeName) || Ke.test(this.type))
            }).map(function (e, t) {
                var n = F(this).val();
                return null == n ? null : F.isArray(n) ? F.map(n, function (e, n) {
                    return {name: t.name, value: e.replace(We, "\r\n")}
                }) : {name: t.name, value: n.replace(We, "\r\n")}
            }).get()
        }
    }), F.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "), function (e, t) {
        F.fn[t] = function (e) {
            return this.on(t, e)
        }
    }), F.each(["get", "post"], function (e, n) {
        F[n] = function (e, i, r, s) {
            return F.isFunction(i) && (s = s || r, r = i, i = t), F.ajax({
                type: n,
                url: e,
                data: i,
                success: r,
                dataType: s
            })
        }
    }), F.extend({
        getScript: function (e, n) {
            return F.get(e, t, n, "script")
        },
        getJSON: function (e, t, n) {
            return F.get(e, t, n, "json")
        },
        ajaxSetup: function (e, t) {
            return t ? p(e, F.ajaxSettings) : (t = e, e = F.ajaxSettings), p(e, t), e
        },
        ajaxSettings: {
            url: He,
            isLocal: Ve.test(Pe[1]),
            global: !0,
            type: "GET",
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            processData: !0,
            async: !0,
            accepts: {
                xml: "application/xml, text/xml",
                html: "text/html",
                text: "text/plain",
                json: "application/json, text/javascript",
                "*": at
            },
            contents: {xml: /xml/, html: /html/, json: /json/},
            responseFields: {xml: "responseXML", text: "responseText"},
            converters: {"* text": e.String, "text html": !0, "text json": F.parseJSON, "text xml": F.parseXML},
            flatOptions: {context: !0, url: !0}
        },
        ajaxPrefilter: d(st),
        ajaxTransport: d(ot),
        ajax: function (e, n) {
            function i(e, n, i, o) {
                if (2 !== N) {
                    N = 2, l && clearTimeout(l), a = t, s = o || "", k.readyState = e > 0 ? 4 : 0;
                    var f, h, d, w, T, C = n, q = i ? u(m, k, i) : t;
                    if (e >= 200 && e < 300 || 304 === e)if (m.ifModified && ((w = k.getResponseHeader("Last-Modified")) && (F.lastModified[r] = w), (T = k.getResponseHeader("Etag")) && (F.etag[r] = T)), 304 === e)C = "notmodified", f = !0; else try {
                        h = c(m, q), C = "success", f = !0
                    } catch (S) {
                        C = "parsererror", d = S
                    } else d = C, C && !e || (C = "error", e < 0 && (e = 0));
                    k.status = e, k.statusText = "" + (n || C), f ? y.resolveWith(g, [h, C, k]) : y.rejectWith(g, [k, C, d]), k.statusCode(x), x = t, p && v.trigger("ajax" + (f ? "Success" : "Error"), [k, m, f ? h : d]), b.fireWith(g, [k, C]), p && (v.trigger("ajaxComplete", [k, m]), --F.active || F.event.trigger("ajaxStop"))
                }
            }

            "object" == typeof e && (n = e, e = t), n = n || {};
            var r, s, o, a, l, f, p, d, m = F.ajaxSetup({}, n), g = m.context || m, v = g !== m && (g.nodeType || g instanceof F) ? F(g) : F.event, y = F.Deferred(), b = F.Callbacks("once memory"), x = m.statusCode || {}, w = {}, T = {}, N = 0, k = {
                readyState: 0,
                setRequestHeader: function (e, t) {
                    if (!N) {
                        var n = e.toLowerCase();
                        e = T[n] = T[n] || e, w[e] = t
                    }
                    return this
                },
                getAllResponseHeaders: function () {
                    return 2 === N ? s : null
                },
                getResponseHeader: function (e) {
                    var n;
                    if (2 === N) {
                        if (!o)for (o = {}; n = Ue.exec(s);)o[n[1].toLowerCase()] = n[2];
                        n = o[e.toLowerCase()]
                    }
                    return n === t ? null : n
                },
                overrideMimeType: function (e) {
                    return N || (m.mimeType = e), this
                },
                abort: function (e) {
                    return e = e || "abort", a && a.abort(e), i(0, e), this
                }
            };
            if (y.promise(k), k.success = k.done, k.error = k.fail, k.complete = b.add, k.statusCode = function (e) {
                    if (e) {
                        var t;
                        if (N < 2)for (t in e)x[t] = [x[t], e[t]]; else t = e[k.status], k.then(t, t)
                    }
                    return this
                }, m.url = ((e || m.url) + "").replace(Xe, "").replace(Ye, Pe[1] + "//"), m.dataTypes = F.trim(m.dataType || "*").toLowerCase().split(tt), null == m.crossDomain && (f = it.exec(m.url.toLowerCase()), m.crossDomain = !(!f || f[1] == Pe[1] && f[2] == Pe[2] && (f[3] || ("http:" === f[1] ? 80 : 443)) == (Pe[3] || ("http:" === Pe[1] ? 80 : 443)))), m.data && m.processData && "string" != typeof m.data && (m.data = F.param(m.data, m.traditional)), h(st, m, n, k), 2 === N)return !1;
            if (p = m.global, m.type = m.type.toUpperCase(), m.hasContent = !Ge.test(m.type), p && 0 === F.active++ && F.event.trigger("ajaxStart"), !m.hasContent && (m.data && (m.url += (Je.test(m.url) ? "&" : "?") + m.data, delete m.data), r = m.url, m.cache === !1)) {
                var C = F.now(), q = m.url.replace(nt, "$1_=" + C);
                m.url = q + (q === m.url ? (Je.test(m.url) ? "&" : "?") + "_=" + C : "")
            }
            (m.data && m.hasContent && m.contentType !== !1 || n.contentType) && k.setRequestHeader("Content-Type", m.contentType), m.ifModified && (r = r || m.url, F.lastModified[r] && k.setRequestHeader("If-Modified-Since", F.lastModified[r]), F.etag[r] && k.setRequestHeader("If-None-Match", F.etag[r])), k.setRequestHeader("Accept", m.dataTypes[0] && m.accepts[m.dataTypes[0]] ? m.accepts[m.dataTypes[0]] + ("*" !== m.dataTypes[0] ? ", " + at + "; q=0.01" : "") : m.accepts["*"]);
            for (d in m.headers)k.setRequestHeader(d, m.headers[d]);
            if (m.beforeSend && (m.beforeSend.call(g, k, m) === !1 || 2 === N))return k.abort(), !1;
            for (d in{success: 1, error: 1, complete: 1})k[d](m[d]);
            if (a = h(ot, m, n, k)) {
                k.readyState = 1, p && v.trigger("ajaxSend", [k, m]), m.async && m.timeout > 0 && (l = setTimeout(function () {
                    k.abort("timeout")
                }, m.timeout));
                try {
                    N = 1, a.send(w, i)
                } catch (S) {
                    if (!(N < 2))throw S;
                    i(-1, S)
                }
            } else i(-1, "No Transport");
            return k
        },
        param: function (e, n) {
            var i = [], r = function (e, t) {
                t = F.isFunction(t) ? t() : t, i[i.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
            };
            if (n === t && (n = F.ajaxSettings.traditional), F.isArray(e) || e.jquery && !F.isPlainObject(e))F.each(e, function () {
                r(this.name, this.value)
            }); else for (var s in e)f(s, e[s], n, r);
            return i.join("&").replace($e, "+")
        }
    }), F.extend({active: 0, lastModified: {}, etag: {}});
    var ct = F.now(), ut = /(\=)\?(&|$)|\?\?/i;
    F.ajaxSetup({
        jsonp: "callback", jsonpCallback: function () {
            return F.expando + "_" + ct++
        }
    }), F.ajaxPrefilter("json jsonp", function (t, n, i) {
        var r = "string" == typeof t.data && /^application\/x\-www\-form\-urlencoded/.test(t.contentType);
        if ("jsonp" === t.dataTypes[0] || t.jsonp !== !1 && (ut.test(t.url) || r && ut.test(t.data))) {
            var s, o = t.jsonpCallback = F.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, a = e[o], l = t.url, c = t.data, u = "$1" + o + "$2";
            return t.jsonp !== !1 && (l = l.replace(ut, u), t.url === l && (r && (c = c.replace(ut, u)), t.data === c && (l += (/\?/.test(l) ? "&" : "?") + t.jsonp + "=" + o))), t.url = l, t.data = c, e[o] = function (e) {
                s = [e]
            }, i.always(function () {
                e[o] = a, s && F.isFunction(a) && e[o](s[0])
            }), t.converters["script json"] = function () {
                return s || F.error(o + " was not called"), s[0]
            }, t.dataTypes[0] = "json", "script"
        }
    }), F.ajaxSetup({
        accepts: {script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},
        contents: {script: /javascript|ecmascript/},
        converters: {
            "text script": function (e) {
                return F.globalEval(e), e
            }
        }
    }), F.ajaxPrefilter("script", function (e) {
        e.cache === t && (e.cache = !1), e.crossDomain && (e.type = "GET", e.global = !1)
    }), F.ajaxTransport("script", function (e) {
        if (e.crossDomain) {
            var n, i = A.head || A.getElementsByTagName("head")[0] || A.documentElement;
            return {
                send: function (r, s) {
                    n = A.createElement("script"), n.async = "async", e.scriptCharset && (n.charset = e.scriptCharset), n.src = e.url, n.onload = n.onreadystatechange = function (e, r) {
                        (r || !n.readyState || /loaded|complete/.test(n.readyState)) && (n.onload = n.onreadystatechange = null, i && n.parentNode && i.removeChild(n), n = t, r || s(200, "success"))
                    }, i.insertBefore(n, i.firstChild)
                }, abort: function () {
                    n && n.onload(0, 1)
                }
            }
        }
    });
    var ft, pt = !!e.ActiveXObject && function () {
            for (var e in ft)ft[e](0, 1)
        }, ht = 0;
    F.ajaxSettings.xhr = e.ActiveXObject ? function () {
        return !this.isLocal && l() || a()
    } : l, function (e) {
        F.extend(F.support, {ajax: !!e, cors: !!e && "withCredentials" in e})
    }(F.ajaxSettings.xhr()), F.support.ajax && F.ajaxTransport(function (n) {
        if (!n.crossDomain || F.support.cors) {
            var i;
            return {
                send: function (r, s) {
                    var o, a, l = n.xhr();
                    if (n.username ? l.open(n.type, n.url, n.async, n.username, n.password) : l.open(n.type, n.url, n.async), n.xhrFields)for (a in n.xhrFields)l[a] = n.xhrFields[a];
                    n.mimeType && l.overrideMimeType && l.overrideMimeType(n.mimeType), !n.crossDomain && !r["X-Requested-With"] && (r["X-Requested-With"] = "XMLHttpRequest");
                    try {
                        for (a in r)l.setRequestHeader(a, r[a])
                    } catch (c) {
                    }
                    l.send(n.hasContent && n.data || null), i = function (e, r) {
                        var a, c, u, f, p;
                        try {
                            if (i && (r || 4 === l.readyState))if (i = t, o && (l.onreadystatechange = F.noop, pt && delete ft[o]), r)4 !== l.readyState && l.abort(); else {
                                a = l.status, u = l.getAllResponseHeaders(), f = {}, p = l.responseXML, p && p.documentElement && (f.xml = p);
                                try {
                                    f.text = l.responseText
                                } catch (e) {
                                }
                                try {
                                    c = l.statusText
                                } catch (h) {
                                    c = ""
                                }
                                a || !n.isLocal || n.crossDomain ? 1223 === a && (a = 204) : a = f.text ? 200 : 404
                            }
                        } catch (d) {
                            r || s(-1, d)
                        }
                        f && s(a, c, f, u)
                    }, n.async && 4 !== l.readyState ? (o = ++ht, pt && (ft || (ft = {}, F(e).unload(pt)), ft[o] = i), l.onreadystatechange = i) : i()
                }, abort: function () {
                    i && i(0, 1)
                }
            }
        }
    });
    var dt, mt, gt, vt, yt = {}, bt = /^(?:toggle|show|hide)$/, xt = /^([+\-]=)?([\d+.\-]+)([a-z%]*)$/i, wt = [["height", "marginTop", "marginBottom", "paddingTop", "paddingBottom"], ["width", "marginLeft", "marginRight", "paddingLeft", "paddingRight"], ["opacity"]];
    F.fn.extend({
        show: function (e, t, n) {
            var s, o;
            if (e || 0 === e)return this.animate(r("show", 3), e, t, n);
            for (var a = 0, l = this.length; a < l; a++)s = this[a], s.style && (o = s.style.display, !F._data(s, "olddisplay") && "none" === o && (o = s.style.display = ""), ("" === o && "none" === F.css(s, "display") || !F.contains(s.ownerDocument.documentElement, s)) && F._data(s, "olddisplay", i(s.nodeName)));
            for (a = 0; a < l; a++)s = this[a], s.style && (o = s.style.display, "" !== o && "none" !== o || (s.style.display = F._data(s, "olddisplay") || ""));
            return this
        }, hide: function (e, t, n) {
            if (e || 0 === e)return this.animate(r("hide", 3), e, t, n);
            for (var i, s, o = 0, a = this.length; o < a; o++)i = this[o], i.style && (s = F.css(i, "display"), "none" !== s && !F._data(i, "olddisplay") && F._data(i, "olddisplay", s));
            for (o = 0; o < a; o++)this[o].style && (this[o].style.display = "none");
            return this
        }, _toggle: F.fn.toggle, toggle: function (e, t, n) {
            var i = "boolean" == typeof e;
            return F.isFunction(e) && F.isFunction(t) ? this._toggle.apply(this, arguments) : null == e || i ? this.each(function () {
                var t = i ? e : F(this).is(":hidden");
                F(this)[t ? "show" : "hide"]()
            }) : this.animate(r("toggle", 3), e, t, n), this
        }, fadeTo: function (e, t, n, i) {
            return this.filter(":hidden").css("opacity", 0).show().end().animate({opacity: t}, e, n, i)
        }, animate: function (e, t, n, r) {
            function s() {
                o.queue === !1 && F._mark(this);
                var t, n, r, s, a, l, c, u, f, p, h, d = F.extend({}, o), m = 1 === this.nodeType, g = m && F(this).is(":hidden");
                d.animatedProperties = {};
                for (r in e)if (t = F.camelCase(r), r !== t && (e[t] = e[r], delete e[r]), (a = F.cssHooks[t]) && "expand" in a) {
                    l = a.expand(e[t]), delete e[t];
                    for (r in l)r in e || (e[r] = l[r])
                }
                for (t in e) {
                    if (n = e[t], F.isArray(n) ? (d.animatedProperties[t] = n[1], n = e[t] = n[0]) : d.animatedProperties[t] = d.specialEasing && d.specialEasing[t] || d.easing || "swing", "hide" === n && g || "show" === n && !g)return d.complete.call(this);
                    m && ("height" === t || "width" === t) && (d.overflow = [this.style.overflow, this.style.overflowX, this.style.overflowY], "inline" === F.css(this, "display") && "none" === F.css(this, "float") && (F.support.inlineBlockNeedsLayout && "inline" !== i(this.nodeName) ? this.style.zoom = 1 : this.style.display = "inline-block"))
                }
                null != d.overflow && (this.style.overflow = "hidden");
                for (r in e)s = new F.fx(this, d, r), n = e[r], bt.test(n) ? (h = F._data(this, "toggle" + r) || ("toggle" === n ? g ? "show" : "hide" : 0), h ? (F._data(this, "toggle" + r, "show" === h ? "hide" : "show"), s[h]()) : s[n]()) : (c = xt.exec(n), u = s.cur(), c ? (f = parseFloat(c[2]), p = c[3] || (F.cssNumber[r] ? "" : "px"), "px" !== p && (F.style(this, r, (f || 1) + p), u = (f || 1) / s.cur() * u, F.style(this, r, u + p)), c[1] && (f = ("-=" === c[1] ? -1 : 1) * f + u), s.custom(u, f, p)) : s.custom(u, n, ""));
                return !0
            }

            var o = F.speed(t, n, r);
            return F.isEmptyObject(e) ? this.each(o.complete, [!1]) : (e = F.extend({}, e), o.queue === !1 ? this.each(s) : this.queue(o.queue, s))
        }, stop: function (e, n, i) {
            return "string" != typeof e && (i = n, n = e, e = t), n && e !== !1 && this.queue(e || "fx", []), this.each(function () {
                function t(e, t, n) {
                    var r = t[n];
                    F.removeData(e, n, !0), r.stop(i)
                }

                var n, r = !1, s = F.timers, o = F._data(this);
                if (i || F._unmark(!0, this), null == e)for (n in o)o[n] && o[n].stop && n.indexOf(".run") === n.length - 4 && t(this, o, n); else o[n = e + ".run"] && o[n].stop && t(this, o, n);
                for (n = s.length; n--;)s[n].elem === this && (null == e || s[n].queue === e) && (i ? s[n](!0) : s[n].saveState(), r = !0, s.splice(n, 1));
                (!i || !r) && F.dequeue(this, e)
            })
        }
    }), F.each({
        slideDown: r("show", 1),
        slideUp: r("hide", 1),
        slideToggle: r("toggle", 1),
        fadeIn: {opacity: "show"},
        fadeOut: {opacity: "hide"},
        fadeToggle: {opacity: "toggle"}
    }, function (e, t) {
        F.fn[e] = function (e, n, i) {
            return this.animate(t, e, n, i)
        }
    }), F.extend({
        speed: function (e, t, n) {
            var i = e && "object" == typeof e ? F.extend({}, e) : {
                complete: n || !n && t || F.isFunction(e) && e,
                duration: e,
                easing: n && t || t && !F.isFunction(t) && t
            };
            return i.duration = F.fx.off ? 0 : "number" == typeof i.duration ? i.duration : i.duration in F.fx.speeds ? F.fx.speeds[i.duration] : F.fx.speeds._default, null != i.queue && i.queue !== !0 || (i.queue = "fx"), i.old = i.complete, i.complete = function (e) {
                F.isFunction(i.old) && i.old.call(this), i.queue ? F.dequeue(this, i.queue) : e !== !1 && F._unmark(this)
            }, i
        }, easing: {
            linear: function (e) {
                return e
            }, swing: function (e) {
                return -Math.cos(e * Math.PI) / 2 + .5
            }
        }, timers: [], fx: function (e, t, n) {
            this.options = t, this.elem = e, this.prop = n, t.orig = t.orig || {}
        }
    }), F.fx.prototype = {
        update: function () {
            this.options.step && this.options.step.call(this.elem, this.now, this), (F.fx.step[this.prop] || F.fx.step._default)(this)
        }, cur: function () {
            if (null != this.elem[this.prop] && (!this.elem.style || null == this.elem.style[this.prop]))return this.elem[this.prop];
            var e, t = F.css(this.elem, this.prop);
            return isNaN(e = parseFloat(t)) ? t && "auto" !== t ? t : 0 : e
        }, custom: function (e, n, i) {
            function r(e) {
                return s.step(e)
            }

            var s = this, a = F.fx;
            this.startTime = vt || o(), this.end = n, this.now = this.start = e, this.pos = this.state = 0, this.unit = i || this.unit || (F.cssNumber[this.prop] ? "" : "px"), r.queue = this.options.queue, r.elem = this.elem, r.saveState = function () {
                F._data(s.elem, "fxshow" + s.prop) === t && (s.options.hide ? F._data(s.elem, "fxshow" + s.prop, s.start) : s.options.show && F._data(s.elem, "fxshow" + s.prop, s.end))
            }, r() && F.timers.push(r) && !gt && (gt = setInterval(a.tick, a.interval))
        }, show: function () {
            var e = F._data(this.elem, "fxshow" + this.prop);
            this.options.orig[this.prop] = e || F.style(this.elem, this.prop), this.options.show = !0, e !== t ? this.custom(this.cur(), e) : this.custom("width" === this.prop || "height" === this.prop ? 1 : 0, this.cur()), F(this.elem).show()
        }, hide: function () {
            this.options.orig[this.prop] = F._data(this.elem, "fxshow" + this.prop) || F.style(this.elem, this.prop), this.options.hide = !0, this.custom(this.cur(), 0)
        }, step: function (e) {
            var t, n, i, r = vt || o(), s = !0, a = this.elem, l = this.options;
            if (e || r >= l.duration + this.startTime) {
                this.now = this.end, this.pos = this.state = 1, this.update(), l.animatedProperties[this.prop] = !0;
                for (t in l.animatedProperties)l.animatedProperties[t] !== !0 && (s = !1);
                if (s) {
                    if (null != l.overflow && !F.support.shrinkWrapBlocks && F.each(["", "X", "Y"], function (e, t) {
                            a.style["overflow" + t] = l.overflow[e]
                        }), l.hide && F(a).hide(), l.hide || l.show)for (t in l.animatedProperties)F.style(a, t, l.orig[t]), F.removeData(a, "fxshow" + t, !0), F.removeData(a, "toggle" + t, !0);
                    i = l.complete, i && (l.complete = !1, i.call(a))
                }
                return !1
            }
            return l.duration == 1 / 0 ? this.now = r : (n = r - this.startTime, this.state = n / l.duration, this.pos = F.easing[l.animatedProperties[this.prop]](this.state, n, 0, 1, l.duration), this.now = this.start + (this.end - this.start) * this.pos), this.update(), !0
        }
    }, F.extend(F.fx, {
        tick: function () {
            for (var e, t = F.timers, n = 0; n < t.length; n++)e = t[n], !e() && t[n] === e && t.splice(n--, 1);
            t.length || F.fx.stop()
        }, interval: 13, stop: function () {
            clearInterval(gt), gt = null
        }, speeds: {slow: 600, fast: 200, _default: 400}, step: {
            opacity: function (e) {
                F.style(e.elem, "opacity", e.now)
            }, _default: function (e) {
                e.elem.style && null != e.elem.style[e.prop] ? e.elem.style[e.prop] = e.now + e.unit : e.elem[e.prop] = e.now
            }
        }
    }), F.each(wt.concat.apply([], wt), function (e, t) {
        t.indexOf("margin") && (F.fx.step[t] = function (e) {
            F.style(e.elem, t, Math.max(0, e.now) + e.unit)
        })
    }), F.expr && F.expr.filters && (F.expr.filters.animated = function (e) {
        return F.grep(F.timers, function (t) {
            return e === t.elem
        }).length
    });
    var Tt, Nt = /^t(?:able|d|h)$/i, kt = /^(?:body|html)$/i;
    Tt = "getBoundingClientRect" in A.documentElement ? function (e, t, i, r) {
        try {
            r = e.getBoundingClientRect()
        } catch (s) {
        }
        if (!r || !F.contains(i, e))return r ? {top: r.top, left: r.left} : {top: 0, left: 0};
        var o = t.body, a = n(t), l = i.clientTop || o.clientTop || 0, c = i.clientLeft || o.clientLeft || 0, u = a.pageYOffset || F.support.boxModel && i.scrollTop || o.scrollTop, f = a.pageXOffset || F.support.boxModel && i.scrollLeft || o.scrollLeft, p = r.top + u - l, h = r.left + f - c;
        return {top: p, left: h}
    } : function (e, t, n) {
        for (var i, r = e.offsetParent, s = e, o = t.body, a = t.defaultView, l = a ? a.getComputedStyle(e, null) : e.currentStyle, c = e.offsetTop, u = e.offsetLeft; (e = e.parentNode) && e !== o && e !== n && (!F.support.fixedPosition || "fixed" !== l.position);)i = a ? a.getComputedStyle(e, null) : e.currentStyle, c -= e.scrollTop, u -= e.scrollLeft, e === r && (c += e.offsetTop, u += e.offsetLeft, F.support.doesNotAddBorder && (!F.support.doesAddBorderForTableAndCells || !Nt.test(e.nodeName)) && (c += parseFloat(i.borderTopWidth) || 0, u += parseFloat(i.borderLeftWidth) || 0), s = r, r = e.offsetParent), F.support.subtractsBorderForOverflowNotVisible && "visible" !== i.overflow && (c += parseFloat(i.borderTopWidth) || 0, u += parseFloat(i.borderLeftWidth) || 0), l = i;
        return "relative" !== l.position && "static" !== l.position || (c += o.offsetTop, u += o.offsetLeft), F.support.fixedPosition && "fixed" === l.position && (c += Math.max(n.scrollTop, o.scrollTop), u += Math.max(n.scrollLeft, o.scrollLeft)), {
            top: c,
            left: u
        }
    }, F.fn.offset = function (e) {
        if (arguments.length)return e === t ? this : this.each(function (t) {
            F.offset.setOffset(this, e, t)
        });
        var n = this[0], i = n && n.ownerDocument;
        return i ? n === i.body ? F.offset.bodyOffset(n) : Tt(n, i, i.documentElement) : null
    }, F.offset = {
        bodyOffset: function (e) {
            var t = e.offsetTop, n = e.offsetLeft;
            return F.support.doesNotIncludeMarginInBodyOffset && (t += parseFloat(F.css(e, "marginTop")) || 0, n += parseFloat(F.css(e, "marginLeft")) || 0), {
                top: t,
                left: n
            }
        }, setOffset: function (e, t, n) {
            var i = F.css(e, "position");
            "static" === i && (e.style.position = "relative");
            var r, s, o = F(e), a = o.offset(), l = F.css(e, "top"), c = F.css(e, "left"), u = ("absolute" === i || "fixed" === i) && F.inArray("auto", [l, c]) > -1, f = {}, p = {};
            u ? (p = o.position(), r = p.top, s = p.left) : (r = parseFloat(l) || 0, s = parseFloat(c) || 0), F.isFunction(t) && (t = t.call(e, n, a)), null != t.top && (f.top = t.top - a.top + r), null != t.left && (f.left = t.left - a.left + s), "using" in t ? t.using.call(e, f) : o.css(f)
        }
    }, F.fn.extend({
        position: function () {
            if (!this[0])return null;
            var e = this[0], t = this.offsetParent(), n = this.offset(), i = kt.test(t[0].nodeName) ? {
                top: 0,
                left: 0
            } : t.offset();
            return n.top -= parseFloat(F.css(e, "marginTop")) || 0, n.left -= parseFloat(F.css(e, "marginLeft")) || 0, i.top += parseFloat(F.css(t[0], "borderTopWidth")) || 0, i.left += parseFloat(F.css(t[0], "borderLeftWidth")) || 0, {
                top: n.top - i.top,
                left: n.left - i.left
            }
        }, offsetParent: function () {
            return this.map(function () {
                for (var e = this.offsetParent || A.body; e && !kt.test(e.nodeName) && "static" === F.css(e, "position");)e = e.offsetParent;
                return e
            })
        }
    }), F.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (e, i) {
        var r = /Y/.test(i);
        F.fn[e] = function (s) {
            return F.access(this, function (e, s, o) {
                var a = n(e);
                return o === t ? a ? i in a ? a[i] : F.support.boxModel && a.document.documentElement[s] || a.document.body[s] : e[s] : void(a ? a.scrollTo(r ? F(a).scrollLeft() : o, r ? o : F(a).scrollTop()) : e[s] = o)
            }, e, s, arguments.length, null)
        }
    }), F.each({Height: "height", Width: "width"}, function (e, n) {
        var i = "client" + e, r = "scroll" + e, s = "offset" + e;
        F.fn["inner" + e] = function () {
            var e = this[0];
            return e ? e.style ? parseFloat(F.css(e, n, "padding")) : this[n]() : null
        }, F.fn["outer" + e] = function (e) {
            var t = this[0];
            return t ? t.style ? parseFloat(F.css(t, n, e ? "margin" : "border")) : this[n]() : null
        }, F.fn[n] = function (e) {
            return F.access(this, function (e, n, o) {
                var a, l, c, u;
                return F.isWindow(e) ? (a = e.document, l = a.documentElement[i], F.support.boxModel && l || a.body && a.body[i] || l) : 9 === e.nodeType ? (a = e.documentElement, a[i] >= a[r] ? a[i] : Math.max(e.body[r], a[r], e.body[s], a[s])) : o === t ? (c = F.css(e, n), u = parseFloat(c), F.isNumeric(u) ? u : c) : void F(e).css(n, o)
            }, n, e, arguments.length, null)
        }
    }), e.jQuery = e.$ = F, "function" == typeof define && define.amd && define.amd.jQuery && define("jquery", [], function () {
        return F
    })
}(window), function () {
    function e() {
    }

    function t(e) {
        var t = e.length - 1;
        return function () {
            var n = w.call(arguments, 0, t), i = w.call(arguments, t);
            return e.apply(this, n.concat([i]))
        }
    }

    function n(e) {
        return t(function (t, n) {
            "function" != typeof t && (t = T(t));
            var i = function (e) {
                return t.apply(e, [e].concat(n))
            };
            return e.call(this, i)
        })
    }

    function i(e) {
        var t = w.call(arguments, 1);
        return function () {
            return e.apply(this, t)
        }
    }

    function r(e, t) {
        if (!t)throw new Error("prayer failed: " + e)
    }

    function s(e) {
        r("a direction was passed", e === k || e === C)
    }

    function o(e, t, n) {
        r("a parent is always present", e), r("leftward is properly set up", function () {
            return t ? t[C] === n && t.parent === e : e.ends[k] === n
        }()), r("rightward is properly set up", function () {
            return n ? n[k] === t && n.parent === e : e.ends[C] === t
        }())
    }

    function a() {
        window.console && console.warn('You are using the MathQuill API without specifying an interface version, which will fail in v1.0.0. You can fix this easily by doing this before doing anything else:\n\n    MathQuill = MathQuill.getInterface(1);\n    // now MathQuill.MathField() works like it used to\n\nSee also the "`dev` branch (2014–2015) → v0.10.0 Migration Guide" at\n  https://github.com/mathquill/mathquill/wiki/%60dev%60-branch-(2014%E2%80%932015)-%E2%86%92-v0.10.0-Migration-Guide')
    }

    function l(e) {
        return a(), Qe(e)
    }

    function c(t) {
        function n(e) {
            if (!e || !e.nodeType)return null;
            var t = q(e).children(".mq-root-block").attr(y), n = t && E.byId[t].controller;
            return n ? r[n.KIND_OF_MQ](n) : null
        }

        function i(e, t) {
            t && t.handlers && (t.handlers = {fns: t.handlers, APIClasses: r});
            for (var n in t)if (t.hasOwnProperty(n)) {
                var i = t[n], s = M[n];
                e[n] = s ? s(i) : i
            }
        }

        if (!(R <= t && t <= H))throw"Only interface versions between " + R + " and " + H + " supported. You specified: " + t;
        var r = {};
        n.L = k, n.R = C, n.config = function (e) {
            return i(Q.p, e), this
        }, n.registerEmbed = function (e, t) {
            if (!/^[a-z][a-z0-9]*$/i.test(e))throw"Embed name must start with letter and be only letters and digits";
            I[e] = t
        };
        var s = r.AbstractMathQuill = N(B, function (e) {
            e.init = function (e) {
                this.__controller = e, this.__options = e.options,
                    this.id = e.id, this.data = e.data
            }, e.__mathquillify = function (e) {
                var t = this.__controller, n = t.root, i = t.container;
                t.createTextarea();
                var r = i.addClass(e).contents().detach();
                n.jQ = q('<span class="mq-root-block"/>').attr(y, n.id).appendTo(i), this.latex(r.text()), this.revert = function () {
                    return i.empty().unbind(".mathquill").removeClass("mq-editable-field mq-math-mode mq-text-mode").append(r)
                }
            }, e.config = function (e) {
                return i(this.__options, e), this
            }, e.el = function () {
                return this.__controller.container[0]
            }, e.text = function () {
                return this.__controller.exportText()
            }, e.latex = function (e) {
                return arguments.length > 0 ? (this.__controller.renderLatexMath(e), this.__controller.blurred && this.__controller.cursor.hide().parent.blur(), this) : this.__controller.exportLatex()
            }, e.html = function () {
                return this.__controller.root.jQ.html().replace(/ mathquill-(?:command|block)-id="?\d+"?/g, "").replace(/<span class="?mq-cursor( mq-blink)?"?>.?<\/span>/i, "").replace(/ mq-hasCursor|mq-hasCursor ?/, "").replace(/ class=(""|(?= |>))/g, "")
            }, e.reflow = function () {
                return this.__controller.root.postOrder("reflow"), this
            }
        });
        n.prototype = s.prototype, r.EditableField = N(s, function (t, n) {
            t.__mathquillify = function () {
                return n.__mathquillify.apply(this, arguments), this.__controller.editable = !0, this.__controller.delegateMouseEvents(), this.__controller.editablesTextareaEvents(), this
            }, t.focus = function () {
                return this.__controller.textarea.focus(), this
            }, t.blur = function () {
                return this.__controller.textarea.blur(), this
            }, t.write = function (e) {
                return this.__controller.writeLatex(e), this.__controller.scrollHoriz(), this.__controller.blurred && this.__controller.cursor.hide().parent.blur(), this
            }, t.cmd = function (e) {
                var t = this.__controller.notify(), n = t.cursor;
                if (/^\\[a-z]+$/i.test(e)) {
                    e = e.slice(1);
                    var i = j[e];
                    i && (e = i(e), n.selection && e.replaces(n.replaceSelection()), e.createLeftOf(n.show()), this.__controller.scrollHoriz())
                } else n.parent.write(n, e);
                return t.blurred && n.hide().parent.blur(), this
            }, t.select = function () {
                var e = this.__controller;
                for (e.notify("move").cursor.insAtRightEnd(e.root); e.cursor[k];)e.selectLeft();
                return this
            }, t.clearSelection = function () {
                return this.__controller.cursor.clearSelection(), this
            }, t.moveToDirEnd = function (e) {
                return this.__controller.notify("move").cursor.insAtDirEnd(e, this.__controller.root), this
            }, t.moveToLeftEnd = function () {
                return this.moveToDirEnd(k)
            }, t.moveToRightEnd = function () {
                return this.moveToDirEnd(C)
            }, t.keystroke = function (t) {
                for (var t = t.replace(/^\s+|\s+$/g, "").split(/\s+/), n = 0; n < t.length; n += 1)this.__controller.keystroke(t[n], {preventDefault: e});
                return this
            }, t.typedText = function (e) {
                for (var t = 0; t < e.length; t += 1)this.__controller.typedText(e.charAt(t));
                return this
            }, t.dropEmbedded = function (e, t, n) {
                var i = e - q(window).scrollLeft(), r = t - q(window).scrollTop(), s = document.elementFromPoint(i, r);
                this.__controller.seek(q(s), e, t);
                var o = Fe().setOptions(n);
                o.createLeftOf(this.__controller.cursor)
            }
        }), n.EditableField = function () {
            throw"wtf don't call me, I'm 'abstract'"
        }, n.EditableField.prototype = r.EditableField.prototype;
        for (var o in F)(function (e, i) {
            var s = r[e] = i(r);
            n[e] = function (i, r) {
                var o = n(i);
                if (o instanceof s || !i || !i.nodeType)return o;
                var a = _(s.RootBlock(), q(i), Q());
                return a.KIND_OF_MQ = e, s(a).__mathquillify(r, t)
            }, n[e].prototype = s.prototype
        })(o, F[o]);
        return n
    }

    function u(e) {
        for (var t = "moveOutOf deleteOutOf selectOutOf upOutOf downOutOf".split(" "), n = 0; n < t.length; n += 1)(function (t) {
            e[t] = function (e) {
                this.controller.handle(t, e)
            }
        })(t[n]);
        e.reflow = function () {
            this.controller.handle("reflow"), this.controller.handle("edited"), this.controller.handle("edit")
        }
    }

    function f(e, t, n) {
        return N(Z, {ctrlSeq: e, htmlTemplate: "<" + t + " " + n + ">&0</" + t + ">"})
    }

    function p(e) {
        var t = this.parent, n = e;
        do {
            if (n[C])return e.insLeftOf(t);
            n = n.parent.parent
        } while (n !== t);
        e.insRightOf(t)
    }

    function h(e, t) {
        e.jQadd = function () {
            t.jQadd.apply(this, arguments), this.delimjQs = this.jQ.children(":first").add(this.jQ.children(":last")), this.contentjQ = this.jQ.children(":eq(1)")
        }, e.reflow = function () {
            var e = this.contentjQ.outerHeight() / parseFloat(this.contentjQ.css("fontSize"));
            ye(this.delimjQs, b(1 + .2 * (e - 1), 1.2), 1.2 * e)
        }
    }

    function d(e, t) {
        var t = t || e, n = Le[e], r = Le[t];
        D[e] = i(Ae, k, e, n, t, r), D[n] = i(Ae, C, e, n, t, r)
    }

    var m, g = window.jQuery, v = "mathquill-command-id", y = "mathquill-block-id", b = Math.min, x = Math.max, w = [].slice, T = t(function (e, n) {
        return t(function (t, i) {
            if (e in t)return t[e].apply(t, n.concat(i))
        })
    }), N = function (e, t, n) {
        function i(e) {
            return "object" == typeof e
        }

        function r(e) {
            return "function" == typeof e
        }

        function s() {
        }

        return function o(a, l) {
            function c() {
                var e = new u;
                return r(e.init) && e.init.apply(e, arguments), e
            }

            function u() {
            }

            l === n && (l = a, a = Object), c.Bare = u;
            var f, p = s[e] = a[e], h = u[e] = c[e] = c.p = new s;
            return h.constructor = c, c.mixin = function (t) {
                return u[e] = c[e] = o(c, t)[e], c
            }, (c.open = function (e) {
                if (f = {}, r(e) ? f = e.call(c, h, p, c, a) : i(e) && (f = e), i(f))for (var n in f)t.call(f, n) && (h[n] = f[n]);
                return r(h.init) || (h.init = a), c
            })(l)
        }
    }("prototype", {}.hasOwnProperty), k = -1, C = 1, q = N(g, function (e) {
        e.insDirOf = function (e, t) {
            return e === k ? this.insertBefore(t.first()) : this.insertAfter(t.last())
        }, e.insAtDirEnd = function (e, t) {
            return e === k ? this.prependTo(t) : this.appendTo(t)
        }
    }), S = N(function (e) {
        e.parent = 0, e[k] = 0, e[C] = 0, e.init = function (e, t, n) {
            this.parent = e, this[k] = t, this[C] = n
        }, this.copy = function (e) {
            return S(e.parent, e[k], e[C])
        }
    }), E = N(function (e) {
        function t() {
            return i += 1
        }

        e[k] = 0, e[C] = 0, e.parent = 0;
        var i = 0;
        this.byId = {}, e.init = function () {
            this.id = t(), E.byId[this.id] = this, this.ends = {}, this.ends[k] = 0, this.ends[C] = 0
        }, e.dispose = function () {
            delete E.byId[this.id]
        }, e.toString = function () {
            return "{{ MathQuill Node #" + this.id + " }}"
        }, e.jQ = q(), e.jQadd = function (e) {
            return this.jQ = this.jQ.add(e)
        }, e.jQize = function (e) {
            function t(e) {
                if (e.getAttribute) {
                    var n = e.getAttribute("mathquill-command-id"), i = e.getAttribute("mathquill-block-id");
                    n && E.byId[n].jQadd(e), i && E.byId[i].jQadd(e)
                }
                for (e = e.firstChild; e; e = e.nextSibling)t(e)
            }

            for (var e = q(e || this.html()), n = 0; n < e.length; n += 1)t(e[n]);
            return e
        }, e.createDir = function (e, t) {
            s(e);
            var n = this;
            return n.jQize(), n.jQ.insDirOf(e, t.jQ), t[e] = n.adopt(t.parent, t[k], t[C]), n
        }, e.createLeftOf = function (e) {
            return this.createDir(k, e)
        }, e.selectChildren = function (e, t) {
            return L(e, t)
        }, e.bubble = n(function (e) {
            for (var t = this; t; t = t.parent) {
                var n = e(t);
                if (n === !1)break
            }
            return this
        }), e.postOrder = n(function (e) {
            return function t(n) {
                n.eachChild(t), e(n)
            }(this), this
        }), e.isEmpty = function () {
            return 0 === this.ends[k] && 0 === this.ends[C]
        }, e.children = function () {
            return O(this.ends[k], this.ends[C])
        }, e.eachChild = function () {
            var e = this.children();
            return e.each.apply(e, arguments), this
        }, e.foldChildren = function (e, t) {
            return this.children().fold(e, t)
        }, e.withDirAdopt = function (e, t, n, i) {
            return O(this, this).withDirAdopt(e, t, n, i), this
        }, e.adopt = function (e, t, n) {
            return O(this, this).adopt(e, t, n), this
        }, e.disown = function () {
            return O(this, this).disown(), this
        }, e.remove = function () {
            return this.jQ.remove(), this.postOrder("dispose"), this.disown()
        }
    }), O = N(function (e) {
        e.init = function (e, t, n) {
            if (n === m && (n = k), s(n), r("no half-empty fragments", !e == !t), this.ends = {}, e) {
                r("withDir is passed to Fragment", e instanceof E), r("oppDir is passed to Fragment", t instanceof E), r("withDir and oppDir have the same parent", e.parent === t.parent), this.ends[n] = e, this.ends[-n] = t;
                var i = this.fold([], function (e, t) {
                    return e.push.apply(e, t.jQ.get()), e
                });
                this.jQ = this.jQ.add(i)
            }
        }, e.jQ = q(), e.withDirAdopt = function (e, t, n, i) {
            return e === k ? this.adopt(t, n, i) : this.adopt(t, i, n)
        }, e.adopt = function (e, t, n) {
            o(e, t, n);
            var i = this;
            i.disowned = !1;
            var r = i.ends[k];
            if (!r)return this;
            var s = i.ends[C];
            return t || (e.ends[k] = r), n ? n[k] = s : e.ends[C] = s, i.ends[C][C] = n, i.each(function (n) {
                n[k] = t, n.parent = e, t && (t[C] = n), t = n
            }), i
        }, e.disown = function () {
            var e = this, t = e.ends[k];
            if (!t || e.disowned)return e;
            e.disowned = !0;
            var n = e.ends[C], i = t.parent;
            return o(i, t[k], t), o(i, n, n[C]), t[k] ? t[k][C] = n[C] : i.ends[k] = n[C], n[C] ? n[C][k] = t[k] : i.ends[C] = t[k], e
        }, e.remove = function () {
            return this.jQ.remove(), this.each("postOrder", "dispose"), this.disown()
        }, e.each = n(function (e) {
            var t = this, n = t.ends[k];
            if (!n)return t;
            for (; n !== t.ends[C][C]; n = n[C]) {
                var i = e(n);
                if (i === !1)break
            }
            return t
        }), e.fold = function (e, t) {
            return this.each(function (n) {
                e = t.call(this, e, n)
            }), e
        }
    }), j = {}, D = {}, A = N(S, function (e) {
        e.init = function (e, t) {
            this.parent = e, this.options = t;
            var n = this.jQ = this._jQ = q('<span class="mq-cursor">&#8203;</span>');
            this.blink = function () {
                n.toggleClass("mq-blink")
            }, this.upDownCache = {}
        }, e.show = function () {
            return this.jQ = this._jQ.removeClass("mq-blink"), "intervalId" in this ? clearInterval(this.intervalId) : (this[C] ? this.selection && this.selection.ends[k][k] === this[k] ? this.jQ.insertBefore(this.selection.jQ) : this.jQ.insertBefore(this[C].jQ.first()) : this.jQ.appendTo(this.parent.jQ), this.parent.focus()), this.intervalId = setInterval(this.blink, 500), this
        }, e.hide = function () {
            return "intervalId" in this && clearInterval(this.intervalId), delete this.intervalId, this.jQ.detach(), this.jQ = q(), this
        }, e.withDirInsertAt = function (e, t, n, i) {
            var r = this.parent;
            this.parent = t, this[e] = n, this[-e] = i, r !== t && r.blur && r.blur()
        }, e.insDirOf = function (e, t) {
            return s(e), this.jQ.insDirOf(e, t.jQ), this.withDirInsertAt(e, t.parent, t[e], t), this.parent.jQ.addClass("mq-hasCursor"), this
        }, e.insLeftOf = function (e) {
            return this.insDirOf(k, e)
        }, e.insRightOf = function (e) {
            return this.insDirOf(C, e)
        }, e.insAtDirEnd = function (e, t) {
            return s(e), this.jQ.insAtDirEnd(e, t.jQ), this.withDirInsertAt(e, t, 0, t.ends[e]), t.focus(), this
        }, e.insAtLeftEnd = function (e) {
            return this.insAtDirEnd(k, e)
        }, e.insAtRightEnd = function (e) {
            return this.insAtDirEnd(C, e)
        }, e.jumpUpDown = function (e, t) {
            var n = this;
            n.upDownCache[e.id] = S.copy(n);
            var i = n.upDownCache[t.id];
            if (i)i[C] ? n.insLeftOf(i[C]) : n.insAtRightEnd(i.parent); else {
                var r = n.offset().left;
                t.seek(r, n)
            }
        }, e.offset = function () {
            var e = this, t = e.jQ.removeClass("mq-cursor").offset();
            return e.jQ.addClass("mq-cursor"), t
        }, e.unwrapGramp = function () {
            var e = this.parent.parent, t = e.parent, n = e[C], i = this, r = e[k];
            if (e.disown().eachChild(function (i) {
                    i.isEmpty() || (i.children().adopt(t, r, n).each(function (t) {
                        t.jQ.insertBefore(e.jQ.first())
                    }), r = i.ends[C])
                }), !this[C])if (this[k])this[C] = this[k][C]; else for (; !this[C];) {
                if (this.parent = this.parent[C], !this.parent) {
                    this[C] = e[C], this.parent = t;
                    break
                }
                this[C] = this.parent.ends[k]
            }
            this[C] ? this.insLeftOf(this[C]) : this.insAtRightEnd(t), e.jQ.remove(), e[k].siblingDeleted && e[k].siblingDeleted(i.options, C), e[C].siblingDeleted && e[C].siblingDeleted(i.options, k)
        }, e.startSelection = function () {
            for (var e = this.anticursor = S.copy(this), t = e.ancestors = {}, n = e; n.parent; n = n.parent)t[n.parent.id] = n
        }, e.endSelection = function () {
            delete this.anticursor
        }, e.select = function () {
            var e = this.anticursor;
            if (this[k] === e[k] && this.parent === e.parent)return !1;
            for (var t = this; t.parent; t = t.parent)if (t.parent.id in e.ancestors) {
                var n = t.parent;
                break
            }
            r("cursor and anticursor in the same tree", n);
            var i, s, o = e.ancestors[n.id], a = C;
            if (t[k] !== o)for (var l = t; l; l = l[C])if (l[C] === o[C]) {
                a = k, i = t, s = o;
                break
            }
            return a === C && (i = o, s = t), i instanceof S && (i = i[C]), s instanceof S && (s = s[k]), this.hide().selection = n.selectChildren(i, s), this.insDirOf(a, this.selection.ends[a]), this.selectionChanged(), !0
        }, e.clearSelection = function () {
            return this.selection && (this.selection.clear(), delete this.selection, this.selectionChanged()), this
        }, e.deleteSelection = function () {
            this.selection && (this[k] = this.selection.ends[k][k], this[C] = this.selection.ends[C][C], this.selection.remove(), this.selectionChanged(), delete this.selection)
        }, e.replaceSelection = function () {
            var e = this.selection;
            return e && (this[k] = e.ends[k][k], this[C] = e.ends[C][C], delete this.selection), e
        }
    }), L = N(O, function (e, t) {
        e.init = function () {
            t.init.apply(this, arguments), this.jQ = this.jQ.wrapAll('<span class="mq-selection"></span>').parent()
        }, e.adopt = function () {
            return this.jQ.replaceWith(this.jQ = this.jQ.children()), t.adopt.apply(this, arguments)
        }, e.clear = function () {
            return this.jQ.replaceWith(this.jQ[0].childNodes), this
        }, e.join = function (e) {
            return this.fold("", function (t, n) {
                return t + n[e]()
            })
        }
    }), _ = N(function (e) {
        e.init = function (e, t, n) {
            this.id = e.id, this.data = {}, this.root = e, this.container = t, this.options = n, e.controller = this, this.cursor = e.cursor = A(e, n)
        }, e.handle = function (e, t) {
            var n = this.options.handlers;
            if (n && n.fns[e]) {
                var i = n.APIClasses[this.KIND_OF_MQ](this);
                t === k || t === C ? n.fns[e](t, i) : n.fns[e](i)
            }
        };
        var t = [];
        this.onNotify = function (e) {
            t.push(e)
        }, e.notify = function () {
            for (var e = 0; e < t.length; e += 1)t[e].apply(this.cursor, arguments);
            return this
        }
    }), F = {}, Q = N(), M = {}, B = N(), I = {};
    l.prototype = B.p, l.interfaceVersion = function (e) {
        if (1 !== e)throw"Only interface version 1 supported. You specified: " + e;
        return a = function () {
            window.console && console.warn('You called MathQuill.interfaceVersion(1); to specify the interface version, which will fail in v1.0.0. You can fix this easily by doing this before doing anything else:\n\n    MathQuill = MathQuill.getInterface(1);\n    // now MathQuill.MathField() works like it used to\n\nSee also the "`dev` branch (2014–2015) → v0.10.0 Migration Guide" at\n  https://github.com/mathquill/mathquill/wiki/%60dev%60-branch-(2014%E2%80%932015)-%E2%86%92-v0.10.0-Migration-Guide')
        }, a(), l
    }, l.getInterface = c;
    var R = c.MIN = 1, H = c.MAX = 2;
    l.noConflict = function () {
        return window.MathQuill = P, l
    };
    var P = window.MathQuill;
    window.MathQuill = l;
    var $ = N(function (e, t, n) {
        function i(e, t) {
            throw e = e ? "'" + e + "'" : "EOF", "Parse Error: " + t + " at " + e
        }

        e.init = function (e) {
            this._ = e
        }, e.parse = function (e) {
            function t(e, t) {
                return t
            }

            return this.skip(a)._("" + e, t, i)
        }, e.or = function (e) {
            r("or is passed a parser", e instanceof n);
            var t = this;
            return n(function (n, i, r) {
                function s(t) {
                    return e._(n, i, r)
                }

                return t._(n, i, s)
            })
        }, e.then = function (e) {
            var t = this;
            return n(function (i, s, o) {
                function a(t, i) {
                    var a = e instanceof n ? e : e(i);
                    return r("a parser is returned", a instanceof n), a._(t, s, o)
                }

                return t._(i, a, o)
            })
        }, e.many = function () {
            var e = this;
            return n(function (t, n, i) {
                function r(e, n) {
                    return t = e, o.push(n), !0
                }

                function s() {
                    return !1
                }

                for (var o = []; e._(t, r, s););
                return n(t, o)
            })
        }, e.times = function (e, t) {
            arguments.length < 2 && (t = e);
            var i = this;
            return n(function (n, r, s) {
                function o(e, t) {
                    return u.push(t), n = e, !0
                }

                function a(e, t) {
                    return c = t, n = e, !1
                }

                function l(e, t) {
                    return !1
                }

                for (var c, u = [], f = !0, p = 0; p < e; p += 1)if (f = i._(n, o, a), !f)return s(n, c);
                for (; p < t && f; p += 1)f = i._(n, o, l);
                return r(n, u)
            })
        }, e.result = function (e) {
            return this.then(o(e))
        }, e.atMost = function (e) {
            return this.times(0, e)
        }, e.atLeast = function (e) {
            var t = this;
            return t.times(e).then(function (e) {
                return t.many().map(function (t) {
                    return e.concat(t)
                })
            })
        }, e.map = function (e) {
            return this.then(function (t) {
                return o(e(t))
            })
        }, e.skip = function (e) {
            return this.then(function (t) {
                return e.result(t)
            })
        };
        var s = (this.string = function (e) {
            var t = e.length, i = "expected '" + e + "'";
            return n(function (n, r, s) {
                var o = n.slice(0, t);
                return o === e ? r(n.slice(t), o) : s(n, i)
            })
        }, this.regex = function (e) {
            r("regexp parser is anchored", "^" === e.toString().charAt(1));
            var t = "expected " + e;
            return n(function (n, i, r) {
                var s = e.exec(n);
                if (s) {
                    var o = s[0];
                    return i(n.slice(o.length), o)
                }
                return r(n, t)
            })
        }), o = n.succeed = function (e) {
            return n(function (t, n) {
                return n(t, e)
            })
        }, a = (n.fail = function (e) {
            return n(function (t, n, i) {
                return i(t, e)
            })
        }, n.letter = s(/^[a-z]/i), n.letters = s(/^[a-z]*/i), n.digit = s(/^[0-9]/), n.digits = s(/^[0-9]*/), n.whitespace = s(/^\s+/), n.optWhitespace = s(/^\s*/), n.any = n(function (e, t, n) {
            return e ? t(e.slice(1), e.charAt(0)) : n(e, "expected any character")
        }), n.all = n(function (e, t, n) {
            return t("", e)
        }), n.eof = n(function (e, t, n) {
            return e ? n(e, "expected EOF") : t(e, e)
        }))
    }), z = function () {
        function t(e) {
            var t, i = e.which || e.keyCode, r = n[i], s = [];
            return e.ctrlKey && s.push("Ctrl"), e.originalEvent && e.originalEvent.metaKey && s.push("Meta"), e.altKey && s.push("Alt"), e.shiftKey && s.push("Shift"), t = r || String.fromCharCode(i), s.length || r ? (s.push(t), s.join("-")) : t
        }

        var n = {
            8: "Backspace",
            9: "Tab",
            10: "Enter",
            13: "Enter",
            16: "Shift",
            17: "Control",
            18: "Alt",
            20: "CapsLock",
            27: "Esc",
            32: "Spacebar",
            33: "PageUp",
            34: "PageDown",
            35: "End",
            36: "Home",
            37: "Left",
            38: "Up",
            39: "Right",
            40: "Down",
            45: "Insert",
            46: "Del",
            144: "NumLock"
        };
        return function (n, i) {
            function r(e) {
                x = e, clearTimeout(d), d = setTimeout(e)
            }

            function s(t) {
                x(), x = e, clearTimeout(d), y.val(t), t && y[0].select && y[0].select(), w = !!t
            }

            function o() {
                var e = y[0];
                return "selectionStart" in e && e.selectionStart !== e.selectionEnd
            }

            function a() {
                i.keystroke(t(m), m)
            }

            function l(t) {
                m = t, v = null, w && r(function (t) {
                    t && "focusout" === t.type || !y[0].select || y[0].select(), x = e, clearTimeout(d)
                }), a()
            }

            function c(e) {
                m && v && a(), v = e, r(u)
            }

            function u() {
                if (!o()) {
                    var e = y.val();
                    1 === e.length ? (y.val(""), i.typedText(e)) : e && y[0].select && y[0].select()
                }
            }

            function f() {
                m = v = null
            }

            function p(e) {
                y.focus(), r(h)
            }

            function h() {
                var e = y.val();
                y.val(""), e && i.paste(e)
            }

            var d, m = null, v = null, y = g(n), b = g(i.container || y), x = e;
            b.bind("keydown keypress input keyup focusout paste", function (e) {
                x(e)
            });
            var w = !1;
            return b.bind({keydown: l, keypress: c, focusout: f, paste: p}), {select: s}
        }
    }();
    _.open(function (e, t) {
        e.exportText = function () {
            return this.root.foldChildren("", function (e, t) {
                return e + t.text()
            })
        }
    }), _.open(function (e) {
        e.focusBlurEvents = function () {
            function e() {
                clearTimeout(n), s.selection && s.selection.jQ.addClass("mq-blur"), t()
            }

            function t() {
                s.hide().parent.blur(), i.container.removeClass("mq-focused"), q(window).off("blur", e)
            }

            var n, i = this, r = i.root, s = i.cursor;
            i.textarea.focus(function () {
                i.blurred = !1, clearTimeout(n), i.container.addClass("mq-focused"), s.parent || s.insAtRightEnd(r), s.selection ? (s.selection.jQ.removeClass("mq-blur"), i.selectionChanged()) : s.show()
            }).blur(function () {
                i.blurred = !0, n = setTimeout(function () {
                    r.postOrder("intentionalBlur"), s.clearSelection().endSelection(), t()
                }), q(window).on("blur", e)
            }), i.blurred = !0, s.hide().parent.blur()
        }
    }), _.open(function (e) {
        e.keystroke = function (e, t) {
            this.cursor.parent.keystroke(e, t, this)
        }
    }), E.open(function (e) {
        e.keystroke = function (e, t, n) {
            var i = n.cursor;
            switch (e) {
                case"Ctrl-Shift-Backspace":
                case"Ctrl-Backspace":
                    n.ctrlDeleteDir(k);
                    break;
                case"Shift-Backspace":
                case"Backspace":
                    n.backspace();
                    break;
                case"Esc":
                case"Tab":
                    return void n.escapeDir(C, e, t);
                case"Shift-Tab":
                case"Shift-Esc":
                    return void n.escapeDir(k, e, t);
                case"End":
                    n.notify("move").cursor.insAtRightEnd(i.parent);
                    break;
                case"Ctrl-End":
                    n.notify("move").cursor.insAtRightEnd(n.root);
                    break;
                case"Shift-End":
                    for (; i[C];)n.selectRight();
                    break;
                case"Ctrl-Shift-End":
                    for (; i[C] || i.parent !== n.root;)n.selectRight();
                    break;
                case"Home":
                    n.notify("move").cursor.insAtLeftEnd(i.parent);
                    break;
                case"Ctrl-Home":
                    n.notify("move").cursor.insAtLeftEnd(n.root);
                    break;
                case"Shift-Home":
                    for (; i[k];)n.selectLeft();
                    break;
                case"Ctrl-Shift-Home":
                    for (; i[k] || i.parent !== n.root;)n.selectLeft();
                    break;
                case"Left":
                    n.moveLeft();
                    break;
                case"Shift-Left":
                    n.selectLeft();
                    break;
                case"Ctrl-Left":
                    break;
                case"Right":
                    n.moveRight();
                    break;
                case"Shift-Right":
                    n.selectRight();
                    break;
                case"Ctrl-Right":
                    break;
                case"Up":
                    n.moveUp();
                    break;
                case"Down":
                    n.moveDown();
                    break;
                case"Shift-Up":
                    if (i[k])for (; i[k];)n.selectLeft(); else n.selectLeft();
                case"Shift-Down":
                    if (i[C])for (; i[C];)n.selectRight(); else n.selectRight();
                case"Ctrl-Up":
                    break;
                case"Ctrl-Down":
                    break;
                case"Ctrl-Shift-Del":
                case"Ctrl-Del":
                    n.ctrlDeleteDir(C);
                    break;
                case"Shift-Del":
                case"Del":
                    n.deleteForward();
                    break;
                case"Meta-A":
                case"Ctrl-A":
                    for (n.notify("move").cursor.insAtRightEnd(n.root); i[k];)n.selectLeft();
                    break;
                default:
                    return
            }
            t.preventDefault(), n.scrollHoriz()
        }, e.moveOutOf = e.moveTowards = e.deleteOutOf = e.deleteTowards = e.unselectInto = e.selectOutOf = e.selectTowards = function () {
            r("overridden or never called on this node")
        }
    }), _.open(function (e) {
        function t(e, t) {
            var n = e.notify("upDown").cursor, i = t + "Into", r = t + "OutOf";
            return n[C][i] ? n.insAtLeftEnd(n[C][i]) : n[k][i] ? n.insAtRightEnd(n[k][i]) : n.parent.bubble(function (e) {
                var t = e[r];
                if (t && ("function" == typeof t && (t = e[r](n)), t instanceof E && n.jumpUpDown(e, t), t !== !0))return !1
            }), e
        }

        this.onNotify(function (e) {
            "move" !== e && "upDown" !== e || this.show().clearSelection()
        }), e.escapeDir = function (e, t, n) {
            s(e);
            var i = this.cursor;
            if (i.parent !== this.root && n.preventDefault(), i.parent !== this.root)return i.parent.moveOutOf(e, i), this.notify("move")
        }, M.leftRightIntoCmdGoes = function (e) {
            if (e && "up" !== e && "down" !== e)throw'"up" or "down" required for leftRightIntoCmdGoes option, got "' + e + '"';
            return e
        }, e.moveDir = function (e) {
            s(e);
            var t = this.cursor, n = t.options.leftRightIntoCmdGoes;
            return t.selection ? t.insDirOf(e, t.selection.ends[e]) : t[e] ? t[e].moveTowards(e, t, n) : t.parent.moveOutOf(e, t, n), this.notify("move")
        }, e.moveLeft = function () {
            return this.moveDir(k)
        }, e.moveRight = function () {
            return this.moveDir(C)
        }, e.moveUp = function () {
            return t(this, "up")
        }, e.moveDown = function () {
            return t(this, "down")
        }, this.onNotify(function (e) {
            "upDown" !== e && (this.upDownCache = {})
        }), this.onNotify(function (e) {
            "edit" === e && this.show().deleteSelection()
        }), e.deleteDir = function (e) {
            s(e);
            var t = this.cursor, n = t.selection;
            return this.notify("edit"), n || (t[e] ? t[e].deleteTowards(e, t) : t.parent.deleteOutOf(e, t)), t[k].siblingDeleted && t[k].siblingDeleted(t.options, C), t[C].siblingDeleted && t[C].siblingDeleted(t.options, k), t.parent.bubble("reflow"), this
        }, e.ctrlDeleteDir = function (e) {
            s(e);
            var t = this.cursor;
            return !t[k] || t.selection ? ctrlr.deleteDir() : (this.notify("edit"), O(t.parent.ends[k], t[k]).remove(), t.insAtDirEnd(k, t.parent), t[k].siblingDeleted && t[k].siblingDeleted(t.options, C), t[C].siblingDeleted && t[C].siblingDeleted(t.options, k), t.parent.bubble("reflow"), this)
        }, e.backspace = function () {
            return this.deleteDir(k)
        }, e.deleteForward = function () {
            return this.deleteDir(C)
        }, this.onNotify(function (e) {
            "select" !== e && this.endSelection()
        }), e.selectDir = function (e) {
            var t = this.notify("select").cursor, n = t.selection;
            s(e), t.anticursor || t.startSelection();
            var i = t[e];
            i ? n && n.ends[e] === i && t.anticursor[-e] !== i ? i.unselectInto(e, t) : i.selectTowards(e, t) : t.parent.selectOutOf(e, t), t.clearSelection(), t.select() || t.show()
        }, e.selectLeft = function () {
            return this.selectDir(k)
        }, e.selectRight = function () {
            return this.selectDir(C)
        }
    });
    var W = function () {
        function e(e) {
            var t = Y();
            return e.adopt(t, 0, 0), t
        }

        function t(e) {
            for (var t = e[0] || Y(), n = 1; n < e.length; n += 1)e[n].children().adopt(t, t.ends[C], 0);
            return t
        }

        var n = $.string, i = $.regex, r = $.letter, s = $.any, o = $.optWhitespace, a = $.succeed, l = $.fail, c = r.map(function (e) {
            return se(e)
        }), u = i(/^[^${}\\_^]/).map(function (e) {
            return V(e)
        }), f = i(/^[^\\a-eg-zA-Z]/).or(n("\\").then(i(/^[a-z]+/i).or(i(/^\s+/).result(" ")).or(s))).then(function (e) {
            var t = j[e];
            return t ? t(e).parser() : l("unknown command: \\" + e)
        }), p = f.or(c).or(u), h = n("{").then(function () {
            return m
        }).skip(n("}")), d = o.then(h.or(p.map(e))), m = d.many().map(t).skip(o), g = n("[").then(d.then(function (e) {
            return "]" !== e.join("latex") ? a(e) : l()
        }).many().map(t).skip(o)).skip(n("]")), v = m;
        return v.block = d, v.optBlock = g, v
    }();
    _.open(function (e, t) {
        e.exportLatex = function () {
            return this.root.latex().replace(/(\\[a-z]+) (?![a-z])/gi, "$1")
        }, e.writeLatex = function (e) {
            var t = this.notify("edit").cursor, n = $.all, i = $.eof, r = W.skip(i).or(n.result(!1)).parse(e);
            if (r && !r.isEmpty()) {
                r.children().adopt(t.parent, t[k], t[C]);
                var s = r.jQize();
                s.insertBefore(t.jQ), t[k] = r.ends[C], r.finalizeInsert(t.options, t), r.ends[C][C].siblingCreated && r.ends[C][C].siblingCreated(t.options, k), r.ends[k][k].siblingCreated && r.ends[k][k].siblingCreated(t.options, C), t.parent.bubble("reflow")
            }
            return this
        }, e.renderLatexMath = function (e) {
            var t = this.root, n = this.cursor, i = $.all, r = $.eof, s = W.skip(r).or(i.result(!1)).parse(e);
            t.eachChild("postOrder", "dispose"), t.ends[k] = t.ends[C] = 0, s && s.children().adopt(t, 0, 0);
            var o = t.jQ;
            if (s) {
                var a = s.join("html");
                o.html(a), t.jQize(o.children()), t.finalizeInsert(n.options)
            } else o.empty();
            delete n.selection, n.insAtRightEnd(t)
        }, e.renderLatexText = function (e) {
            var t = this.root, n = this.cursor;
            t.jQ.children().slice(1).remove(), t.eachChild("postOrder", "dispose"), t.ends[k] = t.ends[C] = 0, delete n.selection, n.show().insAtRightEnd(t);
            var i = $.regex, r = $.string, s = $.eof, o = $.all, a = r("$").then(W).skip(r("$").or(s)).map(function (e) {
                var t = te(n);
                t.createBlocks();
                var i = t.ends[k];
                return e.children().adopt(i, 0, 0), t
            }), l = r("\\$").result("$"), c = l.or(i(/^[^$]/)).map(V), u = a.or(c).many(), f = u.skip(s).or(o.result(!1)).parse(e);
            if (f) {
                for (var p = 0; p < f.length; p += 1)f[p].adopt(t, t.ends[C], 0);
                t.jQize().appendTo(t.jQ), t.finalizeInsert(n.options)
            }
        }
    }), _.open(function (t) {
        t.delegateMouseEvents = function () {
            var t = this.root.jQ;
            this.container.bind("mousedown.mathquill", function (n) {
                function i(e) {
                    o = q(e.target)
                }

                function r(e) {
                    u.anticursor || u.startSelection(), c.seek(o, e.pageX, e.pageY).cursor.select(), o = m
                }

                function s(e) {
                    u.blink = f, u.selection || (c.editable ? u.show() : p.detach()), a.unbind("mousemove", i), q(e.target.ownerDocument).unbind("mousemove", r).unbind("mouseup", s)
                }

                var o, a = q(n.target).closest(".mq-root-block"), l = E.byId[a.attr(y) || t.attr(y)], c = l.controller, u = c.cursor, f = u.blink, p = c.textareaSpan, h = c.textarea;
                c.blurred && (c.editable || a.prepend(p), h.focus()), n.preventDefault(), n.target.unselectable = !0, u.blink = e, c.seek(q(n.target), n.pageX, n.pageY).cursor.startSelection(), a.mousemove(i), q(n.target.ownerDocument).mousemove(r).mouseup(s)
            })
        }
    }), _.open(function (e) {
        e.seek = function (e, t, n) {
            var i = this.notify("select").cursor;
            if (e) {
                var s = e.attr(y) || e.attr(v);
                if (!s) {
                    var o = e.parent();
                    s = o.attr(y) || o.attr(v)
                }
            }
            var a = s ? E.byId[s] : this.root;
            return r("nodeId is the id of some Node that exists", a), i.clearSelection().show(), a.seek(t, i), this.scrollHoriz(), this
        }
    }), _.open(function (e) {
        e.scrollHoriz = function () {
            var e = this.cursor, t = e.selection, n = this.root.jQ[0].getBoundingClientRect();
            if (t) {
                var i = t.jQ[0].getBoundingClientRect(), r = i.left - (n.left + 20), s = i.right - (n.right - 20);
                if (t.ends[k] === e[C])if (r < 0)var o = r; else {
                    if (!(s > 0))return;
                    if (i.left - s < n.left + 20)var o = r; else var o = s
                } else if (s > 0)var o = s; else {
                    if (!(r < 0))return;
                    if (i.right - r > n.right - 20)var o = s; else var o = r
                }
            } else {
                var a = e.jQ[0].getBoundingClientRect().left;
                if (a > n.right - 20)var o = a - (n.right - 20); else {
                    if (!(a < n.left + 20))return;
                    var o = a - (n.left + 20)
                }
            }
            this.root.jQ.stop().animate({scrollLeft: "+=" + o}, 100)
        }
    }), _.open(function (e) {
        Q.p.substituteTextarea = function () {
            return q("<textarea autocapitalize=off autocomplete=off autocorrect=off  placeholder=’请描述你遇到的问题（60字以内）‘ spellcheck=false x-palm-disable-ste-all=true />")[0]
        }, e.createTextarea = function () {
            var e = this.textareaSpan = q('<span class="mq-textarea"></span>'), t = this.options.substituteTextarea();
            if (!t.nodeType)throw"substituteTextarea() must return a DOM element, got " + t;
            t = this.textarea = q(t).appendTo(e);
            var n = this;
            n.cursor.selectionChanged = function () {
                n.selectionChanged()
            }, n.container.bind("copy", function () {
                n.setTextareaSelection()
            })
        }, e.selectionChanged = function () {
            var e = this;
            xe(e.container[0]), e.textareaSelectionTimeout === m && (e.textareaSelectionTimeout = setTimeout(function () {
                e.setTextareaSelection()
            }))
        }, e.setTextareaSelection = function () {
            this.textareaSelectionTimeout = m;
            var e = "";
            this.cursor.selection && (e = this.cursor.selection.join("latex"), this.options.statelessClipboard && (e = "$" + e + "$")), this.selectFn(e)
        }, e.staticMathTextareaEvents = function () {
            function e() {
                r.detach(), t.blurred = !0
            }

            var t = this, n = (t.root, t.cursor), i = t.textarea, r = t.textareaSpan;
            this.container.prepend('<span class="mq-selectable">$' + t.exportLatex() + "$</span>"), t.blurred = !0, i.bind("cut paste", !1).focus(function () {
                t.blurred = !1
            }).blur(function () {
                n.selection && n.selection.clear(), setTimeout(e)
            }), t.selectFn = function (e) {
                i.val(e), e && i.select()
            }
        }, e.editablesTextareaEvents = function () {
            var e = this, t = (e.root, e.cursor), n = e.textarea, i = e.textareaSpan, r = z(n, this);
            this.selectFn = function (e) {
                r.select(e)
            }, this.container.prepend(i).on("cut", function (n) {
                t.selection && setTimeout(function () {
                    e.notify("edit"), t.parent.bubble("reflow")
                })
            }), this.focusBlurEvents()
        }, e.typedText = function (e) {
            if ("\n" === e)return this.handle("enter");
            var t = this.notify().cursor;
            t.parent.write(t, e), this.scrollHoriz()
        }, e.paste = function (e) {
            this.options.statelessClipboard && (e = "$" === e.slice(0, 1) && "$" === e.slice(-1) ? e.slice(1, -1) : "\\text{" + e + "}"), this.writeLatex(e).cursor.show()
        }
    });
    var X = N(E, function (e, t) {
        e.finalizeInsert = function (e, t) {
            var n = this;
            n.postOrder("finalizeTree", e), n.postOrder("contactWeld", t), n.postOrder("blur"), n.postOrder("reflow"), n[C].siblingCreated && n[C].siblingCreated(e, k), n[k].siblingCreated && n[k].siblingCreated(e, C), n.bubble("reflow")
        }
    }), U = N(X, function (e, t) {
        e.init = function (e, n, i) {
            var r = this;
            t.init.call(r), r.ctrlSeq || (r.ctrlSeq = e), n && (r.htmlTemplate = n), i && (r.textTemplate = i)
        }, e.replaces = function (e) {
            e.disown(), this.replacedFragment = e
        }, e.isEmpty = function () {
            return this.foldChildren(!0, function (e, t) {
                return e && t.isEmpty()
            })
        }, e.parser = function () {
            var e = W.block, t = this;
            return e.times(t.numBlocks()).map(function (e) {
                t.blocks = e;
                for (var n = 0; n < e.length; n += 1)e[n].adopt(t, t.ends[C], 0);
                return t
            })
        }, e.createLeftOf = function (e) {
            var n = this, i = n.replacedFragment;
            n.createBlocks(), t.createLeftOf.call(n, e), i && (i.adopt(n.ends[k], 0, 0), i.jQ.appendTo(n.ends[k].jQ)), n.finalizeInsert(e.options), n.placeCursor(e)
        }, e.createBlocks = function () {
            for (var e = this, t = e.numBlocks(), n = e.blocks = Array(t), i = 0; i < t; i += 1) {
                var r = n[i] = Y();
                r.adopt(e, e.ends[C], 0)
            }
        }, e.placeCursor = function (e) {
            e.insAtRightEnd(this.foldChildren(this.ends[k], function (e, t) {
                return e.isEmpty() ? e : t
            }))
        }, e.moveTowards = function (e, t, n) {
            var i = n && this[n + "Into"];
            t.insAtDirEnd(-e, i || this.ends[-e])
        }, e.deleteTowards = function (e, t) {
            this.isEmpty() ? t[e] = this.remove()[e] : this.moveTowards(e, t, null)
        }, e.selectTowards = function (e, t) {
            t[-e] = this, t[e] = this[e]
        }, e.selectChildren = function () {
            return L(this, this)
        }, e.unselectInto = function (e, t) {
            t.insAtDirEnd(-e, t.anticursor.ancestors[this.id])
        }, e.seek = function (e, t) {
            function n(e) {
                var t = {};
                return t[k] = e.jQ.offset().left, t[C] = t[k] + e.jQ.outerWidth(), t
            }

            var i = this, r = n(i);
            if (e < r[k])return t.insLeftOf(i);
            if (e > r[C])return t.insRightOf(i);
            var s = r[k];
            i.eachChild(function (o) {
                var a = n(o);
                return e < a[k] ? (e - s < a[k] - e ? o[k] ? t.insAtRightEnd(o[k]) : t.insLeftOf(i) : t.insAtLeftEnd(o), !1) : e > a[C] ? void(o[C] ? s = a[C] : r[C] - e < e - a[C] ? t.insRightOf(i) : t.insAtRightEnd(o)) : (o.seek(e, t), !1)
            })
        }, e.numBlocks = function () {
            var e = this.htmlTemplate.match(/&\d+/g);
            return e ? e.length : 0
        }, e.html = function () {
            var e = this, t = e.blocks, n = " mathquill-command-id=" + e.id, i = e.htmlTemplate.match(/<[^<>]+>|[^<>]+/g);
            r("no unmatched angle brackets", i.join("") === this.htmlTemplate);
            for (var s = 0, o = i[0]; o; s += 1, o = i[s])if ("/>" === o.slice(-2))i[s] = o.slice(0, -2) + n + "/>"; else if ("<" === o.charAt(0)) {
                r("not an unmatched top-level close tag", "/" !== o.charAt(1)), i[s] = o.slice(0, -1) + n + ">";
                var a = 1;
                do s += 1, o = i[s], r("no missing close tags", o), "</" === o.slice(0, 2) ? a -= 1 : "<" === o.charAt(0) && "/>" !== o.slice(-2) && (a += 1); while (a > 0)
            }
            return i.join("").replace(/>&(\d+)/g, function (e, n) {
                return " mathquill-block-id=" + t[n].id + ">" + t[n].join("html")
            })
        }, e.latex = function () {
            return this.foldChildren(this.ctrlSeq, function (e, t) {
                return e + "{" + (t.latex() || " ") + "}"
            })
        }, e.textTemplate = [""], e.text = function () {
            var e = this, t = 0;
            return e.foldChildren(e.textTemplate[t], function (n, i) {
                t += 1;
                var r = i.text();
                return n && "(" === e.textTemplate[t] && "(" === r[0] && ")" === r.slice(-1) ? n + r.slice(1, -1) + e.textTemplate[t] : n + i.text() + (e.textTemplate[t] || "")
            })
        }
    }), K = N(U, function (t, n) {
        t.init = function (e, t, i) {
            i || (i = e && e.length > 1 ? e.slice(1) : e), n.init.call(this, e, t, [i])
        }, t.parser = function () {
            return $.succeed(this)
        }, t.numBlocks = function () {
            return 0
        }, t.replaces = function (e) {
            e.remove()
        }, t.createBlocks = e, t.moveTowards = function (e, t) {
            t.jQ.insDirOf(e, this.jQ), t[-e] = this, t[e] = this[e]
        }, t.deleteTowards = function (e, t) {
            t[e] = this.remove()[e]
        }, t.seek = function (e, t) {
            e - this.jQ.offset().left < this.jQ.outerWidth() / 2 ? t.insLeftOf(this) : t.insRightOf(this)
        }, t.latex = function () {
            return this.ctrlSeq
        }, t.text = function () {
            return this.textTemplate
        }, t.placeCursor = e, t.isEmpty = function () {
            return !0
        }
    }), V = N(K, function (e, t) {
        e.init = function (e, n) {
            t.init.call(this, e, "<span>" + (n || e) + "</span>")
        }
    }), G = N(K, function (e, t) {
        e.init = function (e, n, i) {
            t.init.call(this, e, '<span class="mq-binary-operator">' + n + "</span>", i)
        }
    }), Y = N(X, function (e, t) {
        e.join = function (e) {
            return this.foldChildren("", function (t, n) {
                return t + n[e]()
            })
        }, e.html = function () {
            return this.join("html")
        }, e.latex = function () {
            return this.join("latex")
        }, e.text = function () {
            return this.ends[k] === this.ends[C] && 0 !== this.ends[k] ? this.ends[k].text() : this.join("text")
        }, e.keystroke = function (e, n, i) {
            return !i.options.spaceBehavesLikeTab || "Spacebar" !== e && "Shift-Spacebar" !== e ? t.keystroke.apply(this, arguments) : (n.preventDefault(), void i.escapeDir("Shift-Spacebar" === e ? k : C, e, n))
        }, e.moveOutOf = function (e, t, n) {
            var i = n && this.parent[n + "Into"];
            !i && this[e] ? t.insAtDirEnd(-e, this[e]) : t.insDirOf(e, this.parent)
        }, e.selectOutOf = function (e, t) {
            t.insDirOf(e, this.parent)
        }, e.deleteOutOf = function (e, t) {
            t.unwrapGramp()
        }, e.seek = function (e, t) {
            var n = this.ends[C];
            if (!n || n.jQ.offset().left + n.jQ.outerWidth() < e)return t.insAtRightEnd(this);
            if (e < this.ends[k].jQ.offset().left)return t.insAtLeftEnd(this);
            for (; e < n.jQ.offset().left;)n = n[k];
            return n.seek(e, t)
        }, e.chToCmd = function (e) {
            var t;
            return e.match(/^[a-eg-zA-Z]$/) ? se(e) : /^\d$/.test(e) ? ie(e) : (t = D[e] || j[e]) ? t(e) : V(e)
        }, e.write = function (e, t) {
            var n = this.chToCmd(t);
            e.selection && n.replaces(e.replaceSelection()), n.createLeftOf(e.show())
        }, e.focus = function () {
            return this.jQ.addClass("mq-hasCursor"), this.jQ.removeClass("mq-empty"), this
        }, e.blur = function () {
            return this.jQ.removeClass("mq-hasCursor"), this.isEmpty() && this.jQ.addClass("mq-empty"), this
        }
    });
    F.StaticMath = function (e) {
        return N(e.AbstractMathQuill, function (t, n) {
            this.RootBlock = Y, t.__mathquillify = function () {
                return n.__mathquillify.call(this, "mq-math-mode"), this.__controller.delegateMouseEvents(), this.__controller.staticMathTextareaEvents(), this
            }, t.init = function () {
                n.init.apply(this, arguments), this.__controller.root.postOrder("registerInnerField", this.innerFields = [], e.MathField)
            }, t.latex = function () {
                var t = n.latex.apply(this, arguments);
                return arguments.length > 0 && this.__controller.root.postOrder("registerInnerField", this.innerFields = [], e.MathField), t
            }
        })
    };
    var J = N(Y, u);
    F.MathField = function (t) {
        return N(t.EditableField, function (t, n) {
            this.RootBlock = J, t.__mathquillify = function (t, i) {
                return this.config(t), i > 1 && (this.__controller.root.reflow = e), n.__mathquillify.call(this, "mq-editable-field mq-math-mode"), delete this.__controller.root.reflow, this
            }
        })
    };
    var Z = N(E, function (e, t) {
        function n(e) {
            e.jQ[0].normalize();
            var t = e.jQ[0].firstChild;
            r("only node in TextBlock span is Text node", 3 === t.nodeType);
            var n = ee(t.data);
            return n.jQadd(t), e.children().disown(), n.adopt(e, 0, 0)
        }

        e.ctrlSeq = "\\text", e.replaces = function (e) {
            e instanceof O ? this.replacedText = e.remove().jQ.text() : "string" == typeof e && (this.replacedText = e)
        }, e.jQadd = function (e) {
            t.jQadd.call(this, e), this.ends[k] && this.ends[k].jQadd(this.jQ[0].firstChild)
        }, e.createLeftOf = function (e) {
            var n = this;
            if (t.createLeftOf.call(this, e), n[C].siblingCreated && n[C].siblingCreated(e.options, k), n[k].siblingCreated && n[k].siblingCreated(e.options, C), n.bubble("reflow"), e.insAtRightEnd(n), n.replacedText)for (var i = 0; i < n.replacedText.length; i += 1)n.write(e, n.replacedText.charAt(i))
        }, e.parser = function () {
            var e = this, t = $.string, n = $.regex, i = $.optWhitespace;
            return i.then(t("{")).then(n(/^[^}]*/)).skip(t("}")).map(function (t) {
                return ee(t).adopt(e, 0, 0), e
            })
        }, e.textContents = function () {
            return this.foldChildren("", function (e, t) {
                return e + t.text
            })
        }, e.text = function () {
            return '"' + this.textContents() + '"'
        }, e.latex = function () {
            return "\\text{" + this.textContents() + "}"
        }, e.html = function () {
            return '<span class="mq-text-mode" mathquill-command-id=' + this.id + ">" + this.textContents() + "</span>"
        }, e.moveTowards = function (e, t) {
            t.insAtDirEnd(-e, this)
        }, e.moveOutOf = function (e, t) {
            t.insDirOf(e, this)
        }, e.unselectInto = e.moveTowards, e.selectTowards = U.prototype.selectTowards, e.deleteTowards = U.prototype.deleteTowards, e.selectOutOf = function (e, t) {
            t.insDirOf(e, this)
        }, e.deleteOutOf = function (e, t) {
            this.isEmpty() && t.insRightOf(this)
        }, e.write = function (e, n) {
            if (e.show().deleteSelection(), "$" !== n)e[k] ? e[k].appendText(n) : ee(n).createLeftOf(e); else if (this.isEmpty())e.insRightOf(this), V("\\$", "$").createLeftOf(e); else if (e[C])if (e[k]) {
                var i = Z(), r = this.ends[k];
                r.disown(), r.adopt(i, 0, 0), e.insLeftOf(this), t.createLeftOf.call(i, e)
            } else e.insLeftOf(this); else e.insRightOf(this)
        }, e.seek = function (e, t) {
            t.hide();
            var i = n(this), r = this.jQ.width() / this.text.length, s = Math.round((e - this.jQ.offset().left) / r);
            s <= 0 ? t.insAtLeftEnd(this) : s >= i.text.length ? t.insAtRightEnd(this) : t.insLeftOf(i.splitRight(s));
            for (var o = e - t.show().offset().left, a = o && o < 0 ? k : C, l = a; t[a] && o * l > 0;)t[a].moveTowards(a, t), l = o, o = e - t.offset().left;
            if (a * o < -a * l && t[-a].moveTowards(-a, t), t.anticursor) {
                if (t.anticursor.parent === this) {
                    var c = t[k] && t[k].text.length;
                    if (this.anticursorPosition === c)t.anticursor = S.copy(t); else {
                        if (this.anticursorPosition < c) {
                            var u = t[k].splitRight(this.anticursorPosition);
                            t[k] = u
                        } else var u = t[C].splitRight(this.anticursorPosition - c);
                        t.anticursor = S(this, u[k], u)
                    }
                }
            } else this.anticursorPosition = t[k] && t[k].text.length
        }, e.blur = function () {
            Y.prototype.blur.call(this), n(this)
        }, e.focus = Y.prototype.focus
    }), ee = N(E, function (e, t) {
        function n(e, t) {
            return t.charAt(e === k ? 0 : -1 + t.length)
        }

        e.init = function (e) {
            t.init.call(this), this.text = e
        }, e.jQadd = function (e) {
            this.dom = e, this.jQ = q(e)
        }, e.jQize = function () {
            return this.jQadd(document.createTextNode(this.text))
        }, e.appendText = function (e) {
            this.text += e, this.dom.appendData(e)
        }, e.prependText = function (e) {
            this.text = e + this.text, this.dom.insertData(0, e)
        }, e.insTextAtDirEnd = function (e, t) {
            s(t), t === C ? this.appendText(e) : this.prependText(e)
        }, e.splitRight = function (e) {
            var t = ee(this.text.slice(e)).adopt(this.parent, this, this[C]);
            return t.jQadd(this.dom.splitText(e)), this.text = this.text.slice(0, e), t
        }, e.moveTowards = function (e, t) {
            s(e);
            var i = n(-e, this.text), r = this[-e];
            return r ? r.insTextAtDirEnd(i, e) : ee(i).createDir(-e, t), this.deleteTowards(e, t)
        }, e.latex = function () {
            return this.text
        }, e.deleteTowards = function (e, t) {
            this.text.length > 1 ? e === C ? (this.dom.deleteData(0, 1), this.text = this.text.slice(1)) : (this.dom.deleteData(-1 + this.text.length, 1), this.text = this.text.slice(0, -1)) : (this.remove(), this.jQ.remove(), t[e] = this[e])
        }, e.selectTowards = function (e, t) {
            s(e);
            var i = t.anticursor, r = n(-e, this.text);
            if (i[e] === this) {
                var o = ee(r).createDir(e, t);
                i[e] = o, t.insDirOf(e, o)
            } else {
                var a = this[-e];
                if (a)a.insTextAtDirEnd(r, e); else {
                    var o = ee(r).createDir(-e, t);
                    o.jQ.insDirOf(-e, t.selection.jQ)
                }
                1 === this.text.length && i[-e] === this && (i[-e] = this[-e])
            }
            return this.deleteTowards(e, t)
        }
    });
    D.$ = j.text = j.textnormal = j.textrm = j.textup = j.textmd = Z, j.em = j.italic = j.italics = j.emph = j.textit = j.textsl = f("\\textit", "i", 'class="mq-text-mode"'), j.strong = j.bold = j.textbf = f("\\textbf", "b", 'class="mq-text-mode"'), j.sf = j.textsf = f("\\textsf", "span", 'class="mq-sans-serif mq-text-mode"'), j.tt = j.texttt = f("\\texttt", "span", 'class="mq-monospace mq-text-mode"'), j.textsc = f("\\textsc", "span", 'style="font-variant:small-caps" class="mq-text-mode"'), j.uppercase = f("\\uppercase", "span", 'style="text-transform:uppercase" class="mq-text-mode"'), j.lowercase = f("\\lowercase", "span", 'style="text-transform:lowercase" class="mq-text-mode"');
    var te = N(U, function (e, t) {
        e.init = function (e) {
            t.init.call(this, "$"), this.cursor = e
        }, e.htmlTemplate = '<span class="mq-math-mode">&0</span>', e.createBlocks = function () {
            t.createBlocks.call(this), this.ends[k].cursor = this.cursor, this.ends[k].write = function (e, t) {
                "$" !== t ? Y.prototype.write.call(this, e, t) : this.isEmpty() ? (e.insRightOf(this.parent), this.parent.deleteTowards(dir, e), V("\\$", "$").createLeftOf(e.show())) : e[C] ? e[k] ? Y.prototype.write.call(this, e, t) : e.insLeftOf(this.parent) : e.insRightOf(this.parent)
            }
        }, e.latex = function () {
            return "$" + this.ends[k].latex() + "$"
        }
    }), ne = N(J, function (e, t) {
        e.keystroke = function (e) {
            if ("Spacebar" !== e && "Shift-Spacebar" !== e)return t.keystroke.apply(this, arguments)
        }, e.write = function (e, t) {
            if (e.show().deleteSelection(), "$" === t)te(e).createLeftOf(e); else {
                var n;
                "<" === t ? n = "&lt;" : ">" === t && (n = "&gt;"), V(t, n).createLeftOf(e)
            }
        }
    });
    F.TextField = function (e) {
        return N(e.EditableField, function (e, t) {
            this.RootBlock = ne, e.__mathquillify = function () {
                return t.__mathquillify.call(this, "mq-editable-field mq-text-mode")
            }, e.latex = function (e) {
                return arguments.length > 0 ? (this.__controller.renderLatexText(e), this.__controller.blurred && this.__controller.cursor.hide().parent.blur(), this) : this.__controller.exportLatex()
            }
        })
    };
    D["\\"] = N(U, function (e, t) {
        e.ctrlSeq = "\\", e.replaces = function (e) {
            this._replacedFragment = e.disown(), this.isEmpty = function () {
                return !1
            }
        }, e.htmlTemplate = '<span class="mq-latex-command-input mq-non-leaf">\\<span>&0</span></span>', e.textTemplate = ["\\"], e.createBlocks = function () {
            t.createBlocks.call(this), this.ends[k].focus = function () {
                return this.parent.jQ.addClass("mq-hasCursor"), this.isEmpty() && this.parent.jQ.removeClass("mq-empty"), this
            }, this.ends[k].blur = function () {
                return this.parent.jQ.removeClass("mq-hasCursor"), this.isEmpty() && this.parent.jQ.addClass("mq-empty"), this
            }, this.ends[k].write = function (e, t) {
                e.show().deleteSelection(), t.match(/[a-z]/i) ? V(t).createLeftOf(e) : (this.parent.renderCommand(e), "\\" === t && this.isEmpty() || this.parent.parent.write(e, t))
            }, this.ends[k].keystroke = function (e, n, i) {
                return "Tab" === e || "Enter" === e || "Spacebar" === e ? (this.parent.renderCommand(i.cursor), void n.preventDefault()) : t.keystroke.apply(this, arguments)
            }
        }, e.createLeftOf = function (e) {
            if (t.createLeftOf.call(this, e), this._replacedFragment) {
                var n = this.jQ[0];
                this.jQ = this._replacedFragment.jQ.addClass("mq-blur").bind("mousedown mousemove", function (e) {
                    return q(e.target = n).trigger(e), !1
                }).insertBefore(this.jQ).add(this.jQ)
            }
        }, e.latex = function () {
            return "\\" + this.ends[k].latex() + " "
        }, e.renderCommand = function (e) {
            this.jQ = this.jQ.last(), this.remove(), this[C] ? e.insLeftOf(this[C]) : e.insAtRightEnd(this.parent);
            var t = this.ends[k].latex();
            t || (t = " ");
            var n = j[t];
            n ? (n = n(t), this._replacedFragment && n.replaces(this._replacedFragment), n.createLeftOf(e)) : (n = Z(), n.replaces(t), n.createLeftOf(e), e.insRightOf(n), this._replacedFragment && this._replacedFragment.remove())
        }
    });
    j.notin = j.cong = j.equiv = j.oplus = j.otimes = N(G, function (e, t) {
        e.init = function (e) {
            t.init.call(this, "\\" + e + " ", "&" + e + ";")
        }
    }), j["≠"] = j.ne = j.neq = i(G, "\\ne ", "&ne;"), j.ast = j.star = j.loast = j.lowast = i(G, "\\ast ", "&lowast;"), j.therefor = j.therefore = i(G, "\\therefore ", "&there4;"), j.cuz = j.because = i(G, "\\because ", "&#8757;"), j.prop = j.propto = i(G, "\\propto ", "&prop;"), j["≈"] = j.asymp = j.approx = i(G, "\\approx ", "&asymp;"), j.isin = j["in"] = i(G, "\\in ", "&isin;"), j.ni = j.contains = i(G, "\\ni ", "&ni;"), j.notni = j.niton = j.notcontains = j.doesnotcontain = i(G, "\\not\\ni ", "&#8716;"), j.sub = j.subset = i(G, "\\subset ", "&sub;"), j.sup = j.supset = j.superset = i(G, "\\supset ", "&sup;"), j.nsub = j.notsub = j.nsubset = j.notsubset = i(G, "\\not\\subset ", "&#8836;"), j.nsup = j.notsup = j.nsupset = j.notsupset = j.nsuperset = j.notsuperset = i(G, "\\not\\supset ", "&#8837;"), j.sube = j.subeq = j.subsete = j.subseteq = i(G, "\\subseteq ", "&sube;"), j.supe = j.supeq = j.supsete = j.supseteq = j.supersete = j.superseteq = i(G, "\\supseteq ", "&supe;"), j.nsube = j.nsubeq = j.notsube = j.notsubeq = j.nsubsete = j.nsubseteq = j.notsubsete = j.notsubseteq = i(G, "\\not\\subseteq ", "&#8840;"), j.nsupe = j.nsupeq = j.notsupe = j.notsupeq = j.nsupsete = j.nsupseteq = j.notsupsete = j.notsupseteq = j.nsupersete = j.nsuperseteq = j.notsupersete = j.notsuperseteq = i(G, "\\not\\supseteq ", "&#8841;"), j.N = j.naturals = j.Naturals = i(V, "\\mathbb{N}", "&#8469;"), j.P = j.primes = j.Primes = j.projective = j.Projective = j.probability = j.Probability = i(V, "\\mathbb{P}", "&#8473;"), j.Z = j.integers = j.Integers = i(V, "\\mathbb{Z}", "&#8484;"), j.Q = j.rationals = j.Rationals = i(V, "\\mathbb{Q}", "&#8474;"), j.R = j.reals = j.Reals = i(V, "\\mathbb{R}", "&#8477;"), j.C = j.complex = j.Complex = j.complexes = j.Complexes = j.complexplane = j.Complexplane = j.ComplexPlane = i(V, "\\mathbb{C}", "&#8450;"), j.H = j.Hamiltonian = j.quaternions = j.Quaternions = i(V, "\\mathbb{H}", "&#8461;"), j.quad = j.emsp = i(V, "\\quad ", "    "), j.qquad = i(V, "\\qquad ", "        "), j.diamond = i(V, "\\diamond ", "&#9671;"), j.bigtriangleup = i(V, "\\bigtriangleup ", "&#9651;"), j.ominus = i(V, "\\ominus ", "&#8854;"), j.uplus = i(V, "\\uplus ", "&#8846;"), j.bigtriangledown = i(V, "\\bigtriangledown ", "&#9661;"), j.sqcap = i(V, "\\sqcap ", "&#8851;"), j.triangleleft = i(V, "\\triangleleft ", "&#8882;"), j.sqcup = i(V, "\\sqcup ", "&#8852;"), j.triangleright = i(V, "\\triangleright ", "&#8883;"), j.odot = j.circledot = i(V, "\\odot ", "&#8857;"), j.bigcirc = i(V, "\\bigcirc ", "&#9711;"), j.dagger = i(V, "\\dagger ", "&#0134;"), j.ddagger = i(V, "\\ddagger ", "&#135;"), j.wr = i(V, "\\wr ", "&#8768;"), j.amalg = i(V, "\\amalg ", "&#8720;"), j.models = i(V, "\\models ", "&#8872;"), j.prec = i(V, "\\prec ", "&#8826;"), j.succ = i(V, "\\succ ", "&#8827;"), j.preceq = i(V, "\\preceq ", "&#8828;"), j.succeq = i(V, "\\succeq ", "&#8829;"), j.simeq = i(V, "\\simeq ", "&#8771;"), j.mid = i(V, "\\mid ", "&#8739;"), j.ll = i(V, "\\ll ", "&#8810;"), j.gg = i(V, "\\gg ", "&#8811;"), j.parallel = i(V, "\\parallel ", "&#8741;"), j.nparallel = i(V, "\\nparallel ", "&#8742;"), j.bowtie = i(V, "\\bowtie ", "&#8904;"), j.sqsubset = i(V, "\\sqsubset ", "&#8847;"), j.sqsupset = i(V, "\\sqsupset ", "&#8848;"), j.smile = i(V, "\\smile ", "&#8995;"), j.sqsubseteq = i(V, "\\sqsubseteq ", "&#8849;"), j.sqsupseteq = i(V, "\\sqsupseteq ", "&#8850;"), j.doteq = i(V, "\\doteq ", "&#8784;"), j.frown = i(V, "\\frown ", "&#8994;"), j.vdash = i(V, "\\vdash ", "&#8870;"), j.dashv = i(V, "\\dashv ", "&#8867;"), j.nless = i(V, "\\nless ", "&#8814;"), j.ngtr = i(V, "\\ngtr ", "&#8815;"), j.longleftarrow = i(V, "\\longleftarrow ", "&#8592;"), j.longrightarrow = i(V, "\\longrightarrow ", "&#8594;"), j.Longleftarrow = i(V, "\\Longleftarrow ", "&#8656;"), j.Longrightarrow = i(V, "\\Longrightarrow ", "&#8658;"), j.longleftrightarrow = i(V, "\\longleftrightarrow ", "&#8596;"), j.updownarrow = i(V, "\\updownarrow ", "&#8597;"), j.Longleftrightarrow = i(V, "\\Longleftrightarrow ", "&#8660;"), j.Updownarrow = i(V, "\\Updownarrow ", "&#8661;"), j.mapsto = i(V, "\\mapsto ", "&#8614;"), j.nearrow = i(V, "\\nearrow ", "&#8599;"), j.hookleftarrow = i(V, "\\hookleftarrow ", "&#8617;"), j.hookrightarrow = i(V, "\\hookrightarrow ", "&#8618;"), j.searrow = i(V, "\\searrow ", "&#8600;"), j.leftharpoonup = i(V, "\\leftharpoonup ", "&#8636;"), j.rightharpoonup = i(V, "\\rightharpoonup ", "&#8640;"), j.swarrow = i(V, "\\swarrow ", "&#8601;"), j.leftharpoondown = i(V, "\\leftharpoondown ", "&#8637;"), j.rightharpoondown = i(V, "\\rightharpoondown ", "&#8641;"), j.nwarrow = i(V, "\\nwarrow ", "&#8598;"), j.ldots = i(V, "\\ldots ", "&#8230;"), j.cdots = i(V, "\\cdots ", "&#8943;"), j.vdots = i(V, "\\vdots ", "&#8942;"), j.ddots = i(V, "\\ddots ", "&#8945;"), j.surd = i(V, "\\surd ", "&#8730;"), j.triangle = i(V, "\\triangle ", "&#9651;"), j.ell = i(V, "\\ell ", "&#8467;"), j.top = i(V, "\\top ", "&#8868;"), j.flat = i(V, "\\flat ", "&#9837;"), j.natural = i(V, "\\natural ", "&#9838;"), j.sharp = i(V, "\\sharp ", "&#9839;"), j.wp = i(V, "\\wp ", "&#8472;"), j.bot = i(V, "\\bot ", "&#8869;"), j.clubsuit = i(V, "\\clubsuit ", "&#9827;"), j.diamondsuit = i(V, "\\diamondsuit ", "&#9826;"), j.heartsuit = i(V, "\\heartsuit ", "&#9825;"), j.spadesuit = i(V, "\\spadesuit ", "&#9824;"),j.parallelogram = i(V, "\\parallelogram ", "&#9649;"),j.square = i(V, "\\square ", "&#11036;"),j.oint = i(V, "\\oint ", "&#8750;"),j.bigcap = i(V, "\\bigcap ", "&#8745;"),j.bigcup = i(V, "\\bigcup ", "&#8746;"),j.bigsqcup = i(V, "\\bigsqcup ", "&#8852;"),j.bigvee = i(V, "\\bigvee ", "&#8744;"),j.bigwedge = i(V, "\\bigwedge ", "&#8743;"),j.bigodot = i(V, "\\bigodot ", "&#8857;"),j.bigotimes = i(V, "\\bigotimes ", "&#8855;"),j.bigoplus = i(V, "\\bigoplus ", "&#8853;"),j.biguplus = i(V, "\\biguplus ", "&#8846;"),j.lfloor = i(V, "\\lfloor ", "&#8970;"),j.rfloor = i(V, "\\rfloor ", "&#8971;"),j.lceil = i(V, "\\lceil ", "&#8968;"),j.rceil = i(V, "\\rceil ", "&#8969;"),j.opencurlybrace = j.lbrace = i(V, "\\lbrace ", "{"),j.closecurlybrace = j.rbrace = i(V, "\\rbrace ", "}"),j.lbrack = i(V, "["),j.rbrack = i(V, "]"),j["∫"] = j["int"] = j.integral = i(K, "\\int ", "<big>&int;</big>"),j.slash = i(V, "/"),j.vert = i(V, "|"),j.perp = j.perpendicular = i(V, "\\perp ", "&perp;"),j.nabla = j.del = i(V, "\\nabla ", "&nabla;"),j.hbar = i(V, "\\hbar ", "&#8463;"),j.AA = j.Angstrom = j.angstrom = i(V, "\\text\\AA ", "&#8491;"),j.ring = j.circ = j.circle = i(V, "\\circ ", "&#8728;"),j.bull = j.bullet = i(V, "\\bullet ", "&bull;"),j.setminus = j.smallsetminus = i(V, "\\setminus ", "&#8726;"),j.not = j["¬"] = j.neg = i(V, "\\neg ", "&not;"),j["…"] = j.dots = j.ellip = j.hellip = j.ellipsis = j.hellipsis = i(V, "\\dots ", "&hellip;"),j.converges = j.darr = j.dnarr = j.dnarrow = j.downarrow = i(V, "\\downarrow ", "&darr;"),j.dArr = j.dnArr = j.dnArrow = j.Downarrow = i(V, "\\Downarrow ", "&dArr;"),j.diverges = j.uarr = j.uparrow = i(V, "\\uparrow ", "&uarr;"),j.uArr = j.Uparrow = i(V, "\\Uparrow ", "&uArr;"),j.to = i(G, "\\to ", "&rarr;"),j.rarr = j.rightarrow = i(V, "\\rightarrow ", "&rarr;"),j.implies = i(G, "\\Rightarrow ", "&rArr;"),j.rArr = j.Rightarrow = i(V, "\\Rightarrow ", "&rArr;"),j.gets = i(G, "\\gets ", "&larr;"),j.larr = j.leftarrow = i(V, "\\leftarrow ", "&larr;"),j.impliedby = i(G, "\\Leftarrow ", "&lArr;"),j.lArr = j.Leftarrow = i(V, "\\Leftarrow ", "&lArr;"),j.harr = j.lrarr = j.leftrightarrow = i(V, "\\leftrightarrow ", "&harr;"),j.iff = i(G, "\\Leftrightarrow ", "&hArr;"),j.hArr = j.lrArr = j.Leftrightarrow = i(V, "\\Leftrightarrow ", "&hArr;"),j.Re = j.Real = j.real = i(V, "\\Re ", "&real;"),j.Im = j.imag = j.image = j.imagin = j.imaginary = j.Imaginary = i(V, "\\Im ", "&image;"),j.part = j.partial = i(V, "\\partial ", "&part;"),j.infty = j.infin = j.infinity = i(V, "\\infty ", "&infin;"),j.alef = j.alefsym = j.aleph = j.alephsym = i(V, "\\aleph ", "&alefsym;"),j.xist = j.xists = j.exist = j.exists = i(V, "\\exists ", "&exist;"),j.and = j.land = j.wedge = i(V, "\\wedge ", "&and;"),j.or = j.lor = j.vee = i(V, "\\vee ", "&or;"),j.o = j.O = j.empty = j.emptyset = j.oslash = j.Oslash = j.nothing = j.varnothing = i(G, "\\varnothing ", "&empty;"),j.cup = j.union = i(G, "\\cup ", "&cup;"),j.cap = j.intersect = j.intersection = i(G, "\\cap ", "&cap;"),j.deg = j.degree = i(V, "\\degree ", "&deg;"),j.ang = j.angle = i(V, "\\angle ", "&ang;"),j.measuredangle = i(V, "\\measuredangle ", "&#8737;");
    var ie = N(V, function (e, t) {
        e.createLeftOf = function (e) {
            e.options.autoSubscriptNumerals && e.parent !== e.parent.parent.sub && (e[k] instanceof re && e[k].isItalic !== !1 || e[k] instanceof qe && e[k][k] instanceof re && e[k][k].isItalic !== !1) ? (j._().createLeftOf(e), t.createLeftOf.call(this, e), e.insRightOf(e.parent.parent)) : t.createLeftOf.call(this, e)
        }
    }), re = N(K, function (e, t) {
        e.init = function (e, n) {
            t.init.call(this, e, "<var>" + (n || e) + "</var>")
        }, e.text = function () {
            var e = this.ctrlSeq;
            return !this[k] || this[k] instanceof re || this[k] instanceof G || "\\ " === this[k].ctrlSeq || (e = "*" + e), !this[C] || this[C] instanceof G || this[C] instanceof qe || (e += "*"), e
        }
    });
    Q.p.autoCommands = {_maxLength: 0}, M.autoCommands = function (e) {
        if (!/^[a-z]+(?: [a-z]+)*$/i.test(e))throw'"' + e + '" not a space-delimited list of only letters';
        for (var t = e.split(" "), n = {}, i = 0, r = 0; r < t.length; r += 1) {
            var s = t[r];
            if (s.length < 2)throw'autocommand "' + s + '" not minimum length of 2';
            if (j[s] === ce)throw'"' + s + '" is a built-in operator name';
            n[s] = 1, i = x(i, s.length)
        }
        return n._maxLength = i, n
    };
    var se = N(re, function (e, t) {
        function n(e) {
            return e instanceof K && !(e instanceof G)
        }

        e.init = function (e) {
            return t.init.call(this, this.letter = e)
        }, e.createLeftOf = function (e) {
            var n = e.options.autoCommands, i = n._maxLength;
            if (i > 0) {
                for (var r = this.letter, s = e[k], o = 1; s instanceof se && o < i;)r = s.letter + r, s = s[k], o += 1;
                for (; r.length;) {
                    if (n.hasOwnProperty(r)) {
                        for (var o = 2, s = e[k]; o < r.length; o += 1, s = s[k]);
                        return O(s, e[k]).remove(), e[k] = s[k], j[r](r).createLeftOf(e)
                    }
                    r = r.slice(1)
                }
            }
            t.createLeftOf.apply(this, arguments)
        }, e.italicize = function (e) {
            return this.isItalic = e, this.jQ.toggleClass("mq-operator-name", !e), this
        }, e.finalizeTree = e.siblingDeleted = e.siblingCreated = function (e, t) {
            t !== k && this[C] instanceof se || this.autoUnItalicize(e)
        }, e.autoUnItalicize = function (e) {
            var t = e.autoOperatorNames;
            if (0 !== t._maxLength) {
                for (var i = this.letter, r = this[k]; r instanceof se; r = r[k])i = r.letter + i;
                for (var s = this[C]; s instanceof se; s = s[C])i += s.letter;
                O(r[C] || this.parent.ends[k], s[k] || this.parent.ends[C]).each(function (e) {
                    e.italicize(!0).jQ.removeClass("mq-first mq-last"), e.ctrlSeq = e.letter
                });
                e:for (var o = 0, a = r[C] || this.parent.ends[k]; o < i.length; o += 1, a = a[C])for (var l = b(t._maxLength, i.length - o); l > 0; l -= 1) {
                    var c = i.slice(o, o + l);
                    if (t.hasOwnProperty(c)) {
                        for (var u = 0, f = a; u < l; u += 1, f = f[C]) {
                            f.italicize(!1);
                            var p = f
                        }
                        var h = oe.hasOwnProperty(c);
                        a.ctrlSeq = (h ? "\\" : "\\operatorname{") + a.ctrlSeq, p.ctrlSeq += h ? " " : "}", le.hasOwnProperty(c) && p[k][k][k].jQ.addClass("mq-last"), n(a[k]) && a.jQ.addClass("mq-first"), n(p[C]) && p.jQ.addClass("mq-last"), o += l - 1, a = p;
                        continue e
                    }
                }
            }
        }
    }), oe = {}, ae = Q.p.autoOperatorNames = {_maxLength: 9}, le = {limsup: 1, liminf: 1, projlim: 1, injlim: 1};
    !function () {
        for (var e = "arg deg det dim exp gcd hom inf ker lg lim ln log max min sup limsup liminf injlim projlim Pr".split(" "), t = 0; t < e.length; t += 1)oe[e[t]] = ae[e[t]] = 1;
        for (var n = "sin cos tan arcsin arccos arctan sinh cosh tanh sec csc cot coth".split(" "), t = 0; t < n.length; t += 1)oe[n[t]] = 1;
        for (var i = "sin cos tan sec cosec csc cotan cot ctg".split(" "), t = 0; t < i.length; t += 1)ae[i[t]] = ae["arc" + i[t]] = ae[i[t] + "h"] = ae["ar" + i[t] + "h"] = ae["arc" + i[t] + "h"] = 1;
        for (var r = "gcf hcf lcm proj span".split(" "), t = 0; t < r.length; t += 1)ae[r[t]] = 1
    }(), M.autoOperatorNames = function (e) {
        if (!/^[a-z]+(?: [a-z]+)*$/i.test(e))throw'"' + e + '" not a space-delimited list of only letters';
        for (var t = e.split(" "), n = {}, i = 0, r = 0; r < t.length; r += 1) {
            var s = t[r];
            if (s.length < 2)throw'"' + s + '" not minimum length of 2';
            n[s] = 1, i = x(i, s.length)
        }
        return n._maxLength = i, n
    };
    var ce = N(K, function (e, t) {
        e.init = function (e) {
            this.ctrlSeq = e
        }, e.createLeftOf = function (e) {
            for (var t = this.ctrlSeq, n = 0; n < t.length; n += 1)se(t.charAt(n)).createLeftOf(e)
        }, e.parser = function () {
            for (var e = this.ctrlSeq, t = Y(), n = 0; n < e.length; n += 1)se(e.charAt(n)).adopt(t, t.ends[C], 0);
            return $.succeed(t.children())
        }
    });
    for (var ue in ae)ae.hasOwnProperty(ue) && (j[ue] = ce);
    j.operatorname = N(U, function (t) {
        t.createLeftOf = e, t.numBlocks = function () {
            return 1
        }, t.parser = function () {
            return W.block.map(function (e) {
                return e.children()
            })
        }
    }), j.f = N(se, function (e, t) {
        e.init = function () {
            K.p.init.call(this, this.letter = "f", '<var class="mq-f">f</var>')
        }, e.italicize = function (e) {
            return this.jQ.html("f").toggleClass("mq-f", e), t.italicize.apply(this, arguments)
        }
    }), j[" "] = j.space = i(V, "\\ ", "&nbsp;"), j["'"] = j.prime = i(V, "'", "&prime;"), j.backslash = i(V, "\\backslash ", "\\"), D["\\"] || (D["\\"] = j.backslash), j.$ = i(V, "\\$", "$");
    var fe = N(K, function (e, t) {
        e.init = function (e, n) {
            t.init.call(this, e, '<span class="mq-nonSymbola">' + (n || e) + "</span>")
        }
    });
    j["@"] = fe, j["&"] = i(fe, "\\&", "&amp;"), j["%"] = i(fe, "\\%", "%"), j.alpha = j.beta = j.gamma = j.delta = j.zeta = j.eta = j.theta = j.iota = j.kappa = j.mu = j.nu = j.xi = j.rho = j.sigma = j.tau = j.chi = j.psi = j.omega = N(re, function (e, t) {
        e.init = function (e) {
            t.init.call(this, "\\" + e + " ", "&" + e + ";")
        }
    }), j.phi = i(re, "\\phi ", "&#981;"), j.phiv = j.varphi = i(re, "\\varphi ", "&phi;"), j.epsilon = i(re, "\\epsilon ", "&#1013;"), j.epsiv = j.varepsilon = i(re, "\\varepsilon ", "&epsilon;"), j.piv = j.varpi = i(re, "\\varpi ", "&piv;"), j.sigmaf = j.sigmav = j.varsigma = i(re, "\\varsigma ", "&sigmaf;"), j.thetav = j.vartheta = j.thetasym = i(re, "\\vartheta ", "&thetasym;"), j.upsilon = j.upsi = i(re, "\\upsilon ", "&upsilon;"), j.gammad = j.Gammad = j.digamma = i(re, "\\digamma ", "&#989;"), j.kappav = j.varkappa = i(re, "\\varkappa ", "&#1008;"), j.rhov = j.varrho = i(re, "\\varrho ", "&#1009;"), j.pi = j["π"] = i(fe, "\\pi ", "&pi;"), j.lambda = i(fe, "\\lambda ", "&lambda;"), j.Upsilon = j.Upsi = j.upsih = j.Upsih = i(K, "\\Upsilon ", '<var style="font-family: serif">&upsih;</var>'), j.Gamma = j.Delta = j.Theta = j.Lambda = j.Xi = j.Pi = j.Sigma = j.Phi = j.Psi = j.Omega = j.forall = N(V, function (e, t) {
        e.init = function (e) {
            t.init.call(this, "\\" + e + " ", "&" + e + ";")
        }
    });
    var pe = N(U, function (e) {
        e.init = function (e) {
            this.latex = e
        }, e.createLeftOf = function (e) {
            var t = W.parse(this.latex);
            t.children().adopt(e.parent, e[k], e[C]), e[k] = t.ends[C], t.jQize().insertBefore(e.jQ), t.finalizeInsert(e.options, e), t.ends[C][C].siblingCreated && t.ends[C][C].siblingCreated(e.options, k), t.ends[k][k].siblingCreated && t.ends[k][k].siblingCreated(e.options, C), e.parent.bubble("reflow")
        }, e.parser = function () {
            var e = W.parse(this.latex).children();
            return $.succeed(e)
        }
    });
    j["¹"] = i(pe, "^1"), j["²"] = i(pe, "^2"), j["³"] = i(pe, "^3"), j["¼"] = i(pe, "\\frac14"), j["½"] = i(pe, "\\frac12"), j["¾"] = i(pe, "\\frac34");
    var he = N(G, function (e) {
        e.init = V.prototype.init, e.contactWeld = e.siblingCreated = e.siblingDeleted = function (e, t) {
            if (t !== C)return this.jQ[0].className = !this[k] || this[k] instanceof G ? "" : "mq-binary-operator", this
        }
    });
    j["+"] = i(he, "+", "+"), j["–"] = j["-"] = i(he, "-", "&minus;"), j["±"] = j.pm = j.plusmn = j.plusminus = i(he, "\\pm ", "&plusmn;"), j.mp = j.mnplus = j.minusplus = i(he, "\\mp ", "&#8723;"), D["*"] = j.sdot = j.cdot = i(G, "\\cdot ", "&middot;", "*");
    var de = N(G, function (e, t) {
        e.init = function (e, n) {
            this.data = e, this.strict = n;
            var i = n ? "Strict" : "";
            t.init.call(this, e["ctrlSeq" + i], e["html" + i], e["text" + i])
        }, e.swap = function (e) {
            this.strict = e;
            var t = e ? "Strict" : "";
            this.ctrlSeq = this.data["ctrlSeq" + t], this.jQ.html(this.data["html" + t]), this.textTemplate = [this.data["text" + t]]
        }, e.deleteTowards = function (e, n) {
            return e !== k || this.strict ? void t.deleteTowards.apply(this, arguments) : (this.swap(!0), void this.bubble("reflow"))
        }
    }), me = {
        ctrlSeq: "\\le ",
        html: "&le;",
        text: "≤",
        ctrlSeqStrict: "<",
        htmlStrict: "&lt;",
        textStrict: "<"
    }, ge = {ctrlSeq: "\\ge ", html: "&ge;", text: "≥", ctrlSeqStrict: ">", htmlStrict: "&gt;", textStrict: ">"};
    j["<"] = j.lt = i(de, me, !0), j[">"] = j.gt = i(de, ge, !0), j["≤"] = j.le = j.leq = i(de, me, !1), j["≥"] = j.ge = j.geq = i(de, ge, !1);
    var ve = N(G, function (e, t) {
        e.init = function () {
            t.init.call(this, "=", "=")
        }, e.createLeftOf = function (e) {
            return e[k] instanceof de && e[k].strict ? (e[k].swap(!1), void e[k].bubble("reflow")) : void t.createLeftOf.apply(this, arguments)
        }
    });
    j["="] = ve, j["×"] = j.times = i(G, "\\times ", "&times;", "[x]"), j["÷"] = j.div = j.divide = j.divides = i(G, "\\div ", "&divide;", "[/]"), D["~"] = j.sim = i(G, "\\sim ", "~", "~");
    var ye, be, xe = e, we = document.createElement("div"), Te = we.style, Ne = {
        transform: 1,
        WebkitTransform: 1,
        MozTransform: 1,
        OTransform: 1,
        msTransform: 1
    };
    for (var ke in Ne)if (ke in Te) {
        be = ke;
        break
    }
    be ? ye = function (e, t, n) {
        e.css(be, "scale(" + t + "," + n + ")")
    } : "filter" in Te ? (xe = function (e) {
        e.className = e.className
    }, ye = function (e, t, n) {
        function i() {
            e.css("marginRight", (r.width() - 1) * (t - 1) / t + "px")
        }

        t /= 1 + (n - 1) / 2, e.css("fontSize", n + "em"), e.hasClass("mq-matrixed-container") || e.addClass("mq-matrixed-container").wrapInner('<span class="mq-matrixed"></span>');
        var r = e.children().css("filter", "progid:DXImageTransform.Microsoft.Matrix(M11=" + t + ",SizingMethod='auto expand')");
        i();
        var s = setInterval(i);
        q(window).load(function () {
            clearTimeout(s), i()
        })
    }) : ye = function (e, t, n) {
        e.css("fontSize", n + "em")
    };
    var Ce = N(U, function (e, t) {
        e.init = function (e, n, i) {
            t.init.call(this, e, "<" + n + " " + i + ">&0</" + n + ">")
        }
    });
    j.mathrm = i(Ce, "\\mathrm", "span", 'class="mq-roman mq-font"'), j.mathit = i(Ce, "\\mathit", "i", 'class="mq-font"'), j.mathbf = i(Ce, "\\mathbf", "b", 'class="mq-font"'), j.mathsf = i(Ce, "\\mathsf", "span", 'class="mq-sans-serif mq-font"'), j.mathtt = i(Ce, "\\mathtt", "span", 'class="mq-monospace mq-font"'), j.underline = i(Ce, "\\underline", "span", 'class="mq-non-leaf mq-underline"'), j.overline = j.bar = i(Ce, "\\overline", "span", 'class="mq-non-leaf mq-overline"'), j.overrightarrow = i(Ce, "\\overrightarrow", "span", 'class="mq-non-leaf mq-overarrow mq-arrow-right"'), j.overleftarrow = i(Ce, "\\overleftarrow", "span", 'class="mq-non-leaf mq-overarrow mq-arrow-left"');
    var qe = (j.textcolor = N(U, function (e, t) {
        e.setColor = function (e) {
            this.color = e, this.htmlTemplate = '<span class="mq-textcolor" style="color:' + e + '">&0</span>'
        }, e.latex = function () {
            return "\\textcolor{" + this.color + "}{" + this.blocks[0].latex() + "}"
        }, e.parser = function () {
            var e = this, n = $.optWhitespace, i = $.string, r = $.regex;
            return n.then(i("{")).then(r(/^[#\w\s.,()%-]*/)).skip(i("}")).then(function (n) {
                return e.setColor(n), t.parser.call(e)
            })
        }
    }), j["class"] = N(U, function (e, t) {
        e.parser = function () {
            var e = this, n = $.string, i = $.regex;
            return $.optWhitespace.then(n("{")).then(i(/^[-\w\s\\\xA0-\xFF]*/)).skip(n("}")).then(function (n) {
                return e.htmlTemplate = '<span class="mq-class ' + n + '">&0</span>', t.parser.call(e)
            })
        }
    }), N(U, function (e, t) {
        e.ctrlSeq = "_{...}^{...}", e.createLeftOf = function (e) {
            if (e[k] || !e.options.supSubsRequireOperand)return t.createLeftOf.apply(this, arguments)
        }, e.contactWeld = function (e) {
            for (var t = k; t; t = t === k && C)if (this[t] instanceof qe) {
                for (var n = "sub"; n; n = "sub" === n && "sup") {
                    var i = this[n], r = this[t][n];
                    if (i) {
                        if (r)if (i.isEmpty())var s = S(r, 0, r.ends[k]); else {
                            i.jQ.children().insAtDirEnd(-t, r.jQ);
                            var o = i.children().disown(), s = S(r, o.ends[C], r.ends[k]);
                            t === k ? o.adopt(r, r.ends[C], 0) : o.adopt(r, 0, r.ends[k])
                        } else this[t].addBlock(i.disown());
                        this.placeCursor = function (e, n) {
                            return function (i) {
                                i.insAtDirEnd(-t, e || n)
                            }
                        }(r, i)
                    }
                }
                this.remove(), e && e[k] === this && (t === C && s ? s[k] ? e.insRightOf(s[k]) : e.insAtLeftEnd(s.parent) : e.insRightOf(this[t]));
                break
            }
            this.respace()
        }, Q.p.charsThatBreakOutOfSupSub = "", e.finalizeTree = function () {
            this.ends[k].write = function (e, t) {
                if (e.options.autoSubscriptNumerals && this === this.parent.sub) {
                    if ("_" === t)return;
                    var n = this.chToCmd(t);
                    return n instanceof K ? e.deleteSelection() : e.clearSelection().insRightOf(this.parent), n.createLeftOf(e.show())
                }
                e[k] && !e[C] && !e.selection && e.options.charsThatBreakOutOfSupSub.indexOf(t) > -1 && e.insRightOf(this.parent), Y.p.write.apply(this, arguments)
            }
        }, e.moveTowards = function (e, n, i) {
            n.options.autoSubscriptNumerals && !this.sup ? n.insDirOf(e, this) : t.moveTowards.apply(this, arguments)
        }, e.deleteTowards = function (e, n) {
            if (n.options.autoSubscriptNumerals && this.sub) {
                var i = this.sub.ends[-e];
                i instanceof K ? i.remove() : i && i.deleteTowards(e, n.insAtDirEnd(-e, this.sub)), this.sub.isEmpty() && (this.sub.deleteOutOf(k, n.insAtLeftEnd(this.sub)), this.sup && n.insDirOf(-e, this))
            } else t.deleteTowards.apply(this, arguments)
        }, e.latex = function () {
            function e(e, t) {
                var n = t && t.latex();
                return t ? e + (1 === n.length ? n : "{" + (n || " ") + "}") : ""
            }

            return e("_", this.sub) + e("^", this.sup)
        }, e.respace = e.siblingCreated = e.siblingDeleted = function (e, t) {
            t !== C && this.jQ.toggleClass("mq-limit", "\\int " === this[k].ctrlSeq)
        }, e.addBlock = function (e) {
            "sub" === this.supsub ? (this.sup = this.upInto = this.sub.upOutOf = e, e.adopt(this, this.sub, 0).downOutOf = this.sub, e.jQ = q('<span class="mq-sup"/>').append(e.jQ.children()).attr(y, e.id).prependTo(this.jQ)) : (this.sub = this.downInto = this.sup.downOutOf = e, e.adopt(this, 0, this.sup).upOutOf = this.sup, e.jQ = q('<span class="mq-sub"></span>').append(e.jQ.children()).attr(y, e.id).appendTo(this.jQ.removeClass("mq-sup-only")), this.jQ.append('<span style="display:inline-block;width:0">&#8203;</span>'));
            for (var t = 0; t < 2; t += 1)(function (e, t, n, i) {
                e[t].deleteOutOf = function (r, s) {
                    if (s.insDirOf(this[r] ? -r : r, this.parent), !this.isEmpty()) {
                        var o = this.ends[r];
                        this.children().disown().withDirAdopt(r, s.parent, s[r], s[-r]).jQ.insDirOf(-r, s.jQ), s[-r] = o
                    }
                    e.supsub = n, delete e[t], delete e[i + "Into"], e[n][i + "OutOf"] = p, delete e[n].deleteOutOf, "sub" === t && q(e.jQ.addClass("mq-sup-only")[0].lastChild).remove(), this.remove()
                }
            })(this, "sub sup".split(" ")[t], "sup sub".split(" ")[t], "down up".split(" ")[t])
        }
    }));
    j.subscript = j._ = N(qe, function (e, t) {
        e.supsub = "sub", e.htmlTemplate = '<span class="mq-supsub mq-non-leaf"><span class="mq-sub">&0</span><span style="display:inline-block;width:0">&#8203;</span></span>', e.textTemplate = ["_"], e.finalizeTree = function () {
            this.downInto = this.sub = this.ends[k], this.sub.upOutOf = p, t.finalizeTree.call(this)
        }
    }), j.superscript = j.supscript = j["^"] = N(qe, function (e, t) {
        e.supsub = "sup", e.htmlTemplate = '<span class="mq-supsub mq-non-leaf mq-sup-only"><span class="mq-sup">&0</span></span>', e.textTemplate = ["^"], e.finalizeTree = function () {
            this.upInto = this.sup = this.ends[C], this.sup.downOutOf = p, t.finalizeTree.call(this)
        }
    });
    var Se = N(U, function (e, t) {
        e.init = function (e, t) {
            var n = '<span class="mq-large-operator mq-non-leaf"><span class="mq-to"><span>&1</span></span><big>' + t + '</big><span class="mq-from"><span>&0</span></span></span>';
            K.prototype.init.call(this, e, n)
        }, e.createLeftOf = function (e) {
            t.createLeftOf.apply(this, arguments), e.options.sumStartsWithNEquals && (se("n").createLeftOf(e), ve().createLeftOf(e))
        }, e.latex = function () {
            function e(e) {
                return 1 === e.length ? e : "{" + (e || " ") + "}"
            }

            return this.ctrlSeq + "_" + e(this.ends[k].latex()) + "^" + e(this.ends[C].latex())
        }, e.parser = function () {
            for (var e = $.string, t = $.optWhitespace, n = $.succeed, i = W.block, r = this, s = r.blocks = [Y(), Y()], o = 0; o < s.length; o += 1)s[o].adopt(r, r.ends[C], 0);
            return t.then(e("_").or(e("^"))).then(function (e) {
                var t = s["_" === e ? 0 : 1];
                return i.then(function (e) {
                    return e.children().adopt(t, t.ends[C], 0), n(r)
                })
            }).many().result(r)
        }, e.finalizeTree = function () {
            this.downInto = this.ends[k], this.upInto = this.ends[C], this.ends[k].upOutOf = this.ends[C], this.ends[C].downOutOf = this.ends[k]
        }
    });
    j["∑"] = j.sum = j.summation = i(Se, "\\sum ", "&sum;"), j["∏"] = j.prod = j.product = i(Se, "\\prod ", "&prod;"), j.coprod = j.coproduct = i(Se, "\\coprod ", "&#8720;");
    var Ee = j.frac = j.dfrac = j.cfrac = j.fraction = N(U, function (e, t) {
        e.ctrlSeq = "\\frac", e.htmlTemplate = '<span class="mq-fraction mq-non-leaf"><span class="mq-numerator">&0</span><span class="mq-denominator">&1</span><span style="display:inline-block;width:0">&#8203;</span></span>', e.textTemplate = ["(", ")/(", ")"], e.finalizeTree = function () {
            this.upInto = this.ends[C].upOutOf = this.ends[k], this.downInto = this.ends[k].downOutOf = this.ends[C]
        }
    }), Oe = j.over = D["/"] = N(Ee, function (t, n) {
        t.createLeftOf = function (t) {
            if (!this.replacedFragment) {
                for (var i = t[k]; i && !(i instanceof G || i instanceof (j.text || e) || i instanceof Se || "\\ " === i.ctrlSeq || /^[,;:]$/.test(i.ctrlSeq));)i = i[k];
                i instanceof Se && i[C] instanceof qe && (i = i[C], i[C] instanceof qe && i[C].ctrlSeq != i.ctrlSeq && (i = i[C])), i !== t[k] && (this.replaces(O(i[C] || t.parent.ends[k], t[k])), t[k] = i)
            }
            n.createLeftOf.call(this, t)
        }
    }), je = j.sqrt = j["√"] = N(U, function (e, t) {
        e.ctrlSeq = "\\sqrt", e.htmlTemplate = '<span class="mq-non-leaf"><span class="mq-scaled mq-sqrt-prefix">&radic;</span><span class="mq-non-leaf mq-sqrt-stem">&0</span></span>',
            e.textTemplate = ["sqrt(", ")"], e.parser = function () {
            return W.optBlock.then(function (e) {
                return W.block.map(function (t) {
                    var n = De();
                    return n.blocks = [e, t], e.adopt(n, 0, 0), t.adopt(n, e, 0), n
                })
            }).or(t.parser.call(this))
        }, e.reflow = function () {
            var e = this.ends[C].jQ;
            ye(e.prev(), 1, e.innerHeight() / +e.css("fontSize").slice(0, -2) - .1)
        }
    }), De = (j.vec = N(U, function (e, t) {
        e.ctrlSeq = "\\vec", e.htmlTemplate = '<span class="mq-non-leaf"><span class="mq-vector-prefix">&rarr;</span><span class="mq-vector-stem">&0</span></span>', e.textTemplate = ["vec(", ")"]
    }), j.nthroot = N(je, function (e, t) {
        e.htmlTemplate = '<sup class="mq-nthroot mq-non-leaf">&0</sup><span class="mq-scaled"><span class="mq-sqrt-prefix mq-scaled">&radic;</span><span class="mq-sqrt-stem mq-non-leaf">&1</span></span>', e.textTemplate = ["sqrt[", "](", ")"], e.latex = function () {
            return "\\sqrt[" + this.ends[k].latex() + "]{" + this.ends[C].latex() + "}"
        }
    })), Ae = N(N(U, h), function (t, n) {
        t.init = function (e, t, i, r, s) {
            n.init.call(this, "\\left" + r, m, [t, i]), this.side = e, this.sides = {}, this.sides[k] = {
                ch: t,
                ctrlSeq: r
            }, this.sides[C] = {ch: i, ctrlSeq: s}
        }, t.numBlocks = function () {
            return 1
        }, t.html = function () {
            return this.htmlTemplate = '<span class="mq-non-leaf"><span class="mq-scaled mq-paren' + (this.side === C ? " mq-ghost" : "") + '">' + this.sides[k].ch + '</span><span class="mq-non-leaf">&0</span><span class="mq-scaled mq-paren' + (this.side === k ? " mq-ghost" : "") + '">' + this.sides[C].ch + "</span></span>", n.html.call(this)
        }, t.latex = function () {
            return "\\left" + this.sides[k].ctrlSeq + this.ends[k].latex() + "\\right" + this.sides[C].ctrlSeq
        }, t.oppBrack = function (e, t, n) {
            return t instanceof Ae && t.side && t.side !== -n && ("|" === this.sides[this.side].ch || t.side === -this.side) && (!e.restrictMismatchedBrackets || Le[this.sides[this.side].ch] === t.sides[t.side].ch || {
                    "(": "]",
                    "[": ")"
                }[this.sides[k].ch] === t.sides[C].ch) && t
        }, t.closeOpposing = function (e) {
            e.side = 0, e.sides[this.side] = this.sides[this.side], e.delimjQs.eq(this.side === k ? 0 : 1).removeClass("mq-ghost").html(this.sides[this.side].ch)
        }, t.createLeftOf = function (e) {
            if (!this.replacedFragment)var t = e.options, i = this.oppBrack(t, e[k], k) || this.oppBrack(t, e[C], C) || this.oppBrack(t, e.parent.parent);
            if (i) {
                var r = this.side = -i.side;
                this.closeOpposing(i), i === e.parent.parent && e[r] && (O(e[r], e.parent.ends[r], -r).disown().withDirAdopt(-r, i.parent, i, i[r]).jQ.insDirOf(r, i.jQ), i.bubble("reflow"))
            } else i = this, r = i.side, i.replacedFragment ? i.side = 0 : e[-r] && (i.replaces(O(e[-r], e.parent.ends[-r], r)), e[-r] = 0), n.createLeftOf.call(i, e);
            r === k ? e.insAtLeftEnd(i.ends[k]) : e.insRightOf(i)
        }, t.placeCursor = e, t.unwrap = function () {
            this.ends[k].children().disown().adopt(this.parent, this, this[C]).jQ.insertAfter(this.jQ), this.remove()
        }, t.deleteSide = function (e, t, n) {
            var i = this.parent, r = this[e], s = i.ends[e];
            if (e === this.side)return this.unwrap(), void(r ? n.insDirOf(-e, r) : n.insAtDirEnd(e, i));
            var o = n.options, a = !this.side;
            if (this.side = -e, this.oppBrack(o, this.ends[k].ends[this.side], e)) {
                this.closeOpposing(this.ends[k].ends[this.side]);
                var l = this.ends[k].ends[e];
                this.unwrap(), l.siblingCreated && l.siblingCreated(n.options, e), r ? n.insDirOf(-e, r) : n.insAtDirEnd(e, i)
            } else {
                if (this.oppBrack(o, this.parent.parent, e))this.parent.parent.closeOpposing(this), this.parent.parent.unwrap(); else {
                    if (t && a)return this.unwrap(), void(r ? n.insDirOf(-e, r) : n.insAtDirEnd(e, i));
                    this.sides[e] = {
                        ch: Le[this.sides[this.side].ch],
                        ctrlSeq: Le[this.sides[this.side].ctrlSeq]
                    }, this.delimjQs.removeClass("mq-ghost").eq(e === k ? 0 : 1).addClass("mq-ghost").html(this.sides[e].ch)
                }
                if (r) {
                    var l = this.ends[k].ends[e];
                    O(r, s, -e).disown().withDirAdopt(-e, this.ends[k], l, 0).jQ.insAtDirEnd(e, this.ends[k].jQ.removeClass("mq-empty")), l.siblingCreated && l.siblingCreated(n.options, e), n.insDirOf(-e, r)
                } else t ? n.insDirOf(e, this) : n.insAtDirEnd(e, this.ends[k])
            }
        }, t.deleteTowards = function (e, t) {
            this.deleteSide(-e, !1, t)
        }, t.finalizeTree = function () {
            this.ends[k].deleteOutOf = function (e, t) {
                this.parent.deleteSide(e, !0, t)
            }, this.finalizeTree = this.intentionalBlur = function () {
                this.delimjQs.eq(this.side === k ? 1 : 0).removeClass("mq-ghost"), this.side = 0
            }
        }, t.siblingCreated = function (e, t) {
            t === -this.side && this.finalizeTree()
        }
    }), Le = {
        "(": ")",
        ")": "(",
        "[": "]",
        "]": "[",
        "{": "}",
        "}": "{",
        "\\{": "\\}",
        "\\}": "\\{",
        "&lang;": "&rang;",
        "&rang;": "&lang;",
        "\\langle ": "\\rangle ",
        "\\rangle ": "\\langle ",
        "|": "|"
    };
    d("("), d("["), d("{", "\\{"), j.langle = i(Ae, k, "&lang;", "&rang;", "\\langle ", "\\rangle "), j.rangle = i(Ae, C, "&lang;", "&rang;", "\\langle ", "\\rangle "), D["|"] = i(Ae, k, "|", "|", "|", "|"), j.left = N(U, function (e) {
        e.parser = function () {
            var e = $.regex, t = $.string, n = ($.succeed, $.optWhitespace);
            return n.then(e(/^(?:[([|]|\\\{)/)).then(function (i) {
                var r = "\\" === i.charAt(0) ? i.slice(1) : i;
                return W.then(function (s) {
                    return t("\\right").skip(n).then(e(/^(?:[\])|]|\\\})/)).map(function (e) {
                        var t = "\\" === e.charAt(0) ? e.slice(1) : e, n = Ae(0, r, t, i, e);
                        return n.blocks = [s], s.adopt(n, 0, 0), n
                    })
                })
            })
        }
    }), j.right = N(U, function (e) {
        e.parser = function () {
            return $.fail("unmatched \\right")
        }
    });
    var _e = j.binom = j.binomial = N(N(U, h), function (e, t) {
        e.ctrlSeq = "\\binom", e.htmlTemplate = '<span class="mq-non-leaf"><span class="mq-paren mq-scaled">(</span><span class="mq-non-leaf"><span class="mq-array mq-non-leaf"><span>&0</span><span>&1</span></span></span><span class="mq-paren mq-scaled">)</span></span>', e.textTemplate = ["choose(", ",", ")"]
    });
    j.choose = N(_e, function (e) {
        e.createLeftOf = Oe.prototype.createLeftOf
    });
    j.editable = j.MathQuillMathField = N(U, function (e, t) {
        e.ctrlSeq = "\\MathQuillMathField", e.htmlTemplate = '<span class="mq-editable-field"><span class="mq-root-block">&0</span></span>', e.parser = function () {
            var e = this, n = $.string, i = $.regex, r = $.succeed;
            return n("[").then(i(/^[a-z][a-z0-9]*/i)).skip(n("]")).map(function (t) {
                e.name = t
            }).or(r()).then(t.parser.call(e))
        }, e.finalizeTree = function () {
            var e = _(this.ends[k], this.jQ, Q());
            e.KIND_OF_MQ = "MathField", e.editable = !0, e.createTextarea(), e.editablesTextareaEvents(), e.cursor.insAtRightEnd(e.root), u(e.root)
        }, e.registerInnerField = function (e, t) {
            e.push(e[this.name] = t(this.ends[k].controller))
        }, e.latex = function () {
            return this.ends[k].latex()
        }, e.text = function () {
            return this.ends[k].text()
        }
    });
    var Fe = j.embed = N(K, function (e, t) {
        e.setOptions = function (e) {
            function t() {
                return ""
            }

            return this.text = e.text || t, this.htmlTemplate = e.htmlString || "", this.latex = e.latex || t, this
        }, e.parser = function () {
            var e = this;
            return string = $.string, regex = $.regex, succeed = $.succeed, string("{").then(regex(/^[a-z][a-z0-9]*/i)).skip(string("}")).then(function (t) {
                return string("[").then(regex(/^[-\w\s]*/)).skip(string("]")).or(succeed()).map(function (n) {
                    return e.setOptions(I[t](n))
                })
            })
        }
    }), Qe = c(1);
    for (var Me in Qe)(function (e, t) {
        "function" == typeof t ? (l[e] = function () {
            return a(), t.apply(this, arguments)
        }, l[e].prototype = t.prototype) : l[e] = t
    })(Me, Qe[Me])
}();
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
            }, {symbolName: "且", img: "086.png", latex: "\\wedge"}, {
                symbolName: "或",
                img: "087.png",
                latex: "\\vee"
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
                        $("<img src='/plugin/lib/math_editor/images/formula/" + this.img + "'>").appendTo(e).click(function () {
                            r.write(t.latex)
                        })
                    }), $("<div class='fe-symbol'><img src='/plugin/lib/math_editor/images/formula/" + this.img + "'><br>" + this.className + "</div>").appendTo(s).append(e)
                }
            });
            var o = $("<div class='fe-footer' style='display: none;'></div>").appendTo(t);
            return $('<button class="btn btn-submit">提交</button>').appendTo(o).click(function () {
                n.handlers.completed && n.handlers.completed(r.latex())
            }), $('<button class="btn btn-cancel">取消</button>').appendTo(o).click(function () {
                n.handlers.cancel && n.handlers.cancel()
            }), {mq: r}
        }, t
    }();
    e.UI = t
}(KeleFE || (KeleFE = {}));