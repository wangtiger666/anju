//确认跳转链接
function DoNote(text,gourl) {
    if(!window.confirm(text)){
        return false;
    }
    location.href=gourl;
}
function LinkTo(gourl){
    location.href=gourl;
}
function LinkToOpen(gourl){
	window.open(gourl);
}
$(function(){
    $(".delme").click(function(){
        var url = $(this).data("url");
        var info = $(this).data("info");
        DoNote(info,url);
    });
});

function AutoCheckSub(tp){
    var temp = $(tp);
    var len = temp.length;
    var data = new Array();
    for(i=0;i<len;i++){
        data[i] = new Array();
        data[i]['val'] = temp.eq(i).val();
        data[i]['tname'] = temp.eq(i).attr("sbname");
        data[i]['tmsg'] = temp.eq(i).attr("sbmsg");
        data[i]['treg'] = temp.eq(i).attr("sbreg");

        if(data[i]['val'] == ""){
            showMsg("【"+data[i]['tname']+"】 不能为空！",2);
            temp.eq(i).focus();
            return false;
        }else{
            if(data[i]['treg']){
                var reg = eval(data[i]['treg']);
                if(!reg.test(data[i]['val'])){
                    showMsg(data[i]['tmsg'],2);
                    temp.eq(i).focus();
                    return false;
                }
            }
        }
    }
    return true;
}

//弹出错误
function showMsg(txt,type){
    $(".msgbox").remove();
    $("body").stopTime("msg");
    var winw = $(window).width();
    var winh = $(window).height();
    var x = (winw - 410) / 2;
    var y = (winh - 70) * 3 / 7;
    if(y<0){
        y = 0;
    }
    var html = "";
    html += '<div class="msgbox" style="left:'+x+'px;top:'+y+'px">';
    html += '<div class="main">';
    html += '<div class="msgform">'
    html += '<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0">';
    if(type == 1){
        html += '<tr><td width="55" height="60" align="right"><div class="good"></div></td><td align="left" style="padding: 0 10px;"><span class="green">'+txt+'</span></td></tr>';
    }else if(type == 2){
        html += '<tr><td width="55" height="60" align="right"><div class="tan"></div></td><td align="left" style="padding: 0 10px;"><span class="red">'+txt+'</span></td></tr>';
    }else{
        html += '<tr><td width="55" height="60" align="right"><div class="bad"></div></td><td align="left" style="padding: 0 10px;"><span class="red">'+txt+'</span></td></tr>';
    }

    html += '</table>';
    html += '</div>';
    html += '</div>';
    html += '</div>';
    $("body").prepend(html);
    $("body").oneTime("15ds", "msg", function(){
        $(".msgbox").fadeTo(800, 0, function(){
            $(".msgbox").remove();
        });
    });
}


//计时器
jQuery.fn.extend({
    everyTime: function(interval, label, fn, times, belay) {
        return this.each(function() {
            jQuery.timer.add(this, interval, label, fn, times, belay);
        });
    },
    oneTime: function(interval, label, fn) {
        return this.each(function() {
            jQuery.timer.add(this, interval, label, fn, 1);
        });
    },
    stopTime: function(label, fn) {
        return this.each(function() {
            jQuery.timer.remove(this, label, fn);
        });
    }
});

jQuery.extend({
    timer: {
        guid: 1,
        global: {},
        regex: /^([0-9]+)\s*(.*s)?$/,
        powers: {
            // Yeah this is major overkill...
            'ms': 1,
            'cs': 10,
            'ds': 100,
            's': 1000,
            'das': 10000,
            'hs': 100000,
            'ks': 1000000
        },
        timeParse: function(value) {
            if (value == undefined || value == null)
                return null;
            var result = this.regex.exec(jQuery.trim(value.toString()));
            if (result[2]) {
                var num = parseInt(result[1], 10);
                var mult = this.powers[result[2]] || 1;
                return num * mult;
            } else {
                return value;
            }
        },
        add: function(element, interval, label, fn, times, belay) {
            var counter = 0;

            if (jQuery.isFunction(label)) {
                if (!times)
                    times = fn;
                fn = label;
                label = interval;
            }

            interval = jQuery.timer.timeParse(interval);

            if (typeof interval != 'number' || isNaN(interval) || interval <= 0)
                return;

            if (times && times.constructor != Number) {
                belay = !!times;
                times = 0;
            }

            times = times || 0;
            belay = belay || false;

            if (!element.$timers)
                element.$timers = {};

            if (!element.$timers[label])
                element.$timers[label] = {};

            fn.$timerID = fn.$timerID || this.guid++;

            var handler = function() {
                if (belay && this.inProgress)
                    return;
                this.inProgress = true;
                if ((++counter > times && times !== 0) || fn.call(element, counter) === false)
                    jQuery.timer.remove(element, label, fn);
                this.inProgress = false;
            };

            handler.$timerID = fn.$timerID;

            if (!element.$timers[label][fn.$timerID])
                element.$timers[label][fn.$timerID] = window.setInterval(handler,interval);

            if ( !this.global[label] )
                this.global[label] = [];
            this.global[label].push( element );

        },
        remove: function(element, label, fn) {
            var timers = element.$timers, ret;

            if ( timers ) {

                if (!label) {
                    for ( label in timers )
                        this.remove(element, label, fn);
                } else if ( timers[label] ) {
                    if ( fn ) {
                        if ( fn.$timerID ) {
                            window.clearInterval(timers[label][fn.$timerID]);
                            delete timers[label][fn.$timerID];
                        }
                    } else {
                        for ( var fn in timers[label] ) {
                            window.clearInterval(timers[label][fn]);
                            delete timers[label][fn];
                        }
                    }

                    for ( ret in timers[label] ) break;
                    if ( !ret ) {
                        ret = null;
                        delete timers[label];
                    }
                }

                for ( ret in timers ) break;
                if ( !ret )
                    element.$timers = null;
            }
        }
    }
});

