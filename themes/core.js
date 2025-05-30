(()=>{
    var e, t, n, r, o = {
        333: (e,t,n)=>{
            var r, o, i;
            /*!
 * https://github.com/PrestaShop/jquery.live-polyfill
 *
 * Released under the MIT license
 */
            o = [n(204)],
            void 0 === (i = "function" == typeof (r = function(e) {
                var t = e.fn.init;
                e.fn.init = function(e) {
                    var n = Array.prototype.slice.call(arguments);
                    "string" == typeof e && "#" === e && (console.warn("jQuery('#') is not a valid selector"),
                    n[0] = []);
                    var r = t.apply(this, arguments);
                    return r.selector = "string" == typeof e ? e : "",
                    r
                }
                ,
                e.fn.init.prototype = e.fn,
                void 0 !== e.fn.live && e.isFunction(e.fn.live) || e.fn.extend({
                    live: function(t, n, r) {
                        return this.selector && e(document).on(t, this.selector, n, r),
                        console.warn("jQuery.live() has been removed since jquery v1.9, please use jQuery.on() instead."),
                        this
                    }
                })
            }
            ) ? r.apply(t, o) : r) || (e.exports = i)
        }
        ,
        290: (e,t,n)=>{
            var r, o;
            /*!
 * jQuery Migrate - v3.1.0 - 2019-06-08
 * Copyright OpenJS Foundation and other contributors
 */
            r = [n(204)],
            void 0 === (o = function(e) {
                return function(e, t) {
                    "use strict";
                    function n(e, t) {
                        for (var n = /^(\d+)\.(\d+)\.(\d+)/, r = n.exec(e) || [], o = n.exec(t) || [], i = 1; i <= 3; i++) {
                            if (+r[i] > +o[i])
                                return 1;
                            if (+r[i] < +o[i])
                                return -1
                        }
                        return 0
                    }
                    function r(t) {
                        return n(e.fn.jquery, t) >= 0
                    }
                    e.migrateVersion = "3.1.0",
                    t.console && t.console.log && (e && r("3.0.0") || t.console.log("JQMIGRATE: jQuery 3.0.0+ REQUIRED"),
                    e.migrateWarnings && t.console.log("JQMIGRATE: Migrate plugin loaded multiple times"),
                    t.console.log("JQMIGRATE: Migrate is installed" + (e.migrateMute ? "" : " with logging active") + ", version " + e.migrateVersion));
                    var o = {};
                    function i(n) {
                        var r = t.console;
                        o[n] || (o[n] = !0,
                        e.migrateWarnings.push(n),
                        r && r.warn && !e.migrateMute && (r.warn("JQMIGRATE: " + n),
                        e.migrateTrace && r.trace && r.trace()))
                    }
                    function a(e, t, n, r) {
                        Object.defineProperty(e, t, {
                            configurable: !0,
                            enumerable: !0,
                            get: function() {
                                return i(r),
                                n
                            },
                            set: function(e) {
                                i(r),
                                n = e
                            }
                        })
                    }
                    function s(e, t, n, r) {
                        e[t] = function() {
                            return i(r),
                            n.apply(this, arguments)
                        }
                    }
                    e.migrateWarnings = [],
                    void 0 === e.migrateTrace && (e.migrateTrace = !0),
                    e.migrateReset = function() {
                        o = {},
                        e.migrateWarnings.length = 0
                    }
                    ,
                    "BackCompat" === t.document.compatMode && i("jQuery is not compatible with Quirks Mode");
                    var c, u = e.fn.init, l = e.isNumeric, d = e.find, p = /\[(\s*[-\w]+\s*)([~|^$*]?=)\s*([-\w#]*?#[-\w#]*)\s*\]/, f = /\[(\s*[-\w]+\s*)([~|^$*]?=)\s*([-\w#]*?#[-\w#]*)\s*\]/g;
                    for (c in e.fn.init = function(e) {
                        var t = Array.prototype.slice.call(arguments);
                        return "string" == typeof e && "#" === e && (i("jQuery( '#' ) is not a valid selector"),
                        t[0] = []),
                        u.apply(this, t)
                    }
                    ,
                    e.fn.init.prototype = e.fn,
                    e.find = function(e) {
                        var n = Array.prototype.slice.call(arguments);
                        if ("string" == typeof e && p.test(e))
                            try {
                                t.document.querySelector(e)
                            } catch (r) {
                                e = e.replace(f, (function(e, t, n, r) {
                                    return "[" + t + n + '"' + r + '"]'
                                }
                                ));
                                try {
                                    t.document.querySelector(e),
                                    i("Attribute selector with '#' must be quoted: " + n[0]),
                                    n[0] = e
                                } catch (e) {
                                    i("Attribute selector with '#' was not fixed: " + n[0])
                                }
                            }
                        return d.apply(this, n)
                    }
                    ,
                    d)
                        Object.prototype.hasOwnProperty.call(d, c) && (e.find[c] = d[c]);
                    e.fn.size = function() {
                        return i("jQuery.fn.size() is deprecated and removed; use the .length property"),
                        this.length
                    }
                    ,
                    e.parseJSON = function() {
                        return i("jQuery.parseJSON is deprecated; use JSON.parse"),
                        JSON.parse.apply(null, arguments)
                    }
                    ,
                    e.isNumeric = function(t) {
                        function n(t) {
                            var n = t && t.toString();
                            return !e.isArray(t) && n - parseFloat(n) + 1 >= 0
                        }
                        var r = l(t)
                          , o = n(t);
                        return r !== o && i("jQuery.isNumeric() should not be called on constructed objects"),
                        o
                    }
                    ,
                    r("3.3.0") && s(e, "isWindow", (function(e) {
                        return null != e && e === e.window
                    }
                    ), "jQuery.isWindow() is deprecated"),
                    s(e, "holdReady", e.holdReady, "jQuery.holdReady is deprecated"),
                    s(e, "unique", e.uniqueSort, "jQuery.unique is deprecated; use jQuery.uniqueSort"),
                    a(e.expr, "filters", e.expr.pseudos, "jQuery.expr.filters is deprecated; use jQuery.expr.pseudos"),
                    a(e.expr, ":", e.expr.pseudos, "jQuery.expr[':'] is deprecated; use jQuery.expr.pseudos"),
                    r("3.2.0") && s(e, "nodeName", e.nodeName, "jQuery.nodeName is deprecated");
                    var h = e.ajax;
                    e.ajax = function() {
                        var e = h.apply(this, arguments);
                        return e.promise && (s(e, "success", e.done, "jQXHR.success is deprecated and removed"),
                        s(e, "error", e.fail, "jQXHR.error is deprecated and removed"),
                        s(e, "complete", e.always, "jQXHR.complete is deprecated and removed")),
                        e
                    }
                    ;
                    var m = e.fn.removeAttr
                      , v = e.fn.toggleClass
                      , g = /\S+/g;
                    e.fn.removeAttr = function(t) {
                        var n = this;
                        return e.each(t.match(g), (function(t, r) {
                            e.expr.match.bool.test(r) && (i("jQuery.fn.removeAttr no longer sets boolean properties: " + r),
                            n.prop(r, !1))
                        }
                        )),
                        m.apply(this, arguments)
                    }
                    ,
                    e.fn.toggleClass = function(t) {
                        return void 0 !== t && "boolean" != typeof t ? v.apply(this, arguments) : (i("jQuery.fn.toggleClass( boolean ) is deprecated"),
                        this.each((function() {
                            var n = this.getAttribute && this.getAttribute("class") || "";
                            n && e.data(this, "__className__", n),
                            this.setAttribute && this.setAttribute("class", n || !1 === t ? "" : e.data(this, "__className__") || "")
                        }
                        )))
                    }
                    ;
                    var y = !1;
                    e.swap && e.each(["height", "width", "reliableMarginRight"], (function(t, n) {
                        var r = e.cssHooks[n] && e.cssHooks[n].get;
                        r && (e.cssHooks[n].get = function() {
                            var e;
                            return y = !0,
                            e = r.apply(this, arguments),
                            y = !1,
                            e
                        }
                        )
                    }
                    )),
                    e.swap = function(e, t, n, r) {
                        var o, a, s = {};
                        for (a in y || i("jQuery.swap() is undocumented and deprecated"),
                        t)
                            s[a] = e.style[a],
                            e.style[a] = t[a];
                        for (a in o = n.apply(e, r || []),
                        t)
                            e.style[a] = s[a];
                        return o
                    }
                    ;
                    var b = e.data;
                    e.data = function(t, n, r) {
                        var o;
                        if (n && "object" == typeof n && 2 === arguments.length) {
                            o = e.hasData(t) && b.call(this, t);
                            var a = {};
                            for (var s in n)
                                s !== e.camelCase(s) ? (i("jQuery.data() always sets/gets camelCased names: " + s),
                                o[s] = n[s]) : a[s] = n[s];
                            return b.call(this, t, a),
                            n
                        }
                        return n && "string" == typeof n && n !== e.camelCase(n) && (o = e.hasData(t) && b.call(this, t)) && n in o ? (i("jQuery.data() always sets/gets camelCased names: " + n),
                        arguments.length > 2 && (o[n] = r),
                        o[n]) : b.apply(this, arguments)
                    }
                    ;
                    var x = e.Tween.prototype.run
                      , w = function(e) {
                        return e
                    };
                    e.Tween.prototype.run = function() {
                        e.easing[this.easing].length > 1 && (i("'jQuery.easing." + this.easing.toString() + "' should use only one argument"),
                        e.easing[this.easing] = w),
                        x.apply(this, arguments)
                    }
                    ;
                    var k = e.fx.interval || 13
                      , C = "jQuery.fx.interval is deprecated";
                    t.requestAnimationFrame && Object.defineProperty(e.fx, "interval", {
                        configurable: !0,
                        enumerable: !0,
                        get: function() {
                            return t.document.hidden || i(C),
                            k
                        },
                        set: function(e) {
                            i(C),
                            k = e
                        }
                    });
                    var j = e.fn.load
                      , T = e.event.add
                      , S = e.event.fix;
                    e.event.props = [],
                    e.event.fixHooks = {},
                    a(e.event.props, "concat", e.event.props.concat, "jQuery.event.props.concat() is deprecated and removed"),
                    e.event.fix = function(t) {
                        var n, r = t.type, o = this.fixHooks[r], a = e.event.props;
                        if (a.length)
                            for (i("jQuery.event.props are deprecated and removed: " + a.join()); a.length; )
                                e.event.addProp(a.pop());
                        if (o && !o._migrated_ && (o._migrated_ = !0,
                        i("jQuery.event.fixHooks are deprecated and removed: " + r),
                        (a = o.props) && a.length))
                            for (; a.length; )
                                e.event.addProp(a.pop());
                        return n = S.call(this, t),
                        o && o.filter ? o.filter(n, t) : n
                    }
                    ,
                    e.event.add = function(e, n) {
                        return e === t && "load" === n && "complete" === t.document.readyState && i("jQuery(window).on('load'...) called after load event occurred"),
                        T.apply(this, arguments)
                    }
                    ,
                    e.each(["load", "unload", "error"], (function(t, n) {
                        e.fn[n] = function() {
                            var e = Array.prototype.slice.call(arguments, 0);
                            return "load" === n && "string" == typeof e[0] ? j.apply(this, e) : (i("jQuery.fn." + n + "() is deprecated"),
                            e.splice(0, 0, n),
                            arguments.length ? this.on.apply(this, e) : (this.triggerHandler.apply(this, e),
                            this))
                        }
                    }
                    )),
                    e.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "), (function(t, n) {
                        e.fn[n] = function(e, t) {
                            return i("jQuery.fn." + n + "() event shorthand is deprecated"),
                            arguments.length > 0 ? this.on(n, null, e, t) : this.trigger(n)
                        }
                    }
                    )),
                    e((function() {
                        e(t.document).triggerHandler("ready")
                    }
                    )),
                    e.event.special.ready = {
                        setup: function() {
                            this === t.document && i("'ready' event is deprecated")
                        }
                    },
                    e.fn.extend({
                        bind: function(e, t, n) {
                            return i("jQuery.fn.bind() is deprecated"),
                            this.on(e, null, t, n)
                        },
                        unbind: function(e, t) {
                            return i("jQuery.fn.unbind() is deprecated"),
                            this.off(e, null, t)
                        },
                        delegate: function(e, t, n, r) {
                            return i("jQuery.fn.delegate() is deprecated"),
                            this.on(t, e, n, r)
                        },
                        undelegate: function(e, t, n) {
                            return i("jQuery.fn.undelegate() is deprecated"),
                            1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
                        },
                        hover: function(e, t) {
                            return i("jQuery.fn.hover() is deprecated"),
                            this.on("mouseenter", e).on("mouseleave", t || e)
                        }
                    });
                    var A = e.fn.offset;
                    e.fn.offset = function() {
                        var n, r = this[0], o = {
                            top: 0,
                            left: 0
                        };
                        return r && r.nodeType ? (n = (r.ownerDocument || t.document).documentElement,
                        e.contains(n, r) ? A.apply(this, arguments) : (i("jQuery.fn.offset() requires an element connected to a document"),
                        o)) : (i("jQuery.fn.offset() requires a valid DOM element"),
                        o)
                    }
                    ;
                    var E = e.param;
                    e.param = function(t, n) {
                        var r = e.ajaxSettings && e.ajaxSettings.traditional;
                        return void 0 === n && r && (i("jQuery.param() no longer uses jQuery.ajaxSettings.traditional"),
                        n = r),
                        E.call(this, t, n)
                    }
                    ;
                    var N = e.fn.andSelf || e.fn.addBack;
                    e.fn.andSelf = function() {
                        return i("jQuery.fn.andSelf() is deprecated and removed, use jQuery.fn.addBack()"),
                        N.apply(this, arguments)
                    }
                    ;
                    var q = e.Deferred
                      , D = [["resolve", "done", e.Callbacks("once memory"), e.Callbacks("once memory"), "resolved"], ["reject", "fail", e.Callbacks("once memory"), e.Callbacks("once memory"), "rejected"], ["notify", "progress", e.Callbacks("memory"), e.Callbacks("memory")]];
                    return e.Deferred = function(t) {
                        var n = q()
                          , r = n.promise();
                        return n.pipe = r.pipe = function() {
                            var t = arguments;
                            return i("deferred.pipe() is deprecated"),
                            e.Deferred((function(o) {
                                e.each(D, (function(i, a) {
                                    var s = e.isFunction(t[i]) && t[i];
                                    n[a[1]]((function() {
                                        var t = s && s.apply(this, arguments);
                                        t && e.isFunction(t.promise) ? t.promise().done(o.resolve).fail(o.reject).progress(o.notify) : o[a[0] + "With"](this === r ? o.promise() : this, s ? [t] : arguments)
                                    }
                                    ))
                                }
                                )),
                                t = null
                            }
                            )).promise()
                        }
                        ,
                        t && t.call(n, n),
                        n
                    }
                    ,
                    e.Deferred.exceptionHook = q.exceptionHook,
                    e
                }(e, window)
            }
            .apply(t, r)) || (e.exports = o)
        }
        ,
        768: (e,t,n)=>{
            var r, o;
            /*!
 * jQuery Browser Plugin 0.1.0
 * https://github.com/gabceb/jquery-browser-plugin
 *
 * Original jquery-browser code Copyright 2005, 2015 jQuery Foundation, Inc. and other contributors
 * http://jquery.org/license
 *
 * Modifications Copyright 2015 Gabriel Cebrian
 * https://github.com/gabceb
 *
 * Released under the MIT license
 *
 * Date: 05-07-2015
 */
            r = [n(204)],
            void 0 === (o = function(e) {
                return function(e) {
                    "use strict";
                    function t(e) {
                        void 0 === e && (e = window.navigator.userAgent),
                        e = e.toLowerCase();
                        var t = /(edge)\/([\w.]+)/.exec(e) || /(opr)[\/]([\w.]+)/.exec(e) || /(chrome)[ \/]([\w.]+)/.exec(e) || /(iemobile)[\/]([\w.]+)/.exec(e) || /(version)(applewebkit)[ \/]([\w.]+).*(safari)[ \/]([\w.]+)/.exec(e) || /(webkit)[ \/]([\w.]+).*(version)[ \/]([\w.]+).*(safari)[ \/]([\w.]+)/.exec(e) || /(webkit)[ \/]([\w.]+)/.exec(e) || /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(e) || /(msie) ([\w.]+)/.exec(e) || e.indexOf("trident") >= 0 && /(rv)(?::| )([\w.]+)/.exec(e) || e.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(e) || []
                          , n = /(ipad)/.exec(e) || /(ipod)/.exec(e) || /(windows phone)/.exec(e) || /(iphone)/.exec(e) || /(kindle)/.exec(e) || /(silk)/.exec(e) || /(android)/.exec(e) || /(win)/.exec(e) || /(mac)/.exec(e) || /(linux)/.exec(e) || /(cros)/.exec(e) || /(playbook)/.exec(e) || /(bb)/.exec(e) || /(blackberry)/.exec(e) || []
                          , r = {}
                          , o = {
                            browser: t[5] || t[3] || t[1] || "",
                            version: t[2] || t[4] || "0",
                            versionNumber: t[4] || t[2] || "0",
                            platform: n[0] || ""
                        };
                        if (o.browser && (r[o.browser] = !0,
                        r.version = o.version,
                        r.versionNumber = parseInt(o.versionNumber, 10)),
                        o.platform && (r[o.platform] = !0),
                        (r.android || r.bb || r.blackberry || r.ipad || r.iphone || r.ipod || r.kindle || r.playbook || r.silk || r["windows phone"]) && (r.mobile = !0),
                        (r.cros || r.mac || r.linux || r.win) && (r.desktop = !0),
                        (r.chrome || r.opr || r.safari) && (r.webkit = !0),
                        r.rv || r.iemobile) {
                            var i = "msie";
                            o.browser = i,
                            r[i] = !0
                        }
                        if (r.edge) {
                            delete r.edge;
                            var a = "msedge";
                            o.browser = a,
                            r[a] = !0
                        }
                        if (r.safari && r.blackberry) {
                            var s = "blackberry";
                            o.browser = s,
                            r[s] = !0
                        }
                        if (r.safari && r.playbook) {
                            var c = "playbook";
                            o.browser = c,
                            r[c] = !0
                        }
                        if (r.bb) {
                            var u = "blackberry";
                            o.browser = u,
                            r[u] = !0
                        }
                        if (r.opr) {
                            var l = "opera";
                            o.browser = l,
                            r[l] = !0
                        }
                        if (r.safari && r.android) {
                            var d = "android";
                            o.browser = d,
                            r[d] = !0
                        }
                        if (r.safari && r.kindle) {
                            var p = "kindle";
                            o.browser = p,
                            r[p] = !0
                        }
                        if (r.safari && r.silk) {
                            var f = "silk";
                            o.browser = f,
                            r[f] = !0
                        }
                        return r.name = o.browser,
                        r.platform = o.platform,
                        r
                    }
                    return window.jQBrowser = t(window.navigator.userAgent),
                    window.jQBrowser.uaMatch = t,
                    e && (e.browser = window.jQBrowser),
                    window.jQBrowser
                }(e)
            }
            .apply(t, r)) || (e.exports = o)
        }
        ,
        204: function(e, t) {
            var n;
            /*!
 * jQuery JavaScript Library v3.5.1
 * https://jquery.com/
 *
 * Includes Sizzle.js
 * https://sizzlejs.com/
 *
 * Copyright JS Foundation and other contributors
 * Released under the MIT license
 * https://jquery.org/license
 *
 * Date: 2020-05-04T22:49Z
 */
            !function(t, n) {
                "use strict";
                "object" == typeof e.exports ? e.exports = t.document ? n(t, !0) : function(e) {
                    if (!e.document)
                        throw new Error("jQuery requires a window with a document");
                    return n(e)
                }
                : n(t)
            }("undefined" != typeof window ? window : this, (function(r, o) {
                "use strict";
                var i = []
                  , a = Object.getPrototypeOf
                  , s = i.slice
                  , c = i.flat ? function(e) {
                    return i.flat.call(e)
                }
                : function(e) {
                    return i.concat.apply([], e)
                }
                  , u = i.push
                  , l = i.indexOf
                  , d = {}
                  , p = d.toString
                  , f = d.hasOwnProperty
                  , h = f.toString
                  , m = h.call(Object)
                  , v = {}
                  , g = function(e) {
                    return "function" == typeof e && "number" != typeof e.nodeType
                }
                  , y = function(e) {
                    return null != e && e === e.window
                }
                  , b = r.document
                  , x = {
                    type: !0,
                    src: !0,
                    nonce: !0,
                    noModule: !0
                };
                function w(e, t, n) {
                    var r, o, i = (n = n || b).createElement("script");
                    if (i.text = e,
                    t)
                        for (r in x)
                            (o = t[r] || t.getAttribute && t.getAttribute(r)) && i.setAttribute(r, o);
                    n.head.appendChild(i).parentNode.removeChild(i)
                }
                function k(e) {
                    return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? d[p.call(e)] || "object" : typeof e
                }
                var C = "3.5.1"
                  , j = function(e, t) {
                    return new j.fn.init(e,t)
                };
                function T(e) {
                    var t = !!e && "length"in e && e.length
                      , n = k(e);
                    return !g(e) && !y(e) && ("array" === n || 0 === t || "number" == typeof t && t > 0 && t - 1 in e)
                }
                j.fn = j.prototype = {
                    jquery: C,
                    constructor: j,
                    length: 0,
                    toArray: function() {
                        return s.call(this)
                    },
                    get: function(e) {
                        return null == e ? s.call(this) : e < 0 ? this[e + this.length] : this[e]
                    },
                    pushStack: function(e) {
                        var t = j.merge(this.constructor(), e);
                        return t.prevObject = this,
                        t
                    },
                    each: function(e) {
                        return j.each(this, e)
                    },
                    map: function(e) {
                        return this.pushStack(j.map(this, (function(t, n) {
                            return e.call(t, n, t)
                        }
                        )))
                    },
                    slice: function() {
                        return this.pushStack(s.apply(this, arguments))
                    },
                    first: function() {
                        return this.eq(0)
                    },
                    last: function() {
                        return this.eq(-1)
                    },
                    even: function() {
                        return this.pushStack(j.grep(this, (function(e, t) {
                            return (t + 1) % 2
                        }
                        )))
                    },
                    odd: function() {
                        return this.pushStack(j.grep(this, (function(e, t) {
                            return t % 2
                        }
                        )))
                    },
                    eq: function(e) {
                        var t = this.length
                          , n = +e + (e < 0 ? t : 0);
                        return this.pushStack(n >= 0 && n < t ? [this[n]] : [])
                    },
                    end: function() {
                        return this.prevObject || this.constructor()
                    },
                    push: u,
                    sort: i.sort,
                    splice: i.splice
                },
                j.extend = j.fn.extend = function() {
                    var e, t, n, r, o, i, a = arguments[0] || {}, s = 1, c = arguments.length, u = !1;
                    for ("boolean" == typeof a && (u = a,
                    a = arguments[s] || {},
                    s++),
                    "object" == typeof a || g(a) || (a = {}),
                    s === c && (a = this,
                    s--); s < c; s++)
                        if (null != (e = arguments[s]))
                            for (t in e)
                                r = e[t],
                                "__proto__" !== t && a !== r && (u && r && (j.isPlainObject(r) || (o = Array.isArray(r))) ? (n = a[t],
                                i = o && !Array.isArray(n) ? [] : o || j.isPlainObject(n) ? n : {},
                                o = !1,
                                a[t] = j.extend(u, i, r)) : void 0 !== r && (a[t] = r));
                    return a
                }
                ,
                j.extend({
                    expando: "jQuery" + (C + Math.random()).replace(/\D/g, ""),
                    isReady: !0,
                    error: function(e) {
                        throw new Error(e)
                    },
                    noop: function() {},
                    isPlainObject: function(e) {
                        var t, n;
                        return !(!e || "[object Object]" !== p.call(e)) && (!(t = a(e)) || "function" == typeof (n = f.call(t, "constructor") && t.constructor) && h.call(n) === m)
                    },
                    isEmptyObject: function(e) {
                        var t;
                        for (t in e)
                            return !1;
                        return !0
                    },
                    globalEval: function(e, t, n) {
                        w(e, {
                            nonce: t && t.nonce
                        }, n)
                    },
                    each: function(e, t) {
                        var n, r = 0;
                        if (T(e))
                            for (n = e.length; r < n && !1 !== t.call(e[r], r, e[r]); r++)
                                ;
                        else
                            for (r in e)
                                if (!1 === t.call(e[r], r, e[r]))
                                    break;
                        return e
                    },
                    makeArray: function(e, t) {
                        var n = t || [];
                        return null != e && (T(Object(e)) ? j.merge(n, "string" == typeof e ? [e] : e) : u.call(n, e)),
                        n
                    },
                    inArray: function(e, t, n) {
                        return null == t ? -1 : l.call(t, e, n)
                    },
                    merge: function(e, t) {
                        for (var n = +t.length, r = 0, o = e.length; r < n; r++)
                            e[o++] = t[r];
                        return e.length = o,
                        e
                    },
                    grep: function(e, t, n) {
                        for (var r = [], o = 0, i = e.length, a = !n; o < i; o++)
                            !t(e[o], o) !== a && r.push(e[o]);
                        return r
                    },
                    map: function(e, t, n) {
                        var r, o, i = 0, a = [];
                        if (T(e))
                            for (r = e.length; i < r; i++)
                                null != (o = t(e[i], i, n)) && a.push(o);
                        else
                            for (i in e)
                                null != (o = t(e[i], i, n)) && a.push(o);
                        return c(a)
                    },
                    guid: 1,
                    support: v
                }),
                "function" == typeof Symbol && (j.fn[Symbol.iterator] = i[Symbol.iterator]),
                j.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), (function(e, t) {
                    d["[object " + t + "]"] = t.toLowerCase()
                }
                ));
                var S = function(e) {
                    var t, n, r, o, i, a, s, c, u, l, d, p, f, h, m, v, g, y, b, x = "sizzle" + 1 * new Date, w = e.document, k = 0, C = 0, j = ce(), T = ce(), S = ce(), A = ce(), E = function(e, t) {
                        return e === t && (d = !0),
                        0
                    }, N = {}.hasOwnProperty, q = [], D = q.pop, O = q.push, P = q.push, _ = q.slice, L = function(e, t) {
                        for (var n = 0, r = e.length; n < r; n++)
                            if (e[n] === t)
                                return n;
                        return -1
                    }, $ = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped", H = "[\\x20\\t\\r\\n\\f]", R = "(?:\\\\[\\da-fA-F]{1,6}[\\x20\\t\\r\\n\\f]?|\\\\[^\\r\\n\\f]|[\\w-]|[^\0-\\x7f])+", I = "\\[[\\x20\\t\\r\\n\\f]*(" + R + ")(?:" + H + "*([*^$|!~]?=)" + H + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + R + "))|)" + H + "*\\]", M = ":(" + R + ")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|" + I + ")*)|.*)\\)|)", W = new RegExp(H + "+","g"), F = new RegExp("^[\\x20\\t\\r\\n\\f]+|((?:^|[^\\\\])(?:\\\\.)*)[\\x20\\t\\r\\n\\f]+$","g"), B = new RegExp("^[\\x20\\t\\r\\n\\f]*,[\\x20\\t\\r\\n\\f]*"), Q = new RegExp("^[\\x20\\t\\r\\n\\f]*([>+~]|[\\x20\\t\\r\\n\\f])[\\x20\\t\\r\\n\\f]*"), z = new RegExp(H + "|>"), U = new RegExp(M), X = new RegExp("^" + R + "$"), V = {
                        ID: new RegExp("^#(" + R + ")"),
                        CLASS: new RegExp("^\\.(" + R + ")"),
                        TAG: new RegExp("^(" + R + "|[*])"),
                        ATTR: new RegExp("^" + I),
                        PSEUDO: new RegExp("^" + M),
                        CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\([\\x20\\t\\r\\n\\f]*(even|odd|(([+-]|)(\\d*)n|)[\\x20\\t\\r\\n\\f]*(?:([+-]|)[\\x20\\t\\r\\n\\f]*(\\d+)|))[\\x20\\t\\r\\n\\f]*\\)|)","i"),
                        bool: new RegExp("^(?:" + $ + ")$","i"),
                        needsContext: new RegExp("^[\\x20\\t\\r\\n\\f]*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\([\\x20\\t\\r\\n\\f]*((?:-\\d)?\\d*)[\\x20\\t\\r\\n\\f]*\\)|)(?=[^-]|$)","i")
                    }, G = /HTML$/i, J = /^(?:input|select|textarea|button)$/i, Y = /^h\d$/i, K = /^[^{]+\{\s*\[native \w/, Z = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, ee = /[+~]/, te = new RegExp("\\\\[\\da-fA-F]{1,6}[\\x20\\t\\r\\n\\f]?|\\\\([^\\r\\n\\f])","g"), ne = function(e, t) {
                        var n = "0x" + e.slice(1) - 65536;
                        return t || (n < 0 ? String.fromCharCode(n + 65536) : String.fromCharCode(n >> 10 | 55296, 1023 & n | 56320))
                    }, re = /([\0-\x1f\x7f]|^-?\d)|^-$|[^\0-\x1f\x7f-\uFFFF\w-]/g, oe = function(e, t) {
                        return t ? "\0" === e ? "�" : e.slice(0, -1) + "\\" + e.charCodeAt(e.length - 1).toString(16) + " " : "\\" + e
                    }, ie = function() {
                        p()
                    }, ae = xe((function(e) {
                        return !0 === e.disabled && "fieldset" === e.nodeName.toLowerCase()
                    }
                    ), {
                        dir: "parentNode",
                        next: "legend"
                    });
                    try {
                        P.apply(q = _.call(w.childNodes), w.childNodes),
                        q[w.childNodes.length].nodeType
                    } catch (e) {
                        P = {
                            apply: q.length ? function(e, t) {
                                O.apply(e, _.call(t))
                            }
                            : function(e, t) {
                                for (var n = e.length, r = 0; e[n++] = t[r++]; )
                                    ;
                                e.length = n - 1
                            }
                        }
                    }
                    function se(e, t, r, o) {
                        var i, s, u, l, d, h, g, y = t && t.ownerDocument, w = t ? t.nodeType : 9;
                        if (r = r || [],
                        "string" != typeof e || !e || 1 !== w && 9 !== w && 11 !== w)
                            return r;
                        if (!o && (p(t),
                        t = t || f,
                        m)) {
                            if (11 !== w && (d = Z.exec(e)))
                                if (i = d[1]) {
                                    if (9 === w) {
                                        if (!(u = t.getElementById(i)))
                                            return r;
                                        if (u.id === i)
                                            return r.push(u),
                                            r
                                    } else if (y && (u = y.getElementById(i)) && b(t, u) && u.id === i)
                                        return r.push(u),
                                        r
                                } else {
                                    if (d[2])
                                        return P.apply(r, t.getElementsByTagName(e)),
                                        r;
                                    if ((i = d[3]) && n.getElementsByClassName && t.getElementsByClassName)
                                        return P.apply(r, t.getElementsByClassName(i)),
                                        r
                                }
                            if (n.qsa && !A[e + " "] && (!v || !v.test(e)) && (1 !== w || "object" !== t.nodeName.toLowerCase())) {
                                if (g = e,
                                y = t,
                                1 === w && (z.test(e) || Q.test(e))) {
                                    for ((y = ee.test(e) && ge(t.parentNode) || t) === t && n.scope || ((l = t.getAttribute("id")) ? l = l.replace(re, oe) : t.setAttribute("id", l = x)),
                                    s = (h = a(e)).length; s--; )
                                        h[s] = (l ? "#" + l : ":scope") + " " + be(h[s]);
                                    g = h.join(",")
                                }
                                try {
                                    return P.apply(r, y.querySelectorAll(g)),
                                    r
                                } catch (t) {
                                    A(e, !0)
                                } finally {
                                    l === x && t.removeAttribute("id")
                                }
                            }
                        }
                        return c(e.replace(F, "$1"), t, r, o)
                    }
                    function ce() {
                        var e = [];
                        return function t(n, o) {
                            return e.push(n + " ") > r.cacheLength && delete t[e.shift()],
                            t[n + " "] = o
                        }
                    }
                    function ue(e) {
                        return e[x] = !0,
                        e
                    }
                    function le(e) {
                        var t = f.createElement("fieldset");
                        try {
                            return !!e(t)
                        } catch (e) {
                            return !1
                        } finally {
                            t.parentNode && t.parentNode.removeChild(t),
                            t = null
                        }
                    }
                    function de(e, t) {
                        for (var n = e.split("|"), o = n.length; o--; )
                            r.attrHandle[n[o]] = t
                    }
                    function pe(e, t) {
                        var n = t && e
                          , r = n && 1 === e.nodeType && 1 === t.nodeType && e.sourceIndex - t.sourceIndex;
                        if (r)
                            return r;
                        if (n)
                            for (; n = n.nextSibling; )
                                if (n === t)
                                    return -1;
                        return e ? 1 : -1
                    }
                    function fe(e) {
                        return function(t) {
                            return "input" === t.nodeName.toLowerCase() && t.type === e
                        }
                    }
                    function he(e) {
                        return function(t) {
                            var n = t.nodeName.toLowerCase();
                            return ("input" === n || "button" === n) && t.type === e
                        }
                    }
                    function me(e) {
                        return function(t) {
                            return "form"in t ? t.parentNode && !1 === t.disabled ? "label"in t ? "label"in t.parentNode ? t.parentNode.disabled === e : t.disabled === e : t.isDisabled === e || t.isDisabled !== !e && ae(t) === e : t.disabled === e : "label"in t && t.disabled === e
                        }
                    }
                    function ve(e) {
                        return ue((function(t) {
                            return t = +t,
                            ue((function(n, r) {
                                for (var o, i = e([], n.length, t), a = i.length; a--; )
                                    n[o = i[a]] && (n[o] = !(r[o] = n[o]))
                            }
                            ))
                        }
                        ))
                    }
                    function ge(e) {
                        return e && void 0 !== e.getElementsByTagName && e
                    }
                    for (t in n = se.support = {},
                    i = se.isXML = function(e) {
                        var t = e.namespaceURI
                          , n = (e.ownerDocument || e).documentElement;
                        return !G.test(t || n && n.nodeName || "HTML")
                    }
                    ,
                    p = se.setDocument = function(e) {
                        var t, o, a = e ? e.ownerDocument || e : w;
                        return a != f && 9 === a.nodeType && a.documentElement ? (h = (f = a).documentElement,
                        m = !i(f),
                        w != f && (o = f.defaultView) && o.top !== o && (o.addEventListener ? o.addEventListener("unload", ie, !1) : o.attachEvent && o.attachEvent("onunload", ie)),
                        n.scope = le((function(e) {
                            return h.appendChild(e).appendChild(f.createElement("div")),
                            void 0 !== e.querySelectorAll && !e.querySelectorAll(":scope fieldset div").length
                        }
                        )),
                        n.attributes = le((function(e) {
                            return e.className = "i",
                            !e.getAttribute("className")
                        }
                        )),
                        n.getElementsByTagName = le((function(e) {
                            return e.appendChild(f.createComment("")),
                            !e.getElementsByTagName("*").length
                        }
                        )),
                        n.getElementsByClassName = K.test(f.getElementsByClassName),
                        n.getById = le((function(e) {
                            return h.appendChild(e).id = x,
                            !f.getElementsByName || !f.getElementsByName(x).length
                        }
                        )),
                        n.getById ? (r.filter.ID = function(e) {
                            var t = e.replace(te, ne);
                            return function(e) {
                                return e.getAttribute("id") === t
                            }
                        }
                        ,
                        r.find.ID = function(e, t) {
                            if (void 0 !== t.getElementById && m) {
                                var n = t.getElementById(e);
                                return n ? [n] : []
                            }
                        }
                        ) : (r.filter.ID = function(e) {
                            var t = e.replace(te, ne);
                            return function(e) {
                                var n = void 0 !== e.getAttributeNode && e.getAttributeNode("id");
                                return n && n.value === t
                            }
                        }
                        ,
                        r.find.ID = function(e, t) {
                            if (void 0 !== t.getElementById && m) {
                                var n, r, o, i = t.getElementById(e);
                                if (i) {
                                    if ((n = i.getAttributeNode("id")) && n.value === e)
                                        return [i];
                                    for (o = t.getElementsByName(e),
                                    r = 0; i = o[r++]; )
                                        if ((n = i.getAttributeNode("id")) && n.value === e)
                                            return [i]
                                }
                                return []
                            }
                        }
                        ),
                        r.find.TAG = n.getElementsByTagName ? function(e, t) {
                            return void 0 !== t.getElementsByTagName ? t.getElementsByTagName(e) : n.qsa ? t.querySelectorAll(e) : void 0
                        }
                        : function(e, t) {
                            var n, r = [], o = 0, i = t.getElementsByTagName(e);
                            if ("*" === e) {
                                for (; n = i[o++]; )
                                    1 === n.nodeType && r.push(n);
                                return r
                            }
                            return i
                        }
                        ,
                        r.find.CLASS = n.getElementsByClassName && function(e, t) {
                            if (void 0 !== t.getElementsByClassName && m)
                                return t.getElementsByClassName(e)
                        }
                        ,
                        g = [],
                        v = [],
                        (n.qsa = K.test(f.querySelectorAll)) && (le((function(e) {
                            var t;
                            h.appendChild(e).innerHTML = "<a id='" + x + "'></a><select id='" + x + "-\r\\' msallowcapture=''><option selected=''></option></select>",
                            e.querySelectorAll("[msallowcapture^='']").length && v.push("[*^$]=[\\x20\\t\\r\\n\\f]*(?:''|\"\")"),
                            e.querySelectorAll("[selected]").length || v.push("\\[[\\x20\\t\\r\\n\\f]*(?:value|" + $ + ")"),
                            e.querySelectorAll("[id~=" + x + "-]").length || v.push("~="),
                            (t = f.createElement("input")).setAttribute("name", ""),
                            e.appendChild(t),
                            e.querySelectorAll("[name='']").length || v.push("\\[[\\x20\\t\\r\\n\\f]*name[\\x20\\t\\r\\n\\f]*=[\\x20\\t\\r\\n\\f]*(?:''|\"\")"),
                            e.querySelectorAll(":checked").length || v.push(":checked"),
                            e.querySelectorAll("a#" + x + "+*").length || v.push(".#.+[+~]"),
                            e.querySelectorAll("\\\f"),
                            v.push("[\\r\\n\\f]")
                        }
                        )),
                        le((function(e) {
                            e.innerHTML = "<a href='' disabled='disabled'></a><select disabled='disabled'><option/></select>";
                            var t = f.createElement("input");
                            t.setAttribute("type", "hidden"),
                            e.appendChild(t).setAttribute("name", "D"),
                            e.querySelectorAll("[name=d]").length && v.push("name[\\x20\\t\\r\\n\\f]*[*^$|!~]?="),
                            2 !== e.querySelectorAll(":enabled").length && v.push(":enabled", ":disabled"),
                            h.appendChild(e).disabled = !0,
                            2 !== e.querySelectorAll(":disabled").length && v.push(":enabled", ":disabled"),
                            e.querySelectorAll("*,:x"),
                            v.push(",.*:")
                        }
                        ))),
                        (n.matchesSelector = K.test(y = h.matches || h.webkitMatchesSelector || h.mozMatchesSelector || h.oMatchesSelector || h.msMatchesSelector)) && le((function(e) {
                            n.disconnectedMatch = y.call(e, "*"),
                            y.call(e, "[s!='']:x"),
                            g.push("!=", M)
                        }
                        )),
                        v = v.length && new RegExp(v.join("|")),
                        g = g.length && new RegExp(g.join("|")),
                        t = K.test(h.compareDocumentPosition),
                        b = t || K.test(h.contains) ? function(e, t) {
                            var n = 9 === e.nodeType ? e.documentElement : e
                              , r = t && t.parentNode;
                            return e === r || !(!r || 1 !== r.nodeType || !(n.contains ? n.contains(r) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(r)))
                        }
                        : function(e, t) {
                            if (t)
                                for (; t = t.parentNode; )
                                    if (t === e)
                                        return !0;
                            return !1
                        }
                        ,
                        E = t ? function(e, t) {
                            if (e === t)
                                return d = !0,
                                0;
                            var r = !e.compareDocumentPosition - !t.compareDocumentPosition;
                            return r || (1 & (r = (e.ownerDocument || e) == (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1) || !n.sortDetached && t.compareDocumentPosition(e) === r ? e == f || e.ownerDocument == w && b(w, e) ? -1 : t == f || t.ownerDocument == w && b(w, t) ? 1 : l ? L(l, e) - L(l, t) : 0 : 4 & r ? -1 : 1)
                        }
                        : function(e, t) {
                            if (e === t)
                                return d = !0,
                                0;
                            var n, r = 0, o = e.parentNode, i = t.parentNode, a = [e], s = [t];
                            if (!o || !i)
                                return e == f ? -1 : t == f ? 1 : o ? -1 : i ? 1 : l ? L(l, e) - L(l, t) : 0;
                            if (o === i)
                                return pe(e, t);
                            for (n = e; n = n.parentNode; )
                                a.unshift(n);
                            for (n = t; n = n.parentNode; )
                                s.unshift(n);
                            for (; a[r] === s[r]; )
                                r++;
                            return r ? pe(a[r], s[r]) : a[r] == w ? -1 : s[r] == w ? 1 : 0
                        }
                        ,
                        f) : f
                    }
                    ,
                    se.matches = function(e, t) {
                        return se(e, null, null, t)
                    }
                    ,
                    se.matchesSelector = function(e, t) {
                        if (p(e),
                        n.matchesSelector && m && !A[t + " "] && (!g || !g.test(t)) && (!v || !v.test(t)))
                            try {
                                var r = y.call(e, t);
                                if (r || n.disconnectedMatch || e.document && 11 !== e.document.nodeType)
                                    return r
                            } catch (e) {
                                A(t, !0)
                            }
                        return se(t, f, null, [e]).length > 0
                    }
                    ,
                    se.contains = function(e, t) {
                        return (e.ownerDocument || e) != f && p(e),
                        b(e, t)
                    }
                    ,
                    se.attr = function(e, t) {
                        (e.ownerDocument || e) != f && p(e);
                        var o = r.attrHandle[t.toLowerCase()]
                          , i = o && N.call(r.attrHandle, t.toLowerCase()) ? o(e, t, !m) : void 0;
                        return void 0 !== i ? i : n.attributes || !m ? e.getAttribute(t) : (i = e.getAttributeNode(t)) && i.specified ? i.value : null
                    }
                    ,
                    se.escape = function(e) {
                        return (e + "").replace(re, oe)
                    }
                    ,
                    se.error = function(e) {
                        throw new Error("Syntax error, unrecognized expression: " + e)
                    }
                    ,
                    se.uniqueSort = function(e) {
                        var t, r = [], o = 0, i = 0;
                        if (d = !n.detectDuplicates,
                        l = !n.sortStable && e.slice(0),
                        e.sort(E),
                        d) {
                            for (; t = e[i++]; )
                                t === e[i] && (o = r.push(i));
                            for (; o--; )
                                e.splice(r[o], 1)
                        }
                        return l = null,
                        e
                    }
                    ,
                    o = se.getText = function(e) {
                        var t, n = "", r = 0, i = e.nodeType;
                        if (i) {
                            if (1 === i || 9 === i || 11 === i) {
                                if ("string" == typeof e.textContent)
                                    return e.textContent;
                                for (e = e.firstChild; e; e = e.nextSibling)
                                    n += o(e)
                            } else if (3 === i || 4 === i)
                                return e.nodeValue
                        } else
                            for (; t = e[r++]; )
                                n += o(t);
                        return n
                    }
                    ,
                    (r = se.selectors = {
                        cacheLength: 50,
                        createPseudo: ue,
                        match: V,
                        attrHandle: {},
                        find: {},
                        relative: {
                            ">": {
                                dir: "parentNode",
                                first: !0
                            },
                            " ": {
                                dir: "parentNode"
                            },
                            "+": {
                                dir: "previousSibling",
                                first: !0
                            },
                            "~": {
                                dir: "previousSibling"
                            }
                        },
                        preFilter: {
                            ATTR: function(e) {
                                return e[1] = e[1].replace(te, ne),
                                e[3] = (e[3] || e[4] || e[5] || "").replace(te, ne),
                                "~=" === e[2] && (e[3] = " " + e[3] + " "),
                                e.slice(0, 4)
                            },
                            CHILD: function(e) {
                                return e[1] = e[1].toLowerCase(),
                                "nth" === e[1].slice(0, 3) ? (e[3] || se.error(e[0]),
                                e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])),
                                e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && se.error(e[0]),
                                e
                            },
                            PSEUDO: function(e) {
                                var t, n = !e[6] && e[2];
                                return V.CHILD.test(e[0]) ? null : (e[3] ? e[2] = e[4] || e[5] || "" : n && U.test(n) && (t = a(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t),
                                e[2] = n.slice(0, t)),
                                e.slice(0, 3))
                            }
                        },
                        filter: {
                            TAG: function(e) {
                                var t = e.replace(te, ne).toLowerCase();
                                return "*" === e ? function() {
                                    return !0
                                }
                                : function(e) {
                                    return e.nodeName && e.nodeName.toLowerCase() === t
                                }
                            },
                            CLASS: function(e) {
                                var t = j[e + " "];
                                return t || (t = new RegExp("(^|[\\x20\\t\\r\\n\\f])" + e + "(" + H + "|$)")) && j(e, (function(e) {
                                    return t.test("string" == typeof e.className && e.className || void 0 !== e.getAttribute && e.getAttribute("class") || "")
                                }
                                ))
                            },
                            ATTR: function(e, t, n) {
                                return function(r) {
                                    var o = se.attr(r, e);
                                    return null == o ? "!=" === t : !t || (o += "",
                                    "=" === t ? o === n : "!=" === t ? o !== n : "^=" === t ? n && 0 === o.indexOf(n) : "*=" === t ? n && o.indexOf(n) > -1 : "$=" === t ? n && o.slice(-n.length) === n : "~=" === t ? (" " + o.replace(W, " ") + " ").indexOf(n) > -1 : "|=" === t && (o === n || o.slice(0, n.length + 1) === n + "-"))
                                }
                            },
                            CHILD: function(e, t, n, r, o) {
                                var i = "nth" !== e.slice(0, 3)
                                  , a = "last" !== e.slice(-4)
                                  , s = "of-type" === t;
                                return 1 === r && 0 === o ? function(e) {
                                    return !!e.parentNode
                                }
                                : function(t, n, c) {
                                    var u, l, d, p, f, h, m = i !== a ? "nextSibling" : "previousSibling", v = t.parentNode, g = s && t.nodeName.toLowerCase(), y = !c && !s, b = !1;
                                    if (v) {
                                        if (i) {
                                            for (; m; ) {
                                                for (p = t; p = p[m]; )
                                                    if (s ? p.nodeName.toLowerCase() === g : 1 === p.nodeType)
                                                        return !1;
                                                h = m = "only" === e && !h && "nextSibling"
                                            }
                                            return !0
                                        }
                                        if (h = [a ? v.firstChild : v.lastChild],
                                        a && y) {
                                            for (b = (f = (u = (l = (d = (p = v)[x] || (p[x] = {}))[p.uniqueID] || (d[p.uniqueID] = {}))[e] || [])[0] === k && u[1]) && u[2],
                                            p = f && v.childNodes[f]; p = ++f && p && p[m] || (b = f = 0) || h.pop(); )
                                                if (1 === p.nodeType && ++b && p === t) {
                                                    l[e] = [k, f, b];
                                                    break
                                                }
                                        } else if (y && (b = f = (u = (l = (d = (p = t)[x] || (p[x] = {}))[p.uniqueID] || (d[p.uniqueID] = {}))[e] || [])[0] === k && u[1]),
                                        !1 === b)
                                            for (; (p = ++f && p && p[m] || (b = f = 0) || h.pop()) && ((s ? p.nodeName.toLowerCase() !== g : 1 !== p.nodeType) || !++b || (y && ((l = (d = p[x] || (p[x] = {}))[p.uniqueID] || (d[p.uniqueID] = {}))[e] = [k, b]),
                                            p !== t)); )
                                                ;
                                        return (b -= o) === r || b % r == 0 && b / r >= 0
                                    }
                                }
                            },
                            PSEUDO: function(e, t) {
                                var n, o = r.pseudos[e] || r.setFilters[e.toLowerCase()] || se.error("unsupported pseudo: " + e);
                                return o[x] ? o(t) : o.length > 1 ? (n = [e, e, "", t],
                                r.setFilters.hasOwnProperty(e.toLowerCase()) ? ue((function(e, n) {
                                    for (var r, i = o(e, t), a = i.length; a--; )
                                        e[r = L(e, i[a])] = !(n[r] = i[a])
                                }
                                )) : function(e) {
                                    return o(e, 0, n)
                                }
                                ) : o
                            }
                        },
                        pseudos: {
                            not: ue((function(e) {
                                var t = []
                                  , n = []
                                  , r = s(e.replace(F, "$1"));
                                return r[x] ? ue((function(e, t, n, o) {
                                    for (var i, a = r(e, null, o, []), s = e.length; s--; )
                                        (i = a[s]) && (e[s] = !(t[s] = i))
                                }
                                )) : function(e, o, i) {
                                    return t[0] = e,
                                    r(t, null, i, n),
                                    t[0] = null,
                                    !n.pop()
                                }
                            }
                            )),
                            has: ue((function(e) {
                                return function(t) {
                                    return se(e, t).length > 0
                                }
                            }
                            )),
                            contains: ue((function(e) {
                                return e = e.replace(te, ne),
                                function(t) {
                                    return (t.textContent || o(t)).indexOf(e) > -1
                                }
                            }
                            )),
                            lang: ue((function(e) {
                                return X.test(e || "") || se.error("unsupported lang: " + e),
                                e = e.replace(te, ne).toLowerCase(),
                                function(t) {
                                    var n;
                                    do {
                                        if (n = m ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang"))
                                            return (n = n.toLowerCase()) === e || 0 === n.indexOf(e + "-")
                                    } while ((t = t.parentNode) && 1 === t.nodeType);
                                    return !1
                                }
                            }
                            )),
                            target: function(t) {
                                var n = e.location && e.location.hash;
                                return n && n.slice(1) === t.id
                            },
                            root: function(e) {
                                return e === h
                            },
                            focus: function(e) {
                                return e === f.activeElement && (!f.hasFocus || f.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                            },
                            enabled: me(!1),
                            disabled: me(!0),
                            checked: function(e) {
                                var t = e.nodeName.toLowerCase();
                                return "input" === t && !!e.checked || "option" === t && !!e.selected
                            },
                            selected: function(e) {
                                return e.parentNode && e.parentNode.selectedIndex,
                                !0 === e.selected
                            },
                            empty: function(e) {
                                for (e = e.firstChild; e; e = e.nextSibling)
                                    if (e.nodeType < 6)
                                        return !1;
                                return !0
                            },
                            parent: function(e) {
                                return !r.pseudos.empty(e)
                            },
                            header: function(e) {
                                return Y.test(e.nodeName)
                            },
                            input: function(e) {
                                return J.test(e.nodeName)
                            },
                            button: function(e) {
                                var t = e.nodeName.toLowerCase();
                                return "input" === t && "button" === e.type || "button" === t
                            },
                            text: function(e) {
                                var t;
                                return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                            },
                            first: ve((function() {
                                return [0]
                            }
                            )),
                            last: ve((function(e, t) {
                                return [t - 1]
                            }
                            )),
                            eq: ve((function(e, t, n) {
                                return [n < 0 ? n + t : n]
                            }
                            )),
                            even: ve((function(e, t) {
                                for (var n = 0; n < t; n += 2)
                                    e.push(n);
                                return e
                            }
                            )),
                            odd: ve((function(e, t) {
                                for (var n = 1; n < t; n += 2)
                                    e.push(n);
                                return e
                            }
                            )),
                            lt: ve((function(e, t, n) {
                                for (var r = n < 0 ? n + t : n > t ? t : n; --r >= 0; )
                                    e.push(r);
                                return e
                            }
                            )),
                            gt: ve((function(e, t, n) {
                                for (var r = n < 0 ? n + t : n; ++r < t; )
                                    e.push(r);
                                return e
                            }
                            ))
                        }
                    }).pseudos.nth = r.pseudos.eq,
                    {
                        radio: !0,
                        checkbox: !0,
                        file: !0,
                        password: !0,
                        image: !0
                    })
                        r.pseudos[t] = fe(t);
                    for (t in {
                        submit: !0,
                        reset: !0
                    })
                        r.pseudos[t] = he(t);
                    function ye() {}
                    function be(e) {
                        for (var t = 0, n = e.length, r = ""; t < n; t++)
                            r += e[t].value;
                        return r
                    }
                    function xe(e, t, n) {
                        var r = t.dir
                          , o = t.next
                          , i = o || r
                          , a = n && "parentNode" === i
                          , s = C++;
                        return t.first ? function(t, n, o) {
                            for (; t = t[r]; )
                                if (1 === t.nodeType || a)
                                    return e(t, n, o);
                            return !1
                        }
                        : function(t, n, c) {
                            var u, l, d, p = [k, s];
                            if (c) {
                                for (; t = t[r]; )
                                    if ((1 === t.nodeType || a) && e(t, n, c))
                                        return !0
                            } else
                                for (; t = t[r]; )
                                    if (1 === t.nodeType || a)
                                        if (l = (d = t[x] || (t[x] = {}))[t.uniqueID] || (d[t.uniqueID] = {}),
                                        o && o === t.nodeName.toLowerCase())
                                            t = t[r] || t;
                                        else {
                                            if ((u = l[i]) && u[0] === k && u[1] === s)
                                                return p[2] = u[2];
                                            if (l[i] = p,
                                            p[2] = e(t, n, c))
                                                return !0
                                        }
                            return !1
                        }
                    }
                    function we(e) {
                        return e.length > 1 ? function(t, n, r) {
                            for (var o = e.length; o--; )
                                if (!e[o](t, n, r))
                                    return !1;
                            return !0
                        }
                        : e[0]
                    }
                    function ke(e, t, n, r, o) {
                        for (var i, a = [], s = 0, c = e.length, u = null != t; s < c; s++)
                            (i = e[s]) && (n && !n(i, r, o) || (a.push(i),
                            u && t.push(s)));
                        return a
                    }
                    function Ce(e, t, n, r, o, i) {
                        return r && !r[x] && (r = Ce(r)),
                        o && !o[x] && (o = Ce(o, i)),
                        ue((function(i, a, s, c) {
                            var u, l, d, p = [], f = [], h = a.length, m = i || function(e, t, n) {
                                for (var r = 0, o = t.length; r < o; r++)
                                    se(e, t[r], n);
                                return n
                            }(t || "*", s.nodeType ? [s] : s, []), v = !e || !i && t ? m : ke(m, p, e, s, c), g = n ? o || (i ? e : h || r) ? [] : a : v;
                            if (n && n(v, g, s, c),
                            r)
                                for (u = ke(g, f),
                                r(u, [], s, c),
                                l = u.length; l--; )
                                    (d = u[l]) && (g[f[l]] = !(v[f[l]] = d));
                            if (i) {
                                if (o || e) {
                                    if (o) {
                                        for (u = [],
                                        l = g.length; l--; )
                                            (d = g[l]) && u.push(v[l] = d);
                                        o(null, g = [], u, c)
                                    }
                                    for (l = g.length; l--; )
                                        (d = g[l]) && (u = o ? L(i, d) : p[l]) > -1 && (i[u] = !(a[u] = d))
                                }
                            } else
                                g = ke(g === a ? g.splice(h, g.length) : g),
                                o ? o(null, a, g, c) : P.apply(a, g)
                        }
                        ))
                    }
                    function je(e) {
                        for (var t, n, o, i = e.length, a = r.relative[e[0].type], s = a || r.relative[" "], c = a ? 1 : 0, l = xe((function(e) {
                            return e === t
                        }
                        ), s, !0), d = xe((function(e) {
                            return L(t, e) > -1
                        }
                        ), s, !0), p = [function(e, n, r) {
                            var o = !a && (r || n !== u) || ((t = n).nodeType ? l(e, n, r) : d(e, n, r));
                            return t = null,
                            o
                        }
                        ]; c < i; c++)
                            if (n = r.relative[e[c].type])
                                p = [xe(we(p), n)];
                            else {
                                if ((n = r.filter[e[c].type].apply(null, e[c].matches))[x]) {
                                    for (o = ++c; o < i && !r.relative[e[o].type]; o++)
                                        ;
                                    return Ce(c > 1 && we(p), c > 1 && be(e.slice(0, c - 1).concat({
                                        value: " " === e[c - 2].type ? "*" : ""
                                    })).replace(F, "$1"), n, c < o && je(e.slice(c, o)), o < i && je(e = e.slice(o)), o < i && be(e))
                                }
                                p.push(n)
                            }
                        return we(p)
                    }
                    return ye.prototype = r.filters = r.pseudos,
                    r.setFilters = new ye,
                    a = se.tokenize = function(e, t) {
                        var n, o, i, a, s, c, u, l = T[e + " "];
                        if (l)
                            return t ? 0 : l.slice(0);
                        for (s = e,
                        c = [],
                        u = r.preFilter; s; ) {
                            for (a in n && !(o = B.exec(s)) || (o && (s = s.slice(o[0].length) || s),
                            c.push(i = [])),
                            n = !1,
                            (o = Q.exec(s)) && (n = o.shift(),
                            i.push({
                                value: n,
                                type: o[0].replace(F, " ")
                            }),
                            s = s.slice(n.length)),
                            r.filter)
                                !(o = V[a].exec(s)) || u[a] && !(o = u[a](o)) || (n = o.shift(),
                                i.push({
                                    value: n,
                                    type: a,
                                    matches: o
                                }),
                                s = s.slice(n.length));
                            if (!n)
                                break
                        }
                        return t ? s.length : s ? se.error(e) : T(e, c).slice(0)
                    }
                    ,
                    s = se.compile = function(e, t) {
                        var n, o = [], i = [], s = S[e + " "];
                        if (!s) {
                            for (t || (t = a(e)),
                            n = t.length; n--; )
                                (s = je(t[n]))[x] ? o.push(s) : i.push(s);
                            (s = S(e, function(e, t) {
                                var n = t.length > 0
                                  , o = e.length > 0
                                  , i = function(i, a, s, c, l) {
                                    var d, h, v, g = 0, y = "0", b = i && [], x = [], w = u, C = i || o && r.find.TAG("*", l), j = k += null == w ? 1 : Math.random() || .1, T = C.length;
                                    for (l && (u = a == f || a || l); y !== T && null != (d = C[y]); y++) {
                                        if (o && d) {
                                            for (h = 0,
                                            a || d.ownerDocument == f || (p(d),
                                            s = !m); v = e[h++]; )
                                                if (v(d, a || f, s)) {
                                                    c.push(d);
                                                    break
                                                }
                                            l && (k = j)
                                        }
                                        n && ((d = !v && d) && g--,
                                        i && b.push(d))
                                    }
                                    if (g += y,
                                    n && y !== g) {
                                        for (h = 0; v = t[h++]; )
                                            v(b, x, a, s);
                                        if (i) {
                                            if (g > 0)
                                                for (; y--; )
                                                    b[y] || x[y] || (x[y] = D.call(c));
                                            x = ke(x)
                                        }
                                        P.apply(c, x),
                                        l && !i && x.length > 0 && g + t.length > 1 && se.uniqueSort(c)
                                    }
                                    return l && (k = j,
                                    u = w),
                                    b
                                };
                                return n ? ue(i) : i
                            }(i, o))).selector = e
                        }
                        return s
                    }
                    ,
                    c = se.select = function(e, t, n, o) {
                        var i, c, u, l, d, p = "function" == typeof e && e, f = !o && a(e = p.selector || e);
                        if (n = n || [],
                        1 === f.length) {
                            if ((c = f[0] = f[0].slice(0)).length > 2 && "ID" === (u = c[0]).type && 9 === t.nodeType && m && r.relative[c[1].type]) {
                                if (!(t = (r.find.ID(u.matches[0].replace(te, ne), t) || [])[0]))
                                    return n;
                                p && (t = t.parentNode),
                                e = e.slice(c.shift().value.length)
                            }
                            for (i = V.needsContext.test(e) ? 0 : c.length; i-- && (u = c[i],
                            !r.relative[l = u.type]); )
                                if ((d = r.find[l]) && (o = d(u.matches[0].replace(te, ne), ee.test(c[0].type) && ge(t.parentNode) || t))) {
                                    if (c.splice(i, 1),
                                    !(e = o.length && be(c)))
                                        return P.apply(n, o),
                                        n;
                                    break
                                }
                        }
                        return (p || s(e, f))(o, t, !m, n, !t || ee.test(e) && ge(t.parentNode) || t),
                        n
                    }
                    ,
                    n.sortStable = x.split("").sort(E).join("") === x,
                    n.detectDuplicates = !!d,
                    p(),
                    n.sortDetached = le((function(e) {
                        return 1 & e.compareDocumentPosition(f.createElement("fieldset"))
                    }
                    )),
                    le((function(e) {
                        return e.innerHTML = "<a href='#'></a>",
                        "#" === e.firstChild.getAttribute("href")
                    }
                    )) || de("type|href|height|width", (function(e, t, n) {
                        if (!n)
                            return e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
                    }
                    )),
                    n.attributes && le((function(e) {
                        return e.innerHTML = "<input/>",
                        e.firstChild.setAttribute("value", ""),
                        "" === e.firstChild.getAttribute("value")
                    }
                    )) || de("value", (function(e, t, n) {
                        if (!n && "input" === e.nodeName.toLowerCase())
                            return e.defaultValue
                    }
                    )),
                    le((function(e) {
                        return null == e.getAttribute("disabled")
                    }
                    )) || de($, (function(e, t, n) {
                        var r;
                        if (!n)
                            return !0 === e[t] ? t.toLowerCase() : (r = e.getAttributeNode(t)) && r.specified ? r.value : null
                    }
                    )),
                    se
                }(r);
                j.find = S,
                j.expr = S.selectors,
                j.expr[":"] = j.expr.pseudos,
                j.uniqueSort = j.unique = S.uniqueSort,
                j.text = S.getText,
                j.isXMLDoc = S.isXML,
                j.contains = S.contains,
                j.escapeSelector = S.escape;
                var A = function(e, t, n) {
                    for (var r = [], o = void 0 !== n; (e = e[t]) && 9 !== e.nodeType; )
                        if (1 === e.nodeType) {
                            if (o && j(e).is(n))
                                break;
                            r.push(e)
                        }
                    return r
                }
                  , E = function(e, t) {
                    for (var n = []; e; e = e.nextSibling)
                        1 === e.nodeType && e !== t && n.push(e);
                    return n
                }
                  , N = j.expr.match.needsContext;
                function q(e, t) {
                    return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
                }
                var D = /^<([a-z][^\/\0>:\x20\t\r\n\f]*)[\x20\t\r\n\f]*\/?>(?:<\/\1>|)$/i;
                function O(e, t, n) {
                    return g(t) ? j.grep(e, (function(e, r) {
                        return !!t.call(e, r, e) !== n
                    }
                    )) : t.nodeType ? j.grep(e, (function(e) {
                        return e === t !== n
                    }
                    )) : "string" != typeof t ? j.grep(e, (function(e) {
                        return l.call(t, e) > -1 !== n
                    }
                    )) : j.filter(t, e, n)
                }
                j.filter = function(e, t, n) {
                    var r = t[0];
                    return n && (e = ":not(" + e + ")"),
                    1 === t.length && 1 === r.nodeType ? j.find.matchesSelector(r, e) ? [r] : [] : j.find.matches(e, j.grep(t, (function(e) {
                        return 1 === e.nodeType
                    }
                    )))
                }
                ,
                j.fn.extend({
                    find: function(e) {
                        var t, n, r = this.length, o = this;
                        if ("string" != typeof e)
                            return this.pushStack(j(e).filter((function() {
                                for (t = 0; t < r; t++)
                                    if (j.contains(o[t], this))
                                        return !0
                            }
                            )));
                        for (n = this.pushStack([]),
                        t = 0; t < r; t++)
                            j.find(e, o[t], n);
                        return r > 1 ? j.uniqueSort(n) : n
                    },
                    filter: function(e) {
                        return this.pushStack(O(this, e || [], !1))
                    },
                    not: function(e) {
                        return this.pushStack(O(this, e || [], !0))
                    },
                    is: function(e) {
                        return !!O(this, "string" == typeof e && N.test(e) ? j(e) : e || [], !1).length
                    }
                });
                var P, _ = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]+))$/;
                (j.fn.init = function(e, t, n) {
                    var r, o;
                    if (!e)
                        return this;
                    if (n = n || P,
                    "string" == typeof e) {
                        if (!(r = "<" === e[0] && ">" === e[e.length - 1] && e.length >= 3 ? [null, e, null] : _.exec(e)) || !r[1] && t)
                            return !t || t.jquery ? (t || n).find(e) : this.constructor(t).find(e);
                        if (r[1]) {
                            if (t = t instanceof j ? t[0] : t,
                            j.merge(this, j.parseHTML(r[1], t && t.nodeType ? t.ownerDocument || t : b, !0)),
                            D.test(r[1]) && j.isPlainObject(t))
                                for (r in t)
                                    g(this[r]) ? this[r](t[r]) : this.attr(r, t[r]);
                            return this
                        }
                        return (o = b.getElementById(r[2])) && (this[0] = o,
                        this.length = 1),
                        this
                    }
                    return e.nodeType ? (this[0] = e,
                    this.length = 1,
                    this) : g(e) ? void 0 !== n.ready ? n.ready(e) : e(j) : j.makeArray(e, this)
                }
                ).prototype = j.fn,
                P = j(b);
                var L = /^(?:parents|prev(?:Until|All))/
                  , $ = {
                    children: !0,
                    contents: !0,
                    next: !0,
                    prev: !0
                };
                function H(e, t) {
                    for (; (e = e[t]) && 1 !== e.nodeType; )
                        ;
                    return e
                }
                j.fn.extend({
                    has: function(e) {
                        var t = j(e, this)
                          , n = t.length;
                        return this.filter((function() {
                            for (var e = 0; e < n; e++)
                                if (j.contains(this, t[e]))
                                    return !0
                        }
                        ))
                    },
                    closest: function(e, t) {
                        var n, r = 0, o = this.length, i = [], a = "string" != typeof e && j(e);
                        if (!N.test(e))
                            for (; r < o; r++)
                                for (n = this[r]; n && n !== t; n = n.parentNode)
                                    if (n.nodeType < 11 && (a ? a.index(n) > -1 : 1 === n.nodeType && j.find.matchesSelector(n, e))) {
                                        i.push(n);
                                        break
                                    }
                        return this.pushStack(i.length > 1 ? j.uniqueSort(i) : i)
                    },
                    index: function(e) {
                        return e ? "string" == typeof e ? l.call(j(e), this[0]) : l.call(this, e.jquery ? e[0] : e) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
                    },
                    add: function(e, t) {
                        return this.pushStack(j.uniqueSort(j.merge(this.get(), j(e, t))))
                    },
                    addBack: function(e) {
                        return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
                    }
                }),
                j.each({
                    parent: function(e) {
                        var t = e.parentNode;
                        return t && 11 !== t.nodeType ? t : null
                    },
                    parents: function(e) {
                        return A(e, "parentNode")
                    },
                    parentsUntil: function(e, t, n) {
                        return A(e, "parentNode", n)
                    },
                    next: function(e) {
                        return H(e, "nextSibling")
                    },
                    prev: function(e) {
                        return H(e, "previousSibling")
                    },
                    nextAll: function(e) {
                        return A(e, "nextSibling")
                    },
                    prevAll: function(e) {
                        return A(e, "previousSibling")
                    },
                    nextUntil: function(e, t, n) {
                        return A(e, "nextSibling", n)
                    },
                    prevUntil: function(e, t, n) {
                        return A(e, "previousSibling", n)
                    },
                    siblings: function(e) {
                        return E((e.parentNode || {}).firstChild, e)
                    },
                    children: function(e) {
                        return E(e.firstChild)
                    },
                    contents: function(e) {
                        return null != e.contentDocument && a(e.contentDocument) ? e.contentDocument : (q(e, "template") && (e = e.content || e),
                        j.merge([], e.childNodes))
                    }
                }, (function(e, t) {
                    j.fn[e] = function(n, r) {
                        var o = j.map(this, t, n);
                        return "Until" !== e.slice(-5) && (r = n),
                        r && "string" == typeof r && (o = j.filter(r, o)),
                        this.length > 1 && ($[e] || j.uniqueSort(o),
                        L.test(e) && o.reverse()),
                        this.pushStack(o)
                    }
                }
                ));
                var R = /[^\x20\t\r\n\f]+/g;
                function I(e) {
                    return e
                }
                function M(e) {
                    throw e
                }
                function W(e, t, n, r) {
                    var o;
                    try {
                        e && g(o = e.promise) ? o.call(e).done(t).fail(n) : e && g(o = e.then) ? o.call(e, t, n) : t.apply(void 0, [e].slice(r))
                    } catch (e) {
                        n.apply(void 0, [e])
                    }
                }
                j.Callbacks = function(e) {
                    e = "string" == typeof e ? function(e) {
                        var t = {};
                        return j.each(e.match(R) || [], (function(e, n) {
                            t[n] = !0
                        }
                        )),
                        t
                    }(e) : j.extend({}, e);
                    var t, n, r, o, i = [], a = [], s = -1, c = function() {
                        for (o = o || e.once,
                        r = t = !0; a.length; s = -1)
                            for (n = a.shift(); ++s < i.length; )
                                !1 === i[s].apply(n[0], n[1]) && e.stopOnFalse && (s = i.length,
                                n = !1);
                        e.memory || (n = !1),
                        t = !1,
                        o && (i = n ? [] : "")
                    }, u = {
                        add: function() {
                            return i && (n && !t && (s = i.length - 1,
                            a.push(n)),
                            function t(n) {
                                j.each(n, (function(n, r) {
                                    g(r) ? e.unique && u.has(r) || i.push(r) : r && r.length && "string" !== k(r) && t(r)
                                }
                                ))
                            }(arguments),
                            n && !t && c()),
                            this
                        },
                        remove: function() {
                            return j.each(arguments, (function(e, t) {
                                for (var n; (n = j.inArray(t, i, n)) > -1; )
                                    i.splice(n, 1),
                                    n <= s && s--
                            }
                            )),
                            this
                        },
                        has: function(e) {
                            return e ? j.inArray(e, i) > -1 : i.length > 0
                        },
                        empty: function() {
                            return i && (i = []),
                            this
                        },
                        disable: function() {
                            return o = a = [],
                            i = n = "",
                            this
                        },
                        disabled: function() {
                            return !i
                        },
                        lock: function() {
                            return o = a = [],
                            n || t || (i = n = ""),
                            this
                        },
                        locked: function() {
                            return !!o
                        },
                        fireWith: function(e, n) {
                            return o || (n = [e, (n = n || []).slice ? n.slice() : n],
                            a.push(n),
                            t || c()),
                            this
                        },
                        fire: function() {
                            return u.fireWith(this, arguments),
                            this
                        },
                        fired: function() {
                            return !!r
                        }
                    };
                    return u
                }
                ,
                j.extend({
                    Deferred: function(e) {
                        var t = [["notify", "progress", j.Callbacks("memory"), j.Callbacks("memory"), 2], ["resolve", "done", j.Callbacks("once memory"), j.Callbacks("once memory"), 0, "resolved"], ["reject", "fail", j.Callbacks("once memory"), j.Callbacks("once memory"), 1, "rejected"]]
                          , n = "pending"
                          , o = {
                            state: function() {
                                return n
                            },
                            always: function() {
                                return i.done(arguments).fail(arguments),
                                this
                            },
                            catch: function(e) {
                                return o.then(null, e)
                            },
                            pipe: function() {
                                var e = arguments;
                                return j.Deferred((function(n) {
                                    j.each(t, (function(t, r) {
                                        var o = g(e[r[4]]) && e[r[4]];
                                        i[r[1]]((function() {
                                            var e = o && o.apply(this, arguments);
                                            e && g(e.promise) ? e.promise().progress(n.notify).done(n.resolve).fail(n.reject) : n[r[0] + "With"](this, o ? [e] : arguments)
                                        }
                                        ))
                                    }
                                    )),
                                    e = null
                                }
                                )).promise()
                            },
                            then: function(e, n, o) {
                                var i = 0;
                                function a(e, t, n, o) {
                                    return function() {
                                        var s = this
                                          , c = arguments
                                          , u = function() {
                                            var r, u;
                                            if (!(e < i)) {
                                                if ((r = n.apply(s, c)) === t.promise())
                                                    throw new TypeError("Thenable self-resolution");
                                                u = r && ("object" == typeof r || "function" == typeof r) && r.then,
                                                g(u) ? o ? u.call(r, a(i, t, I, o), a(i, t, M, o)) : (i++,
                                                u.call(r, a(i, t, I, o), a(i, t, M, o), a(i, t, I, t.notifyWith))) : (n !== I && (s = void 0,
                                                c = [r]),
                                                (o || t.resolveWith)(s, c))
                                            }
                                        }
                                          , l = o ? u : function() {
                                            try {
                                                u()
                                            } catch (r) {
                                                j.Deferred.exceptionHook && j.Deferred.exceptionHook(r, l.stackTrace),
                                                e + 1 >= i && (n !== M && (s = void 0,
                                                c = [r]),
                                                t.rejectWith(s, c))
                                            }
                                        }
                                        ;
                                        e ? l() : (j.Deferred.getStackHook && (l.stackTrace = j.Deferred.getStackHook()),
                                        r.setTimeout(l))
                                    }
                                }
                                return j.Deferred((function(r) {
                                    t[0][3].add(a(0, r, g(o) ? o : I, r.notifyWith)),
                                    t[1][3].add(a(0, r, g(e) ? e : I)),
                                    t[2][3].add(a(0, r, g(n) ? n : M))
                                }
                                )).promise()
                            },
                            promise: function(e) {
                                return null != e ? j.extend(e, o) : o
                            }
                        }
                          , i = {};
                        return j.each(t, (function(e, r) {
                            var a = r[2]
                              , s = r[5];
                            o[r[1]] = a.add,
                            s && a.add((function() {
                                n = s
                            }
                            ), t[3 - e][2].disable, t[3 - e][3].disable, t[0][2].lock, t[0][3].lock),
                            a.add(r[3].fire),
                            i[r[0]] = function() {
                                return i[r[0] + "With"](this === i ? void 0 : this, arguments),
                                this
                            }
                            ,
                            i[r[0] + "With"] = a.fireWith
                        }
                        )),
                        o.promise(i),
                        e && e.call(i, i),
                        i
                    },
                    when: function(e) {
                        var t = arguments.length
                          , n = t
                          , r = Array(n)
                          , o = s.call(arguments)
                          , i = j.Deferred()
                          , a = function(e) {
                            return function(n) {
                                r[e] = this,
                                o[e] = arguments.length > 1 ? s.call(arguments) : n,
                                --t || i.resolveWith(r, o)
                            }
                        };
                        if (t <= 1 && (W(e, i.done(a(n)).resolve, i.reject, !t),
                        "pending" === i.state() || g(o[n] && o[n].then)))
                            return i.then();
                        for (; n--; )
                            W(o[n], a(n), i.reject);
                        return i.promise()
                    }
                });
                var F = /^(Eval|Internal|Range|Reference|Syntax|Type|URI)Error$/;
                j.Deferred.exceptionHook = function(e, t) {
                    r.console && r.console.warn && e && F.test(e.name) && r.console.warn("jQuery.Deferred exception: " + e.message, e.stack, t)
                }
                ,
                j.readyException = function(e) {
                    r.setTimeout((function() {
                        throw e
                    }
                    ))
                }
                ;
                var B = j.Deferred();
                function Q() {
                    b.removeEventListener("DOMContentLoaded", Q),
                    r.removeEventListener("load", Q),
                    j.ready()
                }
                j.fn.ready = function(e) {
                    return B.then(e).catch((function(e) {
                        j.readyException(e)
                    }
                    )),
                    this
                }
                ,
                j.extend({
                    isReady: !1,
                    readyWait: 1,
                    ready: function(e) {
                        (!0 === e ? --j.readyWait : j.isReady) || (j.isReady = !0,
                        !0 !== e && --j.readyWait > 0 || B.resolveWith(b, [j]))
                    }
                }),
                j.ready.then = B.then,
                "complete" === b.readyState || "loading" !== b.readyState && !b.documentElement.doScroll ? r.setTimeout(j.ready) : (b.addEventListener("DOMContentLoaded", Q),
                r.addEventListener("load", Q));
                var z = function(e, t, n, r, o, i, a) {
                    var s = 0
                      , c = e.length
                      , u = null == n;
                    if ("object" === k(n))
                        for (s in o = !0,
                        n)
                            z(e, t, s, n[s], !0, i, a);
                    else if (void 0 !== r && (o = !0,
                    g(r) || (a = !0),
                    u && (a ? (t.call(e, r),
                    t = null) : (u = t,
                    t = function(e, t, n) {
                        return u.call(j(e), n)
                    }
                    )),
                    t))
                        for (; s < c; s++)
                            t(e[s], n, a ? r : r.call(e[s], s, t(e[s], n)));
                    return o ? e : u ? t.call(e) : c ? t(e[0], n) : i
                }
                  , U = /^-ms-/
                  , X = /-([a-z])/g;
                function V(e, t) {
                    return t.toUpperCase()
                }
                function G(e) {
                    return e.replace(U, "ms-").replace(X, V)
                }
                var J = function(e) {
                    return 1 === e.nodeType || 9 === e.nodeType || !+e.nodeType
                };
                function Y() {
                    this.expando = j.expando + Y.uid++
                }
                Y.uid = 1,
                Y.prototype = {
                    cache: function(e) {
                        var t = e[this.expando];
                        return t || (t = {},
                        J(e) && (e.nodeType ? e[this.expando] = t : Object.defineProperty(e, this.expando, {
                            value: t,
                            configurable: !0
                        }))),
                        t
                    },
                    set: function(e, t, n) {
                        var r, o = this.cache(e);
                        if ("string" == typeof t)
                            o[G(t)] = n;
                        else
                            for (r in t)
                                o[G(r)] = t[r];
                        return o
                    },
                    get: function(e, t) {
                        return void 0 === t ? this.cache(e) : e[this.expando] && e[this.expando][G(t)]
                    },
                    access: function(e, t, n) {
                        return void 0 === t || t && "string" == typeof t && void 0 === n ? this.get(e, t) : (this.set(e, t, n),
                        void 0 !== n ? n : t)
                    },
                    remove: function(e, t) {
                        var n, r = e[this.expando];
                        if (void 0 !== r) {
                            if (void 0 !== t) {
                                n = (t = Array.isArray(t) ? t.map(G) : (t = G(t))in r ? [t] : t.match(R) || []).length;
                                for (; n--; )
                                    delete r[t[n]]
                            }
                            (void 0 === t || j.isEmptyObject(r)) && (e.nodeType ? e[this.expando] = void 0 : delete e[this.expando])
                        }
                    },
                    hasData: function(e) {
                        var t = e[this.expando];
                        return void 0 !== t && !j.isEmptyObject(t)
                    }
                };
                var K = new Y
                  , Z = new Y
                  , ee = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/
                  , te = /[A-Z]/g;
                function ne(e, t, n) {
                    var r;
                    if (void 0 === n && 1 === e.nodeType)
                        if (r = "data-" + t.replace(te, "-$&").toLowerCase(),
                        "string" == typeof (n = e.getAttribute(r))) {
                            try {
                                n = function(e) {
                                    return "true" === e || "false" !== e && ("null" === e ? null : e === +e + "" ? +e : ee.test(e) ? JSON.parse(e) : e)
                                }(n)
                            } catch (e) {}
                            Z.set(e, t, n)
                        } else
                            n = void 0;
                    return n
                }
                j.extend({
                    hasData: function(e) {
                        return Z.hasData(e) || K.hasData(e)
                    },
                    data: function(e, t, n) {
                        return Z.access(e, t, n)
                    },
                    removeData: function(e, t) {
                        Z.remove(e, t)
                    },
                    _data: function(e, t, n) {
                        return K.access(e, t, n)
                    },
                    _removeData: function(e, t) {
                        K.remove(e, t)
                    }
                }),
                j.fn.extend({
                    data: function(e, t) {
                        var n, r, o, i = this[0], a = i && i.attributes;
                        if (void 0 === e) {
                            if (this.length && (o = Z.get(i),
                            1 === i.nodeType && !K.get(i, "hasDataAttrs"))) {
                                for (n = a.length; n--; )
                                    a[n] && 0 === (r = a[n].name).indexOf("data-") && (r = G(r.slice(5)),
                                    ne(i, r, o[r]));
                                K.set(i, "hasDataAttrs", !0)
                            }
                            return o
                        }
                        return "object" == typeof e ? this.each((function() {
                            Z.set(this, e)
                        }
                        )) : z(this, (function(t) {
                            var n;
                            if (i && void 0 === t)
                                return void 0 !== (n = Z.get(i, e)) || void 0 !== (n = ne(i, e)) ? n : void 0;
                            this.each((function() {
                                Z.set(this, e, t)
                            }
                            ))
                        }
                        ), null, t, arguments.length > 1, null, !0)
                    },
                    removeData: function(e) {
                        return this.each((function() {
                            Z.remove(this, e)
                        }
                        ))
                    }
                }),
                j.extend({
                    queue: function(e, t, n) {
                        var r;
                        if (e)
                            return t = (t || "fx") + "queue",
                            r = K.get(e, t),
                            n && (!r || Array.isArray(n) ? r = K.access(e, t, j.makeArray(n)) : r.push(n)),
                            r || []
                    },
                    dequeue: function(e, t) {
                        t = t || "fx";
                        var n = j.queue(e, t)
                          , r = n.length
                          , o = n.shift()
                          , i = j._queueHooks(e, t);
                        "inprogress" === o && (o = n.shift(),
                        r--),
                        o && ("fx" === t && n.unshift("inprogress"),
                        delete i.stop,
                        o.call(e, (function() {
                            j.dequeue(e, t)
                        }
                        ), i)),
                        !r && i && i.empty.fire()
                    },
                    _queueHooks: function(e, t) {
                        var n = t + "queueHooks";
                        return K.get(e, n) || K.access(e, n, {
                            empty: j.Callbacks("once memory").add((function() {
                                K.remove(e, [t + "queue", n])
                            }
                            ))
                        })
                    }
                }),
                j.fn.extend({
                    queue: function(e, t) {
                        var n = 2;
                        return "string" != typeof e && (t = e,
                        e = "fx",
                        n--),
                        arguments.length < n ? j.queue(this[0], e) : void 0 === t ? this : this.each((function() {
                            var n = j.queue(this, e, t);
                            j._queueHooks(this, e),
                            "fx" === e && "inprogress" !== n[0] && j.dequeue(this, e)
                        }
                        ))
                    },
                    dequeue: function(e) {
                        return this.each((function() {
                            j.dequeue(this, e)
                        }
                        ))
                    },
                    clearQueue: function(e) {
                        return this.queue(e || "fx", [])
                    },
                    promise: function(e, t) {
                        var n, r = 1, o = j.Deferred(), i = this, a = this.length, s = function() {
                            --r || o.resolveWith(i, [i])
                        };
                        for ("string" != typeof e && (t = e,
                        e = void 0),
                        e = e || "fx"; a--; )
                            (n = K.get(i[a], e + "queueHooks")) && n.empty && (r++,
                            n.empty.add(s));
                        return s(),
                        o.promise(t)
                    }
                });
                var re = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source
                  , oe = new RegExp("^(?:([+-])=|)(" + re + ")([a-z%]*)$","i")
                  , ie = ["Top", "Right", "Bottom", "Left"]
                  , ae = b.documentElement
                  , se = function(e) {
                    return j.contains(e.ownerDocument, e)
                }
                  , ce = {
                    composed: !0
                };
                ae.getRootNode && (se = function(e) {
                    return j.contains(e.ownerDocument, e) || e.getRootNode(ce) === e.ownerDocument
                }
                );
                var ue = function(e, t) {
                    return "none" === (e = t || e).style.display || "" === e.style.display && se(e) && "none" === j.css(e, "display")
                };
                function le(e, t, n, r) {
                    var o, i, a = 20, s = r ? function() {
                        return r.cur()
                    }
                    : function() {
                        return j.css(e, t, "")
                    }
                    , c = s(), u = n && n[3] || (j.cssNumber[t] ? "" : "px"), l = e.nodeType && (j.cssNumber[t] || "px" !== u && +c) && oe.exec(j.css(e, t));
                    if (l && l[3] !== u) {
                        for (c /= 2,
                        u = u || l[3],
                        l = +c || 1; a--; )
                            j.style(e, t, l + u),
                            (1 - i) * (1 - (i = s() / c || .5)) <= 0 && (a = 0),
                            l /= i;
                        l *= 2,
                        j.style(e, t, l + u),
                        n = n || []
                    }
                    return n && (l = +l || +c || 0,
                    o = n[1] ? l + (n[1] + 1) * n[2] : +n[2],
                    r && (r.unit = u,
                    r.start = l,
                    r.end = o)),
                    o
                }
                var de = {};
                function pe(e) {
                    var t, n = e.ownerDocument, r = e.nodeName, o = de[r];
                    return o || (t = n.body.appendChild(n.createElement(r)),
                    o = j.css(t, "display"),
                    t.parentNode.removeChild(t),
                    "none" === o && (o = "block"),
                    de[r] = o,
                    o)
                }
                function fe(e, t) {
                    for (var n, r, o = [], i = 0, a = e.length; i < a; i++)
                        (r = e[i]).style && (n = r.style.display,
                        t ? ("none" === n && (o[i] = K.get(r, "display") || null,
                        o[i] || (r.style.display = "")),
                        "" === r.style.display && ue(r) && (o[i] = pe(r))) : "none" !== n && (o[i] = "none",
                        K.set(r, "display", n)));
                    for (i = 0; i < a; i++)
                        null != o[i] && (e[i].style.display = o[i]);
                    return e
                }
                j.fn.extend({
                    show: function() {
                        return fe(this, !0)
                    },
                    hide: function() {
                        return fe(this)
                    },
                    toggle: function(e) {
                        return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each((function() {
                            ue(this) ? j(this).show() : j(this).hide()
                        }
                        ))
                    }
                });
                var he, me, ve = /^(?:checkbox|radio)$/i, ge = /<([a-z][^\/\0>\x20\t\r\n\f]*)/i, ye = /^$|^module$|\/(?:java|ecma)script/i;
                he = b.createDocumentFragment().appendChild(b.createElement("div")),
                (me = b.createElement("input")).setAttribute("type", "radio"),
                me.setAttribute("checked", "checked"),
                me.setAttribute("name", "t"),
                he.appendChild(me),
                v.checkClone = he.cloneNode(!0).cloneNode(!0).lastChild.checked,
                he.innerHTML = "<textarea>x</textarea>",
                v.noCloneChecked = !!he.cloneNode(!0).lastChild.defaultValue,
                he.innerHTML = "<option></option>",
                v.option = !!he.lastChild;
                var be = {
                    thead: [1, "<table>", "</table>"],
                    col: [2, "<table><colgroup>", "</colgroup></table>"],
                    tr: [2, "<table><tbody>", "</tbody></table>"],
                    td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
                    _default: [0, "", ""]
                };
                function xe(e, t) {
                    var n;
                    return n = void 0 !== e.getElementsByTagName ? e.getElementsByTagName(t || "*") : void 0 !== e.querySelectorAll ? e.querySelectorAll(t || "*") : [],
                    void 0 === t || t && q(e, t) ? j.merge([e], n) : n
                }
                function we(e, t) {
                    for (var n = 0, r = e.length; n < r; n++)
                        K.set(e[n], "globalEval", !t || K.get(t[n], "globalEval"))
                }
                be.tbody = be.tfoot = be.colgroup = be.caption = be.thead,
                be.th = be.td,
                v.option || (be.optgroup = be.option = [1, "<select multiple='multiple'>", "</select>"]);
                var ke = /<|&#?\w+;/;
                function Ce(e, t, n, r, o) {
                    for (var i, a, s, c, u, l, d = t.createDocumentFragment(), p = [], f = 0, h = e.length; f < h; f++)
                        if ((i = e[f]) || 0 === i)
                            if ("object" === k(i))
                                j.merge(p, i.nodeType ? [i] : i);
                            else if (ke.test(i)) {
                                for (a = a || d.appendChild(t.createElement("div")),
                                s = (ge.exec(i) || ["", ""])[1].toLowerCase(),
                                c = be[s] || be._default,
                                a.innerHTML = c[1] + j.htmlPrefilter(i) + c[2],
                                l = c[0]; l--; )
                                    a = a.lastChild;
                                j.merge(p, a.childNodes),
                                (a = d.firstChild).textContent = ""
                            } else
                                p.push(t.createTextNode(i));
                    for (d.textContent = "",
                    f = 0; i = p[f++]; )
                        if (r && j.inArray(i, r) > -1)
                            o && o.push(i);
                        else if (u = se(i),
                        a = xe(d.appendChild(i), "script"),
                        u && we(a),
                        n)
                            for (l = 0; i = a[l++]; )
                                ye.test(i.type || "") && n.push(i);
                    return d
                }
                var je = /^key/
                  , Te = /^(?:mouse|pointer|contextmenu|drag|drop)|click/
                  , Se = /^([^.]*)(?:\.(.+)|)/;
                function Ae() {
                    return !0
                }
                function Ee() {
                    return !1
                }
                function Ne(e, t) {
                    return e === function() {
                        try {
                            return b.activeElement
                        } catch (e) {}
                    }() == ("focus" === t)
                }
                function qe(e, t, n, r, o, i) {
                    var a, s;
                    if ("object" == typeof t) {
                        for (s in "string" != typeof n && (r = r || n,
                        n = void 0),
                        t)
                            qe(e, s, n, r, t[s], i);
                        return e
                    }
                    if (null == r && null == o ? (o = n,
                    r = n = void 0) : null == o && ("string" == typeof n ? (o = r,
                    r = void 0) : (o = r,
                    r = n,
                    n = void 0)),
                    !1 === o)
                        o = Ee;
                    else if (!o)
                        return e;
                    return 1 === i && (a = o,
                    (o = function(e) {
                        return j().off(e),
                        a.apply(this, arguments)
                    }
                    ).guid = a.guid || (a.guid = j.guid++)),
                    e.each((function() {
                        j.event.add(this, t, o, r, n)
                    }
                    ))
                }
                function De(e, t, n) {
                    n ? (K.set(e, t, !1),
                    j.event.add(e, t, {
                        namespace: !1,
                        handler: function(e) {
                            var r, o, i = K.get(this, t);
                            if (1 & e.isTrigger && this[t]) {
                                if (i.length)
                                    (j.event.special[t] || {}).delegateType && e.stopPropagation();
                                else if (i = s.call(arguments),
                                K.set(this, t, i),
                                r = n(this, t),
                                this[t](),
                                i !== (o = K.get(this, t)) || r ? K.set(this, t, !1) : o = {},
                                i !== o)
                                    return e.stopImmediatePropagation(),
                                    e.preventDefault(),
                                    o.value
                            } else
                                i.length && (K.set(this, t, {
                                    value: j.event.trigger(j.extend(i[0], j.Event.prototype), i.slice(1), this)
                                }),
                                e.stopImmediatePropagation())
                        }
                    })) : void 0 === K.get(e, t) && j.event.add(e, t, Ae)
                }
                j.event = {
                    global: {},
                    add: function(e, t, n, r, o) {
                        var i, a, s, c, u, l, d, p, f, h, m, v = K.get(e);
                        if (J(e))
                            for (n.handler && (n = (i = n).handler,
                            o = i.selector),
                            o && j.find.matchesSelector(ae, o),
                            n.guid || (n.guid = j.guid++),
                            (c = v.events) || (c = v.events = Object.create(null)),
                            (a = v.handle) || (a = v.handle = function(t) {
                                return void 0 !== j && j.event.triggered !== t.type ? j.event.dispatch.apply(e, arguments) : void 0
                            }
                            ),
                            u = (t = (t || "").match(R) || [""]).length; u--; )
                                f = m = (s = Se.exec(t[u]) || [])[1],
                                h = (s[2] || "").split(".").sort(),
                                f && (d = j.event.special[f] || {},
                                f = (o ? d.delegateType : d.bindType) || f,
                                d = j.event.special[f] || {},
                                l = j.extend({
                                    type: f,
                                    origType: m,
                                    data: r,
                                    handler: n,
                                    guid: n.guid,
                                    selector: o,
                                    needsContext: o && j.expr.match.needsContext.test(o),
                                    namespace: h.join(".")
                                }, i),
                                (p = c[f]) || ((p = c[f] = []).delegateCount = 0,
                                d.setup && !1 !== d.setup.call(e, r, h, a) || e.addEventListener && e.addEventListener(f, a)),
                                d.add && (d.add.call(e, l),
                                l.handler.guid || (l.handler.guid = n.guid)),
                                o ? p.splice(p.delegateCount++, 0, l) : p.push(l),
                                j.event.global[f] = !0)
                    },
                    remove: function(e, t, n, r, o) {
                        var i, a, s, c, u, l, d, p, f, h, m, v = K.hasData(e) && K.get(e);
                        if (v && (c = v.events)) {
                            for (u = (t = (t || "").match(R) || [""]).length; u--; )
                                if (f = m = (s = Se.exec(t[u]) || [])[1],
                                h = (s[2] || "").split(".").sort(),
                                f) {
                                    for (d = j.event.special[f] || {},
                                    p = c[f = (r ? d.delegateType : d.bindType) || f] || [],
                                    s = s[2] && new RegExp("(^|\\.)" + h.join("\\.(?:.*\\.|)") + "(\\.|$)"),
                                    a = i = p.length; i--; )
                                        l = p[i],
                                        !o && m !== l.origType || n && n.guid !== l.guid || s && !s.test(l.namespace) || r && r !== l.selector && ("**" !== r || !l.selector) || (p.splice(i, 1),
                                        l.selector && p.delegateCount--,
                                        d.remove && d.remove.call(e, l));
                                    a && !p.length && (d.teardown && !1 !== d.teardown.call(e, h, v.handle) || j.removeEvent(e, f, v.handle),
                                    delete c[f])
                                } else
                                    for (f in c)
                                        j.event.remove(e, f + t[u], n, r, !0);
                            j.isEmptyObject(c) && K.remove(e, "handle events")
                        }
                    },
                    dispatch: function(e) {
                        var t, n, r, o, i, a, s = new Array(arguments.length), c = j.event.fix(e), u = (K.get(this, "events") || Object.create(null))[c.type] || [], l = j.event.special[c.type] || {};
                        for (s[0] = c,
                        t = 1; t < arguments.length; t++)
                            s[t] = arguments[t];
                        if (c.delegateTarget = this,
                        !l.preDispatch || !1 !== l.preDispatch.call(this, c)) {
                            for (a = j.event.handlers.call(this, c, u),
                            t = 0; (o = a[t++]) && !c.isPropagationStopped(); )
                                for (c.currentTarget = o.elem,
                                n = 0; (i = o.handlers[n++]) && !c.isImmediatePropagationStopped(); )
                                    c.rnamespace && !1 !== i.namespace && !c.rnamespace.test(i.namespace) || (c.handleObj = i,
                                    c.data = i.data,
                                    void 0 !== (r = ((j.event.special[i.origType] || {}).handle || i.handler).apply(o.elem, s)) && !1 === (c.result = r) && (c.preventDefault(),
                                    c.stopPropagation()));
                            return l.postDispatch && l.postDispatch.call(this, c),
                            c.result
                        }
                    },
                    handlers: function(e, t) {
                        var n, r, o, i, a, s = [], c = t.delegateCount, u = e.target;
                        if (c && u.nodeType && !("click" === e.type && e.button >= 1))
                            for (; u !== this; u = u.parentNode || this)
                                if (1 === u.nodeType && ("click" !== e.type || !0 !== u.disabled)) {
                                    for (i = [],
                                    a = {},
                                    n = 0; n < c; n++)
                                        void 0 === a[o = (r = t[n]).selector + " "] && (a[o] = r.needsContext ? j(o, this).index(u) > -1 : j.find(o, this, null, [u]).length),
                                        a[o] && i.push(r);
                                    i.length && s.push({
                                        elem: u,
                                        handlers: i
                                    })
                                }
                        return u = this,
                        c < t.length && s.push({
                            elem: u,
                            handlers: t.slice(c)
                        }),
                        s
                    },
                    addProp: function(e, t) {
                        Object.defineProperty(j.Event.prototype, e, {
                            enumerable: !0,
                            configurable: !0,
                            get: g(t) ? function() {
                                if (this.originalEvent)
                                    return t(this.originalEvent)
                            }
                            : function() {
                                if (this.originalEvent)
                                    return this.originalEvent[e]
                            }
                            ,
                            set: function(t) {
                                Object.defineProperty(this, e, {
                                    enumerable: !0,
                                    configurable: !0,
                                    writable: !0,
                                    value: t
                                })
                            }
                        })
                    },
                    fix: function(e) {
                        return e[j.expando] ? e : new j.Event(e)
                    },
                    special: {
                        load: {
                            noBubble: !0
                        },
                        click: {
                            setup: function(e) {
                                var t = this || e;
                                return ve.test(t.type) && t.click && q(t, "input") && De(t, "click", Ae),
                                !1
                            },
                            trigger: function(e) {
                                var t = this || e;
                                return ve.test(t.type) && t.click && q(t, "input") && De(t, "click"),
                                !0
                            },
                            _default: function(e) {
                                var t = e.target;
                                return ve.test(t.type) && t.click && q(t, "input") && K.get(t, "click") || q(t, "a")
                            }
                        },
                        beforeunload: {
                            postDispatch: function(e) {
                                void 0 !== e.result && e.originalEvent && (e.originalEvent.returnValue = e.result)
                            }
                        }
                    }
                },
                j.removeEvent = function(e, t, n) {
                    e.removeEventListener && e.removeEventListener(t, n)
                }
                ,
                j.Event = function(e, t) {
                    if (!(this instanceof j.Event))
                        return new j.Event(e,t);
                    e && e.type ? (this.originalEvent = e,
                    this.type = e.type,
                    this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && !1 === e.returnValue ? Ae : Ee,
                    this.target = e.target && 3 === e.target.nodeType ? e.target.parentNode : e.target,
                    this.currentTarget = e.currentTarget,
                    this.relatedTarget = e.relatedTarget) : this.type = e,
                    t && j.extend(this, t),
                    this.timeStamp = e && e.timeStamp || Date.now(),
                    this[j.expando] = !0
                }
                ,
                j.Event.prototype = {
                    constructor: j.Event,
                    isDefaultPrevented: Ee,
                    isPropagationStopped: Ee,
                    isImmediatePropagationStopped: Ee,
                    isSimulated: !1,
                    preventDefault: function() {
                        var e = this.originalEvent;
                        this.isDefaultPrevented = Ae,
                        e && !this.isSimulated && e.preventDefault()
                    },
                    stopPropagation: function() {
                        var e = this.originalEvent;
                        this.isPropagationStopped = Ae,
                        e && !this.isSimulated && e.stopPropagation()
                    },
                    stopImmediatePropagation: function() {
                        var e = this.originalEvent;
                        this.isImmediatePropagationStopped = Ae,
                        e && !this.isSimulated && e.stopImmediatePropagation(),
                        this.stopPropagation()
                    }
                },
                j.each({
                    altKey: !0,
                    bubbles: !0,
                    cancelable: !0,
                    changedTouches: !0,
                    ctrlKey: !0,
                    detail: !0,
                    eventPhase: !0,
                    metaKey: !0,
                    pageX: !0,
                    pageY: !0,
                    shiftKey: !0,
                    view: !0,
                    char: !0,
                    code: !0,
                    charCode: !0,
                    key: !0,
                    keyCode: !0,
                    button: !0,
                    buttons: !0,
                    clientX: !0,
                    clientY: !0,
                    offsetX: !0,
                    offsetY: !0,
                    pointerId: !0,
                    pointerType: !0,
                    screenX: !0,
                    screenY: !0,
                    targetTouches: !0,
                    toElement: !0,
                    touches: !0,
                    which: function(e) {
                        var t = e.button;
                        return null == e.which && je.test(e.type) ? null != e.charCode ? e.charCode : e.keyCode : !e.which && void 0 !== t && Te.test(e.type) ? 1 & t ? 1 : 2 & t ? 3 : 4 & t ? 2 : 0 : e.which
                    }
                }, j.event.addProp),
                j.each({
                    focus: "focusin",
                    blur: "focusout"
                }, (function(e, t) {
                    j.event.special[e] = {
                        setup: function() {
                            return De(this, e, Ne),
                            !1
                        },
                        trigger: function() {
                            return De(this, e),
                            !0
                        },
                        delegateType: t
                    }
                }
                )),
                j.each({
                    mouseenter: "mouseover",
                    mouseleave: "mouseout",
                    pointerenter: "pointerover",
                    pointerleave: "pointerout"
                }, (function(e, t) {
                    j.event.special[e] = {
                        delegateType: t,
                        bindType: t,
                        handle: function(e) {
                            var n, r = this, o = e.relatedTarget, i = e.handleObj;
                            return o && (o === r || j.contains(r, o)) || (e.type = i.origType,
                            n = i.handler.apply(this, arguments),
                            e.type = t),
                            n
                        }
                    }
                }
                )),
                j.fn.extend({
                    on: function(e, t, n, r) {
                        return qe(this, e, t, n, r)
                    },
                    one: function(e, t, n, r) {
                        return qe(this, e, t, n, r, 1)
                    },
                    off: function(e, t, n) {
                        var r, o;
                        if (e && e.preventDefault && e.handleObj)
                            return r = e.handleObj,
                            j(e.delegateTarget).off(r.namespace ? r.origType + "." + r.namespace : r.origType, r.selector, r.handler),
                            this;
                        if ("object" == typeof e) {
                            for (o in e)
                                this.off(o, t, e[o]);
                            return this
                        }
                        return !1 !== t && "function" != typeof t || (n = t,
                        t = void 0),
                        !1 === n && (n = Ee),
                        this.each((function() {
                            j.event.remove(this, e, n, t)
                        }
                        ))
                    }
                });
                var Oe = /<script|<style|<link/i
                  , Pe = /checked\s*(?:[^=]|=\s*.checked.)/i
                  , _e = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;
                function Le(e, t) {
                    return q(e, "table") && q(11 !== t.nodeType ? t : t.firstChild, "tr") && j(e).children("tbody")[0] || e
                }
                function $e(e) {
                    return e.type = (null !== e.getAttribute("type")) + "/" + e.type,
                    e
                }
                function He(e) {
                    return "true/" === (e.type || "").slice(0, 5) ? e.type = e.type.slice(5) : e.removeAttribute("type"),
                    e
                }
                function Re(e, t) {
                    var n, r, o, i, a, s;
                    if (1 === t.nodeType) {
                        if (K.hasData(e) && (s = K.get(e).events))
                            for (o in K.remove(t, "handle events"),
                            s)
                                for (n = 0,
                                r = s[o].length; n < r; n++)
                                    j.event.add(t, o, s[o][n]);
                        Z.hasData(e) && (i = Z.access(e),
                        a = j.extend({}, i),
                        Z.set(t, a))
                    }
                }
                function Ie(e, t, n, r) {
                    t = c(t);
                    var o, i, a, s, u, l, d = 0, p = e.length, f = p - 1, h = t[0], m = g(h);
                    if (m || p > 1 && "string" == typeof h && !v.checkClone && Pe.test(h))
                        return e.each((function(o) {
                            var i = e.eq(o);
                            m && (t[0] = h.call(this, o, i.html())),
                            Ie(i, t, n, r)
                        }
                        ));
                    if (p && (i = (o = Ce(t, e[0].ownerDocument, !1, e, r)).firstChild,
                    1 === o.childNodes.length && (o = i),
                    i || r)) {
                        for (s = (a = j.map(xe(o, "script"), $e)).length; d < p; d++)
                            u = o,
                            d !== f && (u = j.clone(u, !0, !0),
                            s && j.merge(a, xe(u, "script"))),
                            n.call(e[d], u, d);
                        if (s)
                            for (l = a[a.length - 1].ownerDocument,
                            j.map(a, He),
                            d = 0; d < s; d++)
                                u = a[d],
                                ye.test(u.type || "") && !K.access(u, "globalEval") && j.contains(l, u) && (u.src && "module" !== (u.type || "").toLowerCase() ? j._evalUrl && !u.noModule && j._evalUrl(u.src, {
                                    nonce: u.nonce || u.getAttribute("nonce")
                                }, l) : w(u.textContent.replace(_e, ""), u, l))
                    }
                    return e
                }
                function Me(e, t, n) {
                    for (var r, o = t ? j.filter(t, e) : e, i = 0; null != (r = o[i]); i++)
                        n || 1 !== r.nodeType || j.cleanData(xe(r)),
                        r.parentNode && (n && se(r) && we(xe(r, "script")),
                        r.parentNode.removeChild(r));
                    return e
                }
                j.extend({
                    htmlPrefilter: function(e) {
                        return e
                    },
                    clone: function(e, t, n) {
                        var r, o, i, a, s, c, u, l = e.cloneNode(!0), d = se(e);
                        if (!(v.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || j.isXMLDoc(e)))
                            for (a = xe(l),
                            r = 0,
                            o = (i = xe(e)).length; r < o; r++)
                                s = i[r],
                                c = a[r],
                                u = void 0,
                                "input" === (u = c.nodeName.toLowerCase()) && ve.test(s.type) ? c.checked = s.checked : "input" !== u && "textarea" !== u || (c.defaultValue = s.defaultValue);
                        if (t)
                            if (n)
                                for (i = i || xe(e),
                                a = a || xe(l),
                                r = 0,
                                o = i.length; r < o; r++)
                                    Re(i[r], a[r]);
                            else
                                Re(e, l);
                        return (a = xe(l, "script")).length > 0 && we(a, !d && xe(e, "script")),
                        l
                    },
                    cleanData: function(e) {
                        for (var t, n, r, o = j.event.special, i = 0; void 0 !== (n = e[i]); i++)
                            if (J(n)) {
                                if (t = n[K.expando]) {
                                    if (t.events)
                                        for (r in t.events)
                                            o[r] ? j.event.remove(n, r) : j.removeEvent(n, r, t.handle);
                                    n[K.expando] = void 0
                                }
                                n[Z.expando] && (n[Z.expando] = void 0)
                            }
                    }
                }),
                j.fn.extend({
                    detach: function(e) {
                        return Me(this, e, !0)
                    },
                    remove: function(e) {
                        return Me(this, e)
                    },
                    text: function(e) {
                        return z(this, (function(e) {
                            return void 0 === e ? j.text(this) : this.empty().each((function() {
                                1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || (this.textContent = e)
                            }
                            ))
                        }
                        ), null, e, arguments.length)
                    },
                    append: function() {
                        return Ie(this, arguments, (function(e) {
                            1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || Le(this, e).appendChild(e)
                        }
                        ))
                    },
                    prepend: function() {
                        return Ie(this, arguments, (function(e) {
                            if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                                var t = Le(this, e);
                                t.insertBefore(e, t.firstChild)
                            }
                        }
                        ))
                    },
                    before: function() {
                        return Ie(this, arguments, (function(e) {
                            this.parentNode && this.parentNode.insertBefore(e, this)
                        }
                        ))
                    },
                    after: function() {
                        return Ie(this, arguments, (function(e) {
                            this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
                        }
                        ))
                    },
                    empty: function() {
                        for (var e, t = 0; null != (e = this[t]); t++)
                            1 === e.nodeType && (j.cleanData(xe(e, !1)),
                            e.textContent = "");
                        return this
                    },
                    clone: function(e, t) {
                        return e = null != e && e,
                        t = null == t ? e : t,
                        this.map((function() {
                            return j.clone(this, e, t)
                        }
                        ))
                    },
                    html: function(e) {
                        return z(this, (function(e) {
                            var t = this[0] || {}
                              , n = 0
                              , r = this.length;
                            if (void 0 === e && 1 === t.nodeType)
                                return t.innerHTML;
                            if ("string" == typeof e && !Oe.test(e) && !be[(ge.exec(e) || ["", ""])[1].toLowerCase()]) {
                                e = j.htmlPrefilter(e);
                                try {
                                    for (; n < r; n++)
                                        1 === (t = this[n] || {}).nodeType && (j.cleanData(xe(t, !1)),
                                        t.innerHTML = e);
                                    t = 0
                                } catch (e) {}
                            }
                            t && this.empty().append(e)
                        }
                        ), null, e, arguments.length)
                    },
                    replaceWith: function() {
                        var e = [];
                        return Ie(this, arguments, (function(t) {
                            var n = this.parentNode;
                            j.inArray(this, e) < 0 && (j.cleanData(xe(this)),
                            n && n.replaceChild(t, this))
                        }
                        ), e)
                    }
                }),
                j.each({
                    appendTo: "append",
                    prependTo: "prepend",
                    insertBefore: "before",
                    insertAfter: "after",
                    replaceAll: "replaceWith"
                }, (function(e, t) {
                    j.fn[e] = function(e) {
                        for (var n, r = [], o = j(e), i = o.length - 1, a = 0; a <= i; a++)
                            n = a === i ? this : this.clone(!0),
                            j(o[a])[t](n),
                            u.apply(r, n.get());
                        return this.pushStack(r)
                    }
                }
                ));
                var We = new RegExp("^(" + re + ")(?!px)[a-z%]+$","i")
                  , Fe = function(e) {
                    var t = e.ownerDocument.defaultView;
                    return t && t.opener || (t = r),
                    t.getComputedStyle(e)
                }
                  , Be = function(e, t, n) {
                    var r, o, i = {};
                    for (o in t)
                        i[o] = e.style[o],
                        e.style[o] = t[o];
                    for (o in r = n.call(e),
                    t)
                        e.style[o] = i[o];
                    return r
                }
                  , Qe = new RegExp(ie.join("|"),"i");
                function ze(e, t, n) {
                    var r, o, i, a, s = e.style;
                    return (n = n || Fe(e)) && ("" !== (a = n.getPropertyValue(t) || n[t]) || se(e) || (a = j.style(e, t)),
                    !v.pixelBoxStyles() && We.test(a) && Qe.test(t) && (r = s.width,
                    o = s.minWidth,
                    i = s.maxWidth,
                    s.minWidth = s.maxWidth = s.width = a,
                    a = n.width,
                    s.width = r,
                    s.minWidth = o,
                    s.maxWidth = i)),
                    void 0 !== a ? a + "" : a
                }
                function Ue(e, t) {
                    return {
                        get: function() {
                            if (!e())
                                return (this.get = t).apply(this, arguments);
                            delete this.get
                        }
                    }
                }
                !function() {
                    function e() {
                        if (l) {
                            u.style.cssText = "position:absolute;left:-11111px;width:60px;margin-top:1px;padding:0;border:0",
                            l.style.cssText = "position:relative;display:block;box-sizing:border-box;overflow:scroll;margin:auto;border:1px;padding:1px;width:60%;top:1%",
                            ae.appendChild(u).appendChild(l);
                            var e = r.getComputedStyle(l);
                            n = "1%" !== e.top,
                            c = 12 === t(e.marginLeft),
                            l.style.right = "60%",
                            a = 36 === t(e.right),
                            o = 36 === t(e.width),
                            l.style.position = "absolute",
                            i = 12 === t(l.offsetWidth / 3),
                            ae.removeChild(u),
                            l = null
                        }
                    }
                    function t(e) {
                        return Math.round(parseFloat(e))
                    }
                    var n, o, i, a, s, c, u = b.createElement("div"), l = b.createElement("div");
                    l.style && (l.style.backgroundClip = "content-box",
                    l.cloneNode(!0).style.backgroundClip = "",
                    v.clearCloneStyle = "content-box" === l.style.backgroundClip,
                    j.extend(v, {
                        boxSizingReliable: function() {
                            return e(),
                            o
                        },
                        pixelBoxStyles: function() {
                            return e(),
                            a
                        },
                        pixelPosition: function() {
                            return e(),
                            n
                        },
                        reliableMarginLeft: function() {
                            return e(),
                            c
                        },
                        scrollboxSize: function() {
                            return e(),
                            i
                        },
                        reliableTrDimensions: function() {
                            var e, t, n, o;
                            return null == s && (e = b.createElement("table"),
                            t = b.createElement("tr"),
                            n = b.createElement("div"),
                            e.style.cssText = "position:absolute;left:-11111px",
                            t.style.height = "1px",
                            n.style.height = "9px",
                            ae.appendChild(e).appendChild(t).appendChild(n),
                            o = r.getComputedStyle(t),
                            s = parseInt(o.height) > 3,
                            ae.removeChild(e)),
                            s
                        }
                    }))
                }();
                var Xe = ["Webkit", "Moz", "ms"]
                  , Ve = b.createElement("div").style
                  , Ge = {};
                function Je(e) {
                    var t = j.cssProps[e] || Ge[e];
                    return t || (e in Ve ? e : Ge[e] = function(e) {
                        for (var t = e[0].toUpperCase() + e.slice(1), n = Xe.length; n--; )
                            if ((e = Xe[n] + t)in Ve)
                                return e
                    }(e) || e)
                }
                var Ye = /^(none|table(?!-c[ea]).+)/
                  , Ke = /^--/
                  , Ze = {
                    position: "absolute",
                    visibility: "hidden",
                    display: "block"
                }
                  , et = {
                    letterSpacing: "0",
                    fontWeight: "400"
                };
                function tt(e, t, n) {
                    var r = oe.exec(t);
                    return r ? Math.max(0, r[2] - (n || 0)) + (r[3] || "px") : t
                }
                function nt(e, t, n, r, o, i) {
                    var a = "width" === t ? 1 : 0
                      , s = 0
                      , c = 0;
                    if (n === (r ? "border" : "content"))
                        return 0;
                    for (; a < 4; a += 2)
                        "margin" === n && (c += j.css(e, n + ie[a], !0, o)),
                        r ? ("content" === n && (c -= j.css(e, "padding" + ie[a], !0, o)),
                        "margin" !== n && (c -= j.css(e, "border" + ie[a] + "Width", !0, o))) : (c += j.css(e, "padding" + ie[a], !0, o),
                        "padding" !== n ? c += j.css(e, "border" + ie[a] + "Width", !0, o) : s += j.css(e, "border" + ie[a] + "Width", !0, o));
                    return !r && i >= 0 && (c += Math.max(0, Math.ceil(e["offset" + t[0].toUpperCase() + t.slice(1)] - i - c - s - .5)) || 0),
                    c
                }
                function rt(e, t, n) {
                    var r = Fe(e)
                      , o = (!v.boxSizingReliable() || n) && "border-box" === j.css(e, "boxSizing", !1, r)
                      , i = o
                      , a = ze(e, t, r)
                      , s = "offset" + t[0].toUpperCase() + t.slice(1);
                    if (We.test(a)) {
                        if (!n)
                            return a;
                        a = "auto"
                    }
                    return (!v.boxSizingReliable() && o || !v.reliableTrDimensions() && q(e, "tr") || "auto" === a || !parseFloat(a) && "inline" === j.css(e, "display", !1, r)) && e.getClientRects().length && (o = "border-box" === j.css(e, "boxSizing", !1, r),
                    (i = s in e) && (a = e[s])),
                    (a = parseFloat(a) || 0) + nt(e, t, n || (o ? "border" : "content"), i, r, a) + "px"
                }
                function ot(e, t, n, r, o) {
                    return new ot.prototype.init(e,t,n,r,o)
                }
                j.extend({
                    cssHooks: {
                        opacity: {
                            get: function(e, t) {
                                if (t) {
                                    var n = ze(e, "opacity");
                                    return "" === n ? "1" : n
                                }
                            }
                        }
                    },
                    cssNumber: {
                        animationIterationCount: !0,
                        columnCount: !0,
                        fillOpacity: !0,
                        flexGrow: !0,
                        flexShrink: !0,
                        fontWeight: !0,
                        gridArea: !0,
                        gridColumn: !0,
                        gridColumnEnd: !0,
                        gridColumnStart: !0,
                        gridRow: !0,
                        gridRowEnd: !0,
                        gridRowStart: !0,
                        lineHeight: !0,
                        opacity: !0,
                        order: !0,
                        orphans: !0,
                        widows: !0,
                        zIndex: !0,
                        zoom: !0
                    },
                    cssProps: {},
                    style: function(e, t, n, r) {
                        if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                            var o, i, a, s = G(t), c = Ke.test(t), u = e.style;
                            if (c || (t = Je(s)),
                            a = j.cssHooks[t] || j.cssHooks[s],
                            void 0 === n)
                                return a && "get"in a && void 0 !== (o = a.get(e, !1, r)) ? o : u[t];
                            "string" === (i = typeof n) && (o = oe.exec(n)) && o[1] && (n = le(e, t, o),
                            i = "number"),
                            null != n && n == n && ("number" !== i || c || (n += o && o[3] || (j.cssNumber[s] ? "" : "px")),
                            v.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (u[t] = "inherit"),
                            a && "set"in a && void 0 === (n = a.set(e, n, r)) || (c ? u.setProperty(t, n) : u[t] = n))
                        }
                    },
                    css: function(e, t, n, r) {
                        var o, i, a, s = G(t);
                        return Ke.test(t) || (t = Je(s)),
                        (a = j.cssHooks[t] || j.cssHooks[s]) && "get"in a && (o = a.get(e, !0, n)),
                        void 0 === o && (o = ze(e, t, r)),
                        "normal" === o && t in et && (o = et[t]),
                        "" === n || n ? (i = parseFloat(o),
                        !0 === n || isFinite(i) ? i || 0 : o) : o
                    }
                }),
                j.each(["height", "width"], (function(e, t) {
                    j.cssHooks[t] = {
                        get: function(e, n, r) {
                            if (n)
                                return !Ye.test(j.css(e, "display")) || e.getClientRects().length && e.getBoundingClientRect().width ? rt(e, t, r) : Be(e, Ze, (function() {
                                    return rt(e, t, r)
                                }
                                ))
                        },
                        set: function(e, n, r) {
                            var o, i = Fe(e), a = !v.scrollboxSize() && "absolute" === i.position, s = (a || r) && "border-box" === j.css(e, "boxSizing", !1, i), c = r ? nt(e, t, r, s, i) : 0;
                            return s && a && (c -= Math.ceil(e["offset" + t[0].toUpperCase() + t.slice(1)] - parseFloat(i[t]) - nt(e, t, "border", !1, i) - .5)),
                            c && (o = oe.exec(n)) && "px" !== (o[3] || "px") && (e.style[t] = n,
                            n = j.css(e, t)),
                            tt(0, n, c)
                        }
                    }
                }
                )),
                j.cssHooks.marginLeft = Ue(v.reliableMarginLeft, (function(e, t) {
                    if (t)
                        return (parseFloat(ze(e, "marginLeft")) || e.getBoundingClientRect().left - Be(e, {
                            marginLeft: 0
                        }, (function() {
                            return e.getBoundingClientRect().left
                        }
                        ))) + "px"
                }
                )),
                j.each({
                    margin: "",
                    padding: "",
                    border: "Width"
                }, (function(e, t) {
                    j.cssHooks[e + t] = {
                        expand: function(n) {
                            for (var r = 0, o = {}, i = "string" == typeof n ? n.split(" ") : [n]; r < 4; r++)
                                o[e + ie[r] + t] = i[r] || i[r - 2] || i[0];
                            return o
                        }
                    },
                    "margin" !== e && (j.cssHooks[e + t].set = tt)
                }
                )),
                j.fn.extend({
                    css: function(e, t) {
                        return z(this, (function(e, t, n) {
                            var r, o, i = {}, a = 0;
                            if (Array.isArray(t)) {
                                for (r = Fe(e),
                                o = t.length; a < o; a++)
                                    i[t[a]] = j.css(e, t[a], !1, r);
                                return i
                            }
                            return void 0 !== n ? j.style(e, t, n) : j.css(e, t)
                        }
                        ), e, t, arguments.length > 1)
                    }
                }),
                j.Tween = ot,
                ot.prototype = {
                    constructor: ot,
                    init: function(e, t, n, r, o, i) {
                        this.elem = e,
                        this.prop = n,
                        this.easing = o || j.easing._default,
                        this.options = t,
                        this.start = this.now = this.cur(),
                        this.end = r,
                        this.unit = i || (j.cssNumber[n] ? "" : "px")
                    },
                    cur: function() {
                        var e = ot.propHooks[this.prop];
                        return e && e.get ? e.get(this) : ot.propHooks._default.get(this)
                    },
                    run: function(e) {
                        var t, n = ot.propHooks[this.prop];
                        return this.options.duration ? this.pos = t = j.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : this.pos = t = e,
                        this.now = (this.end - this.start) * t + this.start,
                        this.options.step && this.options.step.call(this.elem, this.now, this),
                        n && n.set ? n.set(this) : ot.propHooks._default.set(this),
                        this
                    }
                },
                ot.prototype.init.prototype = ot.prototype,
                ot.propHooks = {
                    _default: {
                        get: function(e) {
                            var t;
                            return 1 !== e.elem.nodeType || null != e.elem[e.prop] && null == e.elem.style[e.prop] ? e.elem[e.prop] : (t = j.css(e.elem, e.prop, "")) && "auto" !== t ? t : 0
                        },
                        set: function(e) {
                            j.fx.step[e.prop] ? j.fx.step[e.prop](e) : 1 !== e.elem.nodeType || !j.cssHooks[e.prop] && null == e.elem.style[Je(e.prop)] ? e.elem[e.prop] = e.now : j.style(e.elem, e.prop, e.now + e.unit)
                        }
                    }
                },
                ot.propHooks.scrollTop = ot.propHooks.scrollLeft = {
                    set: function(e) {
                        e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
                    }
                },
                j.easing = {
                    linear: function(e) {
                        return e
                    },
                    swing: function(e) {
                        return .5 - Math.cos(e * Math.PI) / 2
                    },
                    _default: "swing"
                },
                j.fx = ot.prototype.init,
                j.fx.step = {};
                var it, at, st = /^(?:toggle|show|hide)$/, ct = /queueHooks$/;
                function ut() {
                    at && (!1 === b.hidden && r.requestAnimationFrame ? r.requestAnimationFrame(ut) : r.setTimeout(ut, j.fx.interval),
                    j.fx.tick())
                }
                function lt() {
                    return r.setTimeout((function() {
                        it = void 0
                    }
                    )),
                    it = Date.now()
                }
                function dt(e, t) {
                    var n, r = 0, o = {
                        height: e
                    };
                    for (t = t ? 1 : 0; r < 4; r += 2 - t)
                        o["margin" + (n = ie[r])] = o["padding" + n] = e;
                    return t && (o.opacity = o.width = e),
                    o
                }
                function pt(e, t, n) {
                    for (var r, o = (ft.tweeners[t] || []).concat(ft.tweeners["*"]), i = 0, a = o.length; i < a; i++)
                        if (r = o[i].call(n, t, e))
                            return r
                }
                function ft(e, t, n) {
                    var r, o, i = 0, a = ft.prefilters.length, s = j.Deferred().always((function() {
                        delete c.elem
                    }
                    )), c = function() {
                        if (o)
                            return !1;
                        for (var t = it || lt(), n = Math.max(0, u.startTime + u.duration - t), r = 1 - (n / u.duration || 0), i = 0, a = u.tweens.length; i < a; i++)
                            u.tweens[i].run(r);
                        return s.notifyWith(e, [u, r, n]),
                        r < 1 && a ? n : (a || s.notifyWith(e, [u, 1, 0]),
                        s.resolveWith(e, [u]),
                        !1)
                    }, u = s.promise({
                        elem: e,
                        props: j.extend({}, t),
                        opts: j.extend(!0, {
                            specialEasing: {},
                            easing: j.easing._default
                        }, n),
                        originalProperties: t,
                        originalOptions: n,
                        startTime: it || lt(),
                        duration: n.duration,
                        tweens: [],
                        createTween: function(t, n) {
                            var r = j.Tween(e, u.opts, t, n, u.opts.specialEasing[t] || u.opts.easing);
                            return u.tweens.push(r),
                            r
                        },
                        stop: function(t) {
                            var n = 0
                              , r = t ? u.tweens.length : 0;
                            if (o)
                                return this;
                            for (o = !0; n < r; n++)
                                u.tweens[n].run(1);
                            return t ? (s.notifyWith(e, [u, 1, 0]),
                            s.resolveWith(e, [u, t])) : s.rejectWith(e, [u, t]),
                            this
                        }
                    }), l = u.props;
                    for (!function(e, t) {
                        var n, r, o, i, a;
                        for (n in e)
                            if (o = t[r = G(n)],
                            i = e[n],
                            Array.isArray(i) && (o = i[1],
                            i = e[n] = i[0]),
                            n !== r && (e[r] = i,
                            delete e[n]),
                            (a = j.cssHooks[r]) && "expand"in a)
                                for (n in i = a.expand(i),
                                delete e[r],
                                i)
                                    n in e || (e[n] = i[n],
                                    t[n] = o);
                            else
                                t[r] = o
                    }(l, u.opts.specialEasing); i < a; i++)
                        if (r = ft.prefilters[i].call(u, e, l, u.opts))
                            return g(r.stop) && (j._queueHooks(u.elem, u.opts.queue).stop = r.stop.bind(r)),
                            r;
                    return j.map(l, pt, u),
                    g(u.opts.start) && u.opts.start.call(e, u),
                    u.progress(u.opts.progress).done(u.opts.done, u.opts.complete).fail(u.opts.fail).always(u.opts.always),
                    j.fx.timer(j.extend(c, {
                        elem: e,
                        anim: u,
                        queue: u.opts.queue
                    })),
                    u
                }
                j.Animation = j.extend(ft, {
                    tweeners: {
                        "*": [function(e, t) {
                            var n = this.createTween(e, t);
                            return le(n.elem, e, oe.exec(t), n),
                            n
                        }
                        ]
                    },
                    tweener: function(e, t) {
                        g(e) ? (t = e,
                        e = ["*"]) : e = e.match(R);
                        for (var n, r = 0, o = e.length; r < o; r++)
                            n = e[r],
                            ft.tweeners[n] = ft.tweeners[n] || [],
                            ft.tweeners[n].unshift(t)
                    },
                    prefilters: [function(e, t, n) {
                        var r, o, i, a, s, c, u, l, d = "width"in t || "height"in t, p = this, f = {}, h = e.style, m = e.nodeType && ue(e), v = K.get(e, "fxshow");
                        for (r in n.queue || (null == (a = j._queueHooks(e, "fx")).unqueued && (a.unqueued = 0,
                        s = a.empty.fire,
                        a.empty.fire = function() {
                            a.unqueued || s()
                        }
                        ),
                        a.unqueued++,
                        p.always((function() {
                            p.always((function() {
                                a.unqueued--,
                                j.queue(e, "fx").length || a.empty.fire()
                            }
                            ))
                        }
                        ))),
                        t)
                            if (o = t[r],
                            st.test(o)) {
                                if (delete t[r],
                                i = i || "toggle" === o,
                                o === (m ? "hide" : "show")) {
                                    if ("show" !== o || !v || void 0 === v[r])
                                        continue;
                                    m = !0
                                }
                                f[r] = v && v[r] || j.style(e, r)
                            }
                        if ((c = !j.isEmptyObject(t)) || !j.isEmptyObject(f))
                            for (r in d && 1 === e.nodeType && (n.overflow = [h.overflow, h.overflowX, h.overflowY],
                            null == (u = v && v.display) && (u = K.get(e, "display")),
                            "none" === (l = j.css(e, "display")) && (u ? l = u : (fe([e], !0),
                            u = e.style.display || u,
                            l = j.css(e, "display"),
                            fe([e]))),
                            ("inline" === l || "inline-block" === l && null != u) && "none" === j.css(e, "float") && (c || (p.done((function() {
                                h.display = u
                            }
                            )),
                            null == u && (l = h.display,
                            u = "none" === l ? "" : l)),
                            h.display = "inline-block")),
                            n.overflow && (h.overflow = "hidden",
                            p.always((function() {
                                h.overflow = n.overflow[0],
                                h.overflowX = n.overflow[1],
                                h.overflowY = n.overflow[2]
                            }
                            ))),
                            c = !1,
                            f)
                                c || (v ? "hidden"in v && (m = v.hidden) : v = K.access(e, "fxshow", {
                                    display: u
                                }),
                                i && (v.hidden = !m),
                                m && fe([e], !0),
                                p.done((function() {
                                    for (r in m || fe([e]),
                                    K.remove(e, "fxshow"),
                                    f)
                                        j.style(e, r, f[r])
                                }
                                ))),
                                c = pt(m ? v[r] : 0, r, p),
                                r in v || (v[r] = c.start,
                                m && (c.end = c.start,
                                c.start = 0))
                    }
                    ],
                    prefilter: function(e, t) {
                        t ? ft.prefilters.unshift(e) : ft.prefilters.push(e)
                    }
                }),
                j.speed = function(e, t, n) {
                    var r = e && "object" == typeof e ? j.extend({}, e) : {
                        complete: n || !n && t || g(e) && e,
                        duration: e,
                        easing: n && t || t && !g(t) && t
                    };
                    return j.fx.off ? r.duration = 0 : "number" != typeof r.duration && (r.duration in j.fx.speeds ? r.duration = j.fx.speeds[r.duration] : r.duration = j.fx.speeds._default),
                    null != r.queue && !0 !== r.queue || (r.queue = "fx"),
                    r.old = r.complete,
                    r.complete = function() {
                        g(r.old) && r.old.call(this),
                        r.queue && j.dequeue(this, r.queue)
                    }
                    ,
                    r
                }
                ,
                j.fn.extend({
                    fadeTo: function(e, t, n, r) {
                        return this.filter(ue).css("opacity", 0).show().end().animate({
                            opacity: t
                        }, e, n, r)
                    },
                    animate: function(e, t, n, r) {
                        var o = j.isEmptyObject(e)
                          , i = j.speed(t, n, r)
                          , a = function() {
                            var t = ft(this, j.extend({}, e), i);
                            (o || K.get(this, "finish")) && t.stop(!0)
                        };
                        return a.finish = a,
                        o || !1 === i.queue ? this.each(a) : this.queue(i.queue, a)
                    },
                    stop: function(e, t, n) {
                        var r = function(e) {
                            var t = e.stop;
                            delete e.stop,
                            t(n)
                        };
                        return "string" != typeof e && (n = t,
                        t = e,
                        e = void 0),
                        t && this.queue(e || "fx", []),
                        this.each((function() {
                            var t = !0
                              , o = null != e && e + "queueHooks"
                              , i = j.timers
                              , a = K.get(this);
                            if (o)
                                a[o] && a[o].stop && r(a[o]);
                            else
                                for (o in a)
                                    a[o] && a[o].stop && ct.test(o) && r(a[o]);
                            for (o = i.length; o--; )
                                i[o].elem !== this || null != e && i[o].queue !== e || (i[o].anim.stop(n),
                                t = !1,
                                i.splice(o, 1));
                            !t && n || j.dequeue(this, e)
                        }
                        ))
                    },
                    finish: function(e) {
                        return !1 !== e && (e = e || "fx"),
                        this.each((function() {
                            var t, n = K.get(this), r = n[e + "queue"], o = n[e + "queueHooks"], i = j.timers, a = r ? r.length : 0;
                            for (n.finish = !0,
                            j.queue(this, e, []),
                            o && o.stop && o.stop.call(this, !0),
                            t = i.length; t--; )
                                i[t].elem === this && i[t].queue === e && (i[t].anim.stop(!0),
                                i.splice(t, 1));
                            for (t = 0; t < a; t++)
                                r[t] && r[t].finish && r[t].finish.call(this);
                            delete n.finish
                        }
                        ))
                    }
                }),
                j.each(["toggle", "show", "hide"], (function(e, t) {
                    var n = j.fn[t];
                    j.fn[t] = function(e, r, o) {
                        return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(dt(t, !0), e, r, o)
                    }
                }
                )),
                j.each({
                    slideDown: dt("show"),
                    slideUp: dt("hide"),
                    slideToggle: dt("toggle"),
                    fadeIn: {
                        opacity: "show"
                    },
                    fadeOut: {
                        opacity: "hide"
                    },
                    fadeToggle: {
                        opacity: "toggle"
                    }
                }, (function(e, t) {
                    j.fn[e] = function(e, n, r) {
                        return this.animate(t, e, n, r)
                    }
                }
                )),
                j.timers = [],
                j.fx.tick = function() {
                    var e, t = 0, n = j.timers;
                    for (it = Date.now(); t < n.length; t++)
                        (e = n[t])() || n[t] !== e || n.splice(t--, 1);
                    n.length || j.fx.stop(),
                    it = void 0
                }
                ,
                j.fx.timer = function(e) {
                    j.timers.push(e),
                    j.fx.start()
                }
                ,
                j.fx.interval = 13,
                j.fx.start = function() {
                    at || (at = !0,
                    ut())
                }
                ,
                j.fx.stop = function() {
                    at = null
                }
                ,
                j.fx.speeds = {
                    slow: 600,
                    fast: 200,
                    _default: 400
                },
                j.fn.delay = function(e, t) {
                    return e = j.fx && j.fx.speeds[e] || e,
                    t = t || "fx",
                    this.queue(t, (function(t, n) {
                        var o = r.setTimeout(t, e);
                        n.stop = function() {
                            r.clearTimeout(o)
                        }
                    }
                    ))
                }
                ,
                function() {
                    var e = b.createElement("input")
                      , t = b.createElement("select").appendChild(b.createElement("option"));
                    e.type = "checkbox",
                    v.checkOn = "" !== e.value,
                    v.optSelected = t.selected,
                    (e = b.createElement("input")).value = "t",
                    e.type = "radio",
                    v.radioValue = "t" === e.value
                }();
                var ht, mt = j.expr.attrHandle;
                j.fn.extend({
                    attr: function(e, t) {
                        return z(this, j.attr, e, t, arguments.length > 1)
                    },
                    removeAttr: function(e) {
                        return this.each((function() {
                            j.removeAttr(this, e)
                        }
                        ))
                    }
                }),
                j.extend({
                    attr: function(e, t, n) {
                        var r, o, i = e.nodeType;
                        if (3 !== i && 8 !== i && 2 !== i)
                            return void 0 === e.getAttribute ? j.prop(e, t, n) : (1 === i && j.isXMLDoc(e) || (o = j.attrHooks[t.toLowerCase()] || (j.expr.match.bool.test(t) ? ht : void 0)),
                            void 0 !== n ? null === n ? void j.removeAttr(e, t) : o && "set"in o && void 0 !== (r = o.set(e, n, t)) ? r : (e.setAttribute(t, n + ""),
                            n) : o && "get"in o && null !== (r = o.get(e, t)) ? r : null == (r = j.find.attr(e, t)) ? void 0 : r)
                    },
                    attrHooks: {
                        type: {
                            set: function(e, t) {
                                if (!v.radioValue && "radio" === t && q(e, "input")) {
                                    var n = e.value;
                                    return e.setAttribute("type", t),
                                    n && (e.value = n),
                                    t
                                }
                            }
                        }
                    },
                    removeAttr: function(e, t) {
                        var n, r = 0, o = t && t.match(R);
                        if (o && 1 === e.nodeType)
                            for (; n = o[r++]; )
                                e.removeAttribute(n)
                    }
                }),
                ht = {
                    set: function(e, t, n) {
                        return !1 === t ? j.removeAttr(e, n) : e.setAttribute(n, n),
                        n
                    }
                },
                j.each(j.expr.match.bool.source.match(/\w+/g), (function(e, t) {
                    var n = mt[t] || j.find.attr;
                    mt[t] = function(e, t, r) {
                        var o, i, a = t.toLowerCase();
                        return r || (i = mt[a],
                        mt[a] = o,
                        o = null != n(e, t, r) ? a : null,
                        mt[a] = i),
                        o
                    }
                }
                ));
                var vt = /^(?:input|select|textarea|button)$/i
                  , gt = /^(?:a|area)$/i;
                function yt(e) {
                    return (e.match(R) || []).join(" ")
                }
                function bt(e) {
                    return e.getAttribute && e.getAttribute("class") || ""
                }
                function xt(e) {
                    return Array.isArray(e) ? e : "string" == typeof e && e.match(R) || []
                }
                j.fn.extend({
                    prop: function(e, t) {
                        return z(this, j.prop, e, t, arguments.length > 1)
                    },
                    removeProp: function(e) {
                        return this.each((function() {
                            delete this[j.propFix[e] || e]
                        }
                        ))
                    }
                }),
                j.extend({
                    prop: function(e, t, n) {
                        var r, o, i = e.nodeType;
                        if (3 !== i && 8 !== i && 2 !== i)
                            return 1 === i && j.isXMLDoc(e) || (t = j.propFix[t] || t,
                            o = j.propHooks[t]),
                            void 0 !== n ? o && "set"in o && void 0 !== (r = o.set(e, n, t)) ? r : e[t] = n : o && "get"in o && null !== (r = o.get(e, t)) ? r : e[t]
                    },
                    propHooks: {
                        tabIndex: {
                            get: function(e) {
                                var t = j.find.attr(e, "tabindex");
                                return t ? parseInt(t, 10) : vt.test(e.nodeName) || gt.test(e.nodeName) && e.href ? 0 : -1
                            }
                        }
                    },
                    propFix: {
                        for: "htmlFor",
                        class: "className"
                    }
                }),
                v.optSelected || (j.propHooks.selected = {
                    get: function(e) {
                        var t = e.parentNode;
                        return t && t.parentNode && t.parentNode.selectedIndex,
                        null
                    },
                    set: function(e) {
                        var t = e.parentNode;
                        t && (t.selectedIndex,
                        t.parentNode && t.parentNode.selectedIndex)
                    }
                }),
                j.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], (function() {
                    j.propFix[this.toLowerCase()] = this
                }
                )),
                j.fn.extend({
                    addClass: function(e) {
                        var t, n, r, o, i, a, s, c = 0;
                        if (g(e))
                            return this.each((function(t) {
                                j(this).addClass(e.call(this, t, bt(this)))
                            }
                            ));
                        if ((t = xt(e)).length)
                            for (; n = this[c++]; )
                                if (o = bt(n),
                                r = 1 === n.nodeType && " " + yt(o) + " ") {
                                    for (a = 0; i = t[a++]; )
                                        r.indexOf(" " + i + " ") < 0 && (r += i + " ");
                                    o !== (s = yt(r)) && n.setAttribute("class", s)
                                }
                        return this
                    },
                    removeClass: function(e) {
                        var t, n, r, o, i, a, s, c = 0;
                        if (g(e))
                            return this.each((function(t) {
                                j(this).removeClass(e.call(this, t, bt(this)))
                            }
                            ));
                        if (!arguments.length)
                            return this.attr("class", "");
                        if ((t = xt(e)).length)
                            for (; n = this[c++]; )
                                if (o = bt(n),
                                r = 1 === n.nodeType && " " + yt(o) + " ") {
                                    for (a = 0; i = t[a++]; )
                                        for (; r.indexOf(" " + i + " ") > -1; )
                                            r = r.replace(" " + i + " ", " ");
                                    o !== (s = yt(r)) && n.setAttribute("class", s)
                                }
                        return this
                    },
                    toggleClass: function(e, t) {
                        var n = typeof e
                          , r = "string" === n || Array.isArray(e);
                        return "boolean" == typeof t && r ? t ? this.addClass(e) : this.removeClass(e) : g(e) ? this.each((function(n) {
                            j(this).toggleClass(e.call(this, n, bt(this), t), t)
                        }
                        )) : this.each((function() {
                            var t, o, i, a;
                            if (r)
                                for (o = 0,
                                i = j(this),
                                a = xt(e); t = a[o++]; )
                                    i.hasClass(t) ? i.removeClass(t) : i.addClass(t);
                            else
                                void 0 !== e && "boolean" !== n || ((t = bt(this)) && K.set(this, "__className__", t),
                                this.setAttribute && this.setAttribute("class", t || !1 === e ? "" : K.get(this, "__className__") || ""))
                        }
                        ))
                    },
                    hasClass: function(e) {
                        var t, n, r = 0;
                        for (t = " " + e + " "; n = this[r++]; )
                            if (1 === n.nodeType && (" " + yt(bt(n)) + " ").indexOf(t) > -1)
                                return !0;
                        return !1
                    }
                });
                var wt = /\r/g;
                j.fn.extend({
                    val: function(e) {
                        var t, n, r, o = this[0];
                        return arguments.length ? (r = g(e),
                        this.each((function(n) {
                            var o;
                            1 === this.nodeType && (null == (o = r ? e.call(this, n, j(this).val()) : e) ? o = "" : "number" == typeof o ? o += "" : Array.isArray(o) && (o = j.map(o, (function(e) {
                                return null == e ? "" : e + ""
                            }
                            ))),
                            (t = j.valHooks[this.type] || j.valHooks[this.nodeName.toLowerCase()]) && "set"in t && void 0 !== t.set(this, o, "value") || (this.value = o))
                        }
                        ))) : o ? (t = j.valHooks[o.type] || j.valHooks[o.nodeName.toLowerCase()]) && "get"in t && void 0 !== (n = t.get(o, "value")) ? n : "string" == typeof (n = o.value) ? n.replace(wt, "") : null == n ? "" : n : void 0
                    }
                }),
                j.extend({
                    valHooks: {
                        option: {
                            get: function(e) {
                                var t = j.find.attr(e, "value");
                                return null != t ? t : yt(j.text(e))
                            }
                        },
                        select: {
                            get: function(e) {
                                var t, n, r, o = e.options, i = e.selectedIndex, a = "select-one" === e.type, s = a ? null : [], c = a ? i + 1 : o.length;
                                for (r = i < 0 ? c : a ? i : 0; r < c; r++)
                                    if (((n = o[r]).selected || r === i) && !n.disabled && (!n.parentNode.disabled || !q(n.parentNode, "optgroup"))) {
                                        if (t = j(n).val(),
                                        a)
                                            return t;
                                        s.push(t)
                                    }
                                return s
                            },
                            set: function(e, t) {
                                for (var n, r, o = e.options, i = j.makeArray(t), a = o.length; a--; )
                                    ((r = o[a]).selected = j.inArray(j.valHooks.option.get(r), i) > -1) && (n = !0);
                                return n || (e.selectedIndex = -1),
                                i
                            }
                        }
                    }
                }),
                j.each(["radio", "checkbox"], (function() {
                    j.valHooks[this] = {
                        set: function(e, t) {
                            if (Array.isArray(t))
                                return e.checked = j.inArray(j(e).val(), t) > -1
                        }
                    },
                    v.checkOn || (j.valHooks[this].get = function(e) {
                        return null === e.getAttribute("value") ? "on" : e.value
                    }
                    )
                }
                )),
                v.focusin = "onfocusin"in r;
                var kt = /^(?:focusinfocus|focusoutblur)$/
                  , Ct = function(e) {
                    e.stopPropagation()
                };
                j.extend(j.event, {
                    trigger: function(e, t, n, o) {
                        var i, a, s, c, u, l, d, p, h = [n || b], m = f.call(e, "type") ? e.type : e, v = f.call(e, "namespace") ? e.namespace.split(".") : [];
                        if (a = p = s = n = n || b,
                        3 !== n.nodeType && 8 !== n.nodeType && !kt.test(m + j.event.triggered) && (m.indexOf(".") > -1 && (v = m.split("."),
                        m = v.shift(),
                        v.sort()),
                        u = m.indexOf(":") < 0 && "on" + m,
                        (e = e[j.expando] ? e : new j.Event(m,"object" == typeof e && e)).isTrigger = o ? 2 : 3,
                        e.namespace = v.join("."),
                        e.rnamespace = e.namespace ? new RegExp("(^|\\.)" + v.join("\\.(?:.*\\.|)") + "(\\.|$)") : null,
                        e.result = void 0,
                        e.target || (e.target = n),
                        t = null == t ? [e] : j.makeArray(t, [e]),
                        d = j.event.special[m] || {},
                        o || !d.trigger || !1 !== d.trigger.apply(n, t))) {
                            if (!o && !d.noBubble && !y(n)) {
                                for (c = d.delegateType || m,
                                kt.test(c + m) || (a = a.parentNode); a; a = a.parentNode)
                                    h.push(a),
                                    s = a;
                                s === (n.ownerDocument || b) && h.push(s.defaultView || s.parentWindow || r)
                            }
                            for (i = 0; (a = h[i++]) && !e.isPropagationStopped(); )
                                p = a,
                                e.type = i > 1 ? c : d.bindType || m,
                                (l = (K.get(a, "events") || Object.create(null))[e.type] && K.get(a, "handle")) && l.apply(a, t),
                                (l = u && a[u]) && l.apply && J(a) && (e.result = l.apply(a, t),
                                !1 === e.result && e.preventDefault());
                            return e.type = m,
                            o || e.isDefaultPrevented() || d._default && !1 !== d._default.apply(h.pop(), t) || !J(n) || u && g(n[m]) && !y(n) && ((s = n[u]) && (n[u] = null),
                            j.event.triggered = m,
                            e.isPropagationStopped() && p.addEventListener(m, Ct),
                            n[m](),
                            e.isPropagationStopped() && p.removeEventListener(m, Ct),
                            j.event.triggered = void 0,
                            s && (n[u] = s)),
                            e.result
                        }
                    },
                    simulate: function(e, t, n) {
                        var r = j.extend(new j.Event, n, {
                            type: e,
                            isSimulated: !0
                        });
                        j.event.trigger(r, null, t)
                    }
                }),
                j.fn.extend({
                    trigger: function(e, t) {
                        return this.each((function() {
                            j.event.trigger(e, t, this)
                        }
                        ))
                    },
                    triggerHandler: function(e, t) {
                        var n = this[0];
                        if (n)
                            return j.event.trigger(e, t, n, !0)
                    }
                }),
                v.focusin || j.each({
                    focus: "focusin",
                    blur: "focusout"
                }, (function(e, t) {
                    var n = function(e) {
                        j.event.simulate(t, e.target, j.event.fix(e))
                    };
                    j.event.special[t] = {
                        setup: function() {
                            var r = this.ownerDocument || this.document || this
                              , o = K.access(r, t);
                            o || r.addEventListener(e, n, !0),
                            K.access(r, t, (o || 0) + 1)
                        },
                        teardown: function() {
                            var r = this.ownerDocument || this.document || this
                              , o = K.access(r, t) - 1;
                            o ? K.access(r, t, o) : (r.removeEventListener(e, n, !0),
                            K.remove(r, t))
                        }
                    }
                }
                ));
                var jt = r.location
                  , Tt = {
                    guid: Date.now()
                }
                  , St = /\?/;
                j.parseXML = function(e) {
                    var t;
                    if (!e || "string" != typeof e)
                        return null;
                    try {
                        t = (new r.DOMParser).parseFromString(e, "text/xml")
                    } catch (e) {
                        t = void 0
                    }
                    return t && !t.getElementsByTagName("parsererror").length || j.error("Invalid XML: " + e),
                    t
                }
                ;
                var At = /\[\]$/
                  , Et = /\r?\n/g
                  , Nt = /^(?:submit|button|image|reset|file)$/i
                  , qt = /^(?:input|select|textarea|keygen)/i;
                function Dt(e, t, n, r) {
                    var o;
                    if (Array.isArray(t))
                        j.each(t, (function(t, o) {
                            n || At.test(e) ? r(e, o) : Dt(e + "[" + ("object" == typeof o && null != o ? t : "") + "]", o, n, r)
                        }
                        ));
                    else if (n || "object" !== k(t))
                        r(e, t);
                    else
                        for (o in t)
                            Dt(e + "[" + o + "]", t[o], n, r)
                }
                j.param = function(e, t) {
                    var n, r = [], o = function(e, t) {
                        var n = g(t) ? t() : t;
                        r[r.length] = encodeURIComponent(e) + "=" + encodeURIComponent(null == n ? "" : n)
                    };
                    if (null == e)
                        return "";
                    if (Array.isArray(e) || e.jquery && !j.isPlainObject(e))
                        j.each(e, (function() {
                            o(this.name, this.value)
                        }
                        ));
                    else
                        for (n in e)
                            Dt(n, e[n], t, o);
                    return r.join("&")
                }
                ,
                j.fn.extend({
                    serialize: function() {
                        return j.param(this.serializeArray())
                    },
                    serializeArray: function() {
                        return this.map((function() {
                            var e = j.prop(this, "elements");
                            return e ? j.makeArray(e) : this
                        }
                        )).filter((function() {
                            var e = this.type;
                            return this.name && !j(this).is(":disabled") && qt.test(this.nodeName) && !Nt.test(e) && (this.checked || !ve.test(e))
                        }
                        )).map((function(e, t) {
                            var n = j(this).val();
                            return null == n ? null : Array.isArray(n) ? j.map(n, (function(e) {
                                return {
                                    name: t.name,
                                    value: e.replace(Et, "\r\n")
                                }
                            }
                            )) : {
                                name: t.name,
                                value: n.replace(Et, "\r\n")
                            }
                        }
                        )).get()
                    }
                });
                var Ot = /%20/g
                  , Pt = /#.*$/
                  , _t = /([?&])_=[^&]*/
                  , Lt = /^(.*?):[ \t]*([^\r\n]*)$/gm
                  , $t = /^(?:GET|HEAD)$/
                  , Ht = /^\/\//
                  , Rt = {}
                  , It = {}
                  , Mt = "*/".concat("*")
                  , Wt = b.createElement("a");
                function Ft(e) {
                    return function(t, n) {
                        "string" != typeof t && (n = t,
                        t = "*");
                        var r, o = 0, i = t.toLowerCase().match(R) || [];
                        if (g(n))
                            for (; r = i[o++]; )
                                "+" === r[0] ? (r = r.slice(1) || "*",
                                (e[r] = e[r] || []).unshift(n)) : (e[r] = e[r] || []).push(n)
                    }
                }
                function Bt(e, t, n, r) {
                    var o = {}
                      , i = e === It;
                    function a(s) {
                        var c;
                        return o[s] = !0,
                        j.each(e[s] || [], (function(e, s) {
                            var u = s(t, n, r);
                            return "string" != typeof u || i || o[u] ? i ? !(c = u) : void 0 : (t.dataTypes.unshift(u),
                            a(u),
                            !1)
                        }
                        )),
                        c
                    }
                    return a(t.dataTypes[0]) || !o["*"] && a("*")
                }
                function Qt(e, t) {
                    var n, r, o = j.ajaxSettings.flatOptions || {};
                    for (n in t)
                        void 0 !== t[n] && ((o[n] ? e : r || (r = {}))[n] = t[n]);
                    return r && j.extend(!0, e, r),
                    e
                }
                Wt.href = jt.href,
                j.extend({
                    active: 0,
                    lastModified: {},
                    etag: {},
                    ajaxSettings: {
                        url: jt.href,
                        type: "GET",
                        isLocal: /^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(jt.protocol),
                        global: !0,
                        processData: !0,
                        async: !0,
                        contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                        accepts: {
                            "*": Mt,
                            text: "text/plain",
                            html: "text/html",
                            xml: "application/xml, text/xml",
                            json: "application/json, text/javascript"
                        },
                        contents: {
                            xml: /\bxml\b/,
                            html: /\bhtml/,
                            json: /\bjson\b/
                        },
                        responseFields: {
                            xml: "responseXML",
                            text: "responseText",
                            json: "responseJSON"
                        },
                        converters: {
                            "* text": String,
                            "text html": !0,
                            "text json": JSON.parse,
                            "text xml": j.parseXML
                        },
                        flatOptions: {
                            url: !0,
                            context: !0
                        }
                    },
                    ajaxSetup: function(e, t) {
                        return t ? Qt(Qt(e, j.ajaxSettings), t) : Qt(j.ajaxSettings, e)
                    },
                    ajaxPrefilter: Ft(Rt),
                    ajaxTransport: Ft(It),
                    ajax: function(e, t) {
                        "object" == typeof e && (t = e,
                        e = void 0),
                        t = t || {};
                        var n, o, i, a, s, c, u, l, d, p, f = j.ajaxSetup({}, t), h = f.context || f, m = f.context && (h.nodeType || h.jquery) ? j(h) : j.event, v = j.Deferred(), g = j.Callbacks("once memory"), y = f.statusCode || {}, x = {}, w = {}, k = "canceled", C = {
                            readyState: 0,
                            getResponseHeader: function(e) {
                                var t;
                                if (u) {
                                    if (!a)
                                        for (a = {}; t = Lt.exec(i); )
                                            a[t[1].toLowerCase() + " "] = (a[t[1].toLowerCase() + " "] || []).concat(t[2]);
                                    t = a[e.toLowerCase() + " "]
                                }
                                return null == t ? null : t.join(", ")
                            },
                            getAllResponseHeaders: function() {
                                return u ? i : null
                            },
                            setRequestHeader: function(e, t) {
                                return null == u && (e = w[e.toLowerCase()] = w[e.toLowerCase()] || e,
                                x[e] = t),
                                this
                            },
                            overrideMimeType: function(e) {
                                return null == u && (f.mimeType = e),
                                this
                            },
                            statusCode: function(e) {
                                var t;
                                if (e)
                                    if (u)
                                        C.always(e[C.status]);
                                    else
                                        for (t in e)
                                            y[t] = [y[t], e[t]];
                                return this
                            },
                            abort: function(e) {
                                var t = e || k;
                                return n && n.abort(t),
                                T(0, t),
                                this
                            }
                        };
                        if (v.promise(C),
                        f.url = ((e || f.url || jt.href) + "").replace(Ht, jt.protocol + "//"),
                        f.type = t.method || t.type || f.method || f.type,
                        f.dataTypes = (f.dataType || "*").toLowerCase().match(R) || [""],
                        null == f.crossDomain) {
                            c = b.createElement("a");
                            try {
                                c.href = f.url,
                                c.href = c.href,
                                f.crossDomain = Wt.protocol + "//" + Wt.host != c.protocol + "//" + c.host
                            } catch (e) {
                                f.crossDomain = !0
                            }
                        }
                        if (f.data && f.processData && "string" != typeof f.data && (f.data = j.param(f.data, f.traditional)),
                        Bt(Rt, f, t, C),
                        u)
                            return C;
                        for (d in (l = j.event && f.global) && 0 == j.active++ && j.event.trigger("ajaxStart"),
                        f.type = f.type.toUpperCase(),
                        f.hasContent = !$t.test(f.type),
                        o = f.url.replace(Pt, ""),
                        f.hasContent ? f.data && f.processData && 0 === (f.contentType || "").indexOf("application/x-www-form-urlencoded") && (f.data = f.data.replace(Ot, "+")) : (p = f.url.slice(o.length),
                        f.data && (f.processData || "string" == typeof f.data) && (o += (St.test(o) ? "&" : "?") + f.data,
                        delete f.data),
                        !1 === f.cache && (o = o.replace(_t, "$1"),
                        p = (St.test(o) ? "&" : "?") + "_=" + Tt.guid++ + p),
                        f.url = o + p),
                        f.ifModified && (j.lastModified[o] && C.setRequestHeader("If-Modified-Since", j.lastModified[o]),
                        j.etag[o] && C.setRequestHeader("If-None-Match", j.etag[o])),
                        (f.data && f.hasContent && !1 !== f.contentType || t.contentType) && C.setRequestHeader("Content-Type", f.contentType),
                        C.setRequestHeader("Accept", f.dataTypes[0] && f.accepts[f.dataTypes[0]] ? f.accepts[f.dataTypes[0]] + ("*" !== f.dataTypes[0] ? ", " + Mt + "; q=0.01" : "") : f.accepts["*"]),
                        f.headers)
                            C.setRequestHeader(d, f.headers[d]);
                        if (f.beforeSend && (!1 === f.beforeSend.call(h, C, f) || u))
                            return C.abort();
                        if (k = "abort",
                        g.add(f.complete),
                        C.done(f.success),
                        C.fail(f.error),
                        n = Bt(It, f, t, C)) {
                            if (C.readyState = 1,
                            l && m.trigger("ajaxSend", [C, f]),
                            u)
                                return C;
                            f.async && f.timeout > 0 && (s = r.setTimeout((function() {
                                C.abort("timeout")
                            }
                            ), f.timeout));
                            try {
                                u = !1,
                                n.send(x, T)
                            } catch (e) {
                                if (u)
                                    throw e;
                                T(-1, e)
                            }
                        } else
                            T(-1, "No Transport");
                        function T(e, t, a, c) {
                            var d, p, b, x, w, k = t;
                            u || (u = !0,
                            s && r.clearTimeout(s),
                            n = void 0,
                            i = c || "",
                            C.readyState = e > 0 ? 4 : 0,
                            d = e >= 200 && e < 300 || 304 === e,
                            a && (x = function(e, t, n) {
                                for (var r, o, i, a, s = e.contents, c = e.dataTypes; "*" === c[0]; )
                                    c.shift(),
                                    void 0 === r && (r = e.mimeType || t.getResponseHeader("Content-Type"));
                                if (r)
                                    for (o in s)
                                        if (s[o] && s[o].test(r)) {
                                            c.unshift(o);
                                            break
                                        }
                                if (c[0]in n)
                                    i = c[0];
                                else {
                                    for (o in n) {
                                        if (!c[0] || e.converters[o + " " + c[0]]) {
                                            i = o;
                                            break
                                        }
                                        a || (a = o)
                                    }
                                    i = i || a
                                }
                                if (i)
                                    return i !== c[0] && c.unshift(i),
                                    n[i]
                            }(f, C, a)),
                            !d && j.inArray("script", f.dataTypes) > -1 && (f.converters["text script"] = function() {}
                            ),
                            x = function(e, t, n, r) {
                                var o, i, a, s, c, u = {}, l = e.dataTypes.slice();
                                if (l[1])
                                    for (a in e.converters)
                                        u[a.toLowerCase()] = e.converters[a];
                                for (i = l.shift(); i; )
                                    if (e.responseFields[i] && (n[e.responseFields[i]] = t),
                                    !c && r && e.dataFilter && (t = e.dataFilter(t, e.dataType)),
                                    c = i,
                                    i = l.shift())
                                        if ("*" === i)
                                            i = c;
                                        else if ("*" !== c && c !== i) {
                                            if (!(a = u[c + " " + i] || u["* " + i]))
                                                for (o in u)
                                                    if ((s = o.split(" "))[1] === i && (a = u[c + " " + s[0]] || u["* " + s[0]])) {
                                                        !0 === a ? a = u[o] : !0 !== u[o] && (i = s[0],
                                                        l.unshift(s[1]));
                                                        break
                                                    }
                                            if (!0 !== a)
                                                if (a && e.throws)
                                                    t = a(t);
                                                else
                                                    try {
                                                        t = a(t)
                                                    } catch (e) {
                                                        return {
                                                            state: "parsererror",
                                                            error: a ? e : "No conversion from " + c + " to " + i
                                                        }
                                                    }
                                        }
                                return {
                                    state: "success",
                                    data: t
                                }
                            }(f, x, C, d),
                            d ? (f.ifModified && ((w = C.getResponseHeader("Last-Modified")) && (j.lastModified[o] = w),
                            (w = C.getResponseHeader("etag")) && (j.etag[o] = w)),
                            204 === e || "HEAD" === f.type ? k = "nocontent" : 304 === e ? k = "notmodified" : (k = x.state,
                            p = x.data,
                            d = !(b = x.error))) : (b = k,
                            !e && k || (k = "error",
                            e < 0 && (e = 0))),
                            C.status = e,
                            C.statusText = (t || k) + "",
                            d ? v.resolveWith(h, [p, k, C]) : v.rejectWith(h, [C, k, b]),
                            C.statusCode(y),
                            y = void 0,
                            l && m.trigger(d ? "ajaxSuccess" : "ajaxError", [C, f, d ? p : b]),
                            g.fireWith(h, [C, k]),
                            l && (m.trigger("ajaxComplete", [C, f]),
                            --j.active || j.event.trigger("ajaxStop")))
                        }
                        return C
                    },
                    getJSON: function(e, t, n) {
                        return j.get(e, t, n, "json")
                    },
                    getScript: function(e, t) {
                        return j.get(e, void 0, t, "script")
                    }
                }),
                j.each(["get", "post"], (function(e, t) {
                    j[t] = function(e, n, r, o) {
                        return g(n) && (o = o || r,
                        r = n,
                        n = void 0),
                        j.ajax(j.extend({
                            url: e,
                            type: t,
                            dataType: o,
                            data: n,
                            success: r
                        }, j.isPlainObject(e) && e))
                    }
                }
                )),
                j.ajaxPrefilter((function(e) {
                    var t;
                    for (t in e.headers)
                        "content-type" === t.toLowerCase() && (e.contentType = e.headers[t] || "")
                }
                )),
                j._evalUrl = function(e, t, n) {
                    return j.ajax({
                        url: e,
                        type: "GET",
                        dataType: "script",
                        cache: !0,
                        async: !1,
                        global: !1,
                        converters: {
                            "text script": function() {}
                        },
                        dataFilter: function(e) {
                            j.globalEval(e, t, n)
                        }
                    })
                }
                ,
                j.fn.extend({
                    wrapAll: function(e) {
                        var t;
                        return this[0] && (g(e) && (e = e.call(this[0])),
                        t = j(e, this[0].ownerDocument).eq(0).clone(!0),
                        this[0].parentNode && t.insertBefore(this[0]),
                        t.map((function() {
                            for (var e = this; e.firstElementChild; )
                                e = e.firstElementChild;
                            return e
                        }
                        )).append(this)),
                        this
                    },
                    wrapInner: function(e) {
                        return g(e) ? this.each((function(t) {
                            j(this).wrapInner(e.call(this, t))
                        }
                        )) : this.each((function() {
                            var t = j(this)
                              , n = t.contents();
                            n.length ? n.wrapAll(e) : t.append(e)
                        }
                        ))
                    },
                    wrap: function(e) {
                        var t = g(e);
                        return this.each((function(n) {
                            j(this).wrapAll(t ? e.call(this, n) : e)
                        }
                        ))
                    },
                    unwrap: function(e) {
                        return this.parent(e).not("body").each((function() {
                            j(this).replaceWith(this.childNodes)
                        }
                        )),
                        this
                    }
                }),
                j.expr.pseudos.hidden = function(e) {
                    return !j.expr.pseudos.visible(e)
                }
                ,
                j.expr.pseudos.visible = function(e) {
                    return !!(e.offsetWidth || e.offsetHeight || e.getClientRects().length)
                }
                ,
                j.ajaxSettings.xhr = function() {
                    try {
                        return new r.XMLHttpRequest
                    } catch (e) {}
                }
                ;
                var zt = {
                    0: 200,
                    1223: 204
                }
                  , Ut = j.ajaxSettings.xhr();
                v.cors = !!Ut && "withCredentials"in Ut,
                v.ajax = Ut = !!Ut,
                j.ajaxTransport((function(e) {
                    var t, n;
                    if (v.cors || Ut && !e.crossDomain)
                        return {
                            send: function(o, i) {
                                var a, s = e.xhr();
                                if (s.open(e.type, e.url, e.async, e.username, e.password),
                                e.xhrFields)
                                    for (a in e.xhrFields)
                                        s[a] = e.xhrFields[a];
                                for (a in e.mimeType && s.overrideMimeType && s.overrideMimeType(e.mimeType),
                                e.crossDomain || o["X-Requested-With"] || (o["X-Requested-With"] = "XMLHttpRequest"),
                                o)
                                    s.setRequestHeader(a, o[a]);
                                t = function(e) {
                                    return function() {
                                        t && (t = n = s.onload = s.onerror = s.onabort = s.ontimeout = s.onreadystatechange = null,
                                        "abort" === e ? s.abort() : "error" === e ? "number" != typeof s.status ? i(0, "error") : i(s.status, s.statusText) : i(zt[s.status] || s.status, s.statusText, "text" !== (s.responseType || "text") || "string" != typeof s.responseText ? {
                                            binary: s.response
                                        } : {
                                            text: s.responseText
                                        }, s.getAllResponseHeaders()))
                                    }
                                }
                                ,
                                s.onload = t(),
                                n = s.onerror = s.ontimeout = t("error"),
                                void 0 !== s.onabort ? s.onabort = n : s.onreadystatechange = function() {
                                    4 === s.readyState && r.setTimeout((function() {
                                        t && n()
                                    }
                                    ))
                                }
                                ,
                                t = t("abort");
                                try {
                                    s.send(e.hasContent && e.data || null)
                                } catch (e) {
                                    if (t)
                                        throw e
                                }
                            },
                            abort: function() {
                                t && t()
                            }
                        }
                }
                )),
                j.ajaxPrefilter((function(e) {
                    e.crossDomain && (e.contents.script = !1)
                }
                )),
                j.ajaxSetup({
                    accepts: {
                        script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
                    },
                    contents: {
                        script: /\b(?:java|ecma)script\b/
                    },
                    converters: {
                        "text script": function(e) {
                            return j.globalEval(e),
                            e
                        }
                    }
                }),
                j.ajaxPrefilter("script", (function(e) {
                    void 0 === e.cache && (e.cache = !1),
                    e.crossDomain && (e.type = "GET")
                }
                )),
                j.ajaxTransport("script", (function(e) {
                    var t, n;
                    if (e.crossDomain || e.scriptAttrs)
                        return {
                            send: function(r, o) {
                                t = j("<script>").attr(e.scriptAttrs || {}).prop({
                                    charset: e.scriptCharset,
                                    src: e.url
                                }).on("load error", n = function(e) {
                                    t.remove(),
                                    n = null,
                                    e && o("error" === e.type ? 404 : 200, e.type)
                                }
                                ),
                                b.head.appendChild(t[0])
                            },
                            abort: function() {
                                n && n()
                            }
                        }
                }
                ));
                var Xt, Vt = [], Gt = /(=)\?(?=&|$)|\?\?/;
                j.ajaxSetup({
                    jsonp: "callback",
                    jsonpCallback: function() {
                        var e = Vt.pop() || j.expando + "_" + Tt.guid++;
                        return this[e] = !0,
                        e
                    }
                }),
                j.ajaxPrefilter("json jsonp", (function(e, t, n) {
                    var o, i, a, s = !1 !== e.jsonp && (Gt.test(e.url) ? "url" : "string" == typeof e.data && 0 === (e.contentType || "").indexOf("application/x-www-form-urlencoded") && Gt.test(e.data) && "data");
                    if (s || "jsonp" === e.dataTypes[0])
                        return o = e.jsonpCallback = g(e.jsonpCallback) ? e.jsonpCallback() : e.jsonpCallback,
                        s ? e[s] = e[s].replace(Gt, "$1" + o) : !1 !== e.jsonp && (e.url += (St.test(e.url) ? "&" : "?") + e.jsonp + "=" + o),
                        e.converters["script json"] = function() {
                            return a || j.error(o + " was not called"),
                            a[0]
                        }
                        ,
                        e.dataTypes[0] = "json",
                        i = r[o],
                        r[o] = function() {
                            a = arguments
                        }
                        ,
                        n.always((function() {
                            void 0 === i ? j(r).removeProp(o) : r[o] = i,
                            e[o] && (e.jsonpCallback = t.jsonpCallback,
                            Vt.push(o)),
                            a && g(i) && i(a[0]),
                            a = i = void 0
                        }
                        )),
                        "script"
                }
                )),
                v.createHTMLDocument = ((Xt = b.implementation.createHTMLDocument("").body).innerHTML = "<form></form><form></form>",
                2 === Xt.childNodes.length),
                j.parseHTML = function(e, t, n) {
                    return "string" != typeof e ? [] : ("boolean" == typeof t && (n = t,
                    t = !1),
                    t || (v.createHTMLDocument ? ((r = (t = b.implementation.createHTMLDocument("")).createElement("base")).href = b.location.href,
                    t.head.appendChild(r)) : t = b),
                    i = !n && [],
                    (o = D.exec(e)) ? [t.createElement(o[1])] : (o = Ce([e], t, i),
                    i && i.length && j(i).remove(),
                    j.merge([], o.childNodes)));
                    var r, o, i
                }
                ,
                j.fn.load = function(e, t, n) {
                    var r, o, i, a = this, s = e.indexOf(" ");
                    return s > -1 && (r = yt(e.slice(s)),
                    e = e.slice(0, s)),
                    g(t) ? (n = t,
                    t = void 0) : t && "object" == typeof t && (o = "POST"),
                    a.length > 0 && j.ajax({
                        url: e,
                        type: o || "GET",
                        dataType: "html",
                        data: t
                    }).done((function(e) {
                        i = arguments,
                        a.html(r ? j("<div>").append(j.parseHTML(e)).find(r) : e)
                    }
                    )).always(n && function(e, t) {
                        a.each((function() {
                            n.apply(this, i || [e.responseText, t, e])
                        }
                        ))
                    }
                    ),
                    this
                }
                ,
                j.expr.pseudos.animated = function(e) {
                    return j.grep(j.timers, (function(t) {
                        return e === t.elem
                    }
                    )).length
                }
                ,
                j.offset = {
                    setOffset: function(e, t, n) {
                        var r, o, i, a, s, c, u = j.css(e, "position"), l = j(e), d = {};
                        "static" === u && (e.style.position = "relative"),
                        s = l.offset(),
                        i = j.css(e, "top"),
                        c = j.css(e, "left"),
                        ("absolute" === u || "fixed" === u) && (i + c).indexOf("auto") > -1 ? (a = (r = l.position()).top,
                        o = r.left) : (a = parseFloat(i) || 0,
                        o = parseFloat(c) || 0),
                        g(t) && (t = t.call(e, n, j.extend({}, s))),
                        null != t.top && (d.top = t.top - s.top + a),
                        null != t.left && (d.left = t.left - s.left + o),
                        "using"in t ? t.using.call(e, d) : ("number" == typeof d.top && (d.top += "px"),
                        "number" == typeof d.left && (d.left += "px"),
                        l.css(d))
                    }
                },
                j.fn.extend({
                    offset: function(e) {
                        if (arguments.length)
                            return void 0 === e ? this : this.each((function(t) {
                                j.offset.setOffset(this, e, t)
                            }
                            ));
                        var t, n, r = this[0];
                        return r ? r.getClientRects().length ? (t = r.getBoundingClientRect(),
                        n = r.ownerDocument.defaultView,
                        {
                            top: t.top + n.pageYOffset,
                            left: t.left + n.pageXOffset
                        }) : {
                            top: 0,
                            left: 0
                        } : void 0
                    },
                    position: function() {
                        if (this[0]) {
                            var e, t, n, r = this[0], o = {
                                top: 0,
                                left: 0
                            };
                            if ("fixed" === j.css(r, "position"))
                                t = r.getBoundingClientRect();
                            else {
                                for (t = this.offset(),
                                n = r.ownerDocument,
                                e = r.offsetParent || n.documentElement; e && (e === n.body || e === n.documentElement) && "static" === j.css(e, "position"); )
                                    e = e.parentNode;
                                e && e !== r && 1 === e.nodeType && ((o = j(e).offset()).top += j.css(e, "borderTopWidth", !0),
                                o.left += j.css(e, "borderLeftWidth", !0))
                            }
                            return {
                                top: t.top - o.top - j.css(r, "marginTop", !0),
                                left: t.left - o.left - j.css(r, "marginLeft", !0)
                            }
                        }
                    },
                    offsetParent: function() {
                        return this.map((function() {
                            for (var e = this.offsetParent; e && "static" === j.css(e, "position"); )
                                e = e.offsetParent;
                            return e || ae
                        }
                        ))
                    }
                }),
                j.each({
                    scrollLeft: "pageXOffset",
                    scrollTop: "pageYOffset"
                }, (function(e, t) {
                    var n = "pageYOffset" === t;
                    j.fn[e] = function(r) {
                        return z(this, (function(e, r, o) {
                            var i;
                            if (y(e) ? i = e : 9 === e.nodeType && (i = e.defaultView),
                            void 0 === o)
                                return i ? i[t] : e[r];
                            i ? i.scrollTo(n ? i.pageXOffset : o, n ? o : i.pageYOffset) : e[r] = o
                        }
                        ), e, r, arguments.length)
                    }
                }
                )),
                j.each(["top", "left"], (function(e, t) {
                    j.cssHooks[t] = Ue(v.pixelPosition, (function(e, n) {
                        if (n)
                            return n = ze(e, t),
                            We.test(n) ? j(e).position()[t] + "px" : n
                    }
                    ))
                }
                )),
                j.each({
                    Height: "height",
                    Width: "width"
                }, (function(e, t) {
                    j.each({
                        padding: "inner" + e,
                        content: t,
                        "": "outer" + e
                    }, (function(n, r) {
                        j.fn[r] = function(o, i) {
                            var a = arguments.length && (n || "boolean" != typeof o)
                              , s = n || (!0 === o || !0 === i ? "margin" : "border");
                            return z(this, (function(t, n, o) {
                                var i;
                                return y(t) ? 0 === r.indexOf("outer") ? t["inner" + e] : t.document.documentElement["client" + e] : 9 === t.nodeType ? (i = t.documentElement,
                                Math.max(t.body["scroll" + e], i["scroll" + e], t.body["offset" + e], i["offset" + e], i["client" + e])) : void 0 === o ? j.css(t, n, s) : j.style(t, n, o, s)
                            }
                            ), t, a ? o : void 0, a)
                        }
                    }
                    ))
                }
                )),
                j.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], (function(e, t) {
                    j.fn[t] = function(e) {
                        return this.on(t, e)
                    }
                }
                )),
                j.fn.extend({
                    bind: function(e, t, n) {
                        return this.on(e, null, t, n)
                    },
                    unbind: function(e, t) {
                        return this.off(e, null, t)
                    },
                    delegate: function(e, t, n, r) {
                        return this.on(t, e, n, r)
                    },
                    undelegate: function(e, t, n) {
                        return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
                    },
                    hover: function(e, t) {
                        return this.mouseenter(e).mouseleave(t || e)
                    }
                }),
                j.each("blur focus focusin focusout resize scroll click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup contextmenu".split(" "), (function(e, t) {
                    j.fn[t] = function(e, n) {
                        return arguments.length > 0 ? this.on(t, null, e, n) : this.trigger(t)
                    }
                }
                ));
                var Jt = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
                j.proxy = function(e, t) {
                    var n, r, o;
                    if ("string" == typeof t && (n = e[t],
                    t = e,
                    e = n),
                    g(e))
                        return r = s.call(arguments, 2),
                        (o = function() {
                            return e.apply(t || this, r.concat(s.call(arguments)))
                        }
                        ).guid = e.guid = e.guid || j.guid++,
                        o
                }
                ,
                j.holdReady = function(e) {
                    e ? j.readyWait++ : j.ready(!0)
                }
                ,
                j.isArray = Array.isArray,
                j.parseJSON = JSON.parse,
                j.nodeName = q,
                j.isFunction = g,
                j.isWindow = y,
                j.camelCase = G,
                j.type = k,
                j.now = Date.now,
                j.isNumeric = function(e) {
                    var t = j.type(e);
                    return ("number" === t || "string" === t) && !isNaN(e - parseFloat(e))
                }
                ,
                j.trim = function(e) {
                    return null == e ? "" : (e + "").replace(Jt, "")
                }
                ,
                void 0 === (n = function() {
                    return j
                }
                .apply(t, [])) || (e.exports = n);
                var Yt = r.jQuery
                  , Kt = r.$;
                return j.noConflict = function(e) {
                    return r.$ === j && (r.$ = Kt),
                    e && r.jQuery === j && (r.jQuery = Yt),
                    j
                }
                ,
                void 0 === o && (r.jQuery = r.$ = j),
                j
            }
            ))
        }
    }, i = {};
    function a(e) {
        var t = i[e];
        if (void 0 !== t)
            return t.exports;
        var n = i[e] = {
            exports: {}
        };
        return o[e].call(n.exports, n, n.exports, a),
        n.exports
    }
    a.m = o,
    a.n = e=>{
        var t = e && e.__esModule ? ()=>e.default : ()=>e;
        return a.d(t, {
            a: t
        }),
        t
    }
    ,
    t = Object.getPrototypeOf ? e=>Object.getPrototypeOf(e) : e=>e.__proto__,
    a.t = function(n, r) {
        if (1 & r && (n = this(n)),
        8 & r)
            return n;
        if ("object" == typeof n && n) {
            if (4 & r && n.__esModule)
                return n;
            if (16 & r && "function" == typeof n.then)
                return n
        }
        var o = Object.create(null);
        a.r(o);
        var i = {};
        e = e || [null, t({}), t([]), t(t)];
        for (var s = 2 & r && n; "object" == typeof s && !~e.indexOf(s); s = t(s))
            Object.getOwnPropertyNames(s).forEach((e=>i[e] = ()=>n[e]));
        return i.default = ()=>n,
        a.d(o, i),
        o
    }
    ,
    a.d = (e,t)=>{
        for (var n in t)
            a.o(t, n) && !a.o(e, n) && Object.defineProperty(e, n, {
                enumerable: !0,
                get: t[n]
            })
    }
    ,
    a.f = {},
    a.e = e=>Promise.all(Object.keys(a.f).reduce(((t,n)=>(a.f[n](e, t),
    t)), [])),
    a.u = e=>"8342d42b17603f2993b0-chunk.js",
    a.g = function() {
        if ("object" == typeof globalThis)
            return globalThis;
        try {
            return this || new Function("return this")()
        } catch (e) {
            if ("object" == typeof window)
                return window
        }
    }(),
    a.o = (e,t)=>Object.prototype.hasOwnProperty.call(e, t),
    n = {},
    r = "prestashop-core-theme-js:",
    a.l = (e,t,o,i)=>{
        if (n[e])
            n[e].push(t);
        else {
            var s, c;
            if (void 0 !== o)
                for (var u = document.getElementsByTagName("script"), l = 0; l < u.length; l++) {
                    var d = u[l];
                    if (d.getAttribute("src") == e || d.getAttribute("data-webpack") == r + o) {
                        s = d;
                        break
                    }
                }
            s || (c = !0,
            (s = document.createElement("script")).charset = "utf-8",
            s.timeout = 120,
            a.nc && s.setAttribute("nonce", a.nc),
            s.setAttribute("data-webpack", r + o),
            s.src = e),
            n[e] = [t];
            var p = (t,r)=>{
                s.onerror = s.onload = null,
                clearTimeout(f);
                var o = n[e];
                if (delete n[e],
                s.parentNode && s.parentNode.removeChild(s),
                o && o.forEach((e=>e(r))),
                t)
                    return t(r)
            }
              , f = setTimeout(p.bind(null, void 0, {
                type: "timeout",
                target: s
            }), 12e4);
            s.onerror = p.bind(null, s.onerror),
            s.onload = p.bind(null, s.onload),
            c && document.head.appendChild(s)
        }
    }
    ,
    a.r = e=>{
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
            value: "Module"
        }),
        Object.defineProperty(e, "__esModule", {
            value: !0
        })
    }
    ,
    (()=>{
        var e;
        a.g.importScripts && (e = a.g.location + "");
        var t = a.g.document;
        if (!e && t && (t.currentScript && (e = t.currentScript.src),
        !e)) {
            var n = t.getElementsByTagName("script");
            n.length && (e = n[n.length - 1].src)
        }
        if (!e)
            throw new Error("Automatic publicPath is not supported in this browser");
        e = e.replace(/#.*$/, "").replace(/\?.*$/, "").replace(/\/[^\/]+$/, "/"),
        a.p = e
    }
    )(),
    (()=>{
        var e = {
            179: 0
        };
        a.f.j = (t,n)=>{
            var r = a.o(e, t) ? e[t] : void 0;
            if (0 !== r)
                if (r)
                    n.push(r[2]);
                else {
                    var o = new Promise(((n,o)=>r = e[t] = [n, o]));
                    n.push(r[2] = o);
                    var i = a.p + a.u(t)
                      , s = new Error;
                    a.l(i, (n=>{
                        if (a.o(e, t) && (0 !== (r = e[t]) && (e[t] = void 0),
                        r)) {
                            var o = n && ("load" === n.type ? "missing" : n.type)
                              , i = n && n.target && n.target.src;
                            s.message = "Loading chunk " + t + " failed.\n(" + o + ": " + i + ")",
                            s.name = "ChunkLoadError",
                            s.type = o,
                            s.request = i,
                            r[1](s)
                        }
                    }
                    ), "chunk-" + t, t)
                }
        }
        ;
        var t = (t,n)=>{
            var r, o, [i,s,c] = n, u = 0;
            for (r in s)
                a.o(s, r) && (a.m[r] = s[r]);
            if (c)
                c(a);
            for (t && t(n); u < i.length; u++)
                o = i[u],
                a.o(e, o) && e[o] && e[o][0](),
                e[i[u]] = 0
        }
          , n = self.webpackChunkprestashop_core_theme_js = self.webpackChunkprestashop_core_theme_js || [];
        n.forEach(t.bind(null, 0)),
        n.push = t.bind(null, n.push.bind(n))
    }
    )(),
    (()=>{
        "use strict";
        var e = a(204)
          , t = a.n(e);
        void 0 === t().migrateMute && (t().migrateMute = !window.prestashop.debug);
        a(290),
        a(768),
        a(333);
        const n = prestashop;
        var r = a.n(n);
        /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
        r().selectors = {
            quantityWanted: "#quantity_wanted",
            product: {
                imageContainer: ".quickview .images-container, .page-product:not(.modal-open) .row .images-container, .page-product:not(.modal-open) .product-container .images-container, .quickview .js-images-container, .page-product:not(.modal-open) .row .js-images-container, .page-product:not(.modal-open) .product-container .js-images-container",
                container: ".product-container, .js-product-container",
                availability: "#product-availability, .js-product-availability",
                actions: ".product-actions, .js-product-actions",
                variants: ".product-variants, .js-product-variants",
                refresh: ".product-refresh, .js-product-refresh",
                miniature: ".js-product-miniature",
                minimalQuantity: ".product-minimal-quantity, .js-product-minimal-quantity",
                addToCart: ".quickview .product-add-to-cart, .page-product:not(.modal-open) .row .product-add-to-cart, .page-product:not(.modal-open) .product-container .product-add-to-cart, .quickview .js-product-add-to-cart, .page-product:not(.modal-open) .row .js-product-add-to-cart, .page-product:not(.modal-open) .product-container .js-product-add-to-cart",
                prices: ".quickview .product-prices, .page-product:not(.modal-open) .row .product-prices, .page-product:not(.modal-open) .product-container .product-prices, .quickview .js-product-prices, .page-product:not(.modal-open) .row .js-product-prices, .page-product:not(.modal-open) .product-container .js-product-prices",
                inputCustomization: '.product-actions input[name="id_customization"], .js-product-actions .js-product-customization-id',
                customization: ".quickview .product-customization, .page-product:not(.modal-open) .row .product-customization, .page-product:not(.modal-open) .product-container .product-customization, .quickview .js-product-customization, .page-product:not(.modal-open) .row .js-product-customization, .page-product:not(.modal-open) .product-container .js-product-customization",
                variantsUpdate: ".quickview .product-variants, .page-product:not(.modal-open) .row .product-variants, .page-product:not(.modal-open) .product-container .product-variants, .quickview .js-product-variants, .page-product:not(.modal-open) .row .js-product-variants, .page-product:not(.modal-open) .js-product-container .js-product-variants",
                discounts: ".quickview .product-discounts, .page-product:not(.modal-open) .row .product-discounts, .page-product:not(.modal-open) .product-container .product-discounts, .quickview .js-product-discounts, .page-product:not(.modal-open) .row .js-product-discounts, .page-product:not(.modal-open) .product-container .js-product-discounts",
                additionalInfos: ".quickview .product-additional-info, .page-product:not(.modal-open) .row .product-additional-info, .page-product:not(.modal-open) .product-container .product-additional-info, .quickview .js-product-additional-info, .page-product:not(.modal-open) .row .js-product-additional-info, .page-product:not(.modal-open) .js-product-container .js-product-additional-info",
                details: ".quickview #product-details, #product-details, .quickview .js-product-details, .js-product-details",
                flags: ".quickview .product-flags, .page-product:not(.modal-open) .row .product-flags, .page-product:not(.modal-open) .product-container .product-flags, .quickview .js-product-flags, .page-product:not(.modal-open) .row .js-product-flags, .page-product:not(.modal-open) .js-product-container .js-product-flags"
            },
            listing: {
                quickview: ".quick-view, .js-quick-view"
            },
            checkout: {
                form: ".checkout-step form",
                currentStep: "js-current-step",
                step: ".checkout-step",
                stepTitle: ".step-title, .js-step-title",
                confirmationSelector: "#payment-confirmation button, .js-payment-confirmation",
                conditionsSelector: '#conditions-to-approve input[type="checkbox"], .js-conditions-to-approve',
                conditionAlertSelector: ".js-alert-payment-conditions",
                additionalInformatonSelector: ".js-additional-information",
                optionsForm: ".js-payment-option-form",
                termsCheckboxSelector: '#conditions-to-approve input[name="conditions_to_approve[terms-and-conditions]"], .js-conditions-to-approve input[name="conditions_to_approve[terms-and-conditions]"]',
                paymentBinary: ".payment-binary, .js-payment-binary",
                deliveryFormSelector: "#js-delivery",
                summarySelector: "#js-checkout-summary",
                deliveryStepSelector: "#checkout-delivery-step",
                editDeliveryButtonSelector: ".js-edit-delivery",
                deliveryOption: ".delivery-option, .js-delivery-option",
                cartPaymentStepRefresh: ".js-cart-payment-step-refresh",
                editAddresses: ".js-edit-addresses",
                deliveryAddressRadios: "#delivery-addresses input[type=radio], #invoice-addresses input[type=radio], .js-address-selector input[type=radio]",
                addressItem: ".address-item, .js-address-item",
                addressesStep: "#checkout-addresses-step",
                addressItemChecked: ".address-item:has(input[type=radio]:checked), .js-address-item:has(input[type=radio]:checked)",
                addressError: ".js-address-error",
                notValidAddresses: "#not-valid-addresses, .js-not-valid-addresses",
                invoiceAddresses: "#invoice-addresses, .js-address-selector",
                addressForm: ".js-address-form"
            },
            cart: {
                detailedTotals: ".cart-detailed-totals, .js-cart-detailed-totals",
                summaryItemsSubtotal: ".cart-summary-items-subtotal, .js-cart-summary-items-subtotal",
                summarySubTotalsContainer: ".cart-summary-subtotals-container, .js-cart-summary-subtotals-container",
                summaryTotals: ".cart-summary-totals, .js-cart-summary-totals",
                summaryProducts: ".cart-summary-products, .js-cart-summary-products",
                detailedActions: ".cart-detailed-actions, .js-cart-detailed-actions",
                voucher: ".cart-voucher, .js-cart-voucher",
                overview: ".cart-overview",
                summaryTop: ".cart-summary-top, .js-cart-summary-top",
                productCustomizationId: "#product_customization_id, .js-product-customization-id",
                lineProductQuantity: ".js-cart-line-product-quantity"
            }
        },
        t()(document).ready((()=>{
            r().emit("selectorsInit")
        }
        ));
        function o(e) {
            const t = {};
            return window.location.href.replace(location.hash, "").replace(/[?&]+([^=&]+)=?([^&]*)?/gi, ((e,n,r)=>{
                t[n] = void 0 !== r ? r : ""
            }
            )),
            void 0 !== e ? t[e] ? t[e] : null : t
        }
        function i() {
            const e = o();
            if (e.updatedTransaction)
                return void window.location.reload();
            e.updatedTransaction = 1;
            const t = Object.entries(e).map((e=>e.join("="))).join("&");
            window.location.href = `${window.location.pathname}?${t}`
        }
        r().checkPasswordScore = e=>{
            return t = void 0,
            n = null,
            r = function*() {
                return (0,
                (yield a.e(341).then(a.t.bind(a, 341, 23))).default)(e)
            }
            ,
            new Promise(((e,o)=>{
                var i = e=>{
                    try {
                        s(r.next(e))
                    } catch (e) {
                        o(e)
                    }
                }
                  , a = e=>{
                    try {
                        s(r.throw(e))
                    } catch (e) {
                        o(e)
                    }
                }
                  , s = t=>t.done ? e(t.value) : Promise.resolve(t.value).then(i, a);
                s((r = r.apply(t, n)).next())
            }
            ));
            var t, n, r
        }
        ,
        /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
        t()(document).ready((()=>{
            r().on("updateCart", (e=>{
                r().cart = e.resp.cart;
				console.log(e.resp);
				if(e.resp.cart.products.length && e.resp.id_product)
				{
					var elementname,element_reference,element_manufacturer_name,elementcategory,elementvalue,elementprice,elementquantity;
					
					for (var i = 0, len = e.resp.cart.products.length; i < len; i += 1) 
					{
						var product = e.resp.cart.products[i];
						if(parseInt(product.id) === parseInt(e.resp.id_product))
						{
							//console.log(product);
							elementname = product.name + (typeof(product.attributes_small)!=='undefined' ? ' ' + product.attributes_small : '');
							element_reference = product.reference;
							element_manufacturer_name = product.manufacturer_name ? product.manufacturer_name : 'undefined';
							elementcategory = product.category_name;
							elementvalue = parseFloat(product.price_wt * product.quantity).toFixed(0);
							elementprice = parseFloat(product.price_wt).toFixed(0);
							elementquantity = product.quantity;
						}
					}
					//console.log(elementname,element_reference,element_manufacturer_name,elementcategory,elementvalue,elementprice,elementquantity);
					gtag("event", "add_to_cart", {
						value: elementvalue,			
						currency: e.resp.currency_iso_code,		                   		                    
						items: [{
							item_id: element_reference,
							item_name: elementname.trim(),
							affiliation: 'vzutistore.com.ua',
							item_brand: element_manufacturer_name,
							item_category: elementcategory,
							price: elementprice,
							quantity: elementquantity	                   
						}]
					});
				}
                const n = t()(".js-cart").data("refresh-url");
                if (!n)
                    return;
                let o = {};
                e && e.reason && (o = {
                    id_product_attribute: e.reason.idProductAttribute,
                    id_product: e.reason.idProduct
                }),
                t().post(n, o).then((e=>{
                    t()(r().selectors.cart.detailedTotals).replaceWith(e.cart_detailed_totals),
                    t()(r().selectors.cart.summaryItemsSubtotal).replaceWith(e.cart_summary_items_subtotal),
                    t()(r().selectors.cart.summarySubTotalsContainer).replaceWith(e.cart_summary_subtotals_container),
                    t()(r().selectors.cart.summaryProducts).replaceWith(e.cart_summary_products),
                    t()(r().selectors.cart.summaryTotals).replaceWith(e.cart_summary_totals),
                    t()(r().selectors.cart.detailedActions).replaceWith(e.cart_detailed_actions),
                    t()(r().selectors.cart.voucher).replaceWith(e.cart_voucher),
                    t()(r().selectors.cart.overview).replaceWith(e.cart_detailed),
                    t()(r().selectors.cart.summaryTop).replaceWith(e.cart_summary_top),
                    t()(r().selectors.cart.productCustomizationId).val(0),
                    t()(r().selectors.cart.lineProductQuantity).each(((e,n)=>{
                        const r = t()(n);
                        r.attr("value", r.val())
                    }
                    )),
                    t()(r().selectors.checkout.cartPaymentStepRefresh).length && i(),
                    r().emit("updatedCart", {
                        eventType: "updateCart",
                        resp: e
                    })
                }
                )).fail((e=>{
                    r().emit("handleError", {
                        eventType: "updateCart",
                        resp: e
                    })
                }
                ))
            }
            ));
            const e = t()("body");
            e.on("click", '[data-button-action="add-to-cart"]', (e=>{
                e.preventDefault();
                const n = t()(e.currentTarget.form)
                  , o = `${n.serialize()}&add=1&action=update`
                  , i = n.attr("action")
                  , a = t()(e.currentTarget);
                a.prop("disabled", !0);
                let s = e=>{
                    e.parents(r().selectors.product.addToCart).first().find(r().selectors.product.minimalQuantity).addClass("error"),
                    e.parent().find("label").addClass("error")
                }
                ;
                const c = n.find("input[min]");
                (e=>{
                    let n = !0;
                    return e.each(((e,r)=>{
                        const o = t()(r)
                          , i = parseInt(o.attr("min"), 10);
                        i && o.val() < i && (s(o),
                        n = !1)
                    }
                    )),
                    n
                }
                )(c) ? t().post(i, o, null, "json").then((e=>{
                    e.hasError ? r().emit("handleError", {
                        eventType: "addProductToCart",
                        resp: e
                    }) : r().emit("updateCart", {
                        reason: {
                            idProduct: e.id_product,
                            idProductAttribute: e.id_product_attribute,
                            idCustomization: e.id_customization,
                            linkAction: "add-to-cart",
                            cart: e.cart
                        },
                        resp: e
                    })
                }
                )).fail((e=>{
                    r().emit("handleError", {
                        eventType: "addProductToCart",
                        resp: e
                    })
                }
                )).always((()=>{
                    setTimeout((()=>{
                        a.prop("disabled", !1)
                    }
                    ), 1e3)
                }
                )) : s(c)
            }
            )),
            e.on("submit", '[data-link-action="add-voucher"]', (e=>{
                e.preventDefault();
                const n = t()(e.currentTarget)
                  , o = n.attr("action");
                0 === n.find("[name=action]").length && n.append(t()("<input>", {
                    type: "hidden",
                    name: "ajax",
                    value: 1
                })),
                0 === n.find("[name=action]").length && n.append(t()("<input>", {
                    type: "hidden",
                    name: "action",
                    value: "update"
                })),
                t().post(o, n.serialize(), null, "json").then((n=>{
                    n.hasError ? t()(".js-error").show().find(".js-error-text").text(n.errors[0]) : r().emit("updateCart", {
                        reason: e.target.dataset,
                        resp: n
                    })
                }
                )).fail((e=>{
                    r().emit("handleError", {
                        eventType: "updateCart",
                        resp: e
                    })
                }
                ))
            }
            ))
        }
        ));
        /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
        const s = o("editAddress")
          , c = o("use_same_address");
        t()(window).on("load", (()=>{
            let e = t()(`${r().selectors.checkout.addressError}:visible`);
            if (0 === parseInt(c, 10) && t()(r().selectors.checkout.invoiceAddresses).trigger("click"),
            (null !== s || t()(`${r().selectors.checkout.addressForm}:visible`).length > 1) && e.hide(),
            e.length > 0) {
                const n = t()(r().selectors.checkout.addressError).prop("id").split("-").pop();
                e.each((function() {
                    u(!0, n, t()(this).attr("name").split("-").pop())
                }
                ))
            }
            e = t()(`${r().selectors.checkout.addressError}:visible`),
            l(e.length <= 0)
        }
        ));
        const u = function(e, n, r) {
            const o = t()(`#id-address-${r}-address-${n} a.edit-address`)
              , i = ["text-info", "address-item-invalid"];
            t()(`#${r}-addresses a.edit-address`).removeClass(i),
            o.toggleClass(i, e)
        }
          , l = function(e) {
            t()("button[name=confirm-addresses]").prop("disabled", !e)
        };
        /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
        r().checkout = r().checkout || {},
        r().checkout.onCheckOrderableCartResponse = (e,t)=>!0 === e.errors && (r().emit("orderConfirmationErrors", {
            resp: e,
            paymentObject: t
        }),
        !0);
        class d {
            constructor() {
                this.confirmationSelector = r().selectors.checkout.confirmationSelector,
                this.conditionsSelector = r().selectors.checkout.conditionsSelector,
                this.conditionAlertSelector = r().selectors.checkout.conditionAlertSelector,
                this.additionalInformatonSelector = r().selectors.checkout.additionalInformatonSelector,
                this.optionsForm = r().selectors.checkout.optionsForm,
                this.termsCheckboxSelector = r().selectors.checkout.termsCheckboxSelector
            }
            init() {
                r().on("orderConfirmationErrors", (({resp: e, paymentObject: t})=>{
                    "" !== e.cartUrl && (location.href = e.cartUrl)
                }
                ));
                const e = t()("body");
                e.on("change", `${this.conditionsSelector} input[type="checkbox"]`, t().proxy(this.toggleOrderButton, this)),
                e.on("change", 'input[name="payment-option"]', t().proxy(this.toggleOrderButton, this)),
                this.toggleOrderButton(),
                e.on("click", `${this.confirmationSelector} button`, t().proxy(this.confirm, this)),
                this.getSelectedOption() || this.collapseOptions()
            }
            collapseOptions() {
                t()(`${this.additionalInformatonSelector}, ${this.optionsForm}`).hide()
            }
            getSelectedOption() {
                return t()('input[name="payment-option"]:checked').attr("id")
            }
            haveTermsBeenAccepted() {
                return t()(this.termsCheckboxSelector).prop("checked")
            }
            hideConfirmation() {
                t()(this.confirmationSelector).hide()
            }
            showConfirmation() {
                t()(this.confirmationSelector).show()
            }
            toggleOrderButton() {
                let e = !0;
                t()(`${this.conditionsSelector} input[type="checkbox"]`).each(((t,n)=>{
                    n.checked || (e = !1)
                }
                )),
                r().emit("termsUpdated", {
                    isChecked: e
                }),
                this.collapseOptions();
                const n = this.getSelectedOption();
                if (n || (e = !1),
                t()(`#${n}-additional-information`).show(),
                t()(`#pay-with-${n}-form`).show(),
                t()(r().selectors.checkout.paymentBinary).hide(),
                t()(`#${n}`).hasClass("binary")) {
                    const r = this.getPaymentOptionSelector(n);
                    this.hideConfirmation(),
                    t()(r).show(),
                    document.querySelectorAll(`${r} button, ${r} input`).forEach((t=>{
                        e ? t.removeAttribute("disabled") : t.setAttribute("disabled", !e)
                    }
                    )),
                    e ? t()(r).removeClass("disabled") : t()(r).addClass("disabled")
                } else
                    this.showConfirmation(),
                    t()(`${this.confirmationSelector} button`).toggleClass("disabled", !e),
                    t()(`${this.confirmationSelector} button`).attr("disabled", !e),
                    e ? t()(this.conditionAlertSelector).hide() : t()(this.conditionAlertSelector).show()
            }
            getPaymentOptionSelector(e) {
                return `.js-payment-${t()(`#${e}`).data("module-name")}`
            }
            showNativeFormErrors() {
                t()(`input[name=payment-option], ${this.termsCheckboxSelector}`).each((function() {
                    this.reportValidity()
                }
                ))
            }
            confirm() {
                return e = this,
                n = null,
                o = function*() {
                    const e = this.getSelectedOption()
                      , n = this.haveTermsBeenAccepted();
                    if (void 0 === e || !1 === n)
                        return void this.showNativeFormErrors();
                    const o = yield t().post(window.prestashop.urls.pages.order, {
                        ajax: 1,
                        action: "checkCartStillOrderable"
                    });
                    r().checkout.onCheckOrderableCartResponse(o, this) || (t()(`${this.confirmationSelector} button`).addClass("disabled"),
                    t()(`#pay-with-${e}-form form`).submit())
                }
                ,
                new Promise(((t,r)=>{
                    var i = e=>{
                        try {
                            s(o.next(e))
                        } catch (e) {
                            r(e)
                        }
                    }
                      , a = e=>{
                        try {
                            s(o.throw(e))
                        } catch (e) {
                            r(e)
                        }
                    }
                      , s = e=>e.done ? t(e.value) : Promise.resolve(e.value).then(i, a);
                    s((o = o.apply(e, n)).next())
                }
                ));
                var e, n, o
            }
        }
        /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
        const p = r().selectors.checkout.currentStep
          , f = `.${p}`;
        class h {
            constructor() {
                this.$steps = t()(r().selectors.checkout.step),
                this.$steps.off("click"),
                this.$clickableSteps = t()(f).prevAll().andSelf(),
                this.$clickableSteps.addClass("-clickable")
            }
            getClickableSteps() {
                return this.$clickableSteps
            }
            makeCurrent(e) {
                this.$steps.removeClass("-current"),
                this.$steps.removeClass(p),
                e.makeCurrent()
            }
            static getClickedStep(e) {
                return new m(t()(e.target).closest(r().selectors.checkout.step))
            }
        }
        class m {
            constructor(e) {
                this.$step = e
            }
            isUnreachable() {
                return this.$step.hasClass("-unreachable")
            }
            makeCurrent() {
                this.$step.addClass("-current"),
                this.$step.addClass(p)
            }
            hasContinueButton() {
                return t()("button.continue", this.$step).length > 0
            }
            disableAllAfter() {
                const e = this.$step.nextAll();
                e.addClass("-unreachable").removeClass("-complete"),
                t()(r().selectors.checkout.stepTitle, e).addClass("not-allowed")
            }
            enableAllBefore() {
                const e = this.$step.nextAll(`${r().selectors.checkout.step}.-clickable`);
                e.removeClass("-unreachable").addClass("-complete"),
                t()(r().selectors.checkout.stepTitle, e).removeClass("not-allowed")
            }
        }
        /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
        function v() {
            t()(r().selectors.checkout.editAddresses).on("click", (e=>{
                e.stopPropagation(),
                t()(r().selectors.checkout.addressesStep).trigger("click"),
                r().emit("editAddress")
            }
            )),
            t()(r().selectors.checkout.deliveryAddressRadios).on("click", (function() {
                t()(r().selectors.checkout.addressItem).removeClass("selected"),
                t()(r().selectors.checkout.addressItemChecked).addClass("selected");
                const e = t()(r().selectors.checkout.addressError).prop("id").split("-").pop()
                  , n = t()(r().selectors.checkout.notValidAddresses).val()
                  , o = this.name.split("_").pop()
                  , i = t()(`${r().selectors.checkout.addressError}[name=alert-${o}]`);
                u(!1, e, o),
                "" !== n && null === s && n.split(",").indexOf(this.value) >= 0 ? (i.show(),
                u(!0, this.value, o),
                t()(r().selectors.checkout.addressError).prop("id", `id-failure-address-${this.value}`)) : i.hide();
                const a = t()(`${r().selectors.checkout.addressError}:visible`);
                l(a.length <= 0)
            }
            )),
            /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
            function() {
                const e = t()("body")
                  , {deliveryFormSelector: n} = r().selectors.checkout
                  , {summarySelector: o} = r().selectors.checkout
                  , {deliveryStepSelector: a} = r().selectors.checkout
                  , {editDeliveryButtonSelector: s} = r().selectors.checkout;
                e.on("change", `${n} input`, (e=>{
                    const a = t()(n)
                      , s = a.serialize()
                      , c = t()(e.currentTarget).parents(r().selectors.checkout.deliveryOption);
                    t().post(a.data("url-update"), s).then((e=>{
                        t()(o).replaceWith(e.preview),
                        t()(r().selectors.checkout.cartPaymentStepRefresh).length && i(),
                        r().emit("updatedDeliveryForm", {
                            dataForm: a.serializeArray(),
                            deliveryOption: c,
                            resp: e
                        })
                    }
                    )).fail((e=>{
                        r().trigger("handleError", {
                            eventType: "updateDeliveryOptions",
                            resp: e
                        })
                    }
                    ))
                }
                )),
                e.on("click", s, (e=>{
                    e.stopPropagation(),
                    t()(a).trigger("click"),
                    r().emit("editDelivery")
                }
                ))
            }(),
            function() {
                const e = new d;
                e.init()
            }(),
            function() {
                const e = new h;
                e.getClickableSteps().on("click", (t=>{
                    const n = h.getClickedStep(t);
                    n.isUnreachable() || (e.makeCurrent(n),
                    n.hasContinueButton() ? n.disableAllAfter() : n.enableAllBefore()),
                    r().emit("changedCheckoutStep", {
                        event: t
                    })
                }
                ))
            }(),
            function() {
                const e = r().selectors.checkout.form;
                t()(e).submit((function(e) {
                    !0 === t()(this).data("disabled") && e.preventDefault(),
                    t()(this).data("disabled", !0),
                    t()('button[type="submit"]', this).addClass("disabled")
                }
                ))
            }()
        }
        t()(document).ready((()=>{
            1 === t()("#checkout").length && v()
        }
        ));
        /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
        let g = null;
        function y(e) {
            r().emit("updateProductList", e),
            window.history.pushState(e, document.title, e.current_url)
        }
        function b(e, t) {
            return "abort" !== t
        }
        function x(e) {
            g === e && (g = null)
        }
        t()(document).ready((()=>{
            r().on("updateFacets", (e=>{
                !function(e) {
                    g && g.abort();
                    const n = e.indexOf("?") >= 0 ? "&" : "?"
                      , r = `${e + n}from-xhr`;
                    g = t().ajax({
                        url: r,
                        dataType: "json",
                        success: y,
                        error: b,
                        complete: x
                    })
                }(e)
            }
            ))
        }
        )),
        /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
        t()(document).ready((()=>{
            t()("body").on("click", r().selectors.listing.quickview, (e=>{
                r().emit("clickQuickView", {
                    dataset: t()(e.target).closest(r().selectors.product.miniature).data()
                }),
                e.preventDefault()
            }
            ))
        }
        ));
        var w = Object.defineProperty
          , k = Object.getOwnPropertySymbols
          , C = Object.prototype.hasOwnProperty
          , j = Object.prototype.propertyIsEnumerable
          , T = (e,t,n)=>t in e ? w(e, t, {
            enumerable: !0,
            configurable: !0,
            writable: !0,
            value: n
        }) : e[t] = n
          , S = (e,t)=>{
            for (var n in t || (t = {}))
                C.call(t, n) && T(e, n, t[n]);
            if (k)
                for (var n of k(t))
                    j.call(t, n) && T(e, n, t[n]);
            return e
        }
        ;
        /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
        let A = null
          , E = null
          , N = !1;
        const q = [];
        let D = !1;
        function O(e) {
            !function(e, n) {
                const r = t()(`<div class="alert alert-danger ajax-error" role="alert">${n}</div>`);
                e.replaceWith(r)
            }(t()(".quickview #product-availability, .page-product:not(.modal-open) .row #product-availability, .page-product:not(.modal-open) .product-container #product-availability"), e)
        }
        function P(e, n, i) {
            const a = t()(r().selectors.product.actions)
              , s = a.find(r().selectors.quantityWanted)
              , c = a.find("form:first")
              , u = c.serialize();
            let l, d = o("preview");
            if ("function" == typeof Event ? l = new Event("updateRating") : (l = document.createEvent("Event"),
            l.initEvent("updateRating", !0, !0)),
            d = null !== d ? `&preview=${d}` : "",
            null === i)
                return void O();
            if (e && "keyup" === e.type && s.val() === s.data("old-value"))
                return;
            s.data("old-value", s.val()),
            E && clearTimeout(E);
            let p = 30;
            "updatedProductQuantity" === n && (p = 750),
            E = setTimeout((()=>{
                "" !== u && (A = t().ajax({
                    url: i + (-1 === i.indexOf("?") ? "?" : "&") + u + d,
                    method: "POST",
                    data: {
                        quickview: t()(".modal.quickview.in").length,
                        ajax: 1,
                        action: "refresh",
                        quantity_wanted: "updatedProductCombination" === n ? s.attr("min") : s.val()
                    },
                    dataType: "json",
                    beforeSend() {
                        null !== A && A.abort()
                    },
                    error(e, n) {
                        "abort" !== n && 0 === t()("section#main > .ajax-error").length && O()
                    },
                    success(e) {
                        const o = t()("<div>").append(e.product_cover_thumbnails);
                        t()(r().selectors.product.imageContainer).html() !== o.find(r().selectors.product.imageContainer).html() && t()(r().selectors.product.imageContainer).replaceWith(e.product_cover_thumbnails),
                        t()(r().selectors.product.prices).first().replaceWith(e.product_prices),
                        t()(r().selectors.product.customization).first().replaceWith(e.product_customization),
                        "updatedProductQuantity" !== n && "updatedProductCombination" !== n || !e.id_customization ? t()(r().selectors.product.inputCustomization).val(0) : t()(r().selectors.cart.productCustomizationId).val(e.id_customization),
                        t()(r().selectors.product.variantsUpdate).first().replaceWith(e.product_variants),
                        t()(r().selectors.product.discounts).first().replaceWith(e.product_discounts),
                        t()(r().selectors.product.additionalInfos).first().replaceWith(e.product_additional_info),
                        t()(r().selectors.product.details).replaceWith(e.product_details),
                        t()(r().selectors.product.flags).first().replaceWith(e.product_flags),
                        function(e) {
                            let n = null;
                            t()(e.product_add_to_cart).each(((e,r)=>!t()(r).hasClass("product-add-to-cart") || (n = t()(r),
                            !1))),
                            null === n && O();
                            const o = t()(r().selectors.product.addToCart)
                              , i = "#product-availability"
                              , a = ".product-minimal-quantity";
                            _({
                                $addToCartSnippet: n,
                                $targetParent: o,
                                targetSelector: ".add"
                            }),
                            _({
                                $addToCartSnippet: n,
                                $targetParent: o,
                                targetSelector: i
                            }),
                            _({
                                $addToCartSnippet: n,
                                $targetParent: o,
                                targetSelector: a
                            })
                        }(e);
                        const i = parseInt(e.product_minimal_quantity, 10);
                        document.dispatchEvent(l),
                        isNaN(i) || "updatedProductQuantity" === n || (s.attr("min", i),
                        s.val(i)),
                        r().emit("updatedProduct", e, c.serializeArray())
                    },
                    complete() {
                        A = null,
                        E = null
                    }
                }))
            }
            ), p)
        }
        function _(e) {
            const n = t()(e.$targetParent.find(e.targetSelector));
            if (n.length <= 0)
                return;
            const r = e.$addToCartSnippet.find(e.targetSelector);
            r.length > 0 ? n.replaceWith(r[0].outerHTML) : n.html("")
        }
        t()(document).ready((()=>{
            const e = t()(r().selectors.product.actions);
            t()("body").on("change touchspin.on.startspin", `${r().selectors.product.variants} *[name]`, (e=>{
                D = !0,
                r().emit("updateProduct", {
                    eventType: "updatedProductCombination",
                    event: e,
                    resp: {},
                    reason: {
                        productUrl: r().urls.pages.product || ""
                    }
                })
            }
            )),
            t()(e.find("form:first").serializeArray()).each(((e,{value: t, name: n})=>{
                q.push({
                    value: t,
                    name: n
                })
            }
            )),
            window.addEventListener("popstate", (e=>{
                if (N = !0,
                (!e.state || e.state && e.state.form && 0 === e.state.form.length) && !D)
                    return;
                const n = t()(r().selectors.product.actions).find("form:first");
                e.state && e.state.form ? e.state.form.forEach((e=>{
                    n.find(`[name="${e.name}"]`).val(e.value)
                }
                )) : q.forEach((e=>{
                    n.find(`[name="${e.name}"]`).val(e.value)
                }
                )),
                r().emit("updateProduct", {
                    eventType: "updatedProductCombination",
                    event: e,
                    resp: {},
                    reason: {
                        productUrl: r().urls.pages.product || ""
                    }
                })
            }
            )),
            t()("body").on("click", r().selectors.product.refresh, ((e,t)=>{
                e.preventDefault();
                let n = "updatedProductCombination";
                void 0 !== t && t.eventType && (n = t.eventType),
                r().emit("updateProduct", {
                    eventType: n,
                    event: e,
                    resp: {},
                    reason: {
                        productUrl: r().urls.pages.product || ""
                    }
                })
            }
            )),
            r().on("updateProduct", (e=>{
                const {eventType: n} = e
                  , {event: o} = e;
                (function() {
                    const e = t().Deferred()
                      , n = t()(r().selectors.product.actions)
                      , o = t()(r().selectors.quantityWanted);
                    if (null !== r() && null !== r().urls && null !== r().urls.pages && "" !== r().urls.pages.product && null !== r().urls.pages.product)
                        return e.resolve(r().urls.pages.product),
                        e.promise();
                    const i = {};
                    return t()(n.find("form:first").serializeArray()).each(((e,t)=>{
                        i[t.name] = t.value
                    }
                    )),
                    t().ajax({
                        url: n.find("form:first").attr("action"),
                        method: "POST",
                        data: S({
                            ajax: 1,
                            action: "productrefresh",
                            quantity_wanted: o.val()
                        }, i),
                        dataType: "json",
                        success(t) {
                            const n = t.productUrl;
                            r().page.canonical = n,
                            e.resolve(n)
                        },
                        error(t, n, r) {
                            e.reject({
                                jqXHR: t,
                                textStatus: n,
                                errorThrown: r
                            })
                        }
                    }),
                    e.promise()
                }
                )().done((e=>P(o, n, e))).fail((()=>{
                    0 === t()("section#main > .ajax-error").length && O()
                }
                ))
            }
            )),
            r().on("updatedProduct", ((e,n)=>{
                if (!e.product_url || !e.id_product_attribute)
                    return;
                if (t()(".modal.quickview").length)
                    return;
                let r = document.title;
                e.product_title && (r = e.product_title,
                t()(document).attr("title", r)),
                N || window.history.pushState({
                    id_product_attribute: e.id_product_attribute,
                    form: n
                }, r, e.product_url),
                N = !1
            }
            )),
            r().on("updateCart", (e=>{
                if (!e || !e.reason || "add-to-cart" !== e.reason.linkAction)
                    return;
                t()("#quantity_wanted").val(1)
            }
            )),
            r().on("showErrorNextToAddtoCartButton", (e=>{
                e && e.errorMessage && O(e.errorMessage)
            }
            ))
        }
        )),
        t()(document).ready((()=>{
            var e;
            e = {
                country: ".js-country",
                address: ".js-address-form"
            },
            t()("body").on("change", e.country, (()=>{
                const n = {
                    id_country: t()(e.country).val(),
                    id_address: t()(`${e.address} form`).data("id-address")
                }
                  , o = t()(`${e.address} form`).data("refresh-url")
                  , i = `${e.address} input`;
                t().post(o, n).then((n=>{
                    const o = [];
                    t()(i).each((function() {
                        o[t()(this).prop("name")] = t()(this).val()
                    }
                    )),
                    t()(e.address).replaceWith(n.address_form),
                    t()(i).each((function() {
                        t()(this).val(o[t()(this).prop("name")])
                    }
                    )),
                    r().emit("updatedAddressForm", {
                        target: t()(e.address),
                        resp: n
                    })
                }
                )).fail((e=>{
                    r().emit("handleError", {
                        eventType: "updateAddressForm",
                        resp: e
                    })
                }
                ))
            }
            ))
        }
        ));
        const L = 2147483647
          , $ = 36
          , H = /^xn--/
          , R = /[^\0-\x7E]/
          , I = /[\x2E\u3002\uFF0E\uFF61]/g
          , M = {
            overflow: "Overflow: input needs wider integers to process",
            "not-basic": "Illegal input >= 0x80 (not a basic code point)",
            "invalid-input": "Invalid input"
        }
          , W = Math.floor
          , F = String.fromCharCode;
        function B(e) {
            throw new RangeError(M[e])
        }
        function Q(e, t) {
            const n = e.split("@");
            let r = "";
            n.length > 1 && (r = n[0] + "@",
            e = n[1]);
            const o = function(e, t) {
                const n = [];
                let r = e.length;
                for (; r--; )
                    n[r] = t(e[r]);
                return n
            }((e = e.replace(I, ".")).split("."), t).join(".");
            return r + o
        }
        function z(e) {
            const t = [];
            let n = 0;
            const r = e.length;
            for (; n < r; ) {
                const o = e.charCodeAt(n++);
                if (o >= 55296 && o <= 56319 && n < r) {
                    const r = e.charCodeAt(n++);
                    56320 == (64512 & r) ? t.push(((1023 & o) << 10) + (1023 & r) + 65536) : (t.push(o),
                    n--)
                } else
                    t.push(o)
            }
            return t
        }
        const U = function(e, t) {
            return e + 22 + 75 * (e < 26) - ((0 != t) << 5)
        }
          , X = function(e, t, n) {
            let r = 0;
            for (e = n ? W(e / 700) : e >> 1,
            e += W(e / t); e > 455; r += $)
                e = W(e / 35);
            return W(r + 36 * e / (e + 38))
        }
          , V = function(e) {
            const t = []
              , n = e.length;
            let r = 0
              , o = 128
              , i = 72
              , a = e.lastIndexOf("-");
            a < 0 && (a = 0);
            for (let n = 0; n < a; ++n)
                e.charCodeAt(n) >= 128 && B("not-basic"),
                t.push(e.charCodeAt(n));
            for (let c = a > 0 ? a + 1 : 0; c < n; ) {
                let a = r;
                for (let t = 1, o = $; ; o += $) {
                    c >= n && B("invalid-input");
                    const a = (s = e.charCodeAt(c++)) - 48 < 10 ? s - 22 : s - 65 < 26 ? s - 65 : s - 97 < 26 ? s - 97 : $;
                    (a >= $ || a > W((L - r) / t)) && B("overflow"),
                    r += a * t;
                    const u = o <= i ? 1 : o >= i + 26 ? 26 : o - i;
                    if (a < u)
                        break;
                    const l = $ - u;
                    t > W(L / l) && B("overflow"),
                    t *= l
                }
                const u = t.length + 1;
                i = X(r - a, u, 0 == a),
                W(r / u) > L - o && B("overflow"),
                o += W(r / u),
                r %= u,
                t.splice(r++, 0, o)
            }
            var s;
            return String.fromCodePoint(...t)
        }
          , G = function(e) {
            const t = [];
            let n = (e = z(e)).length
              , r = 128
              , o = 0
              , i = 72;
            for (const n of e)
                n < 128 && t.push(F(n));
            let a = t.length
              , s = a;
            for (a && t.push("-"); s < n; ) {
                let n = L;
                for (const t of e)
                    t >= r && t < n && (n = t);
                const c = s + 1;
                n - r > W((L - o) / c) && B("overflow"),
                o += (n - r) * c,
                r = n;
                for (const n of e)
                    if (n < r && ++o > L && B("overflow"),
                    n == r) {
                        let e = o;
                        for (let n = $; ; n += $) {
                            const r = n <= i ? 1 : n >= i + 26 ? 26 : n - i;
                            if (e < r)
                                break;
                            const o = e - r
                              , a = $ - r;
                            t.push(F(U(r + o % a, 0))),
                            e = W(o / a)
                        }
                        t.push(F(U(e, 0))),
                        i = X(o, c, s == a),
                        o = 0,
                        ++s
                    }
                ++o,
                ++r
            }
            return t.join("")
        }
          , J = {
            version: "2.1.0",
            ucs2: {
                decode: z,
                encode: e=>String.fromCodePoint(...e)
            },
            decode: V,
            encode: G,
            toASCII: function(e) {
                return Q(e, (function(e) {
                    return R.test(e) ? "xn--" + G(e) : e
                }
                ))
            },
            toUnicode: function(e) {
                return Q(e, (function(e) {
                    return H.test(e) ? V(e.slice(4).toLowerCase()) : e
                }
                ))
            }
        }
          , Y = function(e) {
            const n = t()(e);
            t().each(n, ((e,t)=>{
                if (!t.checkValidity()) {
                    const e = t.value.split("@");
                    J.toASCII(e[0]) === e[0] && (t.value = J.toASCII(t.value))
                }
            }
            ))
        };
        /**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
        a.p = window.prestashop.core_js_public_path,
        window.$ = t(),
        window.jQuery = t(),
        t()(document).ready((()=>{
            t()(".ps-shown-by-js").show(),
            t()(".ps-hidden-by-js").hide(),
            Y('input[type="email"]')
        }
        ))
    }
    )()
}
)();
//# sourceMappingURL=core.js.map
