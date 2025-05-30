/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future.If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @category  PrestaShop Module
 * @author    knowband.com <support@nowband.com>
 * @copyright 2016 Knowband
 * @license   see file: LICENSE.txt
 */
!function(e) {
    var t = {};
    function n(r) {
        if (t[r])
            return t[r].exports;
        var o = t[r] = {
            i: r,
            l: !1,
            exports: {}
        };
        return e[r].call(o.exports, o, o.exports, n),
        o.l = !0,
        o.exports
    }
    n.m = e,
    n.c = t,
    n.d = function(e, t, r) {
        n.o(e, t) || Object.defineProperty(e, t, {
            enumerable: !0,
            get: r
        })
    }
    ,
    n.r = function(e) {
        "undefined" !== typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
            value: "Module"
        }),
        Object.defineProperty(e, "__esModule", {
            value: !0
        })
    }
    ,
    n.t = function(e, t) {
        if (1 & t && (e = n(e)),
        8 & t)
            return e;
        if (4 & t && "object" === typeof e && e && e.__esModule)
            return e;
        var r = Object.create(null);
        if (n.r(r),
        Object.defineProperty(r, "default", {
            enumerable: !0,
            value: e
        }),
        2 & t && "string" != typeof e)
            for (var o in e)
                n.d(r, o, function(t) {
                    return e[t]
                }
                .bind(null, o));
        return r
    }
    ,
    n.n = function(e) {
        var t = e && e.__esModule ? function() {
            return e.default
        }
        : function() {
            return e
        }
        ;
        return n.d(t, "a", t),
        t
    }
    ,
    n.o = function(e, t) {
        return Object.prototype.hasOwnProperty.call(e, t)
    }
    ,
    n.p = "",
    n(n.s = 13)
}([function(e, t) {
    var n;
    n = function() {
        return this
    }();
    try {
        n = n || new Function("return this")()
    } catch (r) {
        "object" === typeof window && (n = window)
    }
    e.exports = n
}
, function(e, t, n) {
    "use strict";
    t.a = function(e) {
        var t = this.constructor;
        return this.then((function(n) {
            return t.resolve(e()).then((function() {
                return n
            }
            ))
        }
        ), (function(n) {
            return t.resolve(e()).then((function() {
                return t.reject(n)
            }
            ))
        }
        ))
    }
}
, function(e, t, n) {
    "use strict";
    (function(e) {
        var r = n(1)
          , o = setTimeout;
        function i(e) {
            return Boolean(e && "undefined" !== typeof e.length)
        }
        function a() {}
        function c(e) {
            if (!(this instanceof c))
                throw new TypeError("Promises must be constructed via new");
            if ("function" !== typeof e)
                throw new TypeError("not a function");
            this._state = 0,
            this._handled = !1,
            this._value = void 0,
            this._deferreds = [],
            d(e, this)
        }
        function s(e, t) {
            for (; 3 === e._state; )
                e = e._value;
            0 !== e._state ? (e._handled = !0,
            c._immediateFn((function() {
                var n = 1 === e._state ? t.onFulfilled : t.onRejected;
                if (null !== n) {
                    var r;
                    try {
                        r = n(e._value)
                    } catch (o) {
                        return void l(t.promise, o)
                    }
                    u(t.promise, r)
                } else
                    (1 === e._state ? u : l)(t.promise, e._value)
            }
            ))) : e._deferreds.push(t)
        }
        function u(e, t) {
            try {
                if (t === e)
                    throw new TypeError("A promise cannot be resolved with itself.");
                if (t && ("object" === typeof t || "function" === typeof t)) {
                    var n = t.then;
                    if (t instanceof c)
                        return e._state = 3,
                        e._value = t,
                        void f(e);
                    if ("function" === typeof n)
                        return void d((r = n,
                        o = t,
                        function() {
                            r.apply(o, arguments)
                        }
                        ), e)
                }
                e._state = 1,
                e._value = t,
                f(e)
            } catch (i) {
                l(e, i)
            }
            var r, o
        }
        function l(e, t) {
            e._state = 2,
            e._value = t,
            f(e)
        }
        function f(e) {
            2 === e._state && 0 === e._deferreds.length && c._immediateFn((function() {
                e._handled || c._unhandledRejectionFn(e._value)
            }
            ));
            for (var t = 0, n = e._deferreds.length; t < n; t++)
                s(e, e._deferreds[t]);
            e._deferreds = null
        }
        function h(e, t, n) {
            this.onFulfilled = "function" === typeof e ? e : null,
            this.onRejected = "function" === typeof t ? t : null,
            this.promise = n
        }
        function d(e, t) {
            var n = !1;
            try {
                e((function(e) {
                    n || (n = !0,
                    u(t, e))
                }
                ), (function(e) {
                    n || (n = !0,
                    l(t, e))
                }
                ))
            } catch (r) {
                if (n)
                    return;
                n = !0,
                l(t, r)
            }
        }
        c.prototype.catch = function(e) {
            return this.then(null, e)
        }
        ,
        c.prototype.then = function(e, t) {
            var n = new this.constructor(a);
            return s(this, new h(e,t,n)),
            n
        }
        ,
        c.prototype.finally = r.a,
        c.all = function(e) {
            return new c((function(t, n) {
                if (!i(e))
                    return n(new TypeError("Promise.all accepts an array"));
                var r = Array.prototype.slice.call(e);
                if (0 === r.length)
                    return t([]);
                var o = r.length;
                function a(e, i) {
                    try {
                        if (i && ("object" === typeof i || "function" === typeof i)) {
                            var c = i.then;
                            if ("function" === typeof c)
                                return void c.call(i, (function(t) {
                                    a(e, t)
                                }
                                ), n)
                        }
                        r[e] = i,
                        0 === --o && t(r)
                    } catch (s) {
                        n(s)
                    }
                }
                for (var c = 0; c < r.length; c++)
                    a(c, r[c])
            }
            ))
        }
        ,
        c.resolve = function(e) {
            return e && "object" === typeof e && e.constructor === c ? e : new c((function(t) {
                t(e)
            }
            ))
        }
        ,
        c.reject = function(e) {
            return new c((function(t, n) {
                n(e)
            }
            ))
        }
        ,
        c.race = function(e) {
            return new c((function(t, n) {
                if (!i(e))
                    return n(new TypeError("Promise.race accepts an array"));
                for (var r = 0, o = e.length; r < o; r++)
                    c.resolve(e[r]).then(t, n)
            }
            ))
        }
        ,
        c._immediateFn = "function" === typeof e && function(t) {
            e(t)
        }
        || function(e) {
            o(e, 0)
        }
        ,
        c._unhandledRejectionFn = function(e) {
            "undefined" !== typeof console && console && console.warn("Possible Unhandled Promise Rejection:", e)
        }
        ,
        t.a = c
    }
    ).call(this, n(5).setImmediate)
}
, function(e, t) {
    "document"in window.self && ((!("classList"in document.createElement("_")) || document.createElementNS && !("classList"in document.createElementNS("http://www.w3.org/2000/svg", "g"))) && function(e) {
        "use strict";
        if ("Element"in e) {
            var t = "classList"
              , n = e.Element.prototype
              , r = Object
              , o = String.prototype.trim || function() {
                return this.replace(/^\s+|\s+$/g, "")
            }
              , i = Array.prototype.indexOf || function(e) {
                for (var t = 0, n = this.length; t < n; t++)
                    if (t in this && this[t] === e)
                        return t;
                return -1
            }
              , a = function(e, t) {
                this.name = e,
                this.code = DOMException[e],
                this.message = t
            }
              , c = function(e, t) {
                if ("" === t)
                    throw new a("SYNTAX_ERR","An invalid or illegal string was specified");
                if (/\s/.test(t))
                    throw new a("INVALID_CHARACTER_ERR","String contains an invalid character");
                return i.call(e, t)
            }
              , s = function(e) {
                for (var t = o.call(e.getAttribute("class") || ""), n = t ? t.split(/\s+/) : [], r = 0, i = n.length; r < i; r++)
                    this.push(n[r]);
                this._updateClassName = function() {
                    e.setAttribute("class", this.toString())
                }
            }
              , u = s.prototype = []
              , l = function() {
                return new s(this)
            };
            if (a.prototype = Error.prototype,
            u.item = function(e) {
                return this[e] || null
            }
            ,
            u.contains = function(e) {
                return -1 !== c(this, e += "")
            }
            ,
            u.add = function() {
                var e, t = arguments, n = 0, r = t.length, o = !1;
                do {
                    e = t[n] + "",
                    -1 === c(this, e) && (this.push(e),
                    o = !0)
                } while (++n < r);o && this._updateClassName()
            }
            ,
            u.remove = function() {
                var e, t, n = arguments, r = 0, o = n.length, i = !1;
                do {
                    for (e = n[r] + "",
                    t = c(this, e); -1 !== t; )
                        this.splice(t, 1),
                        i = !0,
                        t = c(this, e)
                } while (++r < o);i && this._updateClassName()
            }
            ,
            u.toggle = function(e, t) {
                e += "";
                var n = this.contains(e)
                  , r = n ? !0 !== t && "remove" : !1 !== t && "add";
                return r && this[r](e),
                !0 === t || !1 === t ? t : !n
            }
            ,
            u.toString = function() {
                return this.join(" ")
            }
            ,
            r.defineProperty) {
                var f = {
                    get: l,
                    enumerable: !0,
                    configurable: !0
                };
                try {
                    r.defineProperty(n, t, f)
                } catch (h) {
                    void 0 !== h.number && -2146823252 !== h.number || (f.enumerable = !1,
                    r.defineProperty(n, t, f))
                }
            } else
                r.prototype.__defineGetter__ && n.__defineGetter__(t, l)
        }
    }(window.self),
    function() {
        "use strict";
        var e = document.createElement("_");
        if (e.classList.add("c1", "c2"),
        !e.classList.contains("c2")) {
            var t = function(e) {
                var t = DOMTokenList.prototype[e];
                DOMTokenList.prototype[e] = function(e) {
                    var n, r = arguments.length;
                    for (n = 0; n < r; n++)
                        e = arguments[n],
                        t.call(this, e)
                }
            };
            t("add"),
            t("remove")
        }
        if (e.classList.toggle("c3", !1),
        e.classList.contains("c3")) {
            var n = DOMTokenList.prototype.toggle;
            DOMTokenList.prototype.toggle = function(e, t) {
                return 1 in arguments && !this.contains(e) === !t ? t : n.call(this, e)
            }
        }
        e = null
    }())
}
, function(e, t, n) {
    "use strict";
    (function(e) {
        var t = n(2)
          , r = n(1)
          , o = function() {
            if ("undefined" !== typeof self)
                return self;
            if ("undefined" !== typeof window)
                return window;
            if ("undefined" !== typeof e)
                return e;
            throw new Error("unable to locate global object")
        }();
        "Promise"in o ? o.Promise.prototype.finally || (o.Promise.prototype.finally = r.a) : o.Promise = t.a
    }
    ).call(this, n(0))
}
, function(e, t, n) {
    (function(e) {
        var r = "undefined" !== typeof e && e || "undefined" !== typeof self && self || window
          , o = Function.prototype.apply;
        function i(e, t) {
            this._id = e,
            this._clearFn = t
        }
        t.setTimeout = function() {
            return new i(o.call(setTimeout, r, arguments),clearTimeout)
        }
        ,
        t.setInterval = function() {
            return new i(o.call(setInterval, r, arguments),clearInterval)
        }
        ,
        t.clearTimeout = t.clearInterval = function(e) {
            e && e.close()
        }
        ,
        i.prototype.unref = i.prototype.ref = function() {}
        ,
        i.prototype.close = function() {
            this._clearFn.call(r, this._id)
        }
        ,
        t.enroll = function(e, t) {
            clearTimeout(e._idleTimeoutId),
            e._idleTimeout = t
        }
        ,
        t.unenroll = function(e) {
            clearTimeout(e._idleTimeoutId),
            e._idleTimeout = -1
        }
        ,
        t._unrefActive = t.active = function(e) {
            clearTimeout(e._idleTimeoutId);
            var t = e._idleTimeout;
            t >= 0 && (e._idleTimeoutId = setTimeout((function() {
                e._onTimeout && e._onTimeout()
            }
            ), t))
        }
        ,
        n(6),
        t.setImmediate = "undefined" !== typeof self && self.setImmediate || "undefined" !== typeof e && e.setImmediate || this && this.setImmediate,
        t.clearImmediate = "undefined" !== typeof self && self.clearImmediate || "undefined" !== typeof e && e.clearImmediate || this && this.clearImmediate
    }
    ).call(this, n(0))
}
, function(e, t, n) {
    (function(e, t) {
        !function(e, n) {
            "use strict";
            if (!e.setImmediate) {
                var r, o = 1, i = {}, a = !1, c = e.document, s = Object.getPrototypeOf && Object.getPrototypeOf(e);
                s = s && s.setTimeout ? s : e,
                "[object process]" === {}.toString.call(e.process) ? r = function(e) {
                    t.nextTick((function() {
                        l(e)
                    }
                    ))
                }
                : function() {
                    if (e.postMessage && !e.importScripts) {
                        var t = !0
                          , n = e.onmessage;
                        return e.onmessage = function() {
                            t = !1
                        }
                        ,
                        e.postMessage("", "*"),
                        e.onmessage = n,
                        t
                    }
                }() ? function() {
                    var t = "setImmediate$" + Math.random() + "$"
                      , n = function(n) {
                        n.source === e && "string" === typeof n.data && 0 === n.data.indexOf(t) && l(+n.data.slice(t.length))
                    };
                    e.addEventListener ? e.addEventListener("message", n, !1) : e.attachEvent("onmessage", n),
                    r = function(n) {
                        e.postMessage(t + n, "*")
                    }
                }() : e.MessageChannel ? function() {
                    var e = new MessageChannel;
                    e.port1.onmessage = function(e) {
                        l(e.data)
                    }
                    ,
                    r = function(t) {
                        e.port2.postMessage(t)
                    }
                }() : c && "onreadystatechange"in c.createElement("script") ? function() {
                    var e = c.documentElement;
                    r = function(t) {
                        var n = c.createElement("script");
                        n.onreadystatechange = function() {
                            l(t),
                            n.onreadystatechange = null,
                            e.removeChild(n),
                            n = null
                        }
                        ,
                        e.appendChild(n)
                    }
                }() : r = function(e) {
                    setTimeout(l, 0, e)
                }
                ,
                s.setImmediate = function(e) {
                    "function" !== typeof e && (e = new Function("" + e));
                    for (var t = new Array(arguments.length - 1), n = 0; n < t.length; n++)
                        t[n] = arguments[n + 1];
                    var a = {
                        callback: e,
                        args: t
                    };
                    return i[o] = a,
                    r(o),
                    o++
                }
                ,
                s.clearImmediate = u
            }
            function u(e) {
                delete i[e]
            }
            function l(e) {
                if (a)
                    setTimeout(l, 0, e);
                else {
                    var t = i[e];
                    if (t) {
                        a = !0;
                        try {
                            !function(e) {
                                var t = e.callback
                                  , n = e.args;
                                switch (n.length) {
                                case 0:
                                    t();
                                    break;
                                case 1:
                                    t(n[0]);
                                    break;
                                case 2:
                                    t(n[0], n[1]);
                                    break;
                                case 3:
                                    t(n[0], n[1], n[2]);
                                    break;
                                default:
                                    t.apply(void 0, n)
                                }
                            }(t)
                        } finally {
                            u(e),
                            a = !1
                        }
                    }
                }
            }
        }("undefined" === typeof self ? "undefined" === typeof e ? this : e : self)
    }
    ).call(this, n(0), n(7))
}
, function(e, t) {
    var n, r, o = e.exports = {};
    function i() {
        throw new Error("setTimeout has not been defined")
    }
    function a() {
        throw new Error("clearTimeout has not been defined")
    }
    function c(e) {
        if (n === setTimeout)
            return setTimeout(e, 0);
        if ((n === i || !n) && setTimeout)
            return n = setTimeout,
            setTimeout(e, 0);
        try {
            return n(e, 0)
        } catch (t) {
            try {
                return n.call(null, e, 0)
            } catch (t) {
                return n.call(this, e, 0)
            }
        }
    }
    !function() {
        try {
            n = "function" === typeof setTimeout ? setTimeout : i
        } catch (e) {
            n = i
        }
        try {
            r = "function" === typeof clearTimeout ? clearTimeout : a
        } catch (e) {
            r = a
        }
    }();
    var s, u = [], l = !1, f = -1;
    function h() {
        l && s && (l = !1,
        s.length ? u = s.concat(u) : f = -1,
        u.length && d())
    }
    function d() {
        if (!l) {
            var e = c(h);
            l = !0;
            for (var t = u.length; t; ) {
                for (s = u,
                u = []; ++f < t; )
                    s && s[f].run();
                f = -1,
                t = u.length
            }
            s = null,
            l = !1,
            function(e) {
                if (r === clearTimeout)
                    return clearTimeout(e);
                if ((r === a || !r) && clearTimeout)
                    return r = clearTimeout,
                    clearTimeout(e);
                try {
                    r(e)
                } catch (t) {
                    try {
                        return r.call(null, e)
                    } catch (t) {
                        return r.call(this, e)
                    }
                }
            }(e)
        }
    }
    function p(e, t) {
        this.fun = e,
        this.array = t
    }
    function y() {}
    o.nextTick = function(e) {
        var t = new Array(arguments.length - 1);
        if (arguments.length > 1)
            for (var n = 1; n < arguments.length; n++)
                t[n - 1] = arguments[n];
        u.push(new p(e,t)),
        1 !== u.length || l || c(d)
    }
    ,
    p.prototype.run = function() {
        this.fun.apply(null, this.array)
    }
    ,
    o.title = "browser",
    o.browser = !0,
    o.env = {},
    o.argv = [],
    o.version = "",
    o.versions = {},
    o.on = y,
    o.addListener = y,
    o.once = y,
    o.off = y,
    o.removeListener = y,
    o.removeAllListeners = y,
    o.emit = y,
    o.prependListener = y,
    o.prependOnceListener = y,
    o.listeners = function(e) {
        return []
    }
    ,
    o.binding = function(e) {
        throw new Error("process.binding is not supported")
    }
    ,
    o.cwd = function() {
        return "/"
    }
    ,
    o.chdir = function(e) {
        throw new Error("process.chdir is not supported")
    }
    ,
    o.umask = function() {
        return 0
    }
}
, function(e, t, n) {
    (function(e) {
        !function(e) {
            var t = function() {
                try {
                    return !!Symbol.iterator
                } catch (e) {
                    return !1
                }
            }()
              , n = function(e) {
                var n = {
                    next: function() {
                        var t = e.shift();
                        return {
                            done: void 0 === t,
                            value: t
                        }
                    }
                };
                return t && (n[Symbol.iterator] = function() {
                    return n
                }
                ),
                n
            }
              , r = function(e) {
                return encodeURIComponent(e).replace(/%20/g, "+")
            }
              , o = function(e) {
                return decodeURIComponent(String(e).replace(/\+/g, " "))
            };
            (function() {
                try {
                    var t = e.URLSearchParams;
                    return "a=1" === new t("?a=1").toString() && "function" === typeof t.prototype.set && "function" === typeof t.prototype.entries
                } catch (n) {
                    return !1
                }
            }
            )() || function() {
                var o = function(e) {
                    Object.defineProperty(this, "_entries", {
                        writable: !0,
                        value: {}
                    });
                    var t = typeof e;
                    if ("undefined" === t)
                        ;
                    else if ("string" === t)
                        "" !== e && this._fromString(e);
                    else if (e instanceof o) {
                        var n = this;
                        e.forEach((function(e, t) {
                            n.append(t, e)
                        }
                        ))
                    } else {
                        if (null === e || "object" !== t)
                            throw new TypeError("Unsupported input's type for URLSearchParams");
                        if ("[object Array]" === Object.prototype.toString.call(e))
                            for (var r = 0; r < e.length; r++) {
                                var i = e[r];
                                if ("[object Array]" !== Object.prototype.toString.call(i) && 2 === i.length)
                                    throw new TypeError("Expected [string, any] as entry at index " + r + " of URLSearchParams's input");
                                this.append(i[0], i[1])
                            }
                        else
                            for (var a in e)
                                e.hasOwnProperty(a) && this.append(a, e[a])
                    }
                }
                  , i = o.prototype;
                i.append = function(e, t) {
                    e in this._entries ? this._entries[e].push(String(t)) : this._entries[e] = [String(t)]
                }
                ,
                i.delete = function(e) {
                    delete this._entries[e]
                }
                ,
                i.get = function(e) {
                    return e in this._entries ? this._entries[e][0] : null
                }
                ,
                i.getAll = function(e) {
                    return e in this._entries ? this._entries[e].slice(0) : []
                }
                ,
                i.has = function(e) {
                    return e in this._entries
                }
                ,
                i.set = function(e, t) {
                    this._entries[e] = [String(t)]
                }
                ,
                i.forEach = function(e, t) {
                    var n;
                    for (var r in this._entries)
                        if (this._entries.hasOwnProperty(r)) {
                            n = this._entries[r];
                            for (var o = 0; o < n.length; o++)
                                e.call(t, n[o], r, this)
                        }
                }
                ,
                i.keys = function() {
                    var e = [];
                    return this.forEach((function(t, n) {
                        e.push(n)
                    }
                    )),
                    n(e)
                }
                ,
                i.values = function() {
                    var e = [];
                    return this.forEach((function(t) {
                        e.push(t)
                    }
                    )),
                    n(e)
                }
                ,
                i.entries = function() {
                    var e = [];
                    return this.forEach((function(t, n) {
                        e.push([n, t])
                    }
                    )),
                    n(e)
                }
                ,
                t && (i[Symbol.iterator] = i.entries),
                i.toString = function() {
                    var e = [];
                    return this.forEach((function(t, n) {
                        e.push(r(n) + "=" + r(t))
                    }
                    )),
                    e.join("&")
                }
                ,
                e.URLSearchParams = o
            }();
            var i = e.URLSearchParams.prototype;
            "function" !== typeof i.sort && (i.sort = function() {
                var e = this
                  , t = [];
                this.forEach((function(n, r) {
                    t.push([r, n]),
                    e._entries || e.delete(r)
                }
                )),
                t.sort((function(e, t) {
                    return e[0] < t[0] ? -1 : e[0] > t[0] ? 1 : 0
                }
                )),
                e._entries && (e._entries = {});
                for (var n = 0; n < t.length; n++)
                    this.append(t[n][0], t[n][1])
            }
            ),
            "function" !== typeof i._fromString && Object.defineProperty(i, "_fromString", {
                enumerable: !1,
                configurable: !1,
                writable: !1,
                value: function(e) {
                    if (this._entries)
                        this._entries = {};
                    else {
                        var t = [];
                        this.forEach((function(e, n) {
                            t.push(n)
                        }
                        ));
                        for (var n = 0; n < t.length; n++)
                            this.delete(t[n])
                    }
                    var r, i = (e = e.replace(/^\?/, "")).split("&");
                    for (n = 0; n < i.length; n++)
                        r = i[n].split("="),
                        this.append(o(r[0]), r.length > 1 ? o(r[1]) : "")
                }
            })
        }("undefined" !== typeof e ? e : "undefined" !== typeof window ? window : "undefined" !== typeof self ? self : this),
        function(e) {
            if (function() {
                try {
                    var t = new e.URL("b","http://a");
                    return t.pathname = "c d",
                    "http://a/c%20d" === t.href && t.searchParams
                } catch (n) {
                    return !1
                }
            }() || function() {
                var t = e.URL
                  , n = function(t, n) {
                    "string" !== typeof t && (t = String(t)),
                    n && "string" !== typeof n && (n = String(n));
                    var r, o = document;
                    if (n && (void 0 === e.location || n !== e.location.href)) {
                        n = n.toLowerCase(),
                        (r = (o = document.implementation.createHTMLDocument("")).createElement("base")).href = n,
                        o.head.appendChild(r);
                        try {
                            if (0 !== r.href.indexOf(n))
                                throw new Error(r.href)
                        } catch (h) {
                            throw new Error("URL unable to set base " + n + " due to " + h)
                        }
                    }
                    var i = o.createElement("a");
                    i.href = t,
                    r && (o.body.appendChild(i),
                    i.href = i.href);
                    var a = o.createElement("input");
                    if (a.type = "url",
                    a.value = t,
                    ":" === i.protocol || !/:/.test(i.href) || !a.checkValidity() && !n)
                        throw new TypeError("Invalid URL");
                    Object.defineProperty(this, "_anchorElement", {
                        value: i
                    });
                    var c = new e.URLSearchParams(this.search)
                      , s = !0
                      , u = !0
                      , l = this;
                    ["append", "delete", "set"].forEach((function(e) {
                        var t = c[e];
                        c[e] = function() {
                            t.apply(c, arguments),
                            s && (u = !1,
                            l.search = c.toString(),
                            u = !0)
                        }
                    }
                    )),
                    Object.defineProperty(this, "searchParams", {
                        value: c,
                        enumerable: !0
                    });
                    var f = void 0;
                    Object.defineProperty(this, "_updateSearchParams", {
                        enumerable: !1,
                        configurable: !1,
                        writable: !1,
                        value: function() {
                            this.search !== f && (f = this.search,
                            u && (s = !1,
                            this.searchParams._fromString(this.search),
                            s = !0))
                        }
                    })
                }
                  , r = n.prototype;
                ["hash", "host", "hostname", "port", "protocol"].forEach((function(e) {
                    !function(e) {
                        Object.defineProperty(r, e, {
                            get: function() {
                                return this._anchorElement[e]
                            },
                            set: function(t) {
                                this._anchorElement[e] = t
                            },
                            enumerable: !0
                        })
                    }(e)
                }
                )),
                Object.defineProperty(r, "search", {
                    get: function() {
                        return this._anchorElement.search
                    },
                    set: function(e) {
                        this._anchorElement.search = e,
                        this._updateSearchParams()
                    },
                    enumerable: !0
                }),
                Object.defineProperties(r, {
                    toString: {
                        get: function() {
                            var e = this;
                            return function() {
                                return e.href
                            }
                        }
                    },
                    href: {
                        get: function() {
                            return this._anchorElement.href.replace(/\?$/, "")
                        },
                        set: function(e) {
                            this._anchorElement.href = e,
                            this._updateSearchParams()
                        },
                        enumerable: !0
                    },
                    pathname: {
                        get: function() {
                            return this._anchorElement.pathname.replace(/(^\/?)/, "/")
                        },
                        set: function(e) {
                            this._anchorElement.pathname = e
                        },
                        enumerable: !0
                    },
                    origin: {
                        get: function() {
                            var e = {
                                "http:": 80,
                                "https:": 443,
                                "ftp:": 21
                            }[this._anchorElement.protocol]
                              , t = this._anchorElement.port != e && "" !== this._anchorElement.port;
                            return this._anchorElement.protocol + "//" + this._anchorElement.hostname + (t ? ":" + this._anchorElement.port : "")
                        },
                        enumerable: !0
                    },
                    password: {
                        get: function() {
                            return ""
                        },
                        set: function(e) {},
                        enumerable: !0
                    },
                    username: {
                        get: function() {
                            return ""
                        },
                        set: function(e) {},
                        enumerable: !0
                    }
                }),
                n.createObjectURL = function(e) {
                    return t.createObjectURL.apply(t, arguments)
                }
                ,
                n.revokeObjectURL = function(e) {
                    return t.revokeObjectURL.apply(t, arguments)
                }
                ,
                e.URL = n
            }(),
            void 0 !== e.location && !("origin"in e.location)) {
                var t = function() {
                    return e.location.protocol + "//" + e.location.hostname + (e.location.port ? ":" + e.location.port : "")
                };
                try {
                    Object.defineProperty(e.location, "origin", {
                        get: t,
                        enumerable: !0
                    })
                } catch (n) {
                    setInterval((function() {
                        e.location.origin = t()
                    }
                    ), 100)
                }
            }
        }("undefined" !== typeof e ? e : "undefined" !== typeof window ? window : "undefined" !== typeof self ? self : this)
    }
    ).call(this, n(0))
}
, function(e, t) {
    [Element.prototype, CharacterData.prototype, DocumentType.prototype].forEach((function(e) {
        e.hasOwnProperty("remove") || Object.defineProperty(e, "remove", {
            configurable: !0,
            enumerable: !0,
            writable: !0,
            value: function() {
                null !== this.parentNode && this.parentNode.removeChild(this)
            }
        })
    }
    ))
}
, function(e, t) {
    function n(e) {
        return (n = "function" === typeof Symbol && "symbol" === typeof Symbol.iterator ? function(e) {
            return typeof e
        }
        : function(e) {
            return e && "function" === typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        }
        )(e)
    }
    function r() {
        var e, t = this.parentNode, r = arguments.length;
        if (t)
            for (r || t.removeChild(this); r--; )
                "object" !== n(e = arguments[r]) ? e = this.ownerDocument.createTextNode(e) : e.parentNode && e.parentNode.removeChild(e),
                r ? t.insertBefore(e, this.nextSibling) : t.replaceChild(e, this)
    }
    Element.prototype.replaceWith || (Element.prototype.replaceWith = r),
    CharacterData.prototype.replaceWith || (CharacterData.prototype.replaceWith = r),
    DocumentType.prototype.replaceWith || (DocumentType.prototype.replaceWith = r)
}
, function(e, t) {
    [Element.prototype, Document.prototype, DocumentFragment.prototype].forEach((function(e) {
        e.hasOwnProperty("append") || Object.defineProperty(e, "append", {
            configurable: !0,
            enumerable: !0,
            writable: !0,
            value: function() {
                var e = Array.prototype.slice.call(arguments)
                  , t = document.createDocumentFragment();
                e.forEach((function(e) {
                    var n = e instanceof Node;
                    t.appendChild(n ? e : document.createTextNode(String(e)))
                }
                )),
                this.appendChild(t)
            }
        })
    }
    ))
}
, function(e, t) {
    [Element.prototype, Document.prototype, DocumentFragment.prototype].forEach((function(e) {
        e.hasOwnProperty("prepend") || Object.defineProperty(e, "prepend", {
            configurable: !0,
            enumerable: !0,
            writable: !0,
            value: function() {
                var e = Array.prototype.slice.call(arguments)
                  , t = document.createDocumentFragment();
                e.forEach((function(e) {
                    var n = e instanceof Node;
                    t.appendChild(n ? e : document.createTextNode(String(e)))
                }
                )),
                this.insertBefore(t, this.firstChild)
            }
        })
    }
    ))
}
, function(e, t, n) {
    "use strict";
    n.r(t);
    n(3),
    n(4),
    n(8);
    var r = "undefined" !== typeof globalThis && globalThis || "undefined" !== typeof self && self || "undefined" !== typeof r && r
      , o = "URLSearchParams"in r
      , i = "Symbol"in r && "iterator"in Symbol
      , a = "FileReader"in r && "Blob"in r && function() {
        try {
            return new Blob,
            !0
        } catch (e) {
            return !1
        }
    }()
      , c = "FormData"in r
      , s = "ArrayBuffer"in r;
    if (s)
        var u = ["[object Int8Array]", "[object Uint8Array]", "[object Uint8ClampedArray]", "[object Int16Array]", "[object Uint16Array]", "[object Int32Array]", "[object Uint32Array]", "[object Float32Array]", "[object Float64Array]"]
          , l = ArrayBuffer.isView || function(e) {
            return e && u.indexOf(Object.prototype.toString.call(e)) > -1
        }
        ;
    function f(e) {
        if ("string" !== typeof e && (e = String(e)),
        /[^a-z0-9\-#$%&'*+.^_`|~!]/i.test(e) || "" === e)
            throw new TypeError("Invalid character in header field name");
        return e.toLowerCase()
    }
    function h(e) {
        return "string" !== typeof e && (e = String(e)),
        e
    }
    function d(e) {
        var t = {
            next: function() {
                var t = e.shift();
                return {
                    done: void 0 === t,
                    value: t
                }
            }
        };
        return i && (t[Symbol.iterator] = function() {
            return t
        }
        ),
        t
    }
    function p(e) {
        this.map = {},
        e instanceof p ? e.forEach((function(e, t) {
            this.append(t, e)
        }
        ), this) : Array.isArray(e) ? e.forEach((function(e) {
            this.append(e[0], e[1])
        }
        ), this) : e && Object.getOwnPropertyNames(e).forEach((function(t) {
            this.append(t, e[t])
        }
        ), this)
    }
    function y(e) {
        if (e.bodyUsed)
            return Promise.reject(new TypeError("Already read"));
        e.bodyUsed = !0
    }
    function m(e) {
        return new Promise((function(t, n) {
            e.onload = function() {
                t(e.result)
            }
            ,
            e.onerror = function() {
                n(e.error)
            }
        }
        ))
    }
    function b(e) {
        var t = new FileReader
          , n = m(t);
        return t.readAsArrayBuffer(e),
        n
    }
    function v(e) {
        if (e.slice)
            return e.slice(0);
        var t = new Uint8Array(e.byteLength);
        return t.set(new Uint8Array(e)),
        t.buffer
    }
    function g() {
        return this.bodyUsed = !1,
        this._initBody = function(e) {
            var t;
            this.bodyUsed = this.bodyUsed,
            this._bodyInit = e,
            e ? "string" === typeof e ? this._bodyText = e : a && Blob.prototype.isPrototypeOf(e) ? this._bodyBlob = e : c && FormData.prototype.isPrototypeOf(e) ? this._bodyFormData = e : o && URLSearchParams.prototype.isPrototypeOf(e) ? this._bodyText = e.toString() : s && a && ((t = e) && DataView.prototype.isPrototypeOf(t)) ? (this._bodyArrayBuffer = v(e.buffer),
            this._bodyInit = new Blob([this._bodyArrayBuffer])) : s && (ArrayBuffer.prototype.isPrototypeOf(e) || l(e)) ? this._bodyArrayBuffer = v(e) : this._bodyText = e = Object.prototype.toString.call(e) : this._bodyText = "",
            this.headers.get("content-type") || ("string" === typeof e ? this.headers.set("content-type", "text/plain;charset=UTF-8") : this._bodyBlob && this._bodyBlob.type ? this.headers.set("content-type", this._bodyBlob.type) : o && URLSearchParams.prototype.isPrototypeOf(e) && this.headers.set("content-type", "application/x-www-form-urlencoded;charset=UTF-8"))
        }
        ,
        a && (this.blob = function() {
            var e = y(this);
            if (e)
                return e;
            if (this._bodyBlob)
                return Promise.resolve(this._bodyBlob);
            if (this._bodyArrayBuffer)
                return Promise.resolve(new Blob([this._bodyArrayBuffer]));
            if (this._bodyFormData)
                throw new Error("could not read FormData body as blob");
            return Promise.resolve(new Blob([this._bodyText]))
        }
        ,
        this.arrayBuffer = function() {
            if (this._bodyArrayBuffer) {
                var e = y(this);
                return e || (ArrayBuffer.isView(this._bodyArrayBuffer) ? Promise.resolve(this._bodyArrayBuffer.buffer.slice(this._bodyArrayBuffer.byteOffset, this._bodyArrayBuffer.byteOffset + this._bodyArrayBuffer.byteLength)) : Promise.resolve(this._bodyArrayBuffer))
            }
            return this.blob().then(b)
        }
        ),
        this.text = function() {
            var e = y(this);
            if (e)
                return e;
            if (this._bodyBlob)
                return function(e) {
                    var t = new FileReader
                      , n = m(t);
                    return t.readAsText(e),
                    n
                }(this._bodyBlob);
            if (this._bodyArrayBuffer)
                return Promise.resolve(function(e) {
                    for (var t = new Uint8Array(e), n = new Array(t.length), r = 0; r < t.length; r++)
                        n[r] = String.fromCharCode(t[r]);
                    return n.join("")
                }(this._bodyArrayBuffer));
            if (this._bodyFormData)
                throw new Error("could not read FormData body as text");
            return Promise.resolve(this._bodyText)
        }
        ,
        c && (this.formData = function() {
            return this.text().then(k)
        }
        ),
        this.json = function() {
            return this.text().then(JSON.parse)
        }
        ,
        this
    }
    p.prototype.append = function(e, t) {
        e = f(e),
        t = h(t);
        var n = this.map[e];
        this.map[e] = n ? n + ", " + t : t
    }
    ,
    p.prototype.delete = function(e) {
        delete this.map[f(e)]
    }
    ,
    p.prototype.get = function(e) {
        return e = f(e),
        this.has(e) ? this.map[e] : null
    }
    ,
    p.prototype.has = function(e) {
        return this.map.hasOwnProperty(f(e))
    }
    ,
    p.prototype.set = function(e, t) {
        this.map[f(e)] = h(t)
    }
    ,
    p.prototype.forEach = function(e, t) {
        for (var n in this.map)
            this.map.hasOwnProperty(n) && e.call(t, this.map[n], n, this)
    }
    ,
    p.prototype.keys = function() {
        var e = [];
        return this.forEach((function(t, n) {
            e.push(n)
        }
        )),
        d(e)
    }
    ,
    p.prototype.values = function() {
        var e = [];
        return this.forEach((function(t) {
            e.push(t)
        }
        )),
        d(e)
    }
    ,
    p.prototype.entries = function() {
        var e = [];
        return this.forEach((function(t, n) {
            e.push([n, t])
        }
        )),
        d(e)
    }
    ,
    i && (p.prototype[Symbol.iterator] = p.prototype.entries);
    var E = ["DELETE", "GET", "HEAD", "OPTIONS", "POST", "PUT"];
    function O(e, t) {
        if (!(this instanceof O))
            throw new TypeError('Please use the "new" operator, this DOM object constructor cannot be called as a function.');
        var n = (t = t || {}).body;
        if (e instanceof O) {
            if (e.bodyUsed)
                throw new TypeError("Already read");
            this.url = e.url,
            this.credentials = e.credentials,
            t.headers || (this.headers = new p(e.headers)),
            this.method = e.method,
            this.mode = e.mode,
            this.signal = e.signal,
            n || null == e._bodyInit || (n = e._bodyInit,
            e.bodyUsed = !0)
        } else
            this.url = String(e);
        if (this.credentials = t.credentials || this.credentials || "same-origin",
        !t.headers && this.headers || (this.headers = new p(t.headers)),
        this.method = function(e) {
            var t = e.toUpperCase();
            return E.indexOf(t) > -1 ? t : e
        }(t.method || this.method || "GET"),
        this.mode = t.mode || this.mode || null,
        this.signal = t.signal || this.signal,
        this.referrer = null,
        ("GET" === this.method || "HEAD" === this.method) && n)
            throw new TypeError("Body not allowed for GET or HEAD requests");
        if (this._initBody(n),
        ("GET" === this.method || "HEAD" === this.method) && ("no-store" === t.cache || "no-cache" === t.cache)) {
            var r = /([?&])_=[^&]*/;
            if (r.test(this.url))
                this.url = this.url.replace(r, "$1_=" + (new Date).getTime());
            else {
                this.url += (/\?/.test(this.url) ? "&" : "?") + "_=" + (new Date).getTime()
            }
        }
    }
    function k(e) {
        var t = new FormData;
        return e.trim().split("&").forEach((function(e) {
            if (e) {
                var n = e.split("=")
                  , r = n.shift().replace(/\+/g, " ")
                  , o = n.join("=").replace(/\+/g, " ");
                t.append(decodeURIComponent(r), decodeURIComponent(o))
            }
        }
        )),
        t
    }
    function w(e) {
        var t = new p;
        return e.replace(/\r?\n[\t ]+/g, " ").split(/\r?\n/).forEach((function(e) {
            var n = e.split(":")
              , r = n.shift().trim();
            if (r) {
                var o = n.join(":").trim();
                t.append(r, o)
            }
        }
        )),
        t
    }
    function C(e, t) {
        if (!(this instanceof C))
            throw new TypeError('Please use the "new" operator, this DOM object constructor cannot be called as a function.');
        t || (t = {}),
        this.type = "default",
        this.status = void 0 === t.status ? 200 : t.status,
        this.ok = this.status >= 200 && this.status < 300,
        this.statusText = "statusText"in t ? t.statusText : "",
        this.headers = new p(t.headers),
        this.url = t.url || "",
        this._initBody(e)
    }
    O.prototype.clone = function() {
        return new O(this,{
            body: this._bodyInit
        })
    }
    ,
    g.call(O.prototype),
    g.call(C.prototype),
    C.prototype.clone = function() {
        return new C(this._bodyInit,{
            status: this.status,
            statusText: this.statusText,
            headers: new p(this.headers),
            url: this.url
        })
    }
    ,
    C.error = function() {
        var e = new C(null,{
            status: 0,
            statusText: ""
        });
        return e.type = "error",
        e
    }
    ;
    var P = [301, 302, 303, 307, 308];
    C.redirect = function(e, t) {
        if (-1 === P.indexOf(t))
            throw new RangeError("Invalid status code");
        return new C(null,{
            status: t,
            headers: {
                location: e
            }
        })
    }
    ;
    var T = r.DOMException;
    try {
        new T
    } catch (Ut) {
        (T = function(e, t) {
            this.message = e,
            this.name = t;
            var n = Error(e);
            this.stack = n.stack
        }
        ).prototype = Object.create(Error.prototype),
        T.prototype.constructor = T
    }
    function _(e, t) {
        return new Promise((function(n, o) {
            var i = new O(e,t);
            if (i.signal && i.signal.aborted)
                return o(new T("Aborted","AbortError"));
            var c = new XMLHttpRequest;
            function u() {
                c.abort()
            }
            c.onload = function() {
                var e = {
                    status: c.status,
                    statusText: c.statusText,
                    headers: w(c.getAllResponseHeaders() || "")
                };
                e.url = "responseURL"in c ? c.responseURL : e.headers.get("X-Request-URL");
                var t = "response"in c ? c.response : c.responseText;
                setTimeout((function() {
                    n(new C(t,e))
                }
                ), 0)
            }
            ,
            c.onerror = function() {
                setTimeout((function() {
                    o(new TypeError("Network request failed"))
                }
                ), 0)
            }
            ,
            c.ontimeout = function() {
                setTimeout((function() {
                    o(new TypeError("Network request failed"))
                }
                ), 0)
            }
            ,
            c.onabort = function() {
                setTimeout((function() {
                    o(new T("Aborted","AbortError"))
                }
                ), 0)
            }
            ,
            c.open(i.method, function(e) {
                try {
                    return "" === e && r.location.href ? r.location.href : e
                } catch (t) {
                    return e
                }
            }(i.url), !0),
            "include" === i.credentials ? c.withCredentials = !0 : "omit" === i.credentials && (c.withCredentials = !1),
            "responseType"in c && (a ? c.responseType = "blob" : s && i.headers.get("Content-Type") && -1 !== i.headers.get("Content-Type").indexOf("application/octet-stream") && (c.responseType = "arraybuffer")),
            !t || "object" !== typeof t.headers || t.headers instanceof p ? i.headers.forEach((function(e, t) {
                c.setRequestHeader(t, e)
            }
            )) : Object.getOwnPropertyNames(t.headers).forEach((function(e) {
                c.setRequestHeader(e, h(t.headers[e]))
            }
            )),
            i.signal && (i.signal.addEventListener("abort", u),
            c.onreadystatechange = function() {
                4 === c.readyState && i.signal.removeEventListener("abort", u)
            }
            ),
            c.send("undefined" === typeof i._bodyInit ? null : i._bodyInit)
        }
        ))
    }
    _.polyfill = !0,
    r.fetch || (r.fetch = _,
    r.Headers = p,
    r.Request = O,
    r.Response = C);
    n(9),
    n(10),
    n(11),
    n(12);
    var S = {
        id: "ps_checkoutPayPalSdkScript",
        namespace: "ps_checkoutPayPalSdkInstance",
        src: window.ps_checkoutPayPalSdkUrl,
        card3dsEnabled: window.ps_checkout3dsEnabled,
        cspNonce: window.ps_checkoutCspNonce,
        orderId: window.ps_checkoutPayPalOrderId,
        clientToken: window.ps_checkoutPayPalClientToken
    };
    function I(e, t) {
        var n = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(e);
            t && (r = r.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }
            ))),
            n.push.apply(n, r)
        }
        return n
    }
    function j(e) {
        for (var t = 1; t < arguments.length; t++) {
            var n = null != arguments[t] ? arguments[t] : {};
            t % 2 ? I(Object(n), !0).forEach((function(t) {
                N(e, t, n[t])
            }
            )) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : I(Object(n)).forEach((function(t) {
                Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
            }
            ))
        }
        return e
    }
    function N(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n,
        e
    }
    var x = {
        createUrl: window.ps_checkoutCreateUrl,
        checkCartUrl: window.ps_checkoutCheckUrl,
        validateOrderUrl: window.ps_checkoutValidateUrl,
        confirmationUrl: window.ps_checkoutConfirmUrl,
        cancelUrl: window.ps_checkoutCancelUrl,
        getTokenUrl: window.ps_checkoutGetTokenURL,
        checkoutCheckoutUrl: window.ps_checkoutCheckoutUrl,
        expressCheckoutUrl: window.ps_checkoutExpressCheckoutUrl,
        hostedFieldsEnabled: window.ps_checkoutHostedFieldsEnabled,
        translations: j(j({}, Object.keys(window.ps_checkoutPayWithTranslations || {}).reduce((function(e, t) {
            return e["funding-source.name.".concat(t)] = window.ps_checkoutPayWithTranslations[t],
            e
        }
        ), {})), window.ps_checkoutCheckoutTranslations),
        loaderImage: window.ps_checkoutLoaderImage,
        customMark: {
            card: window.ps_checkoutCardFundingSourceImg
        },
        expressCheckoutSelected: window.ps_checkoutExpressCheckoutSelected,
        expressCheckoutProductEnabled: window.ps_checkoutExpressCheckoutProductEnabled,
        expressCheckoutCartEnabled: window.ps_checkoutExpressCheckoutCartEnabled,
        expressCheckoutOrderEnabled: window.ps_checkoutExpressCheckoutOrderEnabled,
        expressCheckoutHostedFieldsEnabled: window.ps_checkoutHostedFieldsEnabled,
        fundingSourcesSorted: window.ps_checkoutFundingSourcesSorted,
        orderId: window.ps_checkoutPayPalOrderId
    };
    function A(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var L = function() {
        function e(t, n, r) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.config = t,
            this.token = n,
            this.onload = r
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                var e = this
                  , t = document.createElement("script");
                t.setAttribute("async", ""),
                t.setAttribute("id", this.config.id),
                t.setAttribute("src", this.config.src),
                t.setAttribute("data-namespace", this.config.namespace),
                this.config.card3dsEnabled && t.setAttribute("data-enable-3ds", ""),
                this.config.cspNonce && t.setAttribute("data-csp-nonce", this.config.cspNonce),
                this.config.orderId && t.setAttribute("data-order-id", this.config.orderId),
                t.setAttribute("data-client-token", this.token),
                document.head.appendChild(t),
                t.onload = function() {
                    e.sdk = window[e.config.namespace],
                    e.onload(e.sdk)
                }
            }
        }]) && A(t.prototype, n),
        r && A(t, r),
        e
    }()
      , M = "1.6"
      , B = "1.7";
    function R(e, t) {
        var n = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(e);
            t && (r = r.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }
            ))),
            n.push.apply(n, r)
        }
        return n
    }
    function D(e) {
        for (var t = 1; t < arguments.length; t++) {
            var n = null != arguments[t] ? arguments[t] : {};
            t % 2 ? R(Object(n), !0).forEach((function(t) {
                H(e, t, n[t])
            }
            )) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : R(Object(n)).forEach((function(t) {
                Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
            }
            ))
        }
        return e
    }
    function H(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n,
        e
    }
    function F(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var U = function() {
        function e(t, n) {
            var r = this;
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.config = t,
            this.translationService = n,
            this.$ = function(e) {
                return r.translationService.getTranslationString(e)
            }
        }
        var t, n, r;
        return t = e,
        r = [{
            key: "getPrestashopVersion",
            value: function() {
                return window.prestashop ? B : M
            }
        }],
        (n = [{
            key: "isUserLogged",
            value: function() {
                return window.prestashop.customer.is_logged
            }
        }, {
            key: "getProductDetails",
            value: function() {
                return JSON.parse(document.getElementById("product-details").dataset.product)
            }
        }, {
            key: "postCancelOrder",
            value: function(e) {
                return fetch(this.config.cancelUrl, {
                    method: "post",
                    headers: {
                        "content-type": "application/json"
                    },
                    body: JSON.stringify(e)
                }).then((function(e) {
                    if (!1 === e.ok)
                        return e.json().then((function(e) {
                            throw e.body && e.body.error ? e.body.error : {
                                message: "Unknown error"
                            }
                        }
                        ))
                }
                ))
            }
        }, {
            key: "postCheckCartOrder",
            value: function(e, t) {
                return this.config.orderId ? fetch(this.config.checkCartUrl, {
                    method: "post",
                    headers: {
                        "content-type": "application/json"
                    },
                    body: JSON.stringify(e)
                }).then((function(e) {
                    return !1 === e.ok ? e.json().then((function(e) {
                        throw e.body && e.body.error ? e.body.error : {
                            message: "Unknown error"
                        }
                    }
                    )) : e.json()
                }
                )).then((function(e) {
                    return e ? t.resolve() : t.reject()
                }
                )) : Promise.resolve().then((function() {
                    return t.resolve()
                }
                ))
            }
        }, {
            key: "postCreateOrder",
            value: function(e) {
                return fetch(this.config.createUrl, D({
                    method: "post",
                    headers: {
                        "content-type": "application/json"
                    }
                }, e ? {
                    body: JSON.stringify(e)
                } : {})).then((function(e) {
                    return !1 === e.ok ? e.json().then((function(e) {
                        throw e.body && e.body.error ? e.body.error : {
                            message: "Unknown error"
                        }
                    }
                    )) : e.json()
                }
                )).then((function(e) {
                    return e.body.orderID
                }
                ))
            }
        }, {
            key: "postGetToken",
            value: function() {
                return fetch(this.config.getTokenUrl, {
                    method: "post",
                    headers: {
                        "content-type": "application/json"
                    }
                }).then((function(e) {
                    return !1 === e.ok ? e.json().then((function(e) {
                        throw e.body && e.body.error ? e.body.error : {
                            message: "Unknown error"
                        }
                    }
                    )) : e.json()
                }
                )).then((function(e) {
                    return e.body.token
                }
                ))
            }
        }, {
            key: "postValidateOrder",
            value: function(e, t) {
                var n = this;
                return fetch(this.config.validateOrderUrl, {
                    method: "post",
                    headers: {
                        "content-type": "application/json"
                    },
                    body: JSON.stringify(e)
                }).then((function(e) {
                    return !1 === e.ok ? e.json().then((function(e) {
                        throw e.body && e.body.error ? e.body.error : {
                            message: "Unknown error"
                        }
                    }
                    )) : e.json()
                }
                )).then((function(e) {
                    if (e.body && "COMPLETED" === e.body.paypal_status) {
                        var r = e.body
                          , o = r.id_cart
                          , i = r.id_module
                          , a = r.id_order
                          , c = r.secure_key
                          , s = r.paypal_order
                          , u = r.paypal_transaction
                          , l = new URL(n.config.confirmationUrl);
                        l.searchParams.append("id_cart", o),
                        l.searchParams.append("id_module", i),
                        l.searchParams.append("id_order", a),
                        l.searchParams.append("key", c),
                        l.searchParams.append("paypal_order", s),
                        l.searchParams.append("paypal_transaction", u),
                        window.location.href = l.toString()
                    }
                    if (e.error && "INSTRUMENT_DECLINED" === e.error)
                        return t.restart()
                }
                ))
            }
        }, {
            key: "postExpressCheckoutOrder",
            value: function(e, t) {
                var n = this;
                return t.order.get().then((function(t) {
                    var r = t.payer
                      , o = t.purchase_units;
                    return fetch(n.config.expressCheckoutUrl, {
                        method: "post",
                        headers: {
                            "content-type": "application/json"
                        },
                        body: JSON.stringify(D(D({}, e), {}, {
                            order: {
                                payer: r,
                                shipping: o[0].shipping
                            }
                        }))
                    }).then((function(e) {
                        if (!1 === e.ok)
                            return e.json().then((function(e) {
                                throw e.body && e.body.error ? e.body.error : {
                                    message: "Unknown error"
                                }
                            }
                            ));
                        window.location.href = new URL(n.config.checkoutCheckoutUrl).toString()
                    }
                    ))
                }
                ))
            }
        }, {
            key: "validateLiablityShift",
            value: function(e) {
                return void 0 === e ? (console.log("Hosted fields : Liability is undefined."),
                Promise.resolve()) : !1 === e ? (console.log("Hosted fields : Liability is false."),
                Promise.reject(new Error(this.$("error.paypal-sdk.liability.false")))) : "Possible" === e ? (console.log("Hosted fields : Liability might shift to the card issuer."),
                Promise.resolve()) : "No" === e ? (console.log("Hosted fields : Liability is with the merchant."),
                Promise.resolve()) : "Unknown" === e ? (console.log("Hosted fields : The authentication system is not available."),
                Promise.resolve()) : e ? (console.log("Hosted fields : Liability might shift to the card issuer."),
                Promise.resolve()) : (console.log("Hosted fields : Liability unknown."),
                Promise.reject(new Error(this.$("error.paypal-sdk.liability.unknown"))))
            }
        }]) && F(t.prototype, n),
        r && F(t, r),
        e
    }();
    function q(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var Y = function() {
        function e(t, n) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.config = t,
            this.sdk = n
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                return this
            }
        }]) && q(t.prototype, n),
        r && q(t, r),
        e
    }()
      , $ = {
        ANY_PAYMENT_OPTION: '[data-module-name="ps_checkout"]',
        BUTTONS_CONTAINER_ID: "ps_checkout-buttons-container",
        CHECKOUT_EXPRESS_CART_BUTTON_CONTAINER_ID: "js-ps_checkout-express-button-container",
        CHECKOUT_EXPRESS_CHECKOUT_BUTTON_CONTAINER: "#checkout-personal-information-step .content",
        CHECKOUT_EXPRESS_PRODUCT_BUTTON_CONTAINER: ".product-add-to-cart",
        CONDITIONS_CHECKBOX_CONTAINER_ID: "conditions-to-approve",
        CONDITION_CHECKBOX: 'input[type="checkbox"]',
        HOSTED_FIELDS_FORM_ID: "ps_checkout-hosted-fields-form",
        NOTIFICATION_CONDITIONS: ".accept-cgv",
        NOTIFICATION_PAYMENT_CANCELED_ID: "ps_checkout-canceled",
        NOTIFICATION_PAYMENT_ERROR_ID: "ps_checkout-error",
        NOTIFICATION_PAYMENT_ERROR_TEXT_ID: "ps_checkout-error-text",
        PAYMENT_OPTION: '[name="payment-option"]',
        PAYMENT_OPTION_LABEL: function(e) {
            return 'label[for="'.concat(e, '"]')
        },
        PAYMENT_OPTION_SELECT: '[name="select_payment_option"]',
        PAYMENT_OPTION_CONTAINER_ID: function(e) {
            return "".concat(e, "-container")
        },
        PAYMENT_OPTION_ADDITIONAL_INFORMATION_ID: function(e) {
            return "".concat(e, "-additional-information")
        },
        PAYMENT_OPTION_FORM_CONTAINER_ID: function(e) {
            return "pay-with-".concat(e, "-form")
        },
        PAYMENT_OPTION_FORM_BUTTON: function(e) {
            return "#pay-with-".concat(e)
        },
        PAYMENT_OPTIONS_CONTAINER: ".payment-options"
    };
    function X(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var W = function() {
        function e() {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.selectors = $
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "getBasePaymentOption",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.basePaymentOption && !e || (this.basePaymentOption = document.querySelector(this.selectors.ANY_PAYMENT_OPTION)),
                this.basePaymentOption
            }
        }, {
            key: "getButtonContainer",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.buttonContainer && !e || (this.buttonContainer = document.getElementById(this.selectors.BUTTONS_CONTAINER_ID)),
                this.buttonContainer
            }
        }, {
            key: "getCheckoutExpressCartButtonContainer",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.checkoutExpressCartButtonContainer && !e || (this.checkoutExpressCartButtonContainer = document.getElementById(this.selectors.CHECKOUT_EXPRESS_CART_BUTTON_CONTAINER_ID)),
                this.checkoutExpressCartButtonContainer
            }
        }, {
            key: "getCheckoutExpressCheckoutButtonContainer",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.checkoutExpressCheckoutButtonContainer && !e || (this.checkoutExpressCheckoutButtonContainer = document.querySelector(this.selectors.CHECKOUT_EXPRESS_CHECKOUT_BUTTON_CONTAINER)),
                this.checkoutExpressCheckoutButtonContainer
            }
        }, {
            key: "getCheckoutExpressProductButtonContainer",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.checkoutExpressProductButtonContainer && !e || (this.checkoutExpressProductButtonContainer = document.querySelector(this.selectors.CHECKOUT_EXPRESS_PRODUCT_BUTTON_CONTAINER)),
                this.checkoutExpressProductButtonContainer
            }
        }, {
            key: "getConditionsCheckboxContainer",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.conditionsCheckboxContainer && !e || (this.conditionsCheckboxContainer = document.getElementById(this.selectors.CONDITIONS_CHECKBOX_CONTAINER_ID)),
                this.conditionsCheckboxContainer
            }
        }, {
            key: "getConditionsCheckboxes",
            value: function(e) {
                return e ? Array.prototype.slice.call(e.querySelectorAll(this.selectors.CONDITION_CHECKBOX)) : null
            }
        }, {
            key: "getHostedFieldsForm",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.hostedFieldsForm && !e || (this.hostedFieldsForm = document.getElementById(this.selectors.HOSTED_FIELDS_FORM_ID)),
                this.hostedFieldsForm
            }
        }, {
            key: "getNotificationConditions",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.notificationConditions && !e || (this.notificationConditions = document.querySelector(this.selectors.NOTIFICATION_CONDITIONS)),
                this.notificationConditions
            }
        }, {
            key: "getNotificationPaymentCanceled",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.notificationPaymentCanceled && !e || (this.notificationPaymentCanceled = document.getElementById(this.selectors.NOTIFICATION_PAYMENT_CANCELED_ID)),
                this.notificationPaymentCanceled
            }
        }, {
            key: "getNotificationPaymentError",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.notificationPaymentError && !e || (this.notificationPaymentError = document.getElementById(this.selectors.NOTIFICATION_PAYMENT_ERROR_ID)),
                this.notificationPaymentError
            }
        }, {
            key: "getNotificationPaymentErrorText",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.notificationPaymentErrorText && !e || (this.notificationPaymentErrorText = document.getElementById(this.selectors.NOTIFICATION_PAYMENT_ERROR_TEXT_ID)),
                this.notificationPaymentErrorText
            }
        }, {
            key: "getPaymentOption",
            value: function(e) {
                return e.querySelector(this.selectors.PAYMENT_OPTION)
            }
        }, {
            key: "getPaymentOptionLabel",
            value: function(e, t) {
                return Array.prototype.slice.call(e.querySelectorAll("*")).find((function(e) {
                    return e.innerText === t
                }
                ))
            }
        }, {
            key: "getPaymentOptionLabelLegacy",
            value: function(e, t) {
                return e.querySelector(this.selectors.PAYMENT_OPTION_LABEL(t))
            }
        }, {
            key: "getPaymentOptionSelect",
            value: function(e) {
                return e.querySelector(this.selectors.PAYMENT_OPTION_SELECT)
            }
        }, {
            key: "getPaymentOptionContainer",
            value: function(e) {
                return document.getElementById(this.selectors.PAYMENT_OPTION_CONTAINER_ID(e))
            }
        }, {
            key: "getPaymentOptionAdditionalInformation",
            value: function(e) {
                return document.getElementById(this.selectors.PAYMENT_OPTION_ADDITIONAL_INFORMATION_ID(e))
            }
        }, {
            key: "getPaymentOptionFormContainer",
            value: function(e) {
                return document.getElementById(this.selectors.PAYMENT_OPTION_FORM_CONTAINER_ID(e))
            }
        }, {
            key: "getPaymentOptionFormButton",
            value: function(e, t) {
                return e.querySelector(this.selectors.PAYMENT_OPTION_FORM_BUTTON(t))
            }
        }, {
            key: "getPaymentOptionsContainer",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.paymentOptionsContainer && !e || (this.paymentOptionsContainer = document.querySelector(this.selectors.PAYMENT_OPTIONS_CONTAINER)),
                this.paymentOptionsContainer
            }
        }, {
            key: "getPaymentOptions",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.paymentOptions && !e || (this.paymentOptions = this.getPaymentOptionsContainer(e).querySelectorAll(this.selectors.PAYMENT_OPTION),
                this.paymentOptions = Array.prototype.slice.call(this.paymentOptions)),
                this.paymentOptions
            }
        }]) && X(t.prototype, n),
        r && X(t, r),
        e
    }();
    function V(e, t) {
        var n = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(e);
            t && (r = r.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }
            ))),
            n.push.apply(n, r)
        }
        return n
    }
    function K(e) {
        for (var t = 1; t < arguments.length; t++) {
            var n = null != arguments[t] ? arguments[t] : {};
            t % 2 ? V(Object(n), !0).forEach((function(t) {
                J(e, t, n[t])
            }
            )) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : V(Object(n)).forEach((function(t) {
                Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
            }
            ))
        }
        return e
    }
    function J(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n,
        e
    }
    function G(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var z = function() {
        function e(t, n, r) {
            var o = this;
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.sdk = t,
            this.config = n,
            this.translationService = r,
            this.$ = function(e) {
                return o.translationService.getTranslationString(e)
            }
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "getOrderId",
            value: function() {
                return this.config.orderId
            }
        }, {
            key: "getButtonExpress",
            value: function(e, t) {
                return this.sdk.Buttons(K({
                    fundingSource: e,
                    style: {
                        label: "pay",
                        commit: !1
                    }
                }, t))
            }
        }, {
            key: "getButtonPayment",
            value: function(e, t) {
                return this.sdk.Buttons(K({
                    fundingSource: e,
                    style: {
                        label: "pay"
                    }
                }, t))
            }
        }, {
            key: "getHostedFields",
            value: function(e, t) {
                var n = this;
                return this.sdk.HostedFields.render(K({
                    styles: {
                        input: {
                            "font-size": "17px",
                            "font-family": "helvetica, tahoma, calibri, sans-serif",
                            color: "#3a3a3a"
                        },
                        ":focus": {
                            color: "black"
                        }
                    },
                    fields: {
                        number: {
                            selector: e.number,
                            placeholder: this.$("paypal.hosted-fields.placeholder.card-number")
                        },
                        cvv: {
                            selector: e.cvv,
                            placeholder: this.$("paypal.hosted-fields.placeholder.cvv")
                        },
                        expirationDate: {
                            selector: e.expirationDate,
                            placeholder: this.$("paypal.hosted-fields.placeholder.expiration-date")
                        }
                    }
                }, t)).then((function(t) {
                    var r = document.querySelector(e.number)
                      , o = document.querySelector(e.cvv)
                      , i = document.querySelector(e.expirationDate)
                      , a = document.querySelector('label[for="'.concat(r.id, '"]'))
                      , c = document.querySelector('label[for="'.concat(o.id, '"]'))
                      , s = document.querySelector('label[for="'.concat(i.id, '"]'));
                    return a.innerHTML = n.$("paypal.hosted-fields.label.card-number"),
                    c.innerHTML = n.$("paypal.hosted-fields.label.cvv"),
                    s.innerHTML = n.$("paypal.hosted-fields.label.expiration-date"),
                    t
                }
                )).then((function(e) {
                    return e.on("cardTypeChange", (function(t) {
                        var n = t.cards;
                        if (1 === n.length) {
                            document.querySelector(".defautl-credit-card").style.display = "none";
                            var r = document.getElementById("card-image");
                            r.className = "",
                            r.classList.add(n[0].type),
                            document.querySelector("header").classList.add("header-slide"),
                            4 === n[0].code.size && e.setAttribute({
                                field: "cvv",
                                attribute: "placeholder",
                                value: "XXXX"
                            })
                        } else
                            document.querySelector(".defautl-credit-card").style.display = "block",
                            document.getElementById("card-image").className = "",
                            e.setAttribute({
                                field: "cvv",
                                attribute: "placeholder",
                                value: "XXX"
                            })
                    }
                    )),
                    e
                }
                ))
            }
        }, {
            key: "getEligibleFundingSources",
            value: function() {
                var e = this
                  , t = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                if (!this.eligibleFundingSources || t) {
                    var n = this.sdk.getFundingSources();
                    this.eligibleFundingSources = (this.config.fundingSourcesSorted || n).filter((function(e) {
                        return n.indexOf(e) >= 0
                    }
                    )).map((function(t) {
                        return {
                            name: t,
                            mark: e.sdk.Marks({
                                fundingSource: t
                            })
                        }
                    }
                    )).filter((function(t) {
                        var n = t.name
                          , r = t.mark;
                        return "card" === n && e.config.hostedFieldsEnabled ? !!e.isHostedFieldsEligible() || (console.error("Hosted Fields eligibility is declined"),
                        !1) : r.isEligible()
                    }
                    ))
                }
                return this.eligibleFundingSources
            }
        }, {
            key: "isHostedFieldsEligible",
            value: function() {
                return this.sdk.HostedFields && this.sdk.HostedFields.isEligible()
            }
        }]) && G(t.prototype, n),
        r && G(t, r),
        e
    }();
    function Q(e, t) {
        var n = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(e);
            t && (r = r.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }
            ))),
            n.push.apply(n, r)
        }
        return n
    }
    function Z(e) {
        for (var t = 1; t < arguments.length; t++) {
            var n = null != arguments[t] ? arguments[t] : {};
            t % 2 ? Q(Object(n), !0).forEach((function(t) {
                ee(e, t, n[t])
            }
            )) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : Q(Object(n)).forEach((function(t) {
                Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
            }
            ))
        }
        return e
    }
    function ee(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n,
        e
    }
    function te(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var ne = function() {
        function e(t) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.checkout = t,
            this.checkoutConfig = t.config,
            this.htmlElementService = t.htmlElementService,
            this.payPalService = t.payPalService,
            this.psCheckoutService = t.psCheckoutService,
            this.$ = this.checkout.$,
            this.buttonContainer = this.htmlElementService.getCheckoutExpressCartButtonContainer(!0)
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "renderPayPalButton",
            value: function() {
                var e = this;
                if (!(!this.payPalService.getEligibleFundingSources().filter((function(e) {
                    return "paypal" === e.name
                }
                )).length > 0))
                    return this.payPalService.getButtonExpress("paypal", {
                        onInit: function(e, t) {
                            return t.enable()
                        },
                        onClick: function(t, n) {
                            return e.psCheckoutService.postCheckCartOrder(Z(Z({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }), n).catch((function() {
                                return n.reject()
                            }
                            ))
                        },
                        onError: function(e) {
                            return console.error(e)
                        },
                        onApprove: function(t, n) {
                            return e.psCheckoutService.postExpressCheckoutOrder(Z(Z({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }), n)
                        },
                        onCancel: function(t) {
                            return e.psCheckoutService.postCancelOrder(Z(Z({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }))
                        },
                        createOrder: function(t) {
                            return e.psCheckoutService.postCreateOrder(Z(Z({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }))
                        }
                    }).render("#ps-checkout-express-button")
            }
        }, {
            key: "render",
            value: function() {
                if (this.buttonContainer) {
                    this.checkoutExpressButton = document.createElement("div"),
                    this.checkoutExpressButton.id = "ps-checkout-express-button";
                    var e = document.createElement("div");
                    return e.classList.add("ps-checkout-express-separator"),
                    e.innerText = this.$("express-button.cart.separator"),
                    this.buttonContainer.append(e),
                    this.buttonContainer.append(this.checkoutExpressButton),
                    this.renderPayPalButton(),
                    this
                }
            }
        }]) && te(t.prototype, n),
        r && te(t, r),
        e
    }();
    function re(e, t) {
        var n = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(e);
            t && (r = r.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }
            ))),
            n.push.apply(n, r)
        }
        return n
    }
    function oe(e) {
        for (var t = 1; t < arguments.length; t++) {
            var n = null != arguments[t] ? arguments[t] : {};
            t % 2 ? re(Object(n), !0).forEach((function(t) {
                ie(e, t, n[t])
            }
            )) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : re(Object(n)).forEach((function(t) {
                Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
            }
            ))
        }
        return e
    }
    function ie(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n,
        e
    }
    function ae(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var ce = function() {
        function e(t) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.checkout = t,
            this.checkoutConfig = t.config,
            this.htmlElementService = t.htmlElementService,
            this.payPalService = t.payPalService,
            this.psCheckoutService = t.psCheckoutService,
            this.$ = this.checkout.$,
            this.buttonContainer = this.htmlElementService.getCheckoutExpressCheckoutButtonContainer(!0)
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "renderPayPalButton",
            value: function() {
                var e = this;
                if (!(!this.payPalService.getEligibleFundingSources().filter((function(e) {
                    return "paypal" === e.name
                }
                )).length > 0))
                    return this.payPalService.getButtonExpress("paypal", {
                        onInit: function(e, t) {
                            return t.enable()
                        },
                        onClick: function(t, n) {
                            return e.psCheckoutService.postCheckCartOrder(oe(oe({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }), n).catch((function() {
                                return n.reject()
                            }
                            ))
                        },
                        onError: function(e) {
                            return console.error(e)
                        },
                        onApprove: function(t, n) {
                            return e.psCheckoutService.postExpressCheckoutOrder(oe(oe({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }), n)
                        },
                        onCancel: function(t) {
                            return e.psCheckoutService.postCancelOrder(oe(oe({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }))
                        },
                        createOrder: function(t) {
                            return e.psCheckoutService.postCreateOrder(oe(oe({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }))
                        }
                    }).render("#ps-checkout-express-button")
            }
        }, {
            key: "renderTitle",
            value: function() {
                this.checkoutExpressTitle = document.createElement("ul"),
                this.checkoutExpressTitle.classList.add("nav", "nav-inline", "my-1"),
                this.checkoutExpressTitleItem = document.createElement("li"),
                this.checkoutExpressTitleItem.classList.add("nav-item"),
                this.checkoutExpressTitleItemHeading = document.createElement("div"),
                this.checkoutExpressTitleItemHeading.classList.add("nav-link", "active"),
                this.checkoutExpressTitleItemHeading.innerText = this.$("express-button.checkout.express-checkout"),
                this.checkoutExpressTitleItem.append(this.checkoutExpressTitleItemHeading),
                this.checkoutExpressTitle.append(this.checkoutExpressTitleItem)
            }
        }, {
            key: "render",
            value: function() {
                return this.checkoutExpressButton = document.createElement("div"),
                this.checkoutExpressButton.id = "ps-checkout-express-button",
                this.renderTitle(),
                this.buttonContainer.prepend(this.checkoutExpressButton),
                this.buttonContainer.prepend(this.checkoutExpressTitle),
                this.renderPayPalButton(),
                this
            }
        }]) && ae(t.prototype, n),
        r && ae(t, r),
        e
    }();
    function se(e, t) {
        var n = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(e);
            t && (r = r.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }
            ))),
            n.push.apply(n, r)
        }
        return n
    }
    function ue(e) {
        for (var t = 1; t < arguments.length; t++) {
            var n = null != arguments[t] ? arguments[t] : {};
            t % 2 ? se(Object(n), !0).forEach((function(t) {
                le(e, t, n[t])
            }
            )) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : se(Object(n)).forEach((function(t) {
                Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
            }
            ))
        }
        return e
    }
    function le(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n,
        e
    }
    function fe(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var he = function() {
        function e(t) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.checkout = t,
            this.checkoutConfig = t.config,
            this.htmlElementService = t.htmlElementService,
            this.payPalService = t.payPalService,
            this.psCheckoutService = t.psCheckoutService,
            this.buttonContainer = this.htmlElementService.getCheckoutExpressProductButtonContainer(!0)
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "renderPayPalButton",
            value: function() {
                var e = this;
                if (!(!this.payPalService.getEligibleFundingSources().filter((function(e) {
                    return "paypal" === e.name
                }
                )).length > 0))
                    return this.payPalService.getButtonExpress("paypal", {
                        onInit: function(e, t) {
                            return t.enable()
                        },
                        onClick: function(t, n) {
                            return e.psCheckoutService.postCheckCartOrder(ue(ue({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }), n).catch((function() {
                                return n.reject()
                            }
                            ))
                        },
                        onError: function(e) {
                            return console.error(e)
                        },
                        onApprove: function(t, n) {
                            return e.psCheckoutService.postExpressCheckoutOrder(ue(ue({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }), n)
                        },
                        onCancel: function(t) {
                            return e.psCheckoutService.postCancelOrder(ue(ue({}, t), {}, {
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            }))
                        },
                        createOrder: function() {
                            var t = e.psCheckoutService.getProductDetails()
                              , n = t.id_product
                              , r = t.id_product_attribute
                              , o = t.id_customization
                              , i = t.quantity_wanted;
                            return e.psCheckoutService.postCreateOrder({
                                id_product: n,
                                id_product_attribute: r,
                                id_customization: o,
                                quantity_wanted: i,
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            })
                        }
                    }).render("#ps-checkout-express-button")
            }
        }, {
            key: "render",
            value: function() {
                this.checkoutExpressButton = document.createElement("div"),
                this.checkoutExpressButton.id = "ps-checkout-express-button";
                var e = this.buttonContainer.querySelector(".product-quantity").nextElementSibling;
                return this.buttonContainer.insertBefore(this.checkoutExpressButton, e),
                this.renderPayPalButton(),
                this
            }
        }]) && fe(t.prototype, n),
        r && fe(t, r),
        e
    }();
    function de(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var pe = function() {
        function e(t) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.translationMap = t
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "getTranslationString",
            value: function(e) {
                return this.translationMap[e] || "TRANSLATED_STRING(".concat(e, ")")
            }
        }]) && de(t.prototype, n),
        r && de(t, r),
        e
    }();
    function ye(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var me = function() {
        function e(t, n) {
            var r = this;
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.config = t,
            this.sdk = n,
            this.translationService = new pe(this.config.translations),
            this.htmlElementService = new W,
            this.payPalService = new z(this.sdk,this.config,this.translationService),
            this.psCheckoutService = new U(this.config,this.translationService),
            this.$ = function(e) {
                return r.translationService.getTranslationString(e)
            }
            ,
            this.children = {}
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "renderExpressCheckout",
            value: function() {
                switch (document.body.id) {
                case "cart":
                    if (!this.config.expressCheckoutCartEnabled)
                        return;
                    if (document.body.classList.contains("cart-empty"))
                        return;
                    this.children.expressButton = new ne(this).render();
                    break;
                case "checkout":
                    if (!this.config.expressCheckoutOrderEnabled)
                        return;
                    this.children.expressButton = new ce(this).render();
                    break;
                case "product":
                    if (!this.config.expressCheckoutProductEnabled)
                        return;
                    if (this.children.expressButton && this.children.expressButton.checkoutExpressButton && this.children.expressButton.checkoutExpressButton.parentNode)
                        return;
                    this.children.expressButton = new he(this).render()
                }
            }
        }, {
            key: "render",
            value: function() {
                var e = this;
                if ("product" === document.body.id || "cart" === document.body.id || "checkout" === document.body.id) {
                    if (void 0 === this.sdk)
                        throw new Error("No PayPal Javascript SDK Instance");
                    this.renderExpressCheckout(),
                    window.prestashop.on("updatedCart", (function() {
                        return e.renderExpressCheckout()
                    }
                    ))
                }
            }
        }]) && ye(t.prototype, n),
        r && ye(t, r),
        e
    }();
    function be(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n,
        e
    }
    function ve(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var ge = function() {
        function e(t, n) {
            var r;
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.instance = new ((r = {},
            be(r, M, Y),
            be(r, B, me),
            r)[U.getPrestashopVersion()])(t,n)
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                this.instance.render()
            }
        }]) && ve(t.prototype, n),
        r && ve(t, r),
        e
    }()
      , Ee = {
        ANY_PAYMENT_OPTION: "#ps_checkout-displayPayment .payment-option.row",
        CHECKOUT_PAYMENT_OPTIONS_CONTAINER: "#ps_checkout-displayPayment .payment-options",
        NOTIFICATION_TARGET_ID: "HOOK_PAYMENT",
        NOTIFICATION_CONTAINER_ID: "ps_checkout-notification-container",
        NOTIFICATION_PAYMENT_CANCELED_ID: "ps_checkout-canceled",
        NOTIFICATION_PAYMENT_ERROR_ID: "ps_checkout-error",
        NOTIFICATION_PAYMENT_ERROR_TEXT_ID: "ps_checkout-error-text",
        PAYMENT_OPTION: ".row",
        PAYMENT_OPTION_CONTAINER: ".payment-option-container",
        PAYMENT_OPTIONS_CONTAINER: "HOOK_PAYMENT"
    };
    function Oe(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var ke = function() {
        function e() {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.selectors = Ee
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "getBasePaymentOption",
            value: function() {
                return document.querySelector(this.selectors.ANY_PAYMENT_OPTION)
            }
        }, {
            key: "getCheckoutPaymentOptionsContainer",
            value: function() {
                return document.querySelector(this.selectors.CHECKOUT_PAYMENT_OPTIONS_CONTAINER)
            }
        }, {
            key: "getNotificationPaymentContainer",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.notificationPaymentContainer && !e || (this.notificationPaymentContainer = document.getElementById(this.selectors.NOTIFICATION_CONTAINER_ID)),
                this.notificationPaymentContainer
            }
        }, {
            key: "getNotificationPaymentContainerTarget",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.notificationPaymentContainerTarget && !e || (this.notificationPaymentContainerTarget = document.getElementById(this.selectors.NOTIFICATION_TARGET_ID)),
                this.notificationPaymentContainerTarget
            }
        }, {
            key: "getNotificationPaymentCanceled",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.notificationPaymentCanceled && !e || (this.notificationPaymentCanceled = document.getElementById(this.selectors.NOTIFICATION_PAYMENT_CANCELED_ID)),
                this.notificationPaymentCanceled
            }
        }, {
            key: "getNotificationPaymentError",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.notificationPaymentError && !e || (this.notificationPaymentError = document.getElementById(this.selectors.NOTIFICATION_PAYMENT_ERROR_ID)),
                this.notificationPaymentError
            }
        }, {
            key: "getNotificationPaymentErrorText",
            value: function() {
                var e = arguments.length > 0 && void 0 !== arguments[0] && arguments[0];
                return this.notificationPaymentErrorText && !e || (this.notificationPaymentErrorText = document.getElementById(this.selectors.NOTIFICATION_PAYMENT_ERROR_TEXT_ID)),
                this.notificationPaymentErrorText
            }
        }, {
            key: "getPaymentOptionsContainer",
            value: function() {
                return document.getElementById(this.selectors.PAYMENT_OPTIONS_CONTAINER)
            }
        }, {
            key: "getPaymentOptions",
            value: function() {
                return Array.prototype.slice.call(this.getPaymentOptionsContainer().querySelectorAll(this.selectors.PAYMENT_OPTION))
            }
        }, {
            key: "getPaymentOptionContainer",
            value: function(e) {
                return e.querySelector(this.selectors.PAYMENT_OPTION_CONTAINER)
            }
        }]) && Oe(t.prototype, n),
        r && Oe(t, r),
        e
    }();
    function we(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var Ce = function() {
        function e(t) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.htmlElementService = t.htmlElementService,
            this.notificationPaymentContainer = this.htmlElementService.getNotificationPaymentContainer(),
            this.notificationPaymentContainerTarget = this.htmlElementService.getNotificationPaymentContainerTarget(),
            this.notificationPaymentCanceled = this.htmlElementService.getNotificationPaymentCanceled(),
            this.notificationPaymentError = this.htmlElementService.getNotificationPaymentError(),
            this.notificationPaymentErrorText = this.htmlElementService.getNotificationPaymentErrorText()
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                return this.notificationPaymentContainerTarget.prepend(this.notificationPaymentContainer),
                this
            }
        }, {
            key: "hideCancelled",
            value: function() {
                this.notificationPaymentCanceled.style.display = "none"
            }
        }, {
            key: "hideError",
            value: function() {
                this.notificationPaymentError.style.display = "none"
            }
        }, {
            key: "showCanceled",
            value: function() {
                this.notificationPaymentCanceled.style.display = "block"
            }
        }, {
            key: "showError",
            value: function(e) {
                this.notificationPaymentError.style.display = "block",
                this.notificationPaymentErrorText.textContent = e
            }
        }]) && we(t.prototype, n),
        r && we(t, r),
        e
    }();
    function Pe(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    function Te(e, t) {
        if (t !== _e) {
            Te(e, Object.getPrototypeOf(t));
            for (var n = e.app, r = t.INJECT || {}, o = 0, i = Object.keys(r); o < i.length; o++) {
                var a = i[o]
                  , c = r[a];
                e[a] = n[c]
            }
        }
    }
    var _e = function() {
        function e(t, n) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.app = t,
            this.data = {},
            this.props = n || {},
            this.children = {},
            Te(this, this.constructor)
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                return this
            }
        }]) && Pe(t.prototype, n),
        r && Pe(t, r),
        e
    }();
    function Se(e) {
        return (Se = "function" === typeof Symbol && "symbol" === typeof Symbol.iterator ? function(e) {
            return typeof e
        }
        : function(e) {
            return e && "function" === typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        }
        )(e)
    }
    function Ie(e, t) {
        var n = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(e);
            t && (r = r.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }
            ))),
            n.push.apply(n, r)
        }
        return n
    }
    function je(e) {
        for (var t = 1; t < arguments.length; t++) {
            var n = null != arguments[t] ? arguments[t] : {};
            t % 2 ? Ie(Object(n), !0).forEach((function(t) {
                Be(e, t, n[t])
            }
            )) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : Ie(Object(n)).forEach((function(t) {
                Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
            }
            ))
        }
        return e
    }
    function Ne(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    function xe(e, t) {
        return (xe = Object.setPrototypeOf || function(e, t) {
            return e.__proto__ = t,
            e
        }
        )(e, t)
    }
    function Ae(e) {
        var t = function() {
            if ("undefined" === typeof Reflect || !Reflect.construct)
                return !1;
            if (Reflect.construct.sham)
                return !1;
            if ("function" === typeof Proxy)
                return !0;
            try {
                return Date.prototype.toString.call(Reflect.construct(Date, [], (function() {}
                ))),
                !0
            } catch (e) {
                return !1
            }
        }();
        return function() {
            var n, r = Me(e);
            if (t) {
                var o = Me(this).constructor;
                n = Reflect.construct(r, arguments, o)
            } else
                n = r.apply(this, arguments);
            return Le(this, n)
        }
    }
    function Le(e, t) {
        return !t || "object" !== Se(t) && "function" !== typeof t ? function(e) {
            if (void 0 === e)
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            return e
        }(e) : t
    }
    function Me(e) {
        return (Me = Object.setPrototypeOf ? Object.getPrototypeOf : function(e) {
            return e.__proto__ || Object.getPrototypeOf(e)
        }
        )(e)
    }
    function Be(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n,
        e
    }
    var Re = function(e) {
        !function(e, t) {
            if ("function" !== typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function");
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && xe(e, t)
        }(i, e);
        var t, n, r, o = Ae(i);
        function i(e, t) {
            var n;
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, i),
            (n = o.call(this, e, t)).data.name = t.fundingSource.name,
            n.data.validity = !1,
            n.data.HTMLElement = t.HTMLElement,
            n.data.HTMLElementBaseButton = n.getBaseButton(),
            n.data.HTMLElementButton = null,
            n.data.HTMLElementButtonWrapper = n.getButtonWrapper(),
            n.data.HTMLElementCardNumber = n.getCardNumber(),
            n.data.HTMLElementCardCVV = n.getCardCVV(),
            n.data.HTMLElementCardExpirationDate = n.getCardExpirationDate(),
            n.data.HTMLElementSection = n.getSection(),
            n.data.conditionsComponent = n.app.children.conditionsCheckbox,
            n
        }
        return t = i,
        (n = [{
            key: "getBaseButton",
            value: function() {
                return document.querySelector("#payment-confirmation button")
            }
        }, {
            key: "getButtonWrapper",
            value: function() {
                var e = ".ps_checkout-button[data-funding-source=".concat(this.data.name, "]");
                return document.querySelector(e)
            }
        }, {
            key: "getCardNumber",
            value: function() {
                return document.getElementById("#ps_checkout-hosted-fields-card-number")
            }
        }, {
            key: "getCardCVV",
            value: function() {
                return document.getElementById("#ps_checkout-hosted-fields-card-cvv")
            }
        }, {
            key: "getCardExpirationDate",
            value: function() {
                return document.getElementById("#ps_checkout-hosted-fields-card-expiration-date")
            }
        }, {
            key: "getSection",
            value: function() {
                var e = ".js-payment-ps_checkout-".concat(this.data.name);
                return document.querySelector(e)
            }
        }, {
            key: "isSubmittable",
            value: function() {
                return this.data.conditionsComponent ? this.data.conditionsComponent.isChecked() && this.data.validity : this.data.validity
            }
        }, {
            key: "renderPayPalHostedFields",
            value: function() {
                var e = this;
                this.payPalService.getHostedFields({
                    number: "#ps_checkout-hosted-fields-card-number",
                    cvv: "#ps_checkout-hosted-fields-card-cvv",
                    expirationDate: "#ps_checkout-hosted-fields-card-expiration-date"
                }, {
                    createOrder: function() {
                        return e.psCheckoutService.postCreateOrder({
                            fundingSource: e.data.name,
                            isHostedFields: !0
                        }).catch((function(t) {
                            e.app.children.notification.showError("".concat(t.message, " ").concat(t.name))
                        }
                        ))
                    }
                }).then((function(t) {
                    null !== e.data.HTMLElement && (t.on("validityChange", (function(t) {
                        e.data.validity = 0 === Object.keys(t.fields).map((function(e) {
                            return t.fields[e]
                        }
                        )).map((function(e) {
                            return e.isValid
                        }
                        )).filter((function(e) {
                            return !1 === e
                        }
                        )).length,
                        e.data.HTMLElementSection.classList.toggle("disabled", !e.isSubmittable()),
                        e.isSubmittable() ? e.data.HTMLElementButton.removeAttribute("disabled") : e.data.HTMLElementButton.setAttribute("disabled", "")
                    }
                    )),
                    e.data.HTMLElementButton.addEventListener("click", (function(n) {
                        n.preventDefault(),
                        e.app.children.loader.show(),
                        e.data.HTMLElementSection.classList.toggle("disabled", !0),
                        t.submit({
                            contingencies: ["3D_SECURE"]
                        }).then((function(t) {
                            var n = t.liabilityShift;
                            return e.psCheckoutService.validateLiablityShift(n).then((function() {
                                var n = t;
                                return n.orderID = n.orderId,
                                delete n.orderId,
                                e.psCheckoutService.postValidateOrder(je(je({}, n), {}, {
                                    fundingSource: e.data.name,
                                    isHostedFields: !0
                                }))
                            }
                            ))
                        }
                        )).catch((function(t) {
                            e.app.children.loader.hide(),
                            e.app.children.notification.showError(t.message),
                            e.data.HTMLElementButton.disabled = !1
                        }
                        ))
                    }
                    )))
                }
                ))
            }
        }, {
            key: "renderButton",
            value: function() {
                var e = this;
                this.data.HTMLElementButton = this.data.HTMLElementBaseButton.cloneNode(!0),
                this.data.HTMLElementButtonWrapper.append(this.data.HTMLElementButton),
                this.data.HTMLElementButton.disabled = !this.isSubmittable(),
                this.data.conditionsComponent && this.data.conditionsComponent.onChange((function() {
                    e.data.HTMLElementButton.disabled = !e.isSubmittable()
                }
                ))
            }
        }, {
            key: "render",
            value: function() {
                return this.renderButton(),
                this.renderPayPalHostedFields(),
                this
            }
        }]) && Ne(t.prototype, n),
        r && Ne(t, r),
        i
    }(_e);
    function De(e) {
        return (De = "function" === typeof Symbol && "symbol" === typeof Symbol.iterator ? function(e) {
            return typeof e
        }
        : function(e) {
            return e && "function" === typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        }
        )(e)
    }
    function He(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    function Fe(e, t) {
        return (Fe = Object.setPrototypeOf || function(e, t) {
            return e.__proto__ = t,
            e
        }
        )(e, t)
    }
    function Ue(e) {
        var t = function() {
            if ("undefined" === typeof Reflect || !Reflect.construct)
                return !1;
            if (Reflect.construct.sham)
                return !1;
            if ("function" === typeof Proxy)
                return !0;
            try {
                return Date.prototype.toString.call(Reflect.construct(Date, [], (function() {}
                ))),
                !0
            } catch (e) {
                return !1
            }
        }();
        return function() {
            var n, r = Ye(e);
            if (t) {
                var o = Ye(this).constructor;
                n = Reflect.construct(r, arguments, o)
            } else
                n = r.apply(this, arguments);
            return qe(this, n)
        }
    }
    function qe(e, t) {
        return !t || "object" !== De(t) && "function" !== typeof t ? function(e) {
            if (void 0 === e)
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            return e
        }(e) : t
    }
    function Ye(e) {
        return (Ye = Object.setPrototypeOf ? Object.getPrototypeOf : function(e) {
            return e.__proto__ || Object.getPrototypeOf(e)
        }
        )(e)
    }
    Be(Re, "INJECT", {
        config: "config",
        htmlElementService: "htmlElementService",
        payPalService: "payPalService",
        psCheckoutService: "psCheckoutService"
    });
    var $e, Xe, We, Ve = function(e) {
        !function(e, t) {
            if ("function" !== typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function");
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && Fe(e, t)
        }(i, e);
        var t, n, r, o = Ue(i);
        function i(e, t) {
            var n;
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, i),
            (n = o.call(this, e, t)).data.name = t.fundingSource.name,
            n.data.mark = t.fundingSource.mark,
            n.data.HTMLElement = t.HTMLElement,
            n.data.HTMLElementImage = t.HTMLElementImage || null,
            n
        }
        return t = i,
        (n = [{
            key: "hasCustomMark",
            value: function() {
                return this.config.customMark[this.data.name]
            }
        }, {
            key: "renderCustomMark",
            value: function() {
                var e = this.config.customMark[this.data.name];
                this.data.HTMLElementImage = document.createElement("img"),
                this.data.HTMLElementImage.classList.add("ps-checkout-funding-img"),
                this.data.HTMLElementImage.setAttribute("alt", this.data.name),
                this.data.HTMLElementImage.setAttribute("src", e),
                this.data.HTMLElement.append(this.data.HTMLElementImage)
            }
        }, {
            key: "render",
            value: function() {
                if (this.data.HTMLElement.classList.add("ps_checkout-mark"),
                this.data.HTMLElement.setAttribute("data-funding-source", this.data.name),
                this.hasCustomMark())
                    this.renderCustomMark();
                else {
                    var e = ".ps_checkout-mark[data-funding-source=".concat(this.data.name, "]");
                    this.data.mark.render(e)
                }
                return this
            }
        }]) && He(t.prototype, n),
        r && He(t, r),
        i
    }(_e);
    function Ke(e) {
        return (Ke = "function" === typeof Symbol && "symbol" === typeof Symbol.iterator ? function(e) {
            return typeof e
        }
        : function(e) {
            return e && "function" === typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        }
        )(e)
    }
    function Je(e, t) {
        var n = Object.keys(e);
        if (Object.getOwnPropertySymbols) {
            var r = Object.getOwnPropertySymbols(e);
            t && (r = r.filter((function(t) {
                return Object.getOwnPropertyDescriptor(e, t).enumerable
            }
            ))),
            n.push.apply(n, r)
        }
        return n
    }
    function Ge(e) {
        for (var t = 1; t < arguments.length; t++) {
            var n = null != arguments[t] ? arguments[t] : {};
            t % 2 ? Je(Object(n), !0).forEach((function(t) {
                nt(e, t, n[t])
            }
            )) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(n)) : Je(Object(n)).forEach((function(t) {
                Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(n, t))
            }
            ))
        }
        return e
    }
    function ze(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    function Qe(e, t) {
        return (Qe = Object.setPrototypeOf || function(e, t) {
            return e.__proto__ = t,
            e
        }
        )(e, t)
    }
    function Ze(e) {
        var t = function() {
            if ("undefined" === typeof Reflect || !Reflect.construct)
                return !1;
            if (Reflect.construct.sham)
                return !1;
            if ("function" === typeof Proxy)
                return !0;
            try {
                return Date.prototype.toString.call(Reflect.construct(Date, [], (function() {}
                ))),
                !0
            } catch (e) {
                return !1
            }
        }();
        return function() {
            var n, r = tt(e);
            if (t) {
                var o = tt(this).constructor;
                n = Reflect.construct(r, arguments, o)
            } else
                n = r.apply(this, arguments);
            return et(this, n)
        }
    }
    function et(e, t) {
        return !t || "object" !== Ke(t) && "function" !== typeof t ? function(e) {
            if (void 0 === e)
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            return e
        }(e) : t
    }
    function tt(e) {
        return (tt = Object.setPrototypeOf ? Object.getPrototypeOf : function(e) {
            return e.__proto__ || Object.getPrototypeOf(e)
        }
        )(e)
    }
    function nt(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n,
        e
    }
    We = {
        config: "config"
    },
    (Xe = "INJECT")in ($e = Ve) ? Object.defineProperty($e, Xe, {
        value: We,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : $e[Xe] = We;
    var rt = function(e) {
        !function(e, t) {
            if ("function" !== typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function");
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && Qe(e, t)
        }(i, e);
        var t, n, r, o = Ze(i);
        function i(e, t) {
            var n;
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, i),
            (n = o.call(this, e, t)).data.name = t.fundingSource.name,
            n.data.HTMLElement = t.HTMLElement,
            n.data.conditionsComponent = n.app.children.conditionsCheckbox,
            n.data.loaderComponent = n.app.children.loader,
            n.data.notificationComponent = n.app.children.notification,
            n
        }
        return t = i,
        (n = [{
            key: "renderPayPalButton",
            value: function() {
                var e = this
                  , t = ".ps_checkout-button[data-funding-source=".concat(this.data.name, "]");
                return this.data.HTMLElement.classList.add("ps_checkout-button"),
                this.data.HTMLElement.setAttribute("data-funding-source", this.data.name),
                this.payPalService.getButtonPayment(this.data.name, {
                    onInit: function(t, n) {
                        e.data.conditionsComponent ? (e.data.conditionsComponent.isChecked() ? (e.data.notificationComponent.hideConditions(),
                        n.enable()) : (e.data.notificationComponent.showConditions(),
                        n.disable()),
                        e.data.conditionsComponent.onChange((function() {
                            e.data.conditionsComponent.isChecked() ? (e.data.notificationComponent.hideConditions(),
                            n.enable()) : (e.data.notificationComponent.showConditions(),
                            n.disable())
                        }
                        ))) : n.enable()
                    },
                    onClick: function(t, n) {
                        if (e.data.conditionsComponent && !e.data.conditionsComponent.isChecked())
                            return e.data.notificationComponent.hideCancelled(),
                            e.data.notificationComponent.hideError(),
                            void e.data.notificationComponent.showConditions();
                        "card" !== e.data.name && e.data.loaderComponent.show(),
                        e.psCheckoutService.postCheckCartOrder(Ge(Ge({}, t), {}, {
                            fundingSource: e.data.name
                        }), n).catch((function(t) {
                            e.data.loaderComponent.hide(),
                            e.data.notificationComponent.showError(t.message),
                            n.reject()
                        }
                        ))
                    },
                    onError: function(t) {
                        console.error(t),
                        e.data.loaderComponent.hide(),
                        e.data.notificationComponent.showError(t instanceof TypeError ? t.message : "")
                    },
                    onApprove: function(t, n) {
                        return e.data.loaderComponent.show(),
                        e.psCheckoutService.postValidateOrder(Ge(Ge({}, t), {}, {
                            fundingSource: e.data.name
                        }), n).catch((function(t) {
                            e.data.loaderComponent.hide(),
                            e.data.notificationComponent.showError(t.message)
                        }
                        ))
                    },
                    onCancel: function(t) {
                        return e.data.loaderComponent.hide(),
                        e.data.notificationComponent.showCanceled(),
                        e.psCheckoutService.postCancelOrder(Ge(Ge({}, t), {}, {
                            fundingSource: e.data.name
                        })).catch((function(t) {
                            e.data.loaderComponent.hide(),
                            e.data.notificationComponent.showError(t.message)
                        }
                        ))
                    },
                    createOrder: function(t) {
                        return e.psCheckoutService.postCreateOrder(Ge(Ge({}, t), {}, {
                            fundingSource: e.data.name
                        })).catch((function(t) {
                            e.data.loaderComponent.hide(),
                            e.data.notificationComponent.showError("".concat(t.message, " ").concat(t.name))
                        }
                        ))
                    }
                }).render(t)
            }
        }, {
            key: "render",
            value: function() {
                return this.renderPayPalButton(),
                this
            }
        }]) && ze(t.prototype, n),
        r && ze(t, r),
        i
    }(_e);
    function ot(e) {
        return (ot = "function" === typeof Symbol && "symbol" === typeof Symbol.iterator ? function(e) {
            return typeof e
        }
        : function(e) {
            return e && "function" === typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        }
        )(e)
    }
    function it(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    function at(e, t) {
        return (at = Object.setPrototypeOf || function(e, t) {
            return e.__proto__ = t,
            e
        }
        )(e, t)
    }
    function ct(e) {
        var t = function() {
            if ("undefined" === typeof Reflect || !Reflect.construct)
                return !1;
            if (Reflect.construct.sham)
                return !1;
            if ("function" === typeof Proxy)
                return !0;
            try {
                return Date.prototype.toString.call(Reflect.construct(Date, [], (function() {}
                ))),
                !0
            } catch (e) {
                return !1
            }
        }();
        return function() {
            var n, r = ut(e);
            if (t) {
                var o = ut(this).constructor;
                n = Reflect.construct(r, arguments, o)
            } else
                n = r.apply(this, arguments);
            return st(this, n)
        }
    }
    function st(e, t) {
        return !t || "object" !== ot(t) && "function" !== typeof t ? function(e) {
            if (void 0 === e)
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            return e
        }(e) : t
    }
    function ut(e) {
        return (ut = Object.setPrototypeOf ? Object.getPrototypeOf : function(e) {
            return e.__proto__ || Object.getPrototypeOf(e)
        }
        )(e)
    }
    nt(rt, "INJECT", {
        config: "config",
        htmlElementService: "htmlElementService",
        payPalService: "payPalService",
        psCheckoutService: "psCheckoutService"
    });
    var lt = function(e) {
        !function(e, t) {
            if ("function" !== typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function");
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && at(e, t)
        }(i, e);
        var t, n, r, o = ct(i);
        function i(e, t) {
            var n;
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, i),
            (n = o.call(this, e, t)).data.name = t.fundingSource.name,
            n.data.HTMLElement = t.HTMLElement,
            n.data.HTMLElementWrapper = n.getWrapper(),
            n.data.HTMLElementLabel = n.getLabel(),
            n.data.HTMLElementMark = t.HTMLElementMark || null,
            n.data.HTMLElementHostedFields = n.getHostedFields(),
            n.data.HTMLElementSmartButton = n.getSmartButton(),
            n
        }
        return t = i,
        (n = [{
            key: "getHostedFields",
            value: function() {
                return "card" === this.data.name && this.config.hostedFieldsEnabled && (this.props.HTMLElementHostedFields || document.getElementById("ps_checkout-hosted-fields-form"))
            }
        }, {
            key: "getLabel",
            value: function() {
                var e = "funding-source.name.".concat(this.data.name)
                  , t = void 0 !== this.$(e) ? this.$(e) : this.$("funding-source.name.default");
                return Array.prototype.slice.call(this.data.HTMLElementWrapper.querySelectorAll("*")).find((function(e) {
                    return e.innerHTML.trim() === t.trim()
                }
                ))
            }
        }, {
            key: "getSmartButton",
            value: function() {
                var e = ".ps_checkout-button[data-funding-source=".concat(this.data.name, "]");
                return this.props.HTMLElementSmartButton || document.querySelector(e)
            }
        }, {
            key: "getWrapper",
            value: function() {
                var e = "".concat(this.data.HTMLElement.id, "-container");
                return this.props.HTMLElementWrapper || document.getElementById(e)
            }
        }, {
            key: "onLabelClick",
            value: function(e) {
                var t = this;
                this.data.HTMLElementLabel.addEventListener("click", (function(n) {
                    n.preventDefault(),
                    e(t, n)
                }
                ))
            }
        }, {
            key: "renderWrapper",
            value: function() {
                console.log("YYYYY");
                this.data.HTMLElementWrapper.classList.add("ps_checkout-payment-option"),
                this.data.HTMLElementWrapper.style.display = ""
            }
        }, {
            key: "renderMark",
            value: function() {
                this.data.HTMLElementMarker || (this.data.HTMLElementMarker = document.createElement("div"),
                this.data.HTMLElementMarker.style.display = "inline-block",
                "before" === this.props.markPosition ? this.data.HTMLElementLabel.prepend(this.data.HTMLElementMarker) : this.data.HTMLElementLabel.append(this.data.HTMLElementMarker)),
                this.children.Marker = this.marker = new Ve(this.app,{
                    fundingSource: this.props.fundingSource,
                    HTMLElement: this.data.HTMLElementMarker
                }).render()
            }
        }, {
            key: "render",
            value: function() {
                return this.renderWrapper(),
                this.renderMark(),
                this.data.HTMLElementHostedFields ? this.children.hostedFields = new Re(this.app,{
                    fundingSource: this.props.fundingSource,
                    HTMLElement: this.data.HTMLElementHostedFields
                }).render() : this.children.smartButton = new rt(this.app,{
                    fundingSource: this.props.fundingSource,
                    HTMLElement: this.data.HTMLElementSmartButton
                }).render(),
                this
            }
        }]) && it(t.prototype, n),
        r && it(t, r),
        i
    }(_e);
    function ft(e) {
        return (ft = "function" === typeof Symbol && "symbol" === typeof Symbol.iterator ? function(e) {
            return typeof e
        }
        : function(e) {
            return e && "function" === typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        }
        )(e)
    }
    function ht(e, t) {
        return function(e) {
            if (Array.isArray(e))
                return e
        }(e) || function(e, t) {
            if ("undefined" === typeof Symbol || !(Symbol.iterator in Object(e)))
                return;
            var n = []
              , r = !0
              , o = !1
              , i = void 0;
            try {
                for (var a, c = e[Symbol.iterator](); !(r = (a = c.next()).done) && (n.push(a.value),
                !t || n.length !== t); r = !0)
                    ;
            } catch (Ut) {
                o = !0,
                i = Ut
            } finally {
                try {
                    r || null == c.return || c.return()
                } finally {
                    if (o)
                        throw i
                }
            }
            return n
        }(e, t) || function(e, t) {
            if (!e)
                return;
            if ("string" === typeof e)
                return dt(e, t);
            var n = Object.prototype.toString.call(e).slice(8, -1);
            "Object" === n && e.constructor && (n = e.constructor.name);
            if ("Map" === n || "Set" === n)
                return Array.from(e);
            if ("Arguments" === n || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))
                return dt(e, t)
        }(e, t) || function() {
            throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
        }()
    }
    function dt(e, t) {
        (null == t || t > e.length) && (t = e.length);
        for (var n = 0, r = new Array(t); n < t; n++)
            r[n] = e[n];
        return r
    }
    function pt(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    function yt(e, t) {
        return (yt = Object.setPrototypeOf || function(e, t) {
            return e.__proto__ = t,
            e
        }
        )(e, t)
    }
    function mt(e) {
        var t = function() {
            if ("undefined" === typeof Reflect || !Reflect.construct)
                return !1;
            if (Reflect.construct.sham)
                return !1;
            if ("function" === typeof Proxy)
                return !0;
            try {
                return Date.prototype.toString.call(Reflect.construct(Date, [], (function() {}
                ))),
                !0
            } catch (e) {
                return !1
            }
        }();
        return function() {
            var n, r = vt(e);
            if (t) {
                var o = vt(this).constructor;
                n = Reflect.construct(r, arguments, o)
            } else
                n = r.apply(this, arguments);
            return bt(this, n)
        }
    }
    function bt(e, t) {
        return !t || "object" !== ft(t) && "function" !== typeof t ? function(e) {
            if (void 0 === e)
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            return e
        }(e) : t
    }
    function vt(e) {
        return (vt = Object.setPrototypeOf ? Object.getPrototypeOf : function(e) {
            return e.__proto__ || Object.getPrototypeOf(e)
        }
        )(e)
    }
    !function(e, t, n) {
        t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n
    }(lt, "INJECT", {
        config: "config",
        htmlElementService: "htmlElementService",
        $: "$"
    });
    var gt = function(e) {
        !function(e, t) {
            if ("function" !== typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function");
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && yt(e, t)
        }(i, e);
        var t, n, r, o = mt(i);
        function i(e, t) {
            var n;
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, i),
            (n = o.call(this, e, t)).data.HTMLElement = n.getPaymentOptions(),
            n.data.notificationComponent = n.app.children.notification,
            n
        }
        return t = i,
        (n = [{
            key: "getPaymentOptions",
            value: function() {
                return document.querySelector(".payment-options")
            }
        }, {
            key: "renderPaymentOptionItems",
            value: function() {
                var e = this;
                this.children.paymentOptions = this.payPalService.getEligibleFundingSources().map((function(t) {
                    return new lt(e.app,{
                        fundingSource: t,
                        markPosition: e.props.markPosition,
                        HTMLElement: document.querySelector('[data-module-name="ps_checkout-'.concat(t.name, '"]'))
                    }).render()
                }
                ))
            }
        }, {
            key: "renderPaymentOptionListener",
            value: function() {
                var e = this.children.paymentOptions.map((function(e) {
                    var t = e.data.HTMLElementWrapper
                      , n = ht(Array.prototype.slice.call(t.querySelectorAll(".payment_module")), 2);
                    return {
                        button: n[0],
                        form: n[1]
                    }
                }
                ));
                this.children.paymentOptions.forEach((function(t, n) {
                    t.onLabelClick((function() {
                        e.forEach((function(e) {
                            var t = e.button
                              , n = e.form;
                            t.classList.add("closed"),
                            n.classList.add("closed"),
                            t.classList.remove("open"),
                            n.classList.remove("open")
                        }
                        )),
                        e[n].button.classList.add("open"),
                        e[n].button.classList.remove("closed"),
                        e[n].form.classList.add("open"),
                        e[n].form.classList.remove("closed")
                    }
                    ))
                }
                ))
            }
        }, {
            key: "render",
            value: function() {
                return this.config.expressCheckoutSelected || (this.renderPaymentOptionItems(),
                this.renderPaymentOptionListener()),
                this
            }
        }]) && pt(t.prototype, n),
        r && pt(t, r),
        i
    }(_e);
    function Et(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    !function(e, t, n) {
        t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n
    }(gt, "INJECT", {
        config: "config",
        htmlElementService: "htmlElementService",
        payPalService: "payPalService",
        psCheckoutService: "psCheckoutService"
    });
    var Ot = function() {
        function e(t) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.checkout = t,
            this.config = this.checkout.config,
            this.$ = this.checkout.$
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                return this.overlay = document.createElement("div"),
                this.overlay.classList.add("ps-checkout", "overlay"),
                this.popup = document.createElement("div"),
                this.popup.classList.add("ps-checkout", "popup"),
                this.text = document.createElement("h1"),
                this.text.classList.add("ps-checkout", "text"),
                this.text.innerHTML = this.$("loader-component.label.header"),
                this.loader = document.createElement("img"),
                this.loader.classList.add("ps-checkout", "loader"),
                this.loader.setAttribute("src", this.config.loaderImage),
                this.loader.setAttribute("alt", "loader"),
                this.subtext = document.createElement("div"),
                this.subtext.classList.add("ps-checkout", "subtext"),
                this.text.innerHTML = this.$("loader-component.label.body"),
                this.popup.append(this.text),
                this.popup.append(this.loader),
                this.popup.append(this.subtext),
                this.overlay.append(this.popup),
                document.body.append(this.overlay),
                this
            }
        }, {
            key: "show",
            value: function() {
                this.overlay.classList.add("visible"),
                document.body.style.overflow = "hidden"
            }
        }, {
            key: "hide",
            value: function() {
                this.overlay.classList.remove("visible"),
                document.body.style.overflow = ""
            }
        }]) && Et(t.prototype, n),
        r && Et(t, r),
        e
    }();
    function kt(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var wt = function() {
        function e(t, n) {
            var r = this;
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.config = t,
            this.sdk = n,
            this.translationService = new pe(this.config.translations),
            this.htmlElementService = new ke,
            this.payPalService = new z(this.sdk,this.config,this.translationService),
            this.psCheckoutService = new U(this.config,this.translationService),
            this.$ = function(e) {
                return r.translationService.getTranslationString(e)
            }
            ,
            this.children = {}
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                var e = this;
                if ("module-supercheckout-supercheckout" === document.body.id || "order" === document.body.id || "order-opc" === document.body.id) {
                    if (void 0 === this.sdk)
                        throw new Error(this.$("error.paypal-sdk"));
                    if ("order" === document.body.id) {
                        if (this.children.notification = new Ce(this).render(),
                        !document.getElementById("ps_checkout-displayPayment"))
                            return;
                        this.children.loader = new Ot(this).render(),
                        this.children.paymentOptions = new gt(this).render()
                    } else {
                        var t = window.updatePaymentMethods;
                        if (window.updatePaymentMethods = function() {
                            if (t.apply(void 0, arguments),
                            window.isLogged) {
                                var n = document.getElementById("cgv");
                                (n && n.checked || !n) && (e.children.notification = new Ce(e).render(),
                                e.children.loader = new Ot(e).render(),
                                e.children.paymentOptions = new gt(e,{
                                    markPosition: "before"
                                }).render())
                            }
                        }
                        ,
                        window.isLogged) {
                            var n = document.getElementById("cgv");
                            (n && n.checked || !n) && (this.children.notification = new Ce(this).render(),
                            this.children.loader = new Ot(this).render(),
                            this.children.paymentOptions = new gt(this,{
                                markPosition: "before"
                            }).render())
                        }
                    }
                }
            }
        }]) && kt(t.prototype, n),
        r && kt(t, r),
        e
    }();
    function Ct(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var Pt = function() {
        function e(t) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.htmlElementService = t.htmlElementService,
            this.notificationConditions = this.htmlElementService.getNotificationConditions(),
            this.notificationPaymentCanceled = this.htmlElementService.getNotificationPaymentCanceled(),
            this.notificationPaymentError = this.htmlElementService.getNotificationPaymentError(),
            this.notificationPaymentErrorText = this.htmlElementService.getNotificationPaymentErrorText()
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                return this
            }
        }, {
            key: "hideCancelled",
            value: function() {
                this.notificationPaymentCanceled.style.display = "none"
            }
        }, {
            key: "hideConditions",
            value: function() {
                this.notificationConditions.style.display = "none"
            }
        }, {
            key: "hideError",
            value: function() {
                this.notificationPaymentError.style.display = "none"
            }
        }, {
            key: "showCanceled",
            value: function() {
                this.notificationPaymentCanceled.style.display = "block"
            }
        }, {
            key: "showConditions",
            value: function() {
                this.notificationConditions.style.display = "block"
            }
        }, {
            key: "showError",
            value: function(e) {
                this.notificationPaymentError.style.display = "block",
                this.notificationPaymentErrorText.textContent = e
            }
        }]) && Ct(t.prototype, n),
        r && Ct(t, r),
        e
    }();
    function Tt(e) {
        return (Tt = "function" === typeof Symbol && "symbol" === typeof Symbol.iterator ? function(e) {
            return typeof e
        }
        : function(e) {
            return e && "function" === typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
        }
        )(e)
    }
    function _t(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    function St(e, t) {
        return (St = Object.setPrototypeOf || function(e, t) {
            return e.__proto__ = t,
            e
        }
        )(e, t)
    }
    function It(e) {
        var t = function() {
            if ("undefined" === typeof Reflect || !Reflect.construct)
                return !1;
            if (Reflect.construct.sham)
                return !1;
            if ("function" === typeof Proxy)
                return !0;
            try {
                return Date.prototype.toString.call(Reflect.construct(Date, [], (function() {}
                ))),
                !0
            } catch (e) {
                return !1
            }
        }();
        return function() {
            var n, r = Nt(e);
            if (t) {
                var o = Nt(this).constructor;
                n = Reflect.construct(r, arguments, o)
            } else
                n = r.apply(this, arguments);
            return jt(this, n)
        }
    }
    function jt(e, t) {
        return !t || "object" !== Tt(t) && "function" !== typeof t ? function(e) {
            if (void 0 === e)
                throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            return e
        }(e) : t
    }
    function Nt(e) {
        return (Nt = Object.setPrototypeOf ? Object.getPrototypeOf : function(e) {
            return e.__proto__ || Object.getPrototypeOf(e)
        }
        )(e)
    }
    var xt = function(e) {
        !function(e, t) {
            if ("function" !== typeof t && null !== t)
                throw new TypeError("Super expression must either be null or a function");
            e.prototype = Object.create(t && t.prototype, {
                constructor: {
                    value: e,
                    writable: !0,
                    configurable: !0
                }
            }),
            t && St(e, t)
        }(i, e);
        var t, n, r, o = It(i);
        function i(e, t) {
            var n;
            return function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, i),
            (n = o.call(this, e, t)).data.HTMLElement = n.getPaymentOptions(),
            n.data.notificationComponent = n.app.children.notification,
            n
        }
        return t = i,
        (n = [{
            key: "getPaymentOptions",
            value: function() {
                return document.querySelector(".payment-options")
            }
        }, {
            key: "renderPaymentOptionItems",
            value: function() {
                var e = this;
                this.children.paymentOptions = this.payPalService.getEligibleFundingSources().map((function(t) {
                    return new lt(e.app,{
                        fundingSource: t,
                        markPosition: e.props.markPosition,
                        HTMLElement: document.querySelector('[data-module-name="ps_checkout-'.concat(t.name, '"]'))
                    }).render()
                }
                ))
            }
        }, {
            key: "renderPaymentOptionRadios",
            value: function() {
                var e = this;
                Array.prototype.slice.call(this.data.HTMLElement.querySelectorAll('input[type="radio"][name="payment-option"]')).forEach((function(t) {
                    t.addEventListener("change", (function() {
                        e.data.notificationComponent.hideCancelled(),
                        e.data.notificationComponent.hideError()
                    }
                    ))
                }
                ))
            }
        }, {
            key: "render",
            value: function() {
                var e = this;
                if (this.config.expressCheckoutSelected) {
                    this.htmlElementService.getPaymentOptionsContainer().style.display = "none",
                    document.querySelector('input[type="radio"][data-module-name="ps_checkout-paypal"]').checked = !0,
                    this.smartButton = document.createElement("div"),
                    this.smartButton.id = "button-paypal",
                    this.smartButton.classList.add("checkout-smartbutton");
                    var t = document.querySelector("#payment-confirmation [type='submit']").cloneNode(!0);
                    t.id = "ps_checkout-hosted-submit-button",
                    t.type = "button",
                    t.addEventListener("click", (function(t) {
                        t.preventDefault(),
                        e.app.children.loader.show(),
                        e.psCheckoutService.postCheckCartOrder({
                            orderID: e.payPalService.getOrderId(),
                            fundingSource: "paypal",
                            isExpressCheckout: !0
                        }, {
                            resolve: function() {},
                            reject: function() {}
                        }).then((function() {
                            return e.psCheckoutService.postValidateOrder({
                                orderID: e.payPalService.getOrderId(),
                                fundingSource: "paypal",
                                isExpressCheckout: !0
                            })
                        }
                        )).catch((function(t) {
                            console.log(t),
                            e.app.children.loader.hide(),
                            e.app.children.notification.showError(t.message)
                        }
                        ))
                    }
                    )),
                    this.app.children.conditionsCheckbox.onChange((function() {
                        t.disabled = !e.app.children.conditionsCheckbox.isChecked()
                    }
                    )),
                    this.smartButton.append(t),
                    document.querySelector('.ps_checkout-button[data-funding-source="paypal"]').append(this.smartButton)
                } else
                    this.renderPaymentOptionItems(),
                    this.renderPaymentOptionRadios();
                return this
            }
        }]) && _t(t.prototype, n),
        r && _t(t, r),
        i
    }(_e);
    function At(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    !function(e, t, n) {
        t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n
    }(xt, "INJECT", {
        config: "config",
        htmlElementService: "htmlElementService",
        payPalService: "payPalService",
        psCheckoutService: "psCheckoutService"
    });
    var Lt = function() {
        function e(t) {
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.htmlElementService = t.htmlElementService,
            this.conditionsContainer = this.htmlElementService.getConditionsCheckboxContainer(),
            this.conditionsCheckboxes = this.htmlElementService.getConditionsCheckboxes(this.conditionsContainer)
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                return this
            }
        }, {
            key: "isChecked",
            value: function() {
                return !this.conditionsContainer || 0 === this.conditionsCheckboxes.map((function(e) {
                    return e.checked
                }
                )).filter((function(e) {
                    return !e
                }
                )).length
            }
        }, {
            key: "onChange",
            value: function(e) {
                this.conditionsContainer && this.conditionsCheckboxes.forEach((function(t) {
                    return t.addEventListener("change", (function() {
                        return e()
                    }
                    ))
                }
                ))
            }
        }]) && At(t.prototype, n),
        r && At(t, r),
        e
    }();
    function Mt(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var Bt = function() {
        function e(t, n) {
            var r = this;
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.config = t,
            this.sdk = n,
            this.translationService = new pe(this.config.translations),
            this.htmlElementService = new W,
            this.payPalService = new z(this.sdk,this.config,this.translationService),
            this.psCheckoutService = new U(this.config,this.translationService),
            this.$ = function(e) {
                return r.translationService.getTranslationString(e)
            }
            ,
            this.children = {}
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                if ("module-supercheckout-supercheckout" === document.body.id && document.querySelector('[data-module-name^="ps_checkout"]')) {
                    if (void 0 === this.sdk)
                        throw new Error(this.$("error.paypal-sdk"));
                    this.children.loader = new Ot(this).render(),
                    this.children.conditionsCheckbox = new Lt(this).render(),
                    this.children.notification = new Pt(this).render(),
                    this.children.paymentOptions = new xt(this).render()
                }
            }
        }]) && Mt(t.prototype, n),
        r && Mt(t, r),
        e
    }();
    function Rt(e, t, n) {
        return t in e ? Object.defineProperty(e, t, {
            value: n,
            enumerable: !0,
            configurable: !0,
            writable: !0
        }) : e[t] = n,
        e
    }
    function Dt(e, t) {
        for (var n = 0; n < t.length; n++) {
            var r = t[n];
            r.enumerable = r.enumerable || !1,
            r.configurable = !0,
            "value"in r && (r.writable = !0),
            Object.defineProperty(e, r.key, r)
        }
    }
    var Ht, Ft = function() {
        function e(t, n) {
            var r;
            !function(e, t) {
                if (!(e instanceof t))
                    throw new TypeError("Cannot call a class as a function")
            }(this, e),
            this.instance = new ((r = {},
            Rt(r, M, wt),
            Rt(r, B, Bt),
            r)[U.getPrestashopVersion()])(t,n)
        }
        var t, n, r;
        return t = e,
        (n = [{
            key: "render",
            value: function() {
                return this.instance.render()
            }
        }]) && Dt(t.prototype, n),
        r && Dt(t, r),
        e
    }();
    Ht = function() {
        (!function() {
            if (!x.expressCheckoutHostedFieldsEnabled)
                return !1;
            switch (U.getPrestashopVersion()) {
            case M:
                return "order" === document.body.id ? document.getElementById("ps_checkout-displayPayment") : "order-opc" === document.body.id;
            case B:
                return "module-supercheckout-supercheckout" === document.body.id && document.querySelector('[data-module-name^="ps_checkout"]')
            }
        }() ? Promise.resolve("") : S.clientToken ? Promise.resolve(S.clientToken) : new U(x).postGetToken()).then((function(e) {
            new L(S,e,(function(e) {
                console.log("XXXX");
                new Ft(x,e).render(),
                new ge(x,e).render()
            }
            )).render()
        }
        )).catch((function() {
            return console.error("Token could not be retrieved")
        }
        ))
    }
    ,
    Ht();
}
]);