function onoroff() {
    var len = $(".onoff").length;
    for (i = 0; i < len; i++) {
        if ($(".onoff").eq(i).attr("value") == 0) {
            $(".onoff").eq(i).addClass("off");
        }
    }
    $(".onoff").click(function() {
        var ob = $(this).attr("target");
        if ($(this).attr("value") == 1) {
            $(this).addClass("off");
            $(this).attr("value", 0);
            $("#" + ob).val(0);
        } else {
            $(this).removeClass("off");
            $(this).attr("value", 1);
            $("#" + ob).val(1);
        }
    });
}

function swichimg2w(me,ow){
    var theImage = new Image();
    var src = $(me).attr("src");

    theImage.src = src;
    var w = theImage.width;
    var h = theImage.height;

    if(w ==0 || h ==0){
        $(me).css("width","auto");
        $(me).css("height","auto");
        w = $(me).width();
        h = $(me).height();
    }

    if(w>ow){
        $(me).width(ow);
        x= ow*h/w;
        $(me).height(x);
        $(me).parent("div").width(ow);
        $(me).parent("div").height(x);
    }else{
        $(me).width(w);
        $(me).height(h);
        $(me).parent("div").width(w);
        $(me).parent("div").height(h);
    }
}

function swichimg2h(me,oh){
    var theImage = new Image();
    theImage.src = $(me).attr("src");

    var w = theImage.width;
    var h = theImage.height;

    if(w ==0 || h ==0){
        $(me).css("width","auto");
        $(me).css("height","auto");
        w = $(me).width();
        h = $(me).height();
    }

    if(h>oh){
        $(me).height(oh);
        x= oh*w/h;
        $(me).width(x);
        $(me).parent("div").width(x);
        $(me).parent("div").height(oh);
    }else{
        $(me).parent("div").width(w);
        $(me).parent("div").height(h);
    }
}

//图片定位
function photocenterin(myphoto,W,H){
    $(myphoto).removeAttr("width");
    $(myphoto).removeAttr("height");
    var px = myphoto.width;
    var py = myphoto.height;
    var nx;
    var ny;
    if(px / py > W / H){
        if(px>W){
            nx=W;
            ny=nx*(py/px);
        }else{
            nx = px;
            ny = py;
        }
    }else{
        if(py > H){
            ny=H;
            nx=ny*(px/py);
        }else{
            nx = px;
            ny = py;
        }
    }
    myphoto.width = nx;
    myphoto.height = ny;
    $(myphoto).css("marginLeft",(W-nx)/2);
    $(myphoto).css("marginTop",(H-ny)/2);
}
function photocenterout(myphoto,W,H){
    $(myphoto).removeAttr("width");
    $(myphoto).removeAttr("height");
    var px = myphoto.width;
    var py = myphoto.height;
    var nx;
    var ny;
    if(px / py > W / H){
        ny=H;
        nx=ny*(px/py);
    }else{
        nx=W;
        ny=nx*(py/px);
    }
    myphoto.width = nx;
    myphoto.height = ny;
    $(myphoto).css("marginLeft",(W-nx)/2);
    $(myphoto).css("marginTop",(H-ny)/2);
}

function openwin(title,url,ow,oh){
    closewin();

    var html = '';
    var winw = $(window).width();
    var w = winw - 100;
    if(w>ow){
        w= ow;
    }
    var winh = $(window).height();
    var h = winh - 60;
    if(h >oh){
        h = oh;
    }

    var scll = $(window).scrollTop();
    var mtop = (winh - h - 2)*(3/7) + scll;
    var mleft = (winw - w - 2)/2;
    html += '<div id="flywindow" style="width: '+w+'px;height:'+h+'px;overflow:hidden;border:1px solid #ccc;background:#fff;left:'+mleft+'px;top:'+mtop+'px;position: absolute;z-index:100000;">';
    html += '<div style="height:29px;overflow:hidden;border-bottom:1px solid #ccc;background:#f3f3f3; padding: 0 10px;">'
    html += '<div style="font: bold 14px/30px 微软雅黑;overflow:hidden;float:left;cursor:default;">'+title+'</div>';
    html += '<div style="font: bold 12px/30px 微软雅黑;overflow:hidden;float:right;cursor:pointer;" onclick="closewin();">关闭</div>';
    html += '</div>';
    var frameh = h - 30;
    html += '<div style="height:'+frameh+'px;overflow:hidden;background:#fff;">';
    html += '<iframe id="openwindowmain" style="width: '+w+'px;height:'+frameh+'px;" name="openwindowmain" frameborder="0" src="'+url+'" scrolling="auto" />';
    html += '</div>';
    html += '</div>';
    $("body").prepend(html);
}

function closewin(){
    $("#flywindow").remove();
}


function EditDbFomInput(tempid,dir,table,where,key){
    var v = $(tempid).val();
    if(v == ""){
        var w = $(tempid).prev("input").val();
        if(w != ""){
            v = w;
        }
    }
    $.ajax({
        url:dir,
        type:"POST",
        dataType:"JSON",
        data:{
            dopost: "editdb",
            db: table,
            where: where,
            dbkey: key,
            dbdata:v
        },
        success:function(data){
            showMsg(data.txt,data.tp);
        }
    });
}

function EditDbData(v,dir,table,where,key){
    $.ajax({
        url:dir,
        type:"POST",
        dataType:"JSON",
        data:{
            dopost: "editdb",
            db: table,
            where: where,
            dbkey: key,
            dbdata:v
        },
        success:function(data){
            showMsg(data.txt,data.tp);
        }
    });
}