var dx = 0;
var dh = 0;
function openthewin(title,url,w,h){
    closethewin();
    var html = '';
    var winw = $(window).width();
    var winh = $(window).height();
    var scll = $(window).scrollTop();
    var mtop = (winh - h - 2)*(3/7) + scll;
    var mleft = (winw - w - 2)/2;
    var move_w = w - 10 - 50;
    html += '<div id="openwindow" style="width: '+w+'px;height:'+h+'px;overflow:hidden;border:1px solid #ccc;background:#fff;left:'+mleft+'px;top:'+mtop+'px;position: absolute;z-index: 500000;">';
    html += '<div style="height:29px;overflow:hidden;border-bottom:1px solid #ccc;background:#f3f3f3;padding: 0 10px;">'
    html += '<div id="moveban" style="width:'+move_w+'px;font: bold 14px/30px 微软雅黑;overflow:hidden;float:left;cursor:move;">'+title+'</div>';
    html += '<div style="font: bold 12px/30px 微软雅黑;overflow:hidden;float:right;cursor:pointer;" onclick="closethewin();">关闭</div>';
    html += '</div>';
    var frameh = h - 30;
    html += '<div style="height:'+frameh+'px;overflow:hidden;background:#fff;">';
    html += '<iframe id="openwindowmain" style="width: '+w+'px;height:'+frameh+'px;" name="openwindowmain" frameborder="0" src="'+url+'" scrolling="auto" />';
    html += '</div>';
    html += '</div>';
    $("body").prepend(html);
    $("#moveban").bind("mousedown",function(e){
        var pointX = e.pageX;
        var pointY = e.pageY;
        var pos = $("#openwindow").position();
        var winX = pos.left;
        var winY = pos.top;
        dx =  pointX - winX;
        dh =  pointY - winY;

        $("#openwindow").bind("mouseover", function(e){
            var pointX = e.pageX;
            var pointY = e.pageY;
            $mx = pointX - dx;
            $my = pointY - dh;
            $("#openwindow").css("left",$mx);
            $("#openwindow").css("top",$my);
        });
        $("#openwindow").bind("mouseout", function(e){
            var pointX = e.pageX;
            var pointY = e.pageY;
            $mx = pointX - dx;
            $my = pointY - dh;
            $("#openwindow").css("left",$mx);
            $("#openwindow").css("top",$my);
        });
    });
    $("#moveban").bind("mouseup",function(e){
        $("#openwindow").unbind("mouseover");
        $("#openwindow").unbind("mouseout");
    });
}

