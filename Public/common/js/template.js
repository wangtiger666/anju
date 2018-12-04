!
function() {
    function e(e, t) {
        return (/string|function/.test(typeof t) ? u: o)(e, t)
    }
    function t(e, n) {
        return "string" != typeof e && (n = typeof e, "number" === n ? e += "": e = "function" === n ? t(e.call(e)) : ""),
        e
    }
    function n(e) {
        return h[e]
    }
    function r(e) {
        return t(e).replace(/&(?![\w#]+;)|[<>"']/g, n)
    }
    function i(e, t) {
        if (p(e)) for (var n = 0,
        r = e.length; r > n; n++) t.call(e, e[n], n, e);
        else for (n in e) t.call(e, e[n], n)
    }
    function s(e, t) {
        var n = /(\/)[^/] + \1\.\.\1 / ,
        r = ("./" + e).replace(/[^/] + $ / ,
        ""),
        i = r + t;
        for (i = i.replace(/\/\.\//g, "/"); i.match(n);) i = i.replace(n, "/");
        return i
    }
    function o(t, n) {
        var r = e.get(t) || a({
            filename: t,
            name: "Render Error",
            message: "Template not found"
        });
        return n ? r(n) : r
    }
    function u(e, t) {
        if ("string" == typeof t) {
            var n = t;
            t = function() {
                return new c(n)
            }
        }
        var r = l[e] = function(n) {
            try {
                return new t(n, e) + ""
            } catch(r) {
                return a(r)()
            }
        };
        return r.prototype = t.prototype = d,
        r.toString = function() {
            return t + ""
        },
        r
    }
    function a(e) {
        var t = "{Template Error}",
        n = e.stack || "";
        if (n) n = n.split("\n").slice(0, 2).join("\n");
        else for (var r in e) n += "<" + r + ">\n" + e[r] + "\n\n";
        return function() {
            return "object" == typeof console && console.error(t + "\n\n" + n),
            t
        }
    }
    function f(e) {
        var t = new Date((e || "").replace(/-/g, "/").replace(/[TZ]/g, " ")),
        n = (t.getTime() - (new Date).getTime()) / 1e3,
        r = Math.floor(n / 86400);
        return isNaN(r) || 0 >= r ? r = 0 : r
    }
    var l = e.cache = {},
    c = this.String,
    h = {
        "<": "&#60;",
        ">": "&#62;",
        '"': "&#34;",
        "'": "&#39;",
        "&": "&#38;"
    },
    p = Array.isArray ||
    function(e) {
        return "[object Array]" === {}.toString.call(e)
    },
    d = e.utils = {
        $helpers: {},
        $include: function(e, t, n) {
            return e = s(n, e),
            o(e, t)
        },
        $string: t,
        $escape: r,
        $each: i
    },
    v = e.helpers = d.$helpers;
    e.get = function(e) {
        return l[e.replace(/^\.\//, "")]
    },
    e.helper = function(e, t) {
        v[e] = t
    },
    "function" == typeof define ? define([],
    function() {
        return e
    }) : "undefined" != typeof exports ? module.exports = e: this.template = e,
    e.helper("subStr",
    function(e, t) {
        return e.length > t ? e.substr(0, parseInt(t)) + "...": e
    }),
    e.helper("delHtmlTag",
    function(e) {
        return e.replace(/<[^>]+>/g, "")
    }),
    e.helper("beautyDate",
    function(e) {
        var t = new Date((e || "").replace(/-/g, "/").replace(/[TZ]/g, " ")),
        n = ((new Date).getTime() - t.getTime()) / 1e3,
        r = Math.floor(n / 86400);
        if (! (isNaN(r) || 0 > r)) return r >= 31 ? e: 0 == r && (60 > n && "刚刚" || 120 > n && "1分钟前" || 3600 > n && Math.floor(n / 60) + "分钟前" || 7200 > n && "1个小时前" || 86400 > n && Math.floor(n / 3600) + "小时前") || 1 == r && "昨天" || 7 > r && r + "天前" || 31 > r && Math.ceil(r / 7) + "周前"
    }),
    e.helper("formatTime",
    function(e, t) {
        return e.length > t ? e.substr(0, parseInt(t)) : e
    }),
    e.helper("countDown",
    function(e) {
        var t = f(e);
        return t > 0 ? "仅剩 " + t + " 天": void 0
    }),
    e.helper("bidcountDown",
    function(e) {
        var t = f(e);
        return t > 0 ? "仅剩" + t + "天招标截止": void 0
    }),
    e.helper("defaultVal",
    function(e, t) {
        return "" == e ? t: e
    }),
    e.helper("inputChecked",
    function(e, t) {
        return t == e ? "checked": ""
    }),
    e.helper("designerLevel",
    function(e) {
        switch (parseInt(e)) {
        case 1:
            return "体验设计师";
        case 2:
            return "认证设计师";
        case 3:
            return "签约设计师";
        default:
            return "设计师"
        }
        return "设计师"
    }),
    e.helper("formatCurrency",
    function(e, t) {
        t = t || "";
        var n = e.toString().replace(/\$|\,/g, "");
        isNaN(n) && (n = "0"),
        sign = n == (n = Math.abs(n)),
        n = Math.floor(100 * n + .50000000001),
        cents = n % 100,
        n = Math.floor(n / 100).toString(),
        10 > cents && (cents = "0" + cents);
        for (var r = 0; r < Math.floor((n.length - (1 + r)) / 3); r++) n = n.substring(0, n.length - (4 * r + 3)) + t + n.substring(n.length - (4 * r + 3));
        return (sign ? "": "-") + n + "." + cents
    }),
    e.helper("dictObj",
    function(e, t) {
        return e[t]
    }),
    e("account/login", '<div class="sign-wrap" id="sign_wrap"> <div class="top-tab"> <a class="tab-opt active" href="javascript:;">微信登录</a> <a class="tab-opt" href="javascript:;">账号登录</a> </div> <div class="tab-panels"> <div class="tab-panel weixin-panel" style="display:block;"> </div> <div class="tab-panel form-panel"> <form class="login-form" id="login_form"> <div class="form"> <div class="inputline-wrap"> <div class="inputline"> <p class="input"><input class="control" id="sign_username" name="username" type="text" placeholder="手机号/邮箱"><i class="icon share-icon-user"></i></p> </div> <div class="inputline"> <p class="input"><input class="control" id="sign_pwd" name="pwd" type="password" placeholder="密码"><i class="icon share-icon-password"></i></p> </div> </div> <div class="inputline margin-bottom-20"> <p class="float-left"><label class="rem"><input class="rem_i" id="sign_remember" type="checkbox">记住我</label></p> <a class="float-right" href="http://www.fuwo.com/account/reset_password/">忘记密码？</a> </div> <div class="btn-box"> <span class="btn btn-success" id="sign_submit">登录</span> </div> </form> </div> <div class="open-login f12"> <a class="qq J-Open" data-open="qq" href="javascript:;"><i class="icon share-icon-qq"></i>QQ</a> <a class="weibo J-Open" data-open="weibo" href="javascript:;"><i class="icon share-icon-blog"></i>微博</a> <a class="taobao J-Open" data-open="taobao" href="javascript:;"><i class="icon share-icon-taobao"></i>淘宝</a> <a class="float-right sign J-Open" data-open="sign" href="javascript:;">免费注册</a> </div> </div> </div> </div>'),
    e("baojia/pricelist",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$escape),
        r = e.total_price,
        i = t.$each,
        s = e.detail,
        o = (e.value, e.index, e.item, e.$index, e.list, "");
        return o += ' <div class="container baojia-lists"> <div class="list-title">估算价格：<span>',
        o += n(r),
        o += "</span>元<em>提示：该价格仅为估算价格，精准价格以量房为准</em></div> ",
        i(s,
        function(e, t) {
            o += ' <div class="list ',
            0 == t && (o += "active"),
            o += '"> <p class="title">',
            o += n(e.name),
            o += "<span>预算：<em>",
            o += n(e.total_price),
            o += '</em>元</span><i class="',
            o += 0 == t ? "share-icon-up": "share-icon-down",
            o += '"></i></p> <table> <colgroup> <col width="104"> <col width="248"> <col width="84"> <col width="72"> <col width="72"> <col width="400"> </colgroup> <tr> <th>空间工程</th> <th>工程项目</th> <th>工程量</th> <th>单价</th> <th>总价</th> <th>工艺标准</th> </tr> ',
            i(e.detail,
            function(e) {
                o += " ",
                i(e.list,
                function(t, r) {
                    o += " <tr> ",
                    0 == r && (o += ' <td class="category" rowspan="', o += n(e.list.length), o += '">', o += n(e.name), o += "</td> "),
                    o += " <td>",
                    o += n(t.des),
                    o += "</td> <td>",
                    o += n(t.total_amount),
                    o += "</td> <td>",
                    o += n(t.unit_price),
                    o += "</td> <td>",
                    o += n(t.total_price),
                    o += "</td> <td>",
                    o += n(t.note),
                    o += "</td> </tr> "
                }),
                o += " "
            }),
            o += " </table> </div> "
        }),
        o += " </div>",
        new c(o)
    }),
    e("bbs/fuwosmall",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$each),
        r = e.records,
        i = (e.value, e.$index, t.$escape),
        s = "";
        return s += '<div class="ifuwo-large-small"> <div class="ifuwo-small-list"> <a href="javascript:;" class="J-Small-Prev small-prev"><i class="share-arrow-left"></i></a> <div class="ifuwo-slide-wrapper"> <ul class="slides-list"> ',
        n(r,
        function(e) {
            s += ' <li class="slide" data-src="',
            s += i(e.photo_url),
            s += '@!640x1000_e1.jpg" data-no="',
            s += i(e.design_no),
            s += '"> <img class="lazy" src="" data-lazyload="',
            s += i(e.photo_url),
            s += '@!180x135.jpg"> </li> '
        }),
        s += ' </ul> </div> <a href="javascript:;" class="J-Small-Next small-next"><i class="share-arrow-right"></i></a> </div> </div> ',
        new c(s)
    }),
    e("comment/listItem",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$escape),
        r = e.id,
        i = e.parent,
        s = e.user__id,
        o = e.user__avatar,
        u = e.user__first_name,
        a = e.object_user,
        f = t.$string,
        l = e.content,
        h = "";
        return h += '<li> <div class="reply-item inner" data-id="',
        h += n(r),
        h += '" data-applabel="comment" data-model="comment" data-parent="',
        h += n(i.id),
        h += '"> <a href="/member/center/',
        h += n(s),
        h += '/" target="_blank"><img src="',
        h += n(o),
        h += '" class="img-32"></a> <p> <b> <a href="/member/center/',
        h += n(s),
        h += '/" target="_blank">',
        h += n(u),
        h += '</a></b> 回复 <b><a href="/member/center/',
        h += n(a.user__id),
        h += '/" target="_blank">',
        h += n(a.user__first_name),
        h += '</a></b> </p> <div class="reply-content">',
        h += f(l),
        h += '</div> <p class="comment-operate"><em>刚刚</em> <a href="javascrip:;" class="js-comment-delete">删除</a> | <a href="javascrip:;" class="js-comment-reply">回复</a></p> <p class="reply"></p> </div> </li>',
        new c(h)
    }),
    e("comment/newReply", ' <li class="bbs-reply"> <a name="dongfangmingzhu"></a> <img class="reply-pic" src="/static/images/new/bbs/demoimg/test.png"> <div class="reply-info"> <label><i>楼主</i><span>#1</span></label> <a href="">召见送</a> 于5个月前发表 </div> <div class="reply-qunzhu"> | 引用 <a href="">2楼</a> <a href="">@做好的</a>楼主涉及很不错 </div> <div class="reply-contents"> 欧式家具卧室套装想定做欧式家具，求好的设计定做公司，不错~~ </div> <div class="reply-opp"> <a class="J-Reply-Edit" data-id="" href="javacriptt:void(0)">修改</a> <a class="J-Reply-del" data-id="" href="javacriptt:void(0)">删除</a> <a class="J-Reply-List" data-id="" data-zoom="15" data-author="召见送" href="#content"> <i class="share-icon-reply-default"></i> 回复</a> <span class="J-Zan" data-id=""> <i class="share-icon-like-default"></i> <em>赞</em></span> <em class="J-Number">0</em> </div> </li>'),
    e("comment/replyMain",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, e.parent),
        r = t.$escape,
        i = e.id,
        s = e.user__id,
        o = e.user__avatar,
        u = e.user__first_name,
        a = t.$string,
        f = e.content,
        l = e.object_user,
        h = "";
        return h += '<li class="reply-main"> ',
        n ? (h += ' <div class="reply-item" data-id="', h += r(i), h += '" data-applabel="comment" data-model="comment" data-parent="', h += r(n.id), h += '"> ') : (h += ' <div class="reply-item" data-id="', h += r(i), h += '" data-applabel="comment" data-model="comment" data-parent="', h += r(i), h += '"> '),
        h += ' <a href="/member/center/',
        h += r(s),
        h += '/" target="_blank"><img src="',
        h += r(o),
        h += '" class="img-48"></a> <p> <b> <a href="/member/center/',
        h += r(s),
        h += '/" target="_blank">',
        h += r(u),
        h += '</a></b> </p> <div class="reply-content">',
        h += a(f),
        h += '</div> <p class="comment-operate"><em>刚刚</em>',
        s == l.user__id && (h += '<a href="javascript:void(0);" class="js-comment-delete">删除</a> |'),
        h += ' <a href="javascript:void(0);" class="js-comment-reply">回复</a></p> <p class="comment-label">&nbsp;</p> <p class="reply"></p> </div> <ul class="reply-list"></ul> </li>',
        new c(h)
    }),
    e("common/baojia/baojia_calcul", '<div class="baojia-calcul" style="height: auto;"> <h2>装修报价计算器</h2> <form id="subscribe_form"> <div class="row-list form-group"> <select id="selPro" class="J-selProvince place-city" name="city" placeholder="省市"> <option value="">省/市</option> </select> <select id="selCty" class="J-selCity place-city"> <option value="">市/地区</option> </select> </div> <div class="row-list form-group"> <input class="input-area" id="area" name="area" type="text" placeholder="建筑面积" maxlength="10"> <em class="unit">m²</em> </div> <div class="row-list form-group"> <input class="phone" id="phone" name="phone" type="text" placeholder="输入手机号码，获取报价结果" maxlength="11"> </div> <button class="construct-order submit J-Apply">获取报价</button> </form> </div> '),
    e("common/baoming/popup",
    function(e) {
        "use strict";
        var t = this,
        n = t.$helpers,
        r = t.$escape,
        i = e.style,
        s = e.title,
        o = e.dayCount,
        u = e.monthCount,
        a = "";
        
    }),
    e("common/city/select", '<div class="map-search"> <i>城市</i> <input type="text" id="city_search"> <span class="btn btn-orange btn-middle" id="city_check">确定</span> <ul class="map-search-result" id="search_showResult"></ul> </div> <ul class="map-li-style"> <li class="item" data-cityid="0" data-cityname="全国"><span>全国</span></li> <li class="item" data-cityid="1211" data-cityname="北京"><span>北京</span></li> <li class="item" data-cityid="3511" data-cityname="上海"><span>上海</span></li> <li class="item" data-cityid="1311" data-cityname="重庆"><span>重庆</span></li> <li class="item" data-cityid="3811" data-cityname="天津"><span>天津</span></li> </ul> <ul class="map-li-style" id="city_show"> </ul> '),
    e("common/icheck_radio",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$escape),
        r = e.type,
        i = e.id,
        s = e.name,
        o = t.$string,
        u = e.content,
        a = "";
        return a += '<input type="',
        a += n(r),
        a += '" id="',
        a += n(i),
        a += '" name="',
        a += n(s),
        a += '"> <label for="',
        a += n(i),
        a += '">',
        a += o(u),
        a += "</label>",
        new c(a)
    }),
    e("common/icheck_radio_list",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$each),
        r = e.list,
        i = (e.value, e.$index, t.$escape),
        s = "";
        return n(r,
        function(e) {
            s += ' <input type="',
            s += i(e.type),
            s += '" id="',
            s += i(e.id),
            s += '" name="',
            s += i(e.name),
            s += '" value="',
            s += i(e.value),
            s += '"> <label for="',
            s += i(e.id),
            s += '">',
            s += i(e.content),
            s += "</label> "
        }),
        new c(s)
    }),
    e("common/none_content",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$string),
        r = e.content,
        i = "";
        return i += '<div class="tabs-list none-message"> <i class="icon icon-sad"></i> <div class="none-message-text"> ',
        i += n(r),
        i += " </div> </div>",
        new c(i)
    }),
    e("common/popup/confirm",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$escape),
        r = e.height,
        i = e.width,
        s = e.marginTop,
        o = e.marginLeft,
        u = e.title,
        a = t.$string,
        f = e.content,
        l = "";
        
    }),
    e("common/popup/dialog",
    function(e) {
        "use strict";
        var t = this,
        n = t.$helpers,
        r = t.$escape,
        i = e.style,
        s = e.title,
        o = t.$string,
        u = e.content,
        a = "";
        return a += '<div class="screen-bg"></div> <div class="pop" style="width: ',
        a += r(i.width),
        a += "; margin-top: ",
        a += r(i.marginTop),
        a += "; margin-left: ",
        a += r(i.marginLeft),
        a += ";height:",
        a += r(n.defaultVal(i.height, "250px;")),
        a += '"> <span class="pop-close pop-ie7 J-Ie7">x</span> <span class="pop-close icon-cross"></span> <div class="pop-title">',
        a += r(s),
        a += '</div> <div class="pop-content"> ',
        a += o(u),
        a += " </div> </div>",
        new c(a)
    }),
    e("common/popup/float_window",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, e.istriangle),
        r = e.direction,
        i = t.$escape,
        s = e.classname,
        o = e.left,
        u = e.top,
        a = t.$string,
        f = e.content,
        l = "";
        return n ? (l += " ", r ? (l += ' <div class="float-window triangle-left ', l += i(s), l += '" style="left:', l += i(o), l += ";top:", l += i(u), l += '"> ') : (l += ' <div class="float-window triangle-right ', l += i(s), l += '" style="left:', l += i(o), l += ";top:", l += i(u), l += '"> '), l += " ") : (l += ' <div class="float-window ', l += i(s), l += '" style="left:', l += i(o), l += ";top:", l += i(u), l += '"> '),
        l += " ",
        l += a(f),
        l += " </div>",
        new c(l)
    }),
    e("common/popup/message",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$escape),
        r = e.classname,
        i = e.top,
        s = e.message,
        o = "";
        return o += '<div id="message" class="message ',
        o += n(r),
        o += '" style="top:',
        o += n(i),
        o += '"> <span class="message-close icon-cross"></span> <div class="message-content">',
        o += n(s),
        o += "</div> </div>",
        new c(o)
    }),
    e("common/search_result",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, e.houselayout),
        r = t.$escape,
        i = e.title,
        s = t.$each,
        o = (e.$value, e.$index, t.$string),
        u = e.topic,
        a = e.photo,
        f = "";
        return f += "<div> ",
        n && (f += ' <div> <p class="type">', f += r(i), f += '</p> <ul class="result"> ', s(n,
        function(e) {
            f += ' <li class="article-li ',
            f += r(e.more),
            f += '"><a href="',
            f += r(e.href),
            f += '" target="_blank" >',
            f += o(e.title),
            f += "</a></li> "
        }), f += " </ul> </div> "),
        f += " ",
        u && (f += ' <div> <p class="type">帖子</p> <ul class="result"> ', s(u,
        function(e) {
            f += ' <li class="article-li ',
            f += r(e.more),
            f += '"><a href="',
            f += r(e.href),
            f += '" target="_blank" >',
            f += o(e.title),
            f += "</a></li> "
        }), f += " </ul> </div> "),
        f += " ",
        a && (f += ' <div class="s-r-photo"> <p class="type">效果图</p> <ul class="result"> ', s(a,
        function(e) {
            f += " ",
            e.src ? (f += ' <li class="photo-li"><a href="', f += r(e.href), f += '" target="_blank" > <img src="', f += r(e.src), f += '" alt="', f += o(e.title), f += '" title="', f += o(e.title), f += '"/></a></li> ') : (f += ' <li class="photo-more ', f += r(e.more), f += '"><a href="', f += r(e.href), f += '" target="_blank" >', f += o(e.title), f += "</a></li> "),
            f += " "
        }), f += " </ul> </div> "),
        f += " </div>",
        new c(f)
    }),
    e("common/submit_result",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$escape),
        r = e.title,
        i = e.content,
        s = t.$each,
        o = e.btnList,
        u = (e.value, e.$index, e.skip),
        a = "";
        return a += '<div class="row success-success"> <div class="col-md-3"> </div> <div class="col-md-1 success-icon"> <i class="icon-circle-check"></i> </div> <div class="col-md-5 success-item"> <div class="success-title"> ',
        a += n(r),
        a += ' </div> <p class="success-content"> ',
        a += n(i),
        a += ' </p> <div class="success-go"> ',
        s(o,
        function(e) {
            a += ' <a class="btn btn-middle btn-success" href="',
            a += n(e.url),
            a += '">',
            a += n(e.title),
            a += "</a> "
        }),
        a += ' </div> <span class="success-time"><em id="count-down">5</em>秒后转入',
        a += n(u.title),
        a += '</span> </div> <div class="col-md-3"> </div> </div> ',
        new c(a)
    }),
    e("common/verification", '<div class="verification"> <img src="" id="verifycode"/> <span class="refresh" id="js-refresh-code">换一下</span> <br> <input type="text" id="verification" name="verification" max-length="4"> <span class="btn btn-small btn-success js-confirm">确认</span> </div>'),
    e("demand/baoming_bottom", '<div class="baojia-bottom-small J-open-inquiry"> <div class="bg-color"></div> <div class="right-round"></div> <img src="http://static.fuwo.com/static/images/new/pfuwo/relative/pic_zhuanxiubaojia.png"> <i class="share-icon_dakai"></i> </div> <div class="baojia-bottom-big dsn"> <div class="container"> <div class="inquiry-banner"> <img src="http://static.fuwo.com/static/images/new/pfuwo/relative/PIC_mianfeibaojia.png"> </div> <div class="inquiry-baojia"> <div class="inquiry-info"> <form id="baojia_bottom_form" class="form-radius"> <dl class="form-list"> <dd class="per-width-100"> <select id="selProBottom" class="J-selProvince place-city margin-right-5" name="city" placeholder="省市"> <option value="">省/市</option> </select> <select id="selCtyBottom" name="selCty" class="J-selCity place-city"> <option value="">市/地区</option> </select> </dd> </dl> <dl class="form-list"> <dd class="per-width-100"><input type="text" id="area" name="area" placeholder="建筑面积" maxlength="10"> <em class="unit">m²</em></dd> </dl> <dl class="form-list"> <dd class="per-width-100"><input type="text" id="phone" name="phone" placeholder="输入手机号码，获取报价结果" maxlength="11"></dd> </dl> </form> <div class="baojia-bottom-tip"> <span>*本价格为毛坯房半包估算价格（不含水电报价），旧房价格由实际工程量决定。</span> <span>*稍后装修管家将致电您，为您提供免费装修咨询服务。</span> </div> <div class="calcul-btn"><i class="share-icon_anniuzhouwei"></i><span class="J-Calculate">开始计算</span></div> <div class="baojia-bottom-budget"> <h3>您的装修预算约 <span class="J-TotalPrice">0</span>万元</h3> <a href="javascript:;" class="J-specific-baojia">详细报价</a> <ul> <li><span><em>卧室预算 : </em><i class="J-Bedroom">0&nbsp;元</i></span><span><em>客厅预算 : </em><i class="J-Liveroom">0&nbsp;元</i></span></li> <li><span><em>厨房预算 : </em><i class="J-Kitchen">0&nbsp;元</i></span><span><em>阳台预算 : </em><i class="J-Washroom">0&nbsp;元</i></span></li> <li><span><em>卫生间预算 : </em><i class="J-Balcony">0&nbsp;元</i></span><span><em>其他预算 : </em><i class="J-Other">0&nbsp;元</i></span></li> </ul> </div> </div> </div> <i class="share-icon_guanbi close J-close-inquiry"></i> </div> </div> '),
    e("demand/baoming_calcul", '<div class="baojia-calcul" style="height: auto;"> <h2>装修报价计算器</h2> <form id="baojia_form" class="form-radius"> <dl class="form-list"> <dd class="per-width-100"> <select id="selPro" class="J-selProvince place-city margin-right-5" name="city" placeholder="省市"> <option value="">省/市</option> </select> <select id="selCty" name="selCty" class="J-selCity place-city"> <option value="">市/地区</option> </select> </dd> </dl> <dl class="form-list"> <dd class="per-width-100"><input type="text" id="area" name="area" placeholder="建筑面积" maxlength="10"> <em class="unit">m²</em></dd> </dl> <dl class="form-list"> <dd class="per-width-100"><input type="text" id="phone" name="phone" placeholder="输入手机号码，获取报价结果" maxlength="11"></dd> </dl> <dl class="form-list"> <span class="btn btn-full btn-red J-Apply">获取报价</span> </dl> </form> </div> '),
    e("demand/baoming_normal",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, e.serviceCount),
        r = t.$escape,
        i = "";
        return i += '<div class="baoming-normal"> <span class="span-title">免费设计与报价</span> <span class="span-tip">10秒登记，免费获取专业装修方案</span> <form id="baojia_normal_form" class="form-radius"> <dl class="form-list"> <dd class="per-width-100"> <input type="text" id="username" name="username" placeholder="您的称呼" maxlength="32"> </dd> </dl> <dl class="form-list"> <dd class="per-width-100"> <input type="text" id="mobile" name="mobile" placeholder="您的电话" maxlength="11"> </dd> </dl> <dl class="form-list"> <span class="btn btn-full btn-red J-Normal-Apply">立即申请</span> </dl> </form> ',
        n && (i += ' <span class="span-count">已有<i>', i += r(n), i += "</i>人报名</span> "),
        i += " </div> ",
        new c(i)
    }),
    e("imanage/houselayout",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$each),
        r = e.records,
        i = (e.$value, e.$index, t.$escape),
        s = "";
        return s += '<ul class="result-imgs"> ',
        n(r,
        function(e) {
            s += ' <li> <img width="240" height="240" class="lazy" src="http://static.fuwo.com/static/images/new/common/placeholder.jpg" data-lazyload="',
            s += i(e.thumbnail_url),
            s += '"> <div> <em>',
            s += i(e.area),
            s += "㎡ </em>",
            s += i(e.house_type_label),
            s += "<br/> <span>",
            s += i(e.city_name),
            s += " &nbsp;&nbsp;&nbsp;",
            s += i(e.community_name),
            s += '</span> </div> <a href="/myhome3d/?openCommand=OPEN_NEW_DESIGN_FROM_HOUSELAYOUT:',
            s += i(e.no),
            s += '" target="_blank">选择</a> </li> '
        }),
        s += " </ul>",
        new c(s)
    }),
    e("item/addItem", '<form class="model-form" id="model_detail"> <dl class="form-model-list"> <dt><span>*</span>名称：</dt> <dd><input class="J-Name" type="text" name="product_name" placeholder="请输入名称" value=""></dd> </dl> <dl class="form-model-list"> <dt><span>*</span>模型类型：</dt> <dd> <input class="J-Stype" id="model_default" data-type="default" data-id="" type="radio" name="model_type" value="1" checked> <label for="model_default">默认</label> <input class="J-Stype" id="model_material" data-type="material" data-id="" type="radio" name="model_type" value="4"> <label for="model_material">材质</label> <input class="J-Stype" id="model_door" data-type="door" data-id="" type="radio" name="model_type" value="2"> <label for="model_door">门</label> <input class="J-Stype" id="model_window" data-id="" data-type="window" type="radio" name="model_type" value="3"> <label for="model_window">窗</label> </dd> </dl> <dl class="form-model-list"> <dt><span>*</span>子类型：</dt>  <dd class="sub-type-info" data-type="default"> <input class="J-Sub" id="sub_type_default" type="radio" name="sub_type_default" value="101" checked> <label for="sub_type_default">默认</label> <input class="J-Sub" id="sub_type_space" type="radio" name="sub_type_default" value="104"> <label for="sub_type_space">墙面模型</label> <input class="J-Sub" id="sub_type_top" type="radio" name="sub_type_default" value="106"> <label for="sub_type_top">顶面模型</label> <input class="J-Sub" id="sub_type_bottom" type="radio" name="sub_type_default" value="102"> <label for="sub_type_bottom">平台模型</label> <input class="J-Sub" id="sub_type_ceiling" type="radio" name="sub_type_default" value="103"> <label for="sub_type_ceiling">台面模型</label> <input class="J-Sub" id="sub_type_decoration" type="radio" name="sub_type_default" value="105"> <label for="sub_type_decoration">贴地模型</label> </dd>  <dd class="sub-type-info ihide" data-type="material"> <input class="J-Sub" id="radio_material_1" type="radio" name="sub_type_material" value="401" checked> <label for="radio_material_1">地面材质</label> <input class="J-Sub" id="radio_material_2" type="radio" name="sub_type_material" value="402"> <label for="radio_material_2">墙面材质</label> <input class="J-Sub" id="radio_material_3" type="radio" name="sub_type_material" value="403"> <label for="radio_material_3">踢脚线</label> <input class="J-Sub" id="radio_material_4" type="radio" name="sub_type_material" value="404"> <label for="radio_material_4">顶面材质</label> </dd>  <dd class="sub-type-info ihide" data-type="door"> <input class="J-Sub" id="radio_door_1" type="radio" name="sub_type_door" value="201" checked> <label for="radio_door_1">单开门</label> <input class="J-Sub" id="radio_door_2" type="radio" name="sub_type_door" value="202"> <label for="radio_door_2">双开门</label> <input class="J-Sub" id="radio_door_3" type="radio" name="sub_type_door" value="203"> <label for="radio_door_3">移门</label> <input class="J-Sub" id="radio_door_4" type="radio" name="sub_type_door" value="204"> <label for="radio_door_4">门套</label> </dd>  <dd class="sub-type-info ihide" data-type="window"> <input class="J-Sub" id="radio_window_1" type="radio" name="sub_type_window" value="301" checked> <label for="radio_window_1">普通窗</label> <input class="J-Sub" id="radio_window_2" type="radio" name="sub_type_window" value="302"> <label for="radio_window_2">飘窗</label> </dd> </dl> <dl class="form-model-list"> <dt><span>*</span>光源类型：</dt> <dd> <input class="J-Light" id="light_1" type="radio" name="lightable" value="0" checked> <label for="light_1">无灯光</label> <input class="J-Light" id="light_2" type="radio" name="lightable" value="1"> <label for="light_2">默认光</label> </dd> </dl> <dl class="form-model-list"> <dt><span>*</span>模型尺寸：</dt> <div class="model-size"> <dd> <input class="J-Model-Width" type="text" class="form-control" placeholder="请输入模型宽度" name="trim_width" value=""> <span class="input-group-addon">宽x（厘米）</span> </dd> <dd> <input class="J-Model-Length" type="text" class="form-control" placeholder="请输入模型长度" name="trim_length" value=""> <span class="input-group-addon">长y（厘米）</span> </dd> <dd> <input class="J-Model-Height" type="text" class="form-control" placeholder="请输入模型高度" name="trim_height" value=""> <span class="input-group-addon">高z（厘米）</span> </dd> </div> </dl> <dl class="form-model-list"> <dt><span>*</span>是否公开：</dt> <dd> <input class="J-Is-Public" id="is_public_1" type="radio" name="is_public" value="1" checked="checked"> <label for="is_public_1">是</label> <input class="J-Is-Public" id="is_public_2" type="radio" name="is_public" value="0"> <label for="is_public_2">否</label> </dd> </dl> <dl class="form-model-list ml10"> <dt>所属品牌：</dt> <dd><input class="product-brand J-Brand" type="text" name="product_brand"></dd> </dl> <dl class="form-model-list ml10"> <dt>产品货号：</dt> <dd><input class="product-no J-No" type="text" name="product_no"></dd> </dl> <dl class="form-model-list"> <dt><span>*</span>产品单位：</dt> <dd> <select class="unit-select J-Unit" name="unit"> <option value="1" selected="">件</option> <option value="2">套</option> <option value="3">块</option> </select> </dd> </dl> <dl class="form-model-list"> <dt><span>*</span>是否销售商品：</dt> <dd> <input class="J-Ionsale" type="radio" name="salable" value="1" id="is_onsale_1"> <label for="is_onsale_1">是</label> <input class="J-Ionsale" type="radio" name="salable" value="0" checked="checked" id="is_onsale_2"> <label for="is_onsale_2">否</label> </dd> </dl>  <dl class="form-model-list ml10 ihide J-Show"> <dt> 商品链接： </dt> <dd> <input class="J-Link" type="text" name="product_link" placeholder="请输入商品链接"> </dd> </dl> <dl class="form-model-list ihide J-Show"> <dt> <span>*</span>商品价格： </dt> <dd> <input class="J-Price" type="text" name="product_price" placeholder="请输入商品价格" value="0" aria-invalid="false"> <span class="input-group-addon">元</span> </dd> </dl> <dl class="form-model-list ihide J-Show"> <dt> <span>*</span>在线价格： </dt> <dd> <input class="J-Line-Price" type="text" name="discount_price" placeholder="请输入在线价格" value="0" aria-invalid="false"> <span class="input-group-addon">元</span> </dd> </dl> <div class="widget-form-next"><span class="btn btn-middle btn-orange J-Save" data-no="">保存</span></div> </form> '),
    e("item/detailItem",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$escape),
        r = e.record,
        i = t.$each,
        s = e.categorys,
        o = (e.value, e.$index, "");
        return o += '<form class="model-form" id="model_detail"> <dl class="form-model-list"> <dt>模型编号：</dt> <dd>',
        o += n(r.no),
        o += '</dd> </dl> <dl class="form-model-list"> <dt>模型分类：</dt> <dd> ',
        i(s,
        function(e) {
            o += ' <em data-id="',
            o += n(e.id),
            o += '" data-no="',
            o += n(r.no),
            o += '">',
            o += n(e.name),
            o += ' <i class="J-Del-ItemCategory">x</i></em> '
        }),
        o += ' <a class="J-Set" href="javascript:void(0);" data-no="',
        o += n(r.no),
        o += '" data-rootid="',
        o += n(r.stand_category_id),
        o += '">设置分类</a> </dd> </dl> <dl class="form-model-list"> <dt><span>*</span>名称：</dt> <dd><input class="J-Name" type="text" name="product_name" placeholder="请输入名称" value="',
        o += n(r.product_name),
        o += '"></dd> </dl> <dl class="form-model-list ',
        40 == r.status && (o += "form-model-disabeld"),
        o += '"> <dt><span>*</span>模型类型：</dt> <dd> <input class="J-Stype" id="model_default" data-type="default" data-id="" type="radio" name="model_type" value="1" ',
        1 == r.inner_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="model_default">默认</label> <input class="J-Stype" id="model_material" data-type="material" data-id="" type="radio" name="model_type" value="4" ',
        4 == r.inner_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="model_material">材质</label> <input class="J-Stype" id="model_door" data-type="door" data-id="" type="radio" name="model_type" value="2" ',
        2 == r.inner_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="model_door">门</label> <input class="J-Stype" id="model_window" data-id="" data-type="window" type="radio" name="model_type" value="3" ',
        3 == r.inner_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="model_window">窗</label> </dd> </dl> <dl class="form-model-list ',
        40 == r.status && (o += "form-model-disabeld"),
        o += '"> <dt><span>*</span>子类型：</dt>  <dd class="sub-type-info ',
        1 != r.inner_type && (o += "ihide"),
        o += '" data-type="default"> <input class="J-Sub" id="sub_type_default" type="radio" name="sub_type_default" value="101" ',
        101 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="sub_type_default">默认</label> <input class="J-Sub" id="sub_type_space" type="radio" name="sub_type_default" value="104" ',
        104 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="sub_type_space">墙面模型</label> <input class="J-Sub" id="sub_type_top" type="radio" name="sub_type_default" value="106" ',
        106 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="sub_type_top">顶面模型</label> <input class="J-Sub" id="sub_type_bottom" type="radio" name="sub_type_default" value="102" ',
        102 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="sub_type_bottom">平台模型</label> <input class="J-Sub" id="sub_type_ceiling" type="radio" name="sub_type_default" value="103" ',
        103 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="sub_type_ceiling">台面模型</label> <input class="J-Sub" id="sub_type_decoration" type="radio" name="sub_type_default" value="105" ',
        105 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="sub_type_decoration">贴地模型</label> </dd>  <dd class="sub-type-info ',
        4 != r.inner_type && (o += "ihide"),
        o += '" data-type="material"> <input class="J-Sub" id="radio_material_1" type="radio" name="sub_type_material" value="401" ',
        401 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="radio_material_1">地面材质</label> <input class="J-Sub" id="radio_material_2" type="radio" name="sub_type_material" value="402" ',
        402 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="radio_material_2">墙面材质</label> <input class="J-Sub" id="radio_material_3" type="radio" name="sub_type_material" value="403" ',
        403 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="radio_material_3">踢脚线</label> <input class="J-Sub" id="radio_material_4" type="radio" name="sub_type_material" value="404" ',
        404 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="radio_material_4">顶面材质</label> </dd>  <dd class="sub-type-info ',
        2 != r.inner_type && (o += "ihide"),
        o += '" data-type="door"> <input class="J-Sub" id="radio_door_1" type="radio" name="sub_type_door" value="201" ',
        201 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="radio_door_1">单开门</label> <input class="J-Sub" id="radio_door_2" type="radio" name="sub_type_door" value="202" ',
        202 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="radio_door_2">双开门</label> <input class="J-Sub" id="radio_door_3" type="radio" name="sub_type_door" value="203" ',
        203 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="radio_door_3">移门</label> <input class="J-Sub" id="radio_door_4" type="radio" name="sub_type_door" value="204" ',
        204 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="radio_door_4">门套</label> </dd>  <dd class="sub-type-info ',
        3 != r.inner_type && (o += "ihide"),
        o += '" data-type="window"> <input class="J-Sub" id="radio_window_1" type="radio" name="sub_type_window" value="301" ',
        301 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="radio_window_1">普通窗</label> <input class="J-Sub" id="radio_window_2" type="radio" name="sub_type_window" value="302" ',
        302 == r.sub_type && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="radio_window_2">飘窗</label> </dd> </dl> <dl class="form-model-list ',
        40 == r.status && (o += "form-model-disabeld"),
        o += '"> <dt><span>*</span>光源类型：</dt> <dd> <input class="J-Light" id="light_1" type="radio" name="lightable" value="0" ',
        0 == r.lightable && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="light_1">无灯光</label> <input class="J-Light" id="light_2" type="radio" name="lightable" value="1" ',
        1 == r.lightable && (o += "checked"),
        o += " ",
        40 == r.status && (o += "disabled"),
        o += '> <label for="light_2">默认光</label> </dd> </dl> <dl class="form-model-list"> <dt><span>*</span>模型尺寸：</dt> <div class="model-size"> <dd> <input class="J-Model-Width" type="text" class="form-control" placeholder="请输入模型宽度" name="trim_width" value="',
        o += n(r.trim_width),
        o += '"> <span class="input-group-addon">宽x（厘米）</span> </dd> <dd> <input class="J-Model-Length" type="text" class="form-control" placeholder="请输入模型长度" name="trim_length" value="',
        o += n(r.trim_length),
        o += '"> <span class="input-group-addon">长y（厘米）</span> </dd> <dd> <input class="J-Model-Height" type="text" class="form-control" placeholder="请输入模型高度" name="trim_height" value="',
        o += n(r.trim_height),
        o += '"> <span class="input-group-addon">高z（厘米）</span> </dd> </div> </dl> <dl class="form-model-list"> <dt><span>*</span>是否公开：</dt> <dd> <input class="J-Is-Public" id="is_public_1" type="radio" name="is_public" value="1" ',
        1 == r.is_public && (o += "checked"),
        o += '> <label for="is_public_1">是</label> <input class="J-Is-Public" id="is_public_2" type="radio" name="is_public" value="0" ',
        0 == r.is_public && (o += "checked"),
        o += '> <label for="is_public_2">否</label> </dd> </dl> <dl class="form-model-list ml10"> <dt>所属品牌：</dt> <dd><input class="product-brand J-Brand" type="text" name="product_brand" value="',
        o += n(r.product_brand),
        o += '"></dd> </dl> <dl class="form-model-list ml10"> <dt>产品货号：</dt> <dd><input class="product-no J-No" type="text" name="cargo_no" value="',
        o += n(r.cargo_no),
        o += '"></dd> </dl> <dl class="form-model-list"> <dt><span>*</span>产品单位：</dt> <dd> <select class="unit-select J-Unit" name="unit"> <option value="1" ',
        1 == r.unit && (o += "selected"),
        o += '>件</option> <option value="2" ',
        2 == r.unit && (o += "selected"),
        o += '>套</option> <option value="3" ',
        3 == r.unit && (o += "selected"),
        o += '>块</option> </select> </dd> </dl> <dl class="form-model-list"> <dt><span>*</span>是否销售商品：</dt> <dd> <input class="J-Ionsale" id="is_onsale_1" type="radio" name="salable" value="1" ',
        1 == r.salable && (o += "checked"),
        o += '> <label for="is_onsale_1">是</label> <input class="J-Ionsale" id="is_onsale_2" type="radio" name="salable" value="0" ',
        0 == r.salable && (o += "checked"),
        o += '> <label for="is_onsale_2">否</label> </dd> </dl>  <dl class="form-model-list ml10 ',
        0 == r.salable && (o += "ihide"),
        o += ' J-Show"> <dt> 商品链接： </dt> <dd> <input class="J-Link" type="text" name="product_link" placeholder="请输入商品链接" value="',
        o += n(r.product_link),
        o += '"> </dd> </dl> <dl class="form-model-list ',
        0 == r.salable && (o += "ihide"),
        o += ' J-Show"> <dt> <span>*</span>商品价格： </dt> <dd> <input class="J-Price" type="text" name="product_price" placeholder="请输入商品价格" aria-invalid="false" value="',
        o += n(r.product_price),
        o += '"> <span class="input-group-addon">元</span> </dd> </dl> <dl class="form-model-list ',
        0 == r.salable && (o += "ihide"),
        o += ' J-Show"> <dt> <span>*</span>在线价格： </dt> <dd> <input class="J-Line-Price" type="text" name="discount_price" placeholder="请输入折扣价格" aria-invalid="false" value="',
        o += n(r.discount_price),
        o += '"> <span class="input-group-addon">元</span> </dd> </dl> <div class="widget-form-next"><span class="btn btn-middle btn-orange J-Save" data-no="',
        o += n(r.no),
        o += '">保存</span></div> </form> ',
        new c(o)
    }),
    e("item/itemcategory",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$each),
        r = e.categorys,
        i = (e.value, e.$index, t.$escape),
        s = e.record,
        o = e.no,
        u = "";
        return u += '<div class="pop-model-type"> <div class="pop-model-top J-Add-type">当前分类： ',
        n(r,
        function(e) {
            u += ' <span data-id="',
            u += i(e.id),
            u += '"> ',
            u += i(e.name),
            u += ' <i class="J-Del-type">x</i></span> '
        }),
        u += ' </div> <div class="pop-model-content"> <ul class="model-ul J-first"> ',
        n(s.children,
        function(e) {
            u += ' <li data-title="',
            u += i(e.name),
            u += '" data-id="',
            u += i(e.id),
            u += '">',
            u += i(e.name),
            u += " <span> > </span></li> "
        }),
        u += ' </ul> </div> <div class="category-properties J-Category-Properties"> </div> <div class="pop-model-bottom"><span class="btn btn-middle btn-orange btn-disabled J-Add-Model" data-no="',
        u += i(o),
        u += '">添加</span> <span class="btn btn-middle btn-orange margin-left-10 J-success-Model">完成</span></div> </div> ',
        new c(u)
    }),
    e("owndesign/picture",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, e.haspic),
        r = t.$each,
        i = e.picList,
        s = (e.$value, e.$index, t.$escape),
        o = e.editdesignlink,
        u = "";
        return n ? (u += ' <div class="pic-origin-wrap J-Origin-Wrap"> <div class="J-Origin-Inner" data-id=""> <img class="J-Origin-Img" src="/static/iyun/images/new/pyun/test_6.jpg" alt=""/> </div> <span class="prev-wrap " href="javascript:void(0)"><i class="prev icon share-arrow-left"></i></span> <span class="next-wrap " href="javascript:void(0)"><i class="next icon share-arrow-right"></i></span> </div> <div class="pic-view J-Pic-View"> <div class="container-fluid"> <div class="picScroll-left"> <div class="hd"> <a class="prev btn-wrap" href="javascript:void(0)"><i class="icon share-arrow-left"></i></a> <a class="next btn-wrap" href="javascript:void(0)"><i class="icon share-arrow-right"></i></a> </div> <div class="bd"> <ul class="J-Thumbnail-List"> ', r(i,
        function(e, t) {
            u += ' <li data-no="',
            u += s(t),
            u += '" data-origin="',
            u += s(e.origin),
            u += '" data-url="',
            u += s(e.url),
            u += '"><a href="javascript:;"><img onerror="nofindSmall()" _src="',
            u += s(e.thumbnail),
            u += '" src=""></a></li> '
        }), u += " </ul> </div> </div> </div> </div> ") : (u += ' <div class="pic-origin-wrap empty"> <div> <div class="empty-link"> <p>您目前还没有效果图，点击 <a target="_blank" href="', u += s(o), u += '">去渲染</a>。</p> <p>不会渲染？ <a target="_blank" href="http://www.fuwo.com/topic/94/">观看教程</a>。</p> </div> </div> </div> <div class="pic-view empty"></div> '),
        new c(u)
    }),
    e("owndesign/picture_view",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, e.haspic),
        r = t.$each,
        i = e.picList,
        s = (e.$value, e.$index, t.$escape),
        o = e.editdesignlink,
        u = "";
        return n ? (u += ' <div class="pic-origin-wrap J-Origin-Wrap"> <div class="J-Origin-Inner" data-id=""> <a class="pic-origin-inner" href="" target="_blank" ><i class="icon share-look-origin"></i><img class="J-Origin-Img" src="/static/iyun/images/new/pyun/test_6.jpg" alt=""/></a> </div> <span class="prev-wrap" href="javascript:void(0)"><i class="prev icon share-arrow-left"></i></span> <span class="next-wrap" href="javascript:void(0)"><i class="next icon share-arrow-right"></i></span> </div> <div class="pic-view J-Pic-View"> <div class="container-fluid"> <div class="picScroll-left"> <div class="hd"> <a class="prev btn-wrap" href="javascript:void(0)"><i class="icon share-arrow-left"></i></a> <a class="next btn-wrap" href="javascript:void(0)"><i class="icon share-arrow-right"></i></a> </div> <div class="bd"> <ul class="J-Thumbnail-List"> ', r(i,
        function(e, t) {
            u += ' <li data-no="',
            u += s(t),
            u += '" data-origin="',
            u += s(e.origin),
            u += '" data-url="',
            u += s(e.url),
            u += '"><a href="javascript:;"><img onerror="nofindSmall()" _src="',
            u += s(e.thumbnail),
            u += '" src=""></a></li> '
        }), u += " </ul> </div> </div> </div> </div> ") : (u += ' <div class="pic-origin-wrap empty"> <div> <div class="empty-link"> <p>您目前还没有效果图，点击 <a target="_blank" href="', u += s(o), u += '">去渲染</a>。</p> <p>不会渲染？ <a target="_blank" href="http://www.fuwo.com/topic/94/">观看教程</a>。</p> </div> </div> </div> <div class="pic-view empty"></div> '),
        new c(u)
    }),
    e("owndesign/popup", '<dl class="content"> <dt class="float-left"> <img src="../../../../static/iyun/images/new/common/huxing.png"> <a class="btn download">立即下载</a> <a class="preview">生成预览</a> </dt> <dd> <div class="name"><label>户型名称：</label>&nbsp;&nbsp;上海市龙柏五村</div> <div class="download-type"> <label>下载类型：</label> <input type="radio" class="default js-default" name="type" value="default" checked><em>默认风格</em> <input type="radio" class="cad js-cad" name="type" value="cad"><em>CAD格式文件</em> <ul> <li> <label>单位：</label> <input type="radio" class="" name="unit" value="" checked><em>厘米</em> <input type="radio" class="" name="unit" value=""><em>米</em> </li> <li> <label>墙标：</label> <input type="radio" class="" name="wall" value="" checked><em>墙中线</em> <input type="radio" class="" name="wall" value=""><em>内墙</em> </li> <li> <label>标尺：</label> <input type="radio" class="" name="staff " value="" checked><em>默认风格</em> <input type="radio" class="" name="staff " value=""><em>CAD风格</em> <input type="radio" class="" name="staff " value=""><em>无</em> </li> <li> <label>房间：</label> <input type="checkbox" class="" name="room" value="" checked><em>显示房间名称</em> <input type="checkbox" class="" name="room" value=""><em>显示家具</em> </li> <li> <label>面积：</label> <input type="radio" class="" name="area" value="" checked><em>显示净面积</em> <input type="radio" class="" name="area" value=""><em>显示套内面积</em> </li> <li> <label>备注：</label> <input type="radio" class="" name="mark" value="" checked><em>显示备注</em> <input type="radio" class="" name="mark" value=""><em>隐藏备注</em> </li> <li> <label>尺寸：</label> <select> <option>精美图</option> <option>A4横屏</option> <option>A4竖屏</option> <option>A3横屏</option> <option>A3竖屏</option> <option>自定义</option> </select> <input type="text" value="1123" class="width">&nbsp;&nbsp;高&nbsp;&nbsp;x&nbsp;&nbsp;<input type="text" value="794" class="height">&nbsp;&nbsp;宽 </li> </ul> </div> </dd> </dl>'),
    e("sign/register", '<div class="tab-panel"> <div class="tab-panel-left"> <form action="" id="signup-form" novalidate="novalidate"> <div class="form"> <div class="inputline"> <p class="input"><i class="ico ico_user"></i><input autocomplete="off" name="user" type="text" class="control" placeholder="请输入常用的手机号/邮箱" id="J_in_zh"></p> </div> <div class="inputline"> <p class="input"><input autocomplete="off" name="password" type="password" class="control" id="J_in_pwd" placeholder="请输入6-20字符的密码"><i class="ico ico_pwd"></i></p> </div> <div class="inputline"> <p class="input"> <input autocomplete="off" name="authCode" type="password" class="control" id="J_code" placeholder="请输入验证码"> <i class="ico ico_shild"></i> <a href="javascript:;" class="get_ma" id="J_check">获取验证码</a> </p> </div> <div class="inputline ovh padding-top-10"> <p class="rem-wrap"> <label class="rem"><input type="checkbox" class="rem_i" checked="">我已阅读并同意<a href="">《爱福窝用户注册协议》</a></label> </p> </div> <div class="btn-box"> <input type="button" class="btn btn-success" value="注册" id="J_submit"> </div> </div> </form> </div> <div class="line-exp"> <ul> <li> <div class="line"> </div> </li> <li class="exp"><span>或</span></li> <li> <div class="line"> </div> </li> </ul> </div> <div class="tab-panel-right"> <div class="other-login"> <div class="three"> <div class="wx"> <a href="javascript:;"> <i class="ico ico_wx"></i>微信登录 </a> </div> <div class="qq"> <a href="javascript:;"> <i class="ico ico_qq"></i>QQ登录 </a> </div> <div class="wb"> <a href="javascript:;"> <i class="ico ico_wb"></i>微博登录 </a> </div> </div> <span> <p class="float-left">已有账号？</p> <a class="float-right" href="">直接登录</a> </span> </div> </div> </div> '),
    e("sign/signin", '<div class="tab-panel"> <div class="tab-panel-left"> <h3>微信登录</h3> <div class="qrcode"> <p class="qr_img"> <img src="/static/images/new/pfuwo/img_code.jpg" id="J_img_code"> <img style="display: none" src="/static/images/new/pfuwo/img_code_h.jpg" id="J_img_codeh"> </p> <div class="text hide"> <p class="til">微信扫码，快速登录</p> <a href="javascript:;" id="J_help" class="help">使用帮助</a> </div> </div> </div> <div class="tab-panel-right"> <form action="" id="login-form" novalidate="novalidate"> <div class="form"> <div class="inputline"> <p class="input"><input type="text" class="control" placeholder="请输入常用的手机号/邮箱" id="J_in_zh"><i class="ico ico_user"></i></p> </div> <div class="inputline"> <p class="input"><input type="password" class="control" placeholder="请输入6-20字符的密码" id="J_in_pwd"><i class="ico ico_pwd"></i></p> </div> <div class="inputline ovh"> <p class="float-left"><label class="rem"><input type="checkbox" class="rem_i" checked id="J_in_rem">记住我</label></p> <a href="" class="float-right">忘记密码？</a> </div> <div class="btn-box"> <input type="button" class="btn btn-success" value="登录" id="J_submit"> </div> </div> </form> <div class="three text-center f12"> <a href="javascript:;" class="float-left"><i class="ico ico_qq"></i>QQ登录</a> <a href="javascript:;" class=""><i class="ico ico_wb"></i>微博登录</a> <a href="/html/pfuwo/signup" class="float-right">免费注册</a> </div> </div> </div> '),
    e("tuce/tuce",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$escape),
        r = e.imgUrl,
        i = e.currentType,
        s = e.currentName,
        o = t.$each,
        u = e.type,
        a = (e.value, e.$index, e.casePhoto),
        f = e.caseCount,
        l = "";
        return l += '<div class="case-top"> <img src="',
        l += n(r),
        l += '"> <div class="case-right"> <label class="case-select-name" id="case_name" data-name="',
        l += n(i),
        l += '"><span>',
        l += n(s),
        l += '</span><i class="icon icon-triangle-down"></i> <div class="case-select"> <ul class="case-name"> ',
        o(u,
        function(e) {
            l += ' <li data-name="',
            l += n(e.listType),
            l += '">',
            l += n(e.listName),
            l += "</li> "
        }),
        l += ' </ul> <div class="case-create"> <input type="text" placeHolder="创建新图册"> <span class="case-create-btn js-create">创建</span> </div> </div> </label> ',
        a && (l += ' <ul class="case-radio"> <li><input type="radio" name="case" id="case_radio_1" checked> <label for="case_radio_1">收藏单张图片</label></li> <li><input type="radio" name="case" id="case_radio_2"> <label for="case_radio_2">收藏整套图片(共', l += n(f), l += "张)</label></li> </ul> "),
        l += ' </div> </div> <div class="case-bottom"> <span class="case-btn js-collect-save">收藏</span> </div>',
        new c(l)
    }),
    e("ueditorext/senddesign",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, e.records),
        r = t.$each,
        i = (e.value, e.$index, t.$escape),
        s = "";
        return s += '<div class="dialog-bd"> <div class="dialog-items-wrap"> ',
        n.length > 0 ? (s += " ", r(n,
        function(e) {
            s += ' <div class="dialog-bd-li"> <a href="http://3d.fuwo.com/design/',
            s += i(e.no),
            s += '/" target="_blank"><img src="',
            s += i(e.photo_url),
            s += '" alt="',
            s += i(e.name),
            s += '" width="205" height="205"/></a><br> <label> ',
            e.houselayout && (s += i(e.houselayout.area), s += "m²"),
            s += " ",
            s += i(e.style),
            s += i(e.name),
            s += "</label> <br> ",
            e.houselayout && (s += i(e.houselayout.city_name), s += i(e.houselayout.community_name)),
            s += '<br> <span class="btn btn-orange btn-middle J-Choice-Design" data-id="',
            s += i(e.id),
            s += '" data-no="',
            s += i(e.no),
            s += '">使用该方案</span> </div> '
        }), s += " ") : s += ' <div class="content-none"> 您还没有设计数据！ <span class="btn btn-middle btn-orange"><a href="http://3d.fuwo.com/myhome3d" target="_blank">新建设计</a></span> </div> ',
        s += ' <input type="hidden" id="json_name"> <input type="hidden" id="pop_title"> </div> <div class="pages-wrap"> <div id="Pagination_dialog" class="Pagination_dialog"></div> </div> </div>',
        new c(s)
    }),
    e("ueditorext/sendhouselayout",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, e.records),
        r = t.$each,
        i = (e.value, e.$index, t.$escape),
        s = "";
        return s += '<div class="dialog-bd"> <div class="dialog-items-wrap"> ',
        n.length > 0 ? (s += " ", r(n,
        function(e) {
            s += ' <div class="dialog-bd-li"> <a href="http://3d.fuwo.com/houselayout/',
            s += i(e.no),
            s += '/" target="_blank"><img src="',
            s += i(e.photo_url),
            s += '" alt="',
            s += i(e.name),
            s += i(e.community_name),
            s += '" width="205" height="205"/></a> <label>',
            s += i(e.city_name),
            s += " ",
            s += i(e.community_name),
            s += "</label> ",
            s += i(e.house_type_name),
            s += i(e.area),
            s += '㎡<br> <span class="btn btn-orange btn-middle J-Choice-Houselayout" data-id="',
            s += i(e.id),
            s += '" data-no="',
            s += i(e.no),
            s += '">使用该户型</span> </div> '
        }), s += " ") : s += ' <div class="content-none"> 您还没有户型图数据！ <span class="btn btn-middle btn-orange"><a href="http://3d.fuwo.com/myhome3d" target="_blank">新建户型</a></span> </div> ',
        s += ' <input type="hidden" id="json_name"> <input type="hidden" id="pop_title"> </div> <div class="pages-wrap"> <div id="Pagination_dialog" class="Pagination_dialog"></div> </div> </div>',
        new c(s)
    }),
    e("ueditorext/sendinfo", '<div class="dialog-bd"> <div class="line-1"> <label class="text-gray" for="">地区</label> <input class="info-item location js-house-loaction" placeholder="所在城市" /> </div> <div class="line-1"> <label class="text-gray" for="">小区</label> <input class="info-item js-house-area" type="text" placeholder="请填写你的小区名"/> </div> <div class="line-1"> <label class="text-gray" for="">户型</label> <select class="info-item select js-house-type" > <option value="一室一厅">一室一厅</option> <option value="两室一厅">两室一厅</option> <option value="两室两厅">两室两厅</option> <option value="一室一厅">三室一厅</option> <option value="一室一厅">三室两厅</option> <option value="一室一厅">四室两厅</option> <option value="一室一厅">五室两厅</option> <option value="一室一厅">LOFT</option> <option value="一室一厅">复式</option> </select> </div> <div class="line-1"> <label class="text-gray" for="">面积</label> <input class="info-item js-house-size" type="text" placeholder="请填写你的房屋面积" maxlength="6"/> m² </div> <div class="btn-wrap"> <a class="btn btn-success btn-confirm btn-large js-house-info-btn" href="javascript:void(0);"> 确 定 </a> </div> </div> '),
    e("ueditorext/sendinsert",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$escape),
        r = e.no,
        i = e.design_img_src,
        s = e.design_house_name,
        o = e.design_house_area,
        u = e.design_house_location,
        a = e.design_house_style,
        f = e.design_house_type,
        l = e.design_house_size,
        h = "";
        return h += ' <div class="text-gray"> <p> <a target="_blank" href="http://3d.fuwo.com/myhome3d/?openCommand=OPEN_EDIT_DESIGN:',
        h += n(r),
        h += '"> <img class="topic-design-img" style="float:left" src="',
        h += n(i),
        h += '" alt="test" title="',
        h += n(s),
        h += '" width="154" height="154"/> </a> </p> <p class="topic-design-item" style="padding-left:174px"> <a target="_blank" href="http://3d.fuwo.com/myhome3d/?openCommand=OPEN_EDIT_DESIGN:',
        h += n(r),
        h += '" class="f16 fB"> ',
        h += n(s),
        h += '</a> </p> <p class="topic-design-item" style="padding-left:174px">小区名： <strong class="fB">',
        h += n(o),
        h += '</strong></p> <p class="topic-design-item" style="padding-left:174px">地区：<strong class="fB">',
        h += n(u),
        h += '</strong></p> <p class="topic-design-item" style="padding-left:174px">风格：<strong class="fB">',
        h += n(a),
        h += '</strong> </p> <p class="topic-design-item" style="padding-left:174px">户型：<strong class="fB">',
        h += n(f),
        h += '</strong> </p> <p class="topic-design-item" style="padding-left:174px">面积：<strong class="fB">',
        h += n(l),
        h += " m²</strong> </p> </div>",
        new c(h)
    }),
    e("zxtc/appermSuc", '<div class="appm-info"> <span class="zxtcshare-icon-confirm"></span> <p>恭喜您预约成功！家装顾问会尽快与您联系。</p> <div class="tip-more"> <dl> <dt>福窝让装修更简单 <dd> <p>1.海量装修效果图，给您提供最好的装修参考。</p> <a href="http://meitu.fuwo.com" class="tip-view">我要看看</a> </dd> <dd> <p>2.爱福窝装修设计软件，自己动手布置温馨的家。</p> <a href="http://3d.fuwo.com" class="tip-physical">立即体验</a> </dd> </dt> </dl> </div> </div>'),
    e("zxxq/baoming_popup",
    function(e) {
        "use strict";
        var t = this,
        n = (t.$helpers, t.$escape),
        r = e.dayCount,
        i = e.monthCount,
        s = "";
        return s += '<div class="pop-order-info"><b>免费设计</b> <div class="pop-order-count">今日报名 <span> ',
        s += n(r),
        s += " </span>位 30天报名 <span> ",
        s += n(i),
        s += ' </span>位</div> <form id="order_form"> <div class="input-line"> <input class="J-Name" type="text" name="username" placeholder="您的称呼" maxlength="32" > </div> <div class="input-line"> <input class="J-Tel" type="text" name="tel" placeholder="您的电话" > </div> <div class="row-list form-group input-line"> <select id="selProvince" class="J-selProvince place-city" name="city" placeholder="省市"><option value="">省/市</option></select> <select id="selCity" class="J-selCity place-city margin-right-0"><option value="">市/地区</option></select> </div> <em class="J-Order-View submit">立即申请</em> <div class="pop-order-desc">我们承诺：爱福窝提供该项<span>免费服务，绝不产生任何费用</span>，为了您的利益以及我们的口碑，您的隐私将被严格保密。</div> <div class="input-error"></div> </form></div>',
        new c(s)
    })
} ();