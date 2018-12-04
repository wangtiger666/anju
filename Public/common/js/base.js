/*!
 * Lazy Load - jQuery plugin for lazy loading images
 *
 * Copyright (c) 2007-2015 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   http://www.appelsiini.net/projects/lazyload
 *
 * Version:  1.9.5
 *
 */

/*!
 * jQuery Validation Plugin v1.13.1
 *
 * http://jqueryvalidation.org/
 *
 * Copyright (c) 2014 Jörn Zaefferer
 * Released under the MIT license
 */

(function() {
    var e = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "+", "/"],
    t = function(e) {
        var t = new Array;
        while (e > 0) {
            var n = e % 2;
            e = Math.floor(e / 2),
            t.push(n)
        }
        return t.reverse(),
        t
    },
    n = function(e) {
        var t = 0,
        n = 0;
        for (var r = e.length - 1; r >= 0; --r) {
            var i = e[r];
            i == 1 && (t += Math.pow(2, n)),
            ++n
        }
        return t
    },
    r = function(e, t) {
        var n = 8 - (e + 1) + (e - 1) * 6,
        r = t.length,
        i = n - r;
        while (--i >= 0) t.unshift(0);
        var s = [],
        o = e;
        while (--o >= 0) s.push(1);
        s.push(0);
        var u = 0,
        a = 8 - (e + 1);
        for (; u < a; ++u) s.push(t[u]);
        for (var f = 0; f < e - 1; ++f) {
            s.push(1),
            s.push(0);
            var l = 6;
            while (--l >= 0) s.push(t[u++])
        }
        return s
    },
    i = {
        encoder: function(i) {
            var s = [],
            o = [];
            for (var u = 0,
            a = i.length; u < a; ++u) {
                var f = i.charCodeAt(u),
                l = t(f);
                if (f < 128) {
                    var c = 8 - l.length;
                    while (--c >= 0) l.unshift(0);
                    o = o.concat(l)
                } else f >= 128 && f <= 2047 ? o = o.concat(r(2, l)) : f >= 2048 && f <= 65535 ? o = o.concat(r(3, l)) : f >= 65536 && f <= 2097151 ? o = o.concat(r(4, l)) : f >= 2097152 && f <= 67108863 ? o = o.concat(r(5, l)) : f >= 4e6 && f <= 2147483647 && (o = o.concat(r(6, l)))
            }
            var h = 0;
            for (var u = 0,
            a = o.length; u < a; u += 6) {
                var p = u + 6 - a;
                p == 2 ? h = 2 : p == 4 && (h = 4);
                var d = h;
                while (--d >= 0) o.push(0);
                s.push(n(o.slice(u, u + 6)))
            }
            var v = "";
            for (var u = 0,
            a = s.length; u < a; ++u) v += e[s[u]];
            for (var u = 0,
            a = h / 2; u < a; ++u) v += "=";
            return v
        },
        decoder: function(r) {
            var i = r.length,
            s = 0;
            r.charAt(i - 1) == "=" && (r.charAt(i - 2) == "=" ? (s = 4, r = r.substring(0, i - 2)) : (s = 2, r = r.substring(0, i - 1)));
            var o = [];
            for (var u = 0,
            a = r.length; u < a; ++u) {
                var f = r.charAt(u);
                for (var l = 0,
                c = e.length; l < c; ++l) if (f == e[l]) {
                    var h = t(l),
                    p = h.length;
                    if (6 - p > 0) for (var d = 6 - p; d > 0; --d) h.unshift(0);
                    o = o.concat(h);
                    break
                }
            }
            s > 0 && (o = o.slice(0, o.length - s));
            var v = [],
            m = [];
            for (var u = 0,
            a = o.length; u < a;) if (o[u] == 0) v = v.concat(n(o.slice(u, u + 8))),
            u += 8;
            else {
                var g = 0;
                while (u < a) {
                    if (o[u] != 1) break; ++g,
                    ++u
                }
                m = m.concat(o.slice(u + 1, u + 8 - g)),
                u += 8 - g;
                while (g > 1) m = m.concat(o.slice(u + 2, u + 8)),
                u += 8,
                --g;
                v = v.concat(n(m)),
                m = []
            }
            return v
        }
    };
    window.BASE64 = i
})(),
define("lib/jbase64",
function() {}),
define("lib/jquery.lazyload", ["jquery"],
function(e) {
    e(function(e) { (function(e, t, n, r) {
            var i = e(t);
            e.fn.lazyload = function(s) {
                function f() {
                    var t = 0;
                    o.each(function() {
                        var n = e(this);
                        if (a.skip_invisible && !n.is(":visible")) return;
                        if (!e.abovethetop(this, a) && !e.leftofbegin(this, a)) if (!e.belowthefold(this, a) && !e.rightoffold(this, a)) n.trigger("appear"),
                        t = 0;
                        else if (++t > a.failure_limit) return ! 1
                    })
                }
                var o = this,
                u, a = {
                    threshold: 0,
                    failure_limit: 0,
                    event: "scroll",
                    effect: "show",
                    container: t,
                    data_attribute: "original",
                    skip_invisible: !1,
                    appear: null,
                    load: null,
                    placeholder: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"
                };
                return s && (r !== s.failurelimit && (s.failure_limit = s.failurelimit, delete s.failurelimit), r !== s.effectspeed && (s.effect_speed = s.effectspeed, delete s.effectspeed), e.extend(a, s)),
                u = a.container === r || a.container === t ? i: e(a.container),
                0 === a.event.indexOf("scroll") && u.bind(a.event,
                function() {
                    return f()
                }),
                this.each(function() {
                    var t = this,
                    n = e(t);
                    t.loaded = !1,
                    (n.attr("src") === r || n.attr("src") === !1) && n.is("img") && n.attr("src", a.placeholder),
                    n.one("appear",
                    function() {
                        if (!this.loaded) {
                            if (a.appear) {
                                var r = o.length;
                                a.appear.call(t, r, a)
                            }
                            e("<img />").bind("load",
                            function() {
                                var r = n.attr("data-" + a.data_attribute);
                                n.hide(),
                                n.is("img") ? n.attr("src", r) : n.css("background-image", "url('" + r + "')"),
                                n[a.effect](a.effect_speed),
                                t.loaded = !0;
                                var i = e.grep(o,
                                function(e) {
                                    return ! e.loaded
                                });
                                o = e(i);
                                if (a.load) {
                                    var s = o.length;
                                    a.load.call(t, s, a)
                                }
                            }).attr("src", n.attr("data-" + a.data_attribute))
                        }
                    }),
                    0 !== a.event.indexOf("scroll") && n.bind(a.event,
                    function() {
                        t.loaded || n.trigger("appear")
                    })
                }),
                i.bind("resize",
                function() {
                    f()
                }),
                /(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion) && i.bind("pageshow",
                function(t) {
                    t.originalEvent && t.originalEvent.persisted && o.each(function() {
                        e(this).trigger("appear")
                    })
                }),
                e(n).ready(function() {
                    f()
                }),
                this
            },
            e.belowthefold = function(n, s) {
                var o;
                return s.container === r || s.container === t ? o = (t.innerHeight ? t.innerHeight: i.height()) + i.scrollTop() : o = e(s.container).offset().top + e(s.container).height(),
                o <= e(n).offset().top - s.threshold
            },
            e.rightoffold = function(n, s) {
                var o;
                return s.container === r || s.container === t ? o = i.width() + i.scrollLeft() : o = e(s.container).offset().left + e(s.container).width(),
                o <= e(n).offset().left - s.threshold
            },
            e.abovethetop = function(n, s) {
                var o;
                return s.container === r || s.container === t ? o = i.scrollTop() : o = e(s.container).offset().top,
                o >= e(n).offset().top + s.threshold + e(n).height()
            },
            e.leftofbegin = function(n, s) {
                var o;
                return s.container === r || s.container === t ? o = i.scrollLeft() : o = e(s.container).offset().left,
                o >= e(n).offset().left + s.threshold + e(n).width()
            },
            e.inviewport = function(t, n) {
                return ! e.rightoffold(t, n) && !e.leftofbegin(t, n) && !e.belowthefold(t, n) && !e.abovethetop(t, n)
            },
            e.extend(e.expr[":"], {
                "below-the-fold": function(t) {
                    return e.belowthefold(t, {
                        threshold: 0
                    })
                },
                "above-the-top": function(t) {
                    return ! e.belowthefold(t, {
                        threshold: 0
                    })
                },
                "right-of-screen": function(t) {
                    return e.rightoffold(t, {
                        threshold: 0
                    })
                },
                "left-of-screen": function(t) {
                    return ! e.rightoffold(t, {
                        threshold: 0
                    })
                },
                "in-viewport": function(t) {
                    return e.inviewport(t, {
                        threshold: 0
                    })
                },
                "above-the-fold": function(t) {
                    return ! e.belowthefold(t, {
                        threshold: 0
                    })
                },
                "right-of-fold": function(t) {
                    return e.rightoffold(t, {
                        threshold: 0
                    })
                },
                "left-of-fold": function(t) {
                    return ! e.rightoffold(t, {
                        threshold: 0
                    })
                }
            })
        })(e, window, document)
    })
}),
define("common/ui", ["jquery", "lib/jquery.lazyload", "common/template"],
function(e, t, n) {
    var r = function() {
        function e(e) {
            var t = t || window.event;
            return t.target || t.srcElement
        }
        function t(e) {
            var t = e.hintEl;
            return t && g(t)
        }
        function n(t) {
            var n = e(t);
            if (!n || n.tagName !== "INPUT" && n.tagName !== "TEXTAREA") return;
            var r = n.__emptyHintEl;
            r && (n.value ? r.style.display = "none": r.style.display = "")
        }
        function r(t) {
            var n = e(t);
            if (!n || n.tagName !== "INPUT" && n.tagName !== "TEXTAREA") return;
            var r = n.__emptyHintEl;
            r && (r.style.display = "none")
        }
        if ("placeholder" in document.createElement("input")) return;
        document.addEventListener ? (document.addEventListener("focus", r, !0), document.addEventListener("blur", n, !0)) : (document.attachEvent("onfocusin", r), document.attachEvent("onfocusout", n));
        var i = [document.getElementsByTagName("input"), document.getElementsByTagName("textarea")];
        for (var s = 0; s < 2; s++) {
            var o = i[s];
            for (var u = 0; u < o.length; u++) {
                var a = o[u],
                f = a.getAttribute("placeholder"),
                l = a.__emptyHintEl;
                f && !l && (l = document.createElement("strong"), l.innerHTML = f, l.className = "placeholder", l.onclick = function(e) {
                    return function() {
                        try {
                            e.focus()
                        } catch(t) {}
                    }
                } (a), a.value && (l.style.display = "none"), a.parentNode.insertBefore(l, a), a.__emptyHintEl = l)
            }
        }
    },
    i = function() {
        if (window.g_config === undefined || window.g_config.disableRightFlex === undefined || window.g_config.disableRightFlex) {
            if (e(".pop-right-info").length == 0) {
                var t = '<div class="pop-right-info"><a class="J-Icon-Tab" href="http://pft.zoosnet.net/LR/Chatpre.aspx?id=PFT91798111&lng=cn" target="_blank"><i class="share-icon-zixun"></i><br>在线咨询</a><a class="J-Icon-Tab" href="http://www.fuwo.com/baojia/" target="_blank"><i class="share-icon-baojia-list"></i><br>智能报价</a><a class="J-Icon-Tab J-Show-Weixin" href="javascript:void(0);"><i class="share-icon-guanzhu"></i><br>关注有礼</a></div>';
                e("body").append(t)
            }
            if (window.g_config === undefined || window.g_config.disableBackTop === undefined || window.g_config.disableBackTop) e(document).scrollTop() <= 100 ? e(".J-Return-Top").remove() : (e(".J-Return-Top").remove(), e(".pop-right-info").append('<a class="J-Icon-Tab J-Return-Top" href="javascript:void(0);"><i class="share-icon-zhiding"></i><br>回到顶部</a>'));
            e(document).delegate(".J-Return-Top", "click",
            function() {
                e(document).scrollTop(0)
            })
        }
    },
    s = function() {
        if (window.g_config == undefined || window.g_config.staticHost == undefined) window.g_config.staticHost = "";
        e("img.lazy").lazyload({
            effect: "fadeIn",
            placeholder: "http://" + window.g_config.staticHost + "/Public/bootstrap/img/placeholder.jpg",
            data_attribute: "lazyload",
            failure_limit: 10
        })
    },
    o = function(t, n, r) {
        e(".J-Auth-Expired").remove();
        var i = n == "success" ? "share-icon-tip": "share-icon-warning",
        s = '<div class="small-error J-Auth-Expired"><i class="' + i + '"></i> <span>' + t + "</span></div>";
        e("body").append(s);
        var o = parseInt(e(".J-Auth-Expired").css("width")) + 1,
        u = parseInt(e(".J-Auth-Expired").css("height")),
        a = -o / 2,
        f = -u / 2 - 200;
        e(".J-Auth-Expired").css({
            height: u,
            marginLeft: a,
            marginTop: f,
            width: o
        }),
        setTimeout(function() {
            r ? r() : e(".J-Auth-Expired").remove()
        },
        2e3)
    },
    u = function() {
        if (window.g_config == undefined || window.g_config.staticHost == undefined) window.g_config.staticHost = "";
        var t = '<div class="container-fluid web-notice"><div class="container web-content-notice" id="fuwo_notice"><img src="/Public/bootstrap/img/ring.gif">网站维护公告：  各位用户，本网站定于2014年3月29日12:00至2014年3月31日19:00进行网站维护；届时，本网站的访问和部分服务可能受到影响，敬请谅解。<span class="close-notice" id="fuwo_notice_close">x</span></div></div>';
        window.g_config = window.g_config || {},
        window.g_config.disableWeihuNotice && (e("#header").find(".navbar").before(t), e("#fuwo_notice_close").click(function() {
            e(".web-notice").remove()
        }))
    },
    a = function() {
        window.g_config = window.g_config || {};
        if (window.g_config == undefined || window.g_config.staticHost == undefined) window.g_config.staticHost = "";
        if (window.g_config.disableActivityNotice == undefined || !window.g_config.disableActivityNotice) if (jQuery.cookie("fubiActivity") === "0" || jQuery.cookie("fubiActivity") === null) {
            var t = '<div class="screen-bg"></div><div class="fubi-activity"><a class="activity-close-btn J-Close" href="javascript:;"></a><img src="/Public/bootstrap/img/fubi_activity.png"><a class="fubi-activity-link" href="http://www.fuwo.com/activity/lottery/" target="_blank">点击火速开抢</a></div>';
            e("body").append(t),
            jQuery.cookie("fubiActivity", "1", {
                path: "/",
                expires: 1
            }),
            e(".screen-bg, .J-Close, .fubi-activity-link").click(function() {
                e(".screen-bg").remove(),
                e(".fubi-activity").remove()
            })
        }
    },
    f = function(e) {
        var t, n = navigator.appName,
        r = navigator.appVersion;
        n === "Microsoft Internet Explorer" && r.indexOf("MSIE 6.0") > -1 ? t = 6 : n === "Microsoft Internet Explorer" && r.indexOf("MSIE 7.0") > -1 && (t = 7);
        if (t < 8) {
            e();
            return
        }
    };
    return e(function(e) {
        r(),
        e(".navbar-nav li").on("mouseenter",
        function() {
            e(this).addClass("tmp-active"),
            e(this).find(".sub-nav").css("display", "block"),
            e(this).find("i").attr("class", "share-icon-up")
        }).on("mouseleave",
        function() {
            e(this).removeClass("tmp-active"),
            e(this).find(".sub-nav").css("display", "none"),
            e(this).find("i").attr("class", "share-icon-down")
        }),
        e(".js_navbar>ul>li").on("mouseenter",
        function() {
            e(this).is(".border-navbottom") ? e(".container-fluid .header-border").css("display", "block") : e(".container-fluid .header-border").css("display", "none")
        }).on("mouseleave",
        function() {
            e(".container-fluid .header-border").css("display", "none")
        }),
        e(".link-fold").click(function(t) {
            t.preventDefault(),
            e(this).find("i").attr("class") === "share-show" ? (e(this).parent().addClass("link-unfold"), e(this).find("i").attr("class", "share-icon-down ")) : (e(this).parent().removeClass("link-unfold"), e(this).find("i").attr("class", "share-show"))
        }),
        i(),
        e(window).scroll(function() {
            i()
        }),
        e(window).resize(function() {
            i()
        }),
        e(document).delegate(".J-Icon-Tab", "mousemove",
        function() {
            var t = e(this),
            n = t.find("i").attr("class");
            if (n.indexOf("act") > 0) return;
            t.find("i").attr("class", n + "-act")
        }).delegate(".J-Icon-Tab", "mouseleave",
        function() {
            var t = e(this),
            n = t.find("i").attr("class"),
            r = n.substr(0, n.length - 4);
            t.find("i").attr("class", r)
        }),
        e(".J-Show-Weixin").bind({
            mouseenter: function() {
                var t = "<i class='share-weixin J-Close-Weixin' style='display:inline-block;position:absolute;left:-120px;top:98px'></i>";
                e(".pop-right-info").append(t)
            },
            mouseleave: function() {
                e(".J-Close-Weixin").remove()
            }
        });
        if (window.baiduRequired && window.baiduSrc) {
            var t = t || [],
            n = document.createElement("script");
            n.src = window.baiduSrc;
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(n, s)
        }
        if (window.tongjiRequired && window.tongjiSrc) {
            var t = t || [];
            t.push(["setAccountId", "84b178f4f8ac5344"]);
            var n = document.createElement("script");
            n.src = window.tongjiSrc;
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(n, s)
        }
        var o;
        e(".J-Pop-Parent, .J-Pop-Child").mousemove(function() {
            e(".J-Pop-Child").show(),
            clearTimeout(o)
        }),
        e(".J-Pop-Child").mouseleave(function() {
            e(this).hide()
        }),
        e(".J-Pop-Parent").mouseleave(function(t) {
            var n = e(".J-Pop-Parent,.J-Pop-Child");
            o = setTimeout(function() {
                n.has(t.target).length === 0 && e(".J-Pop-Child").hide()
            },
            200)
        }),
        e(document).delegate(".J-Ifuwo-Insert,.J-Ifuwo-Beauty", "click",
        function() {
            var t = e(this);
            t.hasClass("active") ? t.removeClass("active") : t.addClass("active")
        }),
        u(),
        a()
    }),
    {
        alert: function(t, r) {
            var i = e("#message"),
            s = 0;
            className = "message-success";
            switch (t) {
            case "info":
                className = "message-info";
                break;
            case "waring":
                className = "message-waring";
                break;
            case "error":
                className = "message-error";
                break;
            default:
                className = "message-success"
            }
            if (i.length === 0) {
                var o = {
                    classname: className,
                    message: r,
                    top: s
                };
                e("body").append(n("common/popup/message", o)),
                e(".message-close").click(function() {
                    e(this).parent().remove()
                }),
                setTimeout(function() {
                    i = e("#message"),
                    i.removeClass("message").addClass("message-out"),
                    setTimeout(function() {
                        i.remove()
                    },
                    200)
                },
                3e3)
            } else i.css("top", s),
            i.attr("class", "message " + className),
            e("#message .message-content").text(r)
        },
        confirm: function(t, r, i, s, o, u, a) {
            var l = {
                title: t,
                content: r,
                width: (i ? i: "400") + "px",
                height: (s ? s: "220") + "px",
                marginTop: parseInt( - (s ? s: 220) / 2) + "px",
                marginLeft: parseInt( - (i ? i: 400) / 2) + "px"
            };
            e("body").append(n("common/popup/confirm", l)),
            f(function() {
                e(".J-Ie7").show()
            }),
            e(".pop-close, .pop .btn").click(function() {
                var t = e(this).data("op");
                t === "confirm" && o ? o(a) : t === "cancel" && u && u(),
                e(".screen-bg").remove(),
                e(".pop").remove()
            })
        },
        placeholder: function() {
            r()
        },
        lazy: function() {
            s()
        },
        submitResult: function(t, r, i, s) {
            e("#submit_result").empty();
            var o = {
                title: t,
                content: r,
                btnList: i,
                skip: s
            };
            e("#submit_result").append(n("common/submit_result", o));
            var u = 5,
            a = null;
            e("#count-down").text(u),
            e(window).scrollTop(0),
            a = setInterval(function() {
                u--,
                e("#count-down").text(u),
                u === 0 && (clearInterval(a), s && (location.href = s.url))
            },
            1e3)
        },
        tipHandler: function(e, t, n) {
            o(e, t, n)
        },
        ieVersion: function(e) {
            f(e)
        }
    }
}),
define("common/services", ["jquery", "common/ui"],
function(e, t) {
    var n = "",
    r = function() {},
    i = function() {},
    s = function(e, n, r) {
        e.code ? n && n(e.code, e.msg, e.data || {},
        r) : t.alert("error", "服务出现异常！")
    },
    o = function() {
        n = "",
        r(),
        t.alert("error", "404：服务器没有响应！")
    },
    u = function() {
        n = "",
        r(),
        t.alert("error", "500：服务器出错！")
    },
    a = function() {
        n = "",
        r(),
        t.alert("error", "504：服务器超时！")
    },
    f = function() {
        n = "",
        r(),
        t.alert("error", "302：接口发生了跳转！")
    },
    l = function() {
        t.alert("waring", "正在处理上一个请求！")
    };
    return {
        DEBUG: location.host.indexOf(".fuwo.com") !== -1 ? !1 : !0,
        HOST: "http://www.fuwo.com",
        CODE_SUCC: "10000",
        CODE_ERROR: "10001",
        CODE_PARAM_ERR: "10002",
        CODE_NOT_EXIST_ERR: "10003",
        CODE_PERM_ERR: "10004",
        CODE_EXIST_ERR: "10006",
        CODE_VERIFI_ERROR: "10012",
        CODE_NOT_LOGIN: "11004",
        CODE_ACCOUNT_NOT_EXIST: "11000",
        post: function(t, c, h, p, d) {
            var v = t;
            if (n === v) {
                l();
                return
            }
            var m = t;
            window.g_config = window.g_config || {},
            window.g_config.serviceHost != undefined && (m.indexOf("http:") >= 0 ? m = m: m = window.g_config.serviceHost == location.host ? m: "http://" + window.g_config.serviceHost + m),
            m !== "" && (n = v, i(), e.ajax({
                url: m,
                dataType: "json",
                type: "POST",
                async: d === undefined ? !0 : d,
                data: c,
                xhrFields: window.g_config.serviceHost == undefined || window.g_config.serviceHost == location.host ? "": {
                    withCredentials: !0
                },
                success: function(e) {
                    n = "",
                    r(),
                    s(e, h, p)
                },
                error: function() {
                    n = "",
                    r()
                },
                statusCode: {
                    404 : function() {
                        o()
                    },
                    500 : function() {
                        u()
                    },
                    504 : function() {
                        a()
                    },
                    302 : function() {
                        f()
                    }
                }
            }))
        },
        get: function(t, c, h, p, d) {
            var v = t;
            if (n === v) {
                l();
                return
            }
            var m = t;
            window.g_config = window.g_config || {},
            window.g_config.serviceHost != undefined && (m.indexOf("http") >= 0 ? m = m: m = window.g_config.serviceHost == location.host ? m: "http://" + window.g_config.serviceHost + m),
            m !== "" && (n = v, i(), e.ajax({
                url: m,
                dataType: "json",
                type: "GET",
                async: d === undefined ? !0 : d,
                data: c,
                xhrFields: window.g_config.serviceHost == undefined || window.g_config.serviceHost == location.host ? "": {
                    withCredentials: !0
                },
                success: function(e) {
                    n = "",
                    r(),
                    s(e, h, p)
                },
                error: function() {
                    n = "",
                    r()
                },
                statusCode: {
                    404 : function() {
                        o()
                    },
                    500 : function() {
                        u()
                    },
                    504 : function() {
                        a()
                    },
                    302 : function() {
                        f()
                    }
                }
            }))
        }
    }
}),
define("common/utils", ["jquery"],
function($) {
    Array.prototype.indexOf || (Array.prototype.indexOf = function(e) {
        var t = this.length >>> 0,
        n = Number(arguments[1]) || 0;
        n = n < 0 ? Math.ceil(n) : Math.floor(n),
        n < 0 && (n += t);
        for (; n < t; n++) if (n in this && this[n] === e) return n;
        return - 1
    }),
    Array.prototype.remove = function(e) {
        var t = this.indexOf(e);
        t > -1 && this.splice(t, 1)
    };
    var _toQueryPair = function(e, t) {
        return typeof t == "undefined" ? e: e + "=" + encodeURIComponent(t === null ? "": String(t))
    };
    return {
        reMobileEmail: /^(1[34578]\d{9}|[a-zA-Z0-9_\.\-]+@(([a-zA-Z0-9])+\.)+([a-zA-Z0-9]{2,4}))$/,
        reMobile: /^(1[34578]\d{9})$/,
        reEmail: /^[a-zA-Z0-9_\.\-]+@(([a-zA-Z0-9])+\.)+([a-zA-Z0-9]{2,4})$/,
        subStr: function(e, t) {
            return e.length > t ? e.substr(0, parseInt(t)) + "...": e
        },
        strTrim: function(e) {
            return e.replace(/(^\s+)|(\s+$)/g, "")
        },
        parseURIParams: function(e) {
            var t = {},
            n, r = /\+/g,
            i = /([^&=]+)=?([^&]*)/g,
            s = function(e) {
                return decodeURIComponent(e.replace(r, " "))
            };
            while (n = i.exec(e)) t[s(n[1])] = s(n[2]);
            return t
        },
        objToQuery: function(e) {
            var t = [];
            for (var n in e) {
                n = encodeURIComponent(n);
                var r = e[n];
                if (r && r.constructor === Array) {
                    var i = [];
                    for (var s = 0,
                    o = r.length,
                    u; s < o; s++) u = r[s],
                    i.push(_toQueryPair(n, u));
                    t = t.concat(i)
                } else t.push(_toQueryPair(n, r))
            }
            return t.join("&")
        },
        parseLocation: function(e) {
            var t = location.search;
            if (t !== "") {
                var n = this.parseURIParams(t.substr(1));
                return n[e] || ""
            }
            return ""
        },
        b64EncodeUrl: function(e) {
            return window.BASE64 ? BASE64.encoder(e.replace("风格", "")).replace("+", "-").replace("/", "_").replace("=", "") : e
        },
        prettyDate: function(e) {
            var t = new Date((e || "").replace(/-/g, "/").replace(/[TZ]/g, " ")),
            n = ((new Date).getTime() - t.getTime()) / 1e3,
            r = Math.floor(n / 86400);
            if (isNaN(r) || r < 0) return;
            return r >= 31 ? e: r === 0 && (n < 60 && "刚刚" || n < 120 && "1分钟前" || n < 3600 && Math.floor(n / 60) + "分钟前" || n < 7200 && "1个小时前" || n < 86400 && Math.floor(n / 3600) + "小时前") || r === 1 && "昨天" || r < 7 && r + "天前" || r < 31 && Math.ceil(r / 7) + "周前"
        },
        changeURLArg: function(url, arg, arg_val) {
            url.indexOf("#") && (url = url.split("#")[0]);
            var pattern = arg + "=([^&]*)",
            replaceText = arg + "=" + arg_val;
            if (url.match(pattern)) {
                var tmp = "/(" + arg + "=)([^&]*)/gi";
                return tmp = url.replace(eval(tmp), replaceText),
                tmp
            }
            return url.match("[?]") ? url + "&" + replaceText: url + "?" + replaceText
        },
        locationUrl: function(e) {
            var t = window.open();
            return t.location = e
        },
        pageCount: function(e, t) {
            var n = Math.floor(e / t),
            r = e % t;
            return r > 0 && (n += 1),
            n
        },
        formatCurrency: function(e, t) {
            t = t || "";
            var n = e.toString().replace(/\$|\,/g, ""),
            r;
            isNaN(n) && (n = "0"),
            r = n == (n = Math.abs(n)),
            n = Math.floor(n * 100 + .50000000001),
            cents = n % 100,
            n = Math.floor(n / 100).toString(),
            cents < 10 && (cents = "0" + cents);
            for (var i = 0; i < Math.floor((n.length - (1 + i)) / 3); i++) n = n.substring(0, n.length - (4 * i + 3)) + t + n.substring(n.length - (4 * i + 3));
            return (r ? "": "-") + n + "." + cents
        },
        uriNext: function(e) {
            return uriObj = this.parseURIParams(location.search.substr(1)),
            uriObj.next || e || ""
        },
        optimizeUrl: function(e) {
            var t = new RegExp("<[^>]*>", "gi");
            return e = e.replace(t, ""),
            e
        },
        isEmail: function(e) {
            return this.reEmail.test(e)
        },
        checkContentUrl: function(e) {
            var t = "fuwo",
            n = !1,
            r, i = new RegExp("(http[s]{0,1}|ftp)?(:)?(//)?[a-zA-Z0-9\\.\\-]+\\.([a-zA-Z]{2,4})(:\\d+)?(/[a-zA-Z0-9\\.\\-~!@#$%^&*+?:_/=<>]*)?", "gi"),
            s = new RegExp(".+.(png|PNG|jpg|JPG|bmp|gif|GIF)$");
            if (e.match(i) === null) return ! 0;
            var o = e.match(i) === null ? "": e.match(i).toString(),
            u = [];
            u = o.split(",");
            if (u !== "") for (var a = 0; a < u.length; a++) {
                u[a] = this.optimizeUrl(u[a]);
                if (!s.test(u[a]) && !this.isEmail(u[a])) {
                    r = u[a].indexOf(t) >= 0 ? !0 : !1;
                    if (!r) {
                        n = !0;
                        break
                    }
                }
            }
            return n ? !1 : !0
        },
        checkUrl: function(e) {
            return this.checkContentUrl(e) === !1 ? (alert("发布内容中包含非本站点链接，请检查您的发布内容！"), !1) : !0
        },
        changeAnchorColor: function(e) {
            var t = new RegExp("<a", "gi");
            return e.replace(t, '<a style="color:rgb(120,120,200)"')
        },
        ucFirst: function(e) {
            return e.substring(0, 1).toUpperCase() + e.substring(1)
        },
        parseUrl: function(e) {
            var t = {
                protocol: /([^\/]+:)\/\/(.*)/i,
                host: /(^[^\:\/]+)((?:\/|:|$)?.*)/,
                port: /\:?([^\/]*)(\/?.*)/,
                pathname: /([^\?#]+)(\??[^#]*)(#?.*)/
            },
            n,
            r = {};
            r.href = e;
            for (p in t) n = t[p].exec(e),
            r[p] = n[1],
            e = n[2],
            e === "" && (e = "/"),
            p === "pathname" && (r.pathname = n[1], r.search = n[2], r.hash = n[3]);
            return r
        },
        getStyleTime: function(e) {
            function o(e) {
                return parseInt(e) < 10 && (e = "0" + e),
                e
            }
            var t = new Date(e * 1e3),
            n = t.getFullYear(),
            r = t.getMonth() + 1,
            i = t.getDate(),
            s = n + "-" + o(r) + "-" + o(i);
            return s
        }
    }
}),
define("message/utils/api", ["jquery", "common/ui", "common/services"],
function(e, t, n) {
    var r = "/message/fresh/",
    i = "/message/read/",
    s = "/message/delete/";
    return {
        fresh: function(e) {
            n.get(r, {},
            function(r, i, s) {
                r === n.CODE_SUCC ? e && e(r, i, s) : t.alert("error", i)
            })
        },
        read: function(e, r) {
            n.post(i, e,
            function(e, i, s) {
                e === n.CODE_SUCC ? r && r(e, i, s) : t.alert("error", i)
            })
        },
        del: function(e, r) {
            n.post(s, e,
            function(e, i, s) {
                e === n.CODE_SUCC ? r && r(e, i, s) : t.alert("error", i)
            })
        }
    }
}),
define("tagging/utils/api", ["jquery", "common/ui", "common/services"],
function(e, t, n) {
    var r = "/tagging/add/",
    i = "/tagging/update/";
    return {
        add: function(e, i, s, o, u) {
            n.post(r, {
                app_label: e,
                model: i,
                object_id: s,
                tag: o
            },
            function(e, r, i) {
                e === n.CODE_SUCC && (u ? u(e, r, i) : t.alert("error", r))
            })
        },
        update: function(e, r, s, o, u) {
            n.post(i, {
                tags: o,
                object_ids: s,
                app_label: e,
                model: r
            },
            function(e, r, i) {
                e === n.CODE_SUCC && (u ? u(e, r, i) : t.alert("error", r))
            })
        }
    }
}),
define("verifycode/utils/api", ["jquery", "common/services"],
function(e, t) {
    var n = "/verifycode/mobile/create/",
    r = "/verifycode/email/create/";
    return {
        mobileCreate: function(e, r) {
            t.post(n, e,
            function(e, t, n) {
                r && r(e, t, n)
            })
        },
        emailCreate: function(e, n) {
            t.post(r, {
                email: e
            },
            function(e, t, r) {
                n && n(e, t, r)
            })
        }
    }
}),
define("common/weixin", ["jquery", "common/services"],
function(e, t) {
    var n = "/weixin/qrcode/ticket/",
    r = "/weixin/listen/login/",
    i = "/weixin/listen/bind/",
    s, o, u, a, f, l, c, h, p = 1,
    d = 2,
    v = function(e) {
        var r = Math.random();
        t.get(n, {
            t: r,
            scene_id: o
        },
        function(n, r, i) {
            n === t.CODE_SUCC && (e ? e(i) : g(i))
        })
    },
    m = function(e) {
        if (a) {
            a(e);
            return
        }
    },
    g = function(t) {
        var n = t.ticket;
        if (f) {
            f(n);
            return
        }
        e("#weixin_loading").remove(),
        e("#weixin_img_code").attr("src", "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" + n).show(),
        w(n),
        S()
    },
    y = function(t) {
        var n = t.ticket;
        l ? l(n) : (e("#weixin_refresh_code").hide(), e("#open_weixin .J-Failure").hide(), e("#open_weixin .J-Tip").show(), e("#weixin_img_code").attr("src", "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" + n).show()),
        w(n),
        S()
    },
    b = null,
    w = function(e) {
        var n, s;
        switch (o) {
        case p:
            s = r;
            break;
        case d:
            s = i
        }
        b = setInterval(function() {
            n = Math.random(),
            t.get(s, {
                ticket: e,
                t: n
            },
            function(e, n, r) {
                e === t.CODE_SUCC && (m(r), clearInterval(b))
            })
        },
        3e3)
    },
    E = null,
    S = function() {
        E && clearTimeout(E),
        E = setTimeout(function() {
            c ? c() : (e("#weixin_refresh_code").show(), e("#open_weixin .J-Failure").show(), e("#open_weixin .J-Tip").hide(), clearInterval(b))
        },
        6e4)
    },
    x = function() {
        v(y)
    },
    T = function(t) {
        if (h) {
            h();
            return
        }
        var n = e("#weixin_img_code"),
        r = e("#weixin_img_codeh"),
        i = e("#weixin_loading");
        e("#weixin_tip_help").hover(function() {
            e(this).hasClass("active") ? (e(this).removeClass("active"), n.show(), r.hide()) : (e(this).addClass("active"), n.hide(), r.show(), i.hide())
        }),
        e("#weixin_refresh_code").click(function() {
            x()
        })
    },
    N = '<div id="open_weixin" class="open-weixin"><div class="qr-code"><div class="qr-img"><div class="qr-refresh-mask"><img id="weixin_loading" src="/Public/bootstrap/img/loading.gif">' + '<img id="weixin_refresh_code" class="icon-refresh-a" style="display: none" src="/Public/bootstrap/img/icon-refresh-a.png"/>' + '<img id="weixin_img_code" style="display: none" width="222" heigth="222"/>' + '<img id="weixin_img_codeh" style="display:none;" src="/Public/bootstrap/img/img_code_h.jpg"/>' + "</div></div>" + '<div class="tip-text J-Tip"><p class="til">微信扫码，快速登录</p>' + '<a class="tip-help" id="weixin_tip_help" href="javascript:;">使用帮助</a></div>' + '<div class="failure-text J-Failure"><p class="qricon failure-icon"></p>' + '<p class="tila">二维码失效</p><p class="tilb">您可点击刷新二维码</p></div><div>';
    return {
        SCENE_LOGIN: p,
        SCENE_BIND: d,
        config: function(e) {
            this.destroy(),
            e.dom && (s = e.dom, o = e.scene || p, u = e.template, a = e.successHandler, f = e.requestHandler, l = e.refreshHandler, c = e.expireHandler, h = e.bindEvent)
        },
        render: function() {
            if (s) {
                if (u) s.html(u);
                else {
                    if (window.g_config == undefined || window.g_config.staticHost == undefined) window.g_config.staticHost = "";
                    s.html(N)
                }
                T(),
                v()
            } else console.log("Component need wrap dom. Please set with config.")
        },
        refresh: function() {
            clearInterval(b),
            clearTimeout(E),
            x()
        },
        reset: function() {
            clearInterval(b),
            clearTimeout(E),
            v(y)
        },
        clear: function() {
            clearInterval(b),
            clearTimeout(E)
        },
        destroy: function() {
            s = null,
            o = null,
            u = null,
            a = null,
            f = null,
            l = null,
            c = null,
            h = null,
            clearInterval(b),
            clearTimeout(E)
        }
    }
}),
define("account/main", ["jquery", "common/utils", "common/ui", "common/services", "common/weixin", "account/utils/api", "message/utils/api", "verifycode/utils/api", "common/template"],
function(e, t, n, r, i, s, o, u, a) {
    var f = !1,
    l = !1,
    c = null,
    h = null,
    p = function() {
        var e = jQuery.cookie("userinfo");
        return e !== null && (e = decodeURI(e), c = t.parseURIParams(e), l = !0, v()),
        c
    },
    d = function(e) {
        if (f === !1 && c == null && l === !1) return;
        if (h !== null && e) {
            e(h);
            return
        }
        s.getUserDetail(c.user_id,
        function(t) {
            h = t,
            e && e(t)
        })
    },
    v = function() {
        if (l === !1) return;
        var n = c,
        i, s = '<span class="on"><a href="http://www.fuwo.com/uc/">' + t.subStr(n.user_name, 8) + ' <i class="icon-triangle-down"></i></a><ul><li><a href="http://www.fuwo.com/uc/service/">我的需求</a></li><li><a href="http://www.fuwo.com/uc/tu/">我的效果图</a></li><li><a href="http://www.fuwo.com/uc/topic/">我的帖子</a></li><li><a href="http://www.fuwo.com/uc/notice/" id="on_notice">我的消息</a></li><li><a href="http://3d.fuwo.com/iyun/mydesign/" target="_blank">我的设计</a></li><li><a href="http://www.fuwo.com/uc/setting/">帐号设置</a></li><li><a href="javascript:void(0);" id="to-logout">退出</a></li></ul></span> ';
        e(".header-top").find("label").find("span").remove(),
        e(".header-top").find("label").prepend(s),
        i = setTimeout(function() {
            o.fresh(function(t, n, s) {
                t == r.CODE_SUCC ? (s.total > 0 && (e(".on").prepend("<i class='on-radius'></i>"), e("#on_notice").append("<b>" + s.total + "</b>")), clearTimeout(i)) : clearTimeout(i)
            })
        },
        3e3)
    },
    m = function(t, o) {
        var u = function(s) {
            if (o) {
                i.destroy(),
                o(s);
                return
            }
            n.alert("success", "登录成功！"),
            p(),
            t && t(r.CODE_SUCC, s),
            i.destroy(),
            e(".J-Mask-Bg, .J-Mark-Close").remove(),
            e("#sign_wrap").remove(),
            location.reload()
        },
        a = e(".tab-opt"),
        f = e(".tab-panel");
        a.click(function() {
            var t = a.index(e(this));
            a.removeClass("active"),
            e(this).addClass("active"),
            f.eq(t).show().siblings().hide(),
            t == 0 ? i.reset() : i.clear()
        });
        var l = e("#sign_remember"),
        c = !!jQuery.cookie("fw_user");
        e("#sign_remember").prop("checked", c),
        l.prop("checked") && (e("#sign_username").val(jQuery.cookie("fw_user")), e(".input").find(".placeholder").hide());
        var h = function() {
            l.prop("checked") ? jQuery.cookie("fw_user", e("#sign_username").val(), {
                path: "/"
            }) : jQuery.cookie("fw_user", null, {
                path: "/"
            })
        };
        i.config({
            dom: e(".sign-wrap .weixin-panel"),
            scene: i.SCENE_LOGIN,
            successHandler: u
        }),
        i.render(),
        n.placeholder();
        var d = e("#login_form").validate({
            ignore: "",
            rules: {
                username: {
                    required: !0,
                    userName: !0
                },
                pwd: {
                    required: !0,
                    passWord: !0
                }
            },
            messages: {
                username: {
                    required: "请使用手机号或邮箱登录",
                    userName: "请填写正确的邮箱或手机号"
                },
                pwd: {
                    required: "请填写密码",
                    passWord: "密码为6-20个字符"
                }
            },
            errorPlacement: function(e, t) {
                e.appendTo(t.closest(".inputline p"))
            },
            submitHandler: function() {
                
            }
        });
        $("#sign_submit").click(function(t) {
            $("#login_form").submit();
        }),
        e("#sign_username").keypress(function(t) {
            t.which == 13 && e("#sign_pwd").focus()
        }),
        e("#sign_pwd").keypress(function(t) {
            t.which == 13 && e("#sign_submit").focus().click()
        }),
        e(".J-Open").on("click",
        function(t) {
            var n = e(this).data("open");
            switch (n) {
            case "qq":
                location.href = "http://www.fuwo.com/account/qz/req_auth/?next=" + location.href;
                break;
            case "weibo":
                location.href = "http://www.fuwo.com/account/sina/req_auth/?next=" + location.href;
                break;
            case "taobao":
                location.href = "http://www.fuwo.com/account/taobao/req_auth/?next=" + location.href;
                break;
            case "sign":
                location.href = "http://www.fuwo.com/sign/signup/?next=" + location.href
            }
        })
    },
    g = function(t) {
        if (e("#sign_wrap").length > 0) return;
        if (window.g_config == undefined || window.g_config.staticHost == undefined) window.g_config.staticHost = "";
        e("body").append('<div class="screen-bg J-Mask-Bg"></div>'),
        e("body").append('<a href="javascript:;" class="mark-close J-Mark-Close"><img src="/Public/bootstrap/img/mask-close.png" /></a>'),
        e("body").append(a("account/login")),
        m(t),
        e(".J-Mask-Bg, .J-Mark-Close").on("click",
        function(t) {
            i.destroy(),
            e(".J-Mask-Bg, .J-Mark-Close").remove(),
            e("#sign_wrap").remove()
        })
    };
    return e(function(e) {
        e("#to-login").click(function(e) {
            e.preventDefault(),
            l !== !0 && (location.pathname.indexOf("/account/") !== -1 ? location.href = "http://www.fuwo.com/sign/signin/": location.href = "http://www.fuwo.com/sign/signin/?next=" + encodeURI(location.href))
        }),
        e("#to-register").click(function(e) {
            e.preventDefault(),
            location.pathname.indexOf("/account/") !== -1 ? location.href = "http://www.fuwo.com/sign/signup/": location.href = "http://www.fuwo.com/sign/signup/?next=" + encodeURI(location.href)
        }),
        e(document).delegate("#to-logout", "click",
        function(e) {
            e.preventDefault(),
            f === !0 ? (jQuery.cookie("userinfo", null), location.reload()) : location.href = "/account/signout/?next=" + encodeURI(location.href)
        }),
        c === null && p(),
        window.loginRequired = function(e) {
            e = e ||
            function() {},
            l === !0 ? e() : g(e)
        }
    }),
    {
        userInfo: function() {
            return c === null ? p() : c
        },
        getUserDetail: function(e) {
            d(e)
        },
        destoryUserDetail: function() {
            h = null
        },
        isLogin: function() {
            var e = jQuery.cookie("userinfo");
            return e !== null ? !0 : !1
        },
        login: function(e) {
            g(e)
        },
        renderLogin: function(e, t) {
            m(e, t)
        },
        loginRequired: function(e) {
            e = e ||
            function() {},
            l === !0 ? e() : g(e)
        },
        updateCookie: function(n, r) {
            c !== null && (c[n] = r, (n === "avatar" || n === "user_name") && e(".js_user_nav").html('<a href="javascript:void(0);"><img class="img-32" src="' + c.avatar + '"></a> ' + t.subStr(c.user_name)), h.avatar = r, jQuery.cookie("userinfo", t.objToQuery(c), {
                domain: "fuwo.com",
                path: "/"
            }))
        },
        verifycode: function(t, i, s, o) {
            var f, l, c, h;
            f = Math.random(),
            l = "http://www.fuwo.com/verifycode/image/show/?code_type=word&width=75&height=30&t=" + f,
            c = t.offset().left + "px",
            h = t.offset().top + 50 + "px";
            var p = {
                istriangle: !0,
                direction: !0,
                classname: "verifycode",
                left: c,
                top: h,
                content: a("common/verification")
            };
            if (e(".float-window").length > 0) return;
            e("body").append(a("common/popup/float_window", p)),
            e("#verifycode").attr("src", l),
            e("#verifycode,#js-refresh-code").click(function() {
                f = Math.random(),
                l = "http://www.fuwo.com/verifycode/image/show/?code_type=word&width=75&height=30&t=" + f,
                e("#verifycode").attr("src", l)
            }),
            e(".js-confirm").click(function() {
                e("#verification").val() === "" ? o ? o("请填写验证码") : n.alert("error", "请填写验证码") : u.mobileCreate({
                    mobile: i,
                    verifycode: e("#verification").val()
                },
                function(t, i, u) {
                    o || e(".float-window").remove(),
                    t === r.CODE_SUCC ? s && s(t, i, u) : o ? o(i) : n.alert("error", i)
                })
            })
        }
    }
}),
define("favorite/utils/api", ["require", "jquery", "common/ui", "common/services", "account/main"],
function(e, t, n, r, i) {
    var s = "/favorite/api/add/",
    o = "/favorite/api/delete/";
    return {
        fav: function(e, t, o, u) {
            i.loginRequired(function() {
                r.post(s, {
                    app_label: e,
                    model: t,
                    object_id: o
                },
                function(e, t, i) {
                    e === r.CODE_SUCC ? (n.alert("success", "收藏成功"), u && u(e, t, i)) : e === r.CODE_EXIST_ERR ? (n.alert("waring", "您已经收藏过了"), u && u(e, t, i)) : n.alert("error", t)
                })
            })
        },
        unFav: function(e, t, s, u) {
            i.loginRequired(function() {
                n.confirm("取消收藏", "你确定要取消收藏吗？", 350, 200,
                function() {
                    r.post(o, {
                        app_label: e,
                        model: t,
                        object_id: s
                    },
                    function(e, t, i) {
                        e === r.CODE_SUCC ? (n.alert("success", "取消成功"), u && u(e, t, i)) : n.alert("error", t)
                    })
                })
            })
        }
    }
}),
define("comment/utils/api", ["jquery", "common/ui", "common/services", "account/main"],
function(e, t, n, r) {
    var i = "/comment/delete/",
    s = "/comment/add/",
    o = "/comment/update/",
    u = "/comment/detail/",
    a = function(e, r) {
        n.post(i, {
            id: e
        },
        function(e, i, s) {
            e === n.CODE_SUCC ? r && r(e, i, s) : t.alert("error", i)
        })
    },
    f = function(e, r, i, o, u, a) {
        var f = {
            app_label: e,
            model: r,
            object_id: i,
            content: o
        };
        parseInt(u) !== 0 && (f.parent_id = u),
        n.post(s, f,
        function(e, r, i) {
            e === n.CODE_SUCC ? a && a(e, r, i) : t.alert("error", r)
        })
    },
    l = function(e, r, i) {
        var s = {
            id: e,
            content: r
        };
        n.post(o, s,
        function(e, r, s) {
            e === n.CODE_SUCC ? i && i(e, r, s) : t.alert("error", r)
        })
    };
    return {
        deleteComment: function(e, t) {
            r.loginRequired(function() {
                a(e, t)
            })
        },
        addComment: function(e, t, n, i, s, o) {
            window.UE && r.loginRequired(function() {
                f(e, t, n, i, s, o)
            })
        },
        editComment: function(e, t, n) {
            window.UE && r.loginRequired(function() {
                l(e, t, n)
            })
        },
        detail: function(e, r, i) {
            n.get(r || u, {
                id: e
            },
            function(e, r, s) {
                e === n.CODE_SUCC ? i && i(e, r, s) : t.alert("error", r)
            })
        }
    }
}),
define("fcoin/utils/api", ["jquery", "common/ui", "common/services", "account/main"],
function(e, t, n, r) {
    var i = "/fcoin/buy/pay/";
    return {
        buyPay: function(e, s) {
            r.loginRequired(function() {
                n.post(i, {
                    amount: e
                },
                function(e, r, i) {
                    e == n.CODE_SUCC ? s && s(e, r, i) : t.alert("error", r)
                },
                "none", !1)
            })
        }
    }
}),
define("tuce/utils/api", ["jquery", "common/ui", "common/services", "account/main"],
function(e, t, n, r) {
    var i = "/tuce/photo/list/",
    s = "/tuce/case/list/",
    o = "/tuce/search/",
    u = "/tuce/collection/create_case/",
    a = "/tuce/collection/change_case/",
    f = "/tuce/collection/delete_case/",
    l = "/tuce/collection/cases/",
    c = "/tuce/collection/bind_photo/",
    h = "/tuce/collection/unbind_photo/",
    p = "/tuce/collection/bind_case/",
    d = "/tuce/img/upload/";
    return {
        collectionCases: function(e, i) {
            if (r.isLogin == 0) return;
            var e = e || 0;
            n.get(l, {
                auto_create: e
            },
            function(e, r, s) {
                e == n.CODE_SUCC ? i && i(e, r, s) : t.alert("error", r)
            })
        },
        colletionCreateCase: function(e, r) {
            n.post(u, {
                title: e
            },
            function(e, i, s) {
                e == n.CODE_SUCC ? r ? r(e, i, s) : t.alert("success", "画册创建成功！") : t.alert("error", i)
            })
        },
        collectionBindPhoto: function(e, r, i) {
            n.post(c, {
                case_id: e,
                photo_id: r
            },
            function(e, r, s) {
                e == n.CODE_SUCC ? i ? i(e, r, s) : t.alert("success", "收藏成功！") : t.alert("error", r)
            })
        },
        collectionUnbindPhoto: function(e, r, i) {
            n.post(h, {
                case_id: e,
                photo_id: r
            },
            function(e, r, s) {
                e == n.CODE_SUCC ? i ? i(e, r, s) : t.alert("success", "取消收藏成功！") : t.alert("error", r)
            })
        },
        collectionBindCase: function(e, r, i) {
            n.post(p, {
                case_id: e,
                source_case_id: r
            },
            function(e, r, s) {
                e == n.CODE_SUCC ? i ? i(e, r, s) : t.alert("success", "收藏成功！") : t.alert("error", r)
            })
        },
        collectionChangeCase: function(e, r, i) {
            n.post(a, {
                id: e,
                title: r
            },
            function(e, r, s) {
                e == n.CODE_SUCC ? i ? i(e, r, s) : t.alert("success", "画册修改成功！") : t.alert("error", r)
            })
        },
        collectionDeleteCase: function(e, r) {
            n.post(f, {
                id: e
            },
            function(e, i, s) {
                e == n.CODE_SUCC ? r ? r(e, i, s) : t.alert("success", "画册删除成功！") : t.alert("error", i)
            })
        },
        photoList: function(e, r) {
            n.get(i, e,
            function(e, i, s) {
                e === n.CODE_SUCC ? r && r(e, i, s) : t.alert("error", i)
            })
        },
        search: function(e, r) {
            n.get(o, e,
            function(e, i, s) {
                e === n.CODE_SUCC ? r && r(e, i, s) : t.alert("error", i)
            })
        },
        caseList: function(e, r) {
            n.get(s, e,
            function(e, i, s) {
                e === n.CODE_SUCC ? r && r(e, i, s) : t.alert("error", i)
            })
        }
    }
}),
define("forum/utils/api", ["jquery", "common/ui", "common/services", "account/main"],
function(e, t, n, r) {
    var i = "/forum/topic/add/",
    s = "/forum/topic/update/",
    o = function(e, r) {
        n.post(i, e,
        function(e, i, s) {
            e === n.CODE_SUCC ? r && r(e, i, s) : t.alert("error", i)
        })
    },
    u = function(e, r) {
        n.post(s, e,
        function(e, i, s) {
            e === n.CODE_SUCC ? r && r(e, i, s) : t.alert("error", i)
        })
    };
    return {
        addForum: function(e, t) {
            window.UE && r.loginRequired(function() {
                o(e, t)
            })
        },
        editForum: function(e, t) {
            window.UE && r.loginRequired(function() {
                u(e, t)
            })
        }
    }
}),
define("location/utils/api", ["jquery", "common/services"],
function(e, t) {
    var n = "/location/position/",
    r = "/location/community/list/",
    i = "/location/community/search/";
    return {
        position: function(e) {
            t.get(n, {},
            function(n, r, i) {
                n === t.CODE_SUCC ? e && e(n, r, i) : ui.alert("error", r)
            })
        },
        communityList: function(e, n) {
            t.get(r, e,
            function(e, r, i) {
                e === t.CODE_SUCC ? n && n(e, r, i) : ui.alert("error", r)
            })
        },
        communitySearch: function(e, n) {
            t.get(i, e,
            function(e, r, i) {
                e === t.CODE_SUCC ? n && n(e, r, i) : ui.alert("error", r)
            })
        }
    }
}),
define("iyun/utils/api", ["jquery", "common/services"],
function(e, t) {
    var n = "/ifuwo/houselayout/delete/",
    r = "/iyun/houselayout/copy/",
    i = "/iyun/houselayout/get/community/",
    s = "/ifuwo/design/delete/",
    o = "/ifuwo/design/copy/",
    u = "/iyun/design/detail/";
    return {
        houselayoutDel: function(e, r) {
            t.post(n, {
                no: e
            },
            function(e, t, n) {
                r && r(e, t, n)
            })
        },
        houselayoutCopy: function(e, n) {
            t.post(r, {
                no: e
            },
            function(e, t, r) {
                n && n(e, t, r)
            })
        },
        houselayoutSearch: function(e, n) {
            t.get(i, e,
            function(e, t, r) {
                n && n(e, t, r)
            })
        },
        designDel: function(e, n) {
            t.post(s, {
                no: e
            },
            function(e, t, r) {
                n && n(e, t, r)
            })
        },
        designCopy: function(e, n) {
            t.post(o, {
                no: e
            },
            function(e, t, r) {
                n && n(e, t, r)
            })
        },
        designDetail: function(e, n) {
            t.get(u, e,
            function(e, t, r) {
                n && n(e, t, r)
            })
        }
    }
}),
define("service/utils/api", ["jquery", "common/ui", "common/services"],
function(e, t, n) {
    var r = "/service/purpose/add/";
    return {
        purposeAdd: function(e, t, i) {
            n.post(r, {
                name: e,
                contact: t
            },
            function(e, t, n) {
                i && i(e, t, n)
            })
        }
    }
}),
define("ifuwo/utils/api", ["jquery", "common/services"],
function(e, t) {
    var n = "/ifuwo/houselayout/own/",
    r = "/ifuwo/design/own/",
    i = "/ifuwo/houselayout/search/",
    s = "/ifuwo/render/list/",
    o = "/ifuwo/item/new/security_code/",
    u = "/ifuwo/item/new/delete/",
    a = "/ifuwo/item/new/save/",
    f = "/ifuwo/item/category/add/",
    l = "/ifuwo/item/category/delete/",
    c = "/ifuwo/item/category/get/",
    h = "/ifuwo/category/property/get/";
    return {
        securitycode: function(e, n) {
            t.post(o, {
                no: e
            },
            function(e, t, r) {
                n && n(e, t, r)
            })
        },
        itemDelete: function(e, n) {
            t.post(u, {
                no: e
            },
            function(e, t, r) {
                n && n(e, t, r)
            })
        },
        itemSave: function(e, n) {
            t.post(a, e,
            function(e, t, r) {
                n && n(e, t, r)
            })
        },
        getCategoryProperties: function(e, n) {
            t.get(h, {
                category_name: e
            },
            function(e, t, r) {
                n && n(e, t, r)
            })
        },
        itemCategoryAdd: function(e, n, r) {
            t.post(f, {
                no: e,
                category_id: n
            },
            function(e, t, n) {
                r && r(e, t, n)
            })
        },
        itemCategoryDelete: function(e, n, r) {
            t.post(l, {
                no: e,
                category_id: n
            },
            function(e, t, n) {
                r && r(e, t, n)
            })
        },
        itemCategoryGet: function(e, n, r) {
            t.get(c, {
                no: e,
                tree_id: n
            },
            function(e, t, n) {
                r && r(e, t, n)
            })
        },
        insertHouse: function(e, r) {
            t.get(n, e,
            function(e, t, n) {
                r && r(e, t, n)
            })
        },
        insertDesign: function(e, n) {
            t.get(_POSITION_API, e,
            function(e, t, r) {
                n && n(e, t, r)
            })
        },
        searchList: function(e, n) {
            t.get(i, e,
            function(e, t, r) {
                n && n(e, t, r)
            })
        },
        renderList: function(e, n) {
            t.get(s, e,
            function(e, t, r) {
                n && n(e, t, r)
            })
        }
    }
}),
define("zxxq/utils/api", ["jquery", "common/services"],
function(e, t) {
    var n = "/zxxq/apply/submit/";
    return {
        DEFAULT: 0,
        SHIGONG_399: 1,
        SHIGONG_688: 2,
        SHIGONG_888: 3,
        SHIGONG_1288: 4,
        SHIGONG_DINGZHI: 5,
        ACTIVITY_3: 11,
        BAOJIA: 21,
        ONLINE_NEW_BAOMING: 31,
        infoSave: function(e, r) {
            t.post(n, e,
            function(e, t, n) {
                r && r(e, t, n)
            })
        }
    }
}),
define("baojia/utils/api", ["jquery", "common/services"],
function(e, t) {
    var n = "/baojia/quote/";
    return {
        priceLists: function(e, r) {
            t.post(n, e,
            function(e, t, n) {
                r && r(e, t, n)
            })
        }
    }
}),
define("common/webapi", ["jquery", "account/utils/api", "message/utils/api", "tagging/utils/api", "verifycode/utils/api", "favorite/utils/api", "comment/utils/api", "fcoin/utils/api", "tuce/utils/api", "forum/utils/api", "location/utils/api", "iyun/utils/api", "service/utils/api", "ifuwo/utils/api", "zxxq/utils/api", "baojia/utils/api"],
function(e, t, n, r, i, s, o, u, a, f, l, c, h, p, d, v) {
    return {
        account: t,
        message: n,
        tagging: r,
        verifycode: i,
        favorite: s,
        comment: o,
        fcoin: u,
        tuce: a,
        forum: f,
        location: l,
        iyun: c,
        service: h,
        ifuwo: p,
        zxxqApi: d,
        baojiaApi: v
    }
}),
define("common/citycascade", ["jquery"],
function(e) {
    var t = {
        provinces: [{
            firstLetter: "A",
            provinceName: "安徽",
            provinceId: 10
        },
        {
            firstLetter: "B",
            provinceName: "北京",
            provinceId: 12
        },
        {
            firstLetter: "C",
            provinceName: "重庆",
            provinceId: 13
        },
        {
            firstLetter: "F",
            provinceName: "福建",
            provinceId: 14
        },
        {
            firstLetter: "G",
            provinceName: "广西",
            provinceId: 17
        },
        {
            firstLetter: "G",
            provinceName: "贵州",
            provinceId: 18
        },
        {
            firstLetter: "G",
            provinceName: "广东",
            provinceId: 16
        },
        {
            firstLetter: "G",
            provinceName: "甘肃",
            provinceId: 15
        },
        {
            firstLetter: "H",
            provinceName: "海南",
            provinceId: 19
        },
        {
            firstLetter: "H",
            provinceName: "湖北",
            provinceId: 23
        },
        {
            firstLetter: "H",
            provinceName: "黑龙江",
            provinceId: 21
        },
        {
            firstLetter: "H",
            provinceName: "河南",
            provinceId: 22
        },
        {
            firstLetter: "H",
            provinceName: "河北",
            provinceId: 20
        },
        {
            firstLetter: "H",
            provinceName: "湖南",
            provinceId: 24
        },
        {
            firstLetter: "J",
            provinceName: "江苏",
            provinceId: 25
        },
        {
            firstLetter: "J",
            provinceName: "吉林",
            provinceId: 27
        },
        {
            firstLetter: "J",
            provinceName: "江西",
            provinceId: 26
        },
        {
            firstLetter: "L",
            provinceName: "辽宁",
            provinceId: 28
        },
        {
            firstLetter: "N",
            provinceName: "内蒙古",
            provinceId: 29
        },
        {
            firstLetter: "N",
            provinceName: "宁夏",
            provinceId: 30
        },
        {
            firstLetter: "Q",
            provinceName: "青海",
            provinceId: 31
        },
        {
            firstLetter: "S",
            provinceName: "四川",
            provinceId: 36
        },
        {
            firstLetter: "S",
            provinceName: "陕西",
            provinceId: 32
        },
        {
            firstLetter: "S",
            provinceName: "上海",
            provinceId: 35
        },
        {
            firstLetter: "S",
            provinceName: "山东",
            provinceId: 33
        },
        {
            firstLetter: "S",
            provinceName: "山西",
            provinceId: 34
        },
        {
            firstLetter: "T",
            provinceName: "天津",
            provinceId: 38
        },
        {
            firstLetter: "X",
            provinceName: "西藏",
            provinceId: 41
        },
        {
            firstLetter: "X",
            provinceName: "新疆",
            provinceId: 39
        },
        {
            firstLetter: "Y",
            provinceName: "云南",
            provinceId: 42
        },
        {
            firstLetter: "Z",
            provinceName: "浙江",
            provinceId: 43
        }],
        "广东": [{
            cityCode: "gz",
            citySlug: "guangzhou",
            cityName: "广州",
            cityId: 1611
        },
        {
            cityCode: "sz",
            citySlug: "shenzhen",
            cityName: "深圳",
            cityId: 1612
        },
        {
            cityCode: "zh",
            citySlug: "zhuhai",
            cityName: "珠海",
            cityId: 1613
        },
        {
            cityCode: "st",
            citySlug: "shantou",
            cityName: "汕头",
            cityId: 1614
        },
        {
            cityCode: "sg",
            citySlug: "shaoguan",
            cityName: "韶关",
            cityId: 1615
        },
        {
            cityCode: "hy",
            citySlug: "heyuan",
            cityName: "河源",
            cityId: 1616
        },
        {
            cityCode: "mz",
            citySlug: "meizhou",
            cityName: "梅州",
            cityId: 1617
        },
        {
            cityCode: "hz",
            citySlug: "huizhou",
            cityName: "惠州",
            cityId: 1618
        },
        {
            cityCode: "sw",
            citySlug: "shanwei",
            cityName: "汕尾",
            cityId: 1619
        },
        {
            cityCode: "dg",
            citySlug: "dongguan",
            cityName: "东莞",
            cityId: 1620
        },
        {
            cityCode: "zs",
            citySlug: "zhongshan",
            cityName: "中山",
            cityId: 1621
        },
        {
            cityCode: "jm",
            citySlug: "jiangmen",
            cityName: "江门",
            cityId: 1622
        },
        {
            cityCode: "fs",
            citySlug: "foshan",
            cityName: "佛山",
            cityId: 1623
        },
        {
            cityCode: "yj",
            citySlug: "yangjiang",
            cityName: "阳江",
            cityId: 1624
        },
        {
            cityCode: "zj",
            citySlug: "zhanjiang",
            cityName: "湛江",
            cityId: 1625
        },
        {
            cityCode: "mm",
            citySlug: "maoming",
            cityName: "茂名",
            cityId: 1626
        },
        {
            cityCode: "zq",
            citySlug: "zhaoqing",
            cityName: "肇庆",
            cityId: 1627
        },
        {
            cityCode: "qy",
            citySlug: "qingyuan",
            cityName: "清远",
            cityId: 1628
        },
        {
            cityCode: "cz",
            citySlug: "chaozhou",
            cityName: "潮州",
            cityId: 1629
        },
        {
            cityCode: "jy",
            citySlug: "jieyang",
            cityName: "揭阳",
            cityId: 1630
        },
        {
            cityCode: "yf",
            citySlug: "yunfu",
            cityName: "云浮",
            cityId: 1631
        },
        {
            cityCode: "yc",
            citySlug: "yangchun",
            cityName: "阳春",
            cityId: 1632
        }],
        "江苏": [{
            cityCode: "nj",
            citySlug: "nanjing",
            cityName: "南京",
            cityId: 2511
        },
        {
            cityCode: "xz",
            citySlug: "xuzhou",
            cityName: "徐州",
            cityId: 2512
        },
        {
            cityCode: "lyg",
            citySlug: "lianyungang",
            cityName: "连云港",
            cityId: 2513
        },
        {
            cityCode: "ha",
            citySlug: "huaian",
            cityName: "淮安",
            cityId: 2514
        },
        {
            cityCode: "yc",
            citySlug: "yancheng",
            cityName: "盐城",
            cityId: 2515
        },
        {
            cityCode: "yz",
            citySlug: "yangzhou",
            cityName: "扬州",
            cityId: 2516
        },
        {
            cityCode: "nt",
            citySlug: "nantong",
            cityName: "南通",
            cityId: 2517
        },
        {
            cityCode: "zj",
            citySlug: "zhenjiang",
            cityName: "镇江",
            cityId: 2518
        },
        {
            cityCode: "cz",
            citySlug: "changzhou",
            cityName: "常州",
            cityId: 2519
        },
        {
            cityCode: "wx",
            citySlug: "wuxi",
            cityName: "无锡",
            cityId: 2520
        },
        {
            cityCode: "sz",
            citySlug: "suzhou",
            cityName: "苏州",
            cityId: 2521
        },
        {
            cityCode: "tz",
            citySlug: "tai_zhou",
            cityName: "泰州",
            cityId: 2522
        },
        {
            cityCode: "sq",
            citySlug: "suqian",
            cityName: "宿迁",
            cityId: 2523
        },
        {
            cityCode: "xh",
            citySlug: "xinghua",
            cityName: "兴化",
            cityId: 2524
        },
        {
            cityCode: "tc",
            citySlug: "taicang",
            cityName: "太仓",
            cityId: 2525
        },
        {
            cityCode: "ks",
            citySlug: "kunshan",
            cityName: "昆山",
            cityId: 2526
        }],
        "浙江": [{
            cityCode: "hz",
            citySlug: "hangzhou",
            cityName: "杭州",
            cityId: 4311
        },
        {
            cityCode: "nb",
            citySlug: "ningbo",
            cityName: "宁波",
            cityId: 4312
        },
        {
            cityCode: "wz",
            citySlug: "wenzhou",
            cityName: "温州",
            cityId: 4313
        },
        {
            cityCode: "jx",
            citySlug: "jiaxing",
            cityName: "嘉兴",
            cityId: 4314
        },
        {
            cityCode: "sx",
            citySlug: "shaoxing",
            cityName: "绍兴",
            cityId: 4315
        },
        {
            cityCode: "jh",
            citySlug: "jinhua",
            cityName: "金华",
            cityId: 4316
        },
        {
            cityCode: "qz",
            citySlug: "quzhou",
            cityName: "衢州",
            cityId: 4317
        },
        {
            cityCode: "zs",
            citySlug: "zhoushan",
            cityName: "舟山",
            cityId: 4318
        },
        {
            cityCode: "tz",
            citySlug: "taizhou",
            cityName: "台州",
            cityId: 4319
        },
        {
            cityCode: "ls",
            citySlug: "lishui",
            cityName: "丽水",
            cityId: 4320
        },
        {
            cityCode: "hz",
            citySlug: "huzhou",
            cityName: "湖州",
            cityId: 4321
        }],
        "山东": [{
            cityCode: "jn",
            citySlug: "jinan",
            cityName: "济南",
            cityId: 3311
        },
        {
            cityCode: "qd",
            citySlug: "qingdao",
            cityName: "青岛",
            cityId: 3312
        },
        {
            cityCode: "zb",
            citySlug: "zibo",
            cityName: "淄博",
            cityId: 3313
        },
        {
            cityCode: "zz",
            citySlug: "zaozhuang",
            cityName: "枣庄",
            cityId: 3314
        },
        {
            cityCode: "dy",
            citySlug: "dongying",
            cityName: "东营",
            cityId: 3315
        },
        {
            cityCode: "wf",
            citySlug: "weifang",
            cityName: "潍坊",
            cityId: 3316
        },
        {
            cityCode: "yt",
            citySlug: "yantai",
            cityName: "烟台",
            cityId: 3317
        },
        {
            cityCode: "wh",
            citySlug: "weihai",
            cityName: "威海",
            cityId: 3318
        },
        {
            cityCode: "jn",
            citySlug: "jining",
            cityName: "济宁",
            cityId: 3319
        },
        {
            cityCode: "ta",
            citySlug: "taian",
            cityName: "泰安",
            cityId: 3320
        },
        {
            cityCode: "rz",
            citySlug: "rizhao",
            cityName: "日照",
            cityId: 3321
        },
        {
            cityCode: "lw",
            citySlug: "laiwu",
            cityName: "莱芜",
            cityId: 3322
        },
        {
            cityCode: "dz",
            citySlug: "dezhou",
            cityName: "德州",
            cityId: 3323
        },
        {
            cityCode: "bz",
            citySlug: "binzhou",
            cityName: "滨州",
            cityId: 3324
        },
        {
            cityCode: "ly",
            citySlug: "linyi",
            cityName: "临沂",
            cityId: 3325
        },
        {
            cityCode: "hz",
            citySlug: "heze",
            cityName: "荷泽",
            cityId: 3326
        },
        {
            cityCode: "lc",
            citySlug: "liaocheng",
            cityName: "聊城",
            cityId: 3327
        }],
        "河北": [{
            cityCode: "sjz",
            citySlug: "shijiazhuang",
            cityName: "石家庄",
            cityId: 2011
        },
        {
            cityCode: "hd",
            citySlug: "handan",
            cityName: "邯郸",
            cityId: 2012
        },
        {
            cityCode: "xt",
            citySlug: "xingtai",
            cityName: "邢台",
            cityId: 2013
        },
        {
            cityCode: "bd",
            citySlug: "baoding",
            cityName: "保定",
            cityId: 2014
        },
        {
            cityCode: "zjk",
            citySlug: "zhangjiakou",
            cityName: "张家口",
            cityId: 2015
        },
        {
            cityCode: "cd",
            citySlug: "chengde",
            cityName: "承德",
            cityId: 2016
        },
        {
            cityCode: "ts",
            citySlug: "tangshan",
            cityName: "唐山",
            cityId: 2017
        },
        {
            cityCode: "qhd",
            citySlug: "qinhuangdao",
            cityName: "秦皇岛",
            cityId: 2018
        },
        {
            cityCode: "cz",
            citySlug: "cangzhou",
            cityName: "沧州",
            cityId: 2019
        },
        {
            cityCode: "lf",
            citySlug: "langfang",
            cityName: "廊坊",
            cityId: 2020
        },
        {
            cityCode: "hs",
            citySlug: "hengshui",
            cityName: "衡水",
            cityId: 2021
        }],
        "河南": [{
            cityCode: "zz",
            citySlug: "zhengzhou",
            cityId: 2211,
            cityName: "郑州"
        },
        {
            cityCode: "ly",
            citySlug: "luoyang",
            cityId: 2212,
            cityName: "洛阳"
        },
        {
            cityCode: "kf",
            citySlug: "kaifeng",
            cityId: 2213,
            cityName: "开封"
        },
        {
            cityCode: "pds",
            citySlug: "pingdingshan",
            cityId: 2214,
            cityName: "平顶山"
        },
        {
            cityCode: "jz",
            citySlug: "jiaozuo",
            cityId: 2215,
            cityName: "焦作"
        },
        {
            cityCode: "hb",
            citySlug: "hebi",
            cityId: 2216,
            cityName: "鹤壁"
        },
        {
            cityCode: "xx",
            citySlug: "xinxiang",
            cityId: 2217,
            cityName: "新乡"
        },
        {
            cityCode: "ay",
            citySlug: "anyang",
            cityId: 2218,
            cityName: "安阳"
        },
        {
            cityCode: "py",
            citySlug: "puyang",
            cityId: 2219,
            cityName: "濮阳"
        },
        {
            cityCode: "xc",
            citySlug: "xuchang",
            cityId: 2220,
            cityName: "许昌"
        },
        {
            cityCode: "lh",
            citySlug: "luohe",
            cityId: 2221,
            cityName: "漯河"
        },
        {
            cityCode: "smx",
            citySlug: "sanmenxia",
            cityId: 2222,
            cityName: "三门峡"
        },
        {
            cityCode: "sq",
            citySlug: "shangqiu",
            cityId: 2223,
            cityName: "商丘"
        },
        {
            cityCode: "zk",
            citySlug: "zhoukou",
            cityId: 2224,
            cityName: "周口"
        },
        {
            cityCode: "zmd",
            citySlug: "zhumadian",
            cityId: 2225,
            cityName: "驻马店"
        },
        {
            cityCode: "xy",
            citySlug: "xinyang",
            cityId: 2226,
            cityName: "信阳"
        },
        {
            cityCode: "ny",
            citySlug: "nanyang",
            cityId: 2228,
            cityName: "南阳"
        }],
        "福建": [{
            cityCode: "fz",
            citySlug: "fuzhou",
            cityName: "福州",
            cityId: 1411
        },
        {
            cityCode: "xm",
            citySlug: "xiamen",
            cityName: "厦门",
            cityId: 1412
        },
        {
            cityCode: "sm",
            citySlug: "sanming",
            cityName: "三明",
            cityId: 1413
        },
        {
            cityCode: "pt",
            citySlug: "putian",
            cityName: "莆田",
            cityId: 1414
        },
        {
            cityCode: "qz",
            citySlug: "quanzhou",
            cityName: "泉州",
            cityId: 1415
        },
        {
            cityCode: "zz",
            citySlug: "zhangzhou",
            cityName: "漳州",
            cityId: 1416
        },
        {
            cityCode: "np",
            citySlug: "nanping",
            cityName: "南平",
            cityId: 1417
        },
        {
            cityCode: "nd",
            citySlug: "ningde",
            cityName: "宁德",
            cityId: 1418
        },
        {
            cityCode: "ly",
            citySlug: "longyan",
            cityName: "龙岩",
            cityId: 1419
        }],
        "湖北": [{
            cityCode: "wh",
            citySlug: "wuhan",
            cityName: "武汉",
            cityId: 2311
        },
        {
            cityCode: "hs",
            citySlug: "huangshi",
            cityName: "黄石",
            cityId: 2312
        },
        {
            cityCode: "xy",
            citySlug: "xiangyang",
            cityName: "襄阳",
            cityId: 2313
        },
        {
            cityCode: "sy",
            citySlug: "shiyan",
            cityName: "十堰",
            cityId: 2314
        },
        {
            cityCode: "yc",
            citySlug: "yichang",
            cityName: "宜昌",
            cityId: 2315
        },
        {
            cityCode: "jz",
            citySlug: "jingzhou",
            cityName: "荆州",
            cityId: 2316
        },
        {
            cityCode: "ez",
            citySlug: "ezhou",
            cityName: "鄂州",
            cityId: 2317
        },
        {
            cityCode: "xg",
            citySlug: "xiaogan",
            cityName: "孝感",
            cityId: 2318
        },
        {
            cityCode: "hg",
            citySlug: "huanggang",
            cityName: "黄冈",
            cityId: 2319
        },
        {
            cityCode: "xn",
            citySlug: "xianning",
            cityName: "咸宁",
            cityId: 2320
        },
        {
            cityCode: "jm",
            citySlug: "jingmen",
            cityName: "荆门",
            cityId: 2321
        },
        {
            cityCode: "sz",
            citySlug: "suizhou",
            cityName: "随州",
            cityId: 2322
        },
        {
            cityCode: "xt",
            citySlug: "xiantao",
            cityName: "仙桃",
            cityId: 2324
        },
        {
            cityCode: "qj",
            citySlug: "qianjiang",
            cityName: "潜江",
            cityId: 2325
        },
        {
            cityCode: "es",
            citySlug: "enshi",
            cityName: "恩施",
            cityId: 2326
        }],
        "四川": [{
            cityCode: "cd",
            citySlug: "chengdu",
            cityName: "成都",
            cityId: 3611
        },
        {
            cityCode: "zg",
            citySlug: "zigong",
            cityName: "自贡",
            cityId: 3612
        },
        {
            cityCode: "ga",
            citySlug: "guangan",
            cityName: "广安",
            cityId: 3613
        },
        {
            cityCode: "pzh",
            citySlug: "panzhihua",
            cityName: "攀枝花",
            cityId: 3614
        },
        {
            cityCode: "lz",
            citySlug: "luzhou",
            cityName: "泸州",
            cityId: 3615
        },
        {
            cityCode: "dy",
            citySlug: "deyang",
            cityName: "德阳",
            cityId: 3616
        },
        {
            cityCode: "my",
            citySlug: "mianyang",
            cityName: "绵阳",
            cityId: 3617
        },
        {
            cityCode: "gy",
            citySlug: "guangyuan",
            cityName: "广元",
            cityId: 3618
        },
        {
            cityCode: "sn",
            citySlug: "suining",
            cityName: "遂宁",
            cityId: 3619
        },
        {
            cityCode: "ls",
            citySlug: "leshan",
            cityName: "乐山",
            cityId: 3620
        },
        {
            cityCode: "nj",
            citySlug: "neijiang",
            cityName: "内江",
            cityId: 3621
        },
        {
            cityCode: "yb",
            citySlug: "yibin",
            cityName: "宜宾",
            cityId: 3622
        },
        {
            cityCode: "nc",
            citySlug: "nanchong",
            cityName: "南充",
            cityId: 3623
        },
        {
            cityCode: "zy",
            citySlug: "ziyang",
            cityName: "资阳",
            cityId: 3624
        },
        {
            cityCode: "ya",
            citySlug: "yaan",
            cityName: "雅安",
            cityId: 3625
        },
        {
            cityCode: "ab",
            citySlug: "aba",
            cityName: "阿坝",
            cityId: 3626
        },
        {
            cityCode: "gz",
            citySlug: "ganzi",
            cityName: "甘孜",
            cityId: 3627
        },
        {
            cityCode: "bz",
            citySlug: "bazhong",
            cityName: "巴中",
            cityId: 3628
        },
        {
            cityCode: "dz",
            citySlug: "dazhou",
            cityName: "达州",
            cityId: 3629
        },
        {
            cityCode: "ms",
            citySlug: "meishan",
            cityName: "眉山",
            cityId: 3630
        },
        {
            cityCode: "ls",
            citySlug: "liangshan",
            cityName: "凉山",
            cityId: 3631
        }],
        "辽宁": [{
            cityCode: "sy",
            citySlug: "shenyang",
            cityName: "沈阳",
            cityId: 2811
        },
        {
            cityCode: "dl",
            citySlug: "dalian",
            cityName: "大连",
            cityId: 2812
        },
        {
            cityCode: "as",
            citySlug: "anshan",
            cityName: "鞍山",
            cityId: 2813
        },
        {
            cityCode: "fs",
            citySlug: "fushun",
            cityName: "抚顺",
            cityId: 2814
        },
        {
            cityCode: "bx",
            citySlug: "benxi",
            cityName: "本溪",
            cityId: 2815
        },
        {
            cityCode: "dd",
            citySlug: "dandong",
            cityName: "丹东",
            cityId: 2816
        },
        {
            cityCode: "jz",
            citySlug: "jinzhou",
            cityName: "锦州",
            cityId: 2817
        },
        {
            cityCode: "yk",
            citySlug: "yingkou",
            cityName: "营口",
            cityId: 2818
        },
        {
            cityCode: "fx",
            citySlug: "fuxin",
            cityName: "阜新",
            cityId: 2819
        },
        {
            cityCode: "ly",
            citySlug: "liaoyang",
            cityName: "辽阳",
            cityId: 2820
        },
        {
            cityCode: "tl",
            citySlug: "tieling",
            cityName: "铁岭",
            cityId: 2821
        },
        {
            cityCode: "zy",
            citySlug: "zhaoyang",
            cityName: "朝阳",
            cityId: 2822
        },
        {
            cityCode: "hld",
            citySlug: "huludao",
            cityName: "葫芦岛",
            cityId: 2823
        },
        {
            cityCode: "pj",
            citySlug: "panjin",
            cityName: "盘锦",
            cityId: 2824
        }],
        "湖南": [{
            cityCode: "cs",
            citySlug: "changsha",
            cityName: "长沙",
            cityId: 2411
        },
        {
            cityCode: "zz",
            citySlug: "zhuzhou",
            cityName: "株洲",
            cityId: 2412
        },
        {
            cityCode: "xt",
            citySlug: "xiangtan",
            cityName: "湘潭",
            cityId: 2413
        },
        {
            cityCode: "hy",
            citySlug: "hengyang",
            cityName: "衡阳",
            cityId: 2414
        },
        {
            cityCode: "sy",
            citySlug: "shaoyang",
            cityName: "邵阳",
            cityId: 2415
        },
        {
            cityCode: "yy",
            citySlug: "yueyang",
            cityName: "岳阳",
            cityId: 2416
        },
        {
            cityCode: "cd",
            citySlug: "changde",
            cityName: "常德",
            cityId: 2417
        },
        {
            cityCode: "zjj",
            citySlug: "zhangjiajie",
            cityName: "张家界",
            cityId: 2418
        },
        {
            cityCode: "ld",
            citySlug: "loudi",
            cityName: "娄底",
            cityId: 2419
        },
        {
            cityCode: "cz",
            citySlug: "chenzhou",
            cityName: "郴州",
            cityId: 2420
        },
        {
            cityCode: "yz",
            citySlug: "yongzhou",
            cityName: "永州",
            cityId: 2421
        },
        {
            cityCode: "hh",
            citySlug: "huaihua",
            cityName: "怀化",
            cityId: 2422
        },
        {
            cityCode: "yy",
            citySlug: "yiyang",
            cityName: "益阳",
            cityId: 2423
        },
        {
            cityCode: "xx",
            citySlug: "xiangxi",
            cityName: "湘西",
            cityId: 2424
        }],
        "安徽": [{
            cityCode: "hf",
            citySlug: "hefei",
            cityName: "合肥",
            cityId: 1011
        },
        {
            cityCode: "hn",
            citySlug: "huainan",
            cityName: "淮南",
            cityId: 1012
        },
        {
            cityCode: "hb",
            citySlug: "huaibei",
            cityName: "淮北",
            cityId: 1013
        },
        {
            cityCode: "wh",
            citySlug: "wuhu",
            cityName: "芜湖",
            cityId: 1014
        },
        {
            cityCode: "tl",
            citySlug: "tongling",
            cityName: "铜陵",
            cityId: 1015
        },
        {
            cityCode: "bb",
            citySlug: "bengbu",
            cityName: "蚌埠",
            cityId: 1016
        },
        {
            cityCode: "mas",
            citySlug: "maanshan",
            cityName: "马鞍山",
            cityId: 1017
        },
        {
            cityCode: "aq",
            citySlug: "anqing",
            cityName: "安庆",
            cityId: 1018
        },
        {
            cityCode: "hs",
            citySlug: "huangshan",
            cityName: "黄山",
            cityId: 1019
        },
        {
            cityCode: "sz",
            citySlug: "su_zhou",
            cityName: "宿州",
            cityId: 1020
        },
        {
            cityCode: "cz",
            citySlug: "chuzhou",
            cityName: "滁州",
            cityId: 1021
        },
        {
            cityCode: "ch",
            citySlug: "chaohu",
            cityName: "巢湖",
            cityId: 1022
        },
        {
            cityCode: "xc",
            citySlug: "xuancheng",
            cityName: "宣城",
            cityId: 1023
        },
        {
            cityCode: "cz",
            citySlug: "chizhou",
            cityName: "池州",
            cityId: 1024
        },
        {
            cityCode: "la",
            citySlug: "luan",
            cityName: "六安",
            cityId: 1025
        },
        {
            cityCode: "fy",
            citySlug: "fuyang",
            cityName: "阜阳",
            cityId: 1026
        },
        {
            cityCode: "bz",
            citySlug: "bozhou",
            cityName: "亳州",
            cityId: 1027
        }],
        "陕西": [{
            cityCode: "xa",
            citySlug: "xian",
            cityName: "西安",
            cityId: 3211
        },
        {
            cityCode: "tc",
            citySlug: "tongchuan",
            cityName: "铜川",
            cityId: 3212
        },
        {
            cityCode: "bj",
            citySlug: "baoji",
            cityName: "宝鸡",
            cityId: 3213
        },
        {
            cityCode: "xy",
            citySlug: "xianyang",
            cityName: "咸阳",
            cityId: 3214
        },
        {
            cityCode: "ya",
            citySlug: "yanan",
            cityName: "延安",
            cityId: 3215
        },
        {
            cityCode: "wn",
            citySlug: "weinan",
            cityName: "渭南",
            cityId: 3216
        },
        {
            cityCode: "sl",
            citySlug: "shangluo",
            cityName: "商洛",
            cityId: 3217
        },
        {
            cityCode: "ak",
            citySlug: "ankang",
            cityName: "安康",
            cityId: 3218
        },
        {
            cityCode: "yl",
            citySlug: "yu_lin",
            cityName: "榆林",
            cityId: 3219
        },
        {
            cityCode: "hz",
            citySlug: "hanzhong",
            cityName: "汉中",
            cityId: 3220
        }],
        "广西": [{
            cityCode: "lz",
            citySlug: "liuzhou",
            cityName: "柳州",
            cityId: 1711
        },
        {
            cityCode: "gl",
            citySlug: "guilin",
            cityName: "桂林",
            cityId: 1712
        },
        {
            cityCode: "wz",
            citySlug: "wuzhou",
            cityName: "梧州",
            cityId: 1713
        },
        {
            cityCode: "bh",
            citySlug: "beihai",
            cityName: "北海",
            cityId: 1714
        },
        {
            cityCode: "nn",
            citySlug: "nanning",
            cityName: "南宁",
            cityId: 1715
        },
        {
            cityCode: "yl",
            citySlug: "yulin",
            cityName: "玉林",
            cityId: 1716
        },
        {
            cityCode: "lb",
            citySlug: "laibin",
            cityName: "来宾",
            cityId: 1717
        },
        {
            cityCode: "bs",
            citySlug: "baishai",
            cityName: "百色",
            cityId: 1718
        },
        {
            cityCode: "fcg",
            citySlug: "fangchenggang",
            cityName: "防城港",
            cityId: 1719
        },
        {
            cityCode: "qz",
            citySlug: "qinzhou",
            cityName: "钦州",
            cityId: 1720
        },
        {
            cityCode: "gg",
            citySlug: "guigang",
            cityName: "贵港",
            cityId: 1721
        },
        {
            cityCode: "hz",
            citySlug: "hezhou",
            cityName: "贺州",
            cityId: 1722
        },
        {
            cityCode: "hc",
            citySlug: "hechi",
            cityName: "河池",
            cityId: 1723
        },
        {
            cityCode: "cz",
            citySlug: "chongzuo",
            cityName: "崇左",
            cityId: 1724
        }],
        "山西": [{
            cityCode: "dt",
            citySlug: "datong",
            cityName: "大同",
            cityId: 3413
        },
        {
            cityCode: "jc",
            citySlug: "jincheng",
            cityName: "晋城",
            cityId: 3416
        },
        {
            cityCode: "jz",
            citySlug: "jinzhong",
            cityName: "晋中",
            cityId: 3418
        },
        {
            cityCode: "lf",
            citySlug: "linfen",
            cityName: "临汾",
            cityId: 3421
        },
        {
            cityCode: "ll",
            citySlug: "lvliang",
            cityName: "吕梁",
            cityId: 3420
        },
        {
            cityCode: "sz",
            citySlug: "shuozhou",
            cityName: "朔州",
            cityId: 3417
        },
        {
            cityCode: "ty",
            citySlug: "taiyuan",
            cityName: "太原",
            cityId: 3412
        },
        {
            cityCode: "xz",
            citySlug: "xinzhou",
            cityName: "忻州",
            cityId: 3419
        },
        {
            cityCode: "yc",
            citySlug: "yuncheng",
            cityName: "运城",
            cityId: 3422
        },
        {
            cityCode: "yq",
            citySlug: "yangquan",
            cityName: "阳泉",
            cityId: 3414
        },
        {
            cityCode: "zc",
            citySlug: "zhoucheng",
            cityName: "邹城",
            cityId: 3411
        },
        {
            cityCode: "zz",
            citySlug: "zhangzhi",
            cityName: "长治",
            cityId: 3415
        }],
        "黑龙江": [{
            cityCode: "heb",
            citySlug: "haerbin",
            cityName: "哈尔滨",
            cityId: 2111
        },
        {
            cityCode: "qqhe",
            citySlug: "qiqihaer",
            cityName: "齐齐哈尔",
            cityId: 2112
        },
        {
            cityCode: "hg",
            citySlug: "hegang",
            cityName: "鹤岗",
            cityId: 2113
        },
        {
            cityCode: "sys",
            citySlug: "shuangyashan",
            cityName: "双鸭山",
            cityId: 2114
        },
        {
            cityCode: "jx",
            citySlug: "jixi",
            cityName: "鸡西",
            cityId: 2115
        },
        {
            cityCode: "dq",
            citySlug: "daqing",
            cityName: "大庆",
            cityId: 2116
        },
        {
            cityCode: "yc",
            citySlug: "yichun",
            cityName: "伊春",
            cityId: 2117
        },
        {
            cityCode: "mdj",
            citySlug: "mudanjiang",
            cityName: "牡丹江",
            cityId: 2118
        },
        {
            cityCode: "jms",
            citySlug: "jiamusi",
            cityName: "佳木斯",
            cityId: 2119
        },
        {
            cityCode: "qth",
            citySlug: "qitaihe",
            cityName: "七台河",
            cityId: 2120
        },
        {
            cityCode: "sh",
            citySlug: "suihua",
            cityName: "绥化",
            cityId: 2121
        },
        {
            cityCode: "hh",
            citySlug: "heihe",
            cityName: "黑河",
            cityId: 2122
        },
        {
            cityCode: "jgdj",
            citySlug: "jiagedaji",
            cityName: "加格达奇",
            cityId: 2123
        }],
        "江西": [{
            cityCode: "nc",
            citySlug: "nanchang",
            cityName: "南昌",
            cityId: 2611
        },
        {
            cityCode: "jdz",
            citySlug: "jingdezhen",
            cityName: "景德镇",
            cityId: 2612
        },
        {
            cityCode: "px",
            citySlug: "pingxiang",
            cityName: "萍乡",
            cityId: 2613
        },
        {
            cityCode: "xy",
            citySlug: "xinyu",
            cityName: "新余",
            cityId: 2614
        },
        {
            cityCode: "jj",
            citySlug: "jiujiang",
            cityName: "九江",
            cityId: 2615
        },
        {
            cityCode: "yt",
            citySlug: "yingtan",
            cityName: "鹰潭",
            cityId: 2616
        },
        {
            cityCode: "sr",
            citySlug: "shangrao",
            cityName: "上饶",
            cityId: 2617
        },
        {
            cityCode: "yc",
            citySlug: "yi_chun",
            cityName: "宜春",
            cityId: 2618
        },
        {
            cityCode: "fz",
            citySlug: "fu_zhou",
            cityName: "抚州",
            cityId: 2619
        },
        {
            cityCode: "ja",
            citySlug: "jian",
            cityName: "吉安",
            cityId: 2620
        },
        {
            cityCode: "gz",
            citySlug: "ganzhou",
            cityName: "赣州",
            cityId: 2621
        }],
        "吉林": [{
            cityCode: "cc",
            citySlug: "changchun",
            cityName: "长春",
            cityId: 2711
        },
        {
            cityCode: "jl",
            citySlug: "jilin",
            cityName: "吉林",
            cityId: 2712
        },
        {
            cityCode: "sp",
            citySlug: "siping",
            cityName: "四平",
            cityId: 2713
        },
        {
            cityCode: "ly",
            citySlug: "liaoyuan",
            cityName: "辽源",
            cityId: 2714
        },
        {
            cityCode: "th",
            citySlug: "tonghua",
            cityName: "通化",
            cityId: 2715
        },
        {
            cityCode: "bc",
            citySlug: "baicheng",
            cityName: "白城",
            cityId: 2716
        },
        {
            cityCode: "yb",
            citySlug: "yanbian",
            cityName: "延边",
            cityId: 2717
        },
        {
            cityCode: "bs",
            citySlug: "baishan",
            cityName: "白山",
            cityId: 2718
        },
        {
            cityCode: "sy",
            citySlug: "songyuan",
            cityName: "松原",
            cityId: 2719
        }],
        "贵州": [{
            cityCode: "gy",
            citySlug: "guiyang",
            cityName: "贵阳",
            cityId: 1811
        },
        {
            cityCode: "lps",
            citySlug: "lupanshui",
            cityName: "六盘水",
            cityId: 1812
        },
        {
            cityCode: "zy",
            citySlug: "zunyi",
            cityName: "遵义",
            cityId: 1813
        },
        {
            cityCode: "tr",
            citySlug: "tongren",
            cityName: "铜仁",
            cityId: 1814
        },
        {
            cityCode: "bj",
            citySlug: "bijie",
            cityName: "毕节",
            cityId: 1815
        },
        {
            cityCode: "as",
            citySlug: "anshun",
            cityName: "安顺",
            cityId: 1816
        },
        {
            cityCode: "qxn",
            citySlug: "qianxinan",
            cityName: "黔西南",
            cityId: 1817
        },
        {
            cityCode: "qn",
            citySlug: "qiannan",
            cityName: "黔南",
            cityId: 1818
        },
        {
            cityCode: "qdn",
            citySlug: "qiandongnan",
            cityName: "黔东南",
            cityId: 1819
        }],
        "内蒙古": [{
            cityCode: "hhht",
            citySlug: "huhehaote",
            cityName: "呼和浩特",
            cityId: 2911
        },
        {
            cityCode: "bt",
            citySlug: "baotou",
            cityName: "包头",
            cityId: 2912
        },
        {
            cityCode: "wh",
            citySlug: "wuhai",
            cityName: "乌海",
            cityId: 2913
        },
        {
            cityCode: "cf",
            citySlug: "chifeng",
            cityName: "赤峰",
            cityId: 2914
        },
        {
            cityCode: "wlcbm",
            citySlug: "wulanchabumeng",
            cityName: "乌兰察布盟",
            cityId: 2915
        },
        {
            cityCode: "xlglm",
            citySlug: "xilinguolemeng",
            cityName: "锡林郭勒盟",
            cityId: 2916
        },
        {
            cityCode: "hlbe",
            citySlug: "hulunbeier",
            cityName: "呼伦贝尔",
            cityId: 2917
        },
        {
            cityCode: "tl",
            citySlug: "tongliao",
            cityName: "通辽",
            cityId: 2918
        },
        {
            cityCode: "eeds",
            citySlug: "eerduosi",
            cityName: "鄂尔多斯",
            cityId: 2919
        },
        {
            cityCode: "bynem",
            citySlug: "bayannaoermeng",
            cityName: "巴彦淖尔盟",
            cityId: 2920
        },
        {
            cityCode: "alsm",
            citySlug: "elashanmeng",
            cityName: "阿拉善盟",
            cityId: 2921
        },
        {
            cityCode: "xa",
            citySlug: "xingan",
            cityName: "兴安",
            cityId: 2922
        }],
        "新疆": [{
            cityCode: "wlmq",
            citySlug: "wulumuqi",
            cityName: "乌鲁木齐",
            cityId: 3911
        },
        {
            cityCode: "klmy",
            citySlug: "kelamayi",
            cityName: "克拉玛依",
            cityId: 3912
        },
        {
            cityCode: "tlf",
            citySlug: "tulufan",
            cityName: "吐鲁番",
            cityId: 3914
        },
        {
            cityCode: "hm",
            citySlug: "hami",
            cityName: "哈密",
            cityId: 3915
        },
        {
            cityCode: "ht",
            citySlug: "hetian",
            cityName: "和田",
            cityId: 3916
        },
        {
            cityCode: "aks",
            citySlug: "akesu",
            cityName: "阿克苏",
            cityId: 3917
        },
        {
            cityCode: "ks",
            citySlug: "kashi",
            cityName: "喀什",
            cityId: 3918
        },
        {
            cityCode: "kzlskz",
            citySlug: "kezilesukeerkezi",
            cityName: "克孜勒苏柯尔克孜",
            cityId: 3919
        },
        {
            cityCode: "bygl",
            citySlug: "bayinguoleng",
            cityName: "巴音郭楞",
            cityId: 3920
        },
        {
            cityCode: "cj",
            citySlug: "changji",
            cityName: "昌吉",
            cityId: 3921
        },
        {
            cityCode: "be",
            citySlug: "boer",
            cityName: "博尔",
            cityId: 3922
        },
        {
            cityCode: "yl",
            citySlug: "yili",
            cityName: "伊犁",
            cityId: 3923
        },
        {
            cityCode: "shz",
            citySlug: "danhezi",
            cityName: "石河子",
            cityId: 3924
        },
        {
            cityCode: "ws",
            citySlug: "wusu",
            cityName: "乌苏",
            cityId: 3925
        }],
        "云南": [{
            cityCode: "km",
            citySlug: "kunming",
            cityName: "昆明",
            cityId: 4211
        },
        {
            cityCode: "zt",
            citySlug: "zhaotong",
            cityName: "昭通",
            cityId: 4212
        },
        {
            cityCode: "qj",
            citySlug: "qujing",
            cityName: "曲靖",
            cityId: 4213
        },
        {
            cityCode: "yx",
            citySlug: "yuxi",
            cityName: "玉溪",
            cityId: 4214
        },
        {
            cityCode: "pr",
            citySlug: "puer",
            cityName: "普洱",
            cityId: 4215
        },
        {
            cityCode: "lc",
            citySlug: "lincang",
            cityName: "临沧",
            cityId: 4216
        },
        {
            cityCode: "bs",
            citySlug: "baoshan",
            cityName: "保山",
            cityId: 4217
        },
        {
            cityCode: "lj",
            citySlug: "lijiang",
            cityName: "丽江",
            cityId: 4218
        },
        {
            cityCode: "ws",
            citySlug: "wenshan",
            cityName: "文山",
            cityId: 4219
        },
        {
            cityCode: "hh",
            citySlug: "honghe",
            cityName: "红河",
            cityId: 4220
        },
        {
            cityCode: "xsbn",
            citySlug: "xishuangbanna",
            cityName: "西双版纳",
            cityId: 4221
        },
        {
            cityCode: "cx",
            citySlug: "chuxiong",
            cityName: "楚雄",
            cityId: 4222
        },
        {
            cityCode: "dl",
            citySlug: "dali",
            cityName: "大理",
            cityId: 4223
        },
        {
            cityCode: "dh",
            citySlug: "dehong",
            cityName: "德宏",
            cityId: 4224
        },
        {
            cityCode: "nj",
            citySlug: "nujiang",
            cityName: "怒江",
            cityId: 4225
        },
        {
            cityCode: "dq",
            citySlug: "diqing",
            cityName: "迪庆",
            cityId: 4226
        }],
        "甘肃": [{
            cityCode: "lz",
            citySlug: "lanzhou",
            cityName: "兰州",
            cityId: 1511
        },
        {
            cityCode: "jc",
            citySlug: "jinchang",
            cityName: "金昌",
            cityId: 1512
        },
        {
            cityCode: "by",
            citySlug: "baiyin",
            cityName: "白银",
            cityId: 1513
        },
        {
            cityCode: "ts",
            citySlug: "tianshui",
            cityName: "天水",
            cityId: 1514
        },
        {
            cityCode: "jyg",
            citySlug: "jiayuguan",
            cityName: "嘉峪关",
            cityId: 1515
        },
        {
            cityCode: "dx",
            citySlug: "dingxi",
            cityName: "定西",
            cityId: 1516
        },
        {
            cityCode: "pl",
            citySlug: "pingliang",
            cityName: "平凉",
            cityId: 1517
        },
        {
            cityCode: "qy",
            citySlug: "qingyang",
            cityName: "庆阳",
            cityId: 1518
        },
        {
            cityCode: "ln",
            citySlug: "longnan",
            cityName: "陇南",
            cityId: 1519
        },
        {
            cityCode: "ww",
            citySlug: "wuwei",
            cityName: "武威",
            cityId: 1520
        },
        {
            cityCode: "zy",
            citySlug: "zhangye",
            cityName: "张掖",
            cityId: 1521
        },
        {
            cityCode: "jq",
            citySlug: "jiuquan",
            cityName: "酒泉",
            cityId: 1522
        },
        {
            cityCode: "gn",
            citySlug: "gannan",
            cityName: "甘南",
            cityId: 1523
        },
        {
            cityCode: "lx",
            citySlug: "linxia",
            cityName: "临夏",
            cityId: 1524
        }],
        "海南": [{
            cityCode: "hk",
            citySlug: "haikou",
            cityId: 1911,
            cityName: "海口"
        },
        {
            cityCode: "sy",
            citySlug: "sanya",
            cityId: 1912,
            cityName: "三亚"
        }],
        "宁夏": [{
            cityCode: "yc",
            citySlug: "yinchuan",
            cityName: "银川",
            cityId: 3011
        },
        {
            cityCode: "szs",
            citySlug: "danzuishan",
            cityName: "石嘴山",
            cityId: 3012
        },
        {
            cityCode: "wz",
            citySlug: "wuzhong",
            cityName: "吴忠",
            cityId: 3013
        },
        {
            cityCode: "gy",
            citySlug: "guyuan",
            cityName: "固原",
            cityId: 3014
        },
        {
            cityCode: "zw",
            citySlug: "zhongwei",
            cityName: "中卫",
            cityId: 3015
        }],
        "青海": [{
            cityCode: "xn",
            citySlug: "xining",
            cityId: 3111,
            cityName: "西宁"
        },
        {
            cityCode: "hd",
            citySlug: "hacityIdong",
            cityId: 3112,
            cityName: "海东"
        },
        {
            cityCode: "hb",
            citySlug: "haibe",
            cityId: 3113,
            cityName: "海北"
        },
        {
            cityCode: "hn",
            citySlug: "huangnan",
            cityId: 3114,
            cityName: "黄南"
        },
        {
            cityCode: "hn",
            citySlug: "hainan",
            cityId: 3115,
            cityName: "海南"
        },
        {
            cityCode: "gl",
            citySlug: "guoluo",
            cityId: 3116,
            cityName: "果洛"
        },
        {
            cityCode: "ys",
            citySlug: "yushu",
            cityId: 3117,
            cityName: "玉树"
        },
        {
            cityCode: "hx",
            citySlug: "haixi",
            cityId: 3118,
            cityName: "海西"
        }],
        "西藏": [{
            cityCode: "ls",
            citySlug: "lasa",
            cityId: 4111,
            cityName: "拉萨"
        },
        {
            cityCode: "nq",
            citySlug: "naqu",
            cityId: 4112,
            cityName: "那曲"
        },
        {
            cityCode: "cd",
            citySlug: "changdou",
            cityId: 4113,
            cityName: "昌都"
        },
        {
            cityCode: "sn",
            citySlug: "shannan",
            cityId: 4114,
            cityName: "山南"
        },
        {
            cityCode: "rkz",
            citySlug: "rikaze",
            cityId: 4115,
            cityName: "日喀则"
        },
        {
            cityCode: "al",
            citySlug: "ali",
            cityId: 4116,
            cityName: "阿里"
        },
        {
            cityCode: "lz",
            citySlug: "linzhi",
            cityId: 4117,
            cityName: "林芝"
        }],
        "上海": [{
            cityCode: "sh",
            citySlug: "shanghai",
            cityId: 3511,
            cityName: "上海"
        }],
        "北京": [{
            cityCode: "bg",
            citySlug: "beijing",
            cityId: 1211,
            cityName: "北京"
        }],
        "重庆": [{
            cityCode: "cq",
            citySlug: "chongqing",
            cityId: 1311,
            cityName: "重庆"
        }],
        "天津": [{
            cityCode: "tj",
            citySlug: "tianjing",
            cityId: 3811,
            cityName: "天津"
        }]
    },
    n = function(t, n) {
        var r = e("#" + t),
        s = r.find("option:selected").attr("name");
        i(s, n)
    },
    r = function(r, i) {
        var s, o, u = e("#" + r),
        a = t.provinces;
        for (var f = 0,
        l = a.length; f < l; f++) o = a[f],
        s = "<option value='" + o.provinceId + "' name='" + o.provinceName + "'>" + o.firstLetter + " " + o.provinceName + "</option>",
        u.append(s);
        n(r, i)
    },
    i = function(n, r) {
        var i = e("#" + r),
        s,
        o = '<option value="">市/地区</option>';
        s = t[n],
        i.empty(),
        n || i.append(o);
        if (s) for (var u = 0,
        a = s.length; u < a; u++) {
            var f = s[u];
            o = "<option value='" + f.cityId + "'>" + f.cityName + "</option>",
            i.append(o)
        }
    },
    s = function(t, n) {
        e("#" + t).val(n)
    },
    o = function(t, n) {
        e("#" + t).val(n)
    },
    u = function(e) {
        var n = parseInt(e),
        r = parseInt(e.toString().substr(0, 2)),
        i = t.provinces,
        s = i.length,
        o = [],
        u = {};
        for (var a = 0; a < s; a++) if (r === i[a].provinceId) {
            u.provinceId = i[a].provinceId,
            u.provinceName = i[a].provinceName,
            o = t[i[a].provinceName];
            break
        }
        s = o.length;
        for (var a = 0; a < s; a++) if (n === o[a].cityId) return u.cityId = o[a].cityId,
        u
    },
    a = function(t, r) {
        e("#" + t).change(function() {
            n(t, r)
        })
    },
    f = function(t, n) {
        r(t, n),
        a(t, n);
        var i = e.cookie("city_id");
        if (parseInt(i) > 0) {
            var u = i.substr(0, 2);
            s(t, u),
            e("#" + t).trigger("change"),
            o(n, i)
        }
    };
    return {
        init: function(e, t) {
            return f(e, t)
        },
        getProvinceId: function(e) {
            return u(e)
        },
        setCity: function(e, t) {
            return i(e, t)
        }
    }
}),
define("common/demand", ["jquery", "common/ui", "common/utils", "common/services", "common/webapi", "common/template", "common/citycascade"],
function(e, t, n, r, i, s, o) {
    var u = function() {
        var n = function(n) {
            var r = {
                style: {
                    marginLeft: "-210px",
                    marginTop: "-200px",
                    height: "auto",
                    width: "420px",
                    overflow: "hidden"
                },
                title: n,
                dayCount: dayCount,
                monthCount: monthCount
            };
            e("body").append(s("common/baoming/popup", r)),
            t.placeholder(),
            t.ieVersion(function() {
                e(".J-Ie7").show()
            }),
            e(".pop-close").click(function() {
                e(".screen-bg").remove(),
                e(".pop").remove()
            })
        };
        e(document).delegate(".J-Pop-Order", "click",
        function() {
            var t = e(this).data("title"),
            s = t || "预约免费设计";
            n(s),
            o.init("selProvince", "selCity");
            var u = e("#order_form").validate({
                ignore: "",
                rules: {
                    username: {
                        required: !0
                    },
                    tel: {
                        required: !0,
                        imobile: !0
                    },
                    city: {
                        required: !0
                    }
                },
                messages: {
                    username: {
                        required: "请填写用户名"
                    },
                    tel: {
                        required: "请填写手机号码",
                        imobile: "请填写正确的手机号码"
                    },
                    city: {
                        required: "请选择省市"
                    }
                },
                errorPlacement: function(e, t) {
                    e.appendTo(t.closest(".input-line"))
                },
                submitHandler: function() {
                    var t = e.trim(e("#order_form").find(".J-Name").val()),
                    s = e.trim(e("#order_form").find(".J-Tel").val()),
                    o = e.trim(e("#order_form").find(".J-selCity").val()),
                    u = {
                        name: t,
                        mobile: s,
                        city_id: o
                    };
                    i.zxxqApi.infoSave(u,
                    function(t, i, s) {
                        e("#order_form").find(".submit").addClass("disabled");
                        if (t === r.CODE_SUCC) {
                            var o = '<div class="pop-success-info"><b>恭喜您已预约成功！</b><br><i class="share-icon-ok"></i><br><span>您已预约成功，我们的装修管家将会尽快与您联系，请保持电话畅通！</span></div>';
                            e(".J-Name").val(""),
                            e(".J-Tel").val(""),
                            e("#order_form").find(".submit").removeClass("disabled"),
                            e(".pop-content").length > 0 ? e(".pop-content").empty().html(o) : n(o)
                        } else e("#order_form").find(".submit").removeClass("disabled"),
                        e("#order_form").find(".input-error").html(i)
                    })
                }
            });
            e(".J-Order-View").click(function() {
                if (e(this).hasClass("disabled")) return;
                e("#order_form").submit()
            })
        }),
        t.placeholder(),
        e(document).delegate(".J-Name, .J-Tel, .J-selProvince", "focus",
        function() {
            e(".input-error").empty()
        })
    };
    return e(function() {
        u()
    }),
    {
        baomingCalcul: function(r) {
            if (!r) return ! 1;
            r.append(s("demand/baoming_calcul")),
            t.placeholder();
            var i = function() {
                return {
                    cn: e("#selCty").find("option:selected").text(),
                    ci: e("#selCty").val(),
                    p: e("#phone").val(),
                    a: e("#area").val()
                }
            },
            u = e("#baojia_form").validate({
                ignore: "",
                rules: {
                    selCty: {
                        required: !0
                    },
                    area: {
                        required: !0,
                        pInt: !0,
                        digits: !0,
                        range: [30, 500]
                    },
                    phone: {
                        required: !0,
                        imobile: !0
                    }
                },
                messages: {
                    selCty: {
                        required: "请选择省市"
                    },
                    area: {
                        required: "请输入面积",
                        pInt: "面积为正整数",
                        digits: "面积为正整数",
                        range: "面积范围在30~500㎡之间"
                    },
                    phone: {
                        required: "请填写手机号码",
                        imobile: "请填写正确的手机号码"
                    }
                },
                errorPlacement: function(e, t) {
                    e.appendTo(t.closest(".form-list dd"))
                },
                submitHandler: function() {
                    pars = i(),
                    location.href = "http://www.fuwo.com/baojia/?" + n.objToQuery(pars)
                }
            });
            o.init("selPro", "selCty"),
            e(".J-Apply").on("click",
            function() {
                e("#baojia_form").submit()
            })
        },
        baomingBottom: function() {
            e("#section").append(s("demand/baoming_bottom")),
            t.placeholder();
            var u = e(".baojia-bottom-small"),
            a = e(".baojia-bottom-big"),
            f = e(".baojia-bottom-tip"),
            l = e("#baojia_bottom_form"),
            c = function() {
                return {
                    cn: e("#selProBottom").find("option:selected").text(),
                    ci: e("#selCtyBottom").val(),
                    p: e("#phone").val(),
                    a: e("#area").val()
                }
            },
            h = l.validate({
                ignore: "",
                rules: {
                    selCty: {
                        required: !0
                    },
                    area: {
                        required: !0,
                        pInt: !0,
                        digits: !0,
                        range: [30, 500]
                    },
                    phone: {
                        required: !0,
                        imobile: !0
                    }
                },
                messages: {
                    selCty: {
                        required: "请选择省市"
                    },
                    area: {
                        required: "请输入面积",
                        pInt: "面积为正整数",
                        digits: "面积为正整数",
                        range: "面积范围在30~500㎡之间"
                    },
                    phone: {
                        required: "请填写手机号码",
                        imobile: "请填写正确的手机号码"
                    }
                },
                errorPlacement: function(e, t) {
                    e.appendTo(t.closest("form"))
                },
                submitHandler: function() {
                    var n = e("#selCtyBottom").val(),
                    s = e("#area").val(),
                    o = e("#phone").val(),
                    u = {
                        city_id: n,
                        area: s,
                        mobile: o
                    };
                    i.baojiaApi.priceLists(u,
                    function(n, i, s) {
                        n === r.CODE_SUCC ? (e(".J-TotalPrice").html(s.approximate_total_price), e(".J-Bedroom").html(Math.round(s.bedroom_price) + "<em>&nbsp;元</em>"), e(".J-Liveroom").html(Math.round(s.parlour_dining_room_price) + "<em>&nbsp;元</em>"), e(".J-Kitchen").html(Math.round(s.kitchen_price) + "<em>&nbsp;元</em>"), e(".J-Washroom").html(Math.round(s.rest_room_price) + "<em>&nbsp;元</em>"), e(".J-Balcony").html(Math.round(s.balcony_price) + "<em>&nbsp;元</em>"), e(".J-Other").html(Math.round(s.other_price) + "<em>&nbsp;元</em>"), e(".inquiry-baojia").css("paddingTop", "0"), e("#baojia_bottom_form").css("height", "auto"), f.css("display", "block"), e(".J-specific-baojia").addClass("actived"), e(".J-Calculate").addClass("calc-end").text("重新计算")) : t.tipHandler(i, "warning")
                    })
                }
            });
            o.init("selProBottom", "selCtyBottom"),
            e(".J-open-inquiry").click(function() {
                u.animate({
                    left: "-166px"
                },
                "fast", "swing",
                function() {
                    a.show().animate({
                        left: "0"
                    },
                    "normal", "swing")
                })
            }),
            e(".J-close-inquiry").click(function() {
                a.animate({
                    left: "-100%"
                },
                "normal", "swing",
                function() {
                    e(".baojia-bottom-big").hide(),
                    u.animate({
                        left: "0"
                    },
                    "fast", "swing")
                })
            }),
            e(".J-Calculate").click(function() {
                e(this).text() == "重新计算" ? (e(this).removeClass("calc-end").text("开始计算"), e(".inquiry-baojia").css("paddingTop", "12px"), f.css("display", "none"), e(".J-specific-baojia").removeClass("actived"), e("#area").val(""), e("#phone").val("")) : l.submit()
            });
            var p = function() {
                if (window.g_config === undefined || window.g_config.disableBackTop === undefined || window.g_config.disableBackTop) e(document).scrollTop() > 200 ? u.css("left") != "-166px" ? u.show() : a.show() : u.css("left") != "-166px" ? u.hide() : a.hide()
            };
            p(),
            e(window).scroll(function() {
                p()
            }),
            e(window).resize(function() {
                p()
            }),
            e(".J-specific-baojia").click(function() {
                e(this).hasClass("actived") && (pars = c(), location.href = "http://www.fuwo.com/baojia/?" + n.objToQuery(pars))
            })
        },
        baomingPopup: function() {
            u()
        },
        baomingNormal: function(n, o) {
            var o = o || window.monthCount;
            if (!n) return ! 1;
            n.append(s("demand/baoming_normal", {
                serviceCount: o
            })),
            t.placeholder();
            var u = null,
            a = function(e) {
                i.location.position(function(n, i, s) {
                    n === r.CODE_SUCC ? (u = s.city_id, u == 0 && (u = 3511), e()) : t.alert("error", i)
                })
            },
            f = function(n) {
                var r = {
                    title: "&nbsp;",
                    content: n,
                    style: {
                        marginLeft: "-210px",
                        marginTop: "-200px",
                        height: "400px",
                        width: "420px"
                    }
                };
                e("body").append(s("common/popup/dialog", r)),
                e(".pop").addClass("xgt-pop"),
                t.ieVersion(function() {
                    e(".J-Ie7").show()
                }),
                e(".pop-close").click(function() {
                    e(".screen-bg").remove(),
                    e(".pop").remove()
                })
            },
            l = e("#baojia_normal_form").validate({
                ignore: "",
                rules: {
                    username: {
                        required: !0
                    },
                    mobile: {
                        required: !0,
                        imobile: !0
                    }
                },
                messages: {
                    username: {
                        required: "请填写您的称呼"
                    },
                    mobile: {
                        required: "请填写手机号码",
                        imobile: "请填写正确的手机号码"
                    }
                },
                errorPlacement: function(e, t) {
                    e.appendTo(t.closest(".form-list dd"))
                },
                submitHandler: function() {
                    var n = {
                        name: e("#username").val(),
                        mobile: e("#mobile").val(),
                        city_id: u,
                        service_type: i.zxxqApi.ONLINE_NEW_BAOMING
                    };
                    e("#J-Normal-Apply").addClass("btn-disabled"),
                    i.zxxqApi.infoSave(n,
                    function(n, i, s) {
                        e(".J-Normal-Apply").removeClass("btn-disabled");
                        if (n === r.CODE_SUCC) {
                            var o = '<div class="pop-success-info"><b>恭喜您已预约成功！</b><br><i class="share-icon-ok"></i><br><span>您已预约成功，我们的装修管家将会尽快与您联系，请保持电话畅通！</span></div>';
                            e("#username").val(""),
                            e("#mobile").val(""),
                            e(".pop-content").length > 0 ? e(".pop-content").empty().html(o) : f(o)
                        } else t.tipHandler(i, "warning")
                    })
                }
            });
            e(".J-Normal-Apply").on("click",
            function() {
                u == null ? a(function() {
                    e("#baojia_normal_form").submit()
                }) : e("#baojia_normal_form").submit()
            })
        }
    }
}),
function(e) {
    typeof define == "function" && define.amd ? define("lib/jquery.validate", ["jquery"], e) : e(jQuery)
} (function(e) {
    e.extend(e.fn, {
        validate: function(t) {
            if (!this.length) {
                t && t.debug && window.console && console.warn("Nothing selected, can't validate, returning nothing.");
                return
            }
            var n = e.data(this[0], "validator");
            return n ? n: (this.attr("novalidate", "novalidate"), n = new e.validator(t, this[0]), e.data(this[0], "validator", n), n.settings.onsubmit && (this.validateDelegate(":submit", "click",
            function(t) {
                n.settings.submitHandler && (n.submitButton = t.target),
                e(t.target).hasClass("cancel") && (n.cancelSubmit = !0),
                e(t.target).attr("formnovalidate") !== undefined && (n.cancelSubmit = !0)
            }), this.submit(function(t) {
                function r() {
                    var r, i;
                    return n.settings.submitHandler ? (n.submitButton && (r = e("<input type='hidden'/>").attr("name", n.submitButton.name).val(e(n.submitButton).val()).appendTo(n.currentForm)), i = n.settings.submitHandler.call(n, n.currentForm, t), n.submitButton && r.remove(), i !== undefined ? i: !1) : !0
                }
                return n.settings.debug && t.preventDefault(),
                n.cancelSubmit ? (n.cancelSubmit = !1, r()) : n.form() ? n.pendingRequest ? (n.formSubmitted = !0, !1) : r() : (n.focusInvalid(), !1)
            })), n)
        },
        valid: function() {
            var t, n;
            return e(this[0]).is("form") ? t = this.validate().form() : (t = !0, n = e(this[0].form).validate(), this.each(function() {
                t = n.element(this) && t
            })),
            t
        },
        removeAttrs: function(t) {
            var n = {},
            r = this;
            return e.each(t.split(/\s/),
            function(e, t) {
                n[t] = r.attr(t),
                r.removeAttr(t)
            }),
            n
        },
        rules: function(t, n) {
            var r = this[0],
            i,
            s,
            o,
            u,
            a,
            f;
            if (t) {
                i = e.data(r.form, "validator").settings,
                s = i.rules,
                o = e.validator.staticRules(r);
                switch (t) {
                case "add":
                    e.extend(o, e.validator.normalizeRule(n)),
                    delete o.messages,
                    s[r.name] = o,
                    n.messages && (i.messages[r.name] = e.extend(i.messages[r.name], n.messages));
                    break;
                case "remove":
                    if (!n) return delete s[r.name],
                    o;
                    return f = {},
                    e.each(n.split(/\s/),
                    function(t, n) {
                        f[n] = o[n],
                        delete o[n],
                        n === "required" && e(r).removeAttr("aria-required")
                    }),
                    f
                }
            }
            return u = e.validator.normalizeRules(e.extend({},
            e.validator.classRules(r), e.validator.attributeRules(r), e.validator.dataRules(r), e.validator.staticRules(r)), r),
            u.required && (a = u.required, delete u.required, u = e.extend({
                required: a
            },
            u), e(r).attr("aria-required", "true")),
            u.remote && (a = u.remote, delete u.remote, u = e.extend(u, {
                remote: a
            })),
            u
        }
    }),
    e.extend(e.expr[":"], {
        blank: function(t) {
            return ! e.trim("" + e(t).val())
        },
        filled: function(t) {
            return !! e.trim("" + e(t).val())
        },
        unchecked: function(t) {
            return ! e(t).prop("checked")
        }
    }),
    e.validator = function(t, n) {
        this.settings = e.extend(!0, {},
        e.validator.defaults, t),
        this.currentForm = n,
        this.init()
    },
    e.validator.format = function(t, n) {
        return arguments.length === 1 ?
        function() {
            var n = e.makeArray(arguments);
            return n.unshift(t),
            e.validator.format.apply(this, n)
        }: (arguments.length > 2 && n.constructor !== Array && (n = e.makeArray(arguments).slice(1)), n.constructor !== Array && (n = [n]), e.each(n,
        function(e, n) {
            t = t.replace(new RegExp("\\{" + e + "\\}", "g"),
            function() {
                return n
            })
        }), t)
    },
    e.extend(e.validator, {
        defaults: {
            messages: {},
            groups: {},
            rules: {},
            errorClass: "error",
            validClass: "valid",
            errorElement: "label",
            focusCleanup: !1,
            focusInvalid: !0,
            errorContainer: e([]),
            errorLabelContainer: e([]),
            onsubmit: !0,
            ignore: ":hidden",
            ignoreTitle: !1,
            onfocusin: function(e) {
                this.lastActive = e,
                this.settings.focusCleanup && (this.settings.unhighlight && this.settings.unhighlight.call(this, e, this.settings.errorClass, this.settings.validClass), this.hideThese(this.errorsFor(e)))
            },
            onfocusout: function(e) { ! this.checkable(e) && (e.name in this.submitted || !this.optional(e)) && this.element(e)
            },
            onkeyup: function(e, t) {
                if (t.which === 9 && this.elementValue(e) === "") return; (e.name in this.submitted || e === this.lastElement) && this.element(e)
            },
            onclick: function(e) {
                e.name in this.submitted ? this.element(e) : e.parentNode.name in this.submitted && this.element(e.parentNode)
            },
            highlight: function(t, n, r) {
                t.type === "radio" ? this.findByName(t.name).addClass(n).removeClass(r) : e(t).addClass(n).removeClass(r)
            },
            unhighlight: function(t, n, r) {
                t.type === "radio" ? this.findByName(t.name).removeClass(n).addClass(r) : e(t).removeClass(n).addClass(r)
            }
        },
        setDefaults: function(t) {
            e.extend(e.validator.defaults, t)
        },
        messages: {
            required: "该字段必填",
            remote: "请修正该字段",
            email: "请输入正确的电子邮箱",
            url: "请输入正确的网址",
            date: "请输入正确的日期",
            dateISO: "请输入正确的日期(ISO)",
            number: "请输入正确的数字",
            digits: "只能输入整数",
            creditcard: "请输入正确的信用卡号",
            equalTo: "请再次输入相同的值",
            maxlength: e.validator.format("请输入一个长度最多是{0}的字符串"),
            minlength: e.validator.format("请输入一个长度最少是{0}的字符串"),
            rangelength: e.validator.format("请输入一个长度介于{0}和{1}之间的字符串"),
            range: e.validator.format("请输入一个介于{0}和{1}之间的值"),
            max: e.validator.format("请输入一个最大为{0}的值"),
            min: e.validator.format("请输入一个最小为{0}的值")
        },
        autoCreateRanges: !1,
        prototype: {
            init: function() {
                function t(t) {
                    var n = e.data(this[0].form, "validator"),
                    r = "on" + t.type.replace(/^validate/, ""),
                    i = n.settings;
                    i[r] && !this.is(i.ignore) && i[r].call(n, this[0], t)
                }
                this.labelContainer = e(this.settings.errorLabelContainer),
                this.errorContext = this.labelContainer.length && this.labelContainer || e(this.currentForm),
                this.containers = e(this.settings.errorContainer).add(this.settings.errorLabelContainer),
                this.submitted = {},
                this.valueCache = {},
                this.pendingRequest = 0,
                this.pending = {},
                this.invalid = {},
                this.reset();
                var n = this.groups = {},
                r;
                e.each(this.settings.groups,
                function(t, r) {
                    typeof r == "string" && (r = r.split(/\s/)),
                    e.each(r,
                    function(e, r) {
                        n[r] = t
                    })
                }),
                r = this.settings.rules,
                e.each(r,
                function(t, n) {
                    r[t] = e.validator.normalizeRule(n)
                }),
                e(this.currentForm).validateDelegate(":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'] ,[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox']", "focusin focusout keyup", t).validateDelegate("select, option, [type='radio'], [type='checkbox']", "click", t),
                this.settings.invalidHandler && e(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler),
                e(this.currentForm).find("[required], [data-rule-required], .required").attr("aria-required", "true")
            },
            form: function() {
                return this.checkForm(),
                e.extend(this.submitted, this.errorMap),
                this.invalid = e.extend({},
                this.errorMap),
                this.valid() || e(this.currentForm).triggerHandler("invalid-form", [this]),
                this.showErrors(),
                this.valid()
            },
            checkForm: function() {
                this.prepareForm();
                for (var e = 0,
                t = this.currentElements = this.elements(); t[e]; e++) this.check(t[e]);
                return this.valid()
            },
            element: function(t) {
                var n = this.clean(t),
                r = this.validationTargetFor(n),
                i = !0;
                return this.lastElement = r,
                r === undefined ? delete this.invalid[n.name] : (this.prepareElement(r), this.currentElements = e(r), i = this.check(r) !== !1, i ? delete this.invalid[r.name] : this.invalid[r.name] = !0),
                e(t).attr("aria-invalid", !i),
                this.numberOfInvalids() || (this.toHide = this.toHide.add(this.containers)),
                this.showErrors(),
                i
            },
            showErrors: function(t) {
                if (t) {
                    e.extend(this.errorMap, t),
                    this.errorList = [];
                    for (var n in t) this.errorList.push({
                        message: t[n],
                        element: this.findByName(n)[0]
                    });
                    this.successList = e.grep(this.successList,
                    function(e) {
                        return ! (e.name in t)
                    })
                }
                this.settings.showErrors ? this.settings.showErrors.call(this, this.errorMap, this.errorList) : this.defaultShowErrors()
            },
            resetForm: function() {
                e.fn.resetForm && e(this.currentForm).resetForm(),
                this.submitted = {},
                this.lastElement = null,
                this.prepareForm(),
                this.hideErrors(),
                this.elements().removeClass(this.settings.errorClass).removeData("previousValue").removeAttr("aria-invalid")
            },
            numberOfInvalids: function() {
                return this.objectLength(this.invalid)
            },
            objectLength: function(e) {
                var t = 0,
                n;
                for (n in e) t++;
                return t
            },
            hideErrors: function() {
                this.hideThese(this.toHide)
            },
            hideThese: function(e) {
                e.not(this.containers).text(""),
                this.addWrapper(e).hide()
            },
            valid: function() {
                return this.size() === 0
            },
            size: function() {
                return this.errorList.length
            },
            focusInvalid: function() {
                if (this.settings.focusInvalid) try {
                    e(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").focus().trigger("focusin")
                } catch(t) {}
            },
            findLastActive: function() {
                var t = this.lastActive;
                return t && e.grep(this.errorList,
                function(e) {
                    return e.element.name === t.name
                }).length === 1 && t
            },
            elements: function() {
                var t = this,
                n = {};
                return e(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled], [readonly]").not(this.settings.ignore).filter(function() {
                    return ! this.name && t.settings.debug && window.console && console.error("%o has no name assigned", this),
                    this.name in n || !t.objectLength(e(this).rules()) ? !1 : (n[this.name] = !0, !0)
                })
            },
            clean: function(t) {
                return e(t)[0]
            },
            errors: function() {
                var t = this.settings.errorClass.split(" ").join(".");
                return e(this.settings.errorElement + "." + t, this.errorContext)
            },
            reset: function() {
                this.successList = [],
                this.errorList = [],
                this.errorMap = {},
                this.toShow = e([]),
                this.toHide = e([]),
                this.currentElements = e([])
            },
            prepareForm: function() {
                this.reset(),
                this.toHide = this.errors().add(this.containers)
            },
            prepareElement: function(e) {
                this.reset(),
                this.toHide = this.errorsFor(e)
            },
            elementValue: function(t) {
                var n, r = e(t),
                i = t.type;
                return i === "radio" || i === "checkbox" ? e("input[name='" + t.name + "']:checked").val() : i === "number" && typeof t.validity != "undefined" ? t.validity.badInput ? !1 : r.val() : (n = r.val(), typeof n == "string" ? n.replace(/\r/g, "") : n)
            },
            check: function(t) {
                t = this.validationTargetFor(this.clean(t));
                var n = e(t).rules(),
                r = e.map(n,
                function(e, t) {
                    return t
                }).length,
                i = !1,
                s = this.elementValue(t),
                o,
                u,
                a;
                for (u in n) {
                    a = {
                        method: u,
                        parameters: n[u]
                    };
//                    try {
//                        o = e.validator.methods[u].call(this, s, t, a.parameters);
//                        if (o === "dependency-mismatch" && r === 1) {
//                            i = !0;
//                            continue
//                        }
//                        i = !1;
//                        if (o === "pending") {
//                            this.toHide = this.toHide.not(this.errorsFor(t));
//                            return
//                        }
//                        if (!o) return this.formatAndAdd(t, a),
//                        !1
//                    } catch(f) {
//                        throw this.settings.debug && window.console && console.log("Exception occurred when checking element " + t.id + ", check the '" + a.method + "' method.", f),
//                        f
//                    }
                }
                if (i) return;
                return this.objectLength(n) && this.successList.push(t),
                !0
            },
            customDataMessage: function(t, n) {
                return e(t).data("msg" + n.charAt(0).toUpperCase() + n.substring(1).toLowerCase()) || e(t).data("msg")
            },
            customMessage: function(e, t) {
                var n = this.settings.messages[e];
                return n && (n.constructor === String ? n: n[t])
            },
            findDefined: function() {
                for (var e = 0; e < arguments.length; e++) if (arguments[e] !== undefined) return arguments[e];
                return undefined
            },
            defaultMessage: function(t, n) {
                return this.findDefined(this.customMessage(t.name, n), this.customDataMessage(t, n), !this.settings.ignoreTitle && t.title || undefined, e.validator.messages[n], "<strong>Warning: No message defined for " + t.name + "</strong>")
            },
            formatAndAdd: function(t, n) {
                var r = this.defaultMessage(t, n.method),
                i = /\$?\{(\d+)\}/g;
                typeof r == "function" ? r = r.call(this, n.parameters, t) : i.test(r) && (r = e.validator.format(r.replace(i, "{$1}"), n.parameters)),
                this.errorList.push({
                    message: r,
                    element: t,
                    method: n.method
                }),
                this.errorMap[t.name] = r,
                this.submitted[t.name] = r
            },
            addWrapper: function(e) {
                return this.settings.wrapper && (e = e.add(e.parent(this.settings.wrapper))),
                e
            },
            defaultShowErrors: function() {
                var e, t, n;
                for (e = 0; this.errorList[e]; e++) n = this.errorList[e],
                this.settings.highlight && this.settings.highlight.call(this, n.element, this.settings.errorClass, this.settings.validClass),
                this.showLabel(n.element, n.message);
                this.errorList.length && (this.toShow = this.toShow.add(this.containers));
                if (this.settings.success) for (e = 0; this.successList[e]; e++) this.showLabel(this.successList[e]);
                if (this.settings.unhighlight) for (e = 0, t = this.validElements(); t[e]; e++) this.settings.unhighlight.call(this, t[e], this.settings.errorClass, this.settings.validClass);
                this.toHide = this.toHide.not(this.toShow),
                this.hideErrors(),
                this.addWrapper(this.toShow).show()
            },
            validElements: function() {
                return this.currentElements.not(this.invalidElements())
            },
            invalidElements: function() {
                return e(this.errorList).map(function() {
                    return this.element
                })
            },
            showLabel: function(t, n) {
                var r, i, s, o = this.errorsFor(t),
                u = this.idOrName(t),
                a = e(t).attr("aria-describedby");
                o.length ? (o.removeClass(this.settings.validClass).addClass(this.settings.errorClass), o.html(n)) : (o = e("<" + this.settings.errorElement + ">").attr("id", u + "-error").addClass(this.settings.errorClass).html(n || ""), r = o, this.settings.wrapper && (r = o.hide().show().wrap("<" + this.settings.wrapper + "/>").parent()), this.labelContainer.length ? this.labelContainer.append(r) : this.settings.errorPlacement ? this.settings.errorPlacement(r, e(t)) : r.insertAfter(t), o.is("label") ? o.attr("for", u) : o.parents("label[for='" + u + "']").length === 0 && (s = o.attr("id").replace(/(:|\.|\[|\])/g, "\\$1"), a ? a.match(new RegExp("\\b" + s + "\\b")) || (a += " " + s) : a = s, e(t).attr("aria-describedby", a), i = this.groups[t.name], i && e.each(this.groups,
                function(t, n) {
                    n === i && e("[name='" + t + "']", this.currentForm).attr("aria-describedby", o.attr("id"))
                }))),
                !n && this.settings.success && (o.text(""), typeof this.settings.success == "string" ? o.addClass(this.settings.success) : this.settings.success(o, t)),
                this.toShow = this.toShow.add(o)
            },
            errorsFor: function(t) {
                var n = this.idOrName(t),
                r = e(t).attr("aria-describedby"),
                i = "label[for='" + n + "'], label[for='" + n + "'] *";
                return r && (i = i + ", #" + r.replace(/\s+/g, ", #")),
                this.errors().filter(i)
            },
            idOrName: function(e) {
                return this.groups[e.name] || (this.checkable(e) ? e.name: e.id || e.name)
            },
            validationTargetFor: function(t) {
                return this.checkable(t) && (t = this.findByName(t.name)),
                e(t).not(this.settings.ignore)[0]
            },
            checkable: function(e) {
                return /radio|checkbox/i.test(e.type)
            },
            findByName: function(t) {
                return e(this.currentForm).find("[name='" + t + "']")
            },
            getLength: function(t, n) {
                switch (n.nodeName.toLowerCase()) {
                case "select":
                    return e("option:selected", n).length;
                case "input":
                    if (this.checkable(n)) return this.findByName(n.name).filter(":checked").length
                }
                return t.length
            },
            depend: function(e, t) {
                return this.dependTypes[typeof e] ? this.dependTypes[typeof e](e, t) : !0
            },
            dependTypes: {
                "boolean": function(e) {
                    return e
                },
                string: function(t, n) {
                    return !! e(t, n.form).length
                },
                "function": function(e, t) {
                    return e(t)
                }
            },
            optional: function(t) {
                var n = this.elementValue(t);
                return ! e.validator.methods.required.call(this, n, t) && "dependency-mismatch"
            },
            startRequest: function(e) {
                this.pending[e.name] || (this.pendingRequest++, this.pending[e.name] = !0)
            },
            stopRequest: function(t, n) {
                this.pendingRequest--,
                this.pendingRequest < 0 && (this.pendingRequest = 0),
                delete this.pending[t.name],
                n && this.pendingRequest === 0 && this.formSubmitted && this.form() ? (e(this.currentForm).submit(), this.formSubmitted = !1) : !n && this.pendingRequest === 0 && this.formSubmitted && (e(this.currentForm).triggerHandler("invalid-form", [this]), this.formSubmitted = !1)
            },
            previousValue: function(t) {
                return e.data(t, "previousValue") || e.data(t, "previousValue", {
                    old: null,
                    valid: !0,
                    message: this.defaultMessage(t, "remote")
                })
            }
        },
        classRuleSettings: {
            required: {
                required: !0
            },
            email: {
                email: !0
            },
            url: {
                url: !0
            },
            date: {
                date: !0
            },
            dateISO: {
                dateISO: !0
            },
            number: {
                number: !0
            },
            digits: {
                digits: !0
            },
            creditcard: {
                creditcard: !0
            }
        },
        addClassRules: function(t, n) {
            t.constructor === String ? this.classRuleSettings[t] = n: e.extend(this.classRuleSettings, t)
        },
        classRules: function(t) {
            var n = {},
            r = e(t).attr("class");
            return r && e.each(r.split(" "),
            function() {
                this in e.validator.classRuleSettings && e.extend(n, e.validator.classRuleSettings[this])
            }),
            n
        },
        attributeRules: function(t) {
            var n = {},
            r = e(t),
            i = t.getAttribute("type"),
            s,
            o;
            for (s in e.validator.methods) s === "required" ? (o = t.getAttribute(s), o === "" && (o = !0), o = !!o) : o = r.attr(s),
            /min|max/.test(s) && (i === null || /number|range|text/.test(i)) && (o = Number(o)),
            o || o === 0 ? n[s] = o: i === s && i !== "range" && (n[s] = !0);
            return n.maxlength && /-1|2147483647|524288/.test(n.maxlength) && delete n.maxlength,
            n
        },
        dataRules: function(t) {
            var n, r, i = {},
            s = e(t);
            for (n in e.validator.methods) r = s.data("rule" + n.charAt(0).toUpperCase() + n.substring(1).toLowerCase()),
            r !== undefined && (i[n] = r);
            return i
        },
        staticRules: function(t) {
            var n = {},
            r = e.data(t.form, "validator");
            return r.settings.rules && (n = e.validator.normalizeRule(r.settings.rules[t.name]) || {}),
            n
        },
        normalizeRules: function(t, n) {
            return e.each(t,
            function(r, i) {
                if (i === !1) {
                    delete t[r];
                    return
                }
                if (i.param || i.depends) {
                    var s = !0;
                    switch (typeof i.depends) {
                    case "string":
                        s = !!e(i.depends, n.form).length;
                        break;
                    case "function":
                        s = i.depends.call(n, n)
                    }
                    s ? t[r] = i.param !== undefined ? i.param: !0 : delete t[r]
                }
            }),
            e.each(t,
            function(r, i) {
                t[r] = e.isFunction(i) ? i(n) : i
            }),
            e.each(["minlength", "maxlength"],
            function() {
                t[this] && (t[this] = Number(t[this]))
            }),
            e.each(["rangelength", "range"],
            function() {
                var n;
                t[this] && (e.isArray(t[this]) ? t[this] = [Number(t[this][0]), Number(t[this][1])] : typeof t[this] == "string" && (n = t[this].replace(/[\[\]]/g, "").split(/[\s,]+/), t[this] = [Number(n[0]), Number(n[1])]))
            }),
            e.validator.autoCreateRanges && (t.min != null && t.max != null && (t.range = [t.min, t.max], delete t.min, delete t.max), t.minlength != null && t.maxlength != null && (t.rangelength = [t.minlength, t.maxlength], delete t.minlength, delete t.maxlength)),
            t
        },
        normalizeRule: function(t) {
            if (typeof t == "string") {
                var n = {};
                e.each(t.split(/\s/),
                function() {
                    n[this] = !0
                }),
                t = n
            }
            return t
        },
        addMethod: function(t, n, r) {
            e.validator.methods[t] = n,
            e.validator.messages[t] = r !== undefined ? r: e.validator.messages[t],
            n.length < 3 && e.validator.addClassRules(t, e.validator.normalizeRule(t))
        },
        methods: {
            required: function(t, n, r) {
                if (!this.depend(r, n)) return "dependency-mismatch";
                if (n.nodeName.toLowerCase() === "select") {
                    var i = e(n).val();
                    return i && i.length > 0
                }
                return this.checkable(n) ? this.getLength(t, n) > 0 : e.trim(t).length > 0
            },
            email: function(e, t) {
                return this.optional(t) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(e)
            },
            url: function(e, t) {
                return this.optional(t) || /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(e)
            },
            date: function(e, t) {
                return this.optional(t) || !/Invalid|NaN/.test((new Date(e)).toString())
            },
            dateISO: function(e, t) {
                return this.optional(t) || /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(e)
            },
            number: function(e, t) {
                return this.optional(t) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(e)
            },
            digits: function(e, t) {
                return this.optional(t) || /^\d+$/.test(e)
            },
            creditcard: function(e, t) {
                if (this.optional(t)) return "dependency-mismatch";
                if (/[^0-9 \-]+/.test(e)) return ! 1;
                var n = 0,
                r = 0,
                i = !1,
                s, o;
                e = e.replace(/\D/g, "");
                if (e.length < 13 || e.length > 19) return ! 1;
                for (s = e.length - 1; s >= 0; s--) o = e.charAt(s),
                r = parseInt(o, 10),
                i && (r *= 2) > 9 && (r -= 9),
                n += r,
                i = !i;
                return n % 10 === 0
            },
            minlength: function(t, n, r) {
                var i = e.isArray(t) ? t.length: this.getLength(t, n);
                return this.optional(n) || i >= r
            },
            maxlength: function(t, n, r) {
                var i = e.isArray(t) ? t.length: this.getLength(t, n);
                return this.optional(n) || i <= r
            },
            rangelength: function(t, n, r) {
                var i = e.isArray(t) ? t.length: this.getLength(t, n);
                return this.optional(n) || i >= r[0] && i <= r[1]
            },
            min: function(e, t, n) {
                return this.optional(t) || e >= n
            },
            max: function(e, t, n) {
                return this.optional(t) || e <= n
            },
            range: function(e, t, n) {
                return this.optional(t) || e >= n[0] && e <= n[1]
            },
            equalTo: function(t, n, r) {
                var i = e(r);
                return this.settings.onfocusout && i.unbind(".validate-equalTo").bind("blur.validate-equalTo",
                function() {
                    e(n).valid()
                }),
                t === i.val()
            },
            remote: function(t, n, r) {
                if (this.optional(n)) return "dependency-mismatch";
                var i = this.previousValue(n),
                s,
                o;
                return this.settings.messages[n.name] || (this.settings.messages[n.name] = {}),
                i.originalMessage = this.settings.messages[n.name].remote,
                this.settings.messages[n.name].remote = i.message,
                r = typeof r == "string" && {
                    url: r
                } || r,
                i.old === t ? i.valid: (i.old = t, s = this, this.startRequest(n), o = {},
                o[n.name] = t, e.ajax(e.extend(!0, {
                    url: r,
                    mode: "abort",
                    port: "validate" + n.name,
                    dataType: "json",
                    data: o,
                    context: s.currentForm,
                    success: function(r) {
                        var o = r === !0 || r === "true",
                        u, a, f;
                        s.settings.messages[n.name].remote = i.originalMessage,
                        o ? (f = s.formSubmitted, s.prepareElement(n), s.formSubmitted = f, s.successList.push(n), delete s.invalid[n.name], s.showErrors()) : (u = {},
                        a = r || s.defaultMessage(n, "remote"), u[n.name] = i.message = e.isFunction(a) ? a(t) : a, s.invalid[n.name] = !0, s.showErrors(u)),
                        i.valid = o,
                        s.stopRequest(n, o)
                    }
                },
                r)), "pending")
            }
        }
    }),
    e.format = function() {
        throw "$.format has been deprecated. Please use $.validator.format instead."
    };
    var t = {},
    n;
    e.ajaxPrefilter ? e.ajaxPrefilter(function(e, n, r) {
        var i = e.port;
        e.mode === "abort" && (t[i] && t[i].abort(), t[i] = r)
    }) : (n = e.ajax, e.ajax = function(r) {
        var i = ("mode" in r ? r: e.ajaxSettings).mode,
        s = ("port" in r ? r: e.ajaxSettings).port;
        return i === "abort" ? (t[s] && t[s].abort(), t[s] = n.apply(this, arguments), t[s]) : n.apply(this, arguments)
    }),
    e.extend(e.fn, {
        validateDelegate: function(t, n, r) {
            return this.bind(n,
            function(n) {
                var i = e(n.target);
                if (i.is(t)) return r.apply(i, arguments)
            })
        }
    })
}),
define("common/validate.expand", ["jquery", "lib/jquery.validate", "common/utils", "common/services"],
function(e, t, n, r) {
    e(function() {
        jQuery.validator && (jQuery.validator.addMethod("userName",
        function(e, t) {
            return this.optional(t) || n.reMobileEmail.test(e)
        },
        "请填写正确的邮箱或手机号"), jQuery.validator.addMethod("imobile",
        function(e, t) {
            return this.optional(t) || n.reMobile.test(e)
        },
        "请填写正确的手机号"), jQuery.validator.addMethod("iemail",
        function(e, t) {
            return this.optional(t) || n.reEmail.test(e)
        },
        "请填写正确的邮箱"), jQuery.validator.addMethod("passWord",
        function(e, t) {
            return this.optional(t) || /^.{6,20}$/.test(e)
        },
        "密码为6-20个字符"), jQuery.validator.addMethod("authCode",
        function(e, t) {
            return this.optional(t) || /^\d{6}$/.test(e)
        },
        "请填写六位数字验证码"), jQuery.validator.addMethod("realName",
        function(e, t) {
            return this.optional(t) || /^[\u4E00-\u9FA5]{2,4}$/.test(e)
        },
        "请输入中文真实姓名"), jQuery.validator.addMethod("authNumber",
        function(e, t) {
            return this.optional(t) || !/[0-9]{5}/.test(e)
        },
        "不允许输入带有5位及以上的数字"), jQuery.validator.addMethod("checkMoney",
        function(e, t) {
            return this.optional(t) || /^([1-9][\d]{0,7}|0)(\.[\d]{1,2})?$/.test(e)
        },
        "金额必须大于等于零，小于等于99999999.99的数字"), jQuery.validator.addMethod("pInt",
        function(e, t) {
            var n = /\b(0+)/gi;
            return this.optional(t) || !n.test(e)
        },
        "请输入正整数"), jQuery.validator.addMethod("userSame",
        function(e, t) {
            var n = 1;
            return r.get("/account/check_username/", {
                field: "user",
                username: e
            },
            function(e, t, i) {
                e === r.CODE_SUCC && (n = i.exists)
            },
            null, !1),
            n === 0 ? !0 : !1
        },
        '该账号已被注册，<a href="javascript:;">直接登录?</a>'))
    })
}),
define("common/jiathis", ["jquery"],
function(e) {
    var t = "2037269",
    n = "http://www.jiathis.com/send/",
    r = "http://www.fuwo.com";
    e(function(e) {
        e(document).delegate(".J-Share", "click",
        function(i) {
            i.stopPropagation();
            var s = e(this),
            o = s.data("webid"),
            u,
            a = s.data("title") || document.title;
            s.data("url") ? s.data("url").indexOf("http") >= 0 ? u = s.data("url") : u = r + s.data("url") : u = location.href,
            window.open(n + "?webid=" + o + "&url=" + u + "&title=" + a + "&uid=" + t)
        })
    })
}),
define("common/globalsearch", ["jquery"],
function(e) {
    var t = e(".header-search"),
    n = e(".header-search .J-Search-Label"),
    r = e(".header-search .J-Type-List"),
    i = e(".header-search .J-Search-Input"),
    s,
    o = function() {
        return {
            clear: function() {
                s && clearTimeout(s)
            },
            start: function() {
                s = setTimeout(function() {
                    n.removeClass("border"),
                    n.css("background", "#fafafa"),
                    n.find("i").attr("class", "share-icon-down"),
                    r.css("display", "none")
                },
                100)
            }
        }
    } (),
    u = function() {
        var t = e.trim(i.val());
        t != "" && (location.href = n.find("span").data("href") + "?query=" + t)
    };
    return e(function(e) {
        n.on("mouseenter",
        function() {
            e(this).addClass("border"),
            e(this).find("i").attr("class", "share-icon-shang"),
            r.css("display", "block"),
            n.css("background", "#fff"),
            o.clear()
        }).on("mouseleave",
        function() {
            o.start()
        }),
        r.on("mouseenter",
        function() {
            o.clear()
        }).on("mouseleave",
        function() {
            o.start()
        }),
        r.find("li").click(function() {
            var t = e(this).data("href"),
            s = e(this).data("placeholder");
            n.find("span").html(e(this).html()).attr({
                "data-href": t
            }),
            i.val("").attr("placeholder", s),
            r.css("display", "none"),
            console.log(),
            i[0].__emptyHintEl && (i[0].__emptyHintEl.innerHTML = s)
        }),
        e(".J-Search-Input").bind("keyup",
        function(e) {
            var t = e.which;
            t === 13 && u()
        }),
        e(".J-Search-Btn").click(function(e) {
            u()
        })
    }),
    {
        relevanceData: function(e, t) {
            return _relevanceData(e, t)
        }
    }
}),
define("common/tab", ["jquery"],
function(e) {
    var t = null,
    n = null,
    r = function(t, r) {
        var i = "." + t + " a";
        e(i).click(function(t) {
            t.preventDefault(),
            n = e(this).attr("data-href"),
            e.each(e(i),
            function(t, i) {
                var s = e(i),
                o = e(s.attr("data-href"));
                s.attr("data-href") === n ? (s.parent("li").addClass("active"), o.addClass("active in"), r && r[t]()) : (s.parent("li").removeClass("active"), o.removeClass("active in"))
            })
        })
    };
    return {
        init: function(e, t) {
            r(e, t)
        }
    }
}),
define("common/paginator", ["jquery"],
function(e) {
    var t, n, r = "",
    i = 2,
    o = 0,
    u = 0,
    a = 0,
    f = 0,
    l, c = 0,
    h = function() {
        a = 0,
        f = 0,
        u > 10 && (o > 4 && (a = o - i), u > 7 && o < u - 4 && (f = o + i))
    },
    p = function() {
        if (o === 0 || u <= 1) return "";
        s = [],
        s.push('<nav><ul class="pagination">'),
        o === 1 ? s.push('<li class="disabled"><a href="javascript:void(0);" aria-label="上一页"><span aria-hidden="true">&laquo;</span>上一页</a></li>') : s.push('<li data-page="' + (o - 1) + '" class="js-page"><a href="javascript:void(0);" aria-label="上一页"><span aria-hidden="true">&laquo;</span>上一页</a></li>');
        if (a > 0) s.push('<li data-page="1" class="' + (1 === o ? "active": "js-page") + '"><a href="javascript:void(0);">1</a></li>'),
        s.push('<li><a href="javascript:void(0);" class="none-border"><em>...</em></a></li>');
        else for (c = 1; c < o; c++) s.push('<li class="' + (c === o ? "active": "js-page") + '" data-page="' + c + '"><a href="javascript:void(0);">' + c + "</a></li>");
        if (a > 0 && f > 0) for (c = a; c < f + 1; c++) s.push('<li class="' + (c === o ? "active": "js-page") + '" data-page="' + c + '"><a href="javascript:void(0);">' + c + "</a></li>");
        else if (a === 0 && f > 0) for (c = o; c < 7; c++) s.push('<li class="' + (c === o ? "active": "js-page") + '" data-page="' + c + '"><a href="javascript:void(0);">' + c + "</a></li>");
        if (f > 0) s.push('<li><a href="javascript:void(0);" class="none-border"><em>...</em></a></li>'),
        s.push('<li data-page="' + u + '" class="js-page"><a href="javascript:void(0);">' + u + "</a></li>");
        else {
            var e = o;
            a > 0 && (e = a);
            for (c = e; c <= u; c++) s.push('<li class="' + (c === o ? "active": "js-page") + '" data-page="' + c + '"><a href="javascript:void(0);">' + c + "</a></li>")
        }
        return o === u ? s.push('<li class="disabled"><a href="javascript:void(0);" aria-label="下一页">下一页<span aria-hidden="true">&raquo;</span></a></li>') : s.push('<li data-page="' + (o + 1) + '" class="js-page"><a href="javascript:void(0);" aria-label="下一页">下一页<span aria-hidden="true">&raquo;</span></a></li>'),
        s.push("</ul></nav>"),
        s.join("\n")
    },
    d = function() {
        h(),
        t.html(p()),
        e("#" + n + " .js-page").click(function(t) {
            t.preventDefault();
            var n = parseInt(e(this).data("page"));
            l(n),
            o = n,
            d()
        })
    },
    v = function(r, i, s, a) {
        n = r,
        t = e("#" + n);
        if (t.length !== 1 || a === undefined) return;
        l = a ||
        function() {},
        o = Math.max(1, i),
        u = Math.max(o, s),
        d()
    };
    return {
        init: function(e, t, n, r) {
            v(e, parseInt(t), parseInt(n), r)
        }
    }
}),
define("common/base", ["require", "jquery", "lib/jbase64", "common/services", "common/utils", "common/ui", "common/demand", "common/template", "common/validate.expand", "common/weixin", "common/jiathis", "common/globalsearch", "common/webapi", "common/tab", "common/paginator", "account/main", "common/citycascade"],
function(e, t, n, r, i, s, o, u, a, f, l, c, h, p, d, v, m) {
    return {
        debug: location.host.indexOf(".fuwo.com") !== -1 ? !1 : !0,
        tpls: u,
        domain: location.protocol + "//" + location.host,
        services: r,
        webapi: h,
        account: v,
        utils: i,
        ui: s,
        demand: o,
        globalsearch: c,
        com: {
            tab: p,
            paginator: d,
            cityselect: m
        },
        open: {
            weixin: f
        }
    }
});