function closethewin(){
    $("#moveban").unbind("mousedown");
    $("#moveban").unbind("mouseup");
    $("#openwindow").remove();
}
jQuery.fn.extend({
    uploader: function(type, script) {
        $(this).click(function() {
            if (type == "image") {
                openthewin("单图片上传",upload_root + "?s=/uploader/main/index&tp=image&num=1&jsname="+script,360,250);
            } else if (type == "audio") {
                openthewin("单音频上传",upload_root + "?s=/uploader/main/index&tp=audio&num=1&jsname="+script,360,250);
            } else if (type == "obj") {
                openthewin("单obj文件上传",upload_root + "?s=/uploader/main/index&tp=obj&num=1&jsname="+script,360,250);
            } else if (type == "mtl") {
                openthewin("单mtl文件上传",upload_root + "?s=/uploader/main/index&tp=mtl&num=1&jsname="+script,360,250);
            }else if (type == "all") {
                openthewin("单文件上传",upload_root + "?s=/uploader/main/index&tp=all&num=1&jsname="+script,360,250);
            }else if (type == "video") {
                openthewin("单视频上传",upload_root + "?s=/uploader/main/index&tp=video&num=1&jsname="+script,360,250);
            } else if (type == "applevideo") {
                openthewin("单mp4上传",upload_root + "?s=/uploader/main/index&tp=applevideo&num=1&jsname="+script,360,250);
            }else if (type == "images") {
                openthewin("多图片上传",upload_root + "?s=/uploader/main/index&tp=image&num=10&jsname="+script,610,400);
            }else if (type == "images2") {
                openthewin("多图片上传",upload_root + "?s=/uploader/main/index&tp=image&namecode=self&num=10&jsname="+script,590,400);
            }
        });
    },
    addimgbox: function(data, name) {
        var datas = data.split("|");
        for (i = 0; i < datas.length; i++) {
            var html = '';
            html += '<div class="photo_outer">';
            html += '<div class="photo_img"><img src="' + upload_root + datas[i] + '" onload="photoin(this,120,120)" /></div>';
            html += '<input type="hidden" name="' + name + '[]" value="' + datas[i] + '" />';
            html += '<div class="photo_btn">';
            html += '<input class="upload_button" type="button" value="删除" onclick="$(this).delimgbox()" />';
            html += '<input class="upload_button" type="button" style="margin-top:4px;" onclick="$(this).upimgbox()"  value="上移" />';
            html += '<input class="upload_button" type="button" style="margin-top:4px;" onclick="$(this).downimgbox()" value="下移" />';
            html += '</div>';
            html += '</div>';
            $(this).append(html);
        }
    },
    editimgbox: function(data,name) {
        if(data != ""){
            var datas = data.split("|");
            for (i = 0; i < datas.length; i++) {
                var html = '';
                html += '<div class="photo_outer">';
                html += '<div class="photo_img"><img src="' + upload_root + datas[i] + '" onload="photoin(this,120,120)" /></div>';
                html += '<input type="hidden" name="' + name + '[]" value="' + datas[i] + '" />';
                html += '<div class="photo_btn">';
                html += '<input class="upload_button" type="button" onclick="$(this).delimgbox2(' + "'delimg'" + ')" value="删除" />';
                html += '<input class="upload_button" type="button" style="margin-top:4px;" onclick="$(this).upimgbox()" value="上移" />';
                html += '<input class="upload_button" type="button" style="margin-top:4px;" onclick="$(this).downimgbox()" value="下移" />';
                html += '</div>';
                html += '</div>';
                $(this).append(html);
            }
        }
    },
    delimgbox: function() {
        var m = $(this).parent(".photo_btn").parent(".photo_outer");
        m.remove();
    },
    delimgbox2: function(inputname) {
        var m = $(this).parent(".photo_btn").parent(".photo_outer");
        var v = m.children("input").val();
        var nowv = $("#" + inputname).val();
        if (nowv == "") {
            $("#" + inputname).val(v);
        } else {
            $("#" + inputname).val(nowv + "|" + v);
        }
        m.remove();
    },
    upimgbox: function() {
        var m = $(this).parent(".photo_btn").parent(".photo_outer");
        var n = m.prevAll().length;
        if (n > 0) {
            var temp1 = m.html();
            var temp2 = m.prev().html();
            m.prev().html(temp1);
            m.html(temp2);
        }
    },
    downimgbox: function() {
        var m = $(this).parent(".photo_btn").parent(".photo_outer");
        var n = m.nextAll().length;
        if (n > 0) {
            var temp1 = m.html();
            var temp2 = m.next().html();
            m.next().html(temp1);
            m.html(temp2);
        }
    },
    addoneimgbox: function(data, name) {
        var datas = data.split("|");
        for (i = 0; i < datas.length; i++) {
            var html = '';
            html += '<div class="photo_outer one">';
            html += '<div class="photo_img"><img src="' + datas[i] + '" onload="photoin(this,120,120)" /></div>';
            html += '<input type="hidden" name="' + name + '[]" value="' + datas[i] + '" />';
            html += '</div>';
            $(this).append(html);
        }
    }
});

// JavaScript Document
function photoin(myphoto,W,H){
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
function photoout(myphoto,W,H){
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
